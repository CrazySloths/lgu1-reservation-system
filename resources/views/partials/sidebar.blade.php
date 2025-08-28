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
    <div id="admin-sidebar" class="fixed left-0 top-0 h-full w-64 bg-lgu-headline shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50 overflow-hidden flex flex-col">
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
            <button id="sidebar-close" class="lg:hidden text-white hover:text-lgu-highlight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

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

        <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4">
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
                        <a href="#analytics" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            Analytics
                        </a>
                    </li>
                </ul>
            </div>

            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Facility Reservation</h4>
                <ul class="space-y-1">
                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z"/>
                                </svg>
                                Facility Directory and Calendar
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="{{ route('facility.list') }}" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Facility Location</a></li>
                            <li><a href="#road-projects" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Active Projects</a></li>
                            <li><a href="#road-maintenance" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Maintenance</a></li>
                            <li><a href="#traffic-management" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Traffic Management</a></li>
                        </ul>
                    </li>
                    
                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 3.636a1 1 0 010 1.414 7 7 0 000 9.9 1 1 0 11-1.414 1.414 9 9 0 010-12.728 1 1 0 011.414 0zm9.9 0a1 1 0 011.414 0 9 9 0 010 12.728 1 1 0 11-1.414-1.414 7 7 0 000-9.9 1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Online Booking and Approval
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="#water-overview" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">System Overview</a></li>
                            <li><a href="#water-quality" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Quality Monitoring</a></li>
                            <li><a href="#water-distribution" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Distribution Network</a></li>
                            <li><a href="#water-treatment" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Treatment Plants</a></li>
                        </ul>
                    </li>

                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.477.859h4z"/>
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
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
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
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
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

            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Settings</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="#system-settings" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                            System Settings
                        </a>
                    </li>
                    <li>
                        <a href="#notifications" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                            </svg>
                            Notifications
                            <span class="ml-auto bg-lgu-tertiary text-white text-xs px-2 py-1 rounded-full">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="#backup-restore" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            Backup & Restore
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="p-4 border-t border-lgu-stroke flex-shrink-0">
            <div class="space-y-2">
                <button class="w-full flex items-center px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Help & Support
                </button>
                <button class="w-full flex items-center px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    Account Settings
                </button>
                <button id="logout-btn" class="w-full flex items-center px-3 py-2 text-sm text-gray-300 hover:text-lgu-tertiary hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                    </svg>
                    Logout
                </button>
            </div>
        </div>
    </div>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    <button id="sidebar-toggle" class="fixed top-4 left-4 z-50 lg:hidden bg-lgu-headline text-white p-2 rounded-lg shadow-lg hover:bg-lgu-stroke transition-colors duration-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

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
                    
                    submenu.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-180');
                    
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

            // Active link and navigation functionality
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');

                    if (href && (href.startsWith('http://') || href.startsWith('https://'))) {
                        // Allow external links to navigate normally. No e.preventDefault() here.
                        console.log('Navigating to external URL:', href);
                    } else {
                        // For internal links (e.g., #dashboard), prevent default and handle with JS.
                        e.preventDefault(); 
                        
                        // Remove active class from all links
                        sidebarLinks.forEach(l => l.classList.remove('active'));
                        
                        // Add active class to clicked link
                        this.classList.add('active');
                        
                        // Close sidebar on mobile after clicking a link
                        if (window.innerWidth < 1024) {
                            closeSidebar();
                        }
                        
                        // Simulate smooth scrolling for internal links
                        const targetElement = document.querySelector(href);
                        if (targetElement) {
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
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
                        if (Math.random() > 0.8) {
                            const currentCount = parseInt(badge.textContent);
                            badge.textContent = currentCount + Math.floor(Math.random() * 3);
                        }
                    }
                });
            }
            setInterval(updateNotificationBadges, 30000);

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
        });
    </script>
</body>
</html>