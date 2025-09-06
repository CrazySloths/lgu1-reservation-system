@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Staff Verification Portal</h1>
                <p class="text-gray-200">Document & Requirement Verification Center</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-200">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-lg font-semibold">{{ now()->format('g:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Verification Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Pending Verifications -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">Pending Verification</p>
                    <p class="text-2xl font-bold text-red-900">{{ $pendingVerifications }}</p>
                    @if($pendingVerifications > 0)
                        <a href="{{ route('staff.verification.index') }}" class="text-xs text-red-600 hover:text-red-800 underline">
                            Review Now →
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- My Today's Verifications -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">Today's Verifications</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $myVerificationsToday }}</p>
                    <p class="text-xs text-blue-600">Completed by me</p>
                </div>
            </div>
        </div>

        <!-- My Total Verifications -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">Total Verified</p>
                    <p class="text-2xl font-bold text-green-900">{{ $myTotalVerifications }}</p>
                    <a href="{{ route('staff.stats') }}" class="text-xs text-green-600 hover:text-green-800 underline">
                        View Details →
                    </a>
                </div>
            </div>
        </div>

        <!-- Pending Admin Approval -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">Awaiting Admin</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $totalPendingAdmin }}</p>
                    <p class="text-xs text-yellow-600">Staff verified</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Verifications -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Verifications</h3>
                    <a href="{{ route('staff.verification.index') }}" class="text-sm text-lgu-button hover:text-lgu-stroke font-medium">
                        View All →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recentPendingBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentPendingBookings as $booking)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $booking->event_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->facility->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">Submitted: {{ $booking->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('staff.verification.show', $booking) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-lgu-button text-lgu-button-text text-xs rounded-lg hover:bg-yellow-400">
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
                        <p class="text-gray-600">No pending verifications</p>
                        <p class="text-gray-500 text-sm">All bookings have been processed!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- My Recent Verifications -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">My Recent Verifications</h3>
                    <a href="{{ route('staff.stats') }}" class="text-sm text-lgu-button hover:text-lgu-stroke font-medium">
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
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($booking->status === 'rejected')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
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
                        <p class="text-gray-600">No verifications yet</p>
                        <p class="text-gray-500 text-sm">Start verifying bookings to see your activity here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('staff.verification.index') }}" 
               class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-lgu-button hover:bg-lgu-bg transition-colors">
                <svg class="w-8 h-8 text-lgu-button mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Verify Requirements</p>
                    <p class="text-sm text-gray-600">Review pending bookings</p>
                </div>
            </a>
            
            <a href="{{ route('staff.stats') }}" 
               class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-lgu-button hover:bg-lgu-bg transition-colors">
                <svg class="w-8 h-8 text-lgu-button mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">View Statistics</p>
                    <p class="text-sm text-gray-600">My verification metrics</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
