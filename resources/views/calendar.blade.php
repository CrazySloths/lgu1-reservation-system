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

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const facilityListItems = document.querySelectorAll('li[data-id]');
        
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
                // This function is now empty to prevent booking on this page.
                // The calendar is for viewing only.
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
                const facilityName = this.dataset.name;
                
                // Fetch and render events for the clicked facility
                fetchAndRenderEvents(facilityId);

                // Add active class for styling
                facilityListItems.forEach(li => li.classList.remove('bg-gray-200'));
                this.classList.add('bg-gray-200');

                // Show Swal message
                Swal.fire({
                    icon: 'success',
                    title: 'Facility Selected',
                    text: `Now viewing the calendar for ${facilityName}.`,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        });
    });
</script>
@endsection