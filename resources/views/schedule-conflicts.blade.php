@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Enhanced Header Section -->
    <div class="bg-lgu-headline rounded-2xl p-6 text-white shadow-lgu-lg overflow-hidden relative">
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
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-lgu-highlight/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-6 h-6 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Schedule Conflicts</h1>
                    <p class="text-gray-200">Overlapping booking time slots requiring resolution</p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-medium rounded-lg hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    @if (isset($conflicts) && $conflicts->count() > 0)
        <div class="space-y-6">
            @foreach ($conflicts as $groupKey => $bookingsInGroup)
                @php
                    $firstBooking = $bookingsInGroup->first();
                @endphp
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-red-500">
                    <div class="bg-red-50 px-6 py-4 border-b border-red-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-red-900">
                                    {{ $firstBooking->facility->name ?? 'Unknown Facility' }}
                                </h3>
                                <p class="text-sm text-red-700 mt-1">
                                    Date: {{ $firstBooking->event_date->format('F j, Y') }}
                                </p>
                            </div>
                            <span class="inline-flex px-4 py-2 text-sm font-bold rounded-full bg-red-600 text-white">
                                CONFLICT
                            </span>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-600 mb-4">
                            The following {{ $bookingsInGroup->count() }} bookings have overlapping time slots:
                        </p>
                        
                        <div class="space-y-3">
                            @foreach ($bookingsInGroup as $booking)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="text-sm font-semibold text-gray-500">ID: #{{ $booking->id }}</span>
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                                    {{ $booking->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>
                                            
                                            <h4 class="font-semibold text-gray-900 mb-1">{{ $booking->event_name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-600 mb-2">
                                                <strong>Booked by:</strong> {{ $booking->user->name ?? $booking->user_name ?? 'N/A' }}
                                            </p>
                                            
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($booking->event_date->format('Y-m-d') . ' ' . $booking->start_time)->format('g:i A') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($booking->event_date->format('Y-m-d') . ' ' . $booking->end_time)->format('g:i A') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    {{ $booking->expected_attendees ?? 'N/A' }} attendees
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="ml-4">
                                            <a href="{{ route('admin.reservations.show', $booking->id) }}" 
                                               class="inline-flex items-center px-3 py-2 bg-lgu-highlight text-white text-sm font-medium rounded-lg hover:bg-lgu-button transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <strong>⚠️ Action Required:</strong> Please review and resolve this scheduling conflict by canceling, rescheduling, or confirming one of the bookings.
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-2xl font-semibold text-gray-900 mb-2">No Schedule Conflicts</h3>
            <p class="text-gray-600">All bookings are properly scheduled without any overlapping time slots.</p>
        </div>
    @endif
</div>
@endsection