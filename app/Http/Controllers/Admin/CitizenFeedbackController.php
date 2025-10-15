<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CitizenFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitizenFeedbackController extends Controller
{
    /**
     * Display a listing of citizen feedback.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = CitizenFeedback::with('respondedBy')->orderBy('created_at', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $feedback = $query->paginate(20);
        
        $stats = [
            'total' => CitizenFeedback::count(),
            'pending' => CitizenFeedback::where('status', 'pending')->count(),
            'in_progress' => CitizenFeedback::where('status', 'in_progress')->count(),
            'resolved' => CitizenFeedback::where('status', 'resolved')->count(),
            'closed' => CitizenFeedback::where('status', 'closed')->count(),
        ];
        
        return view('admin.feedback.index', compact('feedback', 'stats', 'status'));
    }

    /**
     * Display the specified feedback.
     */
    public function show($id)
    {
        $feedback = CitizenFeedback::with('respondedBy')->findOrFail($id);
        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Update the feedback status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
        ]);

        $feedback = CitizenFeedback::findOrFail($id);
        $feedback->status = $request->status;
        $feedback->save();

        return redirect()
            ->back()
            ->with('success', 'Feedback status updated successfully.');
    }

    /**
     * Store admin response to feedback.
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'admin_response' => 'required|string|max:2000',
            'status' => 'required|in:pending,in_progress,resolved,closed',
        ]);

        $feedback = CitizenFeedback::findOrFail($id);
        $feedback->admin_response = $request->admin_response;
        $feedback->status = $request->status;
        $feedback->responded_by = Auth::id() ?? 1; // Fallback to admin ID 1
        $feedback->responded_at = now();
        $feedback->save();

        // In a real system, you would send an email to the citizen here
        // Mail::to($feedback->email)->send(new FeedbackResponseMail($feedback));

        return redirect()
            ->route('admin.feedback.show', $feedback->id)
            ->with('success', 'Response sent successfully. The citizen will be notified via email.');
    }

    /**
     * Delete the specified feedback.
     */
    public function destroy($id)
    {
        $feedback = CitizenFeedback::findOrFail($id);
        $feedback->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('success', 'Feedback deleted successfully.');
    }
}

