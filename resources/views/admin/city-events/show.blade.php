@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">City Event Details</h1>
            <p class="text-gray-600 mt-1">Mayor authorized official city event</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.city-events.edit', $cityEvent->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-lgu-highlight text-lgu-button-text font-medium rounded-lg hover:bg-lgu-button transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Event
            </a>
            <a href="{{ route('admin.city-events.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <!-- Event Details Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">{{ str_replace('CITY EVENT: ', '', $cityEvent->event_name) }}</h2>
                <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                    Mayor Authorized
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Event Description -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Event Description</h3>
                <p class="text-gray-900 whitespace-pre-line">{{ $cityEvent->event_description }}</p>
            </div>

            <!-- Facility & Date Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Facility</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $cityEvent->facility->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">Capacity: {{ $cityEvent->facility->capacity ?? 'N/A' }} people</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Event Date</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($cityEvent->event_date)->format('l, F j, Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $cityEvent->start_time }} - {{ $cityEvent->end_time }}</p>
                </div>
            </div>

            <!-- Attendees & Contact -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Expected Attendees</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($cityEvent->expected_attendees) }} people</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Contact Information</h3>
                    <p class="text-sm text-gray-900">{{ $cityEvent->applicant_email }}</p>
                    <p class="text-sm text-gray-600">{{ $cityEvent->applicant_phone }}</p>
                </div>
            </div>

            <!-- Administrative Information -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Administrative Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Organized by</p>
                        <p class="text-sm font-medium text-gray-900">{{ $cityEvent->applicant_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created on</p>
                        <p class="text-sm font-medium text-gray-900">{{ $cityEvent->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
                @if($cityEvent->admin_notes)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Authorization Notes</p>
                        <p class="text-sm font-medium text-gray-900">{{ $cityEvent->admin_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
            <div class="flex items-center justify-between">
                <form action="{{ route('admin.city-events.destroy', $cityEvent->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this city event? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Event
                    </button>
                </form>
                
                <a href="{{ route('admin.city-events.edit', $cityEvent->id) }}" 
                   class="inline-flex items-center px-6 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-button transition-colors">
                    Edit Event Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

