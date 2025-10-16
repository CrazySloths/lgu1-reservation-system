@extends('citizen.layouts.app-sidebar')

@section('title', 'My Reservations - LGU1 Citizen Portal')
@section('page-title', 'Reservation History')
@section('page-description', 'View your past and current reservations')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="flex justify-end">
        <a href="{{ route('citizen.reservations') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fas fa-plus mr-2"></i>
            New Reservation
        </a>
    </div>

    <!-- Reservations List -->
    <div class="bg-white shadow rounded-lg">
        @if($reservations->count() > 0)
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-7 gap-4 text-sm font-medium text-gray-700">
                    <div>Facility</div>
                    <div>Event</div>
                    <div>Date & Time</div>
                    <div>Status</div>
                    <div>Total Fee</div>
                    <div>Payment</div>
                    <div>Actions</div>
                </div>
            </div>

            <!-- Reservations Data -->
            <div class="divide-y divide-gray-200">
                @foreach($reservations as $reservation)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="grid grid-cols-7 gap-4 items-center">
                        <!-- Facility -->
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $reservation->facility->name ?? 'N/A' }}</h4>
                            <p class="text-sm text-gray-600">{{ $reservation->facility->location ?? '' }}</p>
                        </div>
                        
                        <!-- Event -->
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $reservation->event_name ?? 'N/A' }}</h4>
                            <p class="text-sm text-gray-600">{{ $reservation->expected_attendees ?? 0 }} attendees</p>
                        </div>
                        
                        <!-- Date & Time -->
                        <div>
                            <p class="font-medium text-gray-900">{{ $reservation->event_date ? \Carbon\Carbon::parse($reservation->event_date)->format('M j, Y') : 'N/A' }}</p>
                            <p class="text-sm text-gray-600">
                                @if($reservation->start_time && $reservation->end_time)
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800'
                                ];
                                $status = $reservation->status ?? 'pending';
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        
                        <!-- Total Fee -->
                        <div>
                            <p class="font-medium text-gray-900">₱{{ number_format($reservation->total_fee ?? 0, 2) }}</p>
                        </div>
                        
                        <!-- Payment Status -->
                        <div>
                            @if($reservation->status === 'approved' && $reservation->paymentSlip)
                                @php $paymentStatus = $reservation->paymentSlip->status; @endphp
                                @if($paymentStatus === 'paid')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Paid
                                    </span>
                                @elseif($paymentStatus === 'expired')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Unpaid
                                    </span>
                                @endif
                            @elseif($reservation->status === 'approved')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-hourglass-half mr-1"></i>
                                    Processing
                                </span>
                            @else
                                <span class="text-sm text-gray-500">—</span>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="space-y-1">
                            @if($reservation->status === 'approved' && $reservation->paymentSlip)
                                <div>
                                    <a href="{{ route('citizen.payment-slips.show', $reservation->paymentSlip->id) }}" 
                                       class="text-green-600 hover:text-green-800 text-sm font-medium inline-flex items-center">
                                        <i class="fas fa-receipt mr-1"></i>
                                        Payment Slip
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('citizen.payment-slips.download', $reservation->paymentSlip->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                                        <i class="fas fa-download mr-1"></i>
                                        Download PDF
                                    </a>
                                </div>
                            @endif
                            
                            @if(in_array($reservation->status, ['approved', 'pending']))
                                <div>
                                    <button onclick="openExtensionModal({{ $reservation->id }}, '{{ $reservation->end_time }}', '{{ $reservation->event_name }}', '{{ $reservation->facility->name ?? 'N/A' }}', '{{ \Carbon\Carbon::parse($reservation->event_date)->format('M j, Y') }}')" 
                                       class="text-purple-600 hover:text-purple-800 text-sm font-medium inline-flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        Extend Time
                                    </button>
                                </div>
                            @endif
                            
                            @if($reservation->status === 'pending' || $reservation->status === 'rejected')
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Details
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination would go here -->
            
        @else
            <!-- Empty State -->
            <div class="px-6 py-12 text-center">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Reservations Yet</h3>
                <p class="text-gray-600 mb-6">You haven't made any facility reservations yet.</p>
                
                @if($user->is_verified ?? false)
                    <a href="{{ route('citizen.reservations') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-plus mr-2"></i>
                        Make Your First Reservation
                    </a>
                @else
                    <div class="text-center">
                        <div class="inline-flex items-center px-6 py-3 bg-yellow-100 text-yellow-800 rounded-lg">
                            <i class="fas fa-clock mr-2"></i>
                            Account verification required to make reservations
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Reservations -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $reservations->count() }}</h3>
                    <p class="text-sm text-gray-600">Total Reservations</p>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-hourglass-half text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $reservations->where('status', 'pending')->count() }}</h3>
                    <p class="text-sm text-gray-600">Pending</p>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $reservations->where('status', 'approved')->count() }}</h3>
                    <p class="text-sm text-gray-600">Approved</p>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $reservations->where('status', 'rejected')->count() }}</h3>
                    <p class="text-sm text-gray-600">Rejected</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Reservation Tips</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Submit your reservation at least 3-5 business days in advance</li>
                        <li>All reservations require approval from our staff</li>
                        <li>Make sure to provide complete and accurate information</li>
                        <li>Contact our office for urgent or special requests</li>
                        <li><strong>You can now extend your booking time!</strong> Click "Extend Time" to add more hours (conflict detection enabled)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Extension Modal -->
