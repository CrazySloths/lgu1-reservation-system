@extends('layouts.app')

@section('title', 'New Reservation - LGU Facility Reservation System')

<<<<<<< HEAD
@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">New Facility Reservation</h1>
            <p class="text-gray-600">Reserve public facilities and equipment for your events</p>
            <p class="text-sm text-blue-600 mt-2">Based on South Caloocan City General Services Department Requirements</p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center" id="progressStep1">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Facility & Details</span>
                </div>
                <div class="w-16 h-1 bg-gray-200" id="progressLine1"></div>
                <div class="flex items-center" id="progressStep2">
                    <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Documents</span>
                </div>
                <div class="w-16 h-1 bg-gray-200" id="progressLine2"></div>
                <div class="flex items-center" id="progressStep3">
                    <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Review & Submit</span>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Step 1: Facility Selection & Event Details -->
                <div id="step1" class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Facility Selection & Event Details</h2>
                    
                    <!-- Facility Selection with Pictures -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Facility</h3>
                            <div class="space-y-4">
                                @forelse($facilities as $facility)
                                    <label class="facility-option block border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all overflow-hidden">
                                        <div class="relative">
                                            @if($facility->image_path && Storage::disk('public')->exists($facility->image_path))
                                                <img src="{{ asset('storage/' . $facility->image_path) }}" 
                                                     alt="{{ $facility->name }}" 
                                                     class="w-full h-32 object-cover">
                                            @else
                                                <!-- Fallback placeholder if no image -->
                                                <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $facility->status === 'active' ? 'Available' : 'Unavailable' }}
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex items-center">
                                                <input type="radio" name="facility_id" value="{{ $facility->facility_id }}" class="mr-3 text-blue-600">
                                                <div class="flex-1">
                                                    <div class="font-medium text-gray-900">{{ $facility->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $facility->location }} - {{ ucfirst($facility->facility_type) }} facility</div>
                                                    <div class="text-xs text-blue-600 mt-1">Max capacity: {{ number_format($facility->capacity) }} people</div>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex justify-between items-center">
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-700">₱5,000</span>
                                                    <span class="text-gray-500">base (3hrs)</span>
                                                    <span class="text-xs text-gray-500 block">+₱2,000/hr extension</span>
                                                </div>
                                                <button type="button" class="text-blue-600 text-sm hover:underline" onclick="showFacilityMap('{{ $facility->name }}')">View Location</button>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No facilities available</h3>
                                        <p class="mt-1 text-sm text-gray-500">Please contact the administrator to add facilities.</p>
                                    </div>
                                @endforelse
    </div>
</div>

                        <!-- Equipment Selection -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Equipment</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg">
                                    <input type="checkbox" name="equipment[]" value="chairs" class="mr-3 text-blue-600">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">Chairs</div>
                                        <div class="text-sm text-gray-500">Additional seating arrangement</div>
                                    </div>
                                    <div class="text-right">
                                        <input type="number" name="chairs_quantity" min="0" max="500" placeholder="Qty" class="w-16 text-sm border border-gray-300 rounded px-2 py-1">
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border border-gray-300 rounded-lg">
                                    <input type="checkbox" name="equipment[]" value="tables" class="mr-3 text-blue-600">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">Tables</div>
                                        <div class="text-sm text-gray-500">Conference and dining tables</div>
                                    </div>
                                    <div class="text-right">
                                        <input type="number" name="tables_quantity" min="0" max="100" placeholder="Qty" class="w-16 text-sm border border-gray-300 rounded px-2 py-1">
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border border-gray-300 rounded-lg">
                                    <input type="checkbox" name="equipment[]" value="sound_system" class="mr-3 text-blue-600">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">Sound System</div>
                                        <div class="text-sm text-gray-500">Audio equipment with microphones</div>
                                    </div>
                                    <div class="text-right">
                                        <select name="sound_system_type" class="text-sm border border-gray-300 rounded px-2 py-1">
                                            <option value="">Select Type</option>
                                            <option value="basic">Basic</option>
                                            <option value="advanced">Advanced</option>
        </select>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Event Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="applicant_name" class="block text-sm font-medium text-gray-700 mb-2">Applicant Name <span class="text-red-500">*</span></label>
                            <input type="text" name="applicant_name" id="applicant_name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">Organization/Group</label>
                            <input type="text" name="organization" id="organization" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">Contact Number <span class="text-red-500">*</span></label>
                            <input type="tel" name="contact_number" id="contact_number" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="lg:col-span-2">
                            <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">Event Name <span class="text-red-500">*</span></label>
                            <input type="text" name="event_name" id="event_name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="lg:col-span-2">
                            <label for="event_description" class="block text-sm font-medium text-gray-700 mb-2">Event Description <span class="text-gray-500">(Optional)</span></label>
                            <textarea name="event_description" id="event_description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Provide additional details about your event (optional)"></textarea>
                        </div>
                    </div>

                    <!-- Date and Time Selection -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Schedule Your Event</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Custom Calendar -->
                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Event Date <span class="text-red-500">*</span></label>
                                <input type="date" name="event_date" id="event_date" required class="hidden" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                
                                <button type="button" id="datePickerButton" onclick="openDatePicker()" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left flex items-center justify-between">
                                    <span id="selectedDateDisplay" class="text-gray-500">Select a date</span>
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Custom Start Time -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                                <input type="time" name="start_time" id="start_time" required class="hidden">
                                
                                <button type="button" onclick="openTimePicker('start_time')" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left flex items-center justify-between">
                                    <span id="startTimeDisplay" class="text-gray-500">Select start time</span>
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Custom End Time -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                                <input type="time" name="end_time" id="end_time" required class="hidden">
                                
                                <button type="button" onclick="openTimePicker('end_time')" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left flex items-center justify-between">
                                    <span id="endTimeDisplay" class="text-gray-500">Select end time</span>
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- 3-Hour Minimum Notice -->
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-700">
                                    <span class="font-medium">Minimum Duration:</span> Events must be at least 3 hours long (based on LGU policy from interview findings)
                                </div>
                            </div>
                        </div>

                        <!-- Duration Display -->
                        <div id="durationDisplay" class="mt-3 hidden">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Event Duration:</span> <span id="eventDuration">-</span>
                            </div>
    </div>
    
                        <!-- Duration Warning -->
                        <div id="durationWarning" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg hidden">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="text-sm text-red-700">
                                    <span class="font-medium">Invalid Duration:</span> Events must be at least 3 hours long. Please adjust your end time.
                                </div>
                            </div>
=======
{{-- SweetAlert script to display success message from the server --}}
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>
@endif

<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-xl p-8 text-white shadow-lg">
        <h2 class="text-3xl font-extrabold mb-1">New Facility Reservation</h2>
        <p class="text-lg font-light text-gray-200">Select an available time slot below to make a reservation.</p>
    </div>
</div>

<div class="flex flex-col md:flex-row gap-6">
    <div class="w-full md:w-1/4 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <h3 class="text-lg font-bold mb-4">Available Facilities</h3>
        <ul id="facility-list" class="space-y-2">
            @foreach($facilities as $facility)
                <li class="bg-gray-100 p-3 rounded-md cursor-pointer hover:bg-gray-200 transition"
                    data-id="{{ $facility->facility_id }}"
                    data-name="{{ $facility->name }}">
                    {{ $facility->name }}
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="w-full md:w-3/4 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div id="calendar-container" class="hidden">
            <h4 class="text-lg font-bold mb-4 text-center">Calendar for: <span id="selected-facility-name" class="text-lgu-headline"></span></h4>
            <div id="calendar" class="p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-inner"></div>
        </div>
        <div id="instruction-message" class="text-center p-12 text-gray-500">
            Please select a facility to view its schedule.
        </div>
