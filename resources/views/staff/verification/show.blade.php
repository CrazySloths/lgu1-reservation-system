@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Booking Verification</h1>
                <p class="text-gray-200">Review requirements and documents for approval</p>
            </div>
            <div class="text-right space-y-2">
                <a href="{{ route('staff.verification.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Queue
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Booking Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->event_name }}</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">{{ $booking->facility->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">{{ $booking->event_date->format('F j, Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900 mb-2">Event Details</h5>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Expected Attendees:</span>
                                    <span class="text-gray-900 ml-2">{{ $booking->attendees ?? 'Not specified' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Event Type:</span>
                                    <span class="text-gray-900 ml-2">{{ ucfirst($booking->event_type ?? 'General') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Submitted:</span>
                                    <span class="text-gray-900 ml-2">{{ $booking->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organizer Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Organizer Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-lgu-highlight rounded-full flex items-center justify-center">
                            <span class="text-lgu-button-text font-bold text-lg">
                                {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900">{{ $booking->user->name }}</h4>
                            <p class="text-gray-600">{{ $booking->user->email }}</p>
                            @if($booking->user->phone)
                                <p class="text-gray-600">{{ $booking->user->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents & Requirements -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Documents & Requirements</h3>
                </div>
                <div class="p-6">
                    @php
                        $hasDocuments = $booking->valid_id_path || $booking->id_back_path || $booking->id_selfie_path || 
                                       $booking->authorization_letter_path || $booking->event_proposal_path || $booking->digital_signature;
                    @endphp
                    
                    @if($hasDocuments)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Valid ID (Front) -->
                            @if($booking->valid_id_path)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-lgu-highlight transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">Valid ID (Front)</h4>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $booking->id_type ?? 'ID' }}</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $booking->valid_id_path) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $booking->valid_id_path) }}" 
                                             alt="Valid ID Front" 
                                             class="w-full h-48 object-contain rounded border border-gray-200 hover:opacity-90 transition-opacity bg-gray-50"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-gray-100 rounded border border-gray-200 flex items-center justify-center\' style=\'height: 192px;\'><div class=\'text-center p-4\'><svg class=\'w-12 h-12 text-gray-400 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg><p class=\'text-xs font-medium text-gray-700\'>Image Preview Unavailable</p><p class=\'text-xs text-gray-500 mt-1\'>{{ basename($booking->valid_id_path) }}</p></div></div>';">
                                    </a>
                                    <p class="text-xs text-gray-500 mt-2">Click image to view full size</p>
                                </div>
                            @endif

                            <!-- Valid ID (Back) -->
                            @if($booking->id_back_path)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-lgu-highlight transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">Valid ID (Back)</h4>
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">✓ Uploaded</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $booking->id_back_path) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $booking->id_back_path) }}" 
                                             alt="Valid ID Back" 
                                             class="w-full h-48 object-contain rounded border border-gray-200 hover:opacity-90 transition-opacity bg-gray-50"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-gray-100 rounded border border-gray-200 flex items-center justify-center\' style=\'height: 192px;\'><div class=\'text-center p-4\'><svg class=\'w-12 h-12 text-gray-400 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg><p class=\'text-xs font-medium text-gray-700\'>Image Preview Unavailable</p><p class=\'text-xs text-gray-500 mt-1\'>{{ basename($booking->id_back_path) }}</p></div></div>';">
                                    </a>
                                    <p class="text-xs text-gray-500 mt-2">Click image to view full size</p>
                                </div>
                            @endif

                            <!-- ID Selfie -->
                            @if($booking->id_selfie_path)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-lgu-highlight transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">ID Selfie</h4>
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">✓ Uploaded</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $booking->id_selfie_path) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $booking->id_selfie_path) }}" 
                                             alt="ID Selfie" 
                                             class="w-full h-48 object-contain rounded border border-gray-200 hover:opacity-90 transition-opacity bg-gray-50"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-gray-100 rounded border border-gray-200 flex items-center justify-center\' style=\'height: 192px;\'><div class=\'text-center p-4\'><svg class=\'w-12 h-12 text-gray-400 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'/></svg><p class=\'text-xs font-medium text-gray-700\'>Image Preview Unavailable</p><p class=\'text-xs text-gray-500 mt-1\'>{{ basename($booking->id_selfie_path) }}</p></div></div>';">
                                    </a>
                                    <p class="text-xs text-gray-500 mt-2">Click image to view full size</p>
                                </div>
                            @endif

                            <!-- Authorization Letter (Optional) -->
                            @if($booking->authorization_letter_path)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-lgu-highlight transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">Authorization Letter</h4>
                                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">Optional</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $booking->authorization_letter_path) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $booking->authorization_letter_path) }}" 
                                             alt="Authorization Letter" 
                                             class="w-full h-48 object-contain rounded border border-gray-200 hover:opacity-90 transition-opacity bg-gray-50"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-gray-100 rounded border border-gray-200 flex items-center justify-center\' style=\'height: 192px;\'><div class=\'text-center p-4\'><svg class=\'w-12 h-12 text-gray-400 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg><p class=\'text-xs font-medium text-gray-700\'>Document Uploaded</p><p class=\'text-xs text-gray-500 mt-1\'>{{ basename($booking->authorization_letter_path) }}</p></div></div>';">
                                    </a>
                                    <p class="text-xs text-gray-500 mt-2">Click image to view full size</p>
                                </div>
                            @endif

                            <!-- Event Proposal (Optional) -->
                            @if($booking->event_proposal_path)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-lgu-highlight transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">Event Proposal</h4>
                                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">Optional</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $booking->event_proposal_path) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $booking->event_proposal_path) }}" 
                                             alt="Event Proposal" 
                                             class="w-full h-48 object-contain rounded border border-gray-200 hover:opacity-90 transition-opacity bg-gray-50"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-gray-100 rounded border border-gray-200 flex items-center justify-center\' style=\'height: 192px;\'><div class=\'text-center p-4\'><svg class=\'w-12 h-12 text-gray-400 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg><p class=\'text-xs font-medium text-gray-700\'>Document Uploaded</p><p class=\'text-xs text-gray-500 mt-1\'>{{ basename($booking->event_proposal_path) }}</p></div></div>';">
                                    </a>
                                    <p class="text-xs text-gray-500 mt-2">Click image to view full size</p>
                                </div>
                            @endif

                            <!-- Digital Signature -->
                            @if($booking->digital_signature)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-lgu-highlight transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">Digital Signature</h4>
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">✓ Signed</span>
                                    </div>
                                    <div class="bg-white rounded border border-gray-300 p-6 flex items-center justify-center" style="min-height: 150px;">
                                        @php
                                            $sigLength = strlen($booking->digital_signature);
                                            $isValidSignature = strpos($booking->digital_signature, 'data:image') === 0 && $sigLength > 100;
                                        @endphp
                                        
                                        @if($isValidSignature)
                                            <img src="{{ $booking->digital_signature }}" alt="Digital Signature" class="max-h-32 max-w-full" style="image-rendering: crisp-edges;">
                                        @else
                                            <div class="text-center p-4">
                                                <svg class="w-16 h-16 text-orange-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                <p class="text-sm text-orange-600 font-medium">Signature Preview Unavailable</p>
                                                <p class="text-xs text-gray-500 mt-1">Signature data was corrupted or incomplete</p>
                                                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded">
                                                    <p class="text-xs text-blue-800"><strong>Verification Note:</strong></p>
                                                    <p class="text-xs text-blue-700 mt-1">• Compare signature on uploaded ID documents</p>
                                                    <p class="text-xs text-blue-700">• If unclear, request applicant to re-submit via email</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Applicant's digital signature</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-900">All Required Documents Submitted</p>
                                    <p class="text-xs text-green-700 mt-1">Documents confirmed: {{ $booking->id_type ?? 'Valid ID' }} (front & back), ID Selfie{{ $booking->digital_signature ? ', and Digital Signature' : '' }}.</p>
                                    @if($booking->digital_signature && strlen($booking->digital_signature) < 100)
                                        <p class="text-xs text-orange-600 mt-1"><em>Note: Digital signature preview unavailable. Please verify signature on the uploaded ID documents.</em></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-600">No documents uploaded</p>
                            <p class="text-sm text-gray-500">The applicant hasn't submitted any verification documents yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Verification Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Verification Action</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('staff.verification.approve', $booking->id) }}" method="POST" id="verificationForm">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Verification Decision</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="action" value="approve" class="action-radio focus:ring-lgu-highlight h-4 w-4 text-lgu-highlight border-gray-300" data-route="{{ route('staff.verification.approve', $booking->id) }}" required>
                                    <span class="ml-3 text-sm text-gray-900">Approve - Requirements are complete</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="action" value="reject" class="action-radio focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" data-route="{{ route('staff.verification.reject', $booking->id) }}" required>
                                    <span class="ml-3 text-sm text-gray-900">Reject - Requirements incomplete/invalid</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="staff_notes" class="block text-sm font-medium text-gray-700 mb-2">Verification Notes</label>
                            <textarea name="staff_notes" id="staff_notes" rows="4" 
                                      class="block w-full rounded-lg border-gray-300 focus:ring-lgu-highlight focus:border-lgu-highlight" 
                                      placeholder="Add notes about your verification decision..." required></textarea>
                            <p class="text-xs text-gray-500 mt-1">Explain your decision for record keeping and feedback</p>
                        </div>

                        <div class="space-y-3">
                            <button type="submit" class="w-full bg-lgu-highlight text-lgu-button-text px-4 py-3 rounded-lg font-medium hover:bg-lgu-button transition-colors">
                                Submit Verification
                            </button>
                            <a href="{{ route('staff.verification.index') }}" class="block w-full text-center px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Back to Queue
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Info</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Booking ID:</span>
                            <span class="text-sm font-medium text-gray-900">#{{ $booking->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Status:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                Pending Verification
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Priority:</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if($booking->created_at->diffInHours() < 24)
                                    <span class="text-red-600">High</span>
                                @else
                                    <span class="text-green-600">Normal</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Days since submission:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $booking->created_at->diffInDays() }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Confirmation Dialog -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verificationForm');
    
    // Change form action based on selected radio button
    const radioButtons = document.querySelectorAll('.action-radio');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const route = this.getAttribute('data-route');
            form.action = route;
        });
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const action = document.querySelector('input[name="action"]:checked')?.value;
        const notes = document.getElementById('staff_notes').value;
        
        if (!action || !notes.trim()) {
            Swal.fire({
                title: 'Missing Information',
                text: 'Please select an action and provide verification notes.',
                icon: 'warning',
                confirmButtonColor: '#faae2b'
            });
            return;
        }
        
        const actionText = action === 'approve' ? 'approve' : 'reject';
        const actionColor = action === 'approve' ? '#10b981' : '#ef4444';
        
        Swal.fire({
            title: `Confirm ${actionText.charAt(0).toUpperCase() + actionText.slice(1)}?`,
            text: `Are you sure you want to ${actionText} this booking verification?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, ${actionText} it`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
