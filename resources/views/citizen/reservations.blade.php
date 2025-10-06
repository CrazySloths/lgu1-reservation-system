@extends('citizen.layouts.app-sidebar')

@section('title', 'Make a Reservation - LGU1 Citizen Portal')
@section('page-title', 'New Reservation')
@section('page-description', 'Book a facility for your event')

@section('content')
<div class="space-y-6">
    <!-- Notice -->
    <div class="flex justify-end">
        <div class="flex items-center text-sm text-blue-600 bg-blue-50 px-3 py-2 rounded-lg">
            <i class="fas fa-info-circle mr-1"></i>
            Reservations require approval
        </div>
    </div>


    <!-- Progress Indicator -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <!-- Step 1 -->
            <div id="progressStep1" class="flex items-center">
                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                    1
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900">Facility & Details</span>
            </div>
            
            <!-- Progress Line 1 -->
            <div id="progressLine1" class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            
            <!-- Step 2 -->
            <div id="progressStep2" class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                    2
                </div>
                <span class="ml-2 text-sm text-gray-600">Required Documents</span>
            </div>
            
            <!-- Progress Line 2 -->
            <div id="progressLine2" class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            
            <!-- Step 3 -->
            <div id="progressStep3" class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                    3
                </div>
                <span class="ml-2 text-sm text-gray-600">Review & Submit</span>
            </div>
        </div>
    </div>

    <!-- Multi-Step Form -->
    <form id="reservationForm" method="POST" action="{{ route('citizen.reservations.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Step 1: Facility & Details -->
        <div id="step1" class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Step 1: Select Facility & Event Details</h2>
            
            <!-- Available Facilities -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Facilities</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($facilities as $facility)
                    <div class="facility-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-blue-400 hover:shadow-md" 
                         data-facility-id="{{ $facility->facility_id }}" 
                         data-facility-name="{{ $facility->name }}"
                         data-base-rate="{{ $facility->daily_rate }}"
                         data-hourly-rate="{{ $facility->hourly_rate }}">
                        
                        <!-- Facility Image -->
                        @if($facility->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($facility->image_path))
                            <img src="{{ asset('storage/' . $facility->image_path) }}" 
                                 alt="{{ $facility->name }}" 
                                 class="w-full h-40 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-40 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                <i class="fas fa-building text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        
                        <!-- Facility Details -->
                        <div class="space-y-2">
                            <h4 class="font-bold text-lg text-gray-800">{{ $facility->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $facility->description }}</p>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $facility->location }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users mr-1"></i>
                                Capacity: {{ $facility->capacity }} people
                            </div>
                            <div class="text-sm font-medium text-blue-600">
                                Type: {{ ucfirst($facility->facility_type) }}
                            </div>
                            <div class="text-lg font-bold text-green-600">
                                ₱{{ number_format($facility->daily_rate, 2) }} base (3hrs) +₱{{ number_format($facility->hourly_rate, 2) }}/hr extension
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-building text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">No facilities available at the moment</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Selected Facility Display -->
                <div id="selectedFacilityDisplay" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                    <h4 class="font-semibold text-blue-800 mb-2">Selected Facility:</h4>
                    <p id="selectedFacilityName" class="text-blue-700"></p>
                </div>
                
                <!-- Hidden input for selected facility -->
                <input type="hidden" id="selected_facility" name="facility_name" required>
            </div>

            <!-- Event Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Applicant Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Applicant Information</h3>
                    
                    <div class="mb-4">
                        <label for="applicant_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="applicant_name" name="applicant_name" value="{{ $user->name }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    
                    <div class="mb-4">
                        <label for="applicant_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="applicant_email" name="applicant_email" value="{{ $user->email }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    
                    <div class="mb-4">
                        <label for="applicant_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" id="applicant_phone" name="applicant_phone" value="{{ $user->phone_number ?? '' }}" required
                               placeholder="Enter your phone number (e.g., +63 912 345 6789)"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('applicant_phone') border-red-500 @enderror">
                        @error('applicant_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="applicant_address" class="block text-sm font-medium text-gray-700 mb-2">Address <span class="text-red-500">*</span></label>
                        <textarea id="applicant_address" name="applicant_address" rows="3" required
                                  placeholder="Enter your complete address (Street, Barangay, City, Province)"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('applicant_address') border-red-500 @enderror">{{ $user->address ?? '' }}</textarea>
                        @error('applicant_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Event Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Information</h3>
                    
                    <div class="mb-4">
                        <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">Event Name <span class="text-red-500">*</span></label>
                        <input type="text" id="event_name" name="event_name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Birthday Party, Meeting, Conference">
                    </div>
                    
                    <div class="mb-4">
                        <label for="event_description" class="block text-sm font-medium text-gray-700 mb-2">Event Description (Optional)</label>
                        <textarea id="event_description" name="event_description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Brief description of your event (optional)"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="expected_attendees" class="block text-sm font-medium text-gray-700 mb-2">Expected Number of Attendees <span class="text-red-500">*</span></label>
                        <input type="number" id="expected_attendees" name="expected_attendees" required min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Number of expected attendees">
                    </div>
                </div>
            </div>

            <!-- Date and Time Selection -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Date & Time Selection</h3>
                
                <!-- Enhanced Date & Time Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Date Picker -->
                    <div class="space-y-2">
                        <label for="event_date" class="block text-sm font-semibold text-gray-700">
                            Event Date <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <button type="button" id="datePickerButton" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl text-left focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 bg-white shadow-sm transition-all duration-200 hover:shadow-md group"
                                    onclick="openDatePicker()">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-calendar-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Select Date</div>
                                        <div id="selectedDateDisplay" class="text-gray-900 font-medium">Choose event date</div>
                                    </div>
                                </div>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-chevron-down text-gray-400 group-hover:text-blue-500"></i>
                                </div>
                            </button>
                            <input type="hidden" id="event_date" name="event_date" required>
                        </div>
                    </div>
                    
                    <!-- Start Time Picker -->
                    <div class="space-y-2">
                        <label for="start_time" class="block text-sm font-semibold text-gray-700">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <button type="button" id="startTimeButton" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl text-left focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-green-400 bg-white shadow-sm transition-all duration-200 hover:shadow-md group"
                                    onclick="openTimePicker('start')">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                                        <i class="fas fa-clock text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Start Time</div>
                                        <div id="selectedStartTimeDisplay" class="text-gray-900 font-medium">Select start</div>
                                    </div>
                                </div>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-chevron-down text-gray-400 group-hover:text-green-500"></i>
                                </div>
                            </button>
                            <input type="hidden" id="start_time" name="start_time" required>
                        </div>
                    </div>
                    
                    <!-- End Time Picker -->
                    <div class="space-y-2">
                        <label for="end_time" class="block text-sm font-semibold text-gray-700">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <button type="button" id="endTimeButton" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl text-left focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-orange-400 bg-white shadow-sm transition-all duration-200 hover:shadow-md group"
                                    onclick="openTimePicker('end')">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200 transition-colors">
                                        <i class="fas fa-clock text-orange-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">End Time</div>
                                        <div id="selectedEndTimeDisplay" class="text-gray-900 font-medium">Select end</div>
                                    </div>
                                </div>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-chevron-down text-gray-400 group-hover:text-orange-500"></i>
                                </div>
                            </button>
                            <input type="hidden" id="end_time" name="end_time" required>
                        </div>
                    </div>
                </div>

                <!-- Duration Display & Warnings -->
                <div class="mt-4 space-y-3">
                    <!-- Duration Info -->
                    <div id="durationDisplay" class="p-4 bg-gray-50 border border-gray-200 rounded-xl hidden">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-hourglass-half text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Event Duration</div>
                                    <div id="durationText" class="text-sm text-gray-600">--</div>
                                </div>
                            </div>
                            <div id="durationBadge" class="px-3 py-1 rounded-full text-xs font-medium">
                                <!-- Duration status badge -->
                            </div>
                        </div>
                    </div>

                    <!-- Duration Warning -->
                    <div id="durationWarning" class="p-4 bg-yellow-50 border border-yellow-300 rounded-xl hidden">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-yellow-800">Minimum Duration Required</div>
                                <div class="text-sm text-yellow-700">Events must be at least 3 hours long. Please adjust your end time.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Duration Success -->
                    <div id="durationSuccess" class="p-4 bg-green-50 border border-green-300 rounded-xl hidden">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-green-800">Duration Confirmed</div>
                                <div class="text-sm text-green-700" id="durationSuccessText">Your event duration is valid.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fee Summary -->
            <div id="feesSummary" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Fee Summary</h3>
                <div id="feesBreakdown" class="text-sm text-green-700"></div>
            </div>

            <!-- Step 1 Navigation -->
            <div class="flex justify-end">
                <button type="button" onclick="proceedToStep2()" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Continue to Upload Documents
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Required Documents -->
        <div id="step2" class="bg-white shadow rounded-lg p-6 hidden">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Step 2: Upload Required Documents</h2>
            
            <!-- ID Verification Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Valid ID Verification</h3>
                
                <!-- ID Type Selection -->
                <div class="mb-6">
                    <label for="id_type" class="block text-sm font-medium text-gray-700 mb-2">
                        ID Type <span class="text-red-500">*</span>
                    </label>
                    <select id="id_type" name="id_type" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select ID Type</option>
                        <option value="Government-Issued ID">Government-Issued ID</option>
                        <option value="Driver's License">Driver's License</option>
                        <option value="Passport">Passport</option>
                        <option value="Voter's ID">Voter's ID</option>
                        <option value="School ID">School ID</option>
                        <option value="Senior Citizen ID">Senior Citizen ID</option>
                        <option value="PWD ID">PWD ID</option>
                    </select>
                </div>

                <!-- ID Upload Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- ID Front -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            ID Front Side <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors" id="id_front_container">
                            <input type="file" id="id_front" name="id_front" accept="image/*" required
                                   class="hidden" onchange="handleFilePreview(this, 'id_front_preview')">
                            <div id="id_front_preview" class="space-y-2">
                                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto">
                                    <i class="fas fa-id-card text-blue-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-600">Upload ID Front</p>
                                <button type="button" onclick="document.getElementById('id_front').click()" 
                                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                    Choose File
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ID Back -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            ID Back Side <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-green-400 transition-colors" id="id_back_container">
                            <input type="file" id="id_back" name="id_back" accept="image/*" required
                                   class="hidden" onchange="handleFilePreview(this, 'id_back_preview')">
                            <div id="id_back_preview" class="space-y-2">
                                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto">
                                    <i class="fas fa-id-card text-green-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-600">Upload ID Back</p>
                                <button type="button" onclick="document.getElementById('id_back').click()" 
                                        class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                    Choose File
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Selfie with ID -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Selfie with ID <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-purple-400 transition-colors" id="selfie_container">
                            <input type="file" id="selfie_with_id" name="selfie_with_id" accept="image/*" required
                                   class="hidden" onchange="handleFilePreview(this, 'selfie_preview')">
                            <div id="selfie_preview" class="space-y-2">
                                <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mx-auto">
                                    <i class="fas fa-camera text-purple-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-600">Upload Selfie</p>
                                <button type="button" onclick="document.getElementById('selfie_with_id').click()" 
                                        class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700">
                                    Choose File
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Digital Signature Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Digital Signature</h3>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-sm font-medium text-gray-700">Choose signature method:</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="signature_method" value="draw" checked 
                                       onchange="toggleSignatureMethod('draw')" class="mr-2">
                                <span class="text-sm">Draw Signature</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="signature_method" value="upload" 
                                       onchange="toggleSignatureMethod('upload')" class="mr-2">
                                <span class="text-sm">Upload Image</span>
                            </label>
                        </div>
                    </div>

                    <!-- Digital Signature Canvas -->
                    <div id="signature_draw_section">
                        <div class="border-2 border-gray-300 rounded-lg bg-white">
                            <canvas id="signature_canvas" width="500" height="200" 
                                    class="w-full h-48 cursor-crosshair rounded-lg"></canvas>
                        </div>
                        <div class="flex justify-between mt-3">
                            <button type="button" onclick="clearSignature()" 
                                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                Clear Signature
                            </button>
                            <p class="text-sm text-gray-500 self-center">Sign above using your mouse or touch</p>
                        </div>
                    </div>

                    <!-- Upload Signature Section -->
                    <div id="signature_upload_section" class="hidden">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-400 transition-colors">
                            <input type="file" id="signature_upload" name="signature_upload" accept="image/*"
                                   class="hidden" onchange="handleFilePreview(this, 'signature_upload_preview')">
                            <div id="signature_upload_preview" class="space-y-2">
                                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto">
                                    <i class="fas fa-signature text-indigo-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-600">Upload Signature Image</p>
                                <button type="button" onclick="document.getElementById('signature_upload').click()" 
                                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                                    Choose File
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optional Documents Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Optional Documents</h3>
                <p class="text-sm text-gray-600 mb-4">Required for organizations/groups booking on behalf of others</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Authorization Letter -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Authorization Letter (Optional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-orange-400 transition-colors">
                            <input type="file" id="auth_letter" name="auth_letter" accept=".pdf,.jpg,.jpeg,.png"
                                   class="hidden" onchange="handleFilePreview(this, 'auth_letter_preview')">
                            <div id="auth_letter_preview" class="space-y-2">
                                <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center mx-auto">
                                    <i class="fas fa-file-alt text-orange-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-600">Upload Authorization</p>
                                <button type="button" onclick="document.getElementById('auth_letter').click()" 
                                        class="px-4 py-2 bg-orange-600 text-white text-sm rounded-lg hover:bg-orange-700">
                                    Choose File
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Event Proposal -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Event Proposal/Details (Optional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-teal-400 transition-colors">
                            <input type="file" id="event_proposal" name="event_proposal" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="hidden" onchange="handleFilePreview(this, 'event_proposal_preview')">
                            <div id="event_proposal_preview" class="space-y-2">
                                <div class="w-16 h-16 bg-teal-100 rounded-lg flex items-center justify-center mx-auto">
                                    <i class="fas fa-file-contract text-teal-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-600">Upload Proposal</p>
                                <button type="button" onclick="document.getElementById('event_proposal').click()" 
                                        class="px-4 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-700">
                                    Choose File
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 Navigation -->
            <div class="flex justify-between">
                <button type="button" onclick="goBackToStep1()" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Details
                </button>
                <button type="button" onclick="proceedToStep3()" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Continue to Review
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
            </div>
        </div>

        <!-- Step 3: Review & Submit -->
        <div id="step3" class="bg-white shadow rounded-lg p-6 hidden">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Step 3: Review & Submit</h2>
            
            <!-- Review Content -->
            <div id="reviewContent" class="space-y-6 mb-8">
                <!-- Review details will be populated by JavaScript -->
            </div>

            <!-- Terms Agreement -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <label class="flex items-start">
                    <input type="checkbox" id="terms_agreement" name="terms_agreement" required class="mt-1 mr-3">
                    <div class="text-sm">
                        <p class="font-medium text-blue-900">Terms and Conditions</p>
                        <p class="text-blue-700 mt-1">I confirm that all information provided is accurate and I agree to the facility usage terms and conditions. I understand that false information may result in application rejection.</p>
                    </div>
                </label>
            </div>

            <!-- Step 3 Navigation -->
            <div class="flex justify-between">
                <button type="button" onclick="goBackToStep2()" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Documents
                </button>
                <button type="submit" id="submitReservation" 
                        class="px-8 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Reservation
                </button>
            </div>
        </div>
        
    </form>
</div>

<!-- Date Picker Modal -->
<div id="datePickerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Select Date</h3>
                <button type="button" onclick="closeDatePicker()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Calendar will be rendered here by JavaScript -->
            <div id="calendarContainer"></div>
            
            <div class="mt-4 flex justify-between">
                <button type="button" onclick="goToToday()" class="text-blue-600 hover:text-blue-800 font-medium">Today</button>
                <button type="button" onclick="closeDatePicker()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Time Picker Modal -->
<div id="timePickerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 transform transition-all duration-300">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Select Time</h3>
                    <p class="text-sm text-gray-500" id="timePickerSubtitle">Choose your preferred time</p>
                </div>
                <button type="button" onclick="closeTimePicker()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>
            
            <!-- Time Picker Content -->
            <div class="space-y-6">
                <!-- Hour and Minute Selectors -->
                <div class="flex items-center justify-center space-x-6">
                    <!-- Hour Selector -->
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hour</label>
                        <div class="relative">
                            <select id="hourSelect" class="w-16 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <!-- Hours will be populated by JavaScript -->
                            </select>
                        </div>
                    </div>
                    
                    <!-- Separator -->
                    <div class="text-3xl font-bold text-gray-400 mt-6">:</div>
                    
                    <!-- Minute Selector -->
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minute</label>
                        <div class="relative">
                            <select id="minuteSelect" class="w-16 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="00">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- AM/PM Selector -->
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                        <div class="relative">
                            <select id="periodSelect" class="w-16 h-12 text-center text-lg font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Quick Time Buttons -->
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm font-medium text-gray-700 mb-3">Quick Select:</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="setQuickTime('08:00', 'AM')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors">8:00 AM</button>
                        <button type="button" onclick="setQuickTime('12:00', 'PM')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors">12:00 PM</button>
                        <button type="button" onclick="setQuickTime('02:00', 'PM')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors">2:00 PM</button>
                        <button type="button" onclick="setQuickTime('05:00', 'PM')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors">5:00 PM</button>
                        <button type="button" onclick="setQuickTime('07:00', 'PM')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors">7:00 PM</button>
                        <button type="button" onclick="setQuickTime('09:00', 'PM')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors">9:00 PM</button>
                    </div>
                </div>

                <!-- Selected Time Display -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="text-center">
                        <p class="text-sm text-blue-700">Selected Time</p>
                        <p id="selectedTimePreview" class="text-lg font-bold text-blue-900">--:-- --</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-3 mt-6">
                <button type="button" onclick="closeTimePicker()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="confirmTimeSelection()" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Enhanced Reservation System with 3-hour minimum duration
let selectedFacility = null;
let currentTimeType = null; // 'start' or 'end'
let selectedDate = null;
let selectedStartTime = null;
let selectedEndTime = null;
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    populateHourOptions();
    updateTimePreview();
    
    // Add event listeners for time selectors
    document.getElementById('hourSelect').addEventListener('change', updateTimePreview);
    document.getElementById('minuteSelect').addEventListener('change', updateTimePreview);
    document.getElementById('periodSelect').addEventListener('change', updateTimePreview);
    
    // Initialize signature canvas
    initializeSignatureCanvas();
});

// Facility Selection
document.querySelectorAll('.facility-card').forEach(card => {
    card.addEventListener('click', function() {
        // Remove previous selection
        document.querySelectorAll('.facility-card').forEach(c => {
            c.classList.remove('border-blue-500', 'bg-blue-50');
            c.classList.add('border-gray-200');
        });
        
        // Add selection to clicked card
        this.classList.remove('border-gray-200');
        this.classList.add('border-blue-500', 'bg-blue-50');
        
        // Update selected facility
        selectedFacility = {
            id: this.dataset.facilityId,
            name: this.dataset.facilityName,
            baseRate: parseFloat(this.dataset.baseRate),
            hourlyRate: parseFloat(this.dataset.hourlyRate)
        };
        
        // Update hidden input and display
        document.getElementById('selected_facility').value = selectedFacility.name;
        document.getElementById('selectedFacilityName').textContent = selectedFacility.name;
        document.getElementById('selectedFacilityDisplay').classList.remove('hidden');
        
        calculateFees();
    });
});

// === DATE PICKER FUNCTIONS ===
function openDatePicker() {
    renderCalendar();
    document.getElementById('datePickerModal').classList.remove('hidden');
}

function renderCalendar() {
let calendarHTML = `
        <div class="text-center mb-4">
            <div class="flex justify-between items-center">
                <button type="button" onclick="changeMonth(-1)" class="p-2 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-chevron-left text-gray-600"></i>
                </button>
                <h4 class="text-lg font-semibold">${getMonthName(currentMonth)} ${currentYear}</h4>
                <button type="button" onclick="changeMonth(1)" class="p-2 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-chevron-right text-gray-600"></i>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-7 gap-1 mb-2">
            <div class="text-center text-sm font-medium text-gray-500 p-2">Su</div>
            <div class="text-center text-sm font-medium text-gray-500 p-2">Mo</div>
            <div class="text-center text-sm font-medium text-gray-500 p-2">Tu</div>
            <div class="text-center text-sm font-medium text-gray-500 p-2">We</div>
            <div class="text-center text-sm font-medium text-gray-500 p-2">Th</div>
            <div class="text-center text-sm font-medium text-gray-500 p-2">Fr</div>
            <div class="text-center text-sm font-medium text-gray-500 p-2">Sa</div>
        </div>
        <div class="grid grid-cols-7 gap-1" id="calendarDays">
            ${generateCalendarDays(currentMonth, currentYear)}
        </div>
    `;
    
    document.getElementById('calendarContainer').innerHTML = calendarHTML;
}

function changeMonth(direction) {
    currentMonth += direction;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    } else if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
}

function generateCalendarDays(month, year) {
    const today = new Date();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startingDayOfWeek = firstDay.getDay();
    const daysInMonth = lastDay.getDate();
    
    let daysHTML = '';
    
    // Empty cells for days before the first day of the month
    for (let i = 0; i < startingDayOfWeek; i++) {
        daysHTML += '<div class="p-2"></div>';
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        const isToday = date.toDateString() === today.toDateString();
        const isPast = date < today && !isToday;
        
        let dayClass = 'p-2 text-center cursor-pointer rounded-lg hover:bg-blue-100';
        
        if (isPast) {
            dayClass = 'p-2 text-center text-gray-400 cursor-not-allowed';
        } else if (isToday) {
            dayClass += ' bg-blue-500 text-white font-bold';
        }
        
        daysHTML += `<div class="${dayClass}" onclick="${isPast ? '' : `selectDate(${year}, ${month}, ${day})`}">${day}</div>`;
    }
    
    return daysHTML;
}

function selectDate(year, month, day) {
    selectedDate = new Date(year, month, day);
    const formattedDate = selectedDate.toISOString().split('T')[0];
    const displayDate = selectedDate.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    document.getElementById('event_date').value = formattedDate;
    document.getElementById('selectedDateDisplay').textContent = displayDate;
    
    closeDatePicker();
    calculateFees();
}

function closeDatePicker() {
    document.getElementById('datePickerModal').classList.add('hidden');
}

function getMonthName(monthIndex) {
    const months = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    return months[monthIndex];
}

// === TIME PICKER FUNCTIONS ===
function openTimePicker(type) {
    currentTimeType = type;
    
    const subtitle = type === 'start' ? 'Choose your event start time' : 'Choose your event end time';
    document.getElementById('timePickerSubtitle').textContent = subtitle;
    
    // Set default time
    if (type === 'start') {
        document.getElementById('hourSelect').value = '09';
        document.getElementById('minuteSelect').value = '00';
        document.getElementById('periodSelect').value = 'AM';
    } else {
        // Auto-suggest 3 hours after start time if start time is selected
        if (selectedStartTime) {
            const startTime = parseTime(selectedStartTime);
            const endTime = addHours(startTime, 3);
            const formatted = formatTime(endTime);
            
            document.getElementById('hourSelect').value = formatted.hour;
            document.getElementById('minuteSelect').value = formatted.minute;
            document.getElementById('periodSelect').value = formatted.period;
        } else {
            document.getElementById('hourSelect').value = '12';
            document.getElementById('minuteSelect').value = '00';
            document.getElementById('periodSelect').value = 'PM';
        }
    }
    
    updateTimePreview();
    document.getElementById('timePickerModal').classList.remove('hidden');
}

function populateHourOptions() {
    const hourSelect = document.getElementById('hourSelect');
    hourSelect.innerHTML = '';
    
    for (let i = 1; i <= 12; i++) {
        const hour = i.toString().padStart(2, '0');
        hourSelect.innerHTML += `<option value="${hour}">${hour}</option>`;
    }
}

function setQuickTime(time, period) {
    const [hour, minute] = time.split(':');
    document.getElementById('hourSelect').value = hour;
    document.getElementById('minuteSelect').value = minute;
    document.getElementById('periodSelect').value = period;
    updateTimePreview();
}

function updateTimePreview() {
    const hour = document.getElementById('hourSelect').value || '09';
    const minute = document.getElementById('minuteSelect').value || '00';
    const period = document.getElementById('periodSelect').value || 'AM';
    
    const timeString = `${hour}:${minute} ${period}`;
    document.getElementById('selectedTimePreview').textContent = timeString;
}

function confirmTimeSelection() {
    const hour = document.getElementById('hourSelect').value;
    const minute = document.getElementById('minuteSelect').value;
    const period = document.getElementById('periodSelect').value;
    const timeString = `${hour}:${minute} ${period}`;
    
    if (currentTimeType === 'start') {
        selectedStartTime = timeString;
        document.getElementById('start_time').value = timeString;
        document.getElementById('selectedStartTimeDisplay').textContent = timeString;
        
        // Auto-adjust end time to 3 hours later
        const startTime = parseTime(timeString);
        const endTime = addHours(startTime, 3);
        const endTimeString = formatTimeString(endTime);
        
        selectedEndTime = endTimeString;
        document.getElementById('end_time').value = endTimeString;
        document.getElementById('selectedEndTimeDisplay').textContent = endTimeString;
        
    } else {
        selectedEndTime = timeString;
        document.getElementById('end_time').value = timeString;
        document.getElementById('selectedEndTimeDisplay').textContent = timeString;
    }
    
    closeTimePicker();
    validateDuration();
    calculateFees();
}

function closeTimePicker() {
    document.getElementById('timePickerModal').classList.add('hidden');
}

// === DURATION VALIDATION ===
function validateDuration() {
    if (!selectedStartTime || !selectedEndTime) {
        hideDurationElements();
        return;
    }
    
    const startTime = parseTime(selectedStartTime);
    const endTime = parseTime(selectedEndTime);
    
    // Calculate duration in minutes
    let durationMinutes = (endTime.getTime() - startTime.getTime()) / (1000 * 60);
    
    // Handle next day scenarios
    if (durationMinutes < 0) {
        durationMinutes += 24 * 60; // Add 24 hours
    }
    
    const durationHours = durationMinutes / 60;
    
    // Show duration display
    document.getElementById('durationDisplay').classList.remove('hidden');
    
    if (durationHours < 3) {
        // Less than 3 hours - show warning
        document.getElementById('durationText').textContent = `${Math.floor(durationHours)} hours ${durationMinutes % 60} minutes`;
        document.getElementById('durationBadge').textContent = 'Too Short';
        document.getElementById('durationBadge').className = 'px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
        
        document.getElementById('durationWarning').classList.remove('hidden');
        document.getElementById('durationSuccess').classList.add('hidden');
        
    } else {
        // 3 hours or more - show success
        document.getElementById('durationText').textContent = `${Math.floor(durationHours)} hours ${Math.floor(durationMinutes % 60)} minutes`;
        document.getElementById('durationBadge').textContent = 'Valid';
        document.getElementById('durationBadge').className = 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
        
        document.getElementById('durationWarning').classList.add('hidden');
        document.getElementById('durationSuccess').classList.remove('hidden');
        document.getElementById('durationSuccessText').textContent = `Your event duration of ${Math.floor(durationHours)} hours meets the minimum requirement.`;
    }
    
    return durationHours >= 3;
}

function hideDurationElements() {
    document.getElementById('durationDisplay').classList.add('hidden');
    document.getElementById('durationWarning').classList.add('hidden');
    document.getElementById('durationSuccess').classList.add('hidden');
}

// === TIME UTILITY FUNCTIONS ===
function parseTime(timeString) {
    const [time, period] = timeString.split(' ');
    const [hours, minutes] = time.split(':');
    let hour24 = parseInt(hours);
    
    if (period === 'AM' && hour24 === 12) hour24 = 0;
    if (period === 'PM' && hour24 !== 12) hour24 += 12;
    
    const date = new Date();
    date.setHours(hour24, parseInt(minutes), 0, 0);
    return date;
}

function addHours(date, hours) {
    const result = new Date(date);
    result.setHours(result.getHours() + hours);
    return result;
}

function formatTime(date) {
    let hours = date.getHours();
    const minutes = date.getMinutes();
    const period = hours >= 12 ? 'PM' : 'AM';
    
    if (hours > 12) hours -= 12;
    if (hours === 0) hours = 12;
    
    return {
        hour: hours.toString().padStart(2, '0'),
        minute: minutes.toString().padStart(2, '0'),
        period: period
    };
}

function formatTimeString(date) {
    const formatted = formatTime(date);
    return `${formatted.hour}:${formatted.minute} ${formatted.period}`;
}

// === FEE CALCULATION ===
function calculateFees() {
    if (!selectedFacility || !selectedStartTime || !selectedEndTime) {
        document.getElementById('feesSummary').classList.add('hidden');
        return;
    }
    
    const startTime = parseTime(selectedStartTime);
    const endTime = parseTime(selectedEndTime);
    
    let durationMinutes = (endTime.getTime() - startTime.getTime()) / (1000 * 60);
    if (durationMinutes < 0) durationMinutes += 24 * 60;
    
    const durationHours = Math.ceil(durationMinutes / 60);
    
    let totalFee = selectedFacility.baseRate; // Base 3 hours
    
    if (durationHours > 3) {
        const extraHours = durationHours - 3;
        totalFee += extraHours * selectedFacility.hourlyRate;
    }
    
    document.getElementById('feesSummary').classList.remove('hidden');
    document.getElementById('feesBreakdown').innerHTML = `
        <div class="space-y-1">
            <div class="flex justify-between">
                <span>Base Rate (3 hours):</span>
                <span>₱${selectedFacility.baseRate.toLocaleString()}</span>
            </div>
            ${durationHours > 3 ? `
                <div class="flex justify-between">
                    <span>Extension (${durationHours - 3} hours × ₱${selectedFacility.hourlyRate}):</span>
                    <span>₱${((durationHours - 3) * selectedFacility.hourlyRate).toLocaleString()}</span>
                </div>
            ` : ''}
            <div class="border-t border-green-300 mt-2 pt-2">
                <div class="flex justify-between font-semibold text-lg">
                    <span>Total Fee:</span>
                    <span>₱${totalFee.toLocaleString()}</span>
                </div>
            </div>
        </div>
    `;
}

// === FORM VALIDATION ===
function validateStep1() {
    if (!selectedFacility) {
        Swal.fire({
            icon: 'warning',
            title: 'Facility Required',
            text: 'Please select a facility for your event.'
        });
        return false;
    }
    
    const eventName = document.getElementById('event_name').value.trim();
    const attendees = document.getElementById('expected_attendees').value;
    const eventDate = document.getElementById('event_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (!eventName || !attendees || !eventDate || !startTime || !endTime) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Fields Missing',
            text: 'Please fill in all required fields.'
        });
        return false;
    }
    
    if (!validateDuration()) {
        Swal.fire({
            icon: 'warning',
            title: 'Duration Too Short',
            text: 'Event duration must be at least 3 hours. Please adjust your end time.'
        });
        return false;
    }
    
    return true;
}

// === STEP NAVIGATION ===
function proceedToStep2() {
    if (!validateStep1()) {
        return;
    }
    
    // Hide step 1, show step 2
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
    
    // Update progress indicators
    updateProgressStep(2);
    
    Swal.fire({
        icon: 'success',
        title: 'Step 1 Complete!',
        text: 'Please upload your required documents.',
        timer: 2000,
        showConfirmButton: false
    });
}

function goBackToStep1() {
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step1').classList.remove('hidden');
    updateProgressStep(1);
}

function proceedToStep3() {
    if (!validateStep2()) {
        return;
    }
    
    // Hide step 2, show step 3
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step3').classList.remove('hidden');
    
    // Update progress indicators
    updateProgressStep(3);
    
    // Populate review content
    populateReviewContent();
    
    Swal.fire({
        icon: 'success',
        title: 'Documents Uploaded!',
        text: 'Please review your information before submitting.',
        timer: 2000,
        showConfirmButton: false
    });
}

function goBackToStep2() {
    document.getElementById('step3').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
    updateProgressStep(2);
}

function updateProgressStep(step) {
    // Reset all steps
    for (let i = 1; i <= 3; i++) {
        const stepEl = document.getElementById(`progressStep${i}`);
        const lineEl = document.getElementById(`progressLine${i}`);
        
        if (i <= step) {
            // Active/completed steps
            stepEl.querySelector('.w-8').className = 'w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium';
            stepEl.querySelector('span').className = 'ml-2 text-sm font-medium text-gray-900';
            if (lineEl && i < step) {
                lineEl.className = 'flex-1 mx-4 h-0.5 bg-blue-600';
            }
        } else {
            // Inactive steps
            stepEl.querySelector('.w-8').className = 'w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium';
            stepEl.querySelector('span').className = 'ml-2 text-sm text-gray-600';
            if (lineEl) {
                lineEl.className = 'flex-1 mx-4 h-0.5 bg-gray-300';
            }
        }
    }
}

function validateStep2() {
    const idType = document.getElementById('id_type').value;
    const idFront = document.getElementById('id_front').files.length;
    const idBack = document.getElementById('id_back').files.length;
    const selfie = document.getElementById('selfie_with_id').files.length;
    
    if (!idType) {
        Swal.fire({
            icon: 'warning',
            title: 'ID Type Required',
            text: 'Please select your ID type.'
        });
        return false;
    }
    
    if (!idFront || !idBack || !selfie) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Documents Missing',
            text: 'Please upload your ID front, ID back, and selfie with ID.'
        });
        return false;
    }
    
    // Check if signature is provided
    const signatureMethod = document.querySelector('input[name="signature_method"]:checked').value;
    if (signatureMethod === 'draw') {
        if (isSignatureCanvasEmpty()) {
            Swal.fire({
                icon: 'warning',
                title: 'Signature Required',
                text: 'Please provide your signature.'
            });
            return false;
        }
    } else if (signatureMethod === 'upload') {
        const signatureUpload = document.getElementById('signature_upload').files.length;
        if (!signatureUpload) {
            Swal.fire({
                icon: 'warning',
                title: 'Signature Required',
                text: 'Please upload your signature image.'
            });
            return false;
        }
    }
    
    return true;
}