>>>>>>> 444b2ff3fb7b7f156e5f2f3ffdc9463d7609ccd2
    </div>
</div>

                    <!-- Participants -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="expected_participants" class="block text-sm font-medium text-gray-700 mb-2">Expected Number of Participants <span class="text-red-500">*</span></label>
                            <input type="number" name="expected_participants" id="expected_participants" required min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">Event Type <span class="text-red-500">*</span></label>
                            <select name="event_type" id="event_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Event Type</option>
                                <option value="government">Government Event</option>
                                <option value="community">Community Event</option>
                                <option value="educational">Educational Event</option>
                                <option value="religious">Religious Event</option>
                                <option value="sports">Sports Event</option>
                                <option value="cultural">Cultural Event</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Conflict Detection Alert -->
                    <div id="conflictAlert" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Schedule Conflict Detected</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p id="conflictMessage"></p>
                                    <div class="mt-2">
                                        <span class="font-medium">Suggested alternatives:</span>
                                        <ul id="alternativeSlots" class="mt-1 list-disc list-inside"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between">
                        <button type="button" onclick="cancelReservation()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                        <button type="button" id="continueToStep2" onclick="proceedToStep2()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Continue to Upload Documents</button>
                    </div>
                </div>
                
                <!-- Step 2: Document Upload -->
                <div id="step2" class="p-8 hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Required Documents</h2>
                    <p class="text-gray-600 mb-8">Based on LGU interview requirements, please upload the following documents:</p>
                    
                    <div class="space-y-6">
                        <!-- Enhanced ID Verification -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <div class="flex items-center mb-4">
                                <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-2 5v3"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">ID Verification <span class="text-red-500">*</span></h3>
                            </div>
                            
                            <!-- ID Type Selection -->
                            <div class="mb-6">
                                <label for="id_type" class="block text-sm font-medium text-gray-700 mb-2">Select ID Type <span class="text-red-500">*</span></label>
                                <select name="id_type" id="id_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Choose ID Type</option>
                                    <option value="government_id">Government-Issued ID</option>
                                    <option value="school_id">School ID (For Students)</option>
                                    <option value="drivers_license">Driver's License</option>
                                    <option value="passport">Passport</option>
                                    <option value="senior_id">Senior Citizen ID</option>
                                    <option value="pwd_id">PWD ID</option>
                                    <option value="voters_id">Voter's ID</option>
                                </select>
                            </div>
                            
                            <p class="text-sm text-gray-700 mb-6 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                <span class="font-medium">📋 Required uploads:</span> For complete verification, please upload three clear photos: front of ID, back of ID, and a selfie holding your ID.
                            </p>
                            
                            <!-- ID Front Upload -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ID Front <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-blue-300 rounded-lg p-4 text-center bg-white">
                                    <input type="file" name="id_front" id="id_front" accept="image/*" required class="hidden">
                                    <label for="id_front" class="cursor-pointer">
                                        <svg class="mx-auto h-10 w-10 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-2">
                                            <p class="text-sm font-medium text-blue-600">Upload Front of ID</p>
                                            <p class="text-xs text-gray-500">Clear photo, JPG/PNG up to 5MB</p>
                                        </div>
                                    </label>
                                </div>
                                <div id="idFrontPreview" class="mt-2 hidden">
                                    <div class="space-y-3">
                                        <div class="flex items-center p-2 bg-green-50 border border-green-200 rounded-lg">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="idFrontFileName" class="text-sm text-green-700 font-medium flex-1"></span>
                                            <button type="button" onclick="clearIdFront()" class="text-red-600 hover:text-red-800">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-2">Preview:</p>
                                            <img id="idFrontImage" src="" alt="ID Front Preview" class="w-full max-w-xs h-32 object-cover rounded-lg border border-gray-300">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ID Back Upload -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ID Back <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-blue-300 rounded-lg p-4 text-center bg-white">
                                    <input type="file" name="id_back" id="id_back" accept="image/*" required class="hidden">
                                    <label for="id_back" class="cursor-pointer">
                                        <svg class="mx-auto h-10 w-10 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-2">
                                            <p class="text-sm font-medium text-blue-600">Upload Back of ID</p>
                                            <p class="text-xs text-gray-500">Clear photo, JPG/PNG up to 5MB</p>
                                        </div>
                                    </label>
                                </div>
                                <div id="idBackPreview" class="mt-2 hidden">
                                    <div class="space-y-3">
                                        <div class="flex items-center p-2 bg-green-50 border border-green-200 rounded-lg">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="idBackFileName" class="text-sm text-green-700 font-medium flex-1"></span>
                                            <button type="button" onclick="clearIdBack()" class="text-red-600 hover:text-red-800">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-2">Preview:</p>
                                            <img id="idBackImage" src="" alt="ID Back Preview" class="w-full max-w-xs h-32 object-cover rounded-lg border border-gray-300">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Selfie with ID Upload -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Selfie with ID <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-blue-300 rounded-lg p-4 text-center bg-white">
                                    <input type="file" name="id_selfie" id="id_selfie" accept="image/*" required class="hidden">
                                    <label for="id_selfie" class="cursor-pointer">
                                        <svg class="mx-auto h-10 w-10 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-2">
                                            <p class="text-sm font-medium text-blue-600">Upload Selfie Holding ID</p>
                                            <p class="text-xs text-gray-500">Face and ID clearly visible, JPG/PNG up to 5MB</p>
                                        </div>
                                    </label>
                                </div>
                                <div id="idSelfiePreview" class="mt-2 hidden">
                                    <div class="space-y-3">
                                        <div class="flex items-center p-2 bg-green-50 border border-green-200 rounded-lg">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="idSelfieFileName" class="text-sm text-green-700 font-medium flex-1"></span>
                                            <button type="button" onclick="clearIdSelfie()" class="text-red-600 hover:text-red-800">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-2">Preview:</p>
                                            <img id="idSelfieImage" src="" alt="Selfie with ID Preview" class="w-full max-w-xs h-32 object-cover rounded-lg border border-gray-300">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Authorization Letter Upload (if applicable) -->
                        <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                            <div class="flex items-center mb-4">
                                <svg class="h-6 w-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Authorization Letter <span class="text-gray-500">(If representing organization)</span></h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4">Required only if you're applying on behalf of an organization or group.</p>
                            <div class="border-2 border-dashed border-yellow-300 rounded-lg p-6 text-center bg-white">
                                <input type="file" name="authorization_letter" id="authorization_letter" accept="image/*,.pdf" class="hidden">
                                <label for="authorization_letter" class="cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-yellow-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-yellow-600">Click to upload Authorization Letter</p>
                                        <p class="text-xs text-gray-500">PNG, JPG, or PDF up to 5MB</p>
                                    </div>
                                </label>
                            </div>
                            <div id="authLetterPreview" class="mt-4 hidden">
                                <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="authLetterFileName" class="text-sm text-green-700 font-medium"></span>
                                    <button type="button" onclick="clearAuthLetter()" class="ml-auto text-red-600 hover:text-red-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Event Proposal -->
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <div class="flex items-center mb-4">
                                <svg class="h-6 w-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Event Proposal/Details <span class="text-gray-500">(Optional)</span></h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4">Optional: Upload detailed description for formal events. Simple events like birthdays can skip this.</p>
                            <div class="border-2 border-dashed border-green-300 rounded-lg p-6 text-center bg-white">
                                <input type="file" name="event_proposal" id="event_proposal" accept="image/*,.pdf,.doc,.docx" class="hidden">
                                <label for="event_proposal" class="cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-green-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-green-600">Click to upload Event Proposal</p>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, or images up to 10MB</p>
                                    </div>
                                </label>
                            </div>
                            <div id="proposalPreview" class="mt-4 hidden">
                                <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="proposalFileName" class="text-sm text-green-700 font-medium"></span>
                                    <button type="button" onclick="clearProposal()" class="ml-auto text-red-600 hover:text-red-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Flexible Signature Options -->
                        <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                            <div class="flex items-center mb-4">
                                <svg class="h-6 w-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Signature <span class="text-red-500">*</span></h3>
                            </div>
                            
                            <!-- Signature Method Selection -->
                            <div class="mb-6">
                                <p class="text-sm text-gray-700 mb-4">Choose your preferred signature method:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <label class="flex items-center p-4 border-2 border-purple-200 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors" onclick="selectSignatureMethod('draw')">
                                        <input type="radio" name="signature_method" value="draw" class="mr-3 text-purple-600">
                                        <div>
                                            <div class="font-medium text-gray-900">Draw Signature</div>
                                            <div class="text-sm text-gray-600">Best for mobile/touch devices</div>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border-2 border-purple-200 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors" onclick="selectSignatureMethod('upload')">
                                        <input type="radio" name="signature_method" value="upload" class="mr-3 text-purple-600">
                                        <div>
                                            <div class="font-medium text-gray-900">Upload Image</div>
                                            <div class="text-sm text-gray-600">Best for desktop users</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Draw Signature Option -->
                            <div id="drawSignatureSection" class="hidden">
                                <p class="text-sm text-gray-700 mb-4">Draw your signature below using your finger/stylus or mouse:</p>
                                <div class="bg-white border-2 border-purple-300 rounded-lg">
                                    <canvas id="signatureCanvas" width="500" height="200" class="w-full h-32 cursor-crosshair rounded-lg"></canvas>
                                </div>
                                <div class="flex justify-between items-center mt-3">
                                    <button type="button" onclick="clearSignature()" class="text-sm text-purple-600 hover:text-purple-800">Clear Signature</button>
                                    <div id="signatureStatus" class="text-sm text-gray-500">Click and drag to sign</div>
                                </div>
                            </div>
                            
                            <!-- Upload Signature Option -->
                            <div id="uploadSignatureSection" class="hidden">
                                <p class="text-sm text-gray-700 mb-4">Upload a clear image of your signature on white background:</p>
                                <div class="border-2 border-dashed border-purple-300 rounded-lg p-6 text-center bg-white">
                                    <input type="file" name="signature_upload" id="signature_upload" accept="image/*" class="hidden">
                                    <label for="signature_upload" class="cursor-pointer">
                                        <svg class="mx-auto h-12 w-12 text-purple-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4">
                                            <p class="text-sm font-medium text-purple-600">Click to upload signature image</p>
                                            <p class="text-xs text-gray-500">Clear signature on white background, JPG/PNG up to 2MB</p>
                                        </div>
                                    </label>
                                </div>
                                <div id="signatureUploadPreview" class="mt-4 hidden">
                                    <div class="space-y-3">
                                        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="signatureUploadFileName" class="text-sm text-green-700 font-medium flex-1"></span>
                                            <button type="button" onclick="clearSignatureUpload()" class="text-red-600 hover:text-red-800">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-2">Preview:</p>
                                            <img id="signatureUploadImage" src="" alt="Signature Preview" class="w-full max-w-xs h-20 object-contain rounded-lg border border-gray-300 bg-white">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden input to store signature data -->
                            <input type="hidden" name="signature_data" id="signature_data" required>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8">
                        <button type="button" onclick="backToStep1()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">← Back to Details</button>
                        <button type="button" onclick="proceedToStep3()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Review & Submit →</button>
                    </div>
                </div>
                
                <!-- Step 3: Review & Submit -->
                <div id="step3" class="p-8 hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Review & Submit</h2>
                    <p class="text-gray-600 mb-8">Please review your application details before submitting.</p>
                    
                    <div id="reviewContent" class="bg-gray-50 rounded-lg p-6 mb-6">
                        <!-- Review content will be populated by JavaScript -->
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-700">
                                <span class="font-medium">Note:</span> After submission, your application will undergo review by LGU staff. You will receive updates via email and SMS.
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between">
                        <button type="button" onclick="backToStep2()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">← Back to Documents</button>
                        <button type="submit" class="px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Application
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Integration Points Notice -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">System Integration Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Real-time Schedule Conflict Detection
                </div>
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                    GIS Integration for Facility Locations
                </div>
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Automated Fee Calculation & Payment Integration
                </div>
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5h5l-5 5H8l5-5H8l5-5z"></path>
                    </svg>
                    Multi-level Approval Workflow (Staff → Supervisor → Department Head)
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Date Picker Modal -->
<div id="datePickerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-8 border max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Select Event Date</h3>
            <button type="button" onclick="closeDatePicker()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Calendar Header -->
        <div class="flex items-center justify-between mb-4">
            <button type="button" onclick="previousMonth()" class="p-2 hover:bg-gray-100 rounded-full">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <h4 id="calendarMonthYear" class="text-lg font-semibold text-gray-900"></h4>
            <button type="button" onclick="nextMonth()" class="p-2 hover:bg-gray-100 rounded-full">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
        
        <!-- Calendar Grid -->
        <div class="bg-gray-50 rounded-lg p-4">
            <!-- Days of week header -->
            <div class="grid grid-cols-7 gap-2 mb-3">
                <div class="text-center text-sm font-bold text-gray-700 py-2">Sun</div>
                <div class="text-center text-sm font-bold text-gray-700 py-2">Mon</div>
                <div class="text-center text-sm font-bold text-gray-700 py-2">Tue</div>
                <div class="text-center text-sm font-bold text-gray-700 py-2">Wed</div>
                <div class="text-center text-sm font-bold text-gray-700 py-2">Thu</div>
                <div class="text-center text-sm font-bold text-gray-700 py-2">Fri</div>
                <div class="text-center text-sm font-bold text-gray-700 py-2">Sat</div>
            </div>
            
            <!-- Calendar days -->
            <div id="calendarGrid" class="grid grid-cols-7 gap-2">
                <!-- Dynamic calendar days will be inserted here -->
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-between mt-6">
            <button type="button" onclick="closeDatePicker()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</button>
            <button type="button" onclick="goToToday()" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">Today</button>
        </div>
    </div>
