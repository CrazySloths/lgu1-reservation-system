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

    <!-- Quick Actions -->
    <div class="flex justify-end">
        <a href="{{ route('citizen.reservations') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ !$user->isVerified() ? 'opacity-50 cursor-not-allowed' : '' }}"
           {{ !$user->isVerified() ? 'onclick="event.preventDefault(); showVerificationAlert()"' : '' }}>
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
                            <p class="text-sm text-gray-600">{{ $reservation->start_time ?? 'N/A' }} - {{ $reservation->end_time ?? 'N/A' }}</p>
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
                            @else
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
                
                @if($user->isVerified())
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
                    </ul>
                </div>
            </div>
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
</script>
@endpush
@endsection
