@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Facility List</h2>
                <p class="text-gray-200">Manage all LGU facilities here</p>
            </div>
            <div>
                <a href="#"
                   class="px-4 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg shadow hover:bg-yellow-400 transition">
                   + Add Facility
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Facility Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($facilities as $facility)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $facility->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $facility->location }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $facility->capacity }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs font-semibold rounded-full 
                            {{ $facility->status == 'Available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $facility->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
