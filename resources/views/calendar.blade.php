@extends('layouts.app')

@section('content')
<div class="mb-6">
    <!-- Enhanced Header Section -->
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
        </div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-1 bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Facility Calendar</h1>
                    <p class="text-gray-200 text-lg">View facility schedules and availability here</p>
                </div>
            </div>
        </div>
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