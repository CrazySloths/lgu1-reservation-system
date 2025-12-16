@extends('layouts.master')

@section('content')
<!-- Staff Sidebar -->
<div id="staff-sidebar" class="fixed left-0 top-0 h-full w-64 bg-lgu-headline shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50 overflow-hidden flex flex-col">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-4 border-b border-lgu-stroke">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-lgu-highlight">
                <img src="{{ asset('assets/images/logo.png') }}" alt="LGU Logo" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-white font-bold text-sm">Local Government Unit</h2>
                <p class="text-gray-300 text-xs">LGU1</p>
            </div>
        </div>
        <div class="relative">
            <button id="staff-settings-button" class="p-2 text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
            </button>
            <div id="staff-settings-dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <a href="#" class="block px-4 py-2 text-sm text-lgu-paragraph hover:bg-lgu-bg">Help & Support</a>
                <div class="border-t border-gray-200 my-1"></div>
                <form method="POST" action="{{ route('logout') }}" class="block" id="staffLogoutForm">
                    @csrf
                    <button type="button" onclick="confirmStaffLogout()" class="w-full text-left px-4 py-2 text-sm text-lgu-tertiary hover:bg-lgu-bg">Logout</button>
                </form>
            </div>
        </div>
        <!-- Close button for mobile -->
        <button id="staff-sidebar-close" class="lg:hidden text-white hover:text-lgu-highlight">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Staff Profile Section with Expandable Details -->
    <div class="border-b border-lgu-stroke">
        @php
            $staff = (object) [
                'id' => session('user_id', 1),
                'name' => session('user_name', 'Staff User'),
                'email' => session('user_email', 'staff@lgu1.com'),
                'role' => 'staff'
            ];
            
            // Generate initials
            $nameParts = explode(' ', $staff->name);
            $firstName = $nameParts[0] ?? 'S';
            $lastName = end($nameParts);
            $staffInitials = strtoupper(
                substr($firstName, 0, 1) . 
                (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'T')
            );
        @endphp
        
        <!-- Compact Profile Header (Collapsed State) -->
        <div id="profile-compact" class="transition-all duration-300">
            <button onclick="toggleProfileExpanded()" class="w-full p-4 flex items-center justify-between hover:bg-lgu-stroke/30 transition-all duration-300 group">
                <div class="flex items-center space-x-3">
                    <!-- Small Avatar -->
                    <div class="w-10 h-10 bg-lgu-highlight rounded-full flex items-center justify-center shadow-md border-2 border-lgu-button transition-transform duration-300 group-hover:scale-110">
                        <span class="text-lgu-button-text font-bold text-base">{{ $staffInitials }}</span>
                    </div>
                    
                    <!-- Name and Email Label -->
                    <div class="text-left">
                        <h3 class="text-white font-semibold text-sm leading-tight">{{ $staff->name }}</h3>
                        <p class="text-gray-400 text-xs">{{ $staff->email }}</p>
                    </div>
                </div>
                
                <!-- Dropdown Arrow -->
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
        
        <!-- Expandable Full Profile Details (Maximized State) -->
        <div id="profile-expanded-details" class="hidden transition-all duration-500 ease-in-out">
            <button onclick="toggleProfileExpanded()" class="w-full px-6 pb-6 pt-4 text-center hover:bg-lgu-stroke/20 transition-all duration-300 rounded-lg">
                <!-- Large Centered Staff Avatar -->
                <div class="w-24 h-24 bg-lgu-highlight rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg border-4 border-lgu-button">
                    <span class="text-lgu-button-text font-bold text-3xl">{{ $staffInitials }}</span>
                </div>
                
                <!-- Full Profile Information -->
                <div class="space-y-2 mb-4">
                    <h3 class="text-white font-bold text-lg leading-tight">{{ $staff->name }}</h3>
                    <p class="text-gray-300 text-sm break-all">{{ $staff->email }}</p>
                    
                    <!-- Staff Role Badge -->
                    <div class="flex items-center justify-center mt-3">
                        <div class="flex items-center px-4 py-2 rounded-full bg-blue-900/40">
                            <svg class="w-4 h-4 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-blue-400 text-xs font-semibold">Staff Member</span>
                        </div>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4">
        <!-- Main -->
        <div class="px-4 mb-6">
            <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Main</h4>
            <ul class="space-y-1">
                @include('components.sidebar.staff-menu')
            </ul>
        </div>
    </nav>
