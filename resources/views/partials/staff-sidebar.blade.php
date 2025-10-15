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
<div id="staff-sidebar" class="fixed left-0 top-0 h-full w-64 bg-lgu-headline shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50 overflow-hidden flex flex-col" style="background-color: #00473e!important;">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-4 border-b border-lgu-stroke" style="border-color: #00332c!important;">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-lgu-highlight" style="border-color: #faae2b!important;">
                <img src="{{ asset('image/logo.jpg') }}" alt="LGU Logo" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-white font-bold text-sm">Local Government Unit</h2>
                <p class="text-gray-300 text-xs">LGU1 - Staff Portal</p>
            </div>
        </div>
        <div class="relative">
            <button id="settings-button" class="p-2 text-lgu-paragraph text-white" style="color: white!important;">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
            </button>
            <div id="settings-dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <a href="#" class="block px-4 py-2 text-sm text-lgu-paragraph hover:bg-lgu-bg">Help & Support</a>
                <a href="#" class="block px-4 py-2 text-sm text-lgu-paragraph hover:bg-lgu-bg">Account & Settings</a>
                {{-- AUTHENTICATION DISABLED FOR LOCAL DEVELOPMENT --}}
                {{-- <div class="border-t border-gray-200 my-1"></div>
                <form method="POST" action="{{ route('logout') }}" class="block" id="staffLogoutForm">
                    @csrf
                    <button type="button" onclick="confirmStaffLogout()" class="w-full text-left px-4 py-2 text-sm text-lgu-tertiary hover:bg-lgu-bg">Logout</button>
                </form> --}}
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
    <div class="p-6 border-b border-lgu-stroke" style="border-color: #00332c!important;">
        <div class="text-center">
            @php
                // Static Staff Authentication (Bypass database issues)
                $staff = null;
                
                // Try Laravel Auth first (if working)
                try {
                    if (class_exists('Illuminate\Support\Facades\Auth')) {
                        $staff = Auth::user();
                    }
                } catch (Exception $e) {
                    // Laravel Auth failed, continue to static auth
                }
                
                // If Laravel Auth fails, use static authentication from session
                if (!$staff) {
                    // Check session for static staff (set by SsoController)
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    if (isset($_SESSION['static_staff_user'])) {
                        $staffData = $_SESSION['static_staff_user'];
                        $staff = (object) $staffData;
                    }
                    // Fallback: Create staff from URL parameters
                    elseif (request()->has('user_id') || request()->has('username')) {
                        $userId = request()->get('user_id', 50);
                        $username = request()->get('username', 'Staff Member');
                        
                        // Extract clean username (remove extra chars)
                        $cleanUsername = str_replace(['Staff-Facilities123', '-Facilities123'], '', $username);
                        $cleanUsername = ucfirst(trim($cleanUsername, '-'));
                        if (empty($cleanUsername) || $cleanUsername === 'Staff') {
                            $cleanUsername = 'Staff Member';
                        }
                        
                        $staff = (object) [
                            'id' => $userId,
                            'name' => $cleanUsername,
                            'email' => 'staff@lgu1.com',
                            'role' => 'staff'
                        ];
                    }
                    // Final fallback: Default staff for staff routes
                    elseif (str_contains(request()->url(), '/staff/')) {
                        $staff = (object) [
                            'id' => 50,
                            'name' => 'Staff Member',
                            'email' => 'staff@lgu1.com',
                            'role' => 'staff'
                        ];
                    }
                }
            @endphp
            
            @if($staff && $staff->role === 'staff')
                @php
                    // Generate staff initials
                    $nameParts = explode(' ', $staff->name);
                    $firstName = $nameParts[0] ?? 'S';
                    $lastName = end($nameParts);
                    $staffInitials = strtoupper(
                        substr($firstName, 0, 1) . 
                        (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'T')
                    );
                @endphp
                
                <!-- Large Centered Staff Avatar -->
                <div class="w-20 h-20 bg-lgu-highlight rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg border-2 border-lgu-button" style="background: #faae2b!important; border-color: #faae2b!important;">
                    <span class="text-lgu-button-text font-bold text-2xl" style="color: #00473e!important;">{{ $staffInitials }}</span>
                </div>
                
                <!-- Staff Information -->
                <div class="space-y-2">
                    <h3 class="text-white font-semibold text-base leading-tight">{{ $staff->name }}</h3>
                    <p class="text-gray-300 text-sm">{{ $staff->email }}</p>
                    
                    <!-- Staff Role Badge -->
                    <div class="flex items-center justify-center mt-3">
                        <div class="flex items-center px-3 py-1 rounded-full bg-purple-900/30">
                            <svg class="w-3 h-3 text-purple-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-purple-400 text-xs font-medium">{{ ucfirst($staff->role) }} Verification</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4">
        <!-- Main Navigation -->
        <div class="px-4 mb-6">
            <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Main</h4>
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
                       class="sidebar-link {{ isActiveStaffRoute(['staff.verification']) ? 'active' : '' }} flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Document Verification
                    </a>
                </li>
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

        <!-- System -->
        <div class="px-4 mb-6">
            <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">System</h4>
            <ul class="space-y-1">
                <li>
                    <a href="#" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Help & Support
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Mobile Sidebar Overlay -->
<div id="staff-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

