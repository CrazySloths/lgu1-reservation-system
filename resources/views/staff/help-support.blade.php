@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-lgu-green to-lgu-highlight text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Help & Support</h1>
                <p class="text-gray-100 mt-1">Verification guidelines, FAQs, and technical assistance</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="#guidelines" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900">Guidelines</span>
            </div>
        </a>
        <a href="#faq" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900">FAQ</span>
            </div>
        </a>
        <a href="#contact" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900">Contact</span>
            </div>
        </a>
        <a href="#report" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900">Report Issue</span>
            </div>
        </a>
    </div>

    <!-- Verification Guidelines -->
    <div id="guidelines" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Document Verification Guidelines
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Approval Criteria -->
            <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                <h3 class="font-bold text-green-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                     Approval Criteria
                </h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span>All required documents are submitted and complete</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span>Documents are clear, legible, and authentic</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span>Government ID is valid and not expired</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span>Payment proof matches the booking amount</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span>All names and information are consistent</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span>No scheduling conflicts with other bookings</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span>Event details are reasonable and appropriate</span>
                    </li>
                </ul>
            </div>

            <!-- Rejection Reasons -->
            <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                <h3 class="font-bold text-red-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                     Common Rejection Reasons
                </h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="text-red-600 mr-2">•</span>
                        <span>Incomplete or missing required documents</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-600 mr-2">•</span>
                        <span>Unclear, blurry, or illegible documents</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-600 mr-2">•</span>
                        <span>Expired or invalid government ID</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-600 mr-2">•</span>
                        <span>Suspected forged or altered documents</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-600 mr-2">•</span>
                        <span>Inconsistent information across documents</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-600 mr-2">•</span>
                        <span>Invalid or suspicious payment proof</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-600 mr-2">•</span>
                        <span>Scheduling conflict or facility unavailable</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Best Practices -->
        <div class="mt-6 border-t pt-6">
            <h3 class="font-bold text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Best Practices
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 border border-blue-200 rounded p-3">
                    <p class="text-sm font-semibold text-blue-900 mb-1">⏱ Response Time</p>
                    <p class="text-xs text-gray-700">Verify bookings within 24-48 hours to maintain service quality</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded p-3">
                    <p class="text-sm font-semibold text-purple-900 mb-1"> Attention to Detail</p>
                    <p class="text-xs text-gray-700">Carefully review every document and cross-check information</p>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded p-3">
                    <p class="text-sm font-semibold text-orange-900 mb-1"> Clear Notes</p>
                    <p class="text-xs text-gray-700">Always add detailed notes when rejecting applications</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div id="faq" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            Frequently Asked Questions
        </h2>

        <div class="space-y-6">
            @foreach($faqs as $category)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">{{ $category['category'] }}</h3>
                    <div class="space-y-4">
                        @foreach($category['questions'] as $faq)
                            <div class="border-l-4 border-lgu-highlight pl-4 py-2">
                                <p class="font-semibold text-gray-900 mb-1">Q: {{ $faq['question'] }}</p>
                                <p class="text-gray-700 text-sm">A: {{ $faq['answer'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Contact Information -->
    <div id="contact" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
            </svg>
            Contact Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($contacts as $contact)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h3 class="font-bold text-lgu-green mb-3">{{ $contact['title'] }}</h3>
                    <div class="space-y-2 text-sm">
                        <p class="flex items-center text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            {{ $contact['name'] }}
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            {{ $contact['phone'] }}
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            {{ $contact['email'] }}
                        </p>
                        <p class="flex items-center text-gray-600 text-xs mt-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            {{ $contact['hours'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Report Issue Form -->
    <div id="report" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            Report an Issue
        </h2>

        <form action="{{ route('staff.help-support.submit-issue') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Issue Type</label>
                <select name="issue_type" required class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                    <option value="">Select issue type...</option>
                    <option value="technical">Technical Problem</option>
                    <option value="suspicious">Suspicious Activity</option>
                    <option value="document">Document Issue</option>
                    <option value="system">System Error</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                <input type="text" name="subject" required maxlength="255" 
                    class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight"
                    placeholder="Brief description of the issue">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" required rows="6" 
                    class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight"
                    placeholder="Please provide detailed information about the issue, including steps to reproduce if applicable..."></textarea>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <p class="text-sm text-gray-600">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    For urgent matters, please contact admin directly.
                </p>
                <button type="submit" class="px-6 py-2 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-highlight transition-colors">
                    Submit Issue
                </button>
            </div>
        </form>
    </div>

    <!-- System Status -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
            </svg>
            System Status
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-gray-900">Verification System</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                        Online
                    </span>
                </div>
                <p class="text-sm text-gray-600">All systems operational</p>
            </div>

            <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-gray-900">Document Preview</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                        Online
                    </span>
                </div>
                <p class="text-sm text-gray-600">All systems operational</p>
            </div>

            <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-gray-900">Database</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                        Online
                    </span>
                </div>
                <p class="text-sm text-gray-600">All systems operational</p>
            </div>
        </div>

        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-900">
                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <strong>Scheduled Maintenance:</strong> No maintenance scheduled. System updates are performed during off-peak hours.
            </p>
        </div>
    </div>
</div>
@endsection

