@extends('citizen.layouts.app')

@section('title', 'Dashboard - LGU1 Citizen Portal')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->name }}!</h1>
                <p class="text-gray-600 mt-1">Manage your facility reservations and profile</p>
            </div>
            <div class="flex items-center space-x-4">
                @if($user->isVerified())
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="text-sm font-medium">Verified Account</span>
                    </div>
                @else
                    <div class="flex items-center text-yellow-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span class="text-sm font-medium">Pending Verification</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Account Status Alert -->
    @if(!$user->isVerified())
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Account Verification Pending</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Your account is currently under review by our staff. Once verified, you'll be able to make facility reservations. This process typically takes 1-2 business days.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                    <h2 class="text-lg font-semibold text-gray-900">0</h2>
                    <p class="text-gray-600">My Reservations</p>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ $user->isVerified() ? 'bg-green-100' : 'bg-yellow-100' }}">
                    <i class="fas {{ $user->isVerified() ? 'fa-user-check text-green-600' : 'fa-user-clock text-yellow-600' }} text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $user->isVerified() ? 'Verified' : 'Pending' }}
                    </h2>
                    <p class="text-gray-600">Account Status</p>
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
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200 {{ !$user->isVerified() ? 'opacity-50 cursor-not-allowed' : 'hover:border-blue-300' }}"
               {{ !$user->isVerified() ? 'onclick="event.preventDefault(); showVerificationAlert()"' : '' }}>
                <div class="flex-shrink-0">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-plus text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">New Reservation</h3>
                    <p class="text-xs text-gray-600">Book a facility</p>
                </div>
                @if(!$user->isVerified())
                    <div class="ml-auto">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                @endif
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

    <!-- Recent Activity (Placeholder) -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Recent Activity</h2>
        <div class="text-center py-8">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-clock text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-500">No recent activity to show</p>
            <p class="text-sm text-gray-400 mt-1">Your reservation activities will appear here</p>
        </div>
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
