@extends('layouts.app')

@section('title', 'Reservation Review - Admin Portal')

@section('content')
<div class="space-y-6">
    <!-- Enhanced Header Section -->
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
        </div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-1 text-white">Reservation Review</h1>
                    <p class="text-gray-200 text-lg">Review and manage citizen facility reservations</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-6">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1 1 0 01-1.497 1.32L10 11.819l-2.851 4.35a1 1 0 11-1.497-1.32L8.503 10 5.652 5.651a1 1 0 111.497-1.32L10 8.181l2.851-4.35a1 1 0 111.497 1.32L11.497 10l2.851 4.849z"/>
            </svg>
        </span>
    </div>
</div>
@endif

<!-- Status Filter Tabs -->
<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pending Review
                @if($statusCounts['pending'] > 0)
                    <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $statusCounts['pending'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.reservations.index', ['status' => 'approved']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'approved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Approved
                <span class="ml-2 bg-green-100 text-green-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $statusCounts['approved'] }}</span>
            </a>
            <a href="{{ route('admin.reservations.index', ['status' => 'rejected']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Rejected
                <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $statusCounts['rejected'] }}</span>
            </a>
            <a href="{{ route('admin.reservations.index', ['status' => 'all']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'all' ? 'border-gray-500 text-gray-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                All Reservations
                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $statusCounts['all'] }}</span>
            </a>
        </nav>
    </div>
</div>

<!-- Reservations List -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    @if($reservations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Citizen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reservations as $reservation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-building text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->facility->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $reservation->facility->location ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->applicant_name }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->applicant_email ?? 'No email' }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->applicant_phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->event_name }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->event_date->format('M j, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->expected_attendees }} attendees</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                                @if($reservation->status === 'approved' && $reservation->approved_at)
                                    <div class="text-xs text-gray-500 mt-1">{{ $reservation->approved_at->format('M j, Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservation->created_at->format('M j, Y') }}
                                <div class="text-xs">{{ $reservation->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-eye mr-1"></i>
                                    Review
                                </a>
                                @if($reservation->status === 'pending')
                                    <button onclick="quickApprove({{ $reservation->id }})" 
                                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700">
                                        <i class="fas fa-check mr-1"></i>
                                        Quick Approve
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $reservations->appends(request()->query())->links() }}
            </div>
        @endif

    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-inbox text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Reservations Found</h3>
            <p class="text-gray-600">
                @if($status === 'pending')
                    No pending reservations require review at this time.
                @elseif($status === 'approved')
                    No approved reservations to display.
                @elseif($status === 'rejected')
                    No rejected reservations to display.
                @else
                    No reservations have been submitted yet.
                @endif
            </p>
        </div>
    @endif
</div>

<!-- Quick Approval Modal -->
<div id="quickApprovalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Quick Approve Reservation</h3>
                <button type="button" onclick="closeQuickApprovalModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="quickApprovalForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                    <textarea name="admin_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Add any notes for this approval..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeQuickApprovalModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>
                        Approve
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
let currentReservationId = null;

function quickApprove(reservationId) {
    currentReservationId = reservationId;
    document.getElementById('quickApprovalModal').classList.remove('hidden');
}

function closeQuickApprovalModal() {
    document.getElementById('quickApprovalModal').classList.add('hidden');
    currentReservationId = null;
    document.getElementById('quickApprovalForm').reset();
}

document.getElementById('quickApprovalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentReservationId) return;
    
    const formData = new FormData(this);
    
    fetch(`/admin/reservations/${currentReservationId}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        closeQuickApprovalModal();
        
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#10B981'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An error occurred',
                confirmButtonColor: '#EF4444'
            });
        }
    })
    .catch(error => {
        closeQuickApprovalModal();
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An error occurred while processing the request',
            confirmButtonColor: '#EF4444'
        });
    });
});
</script>
@endpush
