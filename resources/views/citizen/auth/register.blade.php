@extends('citizen.layouts.app')

@section('title', 'Citizen Registration - LGU1 Portal')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-8 max-w-2xl mx-auto">
    <!-- Step Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <!-- Step 1 -->
            <div id="step1Indicator" class="flex items-center">
                <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                    1
                </div>
                <span class="ml-3 text-sm font-medium text-gray-900">Personal Info</span>
            </div>
            
            <!-- Progress Line 1 -->
            <div id="progressLine1" class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            
            <!-- Step 2 -->
            <div id="step2Indicator" class="flex items-center">
                <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                    2
                </div>
                <span class="ml-3 text-sm text-gray-600">Address & Location</span>
            </div>
            
            <!-- Progress Line 2 -->
            <div id="progressLine2" class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            
            <!-- Step 3 -->
            <div id="step3Indicator" class="flex items-center">
                <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                    3
                </div>
                <span class="ml-3 text-sm text-gray-600">Security</span>
            </div>
        </div>
    </div>

    <!-- Registration Header -->
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-user-plus text-2xl text-green-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Create Your Account</h2>
        <p class="text-gray-600 mt-2">Join the LGU1 Citizen Portal</p>
    </div>

    <form id="registrationForm" method="POST" action="{{ route('citizen.register.submit') }}">
        @csrf

        <!-- Step 1: Personal Information -->
        <div id="registrationStep1" class="registration-step">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-user mr-2 text-green-600"></i>Personal Information
            </h3>

            <!-- Name Fields -->
            <div class="mb-6">
                <div class="space-y-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input id="first_name" 
                               name="first_name" 
                               type="text" 
                               required 
                               value="{{ old('first_name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Middle Name <span class="text-gray-400">(Optional)</span>
                        </label>
                        <input id="middle_name" 
                               name="middle_name" 
                               type="text" 
                               value="{{ old('middle_name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('middle_name') border-red-500 @enderror">
                        @error('middle_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input id="last_name" 
                               name="last_name" 
                               type="text" 
                               required 
                               value="{{ old('last_name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Your initials will be generated from your first and last name for your profile avatar
                </p>

                <!-- Hidden field for backward compatibility -->
                <input type="hidden" id="name" name="name">
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input id="email" 
                       name="email" 
                       type="email" 
                       required 
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror"
                       placeholder="Enter your email address">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="mb-6">
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input id="phone_number" 
                       name="phone_number" 
                       type="tel" 
                       required 
                       value="{{ old('phone_number') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone_number') border-red-500 @enderror"
                       placeholder="Enter Your Phone Number">
                @error('phone_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date of Birth with Custom Calendar -->
            <div class="mb-6">
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                    Date of Birth <span class="text-red-500">*</span>
                </label>
                <button type="button" id="birthDateButton" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-left focus:ring-2 focus:ring-green-500 focus:border-transparent hover:bg-gray-50"
                        onclick="openBirthDatePicker()">
                    <i class="fas fa-calendar mr-2 text-gray-400"></i>Select your birth date
                </button>
                <input type="hidden" id="date_of_birth" name="date_of_birth" required value="{{ old('date_of_birth') }}">
                @error('date_of_birth')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-end">
                <button type="button" onclick="proceedToStep2()" 
                        class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                    Next: Address Info
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Address & Location -->
        <div id="registrationStep2" class="registration-step hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>Address & Location
            </h3>

            <!-- Region -->
            <div class="mb-6">
                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                    Region <span class="text-red-500">*</span>
                </label>
                <select id="region" name="region" required onchange="loadProvinces()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select Region</option>
                    <option value="NCR">National Capital Region (NCR)</option>
                    <option value="CAR">Cordillera Administrative Region (CAR)</option>
                    <option value="Region I">Region I - Ilocos Region</option>
                    <option value="Region II">Region II - Cagayan Valley</option>
                    <option value="Region III">Region III - Central Luzon</option>
                    <option value="Region IV-A">Region IV-A - CALABARZON</option>
                    <option value="Region IV-B">Region IV-B - MIMAROPA</option>
                    <option value="Region V">Region V - Bicol Region</option>
                    <option value="Region VI">Region VI - Western Visayas</option>
                    <option value="Region VII">Region VII - Central Visayas</option>
                    <option value="Region VIII">Region VIII - Eastern Visayas</option>
                    <option value="Region IX">Region IX - Zamboanga Peninsula</option>
                    <option value="Region X">Region X - Northern Mindanao</option>
                    <option value="Region XI">Region XI - Davao Region</option>
                    <option value="Region XII">Region XII - SOCCSKSARGEN</option>
                    <option value="Region XIII">Region XIII - Caraga</option>
                    <option value="BARMM">Bangsamoro Autonomous Region</option>
                </select>
            </div>

            <!-- City/Municipality -->
            <div class="mb-6">
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                    City/Municipality <span class="text-red-500">*</span>
                </label>
                <select id="city" name="city" required onchange="loadBarangays()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" disabled>
                    <option value="">Select City/Municipality</option>
                </select>
            </div>

            <!-- Barangay -->
            <div class="mb-6">
                <label for="barangay" class="block text-sm font-medium text-gray-700 mb-2">
                    Barangay <span class="text-red-500">*</span>
                </label>
                <select id="barangay" name="barangay" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" disabled>
                    <option value="">Select Barangay</option>
                </select>
            </div>

            <!-- Street and House Number -->
            <div class="mb-6">
                <label for="street_address" class="block text-sm font-medium text-gray-700 mb-2">
                    Street Address / House Number <span class="text-red-500">*</span>
                </label>
                <input id="street_address" 
                       name="street_address" 
                       type="text" 
                       required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <input type="hidden" id="address" name="address">
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between">
                <button type="button" onclick="backToStep1()" 
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </button>
                <button type="button" onclick="proceedToStep3()" 
                        class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                    Next: Security
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 3: Account Security -->
        <div id="registrationStep3" class="registration-step hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-lock mr-2 text-green-600"></i>Account Security
            </h3>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="password" name="password" type="password" required
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                           placeholder="Enter a strong password">
                    <button type="button" 
                            onclick="togglePassword('password')" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i id="password-toggle-icon" class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Confirm your password">
                    <button type="button" 
                            onclick="togglePassword('password_confirmation')" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i id="password_confirmation-toggle-icon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="mb-6">
                <div class="flex items-start">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-1">
                    <label for="terms" class="ml-3 block text-sm text-gray-900">
                        I agree to the <a href="#" class="text-green-600 hover:text-green-500 hover:underline">Terms and Conditions</a> and <a href="#" class="text-green-600 hover:text-green-500 hover:underline">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between">
                <button type="button" onclick="backToStep2()" 
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </button>
                <button type="submit" 
                        class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Account
                </button>
            </div>
        </div>
    </form>

    <!-- Additional Links -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('citizen.login') }}" class="font-medium text-green-600 hover:text-green-500 hover:underline">
                Sign in here
            </a>
        </p>
        
        <!-- Social Login Options -->
        <div class="mt-4">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or register with</span>
                </div>
            </div>

            <div class="mt-4 flex justify-center space-x-4">
                <!-- Facebook -->
                <button type="button" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200" onclick="registerWithFacebook()">
                    <i class="fab fa-facebook text-blue-600 mr-2"></i>
                    <span class="text-sm text-gray-700">Facebook</span>
                </button>
                
                <!-- Google -->
                <button type="button" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200" onclick="registerWithGoogle()">
                    <i class="fab fa-google text-red-500 mr-2"></i>
                    <span class="text-sm text-gray-700">Google</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Account Verification Notice -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Account Security:</strong> After registration, you'll need to verify your email and phone number for account security. This ensures your account is protected and enables important notifications.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Birth Date Picker Modal -->