</div>

<!-- Time Picker Modal -->
<div id="timePickerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-8 border max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900" id="timePickerTitle">Select Time</h3>
            <button type="button" onclick="closeTimePicker()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Analog Clock Interface -->
        <div class="text-center mb-6">
            <div class="relative mx-auto w-48 h-48 bg-white border-4 border-gray-300 rounded-full">
                <!-- Clock face -->
                <div class="absolute inset-4 border-2 border-gray-200 rounded-full">
                    <!-- Hour numbers -->
                    <div class="absolute inset-0">
                        <div class="absolute top-1 left-1/2 transform -translate-x-1/2 text-sm font-medium text-gray-700">12</div>
                        <div class="absolute top-1/2 right-1 transform -translate-y-1/2 text-sm font-medium text-gray-700">3</div>
                        <div class="absolute bottom-1 left-1/2 transform -translate-x-1/2 text-sm font-medium text-gray-700">6</div>
                        <div class="absolute top-1/2 left-1 transform -translate-y-1/2 text-sm font-medium text-gray-700">9</div>
                    </div>
                    
                    <!-- Clock hands -->
                    <div id="clockHands" class="absolute inset-0">
                        <!-- Hour hand -->
                        <div id="hourHand" class="absolute top-1/2 left-1/2 w-0.5 bg-gray-700 origin-bottom transform -translate-x-1/2 -translate-y-full" style="height: 25%; transform-origin: bottom center;"></div>
                        <!-- Minute hand -->
                        <div id="minuteHand" class="absolute top-1/2 left-1/2 w-0.5 bg-blue-600 origin-bottom transform -translate-x-1/2 -translate-y-full" style="height: 35%; transform-origin: bottom center;"></div>
                        <!-- Center dot -->
                        <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-gray-700 rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Digital Time Display -->
        <div class="text-center mb-6">
            <div class="text-3xl font-bold text-gray-900" id="digitalTimeDisplay">12:00</div>
            <div class="mt-2">
                <button type="button" id="amPmToggle" onclick="toggleAmPm()" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">AM</button>
            </div>
        </div>

        <!-- Time Controls -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hour</label>
                <select id="hourSelect" onchange="updateClock()" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12" selected>12</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Minute</label>
                <select id="minuteSelect" onchange="updateClock()" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="00" selected>00</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between">
            <button type="button" onclick="closeTimePicker()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</button>
            <button type="button" onclick="setSelectedTime()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Set Time</button>
        </div>
    </div>
