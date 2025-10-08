<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Booking; 
use Carbon\Carbon; 

class AnalyticsController extends Controller
{
    public function getUsageData()
    {
        // get all approved bookings
        // Note: Assumed 'start_time' and 'end_time' are the correct column names based on earlier SQL error.
        $bookings = Booking::where('status', 'approved')
                            ->orderBy('start_time') // FIXED column name (replace with your actual column if needed)
                            ->get();

        $monthlyUsage = [];

        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->start_time); // FIXED column name
            $end = Carbon::parse($booking->end_time);     // FIXED column name

            // calculate the duration in hours
            $durationHours = $end->diffInMinutes($start) / 60;
            
            // CRITICAL FIX: Aggregate by MONTH (YYYY-MM)
            $month = $start->format('Y-m'); 

            // increment the usage for that MONTH
            if (!isset($monthlyUsage[$month])) {
                $monthlyUsage[$month] = 0;
            }
            $monthlyUsage[$month] += $durationHours;
        }

        // CRITICAL FIX: Return only the usage values as a simple array for the AI model
        $usageValues = [];
        foreach ($monthlyUsage as $usage) {
            $usageValues[] = round($usage, 2); 
        }

        return response()->json($usageValues); // Output: [20.5, 35.0, 18.25, ...]
    }
}