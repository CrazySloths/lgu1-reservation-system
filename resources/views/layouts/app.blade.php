<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - LGU</title>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @php($hasVite = file_exists(public_path('build/manifest.json')))
    @if ($hasVite)
        @vite('resources/css/app.css')
    @endif

</head>
<body>
    
    @include('partials.sidebar')

    <div class="lg:ml-64 min-h-screen">
        <header class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-40">
            <div class="flex items-center justify-between px-4 py-4">
                <div class="flex items-center space-x-5">
                    <button id="mobile-sidebar-toggle" class="lg:hidden text-gray-700 hover:text-lgu-highlight mr-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                        <p class="text-sm text-gray-600">Welcome back, Admin</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:block relative">
                        <input type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent focus:bg-white transition-all shadow-sm">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
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
                        <button class="p-3 text-gray-700 hover:text-lgu-highlight relative bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 bg-lgu-tertiary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center shadow-lg animate-bounce">3</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    @if ($hasVite)
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

            // Search Input Enhancement
            const searchInput = document.querySelector('input[placeholder="Search..."]');
            if (searchInput) {
                searchInput.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-lgu-highlight');
                });
                
                searchInput.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-lgu-highlight');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>