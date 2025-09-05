<!-- Citizen Sidebar -->
<div id="citizen-sidebar" class="fixed left-0 top-0 h-full w-64 bg-lgu-headline shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50 overflow-hidden flex flex-col">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-4 border-b border-lgu-stroke">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-lgu-highlight">
                <img src="{{ asset('image/logo.jpg') }}" alt="LGU Logo" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-white font-bold text-sm">Local Government Unit</h2>
                <p class="text-gray-300 text-xs">LGU1</p>
            </div>
        </div>
        <div class="relative">
            <button id="citizen-settings-button" class="p-2 text-lgu-paragraph text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
            </button>
            <div id="citizen-settings-dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <a href="{{ route('citizen.profile') }}" class="block px-4 py-2 text-sm text-lgu-paragraph hover:bg-lgu-bg">Profile Settings</a>
                <a href="#help" class="block px-4 py-2 text-sm text-lgu-paragraph hover:bg-lgu-bg">Help & Support</a>
                <div class="border-t border-gray-200 my-1"></div>
                <form method="POST" action="{{ route('citizen.logout') }}" class="block" id="logoutForm">
                    @csrf
                    <button type="button" onclick="confirmLogout()" class="w-full text-left px-4 py-2 text-sm text-lgu-tertiary hover:bg-lgu-bg">Logout</button>
                </form>
            </div>
        </div>
        <!-- Close button for mobile -->
        <button id="citizen-sidebar-close" class="lg:hidden text-white hover:text-lgu-highlight">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Citizen Profile Section -->
    <div class="p-6 border-b border-lgu-stroke">
        <div class="text-center">
            @php
                $user = Auth::user();
            @endphp
            <!-- Large Centered Avatar -->
            <div class="w-20 h-20 bg-lgu-highlight rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="text-lgu-button-text font-bold text-2xl">{{ $user->avatar_initials }}</span>
            </div>
            
            <!-- User Information -->
            <div class="space-y-2">
                <h3 class="text-white font-semibold text-base leading-tight">{{ $user->full_name }}</h3>
                <p class="text-gray-300 text-sm break-all">{{ $user->email }}</p>
                
                <!-- Status Badge -->
                <div class="flex items-center justify-center mt-3">
                    <div class="flex items-center px-3 py-1 rounded-full bg-green-900/30">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-green-400 text-xs font-medium">
                            Active Account
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4">
        <!-- Main Navigation -->
        <div class="px-4 mb-6">
            <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Main</h4>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('citizen.dashboard') }}" class="citizen-sidebar-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('citizen.profile') }}" class="citizen-sidebar-link {{ request()->routeIs('citizen.profile*') ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        My Profile
                    </a>
                </li>
            </ul>
        </div>

        <!-- Facility Services -->
        <div class="px-4 mb-6">
            <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Facility Services</h4>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('citizen.reservations') }}" class="citizen-sidebar-link {{ request()->routeIs('citizen.reservations') ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        New Reservation
                    </a>
                </li>
                <li>
                    <a href="{{ route('citizen.reservation.history') }}" class="citizen-sidebar-link {{ request()->routeIs('citizen.reservation.history') ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Reservation History
                    </a>
                </li>
                <li>
                    <a href="{{ route('citizen.availability') }}" class="citizen-sidebar-link {{ request()->routeIs('citizen.availability') ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        View Availability
                    </a>
                </li>
                <li>
                    <a href="{{ route('citizen.bulletin.board') }}" class="citizen-sidebar-link {{ request()->routeIs('citizen.bulletin.board') ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Bulletin Board
                    </a>
                </li>
                <li>
                    <a href="{{ route('citizen.payment-slips.index') }}" class="citizen-sidebar-link {{ request()->routeIs('citizen.payment-slips*') ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        Payment Slips
                    </a>
                </li>
            </ul>
        </div>

        <!-- Support & Help -->
        <div class="px-4 mb-6">
            <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Support</h4>
            <ul class="space-y-1">
                <li>
                    <a href="#help-support" class="citizen-sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        Help & FAQ
                    </a>
                </li>
                <li>
                    <a href="#contact-support" class="citizen-sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                        </svg>
                        Contact Support
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Mobile Sidebar Overlay -->
<div id="citizen-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

<!-- Mobile Sidebar Toggle Button -->
<button id="citizen-sidebar-toggle" class="fixed top-4 left-4 z-50 lg:hidden bg-lgu-headline text-white p-2 rounded-lg shadow-lg hover:bg-lgu-stroke transition-colors duration-200">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<!-- Sidebar JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('citizen-sidebar');
    const sidebarToggle = document.getElementById('citizen-sidebar-toggle');
    const sidebarClose = document.getElementById('citizen-sidebar-close');
    const sidebarOverlay = document.getElementById('citizen-sidebar-overlay');
    const sidebarLinks = document.querySelectorAll('.citizen-sidebar-link');

    // Mobile sidebar toggle functionality
    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    }

    // Event listeners for mobile sidebar
    if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

    // Active link functionality
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Close sidebar on mobile after clicking a link
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });

    // Settings dropdown functionality
    const settingsButton = document.getElementById('citizen-settings-button');
    const settingsDropdown = document.getElementById('citizen-settings-dropdown');

    if (settingsButton) {
        settingsButton.addEventListener('click', function(event) {
            event.stopPropagation();
            settingsDropdown.classList.toggle('hidden');
        });
    }

    // Close settings dropdown when clicking outside
    window.addEventListener('click', function(event) {
        if (settingsDropdown && !settingsDropdown.classList.contains('hidden') && 
            settingsButton && !settingsButton.contains(event.target)) {
            settingsDropdown.classList.add('hidden');
        }
    });

    // SweetAlert2 logout confirmation
    window.confirmLogout = function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of your citizen account.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fa5246',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, log me out',
            cancelButtonText: 'Cancel',
            background: '#ffffff',
            customClass: {
                title: 'text-gray-900',
                content: 'text-gray-600',
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg',
                cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show logout success message
                Swal.fire({
                    title: 'Logging out...',
                    text: 'Thank you for using LGU1 Citizen Portal!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Submit the logout form
                    document.getElementById('logoutForm').submit();
                });
            }
        });
    };

    // Responsive sidebar behavior
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
        }
    });

    // CSS for active states and transitions
    const style = document.createElement('style');
    style.textContent = `
        .citizen-sidebar-link {
            color: #9CA3AF;
        }
        
        .citizen-sidebar-link:hover {
            color: #FFFFFF;
            background-color: #00332c;
        }
        
        .citizen-sidebar-link.active {
            color: #faae2b;
            background-color: #00332c;
            border-left: 3px solid #faae2b;
        }
        
        /* Custom scrollbar for sidebar */
        #citizen-sidebar nav::-webkit-scrollbar {
            width: 4px;
        }
        
        #citizen-sidebar nav::-webkit-scrollbar-track {
            background: #00332c;
        }
        
        #citizen-sidebar nav::-webkit-scrollbar-thumb {
            background: #faae2b;
            border-radius: 2px;
        }
        
        #citizen-sidebar nav::-webkit-scrollbar-thumb:hover {
            background: #e09900;
        }
    `;
    document.head.appendChild(style);
});
</script>