</div>

<<<<<<< HEAD
=======
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
>>>>>>> 444b2ff3fb7b7f156e5f2f3ffdc9463d7609ccd2
<script>
// Global variables for time picker
let currentTimeField = '';
let selectedHour = 12;
let selectedMinute = 0;
let isAM = true;

// Global variables for date picker
let currentDate = new Date();
let selectedDate = null;
let minDate = new Date();
minDate.setDate(minDate.getDate() + 1); // Tomorrow

// Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
    // Facility selection highlighting
    document.querySelectorAll('input[name="facility_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove highlighting from all options
            document.querySelectorAll('.facility-option').forEach(option => {
                option.classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            // Highlight selected option
            if (this.checked) {
                this.closest('.facility-option').classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });

    // Equipment checkbox interactions
    document.querySelectorAll('input[name="equipment[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const quantityInput = this.closest('label').querySelector('input[type="number"], select');
            if (quantityInput) {
                quantityInput.disabled = !this.checked;
                if (!this.checked) {
                    quantityInput.value = '';
                }
            }
=======
        const calendarEl = document.getElementById('calendar');
        const facilityListItems = document.querySelectorAll('#facility-list li');
        const calendarContainer = document.getElementById('calendar-container');
        const bookingModal = document.getElementById('booking-modal');
        const instructionMessage = document.getElementById('instruction-message');
        const selectedFacilityNameEl = document.getElementById('selected-facility-name');
        
        let calendar = null;
        
        // Function to close the modal
        function closeModal() {
            bookingModal.classList.add('hidden');
        }

        // Attach event listener to the cancel button
        document.getElementById('cancel-booking').addEventListener('click', closeModal);

        // Facility list item click event listener
        facilityListItems.forEach(item => {
            item.addEventListener('click', function() {
                const facilityId = this.dataset.id;
                const facilityName = this.dataset.name;

                // Remove active class from all and add to the clicked item
                facilityListItems.forEach(li => li.classList.remove('bg-gray-200'));
                this.classList.add('bg-gray-200');

                // Update the calendar container and its title
                instructionMessage.classList.add('hidden');
                calendarContainer.classList.remove('hidden');
                selectedFacilityNameEl.textContent = facilityName;

                // Destroy the old calendar instance if it exists
                if (calendar) {
                    calendar.destroy();
                }

                // Initialize a new FullCalendar instance
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    slotMinTime: '08:00:00',
                    slotMaxTime: '18:00:00',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridDay,timeGridWeek,dayGridMonth'
                    },
                    selectable: true,
                    selectOverlap: false,
                    events: `/facilities/${facilityId}/events`,
                    eventColor: '#3B82F6',
                    eventClick: function(info) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Booked',
                            text: 'This time slot is already booked.'
                        });
                    },
                    select: function(info) {
                        const now = new Date();
                        const selectedStart = info.start;
                        const selectedEnd = info.end;

                        if (selectedStart < now) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid Time',
                                text: 'You cannot book a past time slot.'
                            });
                            calendar.unselect();
                            return;
                        }

                        const events = calendar.getEvents();
                        const isOverlap = events.some(event => {
                            return (info.start < event.end && info.end > event.start);
                        });

                        if (isOverlap) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Time Slot Taken',
                                text: 'This time slot is already booked.'
                            });
                            calendar.unselect();
                            return;
                        }

                        // Populate and show the booking modal
                        document.getElementById('modal-facility-id').value = facilityId;
                        document.getElementById('modal-start-time').value = info.startStr;
                        document.getElementById('modal-end-time').value = info.endStr;
                        document.getElementById('modal-start-display').innerText = new Date(info.startStr).toLocaleString();
                        document.getElementById('modal-end-display').innerText = new Date(info.endStr).toLocaleString();
                        document.getElementById('modal-facility-name').innerText = facilityName;

                        bookingModal.classList.remove('hidden');
                    }
                });
                calendar.render();

                // Show Swal confirmation
                Swal.fire({
                    icon: 'success',
                    title: 'Facility Selected',
                    text: `You have selected ${facilityName}.`,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
>>>>>>> 444b2ff3fb7b7f156e5f2f3ffdc9463d7609ccd2
        });
    });

    // Time validation listeners
    document.getElementById('start_time').addEventListener('change', validateDuration);
    document.getElementById('end_time').addEventListener('change', validateDuration);
});

// Time Picker Functions
function openTimePicker(fieldId) {
    currentTimeField = fieldId;
    const modal = document.getElementById('timePickerModal');
    const title = document.getElementById('timePickerTitle');
    
    // Set modal title
    title.textContent = fieldId === 'start_time' ? 'Select Start Time' : 'Select End Time';
    
    // Get current time from field if set
    const currentValue = document.getElementById(fieldId).value;
    if (currentValue) {
        const [hours, minutes] = currentValue.split(':');
        const hour24 = parseInt(hours);
        selectedHour = hour24 > 12 ? hour24 - 12 : (hour24 === 0 ? 12 : hour24);
        selectedMinute = parseInt(minutes);
        isAM = hour24 < 12;
    } else {
        // Default to 8:00 AM for start time, or 3 hours after start for end time
        if (fieldId === 'start_time') {
            selectedHour = 8;
            selectedMinute = 0;
            isAM = true;
        } else {
            // If start time is set, default end time to 3 hours later
            const startTime = document.getElementById('start_time').value;
            if (startTime) {
                const [startHours, startMinutes] = startTime.split(':');
                const startDate = new Date();
                startDate.setHours(parseInt(startHours), parseInt(startMinutes));
                const endDate = new Date(startDate.getTime() + (3 * 60 * 60 * 1000)); // Add 3 hours
                
                const endHour24 = endDate.getHours();
                selectedHour = endHour24 > 12 ? endHour24 - 12 : (endHour24 === 0 ? 12 : endHour24);
                selectedMinute = endDate.getMinutes();
                isAM = endHour24 < 12;
            } else {
                selectedHour = 11;
                selectedMinute = 0;
                isAM = true;
            }
        }
    }
    
    // Update selects and display
    document.getElementById('hourSelect').value = selectedHour;
    document.getElementById('minuteSelect').value = selectedMinute.toString().padStart(2, '0');
    updateClock();
    
    // Show modal
    modal.classList.remove('hidden');
}