<div id="extensionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-clock text-purple-600 mr-2"></i>
                Extend Booking Time
            </h3>
            <button onclick="closeExtensionModal()" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Booking Details -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-900 mb-2">Current Booking</h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-600">Facility:</span>
                        <span class="font-medium ml-2" id="modal-facility">—</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Event:</span>
                        <span class="font-medium ml-2" id="modal-event">—</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Date:</span>
                        <span class="font-medium ml-2" id="modal-date">—</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Current End Time:</span>
                        <span class="font-medium ml-2" id="modal-current-end">—</span>
                    </div>
                </div>
            </div>

            <!-- Extension Form -->
            <form id="extensionForm" method="POST">
                @csrf
                <input type="hidden" id="booking-id" name="booking_id">
                
                <div class="space-y-4">
                    <!-- New End Time -->
                    <div>
                        <label for="new_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            New End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="new_end_time" 
                               name="new_end_time" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-xs text-gray-500 mt-1">Select a time later than your current end time</p>
                    </div>

                    <!-- Extension Reason -->
                    <div>
                        <label for="extension_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Extension (Optional)
                        </label>
                        <textarea id="extension_reason" 
                                  name="extension_reason" 
                                  rows="3"
                                  maxlength="500"
                                  placeholder="e.g., Need extra time for event setup/teardown"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                    </div>

                    <!-- Conflict Warning -->
                    <div id="conflict-warning" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-red-800">Schedule Conflict Detected!</h4>
                                <p class="text-sm text-red-700 mt-1" id="conflict-message"></p>
                                <div id="conflict-details" class="mt-2 text-xs text-red-600"></div>
                            </div>
                        </div>
                    </div>

                    <!-- No Conflict Message -->
                    <div id="no-conflict-message" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">No conflicts detected! You can proceed with the extension.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-4 border-t">
            <button onclick="closeExtensionModal()" 
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                Cancel
            </button>
            <button onclick="checkConflict()" 
                    class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i>
                Check Availability
            </button>
            <button id="submit-extension" 
                    onclick="submitExtension()" 
                    disabled
                    class="px-4 py-2 text-white bg-purple-600 rounded-lg hover:bg-purple-700 disabled:bg-gray-300 disabled:cursor-not-allowed">
                <i class="fas fa-clock mr-2"></i>
                Confirm Extension
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showVerificationAlert() {
    Swal.fire({
        icon: 'warning',
        title: 'Account Verification Required',
        text: 'Your account is still pending verification. Please wait for staff approval before making reservations.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3B82F6'
    });
}

// Extension Modal Functions
let currentBookingId = null;
let currentEndTime = null;

