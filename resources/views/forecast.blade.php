@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Facility Usage Forecast</h2>
        <p class="text-gray-200">Predicted facility usage for the next 6 months.</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden p-6">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Forecast Data</h3>
    
    @if(isset($forecast_data['error']))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            {{ $forecast_data['error'] }}
        </div>
    @elseif(!empty($forecast_data['predicted_bookings']))
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Predicted Bookings</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($forecast_data['predicted_bookings'] as $index => $booking_count)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($forecast_data['forecast_date'][$index])->format('F d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $booking_count }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-500">No forecast data available. Please check the Python script.</p>
    @endif
</div>
@endsection