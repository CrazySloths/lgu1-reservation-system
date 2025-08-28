<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LGU</title>

    @vite('resources/css/app.css')

</head>
<body>
    
    @include('partials.sidebar')

    <div class="lg:ml-64 min-h-screen">
        <header class="bg-white shadow-sm border-b border-fray-200 sticky top-0 z-40">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center spcae-x-4">
                    <button id="mobile-sidebar-toggle" class="lg:hidden text-lgu-headline hover:text-lgu-highlihgt">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linescape="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-lgu-headline">Dashboard</h1>
                        <p class="text-sm text-lgu-paragraph">Welcome back, Admin</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:block relative">
                        <input type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="relative">
                        <button class="p-2 text-lgu-paragraph hover:text-lgu-headline relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 bg-lgu-tertiary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    @vite('resources/js/app.js')

</body>
</html>