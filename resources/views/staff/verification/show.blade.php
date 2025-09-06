@extends('layouts.staff')

@section('page-title', 'Review Booking Requirements')
@section('page-description', 'Verify submitted documents and requirements')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Booking Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-lgu-headline">Booking #{{ $booking->id }}</h1>
                <p class="text-lgu-paragraph mt-1">{{ $booking->event_name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    @if($booking->status === 'pending')
                        bg-yellow-100 text-yellow-800
                    @elseif($booking->status === 'approved')
                        bg-green-100 text-green-800
                    @elseif($booking->status === 'rejected')
                        bg-red-100 text-red-800
                    @endif">
                    @if($booking->status === 'pending')
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($booking->status === 'approved')
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($booking->status === 'rejected')
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    {{ ucfirst($booking->status) }}
                </span>
                
                @if($booking->staff_verified_by)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Staff Verified
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-lgu-headline mb-4">Booking Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Event Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->event_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Facility</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->facility->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Event Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->event_date)->format('F j, Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Time</label>
                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Expected Attendees</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->expected_attendees }} people</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Total Fee</label>
                        <p class="mt-1 text-sm text-gray-900">₱{{ number_format($booking->total_fee, 2) }}</p>
                    </div>
                </div>
                
                @if($booking->event_description)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-lgu-paragraph">Event Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->event_description }}</p>
                    </div>
                @endif
            </div>

            <!-- Applicant Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-lgu-headline mb-4">Applicant Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Full Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->applicant_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->applicant_email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->applicant_phone }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-lgu-paragraph">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->applicant_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Submitted Documents -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-lgu-headline mb-4">Submitted Documents</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($booking->valid_id_path)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Valid ID (Front)</h3>
                            @if(file_exists(public_path($booking->valid_id_path)))
                                <img src="{{ asset($booking->valid_id_path) }}" alt="Valid ID" class="w-full h-32 object-cover rounded border">
                                <a href="{{ asset($booking->valid_id_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-lgu-highlight hover:text-lgu-headline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    View Full Size
                                </a>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center">
                                    <span class="text-gray-500">Image not found</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($booking->id_back_path)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Valid ID (Back)</h3>
                            @if(file_exists(public_path($booking->id_back_path)))
                                <img src="{{ asset($booking->id_back_path) }}" alt="ID Back" class="w-full h-32 object-cover rounded border">
                                <a href="{{ asset($booking->id_back_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-lgu-highlight hover:text-lgu-headline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    View Full Size
                                </a>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center">
                                    <span class="text-gray-500">Image not found</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($booking->id_selfie_path)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">ID Selfie</h3>
                            @if(file_exists(public_path($booking->id_selfie_path)))
                                <img src="{{ asset($booking->id_selfie_path) }}" alt="ID Selfie" class="w-full h-32 object-cover rounded border">
                                <a href="{{ asset($booking->id_selfie_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-lgu-highlight hover:text-lgu-headline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    View Full Size
                                </a>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center">
                                    <span class="text-gray-500">Image not found</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($booking->authorization_letter_path)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Authorization Letter</h3>
                            <img src="{{ asset('storage/' . $booking->authorization_letter_path) }}" alt="Authorization Letter" class="w-full h-32 object-cover rounded border">
                            <a href="{{ asset('storage/' . $booking->authorization_letter_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-lgu-highlight hover:text-lgu-headline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                View Full Size
                            </a>
                        </div>
                    @endif

                    @if($booking->event_proposal_path)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Event Proposal</h3>
                            <img src="{{ asset('storage/' . $booking->event_proposal_path) }}" alt="Event Proposal" class="w-full h-32 object-cover rounded border">
                            <a href="{{ asset('storage/' . $booking->event_proposal_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-lgu-highlight hover:text-lgu-headline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                View Full Size
                            </a>
                        </div>
                    @endif

                    @if($booking->digital_signature)
                        <div class="border border-gray-200 rounded-lg p-4 md:col-span-2">
                            <h3 class="font-medium text-gray-900 mb-2">Digital Signature</h3>
                            @if(str_starts_with($booking->digital_signature, 'data:image'))
                                {{-- Base64 drawn signature --}}
                                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4">
                                    <img src="{{ $booking->digital_signature }}" alt="Digital Signature" class="max-w-full h-auto max-h-32">
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Drawn signature</p>
                            @else
                                {{-- File-based signature --}}
                                @if(file_exists(public_path($booking->digital_signature)))
                                    <img src="{{ asset($booking->digital_signature) }}" alt="Digital Signature" class="w-full h-32 object-contain rounded border bg-white">
                                    <a href="{{ asset($booking->digital_signature) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-lgu-highlight hover:text-lgu-headline">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        View Full Size
                                    </a>
                                @else
                                    <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center">
                                        <span class="text-gray-500">Signature file not found</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Verification Actions -->
            @if(!$booking->staff_verified_by && $booking->status === 'pending')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-lgu-headline mb-4">Verification Action</h3>
                    
                    <!-- Approve Form -->
                    <form action="{{ route('staff.verification.approve', $booking) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="staff_notes" class="block text-sm font-medium text-lgu-paragraph">Verification Notes</label>
                            <textarea name="staff_notes" id="staff_notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-lgu-highlight focus:border-lgu-highlight" placeholder="Optional notes about document verification..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Approve Requirements
                        </button>
                    </form>
                    
                    <!-- Reject Form -->
                    <form action="{{ route('staff.verification.reject', $booking) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="rejection_reason" class="block text-sm font-medium text-lgu-paragraph">Rejection Reason *</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-lgu-highlight focus:border-lgu-highlight" placeholder="Please specify why the requirements are being rejected..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reject Requirements
                        </button>
                    </form>
                </div>
            @endif

            <!-- Verification History -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-lgu-headline mb-4">Verification History</h3>
                
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Booking Submitted</p>
                            <p class="text-sm text-gray-500">{{ $booking->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($booking->staff_verified_by)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Requirements Verified</p>
                                <p class="text-sm text-gray-500">{{ $booking->staffVerifier->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->staff_verified_at->format('M j, Y g:i A') }}</p>
                                @if($booking->staff_notes)
                                    <p class="text-sm text-gray-600 mt-1 italic">{{ $booking->staff_notes }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    @if($booking->approved_by)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Booking Approved</p>
                                <p class="text-sm text-gray-500">{{ $booking->approver->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->approved_at->format('M j, Y g:i A') }}</p>
                                @if($booking->admin_notes)
                                    <p class="text-sm text-gray-600 mt-1 italic">{{ $booking->admin_notes }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back to List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <a href="{{ route('staff.verification.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lgu-highlight">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    Back to Verification List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
