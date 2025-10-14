@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit City Event</h1>
            <p class="text-gray-600 mt-1">Update official city event details</p>
        </div>
        <a href="{{ route('admin.city-events.show', $cityEvent->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Event
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Event Details</h2>
        </div>

        <form action="{{ route('admin.city-events.update', $cityEvent->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Event Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Event Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="event_name" 
                       value="{{ old('event_name', str_replace('CITY EVENT: ', '', $cityEvent->event_name)) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('event_name') border-red-500 @enderror"
                       required>
                @error('event_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Event Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Event Description <span class="text-red-500">*</span>
                </label>
                <textarea name="event_description" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('event_description') border-red-500 @enderror"
                          required>{{ old('event_description', $cityEvent->event_description) }}</textarea>
                @error('event_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Facility Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Facility <span class="text-red-500">*</span>
                </label>
                <select name="facility_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('facility_id') border-red-500 @enderror"
                        required>
                    <option value="">Select a facility</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->facility_id }}" 
                                {{ old('facility_id', $cityEvent->facility_id) == $facility->facility_id ? 'selected' : '' }}>
                            {{ $facility->name }} (Capacity: {{ $facility->capacity }})
                        </option>
                    @endforeach
                </select>
                @error('facility_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Event Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="event_date" 
                           value="{{ old('event_date', $cityEvent->event_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('event_date') border-red-500 @enderror"
                           required>
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Start Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="start_time" 
                           value="{{ old('start_time', $cityEvent->start_time) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('start_time') border-red-500 @enderror"
                           required>
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        End Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="end_time" 
                           value="{{ old('end_time', $cityEvent->end_time) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('end_time') border-red-500 @enderror"
                           required>
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Expected Attendees -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Expected Attendees <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="expected_attendees" 
                       value="{{ old('expected_attendees', $cityEvent->expected_attendees) }}"
                       min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('expected_attendees') border-red-500 @enderror"
                       required>
                @error('expected_attendees')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.city-events.show', $cityEvent->id) }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                    Update City Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

