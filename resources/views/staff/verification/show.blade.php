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
                                    <span class="text-gray-600">{{ \Carbon\Carbon::parse($booking->event_date)->format('F j, Y') }}</span>
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
                    <!-- Placeholder for documents -->
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600">Document management system integration coming soon</p>
                        <p class="text-sm text-gray-500">For now, verify requirements based on booking details</p>
                    </div>
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
                    <form action="{{ route('staff.verification.process', $booking) }}" method="POST" id="verificationForm">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Verification Decision</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="action" value="approve" class="focus:ring-lgu-highlight h-4 w-4 text-lgu-highlight border-gray-300" required>
                                    <span class="ml-3 text-sm text-gray-900">Approve - Requirements are complete</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="action" value="reject" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" required>
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

            <!-- Verification Checklist -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Verification Checklist</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="focus:ring-lgu-highlight h-4 w-4 text-lgu-highlight border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-900">Complete booking information</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="focus:ring-lgu-highlight h-4 w-4 text-lgu-highlight border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-900">Valid contact information</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="focus:ring-lgu-highlight h-4 w-4 text-lgu-highlight border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-900">Appropriate event type</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="focus:ring-lgu-highlight h-4 w-4 text-lgu-highlight border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-900">Facility availability confirmed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="focus:ring-lgu-highlight h-4 w-4 text-lgu-highlight border-gray-300 rounded">
                            <span class="ml-3 text-gray-900">Required documents submitted</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">Check each item before making your verification decision</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Dialog -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verificationForm');
    
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
