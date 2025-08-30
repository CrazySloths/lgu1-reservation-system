@extends('citizen.layouts.app')

@section('title', 'Profile - LGU1 Citizen Portal')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600 mt-1">Manage your account information</p>
    </div>

    <!-- Profile Information -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('citizen.profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" value="{{ $user->email }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Email cannot be changed. Contact support if needed.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone_number') border-red-500 @enderror">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="address" name="address" rows="3" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Account Information (Read-only) -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                        <div class="flex items-center">
                            @if($user->isVerified())
                                <div class="flex items-center text-green-600">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="font-medium">Verified</span>
                                </div>
                            @else
                                <div class="flex items-center text-yellow-600">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span class="font-medium">Pending Verification</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID Type</label>
                        <input type="text" value="{{ $user->id_type }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID Number</label>
                        <input type="text" value="{{ $user->id_number }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Member Since</label>
                        <input type="text" value="{{ $user->created_at->format('F j, Y') }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    
                    @if($user->verified_at)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Verified On</label>
                        <input type="text" value="{{ $user->verified_at->format('F j, Y') }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('citizen.dashboard') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>
                        Update Profile
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ID Verification Notice -->
    @if(!$user->isVerified())
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Account Verification</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Your account is currently under verification. ID information cannot be changed during this process. Contact our office if you need to update your ID information.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
