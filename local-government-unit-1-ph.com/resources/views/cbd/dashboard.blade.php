@extends('layouts.cbd')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'City Budget Department - Revenue & Financial Overview')

@section('page-content')

<!-- Quick Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Current Month Revenue -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-500 text-sm">Current Month Revenue</p>
                <h3 class="text-2xl font-bold text-lgu-headline">₱{{ number_format($currentMonthRevenue, 2) }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i data-lucide="trending-up" class="w-6 h-6 text-blue-600"></i>
            </div>
        </div>
        @if($revenueGrowth > 0)
            <p class="text-sm text-green-600">+{{ number_format($revenueGrowth, 1) }}% from last month</p>
        @elseif($revenueGrowth < 0)
            <p class="text-sm text-red-600">{{ number_format($revenueGrowth, 1) }}% from last month</p>
        @else
            <p class="text-sm text-gray-500">No change from last month</p>
        @endif
    </div>

    <!-- Year-to-Date Revenue -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-500 text-sm">Year-to-Date Revenue</p>
                <h3 class="text-2xl font-bold text-lgu-headline">₱{{ number_format($ytdRevenue, 2) }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i data-lucide="dollar-sign" class="w-6 h-6 text-green-600"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500">{{ date('Y') }} Revenue</p>
    </div>

    <!-- Total Bookings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-500 text-sm">Total Bookings</p>
                <h3 class="text-2xl font-bold text-lgu-headline">{{ number_format($totalBookings) }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i data-lucide="calendar-check" class="w-6 h-6 text-purple-600"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500">Paid & Confirmed</p>
    </div>

    <!-- Active Facilities -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-500 text-sm">Active Facilities</p>
                <h3 class="text-2xl font-bold text-lgu-headline">{{ $activeFacilities }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i data-lucide="building-2" class="w-6 h-6 text-yellow-600"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500">Available for booking</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Revenue Trend -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-lgu-headline mb-4">Monthly Revenue Trend</h3>
        <div id="monthlyRevenueChart" class="h-64"></div>
    </div>

    <!-- Revenue by Payment Method -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-lgu-headline mb-4">Revenue by Payment Method</h3>
        <div id="paymentMethodChart" class="h-64"></div>
    </div>
</div>

<!-- Top Revenue Facilities -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-8">
    <h3 class="text-lg font-bold text-lgu-headline mb-4">Top Revenue Facilities</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($topFacilities as $facility)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $facility->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $facility->city }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($facility->booking_count) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">₱{{ number_format($facility->total_revenue, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No data available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Payments -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-bold text-lgu-headline mb-4">Recent Payments</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Ref</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Citizen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentPayments as $payment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->booking_reference }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->user_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->facility_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">₱{{ number_format($payment->amount_due, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No recent payments</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/apexcharts@3.45.1/dist/apexcharts.min.js"></script>
<script>
// Ensure ApexCharts loads before initializing charts
document.addEventListener('DOMContentLoaded', function() {
    // Wait for ApexCharts to be fully loaded
    setTimeout(function() {
        // Check if ApexCharts is available
        if (typeof ApexCharts === 'undefined') {
            console.error('ApexCharts library not loaded');
            return;
        }
        
        // Check if containers exist
        const monthlyContainer = document.querySelector("#monthlyRevenueChart");
        const paymentContainer = document.querySelector("#paymentMethodChart");
        
        if (!monthlyContainer || !paymentContainer) {
            console.error('Chart containers not found');
            return;
        }
    
    // Monthly Revenue Trend Chart
    const monthlyData = @json($monthlyTrend);
    
    if (monthlyData && monthlyData.length > 0) {
        try {
            const monthlyOptions = {
        series: [{
            name: 'Revenue',
            data: monthlyData.map(item => parseFloat(item.revenue) || 0)
        }],
        chart: {
            height: 250,
            type: 'area',
            toolbar: { show: false }
        },
        colors: ['#0f3d3e'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            categories: monthlyData.map(item => item.month)
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return '₱' + val.toLocaleString();
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
            }
        }
    };
    
            const monthlyChart = new ApexCharts(monthlyContainer, monthlyOptions);
            monthlyChart.render();
        } catch (error) {
            console.error('Error rendering monthly chart:', error);
        }
    }

    // Payment Method Chart
    const paymentMethodData = @json($revenueByPaymentMethod);
    
    if (paymentMethodData && paymentMethodData.length > 0) {
        try {
            const paymentMethodOptions = {
                series: paymentMethodData.map(item => parseFloat(item.total_amount) || 0),
                chart: {
                    type: 'donut',
                    height: 250
                },
                labels: paymentMethodData.map(item => {
                    const method = item.payment_method || '';
                    return method.charAt(0).toUpperCase() + method.slice(1).replace('_', ' ');
                }),
                colors: ['#0f3d3e', '#14b8a6', '#faae2b', '#ffa8ba'],
                legend: {
                    position: 'bottom'
                }
            };
            const paymentMethodChart = new ApexCharts(paymentContainer, paymentMethodOptions);
            paymentMethodChart.render();
        } catch (error) {
            console.error('Error rendering payment method chart:', error);
        }
    }

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }, 100); // Wait 100ms for ApexCharts to load
});
</script>
@endpush

