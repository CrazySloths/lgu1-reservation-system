@extends('citizen.layouts.app')

@section('title', 'Account Verification - LGU1 Portal')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-8 max-w-2xl mx-auto">
    <!-- Verification Header -->
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Account Verification</h2>
        <p class="text-gray-600 mt-2">Complete these security steps to activate your account</p>
    </div>

    <!-- Progress Indicators -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <!-- Email Verification -->
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium
                    {{ $user->email_verified ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                    @if($user->email_verified)
                        <i class="fas fa-check"></i>
                    @else
                        1
                    @endif
                </div>
                <span class="ml-3 text-sm font-medium {{ $user->email_verified ? 'text-green-600' : 'text-gray-900' }}">
                    Email Verification
                </span>
            </div>
            
            <!-- Progress Line -->
            <div class="flex-1 mx-4 h-0.5 {{ $user->email_verified ? 'bg-green-600' : 'bg-gray-300' }}"></div>
            
            <!-- Phone Verification -->
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium
                    {{ $user->phone_verified ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                    @if($user->phone_verified)
                        <i class="fas fa-check"></i>
                    @else
                        2
                    @endif
                </div>
                <span class="ml-3 text-sm font-medium {{ $user->phone_verified ? 'text-green-600' : 'text-gray-900' }}">
                    Phone Verification
                </span>
            </div>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <i class="fas fa-check-circle text-green-400"></i>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <div class="ml-3">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Development Mode: Show Verification Codes (only after email verification) -->
    @if(config('app.env') === 'local' && $user->email_verified && $user->phone_verification_code)
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <i class="fas fa-tools text-yellow-400"></i>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Development Mode - SMS Code</h3>
                    <div class="mt-2 text-sm text-yellow-700 space-y-2">
                        <div class="p-2 bg-yellow-100 rounded border">
                            <strong>SMS Code:</strong>
                            <span class="font-mono text-lg">{{ $user->phone_verification_code }}</span>
                            <br><small>Expires: {{ $user->phone_verification_sent_at?->addMinutes(10)->format('Y-m-d H:i:s') }}</small>
                            <br><small>Sent to: {{ $user->phone_number }}</small>
                        </div>
                        <p class="text-xs text-yellow-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            This SMS code is only shown in development mode for testing purposes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Verification Sections -->
    <div class="space-y-8">
        <!-- Email Verification Section -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fas fa-envelope text-blue-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Email Verification</h3>
                </div>
                <div class="flex items-center">
                    @if($user->email_verified)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Pending
                        </span>
                    @endif
                </div>
            </div>
            
            @if($user->email_verified)
                <p class="text-sm text-gray-600">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    Your email address <strong>{{ $user->email }}</strong> has been verified successfully.
                </p>
            @else
                <div>
                    <p class="text-sm text-gray-600 mb-4">
                        We've sent a verification link to <strong>{{ $user->email }}</strong>. 
                        Click the link in your email to verify your account.
                    </p>
                    
                    <!-- Resend Email Button -->
                    <button type="button" id="resendEmailBtn" 
                            class="inline-flex items-center px-4 py-2 border border-blue-300 text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Resend Email
                    </button>
                    
                    <div id="emailResendMessage" class="hidden mt-2"></div>
                </div>
            @endif
        </div>

        <!-- Phone Verification Section -->
        <div class="border border-gray-200 rounded-lg p-6 {{ !$user->email_verified ? 'opacity-50' : '' }}">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fas fa-mobile-alt text-green-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Phone Verification</h3>
                </div>
                <div class="flex items-center">
                    @if($user->phone_verified)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Verified
                        </span>
                    @elseif(!$user->email_verified)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            <i class="fas fa-lock mr-1"></i>
                            Waiting for Email
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Pending
                        </span>
                    @endif
                </div>
            </div>
            
            @if($user->phone_verified)
                <p class="text-sm text-gray-600">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    Your phone number <strong>{{ $user->phone_number }}</strong> has been verified successfully.
                </p>
            @elseif(!$user->email_verified)
                <div class="text-center py-4">
                    <i class="fas fa-lock text-4xl text-gray-400 mb-3"></i>
                    <p class="text-sm text-gray-500 mb-2">
                        <strong>Please verify your email first</strong>
                    </p>
                    <p class="text-xs text-gray-400">
                        SMS verification will be available after you verify your email address.
                    </p>
                </div>
            @else
                <div>
                    <p class="text-sm text-gray-600 mb-4">
                        We've sent a 6-digit verification code to <strong>{{ $user->phone_number }}</strong>. 
                        Enter the code below to verify your phone number.
                    </p>
                    
                    <!-- Phone Verification Form -->
                    <form id="phoneVerificationForm" class="space-y-4">
                        @csrf
                        <div>
                            <label for="phone_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Verification Code
                            </label>
                            <div class="space-y-3">
                                <input type="text" id="phone_code" name="phone_code" 
                                       maxlength="6" placeholder="000000"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-center text-lg font-mono tracking-widest">
                                <button type="submit" id="verifyPhoneBtn"
                                        class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-medium">
                                    <i class="fas fa-shield-alt mr-2"></i>Verify Phone Number
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Resend SMS Button -->
                    <div class="mt-4">
                        <button type="button" id="resendSmsBtn" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 text-green-700 bg-green-50 rounded-lg hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                            <i class="fas fa-sms mr-2"></i>
                            Resend SMS Code
                        </button>
                        
                        <div id="smsResendMessage" class="hidden mt-2"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Completion Status -->
    @if($user->email_verified && $user->phone_verified)
        <div class="mt-8 p-6 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-2xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold text-green-800">Verification Complete!</h3>
                    <p class="text-green-700 mt-1">Your account has been successfully verified. You will be redirected to your dashboard shortly.</p>
                </div>
            </div>
        </div>
        
        <script>
            // Auto-redirect after successful verification
            setTimeout(function() {
                window.location.href = "{{ route('citizen.dashboard') }}";
            }, 3000);
        </script>
    @else
        <!-- Information Notice -->
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex">
                <i class="fas fa-info-circle text-blue-400"></i>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Security Notice</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Both email and phone verification are required to complete your account setup. This helps ensure the security of your account and enables important notifications about your reservations.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Phone verification form
    const phoneForm = document.getElementById('phoneVerificationForm');
    const phoneCodeInput = document.getElementById('phone_code');
    const verifyPhoneBtn = document.getElementById('verifyPhoneBtn');
    
    // Resend buttons
    const resendEmailBtn = document.getElementById('resendEmailBtn');
    const resendSmsBtn = document.getElementById('resendSmsBtn');
    
    // Message divs
    const emailResendMessage = document.getElementById('emailResendMessage');
    const smsResendMessage = document.getElementById('smsResendMessage');

    // Phone code input formatting
    if (phoneCodeInput) {
        phoneCodeInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Phone verification form submission
    if (phoneForm) {
        phoneForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const code = phoneCodeInput.value;
            if (code.length !== 6) {
                showMessage(smsResendMessage, 'Please enter a 6-digit code', 'error');
                return;
            }
            
            verifyPhoneBtn.disabled = true;
            verifyPhoneBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';
            
            fetch('{{ route("citizen.auth.verify-phone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    phone_code: code
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(smsResendMessage, data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showMessage(smsResendMessage, data.message, 'error');
                    phoneCodeInput.value = '';
                }
            })
            .catch(error => {
                showMessage(smsResendMessage, 'An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                verifyPhoneBtn.disabled = false;
                verifyPhoneBtn.innerHTML = 'Verify';
            });
        });
    }

    // Resend email verification
    if (resendEmailBtn) {
        resendEmailBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            
            fetch('{{ route("citizen.auth.resend-email") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                showMessage(emailResendMessage, data.message, data.success ? 'success' : 'error');
            })
            .catch(error => {
                showMessage(emailResendMessage, 'An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Resend Email';
            });
        });
    }

    // Resend SMS verification
    if (resendSmsBtn) {
        resendSmsBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            
            fetch('{{ route("citizen.auth.resend-sms") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                showMessage(smsResendMessage, data.message, data.success ? 'success' : 'error');
            })
            .catch(error => {
                showMessage(smsResendMessage, 'An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-sms mr-2"></i>Resend SMS';
            });
        });
    }

    // Helper function to show messages
    function showMessage(element, message, type) {
        if (!element) return;
        
        element.className = 'mt-2 p-3 rounded-lg text-sm';
        element.classList.add(type === 'success' ? 'bg-green-100' : 'bg-red-100');
        element.classList.add(type === 'success' ? 'text-green-700' : 'text-red-700');
        element.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>${message}`;
        element.classList.remove('hidden');
        
        // Hide message after 5 seconds
        setTimeout(() => {
            element.classList.add('hidden');
        }, 5000);
    }
});
</script>
@endpush
@endsection
