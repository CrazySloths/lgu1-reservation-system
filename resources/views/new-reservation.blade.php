@extends('layouts.app')

@section('content')

<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-xl p-8 text-white shadow-lg">
        <h2 class="text-3xl font-extrabold mb-1">New Facility Reservation</h2>
        <p class="text-lg font-light text-gray-200">Select an available time slot below to make a reservation.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden p-8">
    <div class="flex items-center justify-between space-x-4 mb-8">
        <h3 class="text-xl font-semibold text-gray-800">Available Facilities</h3>
        <label for="facility-select" class="font-medium text-gray-700 sr-only">Select Facility:</label>
        <select id="facility-select" class="w-full md:w-auto border-gray-300 rounded-lg shadow-sm focus:ring-lgu-headline focus:border-lgu-headline transition duration-150 ease-in-out">
            <option value="">Choose a facility</option>
            @foreach($facilities as $facility)
                <option value="{{ $facility->facility_id }}">{{ $facility->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div id="calendar-container" class="mt-6 hidden">
        <div id="calendar" class="p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-inner"></div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar;
        var facilitySelect = document.getElementById('facility-select');
        var calendarContainer = document.getElementById('calendar-container');
        var bookingModal = document.getElementById('booking-modal');

        // Function to close the modal
        function closeModal() {
            bookingModal.classList.add('hidden');
        }

        // Attach event listener to the cancel button
        document.getElementById('cancel-booking').addEventListener('click', closeModal);

        // Facility select change event listener
        facilitySelect.addEventListener('change', function() {
            var facilityId = this.value;

            if (facilityId) {
                if (calendar) {
                    calendar.destroy();
                }
                calendarContainer.classList.remove('hidden');

                // Initialize FullCalendar
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
                    events: '{{ url('/facilities') }}/' + facilityId + '/events',
                    eventColor: '#3B82F6',
                    eventClick: function(info) {
                        alert('This time slot is already booked.');
                    },
                    select: function(info) {
                        var now = new Date();
                        var selectedStart = info.start;
                        var selectedEnd = info.end;

                        if (selectedStart < now) {
                            alert('You cannot book a past time slot.');
                            calendar.unselect();
                            return;
                        }

                        var events = calendar.getEvents();
                        var isOverlap = events.some(event => {
                            return (info.start < event.end && info.end > event.start);
                        });

                        if (isOverlap) {
                            alert('This time slot is already booked.');
                            calendar.unselect();
                            return;
                        }

                        document.getElementById('modal-facility-id').value = facilityId;
                        document.getElementById('modal-start-time').value = info.startStr;
                        document.getElementById('modal-end-time').value = info.endStr;
                        document.getElementById('modal-start-display').innerText = info.start.toLocaleString();
                        document.getElementById('modal-end-display').innerText = info.end.toLocaleString();
                        
                        var selectedFacilityName = facilitySelect.options[facilitySelect.selectedIndex].text;
                        document.getElementById('modal-facility-name').innerText = selectedFacilityName;

                        bookingModal.classList.remove('hidden');
                    }
                });
                calendar.render();
            } else {
                calendarContainer.classList.add('hidden');
                if (calendar) {
                    calendar.destroy();
                }
            }
        });
    });
</script>

@endsection