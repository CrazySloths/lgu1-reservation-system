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

            <!-- Infrastructure Management -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Infrastructure</h4>
                <ul class="space-y-1">
                    <li>
                        <button class="sidebar-dropdown w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-lgu-stroke rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z"/>
                                </svg>
                                Roads & Transport
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <ul class="sidebar-submenu hidden ml-8 mt-2 space-y-1">
                            <li><a href="#roads-overview" class="sidebar-link block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg">Overview</a></li>
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
                                Water Systems
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
                                Power & Energy
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
                                Waste Management
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

            <!-- Project Management -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Projects</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="#project-overview" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3h4v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45.75a2.5 2.5 0 00-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Project Overview
                        </a>
                    </li>
                    <li>
                        <a href="#active-projects" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Active Projects
                            <span class="ml-auto bg-lgu-highlight text-lgu-button-text text-xs px-2 py-1 rounded-full">12</span>
                        </a>
                    </li>
                    <li>
                        <a href="#project-planning" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Planning Phase
                            <span class="ml-auto bg-lgu-secondary text-white text-xs px-2 py-1 rounded-full">5</span>
                        </a>
                    </li>
                    <li>
                        <a href="#completed-projects" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Completed
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Service Management -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Services</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="#service-requests" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Service Requests
                            <span class="ml-auto bg-lgu-tertiary text-white text-xs px-2 py-1 rounded-full">23</span>
                        </a>
                    </li>
                    <li>
                        <a href="#maintenance-schedule" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Maintenance Schedule
                        </a>
                    </li>
                    <li>
                        <a href="#emergency-response" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Emergency Response
                        </a>
                    </li>
                    <li>
                        <a href="#permits-licenses" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            Permits & Licenses
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Financial Management -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Financial</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="#budget-overview" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            </svg>
                            Budget Overview
                        </a>
                    </li>
                    <li>
                        <a href="#billing-payments" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                                                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                            Billing & Payments
                        </a>
                    </li>
                    <li>
                        <a href="#expense-tracking" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"/>
                            </svg>
                            Expense Tracking
                        </a>
                    </li>
                    <li>
                        <a href="#financial-reports" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4-1a1 1 0 011-1v5a1 1 0 11-2 0v-5a1 1 0 011 1z" clip-rule="evenodd"/>
                            </svg>
                            Financial Reports
                        </a>
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

            <!-- Reports & Analytics -->
            <div class="px-4 mb-6">
                <h4 class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Reports</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="#system-reports" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"/>
                            </svg>
                            System Reports
                        </a>
                    </li>
                    <li>
                        <a href="#performance-metrics" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                            </svg>
                            Performance Metrics
                        </a>
                    </li>
                    <li>
                        <a href="#audit-logs" class="sidebar-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Audit Logs
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Settings -->
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

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-lgu-stroke flex-shrink-0">


            <!-- User Actions -->
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