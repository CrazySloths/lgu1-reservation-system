<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Portal - LGU</title>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    @if (file_exists(public_path('build/manifest.json')))
        @vite('resources/css/app.css')
    @endif

</head>
<body>
    
    @include('partials.staff-sidebar')

    <div class="lg:ml-64 min-h-screen">
        <header class="bg-white shadow-lg border-b border-gray-300 sticky top-0 z-40" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)!important;">
            <div class="flex items-center justify-between px-4 py-4">
                <div class="flex items-center space-x-5">
                    <button id="mobile-sidebar-toggle" class="lg:hidden text-gray-700 hover:text-orange-500 mr-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Staff Portal</h1>
                        <p class="text-sm text-gray-600">Welcome back, @php
                            // Inline staff name calculation to avoid scope issues
                            if (session_status() === PHP_SESSION_NONE) {
                                session_start();
                            }
                            
                            $staffName = 'Staff Member'; // Default
                            
                            if (isset($_SESSION['static_staff_user']) && isset($_SESSION['static_staff_user']['name'])) {
                                $staffName = $_SESSION['static_staff_user']['name'];
                            } elseif (request()->has('username')) {
                                $username = request()->get('username');
                                $cleanUsername = str_replace(['Staff-Facilities123', '-Facilities123'], '', $username);
                                $cleanUsername = ucfirst(trim($cleanUsername, '-'));
                                if (!empty($cleanUsername) && $cleanUsername !== 'Staff') {
                                    $staffName = $cleanUsername;
                                }
                            }
                            
                            echo $staffName;
                        @endphp</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Enhanced Search Bar -->
                    <div class="hidden md:block relative group">
                        <div class="relative">
                            <input type="text" placeholder="Search documents, verifications..." class="w-80 pl-12 pr-4 py-3 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent focus:bg-white transition-all duration-300 shadow-lg hover:shadow-xl" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-lgu-highlight transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <!-- Search Suggestions Dropdown -->
                            <div id="search-suggestions" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-50 max-h-60 overflow-y-auto">
                                <div class="p-2">
                                    <div class="px-3 py-2 text-xs text-gray-500 font-medium uppercase tracking-wider">Quick Search</div>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Pending Verifications
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                        </svg>
                                        My Statistics
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"/>
                                        </svg>
                                        Document Templates
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Quick Stats -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <div class="bg-gray-100 rounded-lg px-3 py-2">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-gray-700 font-medium">System Online</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <button class="p-3 text-gray-700 hover:text-orange-500 relative bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center shadow-lg animate-bounce" style="background: #ef4444!important;">2</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 lg:p-6 bg-gray-50 min-h-screen" style="background: #f9fafb!important;">
            @yield('content')
        </main>
    </div>

    @if (file_exists(public_path('build/manifest.json')))
        @vite('resources/js/app.js')
    @endif

    <!-- Enhanced Header JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced Mobile Sidebar Toggle
            const mobileToggle = document.getElementById('mobile-sidebar-toggle');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    // Add visual feedback
                    this.classList.add('scale-95');
                    setTimeout(() => {
                        this.classList.remove('scale-95');
                    }, 100);
                });
            }

            // Enhanced Search Input Functionality
            const searchInput = document.querySelector('input[placeholder*="Search"]');
            const searchSuggestions = document.getElementById('search-suggestions');
            
            if (searchInput && searchSuggestions) {
                // Focus/Blur effects
                searchInput.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.boxShadow = '0 10px 25px -3px rgba(250, 174, 43, 0.1), 0 4px 6px -2px rgba(250, 174, 43, 0.05)';
                    
                    // Show suggestions after a brief delay
                    setTimeout(() => {
                        if (this.value.length >= 2) {
                            searchSuggestions.classList.remove('hidden');
                        }
                    }, 200);
                });
                
                searchInput.addEventListener('blur', function(e) {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
                    
                    // Hide suggestions unless clicking on them
                    setTimeout(() => {
                        if (!searchSuggestions.contains(e.relatedTarget)) {
                            searchSuggestions.classList.add('hidden');
                        }
                    }, 150);
                });

                // Real-time search functionality
                searchInput.addEventListener('input', function() {
                    const value = this.value.toLowerCase();
                    if (value.length >= 2) {
                        searchSuggestions.classList.remove('hidden');
                        
                        // Filter suggestions based on input
                        const links = searchSuggestions.querySelectorAll('a');
                        links.forEach(link => {
                            const text = link.textContent.toLowerCase();
                            if (text.includes(value)) {
                                link.style.display = 'flex';
                                // Highlight matching text
                                const regex = new RegExp(`(${value})`, 'gi');
                                const originalText = link.querySelector('svg').nextSibling.textContent;
                                link.querySelector('svg').nextSibling.innerHTML = originalText.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
                            } else {
                                link.style.display = 'none';
                            }
                        });
                    } else {
                        searchSuggestions.classList.add('hidden');
                    }
                });

                // Enter key search
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const searchTerm = this.value.trim();
                        if (searchTerm) {
                            // Enhanced search functionality
                            this.style.backgroundColor = '#f0fdf4';
                            setTimeout(() => {
                                this.style.backgroundColor = 'white';
                                alert(`🔍 Searching for: "${searchTerm}"\n\nSearch functionality is coming soon! This will search through:\n• Pending verifications\n• Document templates\n• Staff records\n• Verification history`);
                            }, 200);
                        }
                    }
                });

                // ESC key to close suggestions
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        searchSuggestions.classList.add('hidden');
                        this.blur();
                    }
                });
            }

            // Click outside to close suggestions
            document.addEventListener('click', function(e) {
                if (searchSuggestions && !searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                    searchSuggestions.classList.add('hidden');
                }
            });
        });
    </script>

</body>
</html>