</div>

<!-- Mobile Sidebar Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

<!-- Mobile Sidebar Toggle Button -->
<button id="sidebar-toggle" class="fixed top-4 left-4 z-50 lg:hidden bg-lgu-headline text-white p-2 rounded-lg shadow-lg hover:bg-lgu-stroke transition-colors duration-200">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<!-- Main Content (the rest of the page) -->
<div class="lg:ml-72">
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
        <div class="px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Staff Dashboard')</h1>
            <p class="text-sm text-gray-600">@yield('page-subtitle', 'Manage bookings and verifications')</p>
        </div>
    </header>

    <main class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-6 py-8">
            @yield('page-content')
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-4 px-6">
        <div class="flex justify-between items-center text-sm text-gray-600">
            <p>&copy; {{ date('Y') }} LGU Facility Reservation System. All rights reserved.</p>
            <p>Staff Portal</p>
        </div>
    </footer>
</div>

@include('components.sidebar.staff-script')

@push('scripts')
<script>
(function() {
    // Session timeout from Laravel config (in minutes, convert to milliseconds)
    const SESSION_TIMEOUT = {{ config('session.lifetime') }} * 60 * 1000; // Convert minutes to milliseconds
    let inactivityTimer;
    let lastActivityTime = Date.now();

    // Function to reset the inactivity timer
    function resetInactivityTimer() {
        lastActivityTime = Date.now();
        
        // Clear existing timer
        if (inactivityTimer) {
            clearTimeout(inactivityTimer);
        }

        // Set new timer for session timeout
        inactivityTimer = setTimeout(function() {
            // Force logout after session timeout period of inactivity
            forceLogout();
        }, SESSION_TIMEOUT);

        // Ping server to keep session alive (if user is active)
        updateServerSession();
    }

    // Function to force logout
    function forceLogout() {
        // Redirect to login page with timeout parameter
        window.location.href = '{{ route("login") }}?timeout=1';
    }

    // Update server session timestamp
    function updateServerSession() {
        fetch('{{ route("ping-session") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                last_activity: lastActivityTime
            })
        }).catch(function(error) {
            console.log('Session ping failed - may have expired');
        });
    }

    // Activities that reset the timer
    const activities = [
        'mousedown',
        'mousemove', 
        'keypress',
        'scroll',
        'touchstart',
        'click',
        'keydown',
        'wheel'
    ];

    // Throttle function to prevent too many calls
    let throttleTimeout;
    function throttledResetTimer() {
        if (!throttleTimeout) {
            resetInactivityTimer();
            throttleTimeout = setTimeout(function() {
                throttleTimeout = null;
            }, 5000); // Only reset every 5 seconds max
        }
    }

    // Attach listeners to reset timer on any activity
    activities.forEach(function(activity) {
        document.addEventListener(activity, throttledResetTimer, true);
    });

    // Also check on page visibility change
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            resetInactivityTimer();
        }
    });

    // Initialize timer on page load
    resetInactivityTimer();

    // Backup check: Verify session every 30 seconds
    setInterval(function() {
        const timeSinceActivity = Date.now() - lastActivityTime;
        
        // If somehow timer didn't fire, force logout
        if (timeSinceActivity >= SESSION_TIMEOUT) {
            forceLogout();
        }
    }, 30000); // Check every 30 seconds

    // Handle AJAX errors globally (if session expires during AJAX call)
    document.addEventListener('DOMContentLoaded', function() {
        // Monitor fetch requests
        const originalFetch = window.fetch;
        window.fetch = function() {
            return originalFetch.apply(this, arguments).then(function(response) {
                if (response.status === 401) {
                    // Session expired
                    forceLogout();
                }
                return response;
            });
        };
    });
})();
</script>
@endpush

@endsection