<div id="birthDatePickerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Select Birth Date</h3>
                <button type="button" onclick="closeBirthDatePicker()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Year and Month selectors -->
            <div class="flex justify-between mb-4">
                <select id="birthYearSelect" onchange="renderBirthDateCalendar()" class="px-3 py-2 border border-gray-300 rounded-lg">
                    <!-- Years will be populated by JavaScript -->
                </select>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="previousBirthMonth()" class="p-2 hover:bg-gray-100 rounded">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </button>
                    <span id="birthMonthDisplay" class="text-sm font-medium w-24 text-center">January</span>
                    <button type="button" onclick="nextBirthMonth()" class="p-2 hover:bg-gray-100 rounded">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </button>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div id="birthCalendarContainer" class="grid grid-cols-7 gap-1 mb-4">
                <!-- Calendar will be rendered here -->
            </div>
            
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeBirthDatePicker()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Multi-step form navigation
let currentStep = 1;
const totalSteps = 3;

// Birth date picker variables
let currentBirthYear = new Date().getFullYear() - 18; // Default to 18 years old
let currentBirthMonth = 0; // January
const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'];

// Password visibility toggle function
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(inputId + '-toggle-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Initialize the form
document.addEventListener('DOMContentLoaded', function() {
    updateProgressSteps();
    initializeBirthYearOptions();
    setupNameFieldUpdates();
});

// Setup automatic name field updates
function setupNameFieldUpdates() {
    const firstNameInput = document.getElementById('first_name');
    const middleNameInput = document.getElementById('middle_name');
    const lastNameInput = document.getElementById('last_name');
    const nameInput = document.getElementById('name');
    
    function updateFullName() {
        const firstName = firstNameInput.value.trim();
        const middleName = middleNameInput.value.trim();
        const lastName = lastNameInput.value.trim();
        
        let fullName = firstName;
        if (middleName) {
            fullName += ' ' + middleName;
        }
        if (lastName) {
            fullName += ' ' + lastName;
        }
        
        nameInput.value = fullName;
    }
    
    // Add event listeners
    firstNameInput.addEventListener('input', updateFullName);
    middleNameInput.addEventListener('input', updateFullName);
    lastNameInput.addEventListener('input', updateFullName);
}

// Step Navigation Functions
function proceedToStep2() {
    if (validateStep1()) {
        currentStep = 2;
        showStep(2);
        updateProgressSteps();
    }
}

function backToStep1() {
    currentStep = 1;
    showStep(1);
    updateProgressSteps();
}

function proceedToStep3() {
    if (validateStep2()) {
        currentStep = 3;
        showStep(3);
        updateProgressSteps();
        updateAddressField(); // Combine address components
    }
}

function backToStep2() {
    currentStep = 2;
    showStep(2);
    updateProgressSteps();
}

function showStep(stepNumber) {
    // Hide all steps
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById('registrationStep' + i).classList.add('hidden');
    }
    
    // Show current step
    document.getElementById('registrationStep' + stepNumber).classList.remove('hidden');
}