function closeTimePicker() {
    document.getElementById('timePickerModal').classList.add('hidden');
}

function updateClock() {
    selectedHour = parseInt(document.getElementById('hourSelect').value);
    selectedMinute = parseInt(document.getElementById('minuteSelect').value);
    
    // Update digital display
    const displayHour = selectedHour.toString().padStart(2, '0');
    const displayMinute = selectedMinute.toString().padStart(2, '0');
    document.getElementById('digitalTimeDisplay').textContent = `${displayHour}:${displayMinute}`;
    
    // Update AM/PM button
    document.getElementById('amPmToggle').textContent = isAM ? 'AM' : 'PM';
    
    // Update clock hands
    const hourAngle = ((selectedHour % 12) * 30) + (selectedMinute * 0.5) - 90; // -90 to start from top
    const minuteAngle = (selectedMinute * 6) - 90; // -90 to start from top
    
    document.getElementById('hourHand').style.transform = `translate(-50%, -100%) rotate(${hourAngle}deg)`;
    document.getElementById('minuteHand').style.transform = `translate(-50%, -100%) rotate(${minuteAngle}deg)`;
}

function toggleAmPm() {
    isAM = !isAM;
    document.getElementById('amPmToggle').textContent = isAM ? 'AM' : 'PM';
}

function setSelectedTime() {
    if (currentTimeField) {
        // Convert to 24-hour format
        let hour24 = selectedHour;
        if (!isAM && selectedHour !== 12) {
            hour24 += 12;
        } else if (isAM && selectedHour === 12) {
            hour24 = 0;
        }
        
        const timeString = `${hour24.toString().padStart(2, '0')}:${selectedMinute.toString().padStart(2, '0')}`;
        document.getElementById(currentTimeField).value = timeString;
        
        // Update display text
        const displayHour = selectedHour.toString().padStart(2, '0');
        const displayMinute = selectedMinute.toString().padStart(2, '0');
        const displayTime = `${displayHour}:${displayMinute} ${isAM ? 'AM' : 'PM'}`;
        
        if (currentTimeField === 'start_time') {
            document.getElementById('startTimeDisplay').textContent = displayTime;
            document.getElementById('startTimeDisplay').classList.remove('text-gray-500');
            document.getElementById('startTimeDisplay').classList.add('text-gray-900');
        } else {
            document.getElementById('endTimeDisplay').textContent = displayTime;
            document.getElementById('endTimeDisplay').classList.remove('text-gray-500');
            document.getElementById('endTimeDisplay').classList.add('text-gray-900');
        }
        
        // Validate duration after setting time
        validateDuration();
    }
    
    closeTimePicker();
}

// Date Picker Functions
function openDatePicker() {
    const modal = document.getElementById('datePickerModal');
    currentDate = minDate; // Start from tomorrow
    renderCalendar();
    modal.classList.remove('hidden');
}

function closeDatePicker() {
    document.getElementById('datePickerModal').classList.add('hidden');
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

function goToToday() {
    currentDate = new Date(minDate); // Go to minimum date (tomorrow)
    renderCalendar();
}

function renderCalendar() {
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    
    const monthYear = document.getElementById('calendarMonthYear');
    monthYear.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    
    const calendarGrid = document.getElementById('calendarGrid');
    calendarGrid.innerHTML = '';
    
    // Get first day of month and number of days
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDay = firstDay.getDay();
    
    // Add empty cells for days before the first day of the month
    for (let i = 0; i < startingDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'p-2';
        calendarGrid.appendChild(emptyCell);
    }
    
    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayButton = document.createElement('button');
        dayButton.type = 'button';
        dayButton.textContent = day;
        dayButton.className = 'w-full h-12 text-center rounded text-lg font-semibold hover:bg-blue-100 transition-colors';
        
        const dayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
        
        // Check if day is in the past (before minimum date)
        if (dayDate < minDate) {
            dayButton.className += ' text-gray-400 cursor-not-allowed bg-gray-100';
            dayButton.disabled = true;
        } else {
            dayButton.className += ' text-gray-800 hover:bg-blue-100 bg-white border border-gray-200';
            dayButton.onclick = () => selectDate(dayDate);
            
            // Highlight selected date
            if (selectedDate && 
                dayDate.getDate() === selectedDate.getDate() &&
                dayDate.getMonth() === selectedDate.getMonth() &&
                dayDate.getFullYear() === selectedDate.getFullYear()) {
                dayButton.className += ' bg-blue-600 text-white hover:bg-blue-700 border-blue-600';
            }
            
            // Highlight today (if it's not in the past)
            const today = new Date();
            if (dayDate.getDate() === today.getDate() &&
                dayDate.getMonth() === today.getMonth() &&
                dayDate.getFullYear() === today.getFullYear() &&
                dayDate >= minDate) {
                dayButton.className += ' border-2 border-blue-500 bg-blue-50';
            }
        }
        
        calendarGrid.appendChild(dayButton);
    }
}

function selectDate(date) {
    selectedDate = new Date(date);
    const dateString = selectedDate.toISOString().split('T')[0];
    
    // Update hidden input and display
    document.getElementById('event_date').value = dateString;
    document.getElementById('selectedDateDisplay').textContent = selectedDate.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    document.getElementById('selectedDateDisplay').classList.remove('text-gray-500');
    document.getElementById('selectedDateDisplay').classList.add('text-gray-900');
    
    renderCalendar(); // Re-render to show selection
    closeDatePicker();
}

// Duration Validation (3-hour minimum based on interview findings)
function validateDuration() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const durationDisplay = document.getElementById('durationDisplay');
    const eventDuration = document.getElementById('eventDuration');
    const durationWarning = document.getElementById('durationWarning');
    
    if (startTime && endTime) {
        // Calculate duration
        const start = new Date(`2000-01-01T${startTime}:00`);
        const end = new Date(`2000-01-01T${endTime}:00`);
        
        // Handle next day scenarios
        if (end <= start) {
            end.setDate(end.getDate() + 1);
        }
        
        const durationMs = end.getTime() - start.getTime();
        const durationHours = durationMs / (1000 * 60 * 60);
        const durationMinutes = (durationMs % (1000 * 60 * 60)) / (1000 * 60);
        
        // Display duration
        const hours = Math.floor(durationHours);
        const minutes = Math.round(durationMinutes);
        let durationText = '';
        
        if (hours > 0) {
            durationText += `${hours} hour${hours !== 1 ? 's' : ''}`;
        }
        if (minutes > 0) {
            if (durationText) durationText += ' ';
            durationText += `${minutes} minute${minutes !== 1 ? 's' : ''}`;
        }
        
        eventDuration.textContent = durationText || '0 minutes';
        durationDisplay.classList.remove('hidden');
        
        // Check 3-hour minimum requirement
        if (durationHours < 3) {
            durationWarning.classList.remove('hidden');
            return false;
        } else {
            durationWarning.classList.add('hidden');
            return true;
        }
    } else {
        durationDisplay.classList.add('hidden');
        durationWarning.classList.add('hidden');
        return false;
    }
}

