@extends('layouts.citizen')

@section('title', 'Payment Slip Details')
@section('page-title', 'Payment Slip')
@section('page-subtitle', $paymentSlip->slip_number)

@section('page-content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('citizen.payment-slips') }}" 
           class="inline-flex items-center text-lgu-button hover:text-lgu-highlight font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
            </svg>
            Back to Payment Slips
        </a>
    </div>

    <!-- Status Alert -->
    @php
        // Use match for fixed Tailwind classes
        $statusInfo = match($paymentSlip->status) {
            'unpaid' => [
                'bg' => 'bg-orange-50', 'border' => 'border-orange-500', 'text' => 'text-orange-800', 'icon' => 'text-orange-500',
                'label' => 'Awaiting Payment',
                'message' => 'Please settle this payment before the due date to confirm your booking.'
            ],
            'paid' => [
                'bg' => 'bg-green-50', 'border' => 'border-green-500', 'text' => 'text-green-800', 'icon' => 'text-green-500',
                'label' => 'Payment Confirmed',
                'message' => 'Your payment has been confirmed. Thank you for settling this promptly!'
            ],
            'expired' => [
                'bg' => 'bg-red-50', 'border' => 'border-red-500', 'text' => 'text-red-800', 'icon' => 'text-red-500',
                'label' => 'Payment Expired',
                'message' => 'This payment slip has expired. Please contact support if you need assistance.'
            ],
            default => [
                'bg' => 'bg-gray-50', 'border' => 'border-gray-500', 'text' => 'text-gray-800', 'icon' => 'text-gray-500',
                'label' => ucfirst($paymentSlip->status),
                'message' => ''
            ]
        };
        $dueDate = \Carbon\Carbon::parse($paymentSlip->due_date);
        $isOverdue = $paymentSlip->status === 'unpaid' && $dueDate->isPast();
        $daysUntilDue = $isOverdue ? abs($dueDate->diffInDays(now(), false)) : $dueDate->diffInDays(now(), false);
    @endphp

    <div class="{{ $statusInfo['bg'] }} border-l-8 {{ $statusInfo['border'] }} p-6 rounded-xl shadow-lg {{ $isOverdue ? 'animate-pulse' : '' }}">
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
                @if($paymentSlip->status === 'paid')
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $statusInfo['icon'] }}">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>
                    </svg>
                @elseif($paymentSlip->status === 'expired')
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $statusInfo['icon'] }}">
                        <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $statusInfo['icon'] }}">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                @endif
            </div>
            <div class="ml-4 flex-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3 class="text-2xl font-bold {{ $statusInfo['text'] }}">{{ $statusInfo['label'] }}</h3>
                    @if($isOverdue)
                        <span class="px-4 py-1.5 bg-red-600 text-white text-sm font-bold rounded-full shadow-lg">
                            ⚠ {{ $daysUntilDue }} {{ $daysUntilDue == 1 ? 'DAY' : 'DAYS' }} OVERDUE
                        </span>
                    @elseif($paymentSlip->status === 'unpaid' && $daysUntilDue <= 3)
                        <span class="px-4 py-1.5 bg-yellow-500 text-white text-sm font-bold rounded-full shadow-lg">
                            ⏰ {{ $daysUntilDue }} {{ $daysUntilDue == 1 ? 'DAY' : 'DAYS' }} LEFT
                        </span>
                    @endif
                </div>
                <p class="text-base {{ $statusInfo['text'] }} mt-2 leading-relaxed">
                    {{ $statusInfo['message'] }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Slip Information -->
            <div class="bg-white shadow-lg rounded-xl p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-6 border-b-2 border-gray-200 gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-1">Payment Slip Details</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Slip #</span>
                            <span class="font-mono bg-gray-100 px-3 py-1 rounded-lg text-sm font-bold text-gray-900">{{ $paymentSlip->slip_number }}</span>
                        </div>
                    </div>
                    <button onclick="window.print()" 
                            class="px-5 py-2.5 bg-gray-100 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold shadow-sm hover:shadow-md cursor-pointer flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                        </svg>
                        Print Slip
                    </button>
                </div>

                <!-- Facility & Booking Info -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Facility</h3>
                        <p class="text-lg font-bold text-gray-900">{{ $paymentSlip->facility_name }}</p>
                        <p class="text-sm text-gray-600">{{ $paymentSlip->facility_address }}</p>
                        @if($paymentSlip->city_code)
                            <span class="inline-block mt-1 px-2 py-1 bg-lgu-bg text-lgu-headline text-xs font-semibold rounded">
                                {{ $paymentSlip->city_code }}
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Booking Date</h3>
                            <p class="text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($paymentSlip->start_time)->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Time</h3>
                            <p class="text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($paymentSlip->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($paymentSlip->end_time)->format('g:i A') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Purpose</h3>
                            <p class="text-base font-semibold text-gray-900">{{ $paymentSlip->purpose }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Attendees</h3>
                            <p class="text-base font-semibold text-gray-900">{{ $paymentSlip->expected_attendees }} people</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Breakdown -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Breakdown</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Base Rate (3 hours):</span>
                        <span class="font-semibold">₱{{ number_format($paymentSlip->base_rate, 2) }}</span>
                    </div>
                    @if($paymentSlip->extension_rate > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Extension Charges:</span>
                            <span class="font-semibold">₱{{ number_format($paymentSlip->extension_rate, 2) }}</span>
                        </div>
                    @endif
                    @if($paymentSlip->equipment_total > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Equipment:</span>
                            <span class="font-semibold">₱{{ number_format($paymentSlip->equipment_total, 2) }}</span>
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">₱{{ number_format($paymentSlip->subtotal, 2) }}</span>
                        </div>
                    </div>
                    @if($paymentSlip->resident_discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Resident Discount:</span>
                            <span>- ₱{{ number_format($paymentSlip->resident_discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if($paymentSlip->special_discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Special Discount:</span>
                            <span>- ₱{{ number_format($paymentSlip->special_discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if($paymentSlip->total_discount > 0)
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-sm text-green-600 font-bold">
                                <span>Total Discount:</span>
                                <span>- ₱{{ number_format($paymentSlip->total_discount, 2) }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="border-t-2 border-gray-300 pt-3">
                        <div class="flex justify-between text-lg font-bold text-lgu-headline">
                            <span>Total Amount Due:</span>
                            <span>₱{{ number_format($paymentSlip->amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selected Equipment -->
            @if($equipment->isNotEmpty())
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Selected Equipment</h3>
                    <div class="space-y-3">
                        @foreach($equipment as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->equipment_name }}</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }} × ₱{{ number_format($item->price_per_unit, 2) }}</p>
                                </div>
                                <p class="text-lg font-bold text-lgu-headline">₱{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Payment Proof Upload (for unpaid slips) -->
            @if($paymentSlip->status === 'unpaid')
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 rounded-xl p-8 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-blue-900">Upload Payment Proof</h3>
                            <p class="text-sm text-blue-700">Submit your receipt for verification</p>
                        </div>
                    </div>
                    <div class="bg-white border-2 border-blue-200 rounded-lg p-5 mb-5">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 flex-shrink-0 mt-0.5">
                                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                            </svg>
                            <p class="text-sm text-blue-900 leading-relaxed">
                                After making your payment, please upload your payment receipt or proof of payment here. Our staff will verify and confirm your payment within 24-48 hours.
                            </p>
                        </div>
                    </div>
                    
                    <form id="paymentProofForm" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label for="payment_method" class="block text-sm font-bold text-gray-900 mb-2">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_method" id="payment_method" required
                                    class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-lgu-button transition-all">
                                <option value="">-- Select Payment Method --</option>
                                <option value="cash">💵 Cash</option>
                                <option value="gcash">📱 GCash</option>
                                <option value="paymaya">💳 PayMaya</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                                <option value="check">📝 Check</option>
                            </select>
                        </div>

                        <div>
                            <label for="reference_number" class="block text-sm font-bold text-gray-900 mb-2">
                                Reference Number <span class="text-gray-500 font-normal">(Optional)</span>
                            </label>
                            <input type="text" name="reference_number" id="reference_number"
                                   class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-lgu-button transition-all"
                                   placeholder="Enter transaction ID or reference number">
                        </div>

                        <div>
                            <label for="payment_proof" class="block text-sm font-bold text-gray-900 mb-2">
                                Payment Receipt/Proof <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="payment_proof" id="payment_proof" accept="image/*,.pdf" required
                                   class="block w-full text-sm text-gray-900 border-2 border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none file:mr-4 file:py-3 file:px-4 file:rounded-l-lg file:border-0 file:bg-lgu-button file:text-lgu-button-text file:font-semibold hover:file:bg-lgu-highlight transition-all">
                            <div class="mt-2 flex items-center gap-2 text-xs text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                                </svg>
                                <span>Accepted: JPG, PNG, PDF • Max size: 5MB</span>
                            </div>
                        </div>

                        <button type="submit" id="uploadBtn"
                                class="w-full px-8 py-4 bg-lgu-button text-lgu-button-text font-bold text-lg rounded-lg hover:bg-lgu-highlight transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 cursor-pointer flex items-center justify-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                            Upload Payment Proof
                        </button>
                    </form>
                </div>
            @endif

            <!-- Payment Receipt (if uploaded) -->
            @if($paymentSlip->payment_receipt_url)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Uploaded Payment Proof</h3>
                    <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 mr-3">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/>
                            </svg>
                            <div>
                                <p class="font-medium text-green-900">Payment Receipt</p>
                                <p class="text-sm text-green-700">{{ $paymentSlip->payment_method ? ucfirst(str_replace('_', ' ', $paymentSlip->payment_method)) : 'Uploaded' }}</p>
                                @if($paymentSlip->gateway_reference_number)
                                    <p class="text-xs text-green-600">Ref: {{ $paymentSlip->gateway_reference_number }}</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $paymentSlip->payment_receipt_url) }}" target="_blank"
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                            View
                        </a>
                    </div>
                    @if($paymentSlip->status === 'unpaid')
                        <p class="mt-3 text-sm text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-1">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            Your payment proof is being verified by our staff.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar: Important Dates & Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 sticky top-8 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Important Dates</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button mr-2 mt-0.5">
                                <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Payment Due</p>
                                <p class="text-gray-600">{{ $dueDate->format('F d, Y') }}</p>
                                @if($paymentSlip->status === 'unpaid')
                                    @if($isOverdue)
                                        <p class="text-red-600 font-bold text-xs">{{ abs($dueDate->diffInDays(Carbon\Carbon::now())) }} days overdue</p>
                                    @else
                                        <p class="text-blue-600 text-xs">{{ $dueDate->diffInDays(Carbon\Carbon::now()) }} days remaining</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if($paymentSlip->paid_at)
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 mr-2 mt-0.5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Paid On</p>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($paymentSlip->paid_at)->format('F d, Y') }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button mr-2 mt-0.5">
                                <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Event Date</p>
                                <p class="text-gray-600">{{ \Carbon\Carbon::parse($paymentSlip->start_time)->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($paymentSlip->or_number)
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-bold text-gray-900 mb-2">Official Receipt</h4>
                        <p class="text-2xl font-bold text-lgu-headline">{{ $paymentSlip->or_number }}</p>
                        @if($paymentSlip->treasurer_cashier_name)
                            <p class="text-xs text-gray-600 mt-1">Issued by: {{ $paymentSlip->treasurer_cashier_name }}</p>
                        @endif
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-bold text-gray-900 mb-3">Quick Actions</h4>
                    <div class="space-y-3">
                        <a href="{{ route('citizen.reservations.show', $paymentSlip->booking_id) }}" 
                           class="block w-full px-4 py-3 bg-lgu-button text-lgu-button-text text-center font-semibold rounded-lg hover:bg-lgu-highlight transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                            </svg>
                            View Booking
                        </a>
                        <a href="{{ route('citizen.payment-slips') }}" 
                           class="block w-full px-4 py-3 bg-gray-200 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-300 transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                            </svg>
                            Back to List
                        </a>
                        <button onclick="window.print()" 
                                class="block w-full px-4 py-3 bg-gray-100 border-2 border-gray-300 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                            </svg>
                            Print Slip
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('paymentProofForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const paymentMethod = document.getElementById('payment_method').value;
    const paymentProof = document.getElementById('payment_proof').files[0];
    
    // Client-side validation
    if (!paymentMethod) {
        Swal.fire({
            icon: 'warning',
            title: 'Payment Method Required',
            text: 'Please select a payment method before uploading.',
            confirmButtonColor: '#0f5b3a'
        });
        return;
    }
    
    if (!paymentProof) {
        Swal.fire({
            icon: 'warning',
            title: 'Payment Proof Required',
            text: 'Please select a file to upload.',
            confirmButtonColor: '#0f5b3a'
        });
        return;
    }
    
    // Check file size (5MB max)
    if (paymentProof.size > 5242880) {
        Swal.fire({
            icon: 'error',
            title: 'File Too Large',
            text: 'File size must not exceed 5MB.',
            confirmButtonColor: '#0f5b3a'
        });
        return;
    }
    
    const uploadBtn = document.getElementById('uploadBtn');
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Uploading...';
    
    const formData = new FormData(this);
    
    fetch('/citizen/payments/{{ $paymentSlip->id }}/upload-proof', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Upload Successful!',
                text: data.message,
                confirmButtonColor: '#0f5b3a',
                confirmButtonText: 'Okay'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: data.message || 'An error occurred while uploading.',
                confirmButtonColor: '#0f5b3a'
            });
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg> Upload Payment Proof';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'An unexpected error occurred. Please check your connection and try again.',
            confirmButtonColor: '#0f5b3a'
        });
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg> Upload Payment Proof';
    });
});
</script>
@endpush
@endsection