function updateProgressSteps() {
    for (let i = 1; i <= totalSteps; i++) {
        const stepIndicator = document.getElementById('step' + i + 'Indicator');
        const stepCircle = stepIndicator.querySelector('div');
        const stepText = stepIndicator.querySelector('span');
        const progressLine = document.getElementById('progressLine' + i);
        
        if (i < currentStep) {
            // Completed step
            stepCircle.className = 'w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium';
            stepText.className = 'ml-3 text-sm font-medium text-gray-900';
            if (progressLine) progressLine.className = 'flex-1 mx-4 h-0.5 bg-green-600';
        } else if (i === currentStep) {
            // Current step
            stepCircle.className = 'w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium';
            stepText.className = 'ml-3 text-sm font-medium text-gray-900';
            if (progressLine) progressLine.className = 'flex-1 mx-4 h-0.5 bg-gray-300';
        } else {
            // Future step
            stepCircle.className = 'w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium';
            stepText.className = 'ml-3 text-sm text-gray-600';
            if (progressLine) progressLine.className = 'flex-1 mx-4 h-0.5 bg-gray-300';
        }
    }
}

// Form Validation Functions
function validateStep1() {
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone_number').value.trim();
    const birthDate = document.getElementById('date_of_birth').value;
    
    if (!firstName || !lastName || !email || !phone || !birthDate) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Fields Missing',
            text: 'Please fill in all required fields in Step 1 (First Name, Last Name, Email, Phone, Birth Date).'
        });
        return false;
    }
    
    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Email',
            text: 'Please enter a valid email address.'
        });
        return false;
    }
    
    // Validate phone number format
    const phoneRegex = /^09[0-9]{9}$/;
    if (!phoneRegex.test(phone)) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Phone Number',
            text: 'Please enter a valid Philippine mobile number (e.g., 09123456789).'
        });
        return false;
    }
    
    return true;
}

