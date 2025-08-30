@extends('layouts.app')

@section('title', 'Review Reservation - Admin Portal')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Reservations
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Review Reservation #{{ $reservation->id }}</h1>
            <p class="text-gray-600">{{ $reservation->event_name }} - {{ $reservation->applicant_name }}</p>
        </div>
        <div class="flex items-center space-x-3">
            @if($reservation->status === 'pending')
                <form id="quickApproveForm" method="POST" action="/admin/reservations/{{ $reservation->id }}/approve" style="display: inline;">
                    @csrf
                    <input type="hidden" name="admin_notes" value="">
                    <button type="submit" id="approve-btn" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <i class="fas fa-check mr-2"></i>
                        Approve
                    </button>
                </form>
                <button id="reject-btn" 
                        onclick="document.getElementById('rejectionModal').classList.remove('hidden');"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="fas fa-times mr-2"></i>
                    Reject
                </button>
            @else
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                    {{ $reservation->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Reservation Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Event Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->event_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Facility</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->facility->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->event_date->format('F j, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Time</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->start_time }} - {{ $reservation->end_time }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Expected Attendees</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->expected_attendees }} people</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Fee</label>
                    <p class="mt-1 text-sm text-gray-900">₱{{ number_format($reservation->total_fee, 2) }}</p>
                </div>
            </div>
            @if($reservation->event_description)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Event Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->event_description }}</p>
                </div>
            @endif
        </div>

        <!-- Applicant Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Applicant Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->applicant_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->applicant_email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->applicant_phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->applicant_address }}</p>
                </div>
            </div>
        </div>

        <!-- Uploaded Documents -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Uploaded Documents</h3>
            
            @if(count($uploadedFiles) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($uploadedFiles as $fileKey => $file)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-900">{{ $file['name'] }}</h4>
                                @if($file['exists'])
                                    <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">✓ Uploaded</span>
                                @else
                                    <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded">✗ Missing</span>
                                @endif
                            </div>
                            
                            @if($file['exists'])
                                @if($file['type'] === 'image')
                                    <div class="mb-3">
                                        @if($fileKey === 'signature')
                                            {{-- Special handling for signature - could be base64 or file path --}}
                                            @if(str_starts_with($reservation->digital_signature, 'data:image'))
                                                <img src="{{ $reservation->digital_signature }}" 
                                                     alt="Digital Signature" 
                                                     class="w-full h-32 object-contain bg-gray-50 rounded border cursor-pointer hover:opacity-75"
                                                     onclick="showImageModal('{{ $reservation->digital_signature }}', '{{ $file['name'] }}')" />
                                            @else
                                                <img src="{{ route('admin.reservations.preview', [$reservation->id, $fileKey]) }}" 
                                                     alt="{{ $file['name'] }}" 
                                                     class="w-full h-32 object-contain bg-gray-50 rounded border cursor-pointer hover:opacity-75"
                                                     onclick="showImageModal('{{ route('admin.reservations.preview', [$reservation->id, $fileKey]) }}', '{{ $file['name'] }}')" />
                                            @endif
                                        @else
                                            {{-- Regular image files --}}
                                            <img src="{{ route('admin.reservations.preview', [$reservation->id, $fileKey]) }}" 
                                                 alt="{{ $file['name'] }}" 
                                                 class="w-full h-32 object-cover rounded border cursor-pointer hover:opacity-75"
                                                 onclick="showImageModal('{{ route('admin.reservations.preview', [$reservation->id, $fileKey]) }}', '{{ $file['name'] }}')" />
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <div class="w-full h-24 bg-gray-50 rounded border flex items-center justify-center">
                                            <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>{{ formatFileSize($file['size']) }}</span>
                                    <div class="space-x-2">
                                        @if($file['type'] === 'image' || $file['type'] === 'pdf')
                                            @if($fileKey === 'signature' && str_starts_with($reservation->digital_signature, 'data:image'))
                                                <button onclick="showImageModal('{{ $reservation->digital_signature }}', '{{ $file['name'] }}')" 
                                                        class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            @else
                                                <button onclick="showImageModal('{{ route('admin.reservations.preview', [$reservation->id, $fileKey]) }}', '{{ $file['name'] }}')" 
                                                        class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            @endif
                                        @endif
                                        @if($fileKey === 'signature' && str_starts_with($reservation->digital_signature, 'data:image'))
                                            {{-- Base64 signatures can't be directly downloaded, but we can still provide the route --}}
                                            <a href="{{ route('admin.reservations.download', [$reservation->id, $fileKey]) }}" 
                                               class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        @else
                                            <a href="{{ route('admin.reservations.download', [$reservation->id, $fileKey]) }}" 
                                               class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-gray-500 py-4">
                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                    <p class="text-sm mt-1">File not found</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                    <p>No documents uploaded</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Status & Actions -->
    <div class="space-y-6">
        <!-- Current Status -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Status</label>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        {{ $reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                           ($reservation->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Submitted</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $reservation->created_at->format('F j, Y g:i A') }}</p>
                </div>
                
                @if($reservation->approved_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Approved</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reservation->approved_at->format('F j, Y g:i A') }}</p>
                    </div>
                @endif
                
                @if($reservation->admin_notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Admin Notes</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reservation->admin_notes }}</p>
                    </div>
                @endif
                
                @if($reservation->rejected_reason)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                        <p class="mt-1 text-sm text-red-600">{{ $reservation->rejected_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button onclick="window.print()" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-print mr-2"></i>
                    Print Details
                </button>
                <a href="mailto:{{ $reservation->applicant_email }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-envelope mr-2"></i>
                    Email Citizen
                </a>
                <a href="tel:{{ $reservation->applicant_phone }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-phone mr-2"></i>
                    Call Citizen
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="max-w-4xl max-h-screen p-4">
        <div class="bg-white rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 id="imageTitle" class="text-lg font-semibold"></h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-96 mx-auto" />
        </div>
    </div>
</div>



<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Reject Reservation</h3>
                <button type="button" onclick="document.getElementById('rejectionModal').classList.add('hidden'); document.getElementById('rejectionForm').reset();" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="rejectionForm" onsubmit="return false;">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection <span class="text-red-500">*</span></label>
                    <textarea name="rejected_reason" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Please provide a clear reason for rejection..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea name="admin_notes" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Any additional information..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('rejectionModal').classList.add('hidden'); document.getElementById('rejectionForm').reset();" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="button" onclick="submitRejection()"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i>
                        Reject Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
console.log('Reservation review JavaScript loaded');

// Check if SweetAlert2 is available
if (typeof Swal === 'undefined') {
    console.error('SweetAlert2 not loaded!');
} else {
    console.log('SweetAlert2 is available');
}

// Define modal functions for image viewing (still needed for onclick attributes)
function showImageModal(src, title) {
    const modalImage = document.getElementById('modalImage');
    const imageTitle = document.getElementById('imageTitle');
    const modal = document.getElementById('imageModal');
    
    if (modalImage) modalImage.src = src;
    if (imageTitle) imageTitle.textContent = title;
    if (modal) modal.classList.remove('hidden');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) modal.classList.add('hidden');
}



// Rejection submission function
function submitRejection() {
    const form = document.getElementById('rejectionForm');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        alert('Security token missing. Please refresh the page.');
        return;
    }
    
    // Close modal first
    document.getElementById('rejectionModal').classList.add('hidden');
    
    fetch(`/admin/reservations/{{ $reservation->id }}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Immediately redirect to reservations table
            window.location = '/admin/reservations';
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Rejection Failed!',
                text: data.message || 'An error occurred',
                confirmButtonColor: '#EF4444'
            });
        }
    })
    .catch(error => {
        console.error('Rejection error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Network Error!',
            text: 'Could not process the rejection. Please try again.',
            confirmButtonColor: '#EF4444'
        });
    });
}

// Make image modal functions available globally
window.showImageModal = showImageModal;
window.closeImageModal = closeImageModal;

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    


    // Rejection Form Submission
    const rejectionForm = document.getElementById('rejectionForm');
    if (rejectionForm) {
        rejectionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(`/admin/reservations/{{ $reservation->id }}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Immediately redirect to reservations table
                    window.location = '/admin/reservations';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Rejection Failed!',
                        text: data.message || 'An error occurred',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                document.getElementById('rejectionModal').classList.add('hidden');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while processing the rejection',
                    confirmButtonColor: '#EF4444'
                });
            });
        });
    } else {
        console.error('Rejection form not found!');
    }

    // Close modals on outside click
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    }



    const rejectionModal = document.getElementById('rejectionModal');
    if (rejectionModal) {
        rejectionModal.addEventListener('click', function(e) {
            if (e.target === this) {
                document.getElementById('rejectionModal').classList.add('hidden');
                document.getElementById('rejectionForm').reset();
            }
        });
    }

    console.log('Form submission handlers ready!');
});
</script>
@endpush

@php
    function formatFileSize($bytes) {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
@endphp
