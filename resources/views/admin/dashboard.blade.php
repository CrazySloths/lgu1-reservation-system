@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Enhanced Header Section -->
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
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
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
            <div>
                        <h1 class="text-4xl font-bold mb-1 text-white">LGU Admin Dashboard</h1>
                        <p class="text-gray-200 text-lg">South Caloocan City General Services Department</p>
                    </div>
                </div>
            </div>
            <div class="text-right space-y-2">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                    <p class="text-sm text-gray-200 font-medium" id="current-date">{{ now()->format('l, F j, Y') }}</p>
                    <p class="text-2xl font-bold text-lgu-highlight" id="current-time-main">{{ now()->format('g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Alerts Bar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Pending Approvals -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all hover:scale-105">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-red-800">Pending Approvals</p>
                    <p class="text-3xl font-bold text-red-900">{{ $pendingApprovalsCount }}</p>
                </div>
            </div>
        </div>

        <!-- Schedule Conflicts -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all hover:scale-105">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-orange-800">Schedule Conflicts</p>
                    <p class="text-3xl font-bold text-orange-900">{{ $conflicts->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Overdue Payments -->
        <div class="bg-gradient-to-br from-yellow-50 to-amber-100 border border-yellow-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all hover:scale-105">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8zM6 10a2 2 0 114 0 2 2 0 01-4 0z"/>
                    </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-amber-800">Overdue Payments</p>
                    <p class="text-3xl font-bold text-amber-900">{{ $overduePayments->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Today's Events -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all hover:scale-105">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-emerald-800">Today's Events</p>
                    <p class="text-3xl font-bold text-emerald-900">3</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Monthly Overview & Quick Actions -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Monthly Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ now()->format('F Y') }} Overview</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-lgu-headline">{{ $monthlyStats['bookings_count'] }}</p>
                        <p class="text-sm text-gray-600">Total Bookings</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">{{ $monthlyStats['approved_bookings'] }}</p>
                        <p class="text-sm text-gray-600">Approved Events</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600">₱{{ number_format($monthlyStats['revenue'], 0) }}</p>
                        <p class="text-sm text-gray-600">Revenue Collected</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-orange-600">₱{{ number_format($monthlyStats['pending_revenue'], 0) }}</p>
                        <p class="text-sm text-gray-600">Pending Payment</p>
                    </div>
                </div>
            </div>

            <!-- Facility Utilization -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Facility Utilization ({{ now()->format('F') }})</h3>
                <div class="space-y-4">
                    @forelse($facilityStats as $facility)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3 
                                    @if($facility->name === 'Buena Park') bg-blue-500
                                    @elseif($facility->name === 'Sports Complex') bg-green-500
                                    @elseif(str_contains($facility->name, 'Bulwagan')) bg-purple-500
                                    @elseif(str_contains($facility->name, 'Pacquiao')) bg-red-500
                                    @else bg-gray-500
                                    @endif">
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $facility->name }}</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-2xl font-bold text-gray-900">{{ $facility->monthly_bookings }}</span>
                                <span class="text-xs text-gray-500">bookings</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full 
                                @if($facility->name === 'Buena Park') bg-blue-500
                                @elseif($facility->name === 'Sports Complex') bg-green-500
                                @elseif(str_contains($facility->name, 'Bulwagan')) bg-purple-500
                                @elseif(str_contains($facility->name, 'Pacquiao')) bg-red-500
                                @else bg-gray-500
                                @endif"
                                style="width: {{ min(($facility->monthly_bookings / 15) * 100, 100) }}%">
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No facilities found</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('admin.reservations.index') }}" 
                       class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <svg class="w-8 h-8 text-blue-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-900">Review Reservations</span>
                    </a>
                    
                    <a href="{{ route('calendar') }}" 
                       class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <svg class="w-8 h-8 text-green-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-green-900">View Calendar</span>
                    </a>
                    
                    <a href="{{ route('admin.payment-slips.index') }}" 
                       class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                        <svg class="w-8 h-8 text-yellow-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8zM6 10a2 2 0 114 0 2 2 0 01-4 0z"/>
                        </svg>
                        <span class="text-sm font-medium text-yellow-900">Payment Slips</span>
                    </a>
                    
                    <a href="{{ route('facility.list') }}" 
                       class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <svg class="w-8 h-8 text-purple-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-purple-900">Manage Facilities</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Right Column: Alerts & Activity -->
        <div class="space-y-6">
            
            <!-- Pending Approvals -->
            @if($pendingApprovals->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                    <a href="{{ route('admin.reservations.index') }}" class="text-sm text-lgu-headline hover:underline">View All</a>
                </div>
                <div class="space-y-3">
                    @foreach($pendingApprovals as $booking)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->event_name }}</p>
                                    <p class="text-xs text-gray-600">{{ $booking->facility->name }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->event_date)->format('M j, Y') }} at {{ $booking->start_time }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Schedule Conflicts -->
            @if($conflicts->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Schedule Conflicts</h3>
                <div class="space-y-3">
                    @foreach($conflicts as $conflict)
                        <div class="border border-orange-200 bg-orange-50 rounded-lg p-3">
                            <p class="text-sm font-medium text-orange-900">{{ $conflict['facility']->name }}</p>
                            <p class="text-xs text-orange-700">{{ \Carbon\Carbon::parse($conflict['date'])->format('M j, Y') }}</p>
                            <p class="text-xs text-orange-600">Time overlap detected</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Overdue Payments -->
            @if($overduePayments->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Overdue Payments</h3>
                    <a href="{{ route('admin.payment-slips.index') }}" class="text-sm text-lgu-headline hover:underline">View All</a>
                </div>
                <div class="space-y-3">
                    @foreach($overduePayments as $payment)
                        <div class="border border-red-200 bg-red-50 rounded-lg p-3">
                            <p class="text-sm font-medium text-red-900">{{ $payment->booking->facility->name }}</p>
                            <p class="text-xs text-red-700">₱{{ number_format($payment->amount, 2) }}</p>
                            <p class="text-xs text-red-600">Due: {{ \Carbon\Carbon::parse($payment->due_date)->format('M j, Y') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Upcoming Reservations -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                    <a href="{{ route('calendar') }}" class="text-sm text-lgu-headline hover:underline">View Calendar</a>
                </div>
                @if($upcomingReservations->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingReservations as $reservation)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $reservation->event_name }}</p>
                                        <p class="text-xs text-gray-600">{{ $reservation->facility->name }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reservation->event_date)->format('M j, Y') }} at {{ $reservation->start_time }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4 text-sm">No upcoming events in the next 7 days</p>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                @if($recentActivity->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if($activity['icon'] === 'check-circle')
                                        <svg class="w-5 h-5 {{ $activity['color'] }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($activity['icon'] === 'currency-dollar')
                                        <svg class="w-5 h-5 {{ $activity['color'] }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 {{ $activity['color'] }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['message'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['details'] }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4 text-sm">No recent activity</p>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Auto-refresh functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time clock functionality for main dashboard
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

    // Auto-refresh critical stats every 30 seconds
    setInterval(function() {
        fetch('{{ route("admin.dashboard.quick-stats") }}')
            .then(response => response.json())
            .then(data => {
                // Update pending approvals count
                const pendingElement = document.querySelector('[data-stat="pending"]');
                if (pendingElement && data.pending_approvals !== undefined) {
                    pendingElement.textContent = data.pending_approvals;
                }
                
                // Update conflicts count
                const conflictsElement = document.querySelector('[data-stat="conflicts"]'); 
                if (conflictsElement && data.conflicts !== undefined) {
                    conflictsElement.textContent = data.conflicts;
                }
                
                // Update overdue payments count
                const overdueElement = document.querySelector('[data-stat="overdue"]');
                if (overdueElement && data.overdue_payments !== undefined) {
                    overdueElement.textContent = data.overdue_payments;
                }
                
                // Update today's events count  
                const todayElement = document.querySelector('[data-stat="today"]');
                if (todayElement && data.todays_events !== undefined) {
                    todayElement.textContent = data.todays_events;
                }
            })
            .catch(error => console.log('Stats refresh error:', error));
    }, 30000); // 30 seconds
});
</script>
@endsection
