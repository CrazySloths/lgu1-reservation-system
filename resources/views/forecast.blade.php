@extends('layouts.app')

@section('content')
<div class="mb-6">
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
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold mb-1 text-white">Facility Usage Forecast</h1>
                    <p class="text-gray-200 text-lg">Predicted facility usage for the next 30 days (via TensorFlow.js)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden p-6">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Usage Analytics and Prediction Chart</h3>
    
    <div style="width: 100%; height: 500px;">
        {{-- THIS IS USED BY CHART.JS --}}
        <canvas id="usageChart"></canvas> 
        <p id="chartStatus" class="text-center mt-3 text-gray-600">Initializing TensorFlow model...</p>
    </div>
    
</div>
@endsection
@push('scripts')
    @vite('resources/js/analytics.js')

    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            console.log("Analytics script is attempting to execute...");
            
            startForecasting(); 
        });
    </script>
@endpush