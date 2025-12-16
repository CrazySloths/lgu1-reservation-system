<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the Citizen dashboard.
     */
    public function index()
    {
        // Get user data from session (lgu1_auth system)
        $userId = session('user_id');
        $userName = session('user_name');
        $userEmail = session('user_email');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }
        
        // Get available facilities count (exclude soft-deleted)
        $availableFacilities = DB::connection('facilities_db')
            ->table('facilities')
            ->whereNull('deleted_at')
            ->count();
        
        // Get total reservations (all bookings by user)
        $totalReservations = DB::connection('facilities_db')
            ->table('bookings')
            ->where('user_id', $userId)
            ->count();
        
        // Get active bookings
        $activeBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'staff_verified', 'reserved', 'tentative', 'payment_pending', 'confirmed'])
            ->count();
        
        // Get completed bookings
        $completedBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->where('start_time', '<', Carbon::now())
            ->count();
        
        // Get unpaid payment slips count
        $unpaidPaymentSlips = DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('user_id', $userId)
            ->where('status', 'unpaid')
            ->count();
        
        // Get total spent (from paid payment slips)
        $totalSpent = DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('amount') ?? 0;
        
        // Get upcoming bookings (next 5)
        $upcomingBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'payment_pending', 'reserved'])
            ->where('start_time', '>=', Carbon::now())
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();
        
        // Get pending payments (unpaid payment slips)
        $pendingPayments = DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('user_id', $userId)
            ->where('status', 'unpaid')
            ->where('due_date', '>=', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->get();
        
        return view('citizen.dashboard', [
            'availableFacilities' => $availableFacilities,
            'totalReservations' => $totalReservations,
            'unpaidPaymentSlips' => $unpaidPaymentSlips,
            'activeBookings' => $activeBookings,
            'completedBookings' => $completedBookings,
            'totalSpent' => $totalSpent,
            'upcomingBookings' => $upcomingBookings,
            'pendingPayments' => $pendingPayments,
        ]);
    }
}