// Form validation with 3-hour minimum
function validateStep1() {
    const facility = document.querySelector('input[name="facility_id"]:checked');
    const name = document.getElementById('applicant_name').value;
    const eventName = document.getElementById('event_name').value;
    const eventDate = document.getElementById('event_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (!facility || !name || !eventName || !eventDate || !startTime || !endTime) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Fields Missing',
            text: 'Please fill in all required fields.',
            confirmButtonColor: '#3B82F6'
        });
        return false;
    }
    
    // Check 3-hour minimum
    if (!validateDuration()) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Duration',
            text: 'Events must be at least 3 hours long according to LGU policy.',
            confirmButtonColor: '#3B82F6'
        });
        return false;
    }
    
    return true;
}

// Conflict detection (enhanced with 3-hour consideration)
function checkScheduleConflict() {
    const facilityId = document.querySelector('input[name="facility_id"]:checked')?.value;
    const eventDate = document.getElementById('event_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (facilityId && eventDate && startTime && endTime && validateDuration()) {
        // Simulate API call to conflict detection service
        // This would integrate with your Schedule Conflict Alert microservice
        setTimeout(() => {
            // Simulate occasional conflicts
            const hasConflict = Math.random() < 0.2; // 20% chance of conflict
            
            if (hasConflict) {
                showConflictAlert();
            } else {
                hideConflictAlert();
            }
        }, 500);
    }
}

function showConflictAlert() {
    const alert = document.getElementById('conflictAlert');
    alert.classList.remove('hidden');
    
    document.getElementById('conflictMessage').textContent = 
        'The selected time slot conflicts with another reservation.';
    
    // Show alternative times (simulated - would come from API)
    const alternatives = document.getElementById('alternativeSlots');
    alternatives.innerHTML = `
        <li>6:00 AM - 9:00 AM (3 hours)</li>
        <li>2:00 PM - 5:00 PM (3 hours)</li>
        <li>6:00 PM - 9:00 PM (3 hours)</li>
        <li>Next available: Same time tomorrow</li>
    `;
}

function hideConflictAlert() {
    document.getElementById('conflictAlert').classList.add('hidden');
}

// Fee calculation (integrates with Payment Service microservice) - Updated with interview findings
function calculateFees() {
    const feeBreakdown = document.getElementById('feeBreakdown');
    const totalAmount = document.getElementById('totalAmount');
    
    // Get selected facility data
    const selectedFacility = document.querySelector('input[name="facility_id"]:checked');
    if (!selectedFacility) {
        if (feeBreakdown) {
            feeBreakdown.innerHTML = '<div class="text-red-500">Please select a facility first</div>';
        }
        if (totalAmount) {
            totalAmount.textContent = '₱0.00';
        }
        return;
    }

    // Get duration
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (!startTime || !endTime) {
        if (feeBreakdown) {
            feeBreakdown.innerHTML = '<div class="text-red-500">Please set event times first</div>';
        }
        if (totalAmount) {
            totalAmount.textContent = '₱0.00';
        }
        return;
    }

    // Calculate duration in hours
    const start = new Date(`2000-01-01T${startTime}:00`);
    const end = new Date(`2000-01-01T${endTime}:00`);
    if (end <= start) {
        end.setDate(end.getDate() + 1);
    }
    const durationHours = (end.getTime() - start.getTime()) / (1000 * 60 * 60);

    // Pricing based on interview findings
    let baseFee = 5000; // ₱5,000 for first 3 hours
    let extensionFee = 0;
    
    if (durationHours > 3) {
        const extraHours = Math.ceil(durationHours - 3); // Round up extra hours
        extensionFee = extraHours * 2000; // ₱2,000 per hour extension
    }

    let facilityFee = baseFee + extensionFee;
    
    // Calculate equipment fees
    let equipmentFee = 0;
    const chairs = document.querySelector('input[name="chairs_quantity"]').value || 0;
    const tables = document.querySelector('input[name="tables_quantity"]').value || 0;
    const soundSystem = document.querySelector('select[name="sound_system_type"]').value;
    
    if (chairs > 0) {
        equipmentFee += chairs * 5; // ₱5 per chair
    }
    if (tables > 0) {
        equipmentFee += tables * 25; // ₱25 per table
    }
    if (soundSystem) {
        equipmentFee += soundSystem === 'basic' ? 500 : 1000;
    }
    
    const total = facilityFee + equipmentFee;
    
    let feeBreakdownHtml = `
        <div class="flex justify-between"><span>Base rate (3 hours):</span><span>₱${baseFee.toLocaleString()}.00</span></div>
    `;
    
    if (extensionFee > 0) {
        const extraHours = Math.ceil(durationHours - 3);
        feeBreakdownHtml += `
            <div class="flex justify-between"><span>Extension (${extraHours} hour${extraHours > 1 ? 's' : ''}):</span><span>₱${extensionFee.toLocaleString()}.00</span></div>
        `;
    }
    
    feeBreakdownHtml += `
        <div class="flex justify-between"><span>Equipment rental:</span><span>₱${equipmentFee.toLocaleString()}.00</span></div>
    `;
    
    if (feeBreakdown) {
        feeBreakdown.innerHTML = feeBreakdownHtml;
    }
    if (totalAmount) {
        totalAmount.textContent = `₱${total.toLocaleString()}.00`;
    }
}

// Enhanced form interactions
document.addEventListener('DOMContentLoaded', function() {
    // Setup conflict detection with delay to avoid too many calls
    let conflictCheckTimeout;
    
    ['event_date', 'start_time', 'end_time'].forEach(fieldId => {
        document.getElementById(fieldId).addEventListener('change', function() {
            clearTimeout(conflictCheckTimeout);
            conflictCheckTimeout = setTimeout(checkScheduleConflict, 1000);
        });
    });
    
    document.querySelectorAll('input[name="facility_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            clearTimeout(conflictCheckTimeout);
            conflictCheckTimeout = setTimeout(checkScheduleConflict, 500);
        });
    });

    // Initialize file upload handlers
    initializeFileUploads();
    
    // Initialize signature canvas
    initializeSignatureCanvas();
});

// Multi-step form navigation
let currentStep = 1;

