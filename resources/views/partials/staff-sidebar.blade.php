@php
    // Helper function to determine if a route is active
    function isActiveStaffRoute($routeNames, $exactMatch = false) {
        $currentRoute = Route::currentRouteName();
        
        if (is_string($routeNames)) {
            $routeNames = [$routeNames];
        }
        
        foreach ($routeNames as $routeName) {
            if ($exactMatch) {
                if ($currentRoute === $routeName) {
                    return true;
                }
            } else {
                if (str_starts_with($currentRoute, $routeName)) {
                    return true;
                }
            }
        }
        
        return false;
    }
@endphp

<!-- Staff Sidebar -->
<div id="admin-sidebar" class="fixed left-0 top-0 h-full w-64 bg-lgu-headline shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50 overflow-hidden flex flex-col">
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
            <button id="settings-button" class="p-2 text-lgu-paragraph text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
            </button>
            <div id="settings-dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <a href="#" class="block px-4 py-2 text-sm text-lgu-paragraph hover:bg-lgu-bg">Help & Support</a>
                <div class="border-t border-gray-200 my-1"></div>
                <form method="POST" action="{{ route('logout') }}" class="block" id="staffLogoutForm">
                    @csrf
                    <button type="button" onclick="confirmStaffLogout()" class="w-full text-left px-4 py-2 text-sm text-lgu-tertiary hover:bg-lgu-bg">Logout</button>
                </form>
            </div>
        </div>
        <!-- Close button for mobile -->
        <button id="sidebar-close" class="lg:hidden text-white hover:text-lgu-highlight">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Staff Profile Section -->
    <div class="p-6 border-b border-lgu-stroke">
        <div class="text-center">
            @auth
                @php
                    $staff = Auth::user();
                    // Generate staff initials
                    $nameParts = explode(' ', $staff->name);
                    $firstName = $nameParts[0] ?? 'S';
                    $lastName = end($nameParts);
                    $initials = strtoupper(substr($firstName, 0, 1) . (($lastName !== $firstName) ? substr($lastName, 0, 1) : ''));
                @endphp
                
                <div class="w-16 h-16 rounded-full bg-lgu-button text-lgu-button-text mx-auto mb-3 flex items-center justify-center text-xl font-bold">
                    {{ $initials }}
                </div>
                
                <h3 class="text-white font-semibold text-lg">{{ $staff->name }}</h3>
                <p class="text-gray-300 text-sm mb-2">{{ $staff->email }}</p>
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd"/>
                    </svg>
                    Staff
                </span>
            @endauth
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 py-4 space-y-6 overflow-y-auto">
        <!-- Main Navigation -->
        <div class="px-4 mb-6">
            <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">MAIN</h4>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('staff.dashboard') }}" 
                       class="sidebar-link {{ isActiveStaffRoute(['staff.dashboard'], true) ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.verification.index') }}" 
                       class="sidebar-link {{ isActiveStaffRoute(['staff.verification'], false) ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Requirement Review
                        @php
                            $pendingCount = \App\Models\Booking::where('status', 'pending')->whereNull('staff_verified_by')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 font-semibold">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Staff Tools -->
        <div class="px-4 mb-6">
            <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">STAFF TOOLS</h4>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('staff.stats') }}" 
                       class="sidebar-link {{ isActiveStaffRoute(['staff.stats'], true) ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        My Statistics
                    </a>
                </li>
            </ul>
        </div>
        
    </nav>
</div>

<!-- Overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

<script>
    // Staff Sidebar Functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Settings dropdown toggle
        const settingsButton = document.getElementById('settings-button');
        const settingsDropdown = document.getElementById('settings-dropdown');
        
        if (settingsButton && settingsDropdown) {
            settingsButton.addEventListener('click', function(e) {
                e.preventDefault();
                settingsDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!settingsButton.contains(e.target) && !settingsDropdown.contains(e.target)) {
                    settingsDropdown.classList.add('hidden');
                }
            });
        }

        // Mobile sidebar toggle
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebar = document.getElementById('admin-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (sidebarClose) {
            sidebarClose.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        }
    });

    // Staff SweetAlert2 logout confirmation
    window.confirmStaffLogout = function() {
        Swal.fire({
            title: 'Sign Out?',
            text: "You will be logged out of the Staff Portal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fa5246',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, sign me out',
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
                    title: 'Signing out...',
                    text: 'Thank you for using the LGU1 Staff Portal!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Submit the logout form
                    document.getElementById('staffLogoutForm').submit();
                });
            }
        });
    };

    // CSS for active states and transitions
    const style = document.createElement('style');
    style.textContent = `
        .sidebar-link {
            color: #9CA3AF;
        }
        
        .sidebar-link:hover {
            color: #FFFFFF;
            background-color: #00332c;
        }
        
        .sidebar-link.active {
            color: #faae2b;
            background-color: #00332c;
            border-left: 3px solid #faae2b;
        }
        
        .sidebar-submenu {
            transition: all 0.3s ease-in-out;
        }
        
        .rotate-180 {
            transform: rotate(180deg);
        }
        
        /* Custom scrollbar for sidebar */
        #admin-sidebar nav::-webkit-scrollbar {
            width: 4px;
        }
        
        #admin-sidebar nav::-webkit-scrollbar-track {
            background: #00332c;
        }
        
        #admin-sidebar nav::-webkit-scrollbar-thumb {
            background: #faae2b;
            border-radius: 2px;
        }
        
        #admin-sidebar nav::-webkit-scrollbar-thumb:hover {
            background: #e09900;
        }
    `;
    document.head.appendChild(style);
</script>
