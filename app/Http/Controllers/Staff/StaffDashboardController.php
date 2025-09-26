<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentSlip;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StaffDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Display the staff dashboard with verification metrics
     */
    public function index(Request $request)
    {
        // Handle SSO token-based login
        if ($request->has('user_id') && $request->has('sig')) {
            $user = User::find($request->input('user_id'));

            // Verify the token and its expiration
            if (
                $user &&
                $user->sso_token === $request->input('sig') &&
                $user->sso_token_expires_at &&
                now()->isBefore($user->sso_token_expires_at)
            ) {
                // Invalidate the token to prevent reuse
                $user->forceFill(['sso_token' => null, 'sso_token_expires_at' => null])->save();

                // Log the user in to create a persistent session
                Auth::login($user);
                session()->regenerate(); // Regenerate session to prevent fixation

                // Redirect to the same page without the token in the URL
                return redirect()->route('staff.dashboard');
            }

            // If token is invalid or expired, redirect back to SSO
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
        }

        if (!Auth::check() || Auth::user() === null) {
            // If user is not authenticated and no valid token is present, redirect to external SSO login
            $ssoLoginUrl = 'https://local-government-unit-1-ph.com/public/login.php';
            return redirect()->away($ssoLoginUrl);
        }

        $user = Auth::user();

        // Handle case where user might not be fully authenticated yet
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to access the staff dashboard.');
        }
        
        // Get verification metrics
        $pendingVerifications = Booking::where('status', 'pending')
            ->whereNull('staff_verified_by')
            ->count();
            
        $myVerificationsToday = Booking::where('staff_verified_by', $user->id)
            ->whereDate('staff_verified_at', today())
            ->count();
            
        $myTotalVerifications = Booking::where('staff_verified_by', $user->id)
            ->count();
            
        $totalPendingAdmin = Booking::where('status', 'pending')
            ->whereNotNull('staff_verified_by')
            ->count();

        // Get recent bookings pending staff verification
        $recentPendingBookings = Booking::with(['user', 'facility'])
            ->where('status', 'pending')
            ->whereNull('staff_verified_by')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get my recent verifications
        $myRecentVerifications = Booking::with(['user', 'facility'])
            ->where('staff_verified_by', $user->id)
            ->orderBy('staff_verified_at', 'desc')
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'pendingVerifications',
            'myVerificationsToday', 
            'myTotalVerifications',
            'totalPendingAdmin',
            'recentPendingBookings',
            'myRecentVerifications'
        ));
    }

    /**
     * Get personal statistics for the staff member
     */
    public function myStats()
    {
        $staff = Auth::user();
        
        // Handle case where user might not be fully authenticated yet
        if (!$staff) {
            return redirect()->route('login')->with('error', 'Please login to access staff statistics.');
        }
        
        $stats = [
            'total_verifications' => Booking::where('staff_verified_by', $staff->id)->count(),
            'verifications_this_week' => Booking::where('staff_verified_by', $staff->id)
                ->whereBetween('staff_verified_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'verifications_this_month' => Booking::where('staff_verified_by', $staff->id)
                ->whereMonth('staff_verified_at', now()->month)
                ->count(),
            'average_per_day' => round(
                Booking::where('staff_verified_by', $staff->id)
                    ->whereMonth('staff_verified_at', now()->month)
                    ->count() / now()->day, 1
            )
        ];

        return view('staff.stats', compact('stats'));
    }

    /**
     * Display list of bookings pending staff verification
     */
    public function verificationIndex()
    {
        $bookings = Booking::with(['user', 'facility'])
            ->where('status', 'pending')
            ->whereNull('staff_verified_by')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('staff.verification.index', compact('bookings'));
    }

    /**
     * Display booking details for verification
     */
    public function verificationShow(Booking $booking)
    {
        $booking->load(['user', 'facility']);
        
        return view('staff.verification.show', compact('booking'));
    }

    /**
     * Process staff verification
     */
    public function processVerification(Request $request, Booking $booking)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'staff_notes' => 'required|string|max:500'
        ]);

        $booking->update([
            'staff_verified_by' => Auth::id(),
            'staff_verified_at' => now(),
            'staff_notes' => $request->staff_notes,
            'status' => $request->action === 'approve' ? 'staff_approved' : 'rejected'
        ]);

        return redirect()->route('staff.verification.index')
            ->with('success', 'Booking ' . $request->action . 'd successfully!');
    }
}
