@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <!-- Enhanced Header Section -->
    <div class="bg-gradient-to-r from-green-700 to-green-900 rounded-2xl p-8 text-white shadow-xl overflow-hidden relative" style="background: linear-gradient(135deg, #047857 0%, #064e3b 100%)!important;">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
        </div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-1 bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Staff Verification Portal</h1>
                        <p class="text-gray-200 text-lg">Document & Requirement Verification Center</p>
                    </div>
                </div>
            </div>
            <div class="text-right space-y-2">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                    <p class="text-sm text-gray-200 font-medium" id="current-date">{{ now()->format('l, F j, Y') }}</p>
                    <p class="text-2xl font-bold text-orange-500" id="current-time-main">{{ now()->format('g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Pending Verifications -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-5 shadow-lg hover:shadow-xl transition-all hover:scale-105" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%)!important; border-color: #fca5a5!important;">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg" style="background: #dc2626!important;">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-red-800">Pending Verification</p>
                    <p class="text-3xl font-bold text-red-900">{{ $pendingVerifications }}</p>
                    @if($pendingVerifications > 0)
                        <a href="{{ route('staff.verification.index') }}" class="text-xs text-red-600 hover:text-red-800 underline">
                            Review Now →
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- My Today's Verifications -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5 shadow-lg hover:shadow-xl transition-all hover:scale-105" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%)!important; border-color: #93c5fd!important;">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg" style="background: #3b82f6!important;">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-blue-800">Today's Verifications</p>
                    <p class="text-3xl font-bold text-blue-900">{{ $myVerificationsToday }}</p>
                    <p class="text-xs text-blue-600">Completed by me</p>
                </div>
            </div>
        </div>

        <!-- My Total Verifications -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-xl p-5 shadow-lg hover:shadow-xl transition-all hover:scale-105" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)!important; border-color: #4ade80!important;">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg" style="background: #10b981!important;">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-emerald-800">Total Verified</p>
                    <p class="text-3xl font-bold text-emerald-900">{{ $myTotalVerifications }}</p>
                    <a href="{{ route('staff.stats') }}" class="text-xs text-emerald-600 hover:text-emerald-800 underline">
                        View Details →
                    </a>
                </div>
            </div>
        </div>

        <!-- Pending Admin Approval -->
        <div class="bg-gradient-to-br from-yellow-50 to-amber-100 border border-yellow-200 rounded-xl p-5 shadow-lg hover:shadow-xl transition-all hover:scale-105" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)!important; border-color: #fbbf24!important;">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center shadow-lg" style="background: #f59e0b!important;">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-amber-800">Awaiting Admin</p>
                    <p class="text-3xl font-bold text-amber-900">{{ $totalPendingAdmin }}</p>
                    <p class="text-xs text-amber-600">Staff verified</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Verifications -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-300" style="box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)!important;">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Verifications</h3>
                    <a href="{{ route('staff.verification.index') }}" class="text-sm text-orange-500 hover:text-lgu-headline font-medium">
                        View All →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recentPendingBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentPendingBookings as $booking)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $booking->event_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->facility->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">Submitted: {{ $booking->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('staff.verification.show', $booking) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-lgu-highlight text-lgu-button-text text-sm font-medium rounded-lg hover:bg-lgu-button transition-colors">
                                        Review
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">No pending verifications</p>
                        <p class="text-gray-500 text-sm">All bookings have been processed!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- My Recent Verifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">My Recent Verifications</h3>
                    <a href="{{ route('staff.stats') }}" class="text-sm text-orange-500 hover:text-lgu-headline font-medium">
                        View Stats →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($myRecentVerifications->count() > 0)
                    <div class="space-y-4">
                        @foreach($myRecentVerifications as $booking)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $booking->event_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->facility->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">Verified: {{ $booking->staff_verified_at->format('M j, Y g:i A') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($booking->status === 'approved')
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($booking->status === 'rejected')
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Admin
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">No verifications yet</p>
                        <p class="text-gray-500 text-sm">Start verifying bookings to see your activity here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-300 p-6" style="box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)!important;">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('staff.verification.index') }}" 
               class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-orange-400 hover:bg-orange-50 transition-all">
                <svg class="w-8 h-8 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Verify Requirements</p>
                    <p class="text-sm text-gray-600">Review pending bookings</p>
                </div>
            </a>
            
            <a href="{{ route('staff.stats') }}" 
               class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-orange-400 hover:bg-orange-50 transition-all">
                <svg class="w-8 h-8 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">View Statistics</p>
                    <p class="text-sm text-gray-600">My verification metrics</p>
                </div>
            </a>

            <a href="#" class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-orange-400 hover:bg-orange-50 transition-all">
                <svg class="w-8 h-8 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Guidelines</p>
                    <p class="text-sm text-gray-600">Verification procedures</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Real-time clock functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateDateTime() {
        const now = new Date();
        const dateElement = document.getElementById('current-date');
        const timeElement = document.getElementById('current-time-main');
        
        if (dateElement) {
            const dateString = now.toLocaleDateString('en-US', { 
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            dateElement.textContent = dateString;
        }
        
        if (timeElement) {
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true,
                hour: 'numeric',
                minute: '2-digit'
            });
            timeElement.textContent = timeString;
        }
    }

    // Update date/time immediately and then every second
    updateDateTime();
    setInterval(updateDateTime, 1000);
});
</script>
@endsection
