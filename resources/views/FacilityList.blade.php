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
                <button id="addFacilityBtn"
                    class="px-4 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg shadow hover:bg-yellow-400 transition">
                    + Add Facility
                </button>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate per Hour</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @if ($facilities->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                        No facilities found.
                    </td>
                </tr>
            @else
                @foreach($facilities as $facility)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $facility->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $facility->address }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $facility->capacity }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">₱{{ number_format($facility->rate_per_hour, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <button class="edit-btn px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
                                data-id="{{ $facility->facility_id }}"
                                data-name="{{ $facility->name }}"
                                data-description="{{ $facility->description }}"
                                data-address="{{ $facility->address }}"
                                data-capacity="{{ $facility->capacity }}"
                                data-rate="{{ $facility->rate_per_hour }}">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<div id="addFacilityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden transition-all ease-in-out duration-300">
    <div id="modalAddContent" class="relative top-20 mx-auto p-8 border max-w-xl shadow-lg rounded-md bg-white transition-all ease-in-out duration-300 transform scale-95 opacity-0">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Add New Facility</h3>
        
        <form action="{{ route('facilities.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50"></textarea>
                </div>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                    <input type="number" name="capacity" id="capacity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="rate_per_hour" class="block text-sm font-medium text-gray-700">Rate per Hour</label>
                    <input type="number" step="0.01" name="rate_per_hour" id="rate_per_hour" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" id="closeAddModalBtn" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg shadow hover:bg-yellow-400 transition">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="editFacilityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden transition-all ease-in-out duration-300">
    <div id="modalEditContent" class="relative top-20 mx-auto p-8 border max-w-xl shadow-lg rounded-md bg-white transition-all ease-in-out duration-300 transform scale-95 opacity-0">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Edit Facility</h3>
        
        <form id="editForm" action="#" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="edit_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="edit_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50"></textarea>
                </div>
                
                <div>
                    <label for="edit_address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="edit_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="edit_capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                    <input type="number" name="capacity" id="edit_capacity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="edit_rate_per_hour" class="block text-sm font-medium text-gray-700">Rate per Hour</label>
                    <input type="number" step="0.01" name="rate_per_hour" id="edit_rate_per_hour" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" id="closeEditModalBtn" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg shadow hover:bg-yellow-400 transition">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    const addFacilityBtn = document.getElementById('addFacilityBtn');
    const addFacilityModal = document.getElementById('addFacilityModal');
    const closeAddModalBtn = document.getElementById('closeAddModalBtn');
    const modalAddContent = document.getElementById('modalAddContent');
    
    const editButtons = document.querySelectorAll('.edit-btn');
    const editFacilityModal = document.getElementById('editFacilityModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const modalEditContent = document.getElementById('modalEditContent');
    const editForm = document.getElementById('editForm');

    // Add Modal Functions
    addFacilityBtn.addEventListener('click', () => {
        addFacilityModal.classList.remove('hidden');
        setTimeout(() => {
            addFacilityModal.classList.add('bg-opacity-50');
            modalAddContent.classList.remove('opacity-0', 'scale-95');
        }, 10);
    });

    closeAddModalBtn.addEventListener('click', () => {
        modalAddContent.classList.add('opacity-0', 'scale-95');
        addFacilityModal.classList.remove('bg-opacity-50');
        setTimeout(() => {
            addFacilityModal.classList.add('hidden');
        }, 300);
    });

    // Edit Modal Functions
    editButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const id = event.target.dataset.id;
            const name = event.target.dataset.name;
            const description = event.target.dataset.description;
            const address = event.target.dataset.address;
            const capacity = event.target.dataset.capacity;
            const rate = event.target.dataset.rate;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_capacity').value = capacity;
            document.getElementById('edit_rate_per_hour').value = rate;

            editForm.action = `/facilities/${id}`;

            editFacilityModal.classList.remove('hidden');
            setTimeout(() => {
                editFacilityModal.classList.add('bg-opacity-50');
                modalEditContent.classList.remove('opacity-0', 'scale-95');
            }, 10);
        });
    });

    closeEditModalBtn.addEventListener('click', () => {
        modalEditContent.classList.add('opacity-0', 'scale-95');
        editFacilityModal.classList.remove('bg-opacity-50');
        setTimeout(() => {
            editFacilityModal.classList.add('hidden');
        }, 300);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
@endif
@endsection