function proceedToStep2() {
    if (validateStep1()) {
        // Hide step 1, show step 2
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');
        
        // Update progress
        updateProgressSteps(2);
        currentStep = 2;
        
        // Scroll to top
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
}

function backToStep1() {
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step1').classList.remove('hidden');
    updateProgressSteps(1);
    currentStep = 1;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function proceedToStep3() {
    if (validateStep2()) {
        // Hide step 2, show step 3
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step3').classList.remove('hidden');
        
        // Update progress
        updateProgressSteps(3);
        currentStep = 3;
        
        // Populate review content
        populateReviewContent();
        
        // Scroll to top
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
}

function backToStep2() {
    document.getElementById('step3').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
    updateProgressSteps(2);
    currentStep = 2;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function cancelReservation() {
    Swal.fire({
        title: 'Cancel Reservation?',
        text: 'Are you sure you want to cancel your reservation application? All entered data will be lost.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Cancel',
        cancelButtonText: 'Keep Editing'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/dashboard';
        }
    });
}

function updateProgressSteps(activeStep) {
    // Reset all steps
    for (let i = 1; i <= 3; i++) {
        const step = document.getElementById(`progressStep${i}`);
        const line = document.getElementById(`progressLine${i}`);
        const circle = step.querySelector('div');
        const text = step.querySelector('span');
        
        if (i < activeStep) {
            // Completed steps
            circle.className = 'w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium';
            text.className = 'ml-2 text-sm font-medium text-green-600';
            if (line) line.className = 'w-16 h-1 bg-green-600';
        } else if (i === activeStep) {
            // Current step
            circle.className = 'w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium';
            text.className = 'ml-2 text-sm font-medium text-blue-600';
            if (line) line.className = 'w-16 h-1 bg-gray-200';
        } else {
            // Future steps
            circle.className = 'w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium';
            text.className = 'ml-2 text-sm font-medium text-gray-500';
            if (line) line.className = 'w-16 h-1 bg-gray-200';
        }
    }
}

// Enhanced Step 1 validation
function validateStep1() {
    const facility = document.querySelector('input[name="facility_id"]:checked');
    const name = document.getElementById('applicant_name').value.trim();
    const contact = document.getElementById('contact_number').value.trim();
    const email = document.getElementById('email').value.trim();
    const eventName = document.getElementById('event_name').value.trim();
    const eventDate = document.getElementById('event_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const participants = document.getElementById('expected_participants').value;
    const eventType = document.getElementById('event_type').value;
    
    let errors = [];
    
    if (!facility) errors.push('Please select a facility');
    if (!name) errors.push('Please enter applicant name');
    if (!contact) errors.push('Please enter contact number');
    if (!email) errors.push('Please enter email address');
    if (!eventName) errors.push('Please enter event name');
    if (!eventDate) errors.push('Please select event date');
    if (!startTime) errors.push('Please select start time');
    if (!endTime) errors.push('Please select end time');
    if (!participants) errors.push('Please enter expected participants');
    if (!eventType) errors.push('Please select event type');
    
    // Check 3-hour minimum
    if (startTime && endTime && !validateDuration()) {
        errors.push('Events must be at least 3 hours long');
    }
    
    if (errors.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Please Complete Required Fields',
            html: '• ' + errors.join('<br>• '),
            confirmButtonColor: '#3B82F6'
        });
        return false;
    }
    
    return true;
}

// Step 2 validation
function validateStep2() {
    const idType = document.getElementById('id_type').value;
    const idFront = document.getElementById('id_front').files[0];
    const idBack = document.getElementById('id_back').files[0];
    const idSelfie = document.getElementById('id_selfie').files[0];
    const signatureMethod = document.querySelector('input[name="signature_method"]:checked')?.value;
    const signatureData = document.getElementById('signature_data').value;
    
    let errors = [];
    
    // ID verification requirements
    if (!idType) errors.push('Please select an ID type');
    if (!idFront) errors.push('Please upload the front of your ID');
    if (!idBack) errors.push('Please upload the back of your ID');
    if (!idSelfie) errors.push('Please upload a selfie holding your ID');
    
    // Signature requirements
    if (!signatureMethod) errors.push('Please choose a signature method');
    if (!signatureData) {
        if (signatureMethod === 'draw') {
            errors.push('Please draw your signature');
        } else if (signatureMethod === 'upload') {
            errors.push('Please upload your signature image');
        } else {
            errors.push('Please provide your signature');
        }
    }
    
    if (errors.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Please Complete Required Documents',
            html: '• ' + errors.join('<br>• '),
            confirmButtonColor: '#3B82F6'
        });
        return false;
    }
    
    return true;
}

// File upload initialization and handlers
function initializeFileUploads() {
    // ID Front upload
    document.getElementById('id_front').addEventListener('change', function(e) {
        handleFileUpload(e, 'idFrontPreview', 'idFrontFileName', 5 * 1024 * 1024);
    });
    
    // ID Back upload
    document.getElementById('id_back').addEventListener('change', function(e) {
        handleFileUpload(e, 'idBackPreview', 'idBackFileName', 5 * 1024 * 1024);
    });
    
    // ID Selfie upload
    document.getElementById('id_selfie').addEventListener('change', function(e) {
        handleFileUpload(e, 'idSelfiePreview', 'idSelfieFileName', 5 * 1024 * 1024);
    });
    
    // Authorization letter upload
    document.getElementById('authorization_letter').addEventListener('change', function(e) {
        handleFileUpload(e, 'authLetterPreview', 'authLetterFileName', 5 * 1024 * 1024);
    });
    
    // Event proposal upload
    document.getElementById('event_proposal').addEventListener('change', function(e) {
        handleFileUpload(e, 'proposalPreview', 'proposalFileName', 10 * 1024 * 1024);
    });
    
    // Signature upload
    document.getElementById('signature_upload').addEventListener('change', function(e) {
        handleSignatureUpload(e);
    });
}

function handleFileUpload(event, previewId, fileNameId, maxSize) {
    const file = event.target.files[0];
    const preview = document.getElementById(previewId);
    const fileName = document.getElementById(fileNameId);
    
    if (file) {
        // Validate file size
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: `File size must be less than ${maxSize / (1024 * 1024)}MB`,
                confirmButtonColor: '#3B82F6'
            });
            event.target.value = '';
            return;
        }
        
        // Validate file type for images
        if (!file.type.startsWith('image/')) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload an image file (JPG, PNG, etc.)',
                confirmButtonColor: '#3B82F6'
            });
            event.target.value = '';
            return;
        }
        
        // Show file name and preview
        fileName.textContent = file.name;
        preview.classList.remove('hidden');
        
        // Show image preview if it's an ID upload
        const imageId = previewId.replace('Preview', 'Image');
        const imageElement = document.getElementById(imageId);
        
        if (imageElement) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imageElement.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    } else {
        preview.classList.add('hidden');
    }
}

// Clear ID upload functions
function clearIdFront() {
    document.getElementById('id_front').value = '';
    document.getElementById('idFrontPreview').classList.add('hidden');
    document.getElementById('idFrontImage').src = '';
}

function clearIdBack() {
    document.getElementById('id_back').value = '';
    document.getElementById('idBackPreview').classList.add('hidden');
    document.getElementById('idBackImage').src = '';
}

function clearIdSelfie() {
    document.getElementById('id_selfie').value = '';
    document.getElementById('idSelfiePreview').classList.add('hidden');
    document.getElementById('idSelfieImage').src = '';
}

function clearAuthLetter() {
    document.getElementById('authorization_letter').value = '';
    document.getElementById('authLetterPreview').classList.add('hidden');
}

function clearProposal() {
    document.getElementById('event_proposal').value = '';
    document.getElementById('proposalPreview').classList.add('hidden');
}

// Digital signature canvas functionality
let signatureCanvas, signatureCtx;
let isDrawing = false;
let hasSignature = false;

