<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display bulletin board for citizens
     */
    public function citizenIndex()
    {
        $user = Auth::user();
        
        // Get active announcements for citizens
        $announcements = Announcement::active()
                                   ->forAudience('citizens')
                                   ->byPriority()
                                   ->orderBy('is_pinned', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        // Separate pinned and regular announcements
        $pinnedAnnouncements = $announcements->where('is_pinned', true);
        $regularAnnouncements = $announcements->where('is_pinned', false);

        return view('citizen.bulletin-board', compact('user', 'pinnedAnnouncements', 'regularAnnouncements'));
    }

    /**
     * Display admin announcement management
     */
    public function adminIndex()
    {
        $announcements = Announcement::with('creator')
                                   ->orderBy('is_pinned', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show form to create new announcement
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store new announcement
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,maintenance,event,urgent,facility_update',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_audience' => 'required|in:all,citizens,admins',
            'is_pinned' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'additional_info' => 'nullable|string'
        ]);

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('announcements/attachments', 'public');
        }

        Announcement::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'type' => $validatedData['type'],
            'priority' => $validatedData['priority'],
            'target_audience' => $validatedData['target_audience'],
            'is_pinned' => $request->has('is_pinned'),
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'created_by' => Auth::id(),
            'attachment_path' => $attachmentPath,
            'additional_info' => $validatedData['additional_info']
        ]);

        return redirect()->route('admin.announcements.index')
                        ->with('success', 'Announcement created successfully!');
    }

    /**
     * Show form to edit announcement
     */
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update announcement
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,maintenance,event,urgent,facility_update',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_audience' => 'required|in:all,citizens,admins',
            'is_active' => 'boolean',
            'is_pinned' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'additional_info' => 'nullable|string'
        ]);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($announcement->attachment_path && Storage::disk('public')->exists($announcement->attachment_path)) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            
            $validatedData['attachment_path'] = $request->file('attachment')->store('announcements/attachments', 'public');
        }

        $announcement->update([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'type' => $validatedData['type'],
            'priority' => $validatedData['priority'],
            'target_audience' => $validatedData['target_audience'],
            'is_active' => $request->has('is_active'),
            'is_pinned' => $request->has('is_pinned'),
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'attachment_path' => $validatedData['attachment_path'] ?? $announcement->attachment_path,
            'additional_info' => $validatedData['additional_info']
        ]);

        return redirect()->route('admin.announcements.index')
                        ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Delete announcement
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Delete attachment if exists
        if ($announcement->attachment_path && Storage::disk('public')->exists($announcement->attachment_path)) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }
        
        $announcement->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement deleted successfully!'
        ]);
    }

    /**
     * Toggle announcement status (active/inactive)
     */
    public function toggleStatus($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_active' => !$announcement->is_active]);

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement status updated successfully!',
            'is_active' => $announcement->is_active
        ]);
    }

    /**
     * Toggle pin status
     */
    public function togglePin($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_pinned' => !$announcement->is_pinned]);

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement pin status updated successfully!',
            'is_pinned' => $announcement->is_pinned
        ]);
    }

    /**
     * Download announcement attachment
     */
    public function downloadAttachment($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        if (!$announcement->attachment_path || !Storage::disk('public')->exists($announcement->attachment_path)) {
            abort(404, 'Attachment not found');
        }

        return Storage::disk('public')->download($announcement->attachment_path);
    }
}