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
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-1 text-white">Booking Approval Dashboard</h1>
                    <p class="text-gray-200 text-lg">Review and manage pending booking requests</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @if ($pendingBookings->isEmpty())
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        No pending booking requests found.
                    </td>
                </tr>
            @else
                @foreach($pendingBookings as $booking)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $booking->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->facility->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->user_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->start_time }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->end_time }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 flex items-center gap-2">
                            <form action="{{ route('bookings.approve', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">Approve</button>
                            </form>
                            
                            <form action="{{ route('bookings.reject', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection