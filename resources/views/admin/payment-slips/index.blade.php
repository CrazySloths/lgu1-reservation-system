@extends('layouts.app')

@section('title', 'Payment Slips Management - Admin Portal')

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
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8zM6 10a2 2 0 114 0 2 2 0 01-4 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-1 text-white">Payment Slips Management</h1>
                        <p class="text-gray-200 text-lg">Manage citizen payment slips and record payments</p>
                    </div>
                </div>
            </div>
            <div class="text-right space-y-3">
                <button onclick="markExpiredSlips()" 
                        class="inline-flex items-center px-6 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-all shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Mark Expired
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Status Tabs -->
<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('admin.payment-slips.index', ['status' => 'all']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                All 
                <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $status === 'all' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $statusCounts['all'] }}
                </span>
            </a>
            <a href="{{ route('admin.payment-slips.index', ['status' => 'unpaid']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'unpaid' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Unpaid 
                <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $status === 'unpaid' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $statusCounts['unpaid'] }}
                </span>
            </a>
            <a href="{{ route('admin.payment-slips.index', ['status' => 'paid']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'paid' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Paid 
                <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $status === 'paid' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $statusCounts['paid'] }}
                </span>
            </a>
            <a href="{{ route('admin.payment-slips.index', ['status' => 'expired']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'expired' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Expired 
                <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $status === 'expired' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $statusCounts['expired'] }}
                </span>
            </a>
        </nav>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if($paymentSlips->count() > 0)
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Payment Slip
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Citizen
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Event Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Due Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($paymentSlips as $slip)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $slip->slip_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $slip->created_at->format('M j, Y g:i A') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $slip->booking->applicant_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $slip->booking->applicant_email }}</div>
                                    <div class="text-sm text-gray-500">{{ $slip->booking->applicant_phone }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $slip->booking->event_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $slip->booking->facility->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $slip->booking->event_date->format('M j, Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">₱{{ number_format($slip->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($slip->status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Paid
                                    </span>
                                @elseif($slip->status === 'expired')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $slip->due_date->format('M j, Y') }}</div>
                                @if($slip->status === 'unpaid')
                                    @if($slip->days_until_due > 0)
                                        <div class="text-sm text-yellow-600">{{ $slip->days_until_due }} days left</div>
                                    @else
                                        <div class="text-sm text-red-600">Overdue</div>
                                    @endif
                                @endif
                                @if($slip->paid_at)
                                    <div class="text-sm text-green-600">Paid {{ $slip->paid_at->format('M j') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($slip->status === 'unpaid')
                                        <button onclick="markAsPaid({{ $slip->id }})" 
                                                class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-money-bill-wave"></i> Mark Paid
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.reservations.show', $slip->booking->id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($paymentSlips->hasPages())
        <div class="mt-6">
            {{ $paymentSlips->links() }}
        </div>
    @endif

@else
    <div class="bg-white shadow rounded-lg p-12 text-center">
        <div class="max-w-md mx-auto">
            <i class="fas fa-receipt text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                @if($status === 'all')
                    No Payment Slips Found
                @else
                    No {{ ucfirst($status) }} Payment Slips
                @endif
            </h3>
            <p class="text-gray-500 mb-6">
                @if($status === 'all')
                    Payment slips will appear here when reservations are approved.
                @else
                    No payment slips with "{{ $status }}" status found.
                @endif
            </p>
            <a href="{{ route('admin.reservations.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-calendar-check mr-2"></i>
                Review Reservations
            </a>
        </div>
    </div>
@endif

<!-- Mark as Paid Modal -->
<div id="markPaidModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Record Payment</h3>
                <button type="button" onclick="closeMarkPaidModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="markPaidForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                    <select name="payment_method" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Select payment method</option>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                        <option value="money_order">Money Order</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cashier Notes (Optional)</label>
                    <textarea name="cashier_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                              placeholder="Add any notes about this payment..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeMarkPaidModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>
                        Record Payment
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
let currentSlipId = null;

// Mark as Paid Modal Functions
function markAsPaid(slipId) {
    currentSlipId = slipId;
    document.getElementById('markPaidModal').classList.remove('hidden');
}

function closeMarkPaidModal() {
    document.getElementById('markPaidModal').classList.add('hidden');
    document.getElementById('markPaidForm').reset();
    currentSlipId = null;
}

// Mark as Paid Form Submission
document.getElementById('markPaidForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentSlipId) return;
    
    const formData = new FormData(this);
    
    fetch(`/admin/payment-slips/${currentSlipId}/mark-paid`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        closeMarkPaidModal();
        
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Payment Recorded!',
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
        closeMarkPaidModal();
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An error occurred while recording the payment',
            confirmButtonColor: '#EF4444'
        });
    });
});

// Mark Expired Slips
function markExpiredSlips() {
    Swal.fire({
        title: 'Mark Expired Payment Slips?',
        text: 'This will mark all overdue unpaid payment slips as expired. This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, mark as expired',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/admin/payment-slips/mark-expired', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Completed!',
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while marking expired slips',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
}

// Close modals on outside click
document.getElementById('markPaidModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkPaidModal();
    }
});
</script>
@endpush
