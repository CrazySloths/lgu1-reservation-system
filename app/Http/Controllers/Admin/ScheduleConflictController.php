<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;

class ScheduleConflictController extends Controller
{
    /**
     * Display a list of all schedule conflicts.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        // Retrieve the conflicts and the reservations involved
        $conflicts = $this->findConflicts();

        // Return the view with the conflict data
        return view('schedule-conflicts', compact('conflicts'));
    }

    /**
     * Logic to find overlapping reservations.
     *
     * @return \Illuminate\Support\Collection
     */
    private function findConflicts()
    {
        // Step 1: Get the IDs of reservations that have an overlap.
        $conflictingIds = DB::table('bookings as a') 
            ->select('a.id')
            ->join('bookings as b', function ($join) {
                $join->on('a.facility_id', '=', 'b.facility_id') 
                     ->whereRaw('a.id != b.id')
                     ->whereRaw('(a.start_time < b.end_time AND a.end_time > b.start_time)')
                     ->whereIn('a.status', ['Approved', 'Pending'])
                     ->whereIn('b.status', ['Approved', 'Pending']);
            })
            // Prevent duplicates
            ->distinct()
            ->pluck('a.id');

        // Check if any conflicts were found
        if ($conflictingIds->isEmpty()) {
            return collect(); // Return an empty collection if no conflicts
        }

        // Step 2: Retrieve the full reservation details for the conflicting IDs
        $conflictingReservations = Reservation::whereIn('id', $conflictingIds)
            ->with(['facility', 'user']) 
            ->orderBy('facility_id')
            ->orderBy('start_time')
            ->get();
            
        // Step 3: Group the reservations to be easily displayed in the view.
        $conflictGroups = $conflictingReservations->groupBy(function ($item) {
             return $item->facility_id . '-' . \Carbon\Carbon::parse($item->start_time)->toDateString();
        });

        return $conflictGroups;
    }
} 