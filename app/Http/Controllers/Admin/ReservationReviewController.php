<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentSlip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReservationReviewController extends Controller
{
    /**
     * Display list of reservations for review
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $reservations = Booking::with(['facility', 'user'])
                              ->when($status !== 'all', function ($query) use ($status) {
                                  return $query->where('status', $status);
                              })
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);

        $statusCounts = [
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'all' => Booking::count()
        ];

        return view('admin.reservations.index', compact('reservations', 'statusCounts', 'status'));
    }

    /**
     * Display detailed view of reservation for review
     */
    public function show($id)
    {
        $reservation = Booking::with(['facility', 'user'])->findOrFail($id);
        
        // Get uploaded files information
        $uploadedFiles = $this->getUploadedFilesInfo($reservation);
        
        return view('admin.reservations.review', compact('reservation', 'uploadedFiles'));
    }

    /**
     * Approve a reservation
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $reservation = Booking::findOrFail($id);
        
        $reservation->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes
        ]);

        // Generate Payment Slip
        $paymentSlip = $this->generatePaymentSlip($reservation);

        // Send notification to citizen (you can implement email notifications here)
        $this->sendApprovalNotification($reservation, $paymentSlip);

        // Check if it's an AJAX request or regular form submission
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Reservation approved and payment slip generated successfully!',
                'payment_slip' => [
                    'slip_number' => $paymentSlip->slip_number,
                    'amount' => $paymentSlip->amount,
                    'due_date' => $paymentSlip->due_date->format('Y-m-d')
                ]
            ]);
        } else {
            // Regular form submission - redirect with success message
            return redirect()->route('admin.reservations.index')
                           ->with('success', 'Reservation approved successfully! Payment slip generated.');
        }
    }

    /**
     * Reject a reservation
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:1000'
        ]);

        $reservation = Booking::findOrFail($id);
        
        $reservation->update([
            'status' => 'rejected',
            'rejected_reason' => $request->rejected_reason,
            'admin_notes' => $request->admin_notes ?? null
        ]);

        // Send rejection notification to citizen
        $this->sendRejectionNotification($reservation);

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation rejected successfully!'
        ]);
    }

    /**
     * Download uploaded document
     */
    public function downloadDocument($id, $type)
    {
        $reservation = Booking::findOrFail($id);
        
        $filePath = match($type) {
            'id_front' => $reservation->valid_id_path, 
            'id_back' => $reservation->id_back_path,
            'id_selfie' => $reservation->id_selfie_path,
            'authorization_letter' => $reservation->authorization_letter_path,
            'event_proposal' => $reservation->event_proposal_path,
            'signature' => $reservation->digital_signature,
            default => null
        };
        
        \Log::info('Download request:', ['type' => $type, 'file_path' => $filePath, 'reservation_id' => $reservation->id]);
        
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Document not found');
        }

        return Storage::disk('public')->download($filePath);
    }

    /**
     * Preview uploaded document
     */
    public function previewDocument($id, $type)
    {
        $reservation = Booking::findOrFail($id);
        
        $filePath = match($type) {
            'id_front' => $reservation->valid_id_path,
            'id_back' => $reservation->id_back_path, 
            'id_selfie' => $reservation->id_selfie_path,
            'authorization_letter' => $reservation->authorization_letter_path,
            'event_proposal' => $reservation->event_proposal_path,
            default => null
        };
        
        \Log::info('Preview request:', ['type' => $type, 'file_path' => $filePath, 'reservation_id' => $reservation->id]);
        
        // Handle digital signature separately (can be base64)
        if ($type === 'signature') {
            $signature = $reservation->digital_signature;
            if (!$signature) {
                abort(404, 'Signature not found');
            }
            
            // If it's base64 data, return it directly
            if (str_starts_with($signature, 'data:image')) {
                $data = explode(',', $signature)[1];
                $file = base64_decode($data);
                return response($file, 200)->header('Content-Type', 'image/png');
            }
            
            // Otherwise treat as file path
            if (!Storage::disk('public')->exists($signature)) {
                abort(404, 'Signature file not found');
            }
            
            $file = Storage::disk('public')->get($signature);
            $mimeType = Storage::disk('public')->mimeType($signature);
            return response($file, 200)->header('Content-Type', $mimeType);
        }
        
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            \Log::warning('File not found:', ['path' => $filePath, 'exists' => $filePath ? Storage::disk('public')->exists($filePath) : false]);
            abort(404, 'Document not found');
        }

        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        
        return response($file, 200)->header('Content-Type', $mimeType);
    }

    /**
     * Get information about uploaded files
     */
    private function getUploadedFilesInfo($reservation)
    {
        $files = [];
        
        // ID Documents
        if ($reservation->valid_id_path) {
            $files['id_front'] = [
                'name' => 'ID Front',
                'path' => $reservation->valid_id_path,
                'exists' => Storage::disk('public')->exists($reservation->valid_id_path),
                'size' => Storage::disk('public')->exists($reservation->valid_id_path) 
                    ? Storage::disk('public')->size($reservation->valid_id_path) 
                    : 0,
                'type' => 'image'
            ];
        }
        
        if ($reservation->id_back_path) {
            $files['id_back'] = [
                'name' => 'ID Back',
                'path' => $reservation->id_back_path,
                'exists' => Storage::disk('public')->exists($reservation->id_back_path),
                'size' => Storage::disk('public')->exists($reservation->id_back_path)
                    ? Storage::disk('public')->size($reservation->id_back_path)
                    : 0,
                'type' => 'image'
            ];
        }
        
        if ($reservation->id_selfie_path) {
            $files['id_selfie'] = [
                'name' => 'Selfie with ID',
                'path' => $reservation->id_selfie_path,
                'exists' => Storage::disk('public')->exists($reservation->id_selfie_path),
                'size' => Storage::disk('public')->exists($reservation->id_selfie_path)
                    ? Storage::disk('public')->size($reservation->id_selfie_path)
                    : 0,
                'type' => 'image'
            ];
        }
        
        // Optional Documents
        if ($reservation->authorization_letter_path) {
            $files['authorization_letter'] = [
                'name' => 'Authorization Letter',
                'path' => $reservation->authorization_letter_path,
                'exists' => Storage::disk('public')->exists($reservation->authorization_letter_path),
                'size' => Storage::disk('public')->exists($reservation->authorization_letter_path)
                    ? Storage::disk('public')->size($reservation->authorization_letter_path)
                    : 0,
                'type' => $this->getFileType($reservation->authorization_letter_path)
            ];
        }
        
        if ($reservation->event_proposal_path) {
            $files['event_proposal'] = [
                'name' => 'Event Proposal',
                'path' => $reservation->event_proposal_path,
                'exists' => Storage::disk('public')->exists($reservation->event_proposal_path),
                'size' => Storage::disk('public')->exists($reservation->event_proposal_path)
                    ? Storage::disk('public')->size($reservation->event_proposal_path)
                    : 0,
                'type' => $this->getFileType($reservation->event_proposal_path)
            ];
        }
        
        // Digital Signature
        if ($reservation->digital_signature) {
            $signature = $reservation->digital_signature;
            $isBase64 = str_starts_with($signature, 'data:image');
            
            $files['signature'] = [
                'name' => 'Digital Signature',
                'path' => $signature,
                'exists' => $isBase64 || Storage::disk('public')->exists($signature),
                'size' => $isBase64 ? strlen($signature) : (Storage::disk('public')->exists($signature) ? Storage::disk('public')->size($signature) : 0),
                'type' => 'image' // Changed from 'signature' to 'image' so it shows preview
            ];
        }
        
        return $files;
    }

    /**
     * Get file type based on extension
     */
    private function getFileType($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        return match(strtolower($extension)) {
            'jpg', 'jpeg', 'png', 'gif' => 'image',
            'pdf' => 'pdf',
            'doc', 'docx' => 'document',
            default => 'file'
        };
    }

    /**
     * Generate payment slip for approved reservation
     */
    private function generatePaymentSlip($reservation)
    {
        $paymentSlip = PaymentSlip::create([
            'slip_number' => PaymentSlip::generateSlipNumber(),
            'booking_id' => $reservation->id,
            'user_id' => $reservation->user_id,
            'generated_by' => Auth::id(),
            'amount' => $reservation->total_fee,
            'due_date' => Carbon::now()->addDays(7), // 7 days to pay
            'status' => 'unpaid'
        ]);

        return $paymentSlip;
    }

    /**
     * Send approval notification to citizen
     */
    private function sendApprovalNotification($reservation, $paymentSlip = null)
    {
        // TODO: Implement email notification
        // For now, we'll log it
        \Log::info('Reservation Approved:', [
            'reservation_id' => $reservation->id,
            'citizen_email' => $reservation->applicant_email,
            'event_name' => $reservation->event_name,
            'payment_slip_number' => $paymentSlip ? $paymentSlip->slip_number : null,
            'payment_amount' => $paymentSlip ? $paymentSlip->amount : null,
            'payment_due_date' => $paymentSlip ? $paymentSlip->due_date : null
        ]);
    }

    /**
     * Send rejection notification to citizen
     */
    private function sendRejectionNotification($reservation)
    {
        // TODO: Implement email notification
        // For now, we'll log it
        \Log::info('Reservation Rejected:', [
            'reservation_id' => $reservation->id,
            'citizen_email' => $reservation->applicant_email,
            'event_name' => $reservation->event_name,
            'reason' => $reservation->rejected_reason
        ]);
    }
}