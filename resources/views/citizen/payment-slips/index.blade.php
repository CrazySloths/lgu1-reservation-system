@extends('citizen.layouts.app-sidebar')

@section('title', 'Payment Slips')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Payment Slips</h1>
    <p class="text-gray-600">View and download your payment slips for approved reservations</p>
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
                                    <div class="text-sm text-gray-500">Generated {{ $slip->created_at->format('M j, Y') }}</div>
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
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Paid
                                    </span>
                                @elseif($slip->status === 'expired')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Expired
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('citizen.payment-slips.show', $slip->id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('citizen.payment-slips.download', $slip->id) }}" 
                                       class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Instructions -->
    @if($paymentSlips->where('status', 'unpaid')->count() > 0)
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-900">Payment Instructions</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Print your payment slip or save it to your mobile device</li>
                            <li>Visit the LGU1 Cashier's Office during business hours (8:00 AM - 5:00 PM, Monday-Friday)</li>
                            <li>Present your payment slip and valid ID</li>
                            <li>Pay the exact amount in cash or check</li>
                            <li>Keep your official receipt for your records</li>
                        </ul>
                    </div>
                    <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                        <p class="text-sm font-medium text-blue-900">⚠️ Important:</p>
                        <p class="text-sm text-blue-800">Payment must be made before the due date. Expired payment slips cannot be processed and may require reapplication.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    <div class="bg-white shadow rounded-lg p-12 text-center">
        <div class="max-w-md mx-auto">
            <i class="fas fa-receipt text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Payment Slips Yet</h3>
            <p class="text-gray-500 mb-6">
                You'll see payment slips here once your reservation requests are approved by the admin.
            </p>
            <a href="{{ route('citizen.reservations') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Make a Reservation
            </a>
        </div>
    </div>
@endif
@endsection