function validateStep2() {
    const region = document.getElementById('region').value;
    const city = document.getElementById('city').value;
    const barangay = document.getElementById('barangay').value;
    const streetAddress = document.getElementById('street_address').value.trim();
    
    if (!region || !city || !barangay || !streetAddress) {
        Swal.fire({
            icon: 'warning',
            title: 'Address Required',
            text: 'Please complete all address fields in Step 2.'
        });
        return false;
    }
    
    return true;
}

// Birth Date Picker Functions
function openBirthDatePicker() {
    document.getElementById('birthDatePickerModal').classList.remove('hidden');
    renderBirthDateCalendar();
}

function closeBirthDatePicker() {
    document.getElementById('birthDatePickerModal').classList.add('hidden');
}

function initializeBirthYearOptions() {
    const yearSelect = document.getElementById('birthYearSelect');
    const currentYear = new Date().getFullYear();
    const minYear = currentYear - 100; // 100 years ago
    const maxYear = currentYear - 13; // Minimum 13 years old
    
    yearSelect.innerHTML = '';
    for (let year = maxYear; year >= minYear; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        if (year === currentBirthYear) {
            option.selected = true;
        }
        yearSelect.appendChild(option);
    }
}

function previousBirthMonth() {
    currentBirthMonth--;
    if (currentBirthMonth < 0) {
        currentBirthMonth = 11;
        currentBirthYear--;
        document.getElementById('birthYearSelect').value = currentBirthYear;
    }
    renderBirthDateCalendar();
}

function nextBirthMonth() {
    currentBirthMonth++;
    if (currentBirthMonth > 11) {
        currentBirthMonth = 0;
        currentBirthYear++;
        document.getElementById('birthYearSelect').value = currentBirthYear;
    }
    renderBirthDateCalendar();
}

function renderBirthDateCalendar() {
    currentBirthYear = parseInt(document.getElementById('birthYearSelect').value);
    document.getElementById('birthMonthDisplay').textContent = monthNames[currentBirthMonth];
    
    const container = document.getElementById('birthCalendarContainer');
    container.innerHTML = '';
    
    // Day headers
    const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    dayHeaders.forEach(day => {
        const dayHeader = document.createElement('div');
        dayHeader.className = 'text-xs font-semibold text-gray-600 text-center py-2';
        dayHeader.textContent = day;
        container.appendChild(dayHeader);
    });
    
    // Get first day of month and number of days
    const firstDay = new Date(currentBirthYear, currentBirthMonth, 1);
    const lastDay = new Date(currentBirthYear, currentBirthMonth + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    // Add empty cells for days before the first day of the month
    for (let i = 0; i < startingDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'h-10';
        container.appendChild(emptyCell);
    }
    
    // Add days of the month
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 13, today.getMonth(), today.getDate()); // Minimum 13 years old
    
    for (let day = 1; day <= daysInMonth; day++) {
        const dayButton = document.createElement('button');
        dayButton.type = 'button';
        dayButton.className = 'h-10 w-10 text-sm font-medium rounded-lg hover:bg-green-100 hover:text-green-800 flex items-center justify-center transition duration-200';
        dayButton.textContent = day;
        
        const cellDate = new Date(currentBirthYear, currentBirthMonth, day);
        
        // Disable future dates (must be at least 13 years old)
        if (cellDate > maxDate) {
            dayButton.className += ' text-gray-300 cursor-not-allowed';
            dayButton.disabled = true;
        } else {
            dayButton.onclick = () => selectBirthDate(currentBirthYear, currentBirthMonth, day);
        }
        
        container.appendChild(dayButton);
    }
}

