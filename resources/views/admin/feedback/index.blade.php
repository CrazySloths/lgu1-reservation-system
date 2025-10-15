@extends('layouts.app')

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
                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-1 text-white">Citizen Feedback & Support</h1>
                        <p class="text-gray-200 text-lg">View and respond to citizen questions and feedback</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">In Progress</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Resolved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Closed</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['closed'] }}</p>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('admin.feedback.index', ['status' => 'all']) }}" 
                   class="px-6 py-3 text-sm font-medium {{ $status === 'all' ? 'border-b-2 border-lgu-highlight text-lgu-highlight' : 'text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    All ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.feedback.index', ['status' => 'pending']) }}" 
                   class="px-6 py-3 text-sm font-medium {{ $status === 'pending' ? 'border-b-2 border-lgu-highlight text-lgu-highlight' : 'text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    Pending ({{ $stats['pending'] }})
                </a>
                <a href="{{ route('admin.feedback.index', ['status' => 'in_progress']) }}" 
                   class="px-6 py-3 text-sm font-medium {{ $status === 'in_progress' ? 'border-b-2 border-lgu-highlight text-lgu-highlight' : 'text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    In Progress ({{ $stats['in_progress'] }})
                </a>
                <a href="{{ route('admin.feedback.index', ['status' => 'resolved']) }}" 
                   class="px-6 py-3 text-sm font-medium {{ $status === 'resolved' ? 'border-b-2 border-lgu-highlight text-lgu-highlight' : 'text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    Resolved ({{ $stats['resolved'] }})
                </a>
                <a href="{{ route('admin.feedback.index', ['status' => 'closed']) }}" 
                   class="px-6 py-3 text-sm font-medium {{ $status === 'closed' ? 'border-b-2 border-lgu-highlight text-lgu-highlight' : 'text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    Closed ({{ $stats['closed'] }})
                </a>
            </nav>
        </div>

        <!-- Feedback List -->
        <div class="divide-y divide-gray-200">
            @forelse($feedback as $item)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                                @if($item->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                @elseif($item->status === 'in_progress')
                                    <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">In Progress</span>
                                @elseif($item->status === 'resolved')
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Resolved</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Closed</span>
                                @endif
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ ucfirst(str_replace('_', ' ', $item->category)) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $item->email }}</p>
                            <p class="text-gray-700 mb-3">{{ Str::limit($item->question, 200) }}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>Submitted {{ $item->created_at->diffForHumans() }}</span>
                                @if($item->responded_at)
                                    <span>• Responded {{ $item->responded_at->diffForHumans() }} by {{ $item->respondedBy->name ?? 'Admin' }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('admin.feedback.show', $item->id) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                View & Respond
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-lg font-semibold">No feedback found</p>
                    <p class="text-sm">There are no citizen feedback submissions in this category.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($feedback->hasPages())
            <div class="p-6 border-t border-gray-200">
                {{ $feedback->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