<!-- Mobile Sidebar Toggle Button -->
<button id="staff-sidebar-toggle" class="fixed top-4 left-4 z-50 lg:hidden bg-lgu-headline text-white p-2 rounded-lg shadow-lg hover:bg-lgu-stroke transition-colors duration-200">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<!-- Sidebar JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('staff-sidebar');
        const sidebarToggle = document.getElementById('staff-sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebarOverlay = document.getElementById('staff-sidebar-overlay');
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const settingsButton = document.getElementById('settings-button');
        const settingsDropdown = document.getElementById('settings-dropdown');

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

        // Settings dropdown functionality
        if (settingsButton) {
            settingsButton.addEventListener('click', function(event) {
                event.stopPropagation();
                settingsDropdown.classList.toggle('hidden');
            });
        }

        // Close dropdown when clicking elsewhere
        window.addEventListener('click', function(event) {
            if (settingsDropdown && !settingsDropdown.classList.contains('hidden') && 
                settingsButton && !settingsButton.contains(event.target)) {
                settingsDropdown.classList.add('hidden');
            }
        });

        // Staff logout confirmation
        window.confirmStaffLogout = function() {
            Swal.fire({
                title: 'Sign Out?',
                text: "You will be logged out of the staff verification system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fa5246',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, sign me out',
                cancelButtonText: 'Cancel',
                background: '#ffffff',
                customClass: {
                    title: 'text-gray-900',
                    content: 'text-gray-600'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Signing out...',
                        text: 'Thank you for your verification work!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        document.getElementById('staffLogoutForm').submit();
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

        // CSS for active states
        const style = document.createElement('style');
        style.textContent = `
            .sidebar-link {
                color: #9CA3AF !important;
            }
            
            .sidebar-link:hover {
                color: #FFFFFF !important;
                background-color: #00332c !important;
            }
            
            .sidebar-link.active {
                color: #faae2b !important;
                background-color: #00332c !important;
                border-left: 3px solid #faae2b !important;
            }
            
            /* Custom scrollbar for sidebar */
            #staff-sidebar nav::-webkit-scrollbar {
                width: 4px;
            }
            
            #staff-sidebar nav::-webkit-scrollbar-track {
                background: #00332c !important;
            }
            
            #staff-sidebar nav::-webkit-scrollbar-thumb {
                background: #faae2b !important;
                border-radius: 2px;
            }
            
            #staff-sidebar nav::-webkit-scrollbar-thumb:hover {
                background: #e09900 !important;
            }
        `;
        document.head.appendChild(style);
    });
</script>
