@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Facility Calendar</h2>
        <p class="text-gray-200">View facility schedules and availability here</p>
    </div>
</div>

<div class="flex flex-col md:flex-row gap-6">
    <div class="w-full md:w-1/4 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <h3 class="text-lg font-bold mb-4">Available Facilities</h3>
        <ul class="space-y-2">
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
        <div id="calendar"></div>
    </div>
</div>

<div id="bookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden transition-all ease-in-out duration-300">
    <div id="modalBookingContent" class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transition-all ease-in-out duration-300 transform scale-95 opacity-0">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Book Facility: <span id="bookingFacilityName" class="font-bold"></span></h3>
        
        <form id="bookingForm" action="#" method="POST">
            @csrf
            
            <input type="hidden" name="facility_id" id="bookingFacilityId">
            
            <div class="mb-4">
                <label for="user_name" class="block text-sm font-medium text-gray-700">Your Name</label>
                <input type="text" name="user_name" id="user_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            
            <div class="mb-4">
                <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="datetime-local" name="start_time" id="start_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="mb-4">
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="datetime-local" name="end_time" id="end_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" id="closeBookingModalBtn" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg shadow hover:bg-yellow-400 transition">Submit Booking</button>
            </div>
        </form>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const facilityListItems = document.querySelectorAll('li[data-id]');
        
        const bookingModal = document.getElementById('bookingModal');
        const closeBookingModalBtn = document.getElementById('closeBookingModalBtn');
        const modalBookingContent = document.getElementById('modalBookingContent');
        const bookingFacilityId = document.getElementById('bookingFacilityId');
        const bookingFacilityName = document.getElementById('bookingFacilityName');
        const start_time_input = document.getElementById('start_time');
        const end_time_input = document.getElementById('end_time');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventDidMount: function(info) {
                if (info.event.backgroundColor) {
                    info.el.style.backgroundColor = info.event.backgroundColor;
                }
            },
            dateClick: function(info) {
                // Open the booking modal and pre-fill the date
                const facilityId = document.querySelector('li.bg-gray-200')?.dataset.id;
                const facilityName = document.querySelector('li.bg-gray-200')?.dataset.name;
                
                if (facilityId) {
                    bookingFacilityId.value = facilityId;
                    bookingFacilityName.textContent = facilityName;
                    
                    const clickedDate = info.dateStr;
                    start_time_input.value = `${clickedDate}T08:00`;
                    end_time_input.value = `${clickedDate}T09:00`;

                    bookingModal.classList.remove('hidden');
                    setTimeout(() => {
                        bookingModal.classList.add('bg-opacity-50');
                        modalBookingContent.classList.remove('opacity-0', 'scale-95');
                    }, 10);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Selection Required',
                        text: 'Please select a facility from the list first!'
                    });
                }
            }
        });
        calendar.render();
        
        // Function to fetch and display events
        function fetchAndRenderEvents(facilityId) {
            fetch(`/facilities/${facilityId}/events`)
                .then(response => response.json())
                .then(events => {
                    calendar.removeAllEvents();
                    calendar.addEventSource({ events: events });
                });
        }
        
        // Add click event listener to each facility list item
        facilityListItems.forEach(item => {
            item.addEventListener('click', function() {
                const facilityId = this.dataset.id;
                
                // Fetch and render events for the clicked facility
                fetchAndRenderEvents(facilityId);

                // Add active class for styling
                facilityListItems.forEach(li => li.classList.remove('bg-gray-200'));
                this.classList.add('bg-gray-200');
            });
        });

        closeBookingModalBtn.addEventListener('click', () => {
            modalBookingContent.classList.add('opacity-0', 'scale-95');
            bookingModal.classList.remove('bg-opacity-50');
            setTimeout(() => {
                bookingModal.classList.add('hidden');
            }, 300);
        });
    });
</script>
@endsection