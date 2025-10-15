@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Enhanced Header Section -->
    <div class="bg-lgu-headline rounded-2xl p-6 text-white shadow-lgu-lg overflow-hidden relative">
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
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-lgu-highlight/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-6 h-6 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 text-white">Feedback Details</h1>
                    <p class="text-gray-200">Citizen question and support inquiry</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.feedback.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-medium rounded-lg hover:bg-white/20 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
                <form method="POST" action="{{ route('admin.feedback.destroy', $feedback->id) }}" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Delete
                    </button>
                </form>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Citizen Question -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Citizen Question</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <p class="text-gray-900">{{ $feedback->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-gray-900">{{ $feedback->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <span class="px-3 py-1 text-sm bg-gray-100 text-gray-800 rounded-full">
                            {{ ucfirst(str_replace('_', ' ', $feedback->category)) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Question/Concern</label>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $feedback->question }}</p>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-500">
                        Submitted {{ $feedback->created_at->format('F j, Y \a\t g:i A') }} ({{ $feedback->created_at->diffForHumans() }})
                    </div>
                </div>
            </div>

            <!-- Admin Response Section -->
            @if($feedback->admin_response)
                <div class="bg-green-50 rounded-lg shadow p-6 border border-green-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Your Response</h2>
                    
                    <div class="p-4 bg-white rounded-lg border border-green-200">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $feedback->admin_response }}</p>
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-600">
                        Responded by <strong>{{ $feedback->respondedBy->name ?? 'Admin' }}</strong> 
                        on {{ $feedback->responded_at->format('F j, Y \a\t g:i A') }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Send Response</h2>
                    
                    <form method="POST" action="{{ route('admin.feedback.respond', $feedback->id) }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-2">
                                Your Response *
                            </label>
                            <textarea name="admin_response" id="admin_response" rows="6" required
                                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Type your response here..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Update Status *
                            </label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="in_progress">In Progress</option>
                                <option value="resolved" selected>Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Send Response
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
                
                <form method="POST" action="{{ route('admin.feedback.update-status', $feedback->id) }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <select name="status" 
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $feedback->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $feedback->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $feedback->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $feedback->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Quick Info Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Info</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Status:</span>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full
                            @if($feedback->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($feedback->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($feedback->status === 'resolved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $feedback->status)) }}
                        </span>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">Category:</span>
                        <span class="ml-2 text-gray-900">{{ ucfirst(str_replace('_', ' ', $feedback->category)) }}</span>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">Submitted:</span>
                        <span class="ml-2 text-gray-900">{{ $feedback->created_at->diffForHumans() }}</span>
                    </div>
                    
                    @if($feedback->responded_at)
                        <div>
                            <span class="text-gray-600">Responded:</span>
                            <span class="ml-2 text-gray-900">{{ $feedback->responded_at->diffForHumans() }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-2">
                    <a href="mailto:{{ $feedback->email }}?subject=Re: Your Feedback - {{ $feedback->category }}" 
                       class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors">
                        Send Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

