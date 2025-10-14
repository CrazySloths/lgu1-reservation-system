@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Maintenance Log #{{ $maintenanceLog->id }}</h1>
            <p class="text-gray-600 mt-1">{{ $maintenanceLog->facility->name ?? 'N/A' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.maintenance-logs.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.maintenance-logs.edit', $maintenanceLog->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-lgu-highlight text-lgu-button-text font-medium rounded-lg hover:bg-lgu-button transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="inline-flex px-4 py-2 text-sm font-medium rounded-full
                    {{ $maintenanceLog->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $maintenanceLog->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $maintenanceLog->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $maintenanceLog->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $maintenanceLog->status)) }}
                </span>
                <span class="inline-flex px-4 py-2 text-sm font-bold rounded-full
                    {{ $maintenanceLog->priority === 'urgent' ? 'bg-red-600 text-white' : '' }}
                    {{ $maintenanceLog->priority === 'high' ? 'bg-orange-500 text-white' : '' }}
                    {{ $maintenanceLog->priority === 'medium' ? 'bg-yellow-500 text-white' : '' }}
                    {{ $maintenanceLog->priority === 'low' ? 'bg-green-500 text-white' : '' }}">
                    {{ ucfirst($maintenanceLog->priority) }} Priority
                </span>
                <span class="inline-flex px-4 py-2 text-sm font-medium rounded-full
                    {{ $maintenanceLog->maintenance_type === 'emergency' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $maintenanceLog->maintenance_type === 'repair' ? 'bg-orange-100 text-orange-800' : '' }}
                    {{ $maintenanceLog->maintenance_type === 'cleaning' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $maintenanceLog->maintenance_type === 'inspection' ? 'bg-purple-100 text-purple-800' : '' }}
                    {{ $maintenanceLog->maintenance_type === 'preventive' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $maintenanceLog->maintenance_type === 'other' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst($maintenanceLog->maintenance_type) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Details -->
    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $maintenanceLog->title }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Facility</p>
                <p class="text-lg font-semibold text-gray-900">{{ $maintenanceLog->facility->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Reported By</p>
                <p class="text-lg font-semibold text-gray-900">{{ $maintenanceLog->reported_by ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="border-t pt-6">
            <p class="text-sm text-gray-600 mb-2">Description</p>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $maintenanceLog->description }}</p>
        </div>

        @if($maintenanceLog->assigned_to)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Assigned To</p>
                <p class="text-lg font-semibold text-gray-900">{{ $maintenanceLog->assigned_to }}</p>
            </div>
            @if($maintenanceLog->assigned_contact)
            <div>
                <p class="text-sm text-gray-600 mb-1">Contact</p>
                <p class="text-lg font-semibold text-gray-900">{{ $maintenanceLog->assigned_contact }}</p>
            </div>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t pt-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Scheduled Date</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $maintenanceLog->scheduled_date ? $maintenanceLog->scheduled_date->format('M j, Y') : 'Not scheduled' }}
                </p>
            </div>
            @if($maintenanceLog->completed_date)
            <div>
                <p class="text-sm text-gray-600 mb-1">Completed Date</p>
                <p class="text-lg font-semibold text-green-600">{{ $maintenanceLog->completed_date->format('M j, Y') }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-600 mb-1">Created</p>
                <p class="text-lg font-semibold text-gray-900">{{ $maintenanceLog->created_at->format('M j, Y') }}</p>
            </div>
        </div>

        @if($maintenanceLog->estimated_cost || $maintenanceLog->actual_cost)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
            @if($maintenanceLog->estimated_cost)
            <div>
                <p class="text-sm text-gray-600 mb-1">Estimated Cost</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($maintenanceLog->estimated_cost, 2) }}</p>
            </div>
            @endif
            @if($maintenanceLog->actual_cost)
            <div>
                <p class="text-sm text-gray-600 mb-1">Actual Cost</p>
                <p class="text-2xl font-bold text-green-600">₱{{ number_format($maintenanceLog->actual_cost, 2) }}</p>
            </div>
            @endif
        </div>
        @endif

        @if($maintenanceLog->notes)
        <div class="border-t pt-6">
            <p class="text-sm text-gray-600 mb-2">Notes</p>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $maintenanceLog->notes }}</p>
        </div>
        @endif

        @if($maintenanceLog->completion_notes)
        <div class="border-t pt-6">
            <p class="text-sm text-gray-600 mb-2">Completion Notes</p>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $maintenanceLog->completion_notes }}</p>
        </div>
        @endif
    </div>

    <!-- Delete Button -->
    <div class="flex justify-end">
        <form action="{{ route('admin.maintenance-logs.destroy', $maintenanceLog->id) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this maintenance log?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                Delete Log
            </button>
        </form>
    </div>
</div>
@endsection

