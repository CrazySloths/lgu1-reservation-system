@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">City Events Calendar</h1>
            <p class="text-gray-600 mt-1">Visual schedule of all official city events</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.city-events.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create City Event
            </a>
            <a href="{{ route('admin.city-events.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                List View
            </a>
        </div>
    </div>

    <!-- Calendar -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div id="calendar" class="p-6"></div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Legend</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 rounded bg-blue-500 mr-2"></div>
                <span class="text-sm text-gray-600">City Event (Mayor Authorized)</span>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-900" id="modalTitle"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6" id="modalContent"></div>
        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-xl flex items-center justify-end space-x-3">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Close
            </button>
            <a id="viewEventLink" href="#" class="px-4 py-2 bg-lgu-highlight text-lgu-button-text font-medium rounded-lg hover:bg-lgu-button transition-colors">
                View Full Details
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // City events data from backend
    var cityEvents = @json($cityEvents);
    
    // Transform events for FullCalendar
    var events = cityEvents.map(function(event) {
        return {
            id: event.id,
            title: event.event_name.replace('CITY EVENT: ', ''),
            start: event.event_date + 'T' + event.start_time,
            end: event.event_date + 'T' + event.end_time,
            backgroundColor: '#3B82F6',
            borderColor: '#2563EB',
            extendedProps: {
                facility: event.facility ? event.facility.name : 'N/A',
                attendees: event.expected_attendees,
                description: event.event_description,
                organizerName: event.applicant_name,
                organizerEmail: event.applicant_email,
                organizerPhone: event.applicant_phone
            }
        };
    });
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day',
            list: 'List'
        },
        events: events,
        eventClick: function(info) {
            showEventModal(info.event);
        },
        eventMouseEnter: function(info) {
            info.el.style.cursor = 'pointer';
        },
        height: 'auto',
        displayEventTime: true,
        displayEventEnd: true,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        }
    });
    
    calendar.render();
    
    function showEventModal(event) {
        document.getElementById('modalTitle').textContent = event.title;
        
        var props = event.extendedProps;
        var startDate = new Date(event.start);
        var endTime = event.end ? new Date(event.end) : null;
        
        var content = `
            <div class="space-y-4">
                <div>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        Mayor Authorized City Event
                    </span>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Description</h4>
                    <p class="text-gray-900">${props.description || 'No description provided'}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Facility</h4>
                        <p class="text-gray-900 font-semibold">${props.facility}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Expected Attendees</h4>
                        <p class="text-gray-900 font-semibold">${props.attendees.toLocaleString()} people</p>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Date & Time</h4>
                    <p class="text-gray-900">
                        ${startDate.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                    </p>
                    <p class="text-gray-600">
                        ${startDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })} - 
                        ${endTime ? endTime.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : 'N/A'}
                    </p>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Organizer Information</h4>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-900"><span class="font-medium">Office:</span> ${props.organizerName}</p>
                        <p class="text-sm text-gray-900"><span class="font-medium">Email:</span> ${props.organizerEmail}</p>
                        <p class="text-sm text-gray-900"><span class="font-medium">Phone:</span> ${props.organizerPhone}</p>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('viewEventLink').href = '/admin/city-events/' + event.id;
        document.getElementById('eventModal').classList.remove('hidden');
    }
    
    window.closeModal = function() {
        document.getElementById('eventModal').classList.add('hidden');
    };
    
    // Close modal when clicking outside
    document.getElementById('eventModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
});
</script>
@endpush
@endsection

