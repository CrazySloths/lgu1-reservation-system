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
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-1 text-white">Facility Calendar</h1>
                        <p class="text-gray-200 text-lg">Color-coded view of all facility bookings</p>
                    </div>
                </div>
                
                <!-- View Toggle -->
                <div class="flex items-center gap-2 bg-white/10 rounded-lg p-1">
                    <button id="view-all" class="px-4 py-2 rounded-lg bg-white text-lgu-headline font-medium transition-all">
                        All Facilities
                    </button>
                    <button id="view-single" class="px-4 py-2 rounded-lg text-white hover:bg-white/10 font-medium transition-all">
                        Single Facility
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Sidebar -->
    <div class="w-full lg:w-80 space-y-6">
        <!-- Facility Legend -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Facility Legend
                </h3>
            </div>
            <div class="p-4">
                <div id="facility-legend" class="space-y-2">
                    <!-- Facilities will be loaded here dynamically -->
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg shadow-sm border border-gray-200 p-4">
            <h4 class="text-sm font-semibold text-gray-900 mb-3">Quick Stats</h4>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Bookings</span>
                    <span id="total-bookings" class="text-lg font-bold text-gray-900">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Active Facilities</span>
                    <span id="active-facilities" class="text-lg font-bold text-blue-600">0</span>
                </div>
            </div>
        </div>

        <!-- Single Facility View (Hidden by default) -->
        <div id="facility-selector" class="hidden bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Select Facility
                </h3>
            </div>
            <div class="p-4">
                <ul id="facility-list" class="space-y-2">
            @foreach($facilities as $facility)
                        <li class="bg-gray-50 hover:bg-gray-100 p-3 rounded-lg cursor-pointer transition-all border border-gray-200 hover:border-purple-300"
                    data-id="{{ $facility->facility_id }}"
                    data-name="{{ $facility->name }}">
                            <span class="font-medium text-gray-900">{{ $facility->name }}</span>
                </li>
            @endforeach
        </ul>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="flex-1 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div id="calendar"></div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const facilityListItems = document.querySelectorAll('#facility-list li[data-id]');
        
        let allFacilities = [];
        let allEvents = [];
        let facilityColorMap = {};
        let visibleFacilities = new Set();
        let currentView = 'all'; // 'all' or 'single'
        
        // Initialize FullCalendar
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [],
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventDidMount: function(info) {
                if (info.event.backgroundColor) {
                    info.el.style.backgroundColor = info.event.backgroundColor;
                    info.el.style.borderColor = info.event.backgroundColor;
                }
            },
            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;
                
                Swal.fire({
                    title: event.title,
                    html: `
                        <div class="text-left space-y-2">
                            <p><strong>Facility:</strong> ${props.facility_name || 'N/A'}</p>
                            <p><strong>Applicant:</strong> ${props.applicant || 'N/A'}</p>
                            <p><strong>Date:</strong> ${event.start.toLocaleDateString()}</p>
                            <p><strong>Time:</strong> ${event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                            <p><strong>Attendees:</strong> ${props.attendees || 'N/A'}</p>
                            <p><strong>Status:</strong> <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${props.status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">${props.status ? props.status.toUpperCase() : 'N/A'}</span></p>
                            ${props.description ? `<p><strong>Description:</strong> ${props.description}</p>` : ''}
                            ${props.isCityEvent ? '<p class="text-purple-600 font-semibold flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg> Official City Event</p>' : ''}
                        </div>
                    `,
                    confirmButtonColor: event.backgroundColor,
                    width: 600
                });
            },
            dateClick: function(info) {
                // Calendar is for viewing only
            }
        });
        calendar.render();
        
        // Load all events
        function loadAllEvents() {
            fetch('/admin/calendar/all-events')
                .then(response => response.json())
                .then(data => {
                    allEvents = data.events;
                    allFacilities = data.facilities;
                    facilityColorMap = data.facilityColors;
                    
                    // Initialize all facilities as visible
                    allFacilities.forEach(f => visibleFacilities.add(f.id));
                    
                    // Render legend
                    renderFacilityLegend();
                    
                    // Update stats
                    updateStats();
                    
                    // Display events
                    displayFilteredEvents();
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load calendar events'
                    });
                });
        }
        
        // Render facility legend with color badges
        function renderFacilityLegend() {
            const legendContainer = document.getElementById('facility-legend');
            legendContainer.innerHTML = '';
            
            allFacilities.forEach(facility => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-2 rounded';
                div.innerHTML = `
                    <div class="flex items-center gap-2 flex-1">
                        <div class="w-4 h-4 rounded" style="background-color: ${facility.color}"></div>
                        <span class="text-sm font-medium text-gray-700 flex-1">${facility.name}</span>
                    </div>
                    <span class="text-xs text-gray-500 ml-2" data-count-facility="${facility.id}">0</span>
                `;
                legendContainer.appendChild(div);
            });
            
            // Update booking counts per facility
            allFacilities.forEach(facility => {
                const count = allEvents.filter(e => e.extendedProps.facility_id === facility.id).length;
                const countEl = document.querySelector(`[data-count-facility="${facility.id}"]`);
                if (countEl) countEl.textContent = count;
            });
        }
        
        // Display filtered events based on visible facilities
        function displayFilteredEvents() {
            const filteredEvents = allEvents.filter(event => 
                visibleFacilities.has(event.extendedProps.facility_id)
            );
            
            calendar.removeAllEvents();
            calendar.addEventSource({ events: filteredEvents });
        }
        
        // Update statistics
        function updateStats() {
            document.getElementById('total-bookings').textContent = allEvents.length;
            document.getElementById('active-facilities').textContent = allFacilities.length;
        }
        
        // View switcher
        document.getElementById('view-all').addEventListener('click', function() {
            currentView = 'all';
            this.classList.add('bg-white', 'text-lgu-headline');
            this.classList.remove('text-white', 'hover:bg-white/10');
            document.getElementById('view-single').classList.remove('bg-white', 'text-lgu-headline');
            document.getElementById('view-single').classList.add('text-white', 'hover:bg-white/10');
            
            document.getElementById('facility-legend').parentElement.parentElement.classList.remove('hidden');
            document.getElementById('facility-selector').classList.add('hidden');
            
            loadAllEvents();
        });
        
        document.getElementById('view-single').addEventListener('click', function() {
            currentView = 'single';
            this.classList.add('bg-white', 'text-lgu-headline');
            this.classList.remove('text-white', 'hover:bg-white/10');
            document.getElementById('view-all').classList.remove('bg-white', 'text-lgu-headline');
            document.getElementById('view-all').classList.add('text-white', 'hover:bg-white/10');
            
            document.getElementById('facility-legend').parentElement.parentElement.classList.add('hidden');
            document.getElementById('facility-selector').classList.remove('hidden');
            
            calendar.removeAllEvents();
        });
        
        // Single facility selection
        facilityListItems.forEach(item => {
            item.addEventListener('click', function() {
                const facilityId = this.dataset.id;
                const facilityName = this.dataset.name;
                
                // Fetch events for single facility
                fetch(`/facilities/${facilityId}/events`)
                    .then(response => response.json())
                    .then(events => {
                        calendar.removeAllEvents();
                        calendar.addEventSource({ events: events });
                        
                        // Highlight selected facility
                        facilityListItems.forEach(li => li.classList.remove('bg-purple-100', 'border-purple-400'));
                        this.classList.add('bg-purple-100', 'border-purple-400');
                        
                Swal.fire({
                    icon: 'success',
                    title: 'Facility Selected',
                            text: `Now viewing ${facilityName}`,
                            timer: 2000,
                    showConfirmButton: false
                });
            });
        });
        });
        
        // Initial load
        loadAllEvents();
    });
</script>
@endsection
