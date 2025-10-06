@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-gradient-to-r rounded-lg p-6 text-white" style="background: linear-gradient(to right, #00473e, #00332c);">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Facility List</h2>
                <p class="text-gray-200">Manage all LGU facilities here</p>
            </div>
            <div>
                <button id="addFacilityBtn"
                    class="px-4 py-2 font-semibold rounded-lg shadow hover:bg-yellow-400 transition" style="background-color: #faae2b; color: #00473e;">
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility Rate</th>
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
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $facility->location }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $facility->capacity }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">₱{{ number_format($facility->daily_rate, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <button class="edit-btn px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
                                data-id="{{ $facility->facility_id }}"
                                data-name="{{ $facility->name }}"
                                data-description="{{ $facility->description }}"
                                data-location="{{ $facility->location }}"
                                data-capacity="{{ $facility->capacity }}"
                                data-hourly-rate="{{ $facility->hourly_rate }}"
                                data-daily-rate="{{ $facility->daily_rate }}"
                                data-facility-type="{{ $facility->facility_type }}"
                                data-image-path="{{ $facility->image_path }}">
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
        
        <form action="{{ route('facilities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Facility Image Upload -->
                <div>
                    <label for="facility_image" class="block text-sm font-medium text-gray-700 mb-2">Facility Image <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                        <input type="file" name="facility_image" id="facility_image" accept="image/*" required class="hidden">
                        <label for="facility_image" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Click to upload facility image</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                            </div>
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="previewImg" src="" alt="Preview" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" onclick="clearImagePreview()" class="mt-2 text-red-600 text-sm hover:underline">Remove Image</button>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Facility Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Maximum Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="capacity" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                    </div>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50"></textarea>
                </div>
                
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location/Address <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="location" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>

                <!-- Pricing Information (Based on Interview Findings) -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-800 mb-3">Pricing Structure (Based on LGU Interview)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="base_rate" class="block text-sm font-medium text-gray-700">Base Rate (3 hours) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" name="base_rate" id="base_rate" required min="5000" value="5000" class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-lgu-headline focus:border-lgu-headline">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum ₱5,000 for 3 hours</p>
                </div>
                
                <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Extension Rate (per hour) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" name="hourly_rate" id="hourly_rate" required min="2000" value="2000" class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-lgu-headline focus:border-lgu-headline">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">₱2,000 for each hour beyond 3 hours</p>
                        </div>
                    </div>
                </div>
                
                <!-- Facility Type -->
                <div>
                    <label for="facility_type" class="block text-sm font-medium text-gray-700">Facility Type <span class="text-red-500">*</span></label>
                    <select name="facility_type" id="facility_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                        <option value="">Select Type</option>
                        <option value="outdoor">Outdoor Venue</option>
                        <option value="indoor">Indoor Hall</option>
                        <option value="sports">Sports Facility</option>
                        <option value="conference">Conference Room</option>
                        <option value="multipurpose">Multi-purpose Hall</option>
                    </select>
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
        
        <form id="editForm" action="#" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Facility Image Upload -->
                <div>
                    <label for="edit_facility_image" class="block text-sm font-medium text-gray-700 mb-2">Facility Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                        <input type="file" name="facility_image" id="edit_facility_image" accept="image/*" class="hidden">
                        <label for="edit_facility_image" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Click to upload new facility image</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                            </div>
                        </label>
                    </div>
                    <div id="editImagePreview" class="mt-4 hidden">
                        <img id="editPreviewImg" src="" alt="Preview" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" onclick="clearEditImagePreview()" class="mt-2 text-red-600 text-sm hover:underline">Remove Image</button>
                    </div>
                    <div id="currentImage" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <img id="currentImageDisplay" src="" alt="Current Image" class="w-full h-32 object-cover rounded-lg">
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Facility Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="edit_capacity" class="block text-sm font-medium text-gray-700">Maximum Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="edit_capacity" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                    </div>
                </div>
                
                <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="edit_description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50"></textarea>
                </div>
                
                <div>
                    <label for="edit_location" class="block text-sm font-medium text-gray-700">Location/Address <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="edit_location" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>

                <!-- Pricing Information (Based on Interview Findings) -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-800 mb-3">Pricing Structure (Based on LGU Interview)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_base_rate" class="block text-sm font-medium text-gray-700">Base Rate (3 hours) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" name="base_rate" id="edit_base_rate" required min="5000" value="5000" class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-lgu-headline focus:border-lgu-headline">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum ₱5,000 for 3 hours</p>
                </div>
                
                <div>
                            <label for="edit_hourly_rate" class="block text-sm font-medium text-gray-700">Extension Rate (per hour) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" name="hourly_rate" id="edit_hourly_rate" required min="2000" value="2000" class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-lgu-headline focus:border-lgu-headline">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">₱2,000 for each hour beyond 3 hours</p>
                        </div>
                    </div>
                </div>
                
                <!-- Facility Type -->
                <div>
                    <label for="edit_facility_type" class="block text-sm font-medium text-gray-700">Facility Type <span class="text-red-500">*</span></label>
                    <select name="facility_type" id="edit_facility_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                        <option value="">Select Type</option>
                        <option value="outdoor">Outdoor Venue</option>
                        <option value="indoor">Indoor Hall</option>
                        <option value="sports">Sports Facility</option>
                        <option value="conference">Conference Room</option>
                        <option value="multipurpose">Multi-purpose Hall</option>
                    </select>
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
            const location = event.target.dataset.location;
            const capacity = event.target.dataset.capacity;
            const hourlyRate = event.target.dataset.hourlyRate;
            const dailyRate = event.target.dataset.dailyRate;
            const facilityType = event.target.dataset.facilityType;
            const imagePath = event.target.dataset.imagePath;

            document.getElementById('edit_name').value = name || '';
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_location').value = location || '';
            document.getElementById('edit_capacity').value = capacity || '';
            document.getElementById('edit_hourly_rate').value = hourlyRate || 2000; // Use actual or minimum default
            document.getElementById('edit_base_rate').value = dailyRate || 5000; // Use actual or minimum default
            document.getElementById('edit_facility_type').value = facilityType || 'multipurpose';

            // Clear any previous image previews
            clearEditImagePreview();
            
            // Show current image if exists
            if (imagePath && imagePath !== 'null' && imagePath !== '') {
                document.getElementById('currentImageDisplay').src = `/storage/${imagePath}`;
                document.getElementById('currentImage').classList.remove('hidden');
            } else {
                document.getElementById('currentImage').classList.add('hidden');
            }

            editForm.action = `/admin/facilities/${id}`;

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

    // Image Upload Preview Functionality
    document.getElementById('facility_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                this.value = '';
                return;
            }

            // Validate file type
            if (!file.type.match(/^image\/(jpg|jpeg|png)$/)) {
                alert('Only JPG, JPEG, and PNG files are allowed');
                this.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Edit Image Upload Preview Functionality
    document.getElementById('edit_facility_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                this.value = '';
                return;
            }

            // Validate file type
            if (!file.type.match(/^image\/(jpg|jpeg|png)$/)) {
                alert('Only JPG, JPEG, and PNG files are allowed');
                this.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('editPreviewImg').src = e.target.result;
                document.getElementById('editImagePreview').classList.remove('hidden');
                document.getElementById('currentImage').classList.add('hidden'); // Hide current image when new one is selected
            };
            reader.readAsDataURL(file);
        }
    });

// Clear image preview function
function clearImagePreview() {
    document.getElementById('facility_image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('previewImg').src = '';
}

// Clear edit image preview function
function clearEditImagePreview() {
    document.getElementById('edit_facility_image').value = '';
    document.getElementById('editImagePreview').classList.add('hidden');
    document.getElementById('editPreviewImg').src = '';
}
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
            }).then(() => {
                // Force a hard refresh to ensure fresh data is loaded
                window.location.reload(true);
            });
            
            // Also force refresh after timer completes
            setTimeout(() => {
                window.location.reload(true);
            }, 2100);
        });
    </script>
@endif

@if(session('edit_facility'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find and click the edit button for the specified facility
            const facilityId = '{{ session('edit_facility') }}';
            const editButton = document.querySelector(`.edit-btn[data-id="${facilityId}"]`);
            if (editButton) {
                // Wait a moment for the page to fully load, then click the edit button
                setTimeout(() => {
                    editButton.click();
                }, 500);
            }
        });
    </script>
@endif
@endsection