@extends('citizen.layouts.app-sidebar')

@section('title', 'Payment Slip Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('citizen.payment-slips.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Payment Slips
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Payment Slip #{{ $paymentSlip->slip_number }}</h1>
            <div class="flex items-center mt-2 space-x-4">
                @if($paymentSlip->status === 'paid')
                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Paid
                    </span>
                @elseif($paymentSlip->status === 'expired')
                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-1"></i> Expired
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i> Awaiting Payment
                    </span>
                @endif
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('citizen.payment-slips.download', $paymentSlip->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-download mr-2"></i>
                Download PDF
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
            <button onclick="saveAsImage()" 
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                <i class="fas fa-camera mr-2"></i>
                Save as Image
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Slip Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Payment Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-receipt text-blue-500 mr-2"></i>
                Payment Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Payment Slip Number</label>
                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $paymentSlip->slip_number }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount Due</label>
                    <p class="mt-1 text-lg font-bold text-green-600">₱{{ number_format($paymentSlip->amount, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Generated Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->created_at->format('F j, Y g:i A') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->due_date->format('F j, Y') }}</p>
                    @if($paymentSlip->status === 'unpaid')
                        @if($paymentSlip->days_until_due > 0)
                            <p class="text-sm text-yellow-600 font-medium">{{ $paymentSlip->days_until_due }} days remaining</p>
                        @else
                            <p class="text-sm text-red-600 font-medium">Overdue</p>
                        @endif
                    @endif
                </div>
                @if($paymentSlip->paid_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Paid Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->paid_at->format('F j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($paymentSlip->payment_method) }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Event & Reservation Details -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-calendar-check text-blue-500 mr-2"></i>
                Reservation Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->booking->event_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Facility</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->booking->facility->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->booking->event_date->format('F j, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Time</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->booking->start_time }} - {{ $paymentSlip->booking->end_time }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Expected Attendees</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->booking->expected_attendees }} people</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Reservation Status</label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Approved
                    </span>
                </div>
            </div>
            @if($paymentSlip->booking->event_description)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Event Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $paymentSlip->booking->event_description }}</p>
                </div>
            @endif
        </div>

        @if($paymentSlip->cashier_notes)
            <!-- Cashier Notes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-2 flex items-center">
                    <i class="fas fa-sticky-note text-blue-600 mr-2"></i>
                    Cashier Notes
                </h3>
                <p class="text-sm text-blue-800">{{ $paymentSlip->cashier_notes }}</p>
            </div>
        @endif
    </div>

    <!-- Payment Status & Instructions -->
    <div class="space-y-6">
        <!-- Current Status -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Status</h3>
            <div class="space-y-3">
                @if($paymentSlip->status === 'paid')
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-check-circle text-xl mr-3"></i>
                        <div>
                            <p class="font-medium">Payment Complete</p>
                            <p class="text-sm text-gray-600">Paid on {{ $paymentSlip->paid_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                @elseif($paymentSlip->status === 'expired')
                    <div class="flex items-center text-red-600">
                        <i class="fas fa-times-circle text-xl mr-3"></i>
                        <div>
                            <p class="font-medium">Payment Expired</p>
                            <p class="text-sm text-gray-600">Due date was {{ $paymentSlip->due_date->format('F j, Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center text-yellow-600">
                        <i class="fas fa-clock text-xl mr-3"></i>
                        <div>
                            <p class="font-medium">Payment Pending</p>
                            @if($paymentSlip->days_until_due > 0)
                                <p class="text-sm text-gray-600">{{ $paymentSlip->days_until_due }} days until due</p>
                            @else
                                <p class="text-sm text-red-600">Overdue - please pay immediately</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($paymentSlip->status === 'unpaid')
            <!-- Payment Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    How to Pay
                </h3>
                <div class="space-y-3 text-sm text-blue-800">
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-bold mr-3">1</span>
                        <p>Download and print this payment slip</p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-bold mr-3">2</span>
                        <p>Visit the LGU1 Cashier's Office</p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-bold mr-3">3</span>
                        <p>Present payment slip and valid ID</p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-bold mr-3">4</span>
                        <p>Pay ₱{{ number_format($paymentSlip->amount, 2) }} in cash or check</p>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-blue-100 rounded">
                    <p class="text-xs text-blue-900 font-medium">Office Hours:</p>
                    <p class="text-xs text-blue-800">Monday - Friday: 8:00 AM - 5:00 PM</p>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('citizen.payment-slips.download', $paymentSlip->id) }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>
                    Download PDF
                </a>
                <button onclick="window.print()" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-print mr-2"></i>
                    Print This Page
                </button>
                <button onclick="saveAsImage()" 
                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-camera mr-2"></i>
                    Save as Image
                </button>
                <a href="{{ route('citizen.reservation.history') }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-history mr-2"></i>
                    View All Reservations
                </a>
            </div>
        </div>
    </div>
</div>

<!-- HTML to Canvas Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
function saveAsImage() {
    // Show loading
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Capturing...';
    event.target.disabled = true;
    
    // Create a specific container with the payment slip content
    const paymentSlipElement = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-3.gap-6');
    
    const options = {
        backgroundColor: '#ffffff',
        scale: 2, // Higher resolution
        useCORS: true,
        allowTaint: false,
        width: 1200,
        height: 800,
        scrollX: 0,
        scrollY: 0
    };
    
    html2canvas(paymentSlipElement, options).then(function(canvas) {
        // Create download link
        const link = document.createElement('a');
        link.download = 'payment-slip-{{ $paymentSlip->slip_number }}.png';
        link.href = canvas.toDataURL('image/png');
        
        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reset button
        event.target.innerHTML = originalText;
        event.target.disabled = false;
        
        // Show success message
        alert('Payment slip saved as image successfully!');
    }).catch(function(error) {
        console.error('Error saving image:', error);
        // Reset button
        event.target.innerHTML = originalText;
        event.target.disabled = false;
        alert('Sorry, there was an error saving the image. Please try printing instead.');
    });
}

// Make the payment slip look good for screenshots
document.addEventListener('DOMContentLoaded', function() {
    // Add some styling for better image capture
    const style = document.createElement('style');
    style.textContent = `
        @media screen {
            .payment-slip-container {
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            body * {
                visibility: hidden;
            }
            .printable, .printable * {
                visibility: visible;
            }
            .printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Add printable class to the main content
    document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-3.gap-6').classList.add('printable');
});
</script>

@endsection
