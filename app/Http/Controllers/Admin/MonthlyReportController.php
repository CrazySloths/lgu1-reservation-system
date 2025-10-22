<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlyReportController extends Controller
{
    /**
     * Display monthly reports dashboard.
     */
    public function index(Request $request)
    {
        // Get selected month or default to current month
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $monthCarbon = Carbon::parse($selectedMonth . '-01');

        // Get all available months from bookings
        // Use strftime for SQLite, DATE_FORMAT for MySQL
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            $availableMonths = Booking::selectRaw('strftime("%Y-%m", event_date) as month')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->pluck('month');
        } else {
            $availableMonths = Booking::selectRaw('DATE_FORMAT(event_date, "%Y-%m") as month')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->pluck('month');
        }

        // Get bookings for selected month
        $bookings = Booking::with(['facility', 'user'])
            ->whereYear('event_date', $monthCarbon->year)
            ->whereMonth('event_date', $monthCarbon->month)
            ->get();

        // Calculate statistics
        $stats = [
            'total_bookings' => $bookings->count(),
            'approved_bookings' => $bookings->where('status', 'approved')->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'rejected_bookings' => $bookings->where('status', 'rejected')->count(),
            'total_revenue' => $bookings->where('status', 'approved')->sum('total_fee'),
            'total_attendees' => $bookings->where('status', 'approved')->sum('expected_attendees'),
        ];

        // Facility usage statistics
        $facilityStats = $bookings->where('status', 'approved')
            ->groupBy('facility_id')
            ->map(function ($facilityBookings) {
                $facility = $facilityBookings->first()->facility;
                return [
                    'facility_name' => $facility->name ?? 'N/A',
                    'bookings_count' => $facilityBookings->count(),
                    'total_revenue' => $facilityBookings->sum('total_fee'),
                    'total_attendees' => $facilityBookings->sum('expected_attendees'),
                ];
            })
            ->sortByDesc('bookings_count')
            ->values();

        // Daily bookings count for the month
        $dailyBookings = $bookings->where('status', 'approved')
            ->groupBy(function ($booking) {
                return $booking->event_date->format('Y-m-d');
            })
            ->map(function ($dayBookings) {
                return $dayBookings->count();
            })
            ->sortKeys();

        // Revenue by week
        $weeklyRevenue = $bookings->where('status', 'approved')
            ->groupBy(function ($booking) {
                return 'Week ' . $booking->event_date->weekOfMonth;
            })
            ->map(function ($weekBookings) {
                return $weekBookings->sum('total_fee');
            })
            ->sortKeys();

        // Top users (by booking count)
        // Group by applicant_name since dummy data has user_id = NULL
        $topUsers = $bookings->where('status', 'approved')
            ->groupBy('applicant_name')
            ->map(function ($userBookings) {
                return [
                    'user_name' => $userBookings->first()->applicant_name ?? $userBookings->first()->user_name ?? 'N/A',
                    'bookings_count' => $userBookings->count(),
                    'total_spent' => $userBookings->sum('total_fee'),
                ];
            })
            ->sortByDesc('bookings_count')
            ->take(10)
            ->values();

        return view('admin.monthly-reports.index', compact(
            'selectedMonth',
            'monthCarbon',
            'availableMonths',
            'stats',
            'facilityStats',
            'dailyBookings',
            'weeklyRevenue',
            'topUsers',
            'bookings'
        ));
    }

    /**
     * Export monthly report as PDF or Excel.
     */
    public function export(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $format = $request->input('format', 'pdf'); // pdf or excel

        // For now, return JSON data (can be extended to actual PDF/Excel generation)
        $monthCarbon = Carbon::parse($month . '-01');

        $bookings = Booking::with(['facility', 'user'])
            ->whereYear('event_date', $monthCarbon->year)
            ->whereMonth('event_date', $monthCarbon->month)
            ->get();

        $stats = [
            'month' => $monthCarbon->format('F Y'),
            'total_bookings' => $bookings->count(),
            'approved_bookings' => $bookings->where('status', 'approved')->count(),
            'total_revenue' => $bookings->where('status', 'approved')->sum('total_fee'),
        ];

        return response()->json([
            'stats' => $stats,
            'bookings' => $bookings
        ]);
    }
}
