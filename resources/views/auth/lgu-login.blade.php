<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGU1 Admin Portal - Authentication Required</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'lgu-primary': '#2563eb',
                        'lgu-secondary': '#1e40af',
                        'lgu-highlight': '#3b82f6',
                        'lgu-success': '#059669',
                        'lgu-warning': '#d97706',
                        'lgu-error': '#dc2626',
                        'lgu-headline': '#1f2937',
                        'lgu-paragraph': '#4b5563',
                        'lgu-stroke': '#e5e7eb',
                        'lgu-fill': '#f9fafb',
                        'lgu-background': '#ffffff',
                        'lgu-white': '#ffffff',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-lgu-primary rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h6m-3 7h6"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-lgu-headline">LGU1 Admin Portal</h1>
            <p class="text-lgu-paragraph mt-2">Public Facilities Reservation System</p>
        </div>

        <!-- Authentication Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-lgu-headline mb-2">Authentication Required</h2>
                <p class="text-sm text-lgu-paragraph">
                    Please access this portal through the official LGU1 authentication system.
                </p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Authentication Error</h3>
                            @foreach($errors->all() as $error)
                                <p class="text-sm text-red-600 mt-1">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-green-600">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Authentication Instructions -->
            <div class="space-y-4">
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Access Instructions</h3>
                            <ul class="text-sm text-blue-600 space-y-1">
                                <li>• Access must be through the official LGU1 portal</li>
                                <li>• You will be redirected here automatically after authentication</li>
                                <li>• Contact your IT administrator for access credentials</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-amber-800 mb-2">Security Notice</h3>
                            <p class="text-sm text-amber-600">
                                This is a secure government system. All access attempts are logged and monitored.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Citizen Portal Link -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-lgu-paragraph mb-3">
                        Looking for the citizen portal?
                    </p>
                    <a href="{{ route('citizen.login') }}" 
                       class="inline-flex items-center text-sm text-lgu-highlight hover:text-lgu-secondary font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Citizen Login Portal
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-lgu-paragraph">
            <p>&copy; {{ date('Y') }} Local Government Unit 1. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Check for URL parameters that might indicate an authentication attempt
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('user_id');
            const token = urlParams.get('token');
            
            if (userId && token) {
                // Redirect to token authentication handler
                window.location.href = '{{ route("admin.lgu.auth") }}?' + window.location.search;
            }
        });
    </script>
</body>
</html>
