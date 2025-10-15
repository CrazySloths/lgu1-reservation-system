@extends('citizen.layouts.app-sidebar')

@section('title', 'Help & FAQ - LGU1 Citizen Portal')
@section('page-title', 'Help & Support')
@section('page-description', 'Get answers to your questions or submit a new inquiry')

@section('content')
<div class="space-y-6">
    
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Submit Question Form -->
    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-blue-200">
        <div class="bg-blue-600 text-white rounded-t-lg p-4 -m-6 mb-6">
            <h2 class="text-2xl font-bold mb-2">Need Help?</h2>
            <p class="text-blue-50">Can't find your answer below? Submit your question and our staff will respond within 24 hours.</p>
        </div>
        
        <form method="POST" action="{{ route('citizen.help-faq.submit') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Your Name *</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Your Email *</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Category *</label>
                <select name="category" required 
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white">
                    <option value="">Select a category</option>
                    <option value="booking">Booking & Reservations</option>
                    <option value="payment">Payment & Fees</option>
                    <option value="documents">Required Documents</option>
                    <option value="cancellation">Cancellation & Refunds</option>
                    <option value="account">Account & Profile</option>
                    <option value="technical">Technical Issues</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Your Question *</label>
                <textarea name="question" rows="4" required 
                          class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white"
                          placeholder="Please describe your question or concern in detail..."></textarea>
            </div>
            
            <button type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow">
                Submit Question
            </button>
        </form>
    </div>

    <!-- Quick Reference Guide -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Quick Reference: How to Make a Reservation</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl font-bold text-blue-600">1</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Check Calendar</h3>
                <p class="text-sm text-gray-600">View facility availability</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl font-bold text-green-600">2</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Submit Request</h3>
                <p class="text-sm text-gray-600">Fill out booking form</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl font-bold text-yellow-600">3</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Upload Docs</h3>
                <p class="text-sm text-gray-600">Provide required IDs</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl font-bold text-purple-600">4</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Wait for Approval</h3>
                <p class="text-sm text-gray-600">Staff review (24-48h)</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl font-bold text-pink-600">5</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Pay & Confirm</h3>
                <p class="text-sm text-gray-600">Complete payment</p>
            </div>
        </div>
    </div>

    <!-- Frequently Asked Questions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>

        <div class="space-y-6">
            @foreach($faqs as $category)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-lgu-highlight">
                        {{ $category['category'] }}
                    </h3>
                    <div class="space-y-4">
                        @foreach($category['questions'] as $faq)
                            <div class="pl-4 border-l-4 border-gray-200 hover:border-lgu-highlight transition-colors">
                                <p class="font-semibold text-gray-900 mb-1">{{ $faq['question'] }}</p>
                                <p class="text-gray-700 text-sm">{{ $faq['answer'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Contact Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Contact Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($contacts as $contact)
                <div class="border-l-4 border-lgu-highlight pl-4">
                    <h3 class="font-bold text-lgu-green mb-2">{{ $contact['title'] }}</h3>
                    <div class="space-y-1 text-sm text-gray-700">
                        <p><strong>Department:</strong> {{ $contact['department'] }}</p>
                        <p><strong>Phone:</strong> {{ $contact['phone'] }}</p>
                        <p><strong>Email:</strong> {{ $contact['email'] }}</p>
                        <p class="text-xs text-gray-600 mt-2">{{ $contact['hours'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Important Reminders -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h2 class="text-xl font-bold text-yellow-900 mb-4">Important Reminders</h2>
        <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Book early:</strong> Reserve facilities 2-3 months in advance for popular dates (especially holidays and weekends)</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Clear documents:</strong> Ensure all uploaded documents are clear, legible, and not expired</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Payment deadline:</strong> Pay within 7 days after approval or your reservation will be cancelled</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Monitor email:</strong> Check your email regularly for approval notifications and important updates</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Cancellation policy:</strong> Contact the admin office as soon as possible if you need to cancel or modify your reservation</span>
            </li>
        </ul>
    </div>
</div>
@endsection
