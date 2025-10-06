@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Document Verification</h1>
                <p class="text-gray-200">Review and verify booking requirements</p>
            </div>
            <div class="text-right">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                    <p class="text-2xl font-bold text-lgu-highlight">{{ $bookings->count() }}</p>
                    <p class="text-sm text-gray-200">Total Pending</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="all">All Pending</option>
                        <option value="urgent">Urgent</option>
                        <option value="normal">Normal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort by</label>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="facility">By Facility</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" placeholder="Search bookings..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent w-64">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button class="px-4 py-2 bg-lgu-highlight text-lgu-button-text rounded-lg hover:bg-lgu-button transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Verification Queue -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Verification Queue</h3>
        </div>
        
        @if($bookings->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($bookings as $booking)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $booking->event_name }}</h4>
                                            @if($booking->created_at->diffInHours() < 24)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <p class="font-medium text-gray-900">Organizer</p>
                                                <p>{{ $booking->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">Facility</p>
                                                <p>{{ $booking->facility->name ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->event_date)->format('M j, Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">Submitted</p>
                                                <p>{{ $booking->created_at->format('M j, Y g:i A') }}</p>
                                                <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex items-center space-x-4">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                Pending Verification
                                            </span>
                                            @if($booking->priority === 'high')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                    High Priority
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex-shrink-0 ml-6">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('staff.verification.show', $booking->id) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-lgu-highlight text-lgu-button-text text-sm font-medium rounded-lg hover:bg-lgu-button transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Review & Verify
                                            </a>
                                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg" title="View Details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($bookings->count() > 15)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                    <p class="text-sm text-gray-600 text-center">Showing {{ $bookings->count() }} bookings</p>
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Verifications</h3>
                <p class="text-gray-600">All bookings have been verified and processed!</p>
                <p class="text-sm text-gray-500 mt-2">Check back later for new submissions.</p>
            </div>
        @endif
    </div>
</div>
@endsection
