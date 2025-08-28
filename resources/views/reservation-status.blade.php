@extends('layouts.app')

@section('content')

<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-xl p-8 text-white shadow-lg">
        <h2 class="text-3xl font-extrabold mb-1">My Reservations</h2>
        <p class="text-lg font-light text-gray-200">View the status of your submitted facility booking requests.</p>
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
                        <td class="py-3 px-4 text-sm text-gray-800">{{ $booking->start_time }}</td>
                        <td class="py-3 px-4 text-sm text-gray-800">{{ $booking->end_time }}</td>
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