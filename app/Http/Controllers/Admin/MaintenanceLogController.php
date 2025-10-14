<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of maintenance logs.
     */
    public function index(Request $request)
    {
        $query = MaintenanceLog::with('facility');

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority != 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter by facility
        if ($request->has('facility_id') && $request->facility_id != 'all') {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by maintenance type
        if ($request->has('type') && $request->type != 'all') {
            $query->where('maintenance_type', $request->type);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('assigned_to', 'LIKE', "%{$search}%");
            });
        }

        $maintenanceLogs = $query->orderBy('created_at', 'desc')->paginate(15);
        $facilities = Facility::all();

        // Get counts for stats
        $stats = [
            'total' => MaintenanceLog::count(),
            'pending' => MaintenanceLog::pending()->count(),
            'in_progress' => MaintenanceLog::inProgress()->count(),
            'completed' => MaintenanceLog::completed()->count(),
            'urgent' => MaintenanceLog::urgent()->count(),
        ];

        return view('admin.maintenance-logs.index', compact('maintenanceLogs', 'facilities', 'stats'));
    }

    /**
     * Show the form for creating a new maintenance log.
     */
    public function create()
    {
        $facilities = Facility::all();
        return view('admin.maintenance-logs.create', compact('facilities'));
    }

    /**
     * Store a newly created maintenance log.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'maintenance_type' => 'required|in:repair,cleaning,inspection,preventive,emergency,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'nullable|string|max:255',
            'assigned_contact' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'scheduled_date' => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Add reported_by information
        $user = Auth::user();
        $validated['reported_by'] = $user->name ?? 'Admin';
        $validated['reported_by_id'] = $user->id ?? null;
        $validated['status'] = 'pending';

        $maintenanceLog = MaintenanceLog::create($validated);

        return redirect()->route('admin.maintenance-logs.index')
            ->with('success', 'Maintenance log created successfully!');
    }

    /**
     * Display the specified maintenance log.
     */
    public function show($id)
    {
        $maintenanceLog = MaintenanceLog::with('facility')->findOrFail($id);
        return view('admin.maintenance-logs.show', compact('maintenanceLog'));
    }

    /**
     * Show the form for editing the specified maintenance log.
     */
    public function edit($id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);
        $facilities = Facility::all();
        return view('admin.maintenance-logs.edit', compact('maintenanceLog', 'facilities'));
    }

    /**
     * Update the specified maintenance log.
     */
    public function update(Request $request, $id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);

        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'maintenance_type' => 'required|in:repair,cleaning,inspection,preventive,emergency,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'nullable|string|max:255',
            'assigned_contact' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'scheduled_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'completion_notes' => 'nullable|string',
        ]);

        // Auto-set completed_date if status changed to completed
        if ($validated['status'] === 'completed' && !$maintenanceLog->completed_date) {
            $validated['completed_date'] = now()->format('Y-m-d');
        }

        $maintenanceLog->update($validated);

        return redirect()->route('admin.maintenance-logs.index')
            ->with('success', 'Maintenance log updated successfully!');
    }

    /**
     * Remove the specified maintenance log.
     */
    public function destroy($id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);
        $maintenanceLog->delete();

        return redirect()->route('admin.maintenance-logs.index')
            ->with('success', 'Maintenance log deleted successfully!');
    }

    /**
     * Update the status of a maintenance log.
     */
    public function updateStatus(Request $request, $id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'completion_notes' => 'nullable|string',
            'actual_cost' => 'nullable|numeric|min:0',
        ]);

        if ($validated['status'] === 'completed') {
            $validated['completed_date'] = now()->format('Y-m-d');
        }

        $maintenanceLog->update($validated);

        return redirect()->back()->with('success', 'Status updated successfully!');
    }
}
