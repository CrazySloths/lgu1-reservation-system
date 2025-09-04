@extends('citizen.layouts.app-sidebar')

@section('title', 'Dashboard - LGU1 Citizen Portal')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome to your citizen portal')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->full_name }}!</h1>
                <p class="text-gray-600 mt-1">Manage your facility reservations and profile</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm font-medium">Active Account</span>
                </div>
            </div>
        </div>
    </div>



    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Available Facilities -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $availableFacilities }}</h2>
                    <p class="text-gray-600">Available Facilities</p>
                </div>
            </div>
        </div>

        <!-- My Reservations -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $totalReservations }}</h2>
                    <p class="text-gray-600">My Reservations</p>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ $unpaidPaymentSlips > 0 ? 'bg-yellow-100' : 'bg-green-100' }}">
                    <i class="fas {{ $unpaidPaymentSlips > 0 ? 'fa-exclamation-triangle text-yellow-600' : 'fa-check-circle text-green-600' }} text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $unpaidPaymentSlips }}</h2>
                    <p class="text-gray-600">Pending Payments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- New Reservation -->
            <a href="{{ route('citizen.reservations') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition duration-200">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-plus text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">New Reservation</h3>
                    <p class="text-xs text-gray-600">Book a facility</p>
                </div>
            </a>

            <!-- Reservation History -->
            <a href="{{ route('citizen.reservation.history') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-green-300 transition duration-200">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-history text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">My Reservations</h3>
                    <p class="text-xs text-gray-600">View reservation history</p>
                </div>
            </a>

            <!-- Payment Slips -->
            <a href="{{ route('citizen.payment-slips.index') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-orange-300 transition duration-200">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i class="fas fa-receipt text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Payment Slips</h3>
                    <p class="text-xs text-gray-600">View payment details</p>
                </div>
                @if($unpaidPaymentSlips > 0)
                    <div class="ml-auto">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $unpaidPaymentSlips }}
                        </span>
                    </div>
                @endif
            </a>

            <!-- Profile -->
            <a href="{{ route('citizen.profile') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-purple-300 transition duration-200">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-user-edit text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Update Profile</h3>
                    <p class="text-xs text-gray-600">Manage your information</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Payment Slips -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Recent Payment Slips</h2>
            @if($paymentSlips->count() > 0)
                <a href="{{ route('citizen.payment-slips.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View All
                </a>
            @endif
        </div>
        
        @if($paymentSlips->count() > 0)
            <div class="space-y-3">
                @foreach($paymentSlips as $slip)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 {{ $slip->status === 'paid' ? 'bg-green-100' : ($slip->status === 'expired' ? 'bg-red-100' : 'bg-yellow-100') }} rounded-lg mr-3">
                                @if($slip->status === 'paid')
                                    <i class="fas fa-check-circle text-green-600"></i>
                                @elseif($slip->status === 'expired')
                                    <i class="fas fa-times-circle text-red-600"></i>
                                @else
                                    <i class="fas fa-clock text-yellow-600"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $slip->slip_number }}</h4>
                                <p class="text-xs text-gray-600">{{ $slip->booking->event_name }} • ₱{{ number_format($slip->amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                {{ $slip->status === 'paid' ? 'bg-green-100 text-green-800' : ($slip->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($slip->status) }}
                            </span>
                            <a href="{{ route('citizen.payment-slips.show', $slip->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500">No payment slips yet</p>
                <p class="text-sm text-gray-400 mt-1">Payment slips will appear here once your reservations are approved</p>
            </div>
        @endif
    </div>

    <!-- System Announcements -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-bullhorn text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">System Announcements</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Welcome to the new LGU1 Citizen Portal!</li>
                        <li>All reservations require advance booking and approval.</li>
                        <li>For urgent requests, please contact our office directly.</li>
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
