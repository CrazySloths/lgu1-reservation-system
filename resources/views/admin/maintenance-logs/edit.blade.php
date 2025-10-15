@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
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
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Edit Maintenance Log #{{ $maintenanceLog->id }}</h1>
                    <p class="text-gray-200">Update maintenance record details</p>
                </div>
            </div>
            <a href="{{ route('admin.maintenance-logs.show', $maintenanceLog->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-medium rounded-lg hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.maintenance-logs.update', $maintenanceLog->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Facility & Type -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Facility <span class="text-red-500">*</span>
                </label>
                <select id="facility_id" name="facility_id" required
                    class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->facility_id }}" 
                            {{ old('facility_id', $maintenanceLog->facility_id) == $facility->facility_id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="maintenance_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Maintenance Type <span class="text-red-500">*</span>
                </label>
                <select id="maintenance_type" name="maintenance_type" required
                    class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                    <option value="repair" {{ old('maintenance_type', $maintenanceLog->maintenance_type) == 'repair' ? 'selected' : '' }}>Repair</option>
                    <option value="cleaning" {{ old('maintenance_type', $maintenanceLog->maintenance_type) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                    <option value="inspection" {{ old('maintenance_type', $maintenanceLog->maintenance_type) == 'inspection' ? 'selected' : '' }}>Inspection</option>
                    <option value="preventive" {{ old('maintenance_type', $maintenanceLog->maintenance_type) == 'preventive' ? 'selected' : '' }}>Preventive Maintenance</option>
                    <option value="emergency" {{ old('maintenance_type', $maintenanceLog->maintenance_type) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="other" {{ old('maintenance_type', $maintenanceLog->maintenance_type) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Title <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title', $maintenanceLog->title) }}" required
                class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description <span class="text-red-500">*</span>
            </label>
            <textarea id="description" name="description" rows="4" required
                class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">{{ old('description', $maintenanceLog->description) }}</textarea>
        </div>

        <!-- Status, Priority & Dates -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status" required
                    class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                    <option value="pending" {{ old('status', $maintenanceLog->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status', $maintenanceLog->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status', $maintenanceLog->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $maintenanceLog->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                    Priority <span class="text-red-500">*</span>
                </label>
                <select id="priority" name="priority" required
                    class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                    <option value="low" {{ old('priority', $maintenanceLog->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $maintenanceLog->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $maintenanceLog->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority', $maintenanceLog->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Scheduled Date
                </label>
                <input type="date" id="scheduled_date" name="scheduled_date" 
                    value="{{ old('scheduled_date', $maintenanceLog->scheduled_date ? $maintenanceLog->scheduled_date->format('Y-m-d') : '') }}"
                    class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
            </div>
        </div>

        <!-- Assignment Details -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assignment Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">
                        Assigned To
                    </label>
                    <input type="text" id="assigned_to" name="assigned_to" value="{{ old('assigned_to', $maintenanceLog->assigned_to) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                </div>

                <div>
                    <label for="assigned_contact" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Number/Email
                    </label>
                    <input type="text" id="assigned_contact" name="assigned_contact" value="{{ old('assigned_contact', $maintenanceLog->assigned_contact) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                </div>
            </div>
        </div>

        <!-- Cost Information -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cost Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">
                        Estimated Cost (₱)
                    </label>
                    <input type="number" id="estimated_cost" name="estimated_cost" 
                        value="{{ old('estimated_cost', $maintenanceLog->estimated_cost) }}" step="0.01" min="0"
                        class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                </div>

                <div>
                    <label for="actual_cost" class="block text-sm font-medium text-gray-700 mb-2">
                        Actual Cost (₱)
                    </label>
                    <input type="number" id="actual_cost" name="actual_cost" 
                        value="{{ old('actual_cost', $maintenanceLog->actual_cost) }}" step="0.01" min="0"
                        class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                </div>

                <div>
                    <label for="completed_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Completed Date
                    </label>
                    <input type="date" id="completed_date" name="completed_date" 
                        value="{{ old('completed_date', $maintenanceLog->completed_date ? $maintenanceLog->completed_date->format('Y-m-d') : '') }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="border-t pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">{{ old('notes', $maintenanceLog->notes) }}</textarea>
                </div>

                <div>
                    <label for="completion_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Completion Notes
                    </label>
                    <textarea id="completion_notes" name="completion_notes" rows="3"
                        class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">{{ old('completion_notes', $maintenanceLog->completion_notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('admin.maintenance-logs.show', $maintenanceLog->id) }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                class="px-6 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                Update Maintenance Log
            </button>
        </div>
    </form>
</div>
@endsection

