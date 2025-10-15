@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
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
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 text-white">Create City Event</h1>
                    <p class="text-gray-200">Official city event authorized by the Mayor's Office</p>
                </div>
            </div>
            <a href="{{ route('admin.city-events.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-medium rounded-lg hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-blue-800">City Event Priority</p>
                <p class="text-sm text-blue-700 mt-1">City events automatically override any conflicting citizen bookings. Affected citizens will be notified of the cancellation.</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.city-events.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
            @csrf

            <!-- Mayor Authorization -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mayor's Authorization Reference <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="mayor_authorization" 
                       value="{{ old('mayor_authorization') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('mayor_authorization') border-red-500 @enderror"
                       placeholder="e.g., Mayor's Memo #2024-001"
                       required>
                @error('mayor_authorization')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Official authorization document reference from the Mayor's Office</p>
            </div>

            <!-- Event Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Event Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="event_name" 
                       value="{{ old('event_name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('event_name') border-red-500 @enderror"
                       placeholder="e.g., City Foundation Day Celebration"
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
                          placeholder="Describe the city event..."
                          required>{{ old('event_description') }}</textarea>
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
                        <option value="{{ $facility->facility_id }}" {{ old('facility_id') == $facility->facility_id ? 'selected' : '' }}>
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
                           value="{{ old('event_date') }}"
                           min="{{ date('Y-m-d') }}"
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
                           value="{{ old('start_time', '08:00') }}"
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
                           value="{{ old('end_time', '17:00') }}"
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
                       value="{{ old('expected_attendees') }}"
                       min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('expected_attendees') border-red-500 @enderror"
                       placeholder="e.g., 500"
                       required>
                @error('expected_attendees')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Optional Contact Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Contact Number (Optional)
                </label>
                <input type="text" 
                       name="contact_number" 
                       value="{{ old('contact_number') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent"
                       placeholder="e.g., (02) 1234-5678">
                <p class="mt-1 text-sm text-gray-500">Contact number for event coordination</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.city-events.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                    Create City Event
                </button>
            </div>
    </form>
</div>
@endsection

