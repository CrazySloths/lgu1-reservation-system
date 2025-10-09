<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Booking; 
use Carbon\Carbon; 

class AnalyticsController extends Controller
{
    public function getUsageData()
    {
        // Get all approved bookings ordered by event date
        $bookings = Booking::where('status', 'approved')
                            ->orderBy('event_date')
                            ->get();

        $monthlyUsage = [];

        foreach ($bookings as $booking) {
            // Parse the event_date
            $eventDate = Carbon::parse($booking->event_date);
            $dateStr = $eventDate->format('Y-m-d');
            
            // Parse times on the same day for proper calculation
            $start = Carbon::parse($dateStr . ' ' . $booking->start_time);
            $end = Carbon::parse($dateStr . ' ' . $booking->end_time);

            // Calculate the duration in hours
            $durationHours = $start->diffInHours($end);
            
            // Aggregate by MONTH (YYYY-MM)
            $month = $eventDate->format('Y-m'); 

            // Increment the usage for that MONTH
            if (!isset($monthlyUsage[$month])) {
                $monthlyUsage[$month] = 0;
            }
            $monthlyUsage[$month] += $durationHours;
        }

        // Sort by month to ensure chronological order
        ksort($monthlyUsage);

        // Return only the usage values as a simple array for the AI model
        $usageValues = [];
        foreach ($monthlyUsage as $usage) {
            $usageValues[] = round($usage, 2); 
        }

        return response()->json($usageValues); // Output: [20.5, 35.0, 18.25, ...]
    }
}