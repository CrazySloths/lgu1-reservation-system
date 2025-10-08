@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Facility Usage Forecast</h2>
        <p class="text-gray-200">Predicted facility usage for the next 30 days (via TensorFlow.js).</p> 
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