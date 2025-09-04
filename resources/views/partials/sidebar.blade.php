<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sidebar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'lgu-bg': '#f2f7f5',
                        'lgu-headline': '#00473e',
                        'lgu-paragraph': '#475d5b',
                        'lgu-button': '#faae2b',
                        'lgu-button-text': '#00473e',
                        'lgu-stroke': '#00332c',
                        'lgu-main': '#f2f7f5',
                        'lgu-highlight': '#faae2b',
                        'lgu-secondary': '#ffa8ba',
                        'lgu-tertiary': '#fa5246'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-lgu-bg">
    <!-- Admin Sidebar -->
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
                    <a href="#" class="block px-4 py-2 text-sm text-lgu-paragraph hover:bg-lgu-bg">Account & Settings</a>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}" class="block" id="adminLogoutForm">
                        @csrf
                        <button type="button" onclick="confirmAdminLogout()" class="w-full text-left px-4 py-2 text-sm text-lgu-tertiary hover:bg-lgu-bg">Logout</button>
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

        <!-- Admin Profile Section -->
        <div class="p-6 border-b border-lgu-stroke">
            <div class="text-center">
                @auth
                    @php
                        $admin = Auth::user();
                        // Generate admin initials
                        $nameParts = explode(' ', $admin->name);
                        $firstName = $nameParts[0] ?? 'A';
                        $lastName = end($nameParts);
                        $adminInitials = strtoupper(
                            substr($firstName, 0, 1) . 
                            (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'D')
                        );
                    @endphp
                    <!-- Large Centered Admin Avatar -->
                    <div class="w-20 h-20 bg-lgu-highlight rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg border-2 border-lgu-button">
                        <span class="text-lgu-button-text font-bold text-2xl">{{ $adminInitials }}</span>
                    </div>
                    
                    <!-- Admin Information -->
                    <div class="space-y-2">
                        <h3 class="text-white font-semibold text-base leading-tight">{{ $admin->name }}</h3>
                        <p class="text-gray-300 text-sm">{{ $admin->email }}</p>
                        
                        <!-- Admin Role Badge -->
                        <div class="flex items-center justify-center mt-3">
                            <div class="flex items-center px-3 py-1 rounded-full bg-blue-900/30">
                                <svg class="w-3 h-3 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                                <span class="text-blue-400 text-xs font-medium">{{ ucfirst($admin->role) }} Administrator</span>
                            </div>
                </div>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4">
            <!-- Dashboard -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Main</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('home') }}" class="sidebar-link active flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reservations.index') }}" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Reservation Review
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payment-slips.index') }}" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                            Payment Management
                        </a>
                    </li>
                    <li>
                        <a href="#analytics" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            Analytics
                        </a>
                    </li>
                </ul>
            </div>

            <!-- City Event Management -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">City Event Management</h4>
                <ul class="space-y-1">
                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 4a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1V5a1 1 0 00-1-1H5zm0 2v6h10V6H5z" clip-rule="evenodd"/>
                                    <path d="M3 4a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V4z"/>
                                </svg>
                                Official City Events
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="#new-city-event" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Book City Event</a></li>
                            <li><a href="#city-event-calendar" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>City Event Calendar</a></li>
                            <li><a href="#mayor-permissions" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 4a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>Mayor's Authorization</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
                    
            <!-- Citizen Reservation Oversight -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Citizen Reservation Management</h4>
                <ul class="space-y-1">
                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Approval & Oversight
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="{{ route('bookings.approval') }}" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Pending Approvals</a></li>
                            <li><a href="{{ route('reservation.status') }}" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>All Reservations</a></li>
                            <li><a href="#conflict-resolution" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>Schedule Conflicts</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Facility Management -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Facility Administration</h4>
                <ul class="space-y-1">
                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 16a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v10zM6 6h8v10H6V6z" clip-rule="evenodd"/>
                                    <path d="M2 8a2 2 0 012-2h1V4a2 2 0 012-2h6a2 2 0 012 2v2h1a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8z"/>
                                </svg>
                                Facility Management
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="{{ route('facility.list') }}" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"/></svg>Manage Facilities</a></li>
                            <li><a href="{{ route('calendar') }}" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>Calendar Overview</a></li>
                            <li><a href="#facility-maintenance" class="sidebar-link flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/></svg>Maintenance Logs</a></li>
                        </ul>
                    </li>


                </ul>
            </div>


            <!-- Reports & Analytics -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Reports</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('forecast') }}" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            Usage Analytics
                        </a>
                    </li>
                    <li>
                        <a href="#monthly-reports" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 01-1 1H8a1 1 0 110-2h4a1 1 0 011 1zm-1 4a1 1 0 100-2H8a1 1 0 100 2h4z" clip-rule="evenodd"/>
                            </svg>
                            Monthly Reports
                        </a>
                    </li>
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

    <!-- Sidebar JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const dropdownButtons = document.querySelectorAll('.sidebar-dropdown');
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            // Mobile sidebar toggle functionality
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }

            // Event listeners for mobile sidebar
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarClose.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Dropdown functionality
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const submenu = this.nextElementSibling;
                    const arrow = this.querySelector('svg:last-child');
                    
                    // Toggle submenu
                    submenu.classList.toggle('hidden');
                    
                    // Rotate arrow
                    arrow.classList.toggle('rotate-180');
                    
                    // Close other dropdowns
                    dropdownButtons.forEach(otherButton => {
                        if (otherButton !== this) {
                            const otherSubmenu = otherButton.nextElementSibling;
                            const otherArrow = otherButton.querySelector('svg:last-child');
                            otherSubmenu.classList.add('hidden');
                            otherArrow.classList.remove('rotate-180');
                        }
                    });
                });
            });

            // Active link functionality
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    
                    
                    // Remove active class from all links
                    sidebarLinks.forEach(l => l.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Close sidebar on mobile after clicking a link
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                    
                    // Simulate navigation (replace with actual routing logic)
                    const href = this.getAttribute('href');
                    console.log('Navigating to:', href);
                    
                    // You can add actual navigation logic here
                    // For example: window.location.hash = href;
                });
            });

            // Admin SweetAlert2 logout confirmation
            window.confirmAdminLogout = function() {
                Swal.fire({
                    title: 'Sign Out?',
                    text: "You will be logged out of the administrative system.",
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
                            text: 'Thank you for using the LGU1 Admin Portal!',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Submit the logout form
                            document.getElementById('adminLogoutForm').submit();
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

            // Notification badge updates (simulate real-time updates)
            function updateNotificationBadges() {
                const badges = document.querySelectorAll('.sidebar-link span');
                badges.forEach(badge => {
                    if (badge.textContent && !isNaN(badge.textContent)) {
                        // Simulate random updates for demo purposes
                        if (Math.random() > 0.8) {
                            const currentCount = parseInt(badge.textContent);
                            badge.textContent = currentCount + Math.floor(Math.random() * 3);
                        }
                    }
                });
            }

            // Update badges every 30 seconds (for demo purposes)
            setInterval(updateNotificationBadges, 30000);

            // Smooth scrolling for anchor links
            document.addEventListener('click', function(e) {
                if (e.target.matches('a[href^="#"]')) {
                    e.preventDefault();
                    const targetId = e.target.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // --- Settings Dropdown ---
        const settingsButton = document.getElementById('settings-button');
        const settingsDropdown = document.getElementById('settings-dropdown');

        if (settingsButton) {
            settingsButton.addEventListener('click', function(event) {
                event.stopPropagation();
                settingsDropdown.classList.toggle('hidden');
            });
        }

        // Close dropdown settings
        window.addEventListener('click', function(event) {
            if (settingsDropdown && !settingsDropdown.classList.contains('hidden') && !settingsButton.contains(event.target)) {
                settingsDropdown.classList.add('hidden');
            }
        });

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
</body>
</html>