function selectBirthDate(year, month, day) {
    const selectedDate = new Date(year, month, day);
    const formattedDate = selectedDate.toISOString().split('T')[0];
    
    document.getElementById('date_of_birth').value = formattedDate;
    document.getElementById('birthDateButton').innerHTML = `<i class="fas fa-calendar mr-2 text-green-600"></i>${selectedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`;
    
    closeBirthDatePicker();
}

// Philippine Address Functions
const philippineData = {
    'NCR': {
        'Caloocan City': ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10', 'Bagumbong North', 'Bagumbong South', 'Camarin', 'North Caloocan', 'South Caloocan'],
        'Manila': ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Ermita', 'Intramuros', 'Malate', 'Binondo', 'Chinatown'],
        'Quezon City': ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Project 1', 'Project 2', 'Project 3', 'Diliman', 'Commonwealth', 'Fairview', 'Novaliches'],
        'Makati City': ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Poblacion', 'Salcedo Village', 'Legaspi Village', 'San Lorenzo Village', 'Bel-Air Village'],
        'Taguig City': ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Bonifacio Global City', 'Fort Bonifacio', 'Western Bicutan', 'Eastern Bicutan'],
        'Pasig City': ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Ortigas Center', 'Kapitolyo', 'Ugong', 'San Miguel', 'Malinao']
    }
    // Add more regions, cities, and barangays as needed
};

function loadProvinces() {
    const region = document.getElementById('region').value;
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    
    // Reset dependent dropdowns
    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
    barangaySelect.disabled = true;
    
    if (region && philippineData[region]) {
        citySelect.disabled = false;
        Object.keys(philippineData[region]).forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });
    } else {
        citySelect.disabled = true;
    }
}

function loadBarangays() {
    const region = document.getElementById('region').value;
    const city = document.getElementById('city').value;
    const barangaySelect = document.getElementById('barangay');
    
    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
    
    if (region && city && philippineData[region] && philippineData[region][city]) {
        barangaySelect.disabled = false;
        philippineData[region][city].forEach(barangay => {
            const option = document.createElement('option');
            option.value = barangay;
            option.textContent = barangay;
            barangaySelect.appendChild(option);
        });
    } else {
        barangaySelect.disabled = true;
    }
}

function updateAddressField() {
    const region = document.getElementById('region').value;
    const city = document.getElementById('city').value;
    const barangay = document.getElementById('barangay').value;
    const streetAddress = document.getElementById('street_address').value;
    
    const fullAddress = `${streetAddress}, ${barangay}, ${city}, ${region}`;
    document.getElementById('address').value = fullAddress;
}

// Social Media Login Functions (placeholders)
function registerWithFacebook() {
    Swal.fire({
        icon: 'info',
        title: 'Coming Soon!',
        text: 'Facebook registration will be available soon. Please use the regular registration form for now.',
        confirmButtonColor: '#3B82F6'
    });
}

function registerWithGoogle() {
    Swal.fire({
        icon: 'info',
        title: 'Coming Soon!',
        text: 'Google registration will be available soon. Please use the regular registration form for now.',
        confirmButtonColor: '#3B82F6'
    });
}

// Form submission
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    // Final validation before submission
    updateAddressField();
    
    // Check if all steps are valid
    if (!validateStep1() || !validateStep2()) {
        e.preventDefault();
        return false;
    }
    
    // Optional: Show loading state
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
    submitButton.disabled = true;
    
    // Re-enable button after a delay in case of errors
    setTimeout(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    }, 10000);
});
</script>
@endpush
@endsection
