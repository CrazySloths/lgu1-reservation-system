<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display all bookings/reservations for the logged-in citizen.
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $status = $request->get('status', 'all');
        $search = $request->get('search');

        // Base query
        $query = DB::connection('facilities_db')
            ->table('bookings')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->select(
                'bookings.*',
                'facilities.name as facility_name',
                'facilities.address as facility_address',
                'facilities.image_path as facility_image',
                'lgu_cities.city_name',
                'lgu_cities.city_code'
            )
            ->where('bookings.user_id', $userId)
            ->orderBy('bookings.start_time', 'desc');

        // Filter by status
        if ($status !== 'all') {
            if ($status === 'active') {
                $query->whereIn('bookings.status', ['pending', 'staff_verified', 'payment_pending', 'confirmed']);
            } elseif ($status === 'completed') {
                $query->where('bookings.status', 'completed');
            } elseif ($status === 'cancelled') {
                $query->whereIn('bookings.status', ['cancelled', 'rejected']);
            } else {
                $query->where('bookings.status', $status);
            }
        }

        // Search by facility name or booking reference
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('facilities.name', 'like', "%{$search}%")
                  ->orWhere('bookings.id', 'like', "%{$search}%")
                  ->orWhere('bookings.purpose', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(10);

        // Get counts for filter badges
        $statusCounts = [
            'all' => DB::connection('facilities_db')->table('bookings')->where('user_id', $userId)->count(),
            'active' => DB::connection('facilities_db')->table('bookings')
                ->where('user_id', $userId)
                ->whereIn('status', ['pending', 'staff_verified', 'payment_pending', 'confirmed'])
                ->count(),
            'completed' => DB::connection('facilities_db')->table('bookings')
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->count(),
            'cancelled' => DB::connection('facilities_db')->table('bookings')
                ->where('user_id', $userId)
                ->whereIn('status', ['cancelled', 'rejected'])
                ->count(),
        ];

        return view('citizen.reservations.index', compact('bookings', 'status', 'search', 'statusCounts'));
    }

    /**
     * Display details of a specific booking.
     */
    public function show($id)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = DB::connection('facilities_db')
            ->table('bookings')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->select(
                'bookings.*',
                'facilities.name as facility_name',
                'facilities.description as facility_description',
                'facilities.address as facility_address',
                'facilities.capacity as facility_capacity',
                'facilities.image_path as facility_image',
                'lgu_cities.city_name',
                'lgu_cities.city_code'
            )
            ->where('bookings.id', $id)
            ->where('bookings.user_id', $userId)
            ->first();

        if (!$booking) {
            return redirect()->route('citizen.reservations')->with('error', 'Booking not found.');
        }

        // Get selected equipment
        $equipment = DB::connection('facilities_db')
            ->table('booking_equipment')
            ->join('equipment_items', 'booking_equipment.equipment_item_id', '=', 'equipment_items.id')
            ->select('booking_equipment.*', 'equipment_items.name as equipment_name', 'equipment_items.category')
            ->where('booking_equipment.booking_id', $id)
            ->get();

        // Get payment slip if exists
        $paymentSlip = DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('booking_id', $id)
            ->first();

        return view('citizen.reservations.show', compact('booking', 'equipment', 'paymentSlip'));
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, $id)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = DB::connection('facilities_db')
            ->table('bookings')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$booking) {
            return redirect()->route('citizen.reservations')->with('error', 'Booking not found.');
        }

        // Check if booking can be cancelled (only pending, staff_verified, payment_pending)
        if (!in_array($booking->status, ['pending', 'staff_verified', 'payment_pending'])) {
            return redirect()->back()->with('error', 'This booking cannot be cancelled at this stage.');
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update booking status
        DB::connection('facilities_db')
            ->table('bookings')
            ->where('id', $id)
            ->update([
                'status' => 'cancelled',
                'rejected_reason' => $request->cancellation_reason,
                'updated_at' => Carbon::now(),
            ]);

        return redirect()->route('citizen.reservations')->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Upload additional documents for a booking.
     */
    public function uploadDocument(Request $request, $id)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = DB::connection('facilities_db')
            ->table('bookings')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'document_type' => 'required|in:valid_id,special_discount_id,supporting_doc',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        // Handle file upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('booking_documents/' . $request->document_type, 'public');
        }

        // Update the corresponding field
        $fieldMap = [
            'valid_id' => 'valid_id_path',
            'special_discount_id' => 'special_discount_id_path',
            'supporting_doc' => 'supporting_doc_path',
        ];

        $field = $fieldMap[$request->document_type];

        DB::connection('facilities_db')
            ->table('bookings')
            ->where('id', $id)
            ->update([
                $field => $documentPath,
                'updated_at' => Carbon::now(),
            ]);

        return response()->json(['success' => true, 'message' => 'Document uploaded successfully.', 'path' => $documentPath]);
    }

    /**
     * Display reservation history (completed and cancelled).
     */
    public function history(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $search = $request->get('search');

        $query = DB::connection('facilities_db')
            ->table('bookings')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->select(
                'bookings.*',
                'facilities.name as facility_name',
                'facilities.address as facility_address',
                'facilities.image_path as facility_image',
                'lgu_cities.city_name',
                'lgu_cities.city_code'
            )
            ->where('bookings.user_id', $userId)
            ->whereIn('bookings.status', ['completed', 'cancelled', 'rejected'])
            ->orderBy('bookings.start_time', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('facilities.name', 'like', "%{$search}%")
                  ->orWhere('bookings.id', 'like', "%{$search}%")
                  ->orWhere('bookings.purpose', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15);

        return view('citizen.reservations.history', compact('bookings', 'search'));
    }
}

