<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LGU1 Citizen Portal')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    @auth
    <!-- Navigation Bar for Authenticated Users -->
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-white text-xl font-bold">🏛️ LGU1 Citizen Portal</h1>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('citizen.dashboard') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('citizen.dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('citizen.reservations') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('citizen.reservations') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-calendar-plus mr-1"></i> New Reservation
                    </a>
                    <a href="{{ route('citizen.reservation.history') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('citizen.reservation.history') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-history mr-1"></i> My Reservations
                    </a>
                    <a href="{{ route('citizen.profile') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('citizen.profile') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-user mr-1"></i> Profile
                    </a>
                    
                    <!-- User Dropdown -->
                    <div class="relative ml-3">
                        <div class="flex items-center text-white">
                            <span class="text-sm mr-2">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('citizen.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="@auth py-6 @else min-h-screen flex items-center justify-center @endauth">
        <div class="@auth max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 @else w-full max-w-md px-4 @endauth">
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: '{{ session('error') }}',
                            showConfirmButton: true
                        });
                    });
                </script>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="@auth mt-12 @else mt-8 @endauth bg-gray-800 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} Local Government Unit 1 - Citizen Portal. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