function populateReviewContent() {
    const facility = selectedFacility ? selectedFacility.name : 'Not selected';
    const eventName = document.getElementById('event_name').value;
    const eventDate = document.getElementById('event_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const attendees = document.getElementById('expected_attendees').value;
    const idType = document.getElementById('id_type').value;
    
    const reviewHTML = `
        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">Event Details</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="font-medium">Event:</span> ${eventName}</div>
                    <div><span class="font-medium">Facility:</span> ${facility}</div>
                    <div><span class="font-medium">Date:</span> ${eventDate}</div>
                    <div><span class="font-medium">Time:</span> ${startTime} - ${endTime}</div>
                    <div><span class="font-medium">Attendees:</span> ${attendees}</div>
                    <div><span class="font-medium">ID Type:</span> ${idType}</div>
                </div>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-semibold text-green-900 mb-2">Fee Summary</h4>
                <div id="finalFeeBreakdown" class="text-sm">
                    ${document.getElementById('feesBreakdown').innerHTML}
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('reviewContent').innerHTML = reviewHTML;
}

// === FILE PREVIEW FUNCTIONS ===
function handleFilePreview(input, previewId) {
    const file = input.files[0];
    const previewContainer = document.getElementById(previewId);
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create preview with image and details
            const fileName = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            
            previewContainer.innerHTML = `
                <div class="space-y-2">
                    <div class="w-20 h-20 mx-auto rounded-lg overflow-hidden border-2 border-green-200">
                        <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                    </div>
                    <p class="text-sm font-medium text-green-700">${fileName}</p>
                    <p class="text-xs text-gray-500">${fileSize}</p>
                    <div class="flex space-x-2 justify-center">
                        <button type="button" onclick="document.getElementById('${input.id}').click()" 
                                class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                            Change
                        </button>
                        <button type="button" onclick="removeFile('${input.id}', '${previewId}')" 
                                class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                            Remove
                        </button>
                    </div>
                </div>
            `;
            
            // Update container styling to show success
            const container = previewContainer.closest('.border-2');
            container.classList.remove('border-gray-300', 'border-dashed');
            container.classList.add('border-green-400', 'border-solid', 'bg-green-50');
        };
        reader.readAsDataURL(file);
    }
}

function removeFile(inputId, previewId) {
    const input = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewId);
    const container = previewContainer.closest('.border-2');
    
    // Reset file input
    input.value = '';
    
    // Reset container styling
    container.classList.remove('border-green-400', 'border-solid', 'bg-green-50');
    container.classList.add('border-gray-300', 'border-dashed');
    
    // Reset preview content based on input type
    const inputType = inputId.includes('id_front') ? 'front' : 
                     inputId.includes('id_back') ? 'back' :
                     inputId.includes('selfie') ? 'selfie' :
                     inputId.includes('signature') ? 'signature' :
                     inputId.includes('auth') ? 'authorization' : 'proposal';
    
    const iconMap = {
        'front': '<i class="fas fa-id-card text-blue-600 text-2xl"></i>',
        'back': '<i class="fas fa-id-card text-green-600 text-2xl"></i>',
        'selfie': '<i class="fas fa-camera text-purple-600 text-2xl"></i>',
        'signature': '<i class="fas fa-signature text-indigo-600 text-2xl"></i>',
        'authorization': '<i class="fas fa-file-alt text-orange-600 text-2xl"></i>',
        'proposal': '<i class="fas fa-file-contract text-teal-600 text-2xl"></i>'
    };
    
    const colorMap = {
        'front': 'blue', 'back': 'green', 'selfie': 'purple', 
        'signature': 'indigo', 'authorization': 'orange', 'proposal': 'teal'
    };
    
    const textMap = {
        'front': 'Upload ID Front', 'back': 'Upload ID Back', 'selfie': 'Upload Selfie',
        'signature': 'Upload Signature Image', 'authorization': 'Upload Authorization', 'proposal': 'Upload Proposal'
    };
    
    const color = colorMap[inputType];
    
    previewContainer.innerHTML = `
        <div class="w-16 h-16 bg-${color}-100 rounded-lg flex items-center justify-center mx-auto">
            ${iconMap[inputType]}
        </div>
        <p class="text-sm text-gray-600">${textMap[inputType]}</p>
        <button type="button" onclick="document.getElementById('${inputId}').click()" 
                class="px-4 py-2 bg-${color}-600 text-white text-sm rounded-lg hover:bg-${color}-700">
            Choose File
        </button>
    `;
}

// === SIGNATURE FUNCTIONS ===
let signatureCanvas = null;
let signatureContext = null;
let isDrawing = false;
let hasSignature = false;

function initializeSignatureCanvas() {
    signatureCanvas = document.getElementById('signature_canvas');
    if (signatureCanvas) {
        signatureContext = signatureCanvas.getContext('2d');
        
        // Set up canvas
        signatureContext.strokeStyle = '#000000';
        signatureContext.lineWidth = 2;
        signatureContext.lineCap = 'round';
        
        // Mouse events
        signatureCanvas.addEventListener('mousedown', startDrawing);
        signatureCanvas.addEventListener('mousemove', draw);
        signatureCanvas.addEventListener('mouseup', stopDrawing);
        signatureCanvas.addEventListener('mouseout', stopDrawing);
        
        // Touch events for mobile
        signatureCanvas.addEventListener('touchstart', handleTouch);
        signatureCanvas.addEventListener('touchmove', handleTouch);
        signatureCanvas.addEventListener('touchend', stopDrawing);
    }
}

function startDrawing(e) {
    isDrawing = true;
    const rect = signatureCanvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    signatureContext.beginPath();
    signatureContext.moveTo(x, y);
}

function draw(e) {
    if (!isDrawing) return;
    
    const rect = signatureCanvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    signatureContext.lineTo(x, y);
    signatureContext.stroke();
    hasSignature = true;
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        signatureContext.beginPath();
    }
}

function handleTouch(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                     e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    signatureCanvas.dispatchEvent(mouseEvent);
}

function clearSignature() {
    if (signatureContext) {
        signatureContext.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
        hasSignature = false;
    }
}

function isSignatureCanvasEmpty() {
    return !hasSignature;
}

function toggleSignatureMethod(method) {
    const drawSection = document.getElementById('signature_draw_section');
    const uploadSection = document.getElementById('signature_upload_section');
    
    if (method === 'draw') {
        drawSection.classList.remove('hidden');
        uploadSection.classList.add('hidden');
        // Clear upload if switching to draw
        document.getElementById('signature_upload').value = '';
        removeFile('signature_upload', 'signature_upload_preview');
    } else {
        drawSection.classList.add('hidden');
        uploadSection.classList.remove('hidden');
        // Clear canvas if switching to upload
        clearSignature();
    }
}
</script>
@endpush
@endsection
