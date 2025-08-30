@extends('citizen.layouts.app-sidebar')

@section('title', 'Facility Availability - LGU1 Citizen Portal')
@section('page-title', 'Facility Availability')
@section('page-description', 'View real-time facility availability and existing reservations')

@section('content')
<div class="space-y-6">
    <!-- Availability Overview -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Real-Time Availability Calendar</h3>
                <p class="text-sm text-gray-600">Select a facility to view its booking schedule and availability</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Booked</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Available</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Pending</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Facility List -->
            <div class="lg:col-span-1">
                <h4 class="font-medium text-gray-900 mb-4">Select Facility</h4>
                <div class="space-y-2">
                    @foreach($facilities as $facility)
                        <div class="facility-item p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-colors"
                             data-facility-id="{{ $facility->facility_id }}"
                             data-facility-name="{{ $facility->name }}"
                             data-facility-location="{{ $facility->location }}"
                             data-facility-capacity="{{ $facility->capacity }}"
                             data-facility-rate="{{ number_format($facility->daily_rate, 2) }}">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-building text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h5 class="font-medium text-gray-900 truncate">{{ $facility->name }}</h5>
                                    <p class="text-sm text-gray-600 truncate">{{ $facility->location }}</p>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <i class="fas fa-users mr-1"></i>
                                        <span>{{ $facility->capacity }} capacity</span>
                                    </div>
                                    <div class="mt-1 text-sm font-medium text-green-600">
                                        ₱{{ number_format($facility->daily_rate, 2) }}/day
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($user->isVerified())
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('citizen.reservations') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-plus mr-2"></i>
                            Make Reservation
                        </a>
                    </div>
                @else
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="text-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 mb-2"></i>
                            <p class="text-sm text-yellow-800">Account verification required</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Calendar -->
            <div class="lg:col-span-3">
                <div class="border border-gray-200 rounded-lg">
                    <!-- Selected Facility Info -->
                    <div id="selected-facility-info" class="px-6 py-4 bg-gray-50 border-b border-gray-200 hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 id="selected-facility-name" class="font-medium text-gray-900"></h4>
                                <p id="selected-facility-details" class="text-sm text-gray-600"></p>
                            </div>
                            <div id="selected-facility-rate" class="text-lg font-semibold text-green-600"></div>
                        </div>
                    </div>

                    <!-- Calendar Container -->
                    <div class="p-6">
                        <div id="availability-calendar" class="min-h-[600px]"></div>
                    </div>
                </div>

                <!-- No Facility Selected State -->
                <div id="no-facility-selected" class="border border-gray-200 rounded-lg p-12 text-center">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-alt text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Select a Facility</h3>
                    <p class="text-gray-600">Choose a facility from the list to view its availability calendar and existing bookings.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Tips -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Availability Tips</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Red events indicate confirmed bookings - these times are unavailable</li>
                        <li>Yellow events show pending reservations that may be approved or rejected</li>
                        <li>Click on any date to see if it's available for booking</li>
                        <li>Submit reservations 3-5 business days in advance for best availability</li>
                        <li>Consider alternative dates if your preferred slot is already booked</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div id="event-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title">Booking Details</h3>
                <button type="button" id="close-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modal-content" class="space-y-3">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" id="close-modal-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- FullCalendar CDN -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('availability-calendar');
    const facilityItems = document.querySelectorAll('.facility-item');
    const selectedFacilityInfo = document.getElementById('selected-facility-info');
    const noFacilitySelected = document.getElementById('no-facility-selected');
    const modalEl = document.getElementById('event-details-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const closeModal = document.getElementById('close-modal');
    
    let calendar;
    let currentFacilityId = null;

    // Initialize calendar
    function initializeCalendar() {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [],
            eventDidMount: function(info) {
                // Custom styling for events
                if (info.event.backgroundColor) {
                    info.el.style.backgroundColor = info.event.backgroundColor;
                    info.el.style.borderColor = info.event.borderColor || info.event.backgroundColor;
                }
            },
            eventClick: function(info) {
                showEventDetails(info.event);
            },
            dateClick: function(info) {
                if (currentFacilityId) {
                    checkDateAvailability(info.dateStr);
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Select a Facility',
                        text: 'Please select a facility first to check availability for this date.',
                        confirmButtonColor: '#3B82F6'
                    });
                }
            },
            height: 'auto',
            aspectRatio: 1.8
        });
        
        calendar.render();
    }

    // Load facility bookings
    function loadFacilityBookings(facilityId) {
        fetch(`/citizen/api/facility/${facilityId}/bookings`)
            .then(response => response.json())
            .then(events => {
                calendar.removeAllEvents();
                
                // Add pending bookings as yellow events
                events.forEach(event => {
                    calendar.addEvent(event);
                });
            })
            .catch(error => {
                console.error('Error loading facility bookings:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load facility bookings. Please try again.',
                    confirmButtonColor: '#3B82F6'
                });
            });
    }

    // Handle facility selection
    facilityItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            facilityItems.forEach(i => i.classList.remove('bg-blue-50', 'border-blue-200'));
            
            // Add active class to selected item
            this.classList.add('bg-blue-50', 'border-blue-200');
            
            // Get facility data
            const facilityId = this.dataset.facilityId;
            const facilityName = this.dataset.facilityName;
            const facilityLocation = this.dataset.facilityLocation;
            const facilityCapacity = this.dataset.facilityCapacity;
            const facilityRate = this.dataset.facilityRate;
            
            // Update selected facility info
            document.getElementById('selected-facility-name').textContent = facilityName;
            document.getElementById('selected-facility-details').textContent = `${facilityLocation} • Capacity: ${facilityCapacity}`;
            document.getElementById('selected-facility-rate').textContent = `₱${facilityRate}/day`;
            
            // Show facility info and hide no selection state
            selectedFacilityInfo.classList.remove('hidden');
            noFacilitySelected.classList.add('hidden');
            calendarEl.parentElement.classList.remove('hidden');
            
            // Update current facility
            currentFacilityId = facilityId;
            
            // Load bookings for this facility
            loadFacilityBookings(facilityId);
        });
    });

    // Show event details in modal
    function showEventDetails(event) {
        const modalContent = document.getElementById('modal-content');
        const props = event.extendedProps;
        
        modalContent.innerHTML = `
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event</label>
                    <p class="text-sm text-gray-900">${event.title}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                    <p class="text-sm text-gray-900">${event.start.toLocaleDateString()} ${event.start.toLocaleTimeString()} - ${event.end.toLocaleTimeString()}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Applicant</label>
                    <p class="text-sm text-gray-900">${props.applicant}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Expected Attendees</label>
                    <p class="text-sm text-gray-900">${props.attendees}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        ${props.status.charAt(0).toUpperCase() + props.status.slice(1)}
                    </span>
                </div>
                ${props.description ? `
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="text-sm text-gray-900">${props.description}</p>
                    </div>
                ` : ''}
            </div>
        `;
        
        modalEl.classList.remove('hidden');
    }

    // Check date availability
    function checkDateAvailability(dateStr) {
        const events = calendar.getEvents();
        const selectedDate = new Date(dateStr);
        
        const conflictingEvents = events.filter(event => {
            const eventDate = new Date(event.start.toDateString());
            const selectedDateOnly = new Date(selectedDate.toDateString());
            return eventDate.getTime() === selectedDateOnly.getTime();
        });
        
        if (conflictingEvents.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Date Unavailable',
                text: `This date has ${conflictingEvents.length} existing booking(s). Please select a different date or consider alternative facilities.`,
                confirmButtonColor: '#3B82F6',
                confirmButtonText: 'Choose Different Date',
                showCancelButton: true,
                cancelButtonText: 'View Alternative Facilities'
            }).then((result) => {
                if (!result.isConfirmed) {
                    // User wants to see alternatives - could implement AI recommendations here
                    showAlternativeFacilities(dateStr);
                }
            });
        } else {
            Swal.fire({
                icon: 'success',
                title: 'Date Available!',
                text: `${selectedDate.toLocaleDateString()} is available for booking at this facility.`,
                confirmButtonColor: '#10B981',
                confirmButtonText: 'Make Reservation',
                showCancelButton: true,
                cancelButtonText: 'Continue Browsing'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to reservation form with pre-filled date
                    window.location.href = '{{ route("citizen.reservations") }}?date=' + dateStr + '&facility=' + currentFacilityId;
                }
            });
        }
    }

    // Show alternative facilities (placeholder for AI integration)
    function showAlternativeFacilities(dateStr) {
        Swal.fire({
            icon: 'info',
            title: 'Alternative Facilities',
            html: `
                <p class="mb-4">Would you like to see alternative facilities available on ${new Date(dateStr).toLocaleDateString()}?</p>
                <p class="text-sm text-gray-600">Our AI system can suggest similar facilities that might meet your needs.</p>
            `,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: 'Show Alternatives',
            showCancelButton: true,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // This would integrate with the AI recommendation system
                Swal.fire({
                    icon: 'info',
                    title: 'AI Recommendations',
                    text: 'AI-powered facility recommendations will be implemented here.',
                    confirmButtonColor: '#3B82F6'
                });
            }
        });
    }

    // Modal close handlers
    closeModalBtn.addEventListener('click', () => {
        modalEl.classList.add('hidden');
    });
    
    closeModal.addEventListener('click', () => {
        modalEl.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    modalEl.addEventListener('click', (e) => {
        if (e.target === modalEl) {
            modalEl.classList.add('hidden');
        }
    });

    // Initialize calendar
    initializeCalendar();
});
</script>
@endpush