function openExtensionModal(bookingId, endTime, eventName, facilityName, eventDate) {
    currentBookingId = bookingId;
    currentEndTime = endTime;
    
    // Populate modal with booking details
    document.getElementById('booking-id').value = bookingId;
    document.getElementById('modal-facility').textContent = facilityName;
    document.getElementById('modal-event').textContent = eventName;
    document.getElementById('modal-date').textContent = eventDate;
    
    // Format and display current end time
    const formattedEndTime = formatTime(endTime);
    document.getElementById('modal-current-end').textContent = formattedEndTime;
    
    // Reset form and messages
    document.getElementById('new_end_time').value = '';
    document.getElementById('extension_reason').value = '';
    document.getElementById('conflict-warning').classList.add('hidden');
    document.getElementById('no-conflict-message').classList.add('hidden');
    document.getElementById('submit-extension').disabled = true;
    
    // Show modal
    document.getElementById('extensionModal').classList.remove('hidden');
}

function closeExtensionModal() {
    document.getElementById('extensionModal').classList.add('hidden');
    currentBookingId = null;
    currentEndTime = null;
}

function formatTime(timeString) {
    // Convert 24h format to 12h format with AM/PM
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

function checkConflict() {
    const newEndTime = document.getElementById('new_end_time').value;
    
    if (!newEndTime) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Information',
            text: 'Please select a new end time first.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    // Validate that new end time is later than current end time
    if (newEndTime <= currentEndTime) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Time',
            text: 'The new end time must be later than the current end time (' + formatTime(currentEndTime) + ')',
            confirmButtonColor: '#EF4444'
        });
        return;
    }
    
    // Show loading
    Swal.fire({
        title: 'Checking Availability...',
        text: 'Please wait while we check for schedule conflicts',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Make AJAX request to check for conflicts
    fetch(`/citizen/bookings/${currentBookingId}/check-extension-conflict`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            new_end_time: newEndTime
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.hasConflict) {
            // Show conflict warning
            document.getElementById('conflict-warning').classList.remove('hidden');
            document.getElementById('no-conflict-message').classList.add('hidden');
            document.getElementById('conflict-message').textContent = data.message;
            
            // Display conflict details
            if (data.conflicts && data.conflicts.length > 0) {
                let conflictHtml = '<ul class="list-disc list-inside mt-2">';
                data.conflicts.forEach(conflict => {
                    conflictHtml += `<li>${conflict.event_name} by ${conflict.user_name} (${conflict.start_time} - ${conflict.end_time})</li>`;
                });
                conflictHtml += '</ul>';
                document.getElementById('conflict-details').innerHTML = conflictHtml;
            }
            
            // Disable submit button
            document.getElementById('submit-extension').disabled = true;
        } else {
            // No conflict - show success message
            document.getElementById('conflict-warning').classList.add('hidden');
            document.getElementById('no-conflict-message').classList.remove('hidden');
            
            // Enable submit button
            document.getElementById('submit-extension').disabled = false;
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while checking for conflicts. Please try again.',
            confirmButtonColor: '#EF4444'
        });
    });
}

function submitExtension() {
    const newEndTime = document.getElementById('new_end_time').value;
    const extensionReason = document.getElementById('extension_reason').value;
    
    if (!newEndTime) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Information',
            text: 'Please select a new end time.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    // Confirm extension
    Swal.fire({
        title: 'Confirm Extension',
        text: `Extend booking until ${formatTime(newEndTime)}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#9333EA',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Extend',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/citizen/bookings/${currentBookingId}/extend`;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);
            
            // Add new end time
            const endTimeInput = document.createElement('input');
            endTimeInput.type = 'hidden';
            endTimeInput.name = 'new_end_time';
            endTimeInput.value = newEndTime;
            form.appendChild(endTimeInput);
            
            // Add extension reason
            if (extensionReason) {
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'extension_reason';
                reasonInput.value = extensionReason;
                form.appendChild(reasonInput);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Close modal when clicking outside
document.getElementById('extensionModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeExtensionModal();
    }
});
</script>
@endpush
@endsection
