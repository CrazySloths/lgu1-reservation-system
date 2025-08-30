@extends('citizen.layouts.app')

@section('title', 'Make a Reservation - LGU1 Citizen Portal')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Make a Reservation</h1>
                <p class="text-gray-600 mt-1">Select a facility and book your event</p>
            </div>
            <div class="flex items-center text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Reservations require approval
            </div>
        </div>
    </div>

    <!-- Verification Check -->
    @if(!$user->isVerified())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Account Verification Required</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>Your account must be verified before you can make reservations. Please wait for staff approval or contact our office for assistance.</p>
                </div>
                <div class="mt-3">
                    <a href="{{ route('citizen.dashboard') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($user->isVerified())
    <!-- Progress Indicator -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <!-- Step 1 -->
            <div id="progressStep1" class="flex items-center">
                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                    1
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900">Facility & Details</span>
            </div>
            
            <!-- Progress Line 1 -->
            <div id="progressLine1" class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            
            <!-- Step 2 -->
            <div id="progressStep2" class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                    2
                </div>
                <span class="ml-2 text-sm text-gray-600">Required Documents</span>
            </div>
            
            <!-- Progress Line 2 -->
            <div id="progressLine2" class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            
            <!-- Step 3 -->
            <div id="progressStep3" class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                    3
                </div>
                <span class="ml-2 text-sm text-gray-600">Review & Submit</span>
            </div>
        </div>
    </div>

    <!-- Multi-Step Form -->
    <form id="reservationForm" method="POST" action="{{ route('citizen.reservations.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Step 1: Facility & Details -->
        <div id="step1" class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Step 1: Select Facility & Event Details</h2>
            
            <!-- Available Facilities -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Facilities</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($facilities as $facility)
                    <div class="facility-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-blue-400 hover:shadow-md" 
                         data-facility-id="{{ $facility->facility_id }}" 
                         data-facility-name="{{ $facility->name }}"
                         data-base-rate="{{ $facility->daily_rate }}"
                         data-hourly-rate="{{ $facility->hourly_rate }}">
                        
                        <!-- Facility Image -->
                        @if($facility->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($facility->image_path))
                            <img src="{{ asset('storage/' . $facility->image_path) }}" 
                                 alt="{{ $facility->name }}" 
                                 class="w-full h-40 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-40 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                <i class="fas fa-building text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        
                        <!-- Facility Details -->
                        <div class="space-y-2">
                            <h4 class="font-bold text-lg text-gray-800">{{ $facility->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $facility->description }}</p>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $facility->location }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users mr-1"></i>
                                Capacity: {{ $facility->capacity }} people
                            </div>
                            <div class="text-sm font-medium text-blue-600">
                                Type: {{ ucfirst($facility->facility_type) }}
                            </div>
                            <div class="text-lg font-bold text-green-600">
                                ₱{{ number_format($facility->daily_rate, 2) }} base (3hrs) +₱{{ number_format($facility->hourly_rate, 2) }}/hr extension
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-building text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">No facilities available at the moment</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Selected Facility Display -->
                <div id="selectedFacilityDisplay" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                    <h4 class="font-semibold text-blue-800 mb-2">Selected Facility:</h4>
                    <p id="selectedFacilityName" class="text-blue-700"></p>
                </div>
                
                <!-- Hidden input for selected facility -->
                <input type="hidden" id="selected_facility" name="facility_name" required>
            </div>

            <!-- Event Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Applicant Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Applicant Information</h3>
                    
                    <div class="mb-4">
                        <label for="applicant_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="applicant_name" name="applicant_name" value="{{ $user->name }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    
                    <div class="mb-4">
                        <label for="applicant_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="applicant_email" name="applicant_email" value="{{ $user->email }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    
                    <div class="mb-4">
                        <label for="applicant_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="applicant_phone" name="applicant_phone" value="{{ $user->phone_number }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    
                    <div class="mb-4">
                        <label for="applicant_address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="applicant_address" name="applicant_address" rows="3" readonly
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">{{ $user->address }}</textarea>
                    </div>
                </div>

                <!-- Event Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Information</h3>
                    
                    <div class="mb-4">
                        <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">Event Name <span class="text-red-500">*</span></label>
                        <input type="text" id="event_name" name="event_name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Birthday Party, Meeting, Conference">
                    </div>
                    
                    <div class="mb-4">
                        <label for="event_description" class="block text-sm font-medium text-gray-700 mb-2">Event Description (Optional)</label>
                        <textarea id="event_description" name="event_description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Brief description of your event (optional)"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="expected_attendees" class="block text-sm font-medium text-gray-700 mb-2">Expected Number of Attendees <span class="text-red-500">*</span></label>
                        <input type="number" id="expected_attendees" name="expected_attendees" required min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Number of expected attendees">
                    </div>
                </div>
            </div>

            <!-- Date and Time Selection -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Date & Time</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Event Date <span class="text-red-500">*</span></label>
                        <button type="button" id="datePickerButton" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-left focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-50"
                                onclick="openDatePicker()">
                            Select Date
                        </button>
                        <input type="hidden" id="event_date" name="event_date" required>
                    </div>
                    
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                        <button type="button" id="startTimeButton" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-left focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-50"
                                onclick="openTimePicker('start')">
                            <i class="fas fa-clock mr-2"></i>Select Start Time
                        </button>
                        <input type="hidden" id="start_time" name="start_time" required>
                    </div>
                    
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                        <button type="button" id="endTimeButton" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-left focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-50"
                                onclick="openTimePicker('end')">
                            <i class="fas fa-clock mr-2"></i>Select End Time
                        </button>
                        <input type="hidden" id="end_time" name="end_time" required>
                    </div>
                </div>
                
                <!-- Duration Warning -->
                <div id="durationWarning" class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                        <span class="text-sm text-yellow-700">Minimum event duration is 3 hours</span>
                    </div>
                </div>
            </div>

            <!-- Fee Summary -->
            <div id="feesSummary" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Fee Summary</h3>
                <div id="feesBreakdown" class="text-sm text-green-700"></div>
            </div>

            <!-- Step 1 Navigation -->
            <div class="flex justify-end">
                <button type="button" onclick="proceedToStep2()" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Continue to Upload Documents
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Additional steps (2 & 3) would be added here similar to the original new-reservation form -->
        <!-- For now, showing a simplified version -->
        
    </form>
    @endif
</div>

<!-- Date Picker Modal -->
<div id="datePickerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Select Date</h3>
                <button type="button" onclick="closeDatePicker()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Calendar will be rendered here by JavaScript -->
            <div id="calendarContainer"></div>
            
            <div class="mt-4 flex justify-between">
                <button type="button" onclick="goToToday()" class="text-blue-600 hover:text-blue-800 font-medium">Today</button>
                <button type="button" onclick="closeDatePicker()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Time Picker Modal -->
<div id="timePickerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
            <!-- Time picker content will be added by JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
// Add all the JavaScript functionality from the original new-reservation form
// This includes facility selection, time picker, date picker, validation, etc.

let selectedFacility = null;
let currentTimeType = null; // 'start' or 'end'

// Facility Selection
document.querySelectorAll('.facility-card').forEach(card => {
    card.addEventListener('click', function() {
        // Remove previous selection
        document.querySelectorAll('.facility-card').forEach(c => {
            c.classList.remove('border-blue-500', 'bg-blue-50');
            c.classList.add('border-gray-200');
        });
        
        // Add selection to clicked card
        this.classList.remove('border-gray-200');
        this.classList.add('border-blue-500', 'bg-blue-50');
        
        // Update selected facility
        selectedFacility = {
            id: this.dataset.facilityId,
            name: this.dataset.facilityName,
            baseRate: parseFloat(this.dataset.baseRate),
            hourlyRate: parseFloat(this.dataset.hourlyRate)
        };
        
        // Update hidden input and display
        document.getElementById('selected_facility').value = selectedFacility.name;
        document.getElementById('selectedFacilityName').textContent = selectedFacility.name;
        document.getElementById('selectedFacilityDisplay').classList.remove('hidden');
        
        calculateFees();
    });
});

// Step Navigation
function proceedToStep2() {
    if (!validateStep1()) {
        return;
    }
    
    Swal.fire({
        icon: 'info',
        title: 'Coming Soon!',
        text: 'The complete multi-step reservation form with document upload will be implemented next.',
        confirmButtonText: 'OK'
    });
}

function validateStep1() {
    if (!selectedFacility) {
        Swal.fire({
            icon: 'warning',
            title: 'Facility Required',
            text: 'Please select a facility for your event.'
        });
        return false;
    }
    
    const eventName = document.getElementById('event_name').value.trim();
    const attendees = document.getElementById('expected_attendees').value;
    const eventDate = document.getElementById('event_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (!eventName || !attendees || !eventDate || !startTime || !endTime) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Fields Missing',
            text: 'Please fill in all required fields.'
        });
        return false;
    }
    
    return true;
}

// Placeholder functions for time and date pickers
function openDatePicker() {
    Swal.fire({
        icon: 'info',
        title: 'Date Picker',
        text: 'Custom date picker will be implemented soon!',
        confirmButtonText: 'OK'
    });
}

function openTimePicker(type) {
    currentTimeType = type;
    Swal.fire({
        icon: 'info',
        title: 'Time Picker',
        text: 'Custom time picker will be implemented soon!',
        confirmButtonText: 'OK'
    });
}

function calculateFees() {
    if (!selectedFacility) return;
    
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (startTime && endTime) {
        // This will be implemented with proper time calculation
        document.getElementById('feesSummary').classList.remove('hidden');
        document.getElementById('feesBreakdown').innerHTML = `
            <div>Base Rate (3 hours): ₱${selectedFacility.baseRate.toLocaleString()}</div>
            <div class="font-semibold mt-2">Total: ₱${selectedFacility.baseRate.toLocaleString()}</div>
        `;
    }
}
</script>
@endpush
@endsection
