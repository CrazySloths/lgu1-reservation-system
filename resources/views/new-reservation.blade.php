@extends('layouts.app')

@section('content')

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
    </div>
</div>

<div id="booking-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center transition transform sm:scale-100">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-6 py-8 text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full">
            <form id="booking-form" action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="facility_id" id="modal-facility-id">
                <input type="hidden" name="start_time" id="modal-start-time">
                <input type="hidden" name="end_time" id="modal-end-time">
                
                <h3 class="text-2xl font-bold text-gray-900 mb-4" id="modal-title">Confirm Your Booking</h3>
                
                <div class="mt-2 text-gray-600">
                    <p class="mb-2">You are reserving **<span id="modal-facility-name" class="font-bold text-lgu-headline"></span>**.</p>
                    <p class="mb-4">
                        <span class="font-medium">From:</span> <span id="modal-start-display" class="font-bold text-lgu-stroke"></span><br>
                        <span class="font-medium">To:</span> <span id="modal-end-display" class="font-bold text-lgu-stroke"></span>
                    </p>
                </div>
                
                <div class="mb-6">
                    <label for="user_name" class="block text-sm font-medium text-gray-700">Your Full Name</label>
                    <input type="text" name="user_name" id="user_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-lgu-headline focus:border-lgu-headline transition duration-150">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-booking" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-150">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-lgu-headline rounded-lg hover:bg-lgu-stroke transition duration-150">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        });
    });
</script>
@endsection