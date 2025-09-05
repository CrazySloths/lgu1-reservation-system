@extends('citizen.layouts.app-sidebar')

@section('title', 'Setup Two-Factor Authentication - LGU1 Portal')
@section('page-title', 'Security Settings')
@section('page-description', 'Setup two-factor authentication for enhanced security')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Two-Factor Authentication</h1>
                <p class="text-gray-600 mt-1">Add an extra layer of security to your account</p>
            </div>
        </div>
    </div>

    <!-- 2FA Setup Instructions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Setup Authenticator App</h2>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Instructions -->
            <div>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm mr-3 mt-0.5">1</div>
                        <div>
                            <h3 class="font-medium text-gray-900">Download an Authenticator App</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Install a TOTP authenticator app like Google Authenticator, Authy, or Microsoft Authenticator on your mobile device.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm mr-3 mt-0.5">2</div>
                        <div>
                            <h3 class="font-medium text-gray-900">Scan QR Code</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Open your authenticator app and scan the QR code shown here, or manually enter the secret key.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm mr-3 mt-0.5">3</div>
                        <div>
                            <h3 class="font-medium text-gray-900">Enter Verification Code</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Enter the 6-digit code from your authenticator app below to verify the setup.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Popular Apps -->
                <div class="mt-6">
                    <h4 class="font-medium text-gray-900 mb-3">Popular Authenticator Apps</h4>
                    <div class="grid grid-cols-1 gap-2">
                        <div class="flex items-center p-2 border border-gray-200 rounded-lg">
                            <i class="fab fa-google text-blue-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Google Authenticator</span>
                        </div>
                        <div class="flex items-center p-2 border border-gray-200 rounded-lg">
                            <i class="fas fa-mobile-alt text-green-500 mr-3"></i>
                            <span class="text-sm text-gray-700">Authy</span>
                        </div>
                        <div class="flex items-center p-2 border border-gray-200 rounded-lg">
                            <i class="fab fa-microsoft text-blue-600 mr-3"></i>
                            <span class="text-sm text-gray-700">Microsoft Authenticator</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- QR Code and Secret -->
            <div class="text-center">
                <div class="mb-6">
                    <h3 class="font-medium text-gray-900 mb-4">Scan QR Code</h3>
                    <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg shadow-sm">
                        <div id="qrcode" data-qr="{{ $qrCodeUrl }}"></div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-2">Manual Entry</h4>
                    <p class="text-xs text-gray-600 mb-2">If you can't scan the QR code, enter this secret key manually:</p>
                    <div class="bg-gray-100 p-3 rounded-lg border">
                        <code class="text-sm font-mono break-all">{{ $secret }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Verify Setup</h3>
        
        <form id="enable2faForm" class="max-w-md">
            @csrf
            <div class="mb-4">
                <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-2">
                    Enter the 6-digit code from your authenticator app
                </label>
                <input type="text" id="verification_code" name="verification_code" 
                       maxlength="6" placeholder="000000"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-lg font-mono tracking-widest">
            </div>
            
            <div class="flex space-x-4">
                <button type="submit" id="enable2faBtn"
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Enable 2FA
                </button>
                
                <a href="{{ route('citizen.dashboard') }}" 
                   class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 text-center">
                    Skip for Now
                </a>
            </div>
        </form>
        
        <!-- Status Message -->
        <div id="statusMessage" class="hidden mt-4"></div>
    </div>

    <!-- Recovery Codes Modal -->
    <div id="recoveryCodesModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">2FA Enabled Successfully!</h3>
                    <p class="text-gray-600 mt-2">Save these recovery codes in a safe place</p>
                </div>
                
                <div class="mb-6">
                    <div class="bg-gray-100 p-4 rounded-lg border">
                        <div id="recoveryCodesList" class="grid grid-cols-2 gap-2 font-mono text-sm"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">
                        These codes can be used to access your account if you lose your authenticator device. Each code can only be used once.
                    </p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" id="downloadCodes"
                            class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Download
                    </button>
                    <button type="button" id="continueToDashboard"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <i class="fas fa-info-circle text-blue-400"></i>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Security Enhancement</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Two-factor authentication significantly improves your account security. Even if someone gets your password, they won't be able to access your account without your authenticator device.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate QR Code
    const qrCodeContainer = document.getElementById('qrcode');
    const qrCodeUrl = qrCodeContainer.dataset.qr;
    
    QRCode.toCanvas(qrCodeContainer, qrCodeUrl, {
        width: 200,
        height: 200,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    }, function (error) {
        if (error) {
            console.error('QR Code generation failed:', error);
            qrCodeContainer.innerHTML = '<p class="text-red-600 text-sm">Failed to generate QR code</p>';
        }
    });

    // Form elements
    const form = document.getElementById('enable2faForm');
    const codeInput = document.getElementById('verification_code');
    const enableBtn = document.getElementById('enable2faBtn');
    const statusMessage = document.getElementById('statusMessage');
    const modal = document.getElementById('recoveryCodesModal');
    const recoveryCodesList = document.getElementById('recoveryCodesList');
    const downloadBtn = document.getElementById('downloadCodes');
    const continueBtn = document.getElementById('continueToDashboard');

    // Code input formatting
    codeInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const code = codeInput.value;
        if (code.length !== 6) {
            showMessage('Please enter a 6-digit code', 'error');
            return;
        }
        
        enableBtn.disabled = true;
        enableBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enabling...';
        
        fetch('{{ route("citizen.security.enable-2fa") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                verification_code: code
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                showRecoveryCodes(data.recovery_codes);
            } else {
                showMessage(data.message, 'error');
                codeInput.value = '';
            }
        })
        .catch(error => {
            showMessage('An error occurred. Please try again.', 'error');
        })
        .finally(() => {
            enableBtn.disabled = false;
            enableBtn.innerHTML = '<i class="fas fa-shield-alt mr-2"></i>Enable 2FA';
        });
    });

    // Show recovery codes modal
    function showRecoveryCodes(codes) {
        recoveryCodesList.innerHTML = '';
        codes.forEach(code => {
            const codeElement = document.createElement('div');
            codeElement.className = 'text-center py-1';
            codeElement.textContent = code;
            recoveryCodesList.appendChild(codeElement);
        });
        modal.classList.remove('hidden');
    }

    // Download recovery codes
    downloadBtn.addEventListener('click', function() {
        const codes = Array.from(recoveryCodesList.children).map(el => el.textContent).join('\n');
        const blob = new Blob([
            'LGU1 Portal - Two-Factor Authentication Recovery Codes\n',
            '======================================================\n\n',
            'Keep these codes safe and secure. Each code can only be used once.\n\n',
            codes,
            '\n\nGenerated on: ' + new Date().toLocaleString()
        ], { type: 'text/plain' });
        
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'lgu1-recovery-codes.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    });

    // Continue to dashboard
    continueBtn.addEventListener('click', function() {
        window.location.href = '{{ route("citizen.dashboard") }}';
    });

    // Helper function to show messages
    function showMessage(message, type) {
        statusMessage.className = 'mt-4 p-3 rounded-lg text-sm';
        statusMessage.classList.add(type === 'success' ? 'bg-green-100' : 'bg-red-100');
        statusMessage.classList.add(type === 'success' ? 'text-green-700' : 'text-red-700');
        statusMessage.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>${message}`;
        statusMessage.classList.remove('hidden');
        
        // Hide message after 5 seconds for errors
        if (type === 'error') {
            setTimeout(() => {
                statusMessage.classList.add('hidden');
            }, 5000);
        }
    }
});
</script>
@endpush
@endsection
