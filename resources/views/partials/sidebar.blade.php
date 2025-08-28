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
                    <h2 class="text-white font-bold text-sm">LGU Admin</h2>
                    <p class="text-gray-300 text-xs">Infrastructure Panel</p>
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
                    <a href="#" class="block px-4 py-2 text-sm text-lgu-tertiary hover:bg-lgu-bg">Logout</a>
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
        <div class="p-4 border-b border-lgu-stroke">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-lgu-highlight rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-lgu-button-text" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm">Admin User</h3>
                    <p class="text-gray-300 text-xs">Infrastructure Manager</p>
                    <div class="flex items-center mt-1">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-green-400 text-xs">Online</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4">
            <!-- Dashboard -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Main</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="#dashboard" class="sidebar-link active flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            Dashboard
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

            <!-- Facility Management -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Facility Reservation</h4>
                <ul class="space-y-1">
                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 2a1 1 0 000 2h8a1 1 0 100-2H6zM3 6a2 2 0 012-2h10a2 2 0 012 2v1H3V6zm0 3h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                </svg>
                                Facility Directory and Calendar
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="#roads-overview" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Facility List</a></li>
                            <li><a href="#road-projects" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Facility Location</a></li>
                            <li><a href="#road-maintenance" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Calendar Interface</a></li>
                            <li><a href="#traffic-management" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Availabilty Status</a></li>
                            <li><a href="#traffic-management" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Facilty Booking</a></li>
                        </ul>
                    </li>
                    
                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h5.586A2 2 0 0013 17.414l3.707-3.707A1 1 0 0017 13V4a2 2 0 00-2-2H6z"/>
                                </svg>
                                Online Booking and Approval
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="#water-overview" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Reservation Request</a></li>
                            <li><a href="#water-quality" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Suggesting Booking</a></li>
                            <li><a href="#water-distribution" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Document Upload Verification</a></li>
                            <li><a href="#water-treatment" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Treatment Plants</a></li>
                        </ul>
                    </li>

                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v1H2V6zm0 3h16v5a2 2 0 01-2 2H4a2 2 0 01-2-2V9zm3 3a1 1 0 100 2h2a1 1 0 100-2H5z"/>
                                </svg>
                                Usage Fee and Payment
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="#power-grid" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Power Grid</a></li>
                            <li><a href="#street-lighting" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Street Lighting</a></li>
                            <li><a href="#renewable-energy" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Renewable Energy</a></li>
                            <li><a href="#energy-consumption" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Consumption Monitor</a></li>
                        </ul>
                    </li>

                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.518 11.598c.75 1.336-.213 3.003-1.743 3.003H3.482c-1.53 0-2.493-1.667-1.743-3.003L8.257 3.1zM11 14a1 1 0 11-2 0 1 1 0 012 0zm-1-2a1 1 0 01-1-1V7a1 1 0 112 0v4a1 1 0 01-1 1z" clip-rule="evenodd"/>
                                </svg>
                                Schedule Conflict Alert
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="#waste-collection" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Collection Routes</a></li>
                            <li><a href="#recycling-centers" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Recycling Centers</a></li>
                            <li><a href="#disposal-sites" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Disposal Sites</a></li>
                            <li><a href="#waste-analytics" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Waste Analytics</a></li>
                        </ul>
                    </li>

                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 3a1 1 0 000 2h1v11a1 1 0 102 0V5h1a1 1 0 100-2H3zm6 4a1 1 0 011-1h1v9a1 1 0 11-2 0V7zm5-3a1 1 0 000 2h1v11a1 1 0 102 0V4h1a1 1 0 100-2h-4z"/>
                                </svg>
                                Usage Reports and Feedback
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="#waste-collection" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Collection Routes</a></li>
                            <li><a href="#recycling-centers" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Recycling Centers</a></li>
                            <li><a href="#disposal-sites" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Disposal Sites</a></li>
                            <li><a href="#waste-analytics" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Waste Analytics</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            

            <!-- User Management -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Users</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="#user-management" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            User Management
                        </a>
                    </li>
                    <li>
                        <a href="#roles-permissions" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Roles & Permissions
                        </a>
                    </li>
                    <li>
                        <a href="#citizen-accounts" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Citizen Accounts
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
                sidebarOverlay.classList.toggle('hidden');
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
                    e.preventDefault();
                    
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

            // Logout functionality
            document.getElementById('logout-btn').addEventListener('click', function() {
                if (confirm('Are you sure you want to logout?')) {
                    // Add logout logic here
                    alert('Logging out...');
                    // window.location.href = '/login';
                }
            });



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