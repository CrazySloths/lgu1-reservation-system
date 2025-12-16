<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display all payment slips for the logged-in citizen.
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        // Base query
        $query = DB::connection('facilities_db')
            ->table('payment_slips')
            ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->select(
                'payment_slips.*',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.purpose',
                'facilities.name as facility_name',
                'facilities.address as facility_address'
            )
            ->where('payment_slips.user_id', $userId)
            ->orderBy('payment_slips.created_at', 'desc');

        // Filter by status
        if ($status !== 'all') {
            $query->where('payment_slips.status', $status);
        }

        // Live search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('payment_slips.slip_number', 'like', "%{$search}%")
                  ->orWhere('facilities.name', 'like', "%{$search}%")
                  ->orWhere('bookings.purpose', 'like', "%{$search}%");
            });
        }

        $paymentSlips = $query->paginate(10);

        // Get counts for filter badges
        $statusCounts = [
            'all' => DB::connection('facilities_db')->table('payment_slips')->where('user_id', $userId)->count(),
            'unpaid' => DB::connection('facilities_db')->table('payment_slips')->where('user_id', $userId)->where('status', 'unpaid')->count(),
            'paid' => DB::connection('facilities_db')->table('payment_slips')->where('user_id', $userId)->where('status', 'paid')->count(),
            'expired' => DB::connection('facilities_db')->table('payment_slips')->where('user_id', $userId)->where('status', 'expired')->count(),
        ];

        return view('citizen.payments.index', compact('paymentSlips', 'status', 'statusCounts'));
    }

    /**
     * Display details of a specific payment slip.
     */
    public function show($id)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $paymentSlip = DB::connection('facilities_db')
            ->table('payment_slips')
            ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->select(
                'payment_slips.*',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.purpose',
                'bookings.expected_attendees',
                'bookings.base_rate',
                'bookings.extension_rate',
                'bookings.equipment_total',
                'bookings.subtotal',
                'bookings.resident_discount_amount',
                'bookings.special_discount_amount',
                'bookings.total_discount',
                'facilities.name as facility_name',
                'facilities.address as facility_address',
                'facilities.image_path as facility_image',
                'lgu_cities.city_name',
                'lgu_cities.city_code'
            )
            ->where('payment_slips.id', $id)
            ->where('payment_slips.user_id', $userId)
            ->first();

        if (!$paymentSlip) {
            return redirect()->route('citizen.payment-slips')->with('error', 'Payment slip not found.');
        }

        // Get selected equipment for the booking
        $equipment = DB::connection('facilities_db')
            ->table('booking_equipment')
            ->join('equipment_items', 'booking_equipment.equipment_item_id', '=', 'equipment_items.id')
            ->select('booking_equipment.*', 'equipment_items.name as equipment_name', 'equipment_items.category')
            ->where('booking_equipment.booking_id', $paymentSlip->booking_id)
            ->get();

        return view('citizen.payments.show', compact('paymentSlip', 'equipment'));
    }

    /**
     * Upload payment proof/receipt.
     */
    public function uploadProof(Request $request, $id)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please login to continue.'], 401);
        }

        $paymentSlip = DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$paymentSlip) {
            return response()->json(['success' => false, 'message' => 'Payment slip not found.'], 404);
        }

        if ($paymentSlip->status !== 'unpaid') {
            return response()->json(['success' => false, 'message' => 'This payment slip has already been processed.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'payment_method' => 'required|in:cash,gcash,paymaya,bank_transfer,check',
            'reference_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        // Handle file upload
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // Update payment slip
        DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('id', $id)
            ->update([
                'payment_receipt_url' => $paymentProofPath,
                'payment_method' => $request->payment_method,
                'payment_gateway' => $request->payment_method,
                'gateway_reference_number' => $request->reference_number,
                'updated_at' => Carbon::now(),
            ]);

        return response()->json(['success' => true, 'message' => 'Payment proof uploaded successfully. Please wait for staff verification.']);
    }
}

