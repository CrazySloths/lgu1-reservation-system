@extends('citizen.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Help & Frequently Asked Questions</h1>
                <p class="text-blue-100 mt-1">Everything you need to know about facility reservations</p>
            </div>
        </div>
    </div>

    <!-- Quick Start Guide -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
            </svg>
            Quick Start: How to Make a Reservation
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">1</div>
                <h3 class="font-semibold text-gray-900 mb-2">Check Availability</h3>
                <p class="text-sm text-gray-600">View the calendar to see available dates and facilities</p>
            </div>
            
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">2</div>
                <h3 class="font-semibold text-gray-900 mb-2">Submit Request</h3>
                <p class="text-sm text-gray-600">Fill out the booking form with your event details</p>
            </div>
            
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">3</div>
                <h3 class="font-semibold text-gray-900 mb-2">Upload Documents</h3>
                <p class="text-sm text-gray-600">Provide required IDs and certificates</p>
            </div>
            
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">4</div>
                <h3 class="font-semibold text-gray-900 mb-2">Wait for Approval</h3>
                <p class="text-sm text-gray-600">Staff will review within 24-48 hours</p>
            </div>
            
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">5</div>
                <h3 class="font-semibold text-gray-900 mb-2">Pay & Confirm</h3>
                <p class="text-sm text-gray-600">Download payment slip and complete payment</p>
            </div>
        </div>

        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-900 flex items-start">
                <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Pro Tip:</strong> Have all your documents ready before starting your reservation to ensure a smooth and quick approval process.</span>
            </p>
        </div>
    </div>

    <!-- FAQ Sections -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            Frequently Asked Questions
        </h2>

        <div class="space-y-8">
            @foreach($faqs as $categoryIndex => $category)
                <div>
                    <h3 class="text-xl font-bold text-blue-600 mb-4 pb-2 border-b-2 border-blue-200">
                        {{ $category['category'] }}
                    </h3>
                    <div class="space-y-4">
                        @foreach($category['questions'] as $faq)
                            <div class="border-l-4 border-blue-400 pl-4 py-2 bg-gray-50 rounded-r">
                                <p class="font-semibold text-gray-900 mb-2">
                                    <svg class="w-4 h-4 inline mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $faq['question'] }}
                                </p>
                                <p class="text-gray-700 text-sm pl-5">{{ $faq['answer'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tips for Success -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            Tips for a Successful Reservation
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-bold text-yellow-900 mb-2">Book Early</h3>
                <p class="text-sm text-gray-700">Popular dates fill up quickly. Book 2-3 months in advance for best availability.</p>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-bold text-green-900 mb-2">Clear Documents</h3>
                <p class="text-sm text-gray-700">Ensure all uploaded documents are clear, well-lit, and show all required information.</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-bold text-blue-900 mb-2">Accurate Information</h3>
                <p class="text-sm text-gray-700">Double-check all details before submitting to avoid delays in approval.</p>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <h3 class="font-bold text-purple-900 mb-2">Check Email</h3>
                <p class="text-sm text-gray-700">Monitor your email for approval notifications and important updates.</p>
            </div>

            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                <h3 class="font-bold text-pink-900 mb-2">Pay Promptly</h3>
                <p class="text-sm text-gray-700">Complete payment within the due date to secure your reservation.</p>
            </div>

            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                <h3 class="font-bold text-indigo-900 mb-2">Read Policies</h3>
                <p class="text-sm text-gray-700">Familiarize yourself with facility rules and cancellation policies.</p>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
            </svg>
            Need More Help? Contact Us
        </h2>

        <p class="text-gray-600 mb-6">Can't find the answer you're looking for? Our support team is here to help!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($contacts as $contact)
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow bg-gradient-to-br from-gray-50 to-white">
                    <h3 class="font-bold text-blue-600 text-lg mb-3">{{ $contact['title'] }}</h3>
                    <div class="space-y-3 text-sm">
                        <p class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"/>
                            </svg>
                            <strong class="mr-1">Office:</strong> {{ $contact['department'] }}
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            {{ $contact['phone'] }}
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            {{ $contact['email'] }}
                        </p>
                        <p class="flex items-center text-gray-600 text-xs pt-2 border-t">
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

    <!-- Still Need Help -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg p-6">
        <div class="text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"/>
            </svg>
            <h3 class="text-2xl font-bold mb-2">Still Have Questions?</h3>
            <p class="text-blue-100 mb-4">We're here to help! Contact our support team for personalized assistance.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="mailto:support@lgu1.com" class="px-6 py-3 bg-white text-blue-700 rounded-lg font-semibold hover:bg-blue-50 transition-colors inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    Email Support
                </a>
                <a href="tel:+63XXXXXXXXX" class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-400 transition-colors inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    Call Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

