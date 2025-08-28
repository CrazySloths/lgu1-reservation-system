@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Booking Approval Dashboard</h2>
        <p class="text-gray-200">Review and manage pending booking requests</p>
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