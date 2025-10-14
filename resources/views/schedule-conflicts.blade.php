@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Schedule Conflicts</h1>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
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