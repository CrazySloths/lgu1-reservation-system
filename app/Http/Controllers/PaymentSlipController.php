<?php

namespace App\Http\Controllers;

use App\Models\PaymentSlip;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Barryvdh\DomPDF\Facade\Pdf;

class PaymentSlipController extends Controller
{
    /**
     * Display citizen's payment slips
     */
    public function citizenIndex()
    {
        $user = Auth::user();
        
        // Fallback for static authentication
        if (!$user) {
            // Use default citizen user ID from static auth
            $userId = 1; // Default citizen user ID
        } else {
            $userId = $user->id;
        }
        
        $paymentSlips = PaymentSlip::where('user_id', $userId)
                                  ->with(['booking.facility', 'generatedBy'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('citizen.payment-slips.index', compact('paymentSlips'));
    }

    /**
     * Display specific payment slip for citizen
     */
    public function citizenShow($id)
    {
        $user = Auth::user();
        
        // Fallback for static authentication
        if (!$user) {
            // Use default citizen user ID from static auth
            $userId = 1; // Default citizen user ID
        } else {
            $userId = $user->id;
        }
        
        $paymentSlip = PaymentSlip::where('user_id', $userId)
                                 ->where('id', $id)
                                 ->with(['booking.facility', 'generatedBy'])
                                 ->firstOrFail();

        return view('citizen.payment-slips.show', compact('paymentSlip'));
    }

    /**
     * Download payment slip as PDF for citizen
     */
    public function citizenDownloadPdf($id)
    {
        $user = Auth::user();
        
        // Fallback for static authentication
        if (!$user) {
            // Use default citizen user ID from static auth
            $userId = 1; // Default citizen user ID
        } else {
            $userId = $user->id;
        }
        
        $paymentSlip = PaymentSlip::where('user_id', $userId)
                                 ->where('id', $id)
                                 ->with(['booking.facility', 'generatedBy'])
                                 ->firstOrFail();

        // For now, return the printable HTML view
        // TODO: Install barryvdh/laravel-dompdf for PDF generation
        return view('citizen.payment-slips.pdf', compact('paymentSlip'));
        
        // Once PDF package is installed, uncomment this:
        // $pdf = Pdf::loadView('citizen.payment-slips.pdf', compact('paymentSlip'));
        // return $pdf->download("payment-slip-{$paymentSlip->slip_number}.pdf");
    }

    /**
     * Admin view all payment slips
     */
    public function adminIndex(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $paymentSlips = PaymentSlip::with(['booking.facility', 'user', 'generatedBy', 'paidByCashier'])
                                  ->when($status !== 'all', function ($query) use ($status) {
                                      return $query->where('status', $status);
                                  })
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(15);

        $statusCounts = [
            'unpaid' => PaymentSlip::where('status', 'unpaid')->count(),
            'paid' => PaymentSlip::where('status', 'paid')->count(),
            'expired' => PaymentSlip::where('status', 'expired')->count(),
            'all' => PaymentSlip::count()
        ];

        return view('admin.payment-slips.index', compact('paymentSlips', 'statusCounts', 'status'));
    }

    /**
     * Mark payment slip as paid (Admin only)
     */
    public function markAsPaid(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string|max:100',
            'cashier_notes' => 'nullable|string|max:1000'
        ]);

        $paymentSlip = PaymentSlip::findOrFail($id);
        
        if ($paymentSlip->status !== 'unpaid') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment slip is not in unpaid status'
            ]);
        }

        $paymentSlip->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method,
            'cashier_notes' => $request->cashier_notes,
            'paid_by_cashier' => Auth::id()
        ]);

        \Log::info('Payment Slip Marked as Paid:', [
            'slip_number' => $paymentSlip->slip_number,
            'paid_by_cashier' => Auth::id(),
            'payment_method' => $request->payment_method
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment recorded successfully!'
        ]);
    }

    /**
     * Mark expired payment slips (Admin/System function)
     */
    public function markExpired()
    {
        $expiredCount = PaymentSlip::where('status', 'unpaid')
                                  ->where('due_date', '<', now())
                                  ->update(['status' => 'expired']);

        return response()->json([
            'status' => 'success',
            'message' => "Marked {$expiredCount} payment slips as expired"
        ]);
    }
}