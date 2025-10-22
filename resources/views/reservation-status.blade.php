@extends('layouts.app')

@section('content')

<div class="mb-6">
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
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-1 text-white">My Reservations</h1>
                    <p class="text-gray-200 text-lg">View the status of your submitted facility booking requests</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden p-8">
    @if($bookings->isEmpty())
        <p class="text-gray-600">You have no reservations yet.</p>
    @else
        <table class="min-w-full bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Booking ID</th>
                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Facility</th>
                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Start Time</th>
                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">End Time</th>
                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-sm text-gray-800">{{ $booking->id }}</td>
                        <td class="py-3 px-4 text-sm text-gray-800">{{ $booking->facility->name }}</td>
                        <td class="py-3 px-4 text-sm text-gray-800">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</td>
                        <td class="py-3 px-4 text-sm text-gray-800">{{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</td>
                        <td class="py-3 px-4 text-sm font-semibold">
                            @if($booking->status === 'approved')
                                <span class="text-green-600">{{ ucfirst($booking->status) }}</span>
                            @elseif($booking->status === 'pending')
                                <span class="text-yellow-600">{{ ucfirst($booking->status) }}</span>
                            @else
                                <span class="text-red-600">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection