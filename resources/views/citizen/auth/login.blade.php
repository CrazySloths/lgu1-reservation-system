@extends('citizen.layouts.app')

@section('title', 'Citizen Login - LGU1 Portal')

@section('content')
<div class="bg-white shadow-xl rounded-lg p-8 max-w-md mx-auto">
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-user-circle text-2xl text-blue-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
        <p class="text-gray-600 mt-1">Sign in to your citizen account</p>
    </div>

    <!-- Social Login Options -->
    <div class="mb-6">
        <div class="grid grid-cols-2 gap-3">
            <!-- Facebook Login -->
            <button type="button" onclick="loginWithFacebook()" 
                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                <i class="fab fa-facebook text-blue-600 mr-2"></i>
                <span class="text-sm font-medium text-gray-700">Facebook</span>
            </button>
            
            <!-- Google Login -->
            <button type="button" onclick="loginWithGoogle()" 
                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                <i class="fab fa-google text-red-500 mr-2"></i>
                <span class="text-sm font-medium text-gray-700">Google</span>
            </button>
        </div>

        <div class="relative mt-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Or continue with email</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('citizen.login.submit') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input id="email" 
                       name="email" 
                       type="email" 
                       autocomplete="email" 
                       required 
                       value="{{ old('email') }}"
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                       placeholder="Enter your email address">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input id="password" 
                       name="password" 
                       type="password" 
                       autocomplete="current-password" 
                       required
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                       placeholder="Enter your password">
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" 
                       name="remember" 
                       type="checkbox" 
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                    Remember me
                </label>
            </div>
            <a href="#" class="text-sm text-blue-600 hover:text-blue-500 hover:underline">
                Forgot password?
            </a>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Sign In
            </button>
        </div>
    </form>

    <!-- Additional Links -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('citizen.register') }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline">
                Register here
            </a>
        </p>
        
        <!-- Quick Demo Account Access -->
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-600 mb-2">Quick Demo Access:</p>
            <button type="button" onclick="fillDemoCredentials()" 
                    class="text-xs text-blue-600 hover:text-blue-800 underline">
                Use Test Account (citizen.test@email.com)
            </button>
        </div>
    </div>

    <!-- System Info -->
    <div class="mt-6 pt-4 border-t border-gray-200 text-center">
        <p class="text-xs text-gray-500">
            <i class="fas fa-info-circle mr-1"></i>
            <strong>Citizen Portal</strong> - Facility Reservations
        </p>
    </div>
</div>

@push('scripts')
<script>
// Social Media Login Functions
function loginWithFacebook() {
    Swal.fire({
        icon: 'info',
        title: 'Facebook Login',
        text: 'Facebook login integration will be available soon. Please use your email and password for now.',
        confirmButtonColor: '#3B82F6',
        confirmButtonText: 'OK'
    });
}

function loginWithGoogle() {
    Swal.fire({
        icon: 'info',
        title: 'Google Login',
        text: 'Google login integration will be available soon. Please use your email and password for now.',
        confirmButtonColor: '#3B82F6',
        confirmButtonText: 'OK'
    });
}

// Demo Account Helper
function fillDemoCredentials() {
    document.getElementById('email').value = 'citizen.test@email.com';
    document.getElementById('password').value = 'citizen123';
    
    // Add visual feedback
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    emailInput.classList.add('ring-2', 'ring-green-500', 'border-transparent');
    passwordInput.classList.add('ring-2', 'ring-green-500', 'border-transparent');
    
    // Remove feedback after 2 seconds
    setTimeout(() => {
        emailInput.classList.remove('ring-2', 'ring-green-500', 'border-transparent');
        passwordInput.classList.remove('ring-2', 'ring-green-500', 'border-transparent');
    }, 2000);
    
    Swal.fire({
        icon: 'success',
        title: 'Demo Credentials Filled!',
        text: 'You can now click "Sign In" to access the test account.',
        timer: 2000,
        showConfirmButton: false
    });
}

// Form enhancement - show loading state
document.querySelector('form').addEventListener('submit', function(e) {
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
    submitButton.disabled = true;
    
    // Re-enable button after delay in case of errors
    setTimeout(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    }, 10000);
});

// Auto-focus email input
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('email').focus();
});
</script>
@endpush
@endsection