function initializeSignatureCanvas() {
    signatureCanvas = document.getElementById('signatureCanvas');
    signatureCtx = signatureCanvas.getContext('2d');
    
    // Set canvas size for proper drawing
    const rect = signatureCanvas.getBoundingClientRect();
    signatureCanvas.width = rect.width * 2;  // High DPI
    signatureCanvas.height = rect.height * 2;
    signatureCtx.scale(2, 2);
    
    // Set drawing styles
    signatureCtx.strokeStyle = '#000';
    signatureCtx.lineWidth = 2;
    signatureCtx.lineCap = 'round';
    
    // Mouse events
    signatureCanvas.addEventListener('mousedown', startDrawing);
    signatureCanvas.addEventListener('mousemove', draw);
    signatureCanvas.addEventListener('mouseup', stopDrawing);
    signatureCanvas.addEventListener('mouseout', stopDrawing);
    
    // Touch events for mobile
    signatureCanvas.addEventListener('touchstart', handleTouch);
    signatureCanvas.addEventListener('touchmove', handleTouch);
    signatureCanvas.addEventListener('touchend', stopDrawing);
}

function startDrawing(e) {
    isDrawing = true;
    const rect = signatureCanvas.getBoundingClientRect();
    signatureCtx.beginPath();
    signatureCtx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
}

function draw(e) {
    if (!isDrawing) return;
    
    const rect = signatureCanvas.getBoundingClientRect();
    signatureCtx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
    signatureCtx.stroke();
    
    if (!hasSignature) {
        hasSignature = true;
        document.getElementById('signatureStatus').textContent = 'Signature captured';
        document.getElementById('signature_data').value = signatureCanvas.toDataURL();
    }
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        document.getElementById('signature_data').value = signatureCanvas.toDataURL();
    }
}

function handleTouch(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    signatureCanvas.dispatchEvent(mouseEvent);
}

// Signature method selection
function selectSignatureMethod(method) {
    const drawSection = document.getElementById('drawSignatureSection');
    const uploadSection = document.getElementById('uploadSignatureSection');
    const radioButtons = document.querySelectorAll('input[name="signature_method"]');
    
    // Update radio button
    radioButtons.forEach(radio => {
        if (radio.value === method) {
            radio.checked = true;
            radio.closest('label').classList.add('border-purple-400', 'bg-purple-50');
        } else {
            radio.checked = false;
            radio.closest('label').classList.remove('border-purple-400', 'bg-purple-50');
        }
    });
    
    // Show/hide appropriate sections
    if (method === 'draw') {
        drawSection.classList.remove('hidden');
        uploadSection.classList.add('hidden');
        // Clear upload if switching methods
        clearSignatureUpload();
        // Re-initialize canvas if needed
        setTimeout(() => {
            if (!signatureCanvas) {
                initializeSignatureCanvas();
            }
        }, 100);
    } else {
        drawSection.classList.add('hidden');
        uploadSection.classList.remove('hidden');
        // Clear canvas if switching methods
        if (signatureCanvas) {
            clearSignature();
        }
    }
}

// Signature upload handler
function handleSignatureUpload(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('signatureUploadPreview');
    const fileName = document.getElementById('signatureUploadFileName');
    const imageElement = document.getElementById('signatureUploadImage');
    const maxSize = 2 * 1024 * 1024; // 2MB for signature
    
    if (file) {
        // Validate file size
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Signature image must be less than 2MB',
                confirmButtonColor: '#3B82F6'
            });
            event.target.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload an image file (JPG, PNG, etc.)',
                confirmButtonColor: '#3B82F6'
            });
            event.target.value = '';
            return;
        }
        
        // Convert file to base64 and store, show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('signature_data').value = e.target.result;
            fileName.textContent = file.name;
            imageElement.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
        document.getElementById('signature_data').value = '';
    }
}

function clearSignature() {
    if (signatureCanvas && signatureCtx) {
        signatureCtx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
        document.getElementById('signature_data').value = '';
        document.getElementById('signatureStatus').textContent = 'Click and drag to sign';
        hasSignature = false;
    }
}

function clearSignatureUpload() {
    document.getElementById('signature_upload').value = '';
    document.getElementById('signatureUploadPreview').classList.add('hidden');
    document.getElementById('signatureUploadImage').src = '';
    document.getElementById('signature_data').value = '';
}

// Review content population
function populateReviewContent() {
    const facility = document.querySelector('input[name="facility_id"]:checked');
    const facilityName = facility ? facility.closest('.facility-option').querySelector('.font-medium').textContent : 'None';
    
    const reviewContent = document.getElementById('reviewContent');
    reviewContent.innerHTML = `
        <h3 class="text-lg font-semibold mb-4">Application Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Applicant Information</h4>
                <div class="text-sm space-y-1">
                    <div><span class="text-gray-600">Name:</span> ${document.getElementById('applicant_name').value}</div>
                    <div><span class="text-gray-600">Organization:</span> ${document.getElementById('organization').value || 'N/A'}</div>
                    <div><span class="text-gray-600">Contact:</span> ${document.getElementById('contact_number').value}</div>
                    <div><span class="text-gray-600">Email:</span> ${document.getElementById('email').value}</div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Event Information</h4>
                <div class="text-sm space-y-1">
                    <div><span class="text-gray-600">Event:</span> ${document.getElementById('event_name').value}</div>
                    <div><span class="text-gray-600">Type:</span> ${document.getElementById('event_type').value}</div>
                    <div><span class="text-gray-600">Facility:</span> ${facilityName}</div>
                    <div><span class="text-gray-600">Date:</span> ${document.getElementById('selectedDateDisplay').textContent}</div>
                    <div><span class="text-gray-600">Time:</span> ${document.getElementById('startTimeDisplay').textContent} - ${document.getElementById('endTimeDisplay').textContent}</div>
                    <div><span class="text-gray-600">Participants:</span> ${document.getElementById('expected_participants').value}</div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <h4 class="font-medium text-gray-900 mb-2">ID Verification</h4>
            <div class="text-sm space-y-1 mb-4">
                <div><span class="text-gray-600">ID Type:</span> ${document.getElementById('id_type').options[document.getElementById('id_type').selectedIndex]?.text || 'Not selected'}</div>
                <div>✅ ID Front: ${document.getElementById('id_front').files[0]?.name || 'Not uploaded'}</div>
                <div>✅ ID Back: ${document.getElementById('id_back').files[0]?.name || 'Not uploaded'}</div>
                <div>✅ Selfie with ID: ${document.getElementById('id_selfie').files[0]?.name || 'Not uploaded'}</div>
            </div>
            
            <h4 class="font-medium text-gray-900 mb-2">Documents Uploaded</h4>
            <div class="text-sm space-y-1 mb-4">
                <div>${document.getElementById('authorization_letter').files[0] ? '✅' : '⚪'} Authorization Letter: ${document.getElementById('authorization_letter').files[0]?.name || 'Not required'}</div>
                <div>${document.getElementById('event_proposal').files[0] ? '✅' : '⚪'} Event Proposal: ${document.getElementById('event_proposal').files[0]?.name || 'Optional - not provided'}</div>
            </div>
            
            <h4 class="font-medium text-gray-900 mb-2">Signature</h4>
            <div class="text-sm space-y-1">
                <div><span class="text-gray-600">Method:</span> ${document.querySelector('input[name="signature_method"]:checked')?.value === 'draw' ? 'Digital Drawing' : document.querySelector('input[name="signature_method"]:checked')?.value === 'upload' ? 'Image Upload' : 'Not selected'}</div>
                <div>${document.getElementById('signature_data').value ? '✅' : '❌'} Signature: ${document.getElementById('signature_data').value ? 'Provided' : 'Required'}</div>
            </div>
        </div>
    `;
}
</script>
<<<<<<< HEAD

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
=======
>>>>>>> 444b2ff3fb7b7f156e5f2f3ffdc9463d7609ccd2
@endsection