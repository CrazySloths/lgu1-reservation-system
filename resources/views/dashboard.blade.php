@extends('layouts.app')

@section('content')

<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Good Morning, Admin!</h2>
                <p class="text-grey-200">Here's what's happen today</p>
            </div>
            <div class="hidden md:block">
                <div class="text-right">
                    <p class="text-sm text-gray-200">Today's Date</p>
                    <p class="text-lg font-semibold" id="current-date">Friday, August 14, 2025</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-meduim text-lgu-paragraph">Active Project</p>
                <p class="text-3xl font-bold text-lgu-headline">12</p>
                <p class="text-sm text-green-600">+2 from last month</p>
            </div>
            <div class="w-12 h-12 bg-lgu-highlight bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.923a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

@endsection