

<?php $__env->startSection('title', 'Revenue Reports - CBD'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-6">
    <!-- Header with Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-[#0f3d3e]">Revenue Report</h2>
                <p class="text-gray-600 mt-1"><?php echo e($startDate->format('F d, Y')); ?> - <?php echo e($endDate->format('F d, Y')); ?></p>
            </div>
            
            <!-- Filter Form -->
            <form method="GET" action="<?php echo e(route('cbd.reports.revenue')); ?>" class="flex flex-wrap items-end gap-3">
                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                    <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0f3d3e] focus:border-[#0f3d3e]" onchange="toggleReportType(this.value)">
                        <option value="monthly" <?php echo e($reportType === 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                        <option value="quarterly" <?php echo e($reportType === 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                    </select>
                </div>
                
                <!-- Month Selector (for monthly) -->
                <div id="monthSelector" style="display: <?php echo e($reportType === 'monthly' ? 'block' : 'none'); ?>">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0f3d3e] focus:border-[#0f3d3e]">
                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($num); ?>" <?php echo e($selectedMonth == $num ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <!-- Quarter Selector (for quarterly) -->
                <div id="quarterSelector" style="display: <?php echo e($reportType === 'quarterly' ? 'block' : 'none'); ?>">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quarter</label>
                    <select name="quarter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0f3d3e] focus:border-[#0f3d3e]">
                        <?php $__currentLoopData = $quarters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($num); ?>" <?php echo e($selectedQuarter == $num ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <!-- Year Selector -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select name="year" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0f3d3e] focus:border-[#0f3d3e]">
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>" <?php echo e($selectedYear == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-[#0f3d3e] text-white rounded-lg hover:bg-opacity-90 transition-all">
                        Generate
                    </button>
                    <button type="button" class="px-6 py-2 bg-[#14b8a6] text-white rounded-lg hover:bg-opacity-90 transition-all" onclick="exportReport()">
                        <i data-lucide="download" class="w-4 h-4 inline mr-1"></i>
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Revenue Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Current Period Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Current Period</h3>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i data-lucide="trending-up" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-[#0f3d3e]">₱<?php echo e(number_format($currentRevenue, 2)); ?></p>
            <p class="text-sm text-gray-500 mt-2"><?php echo e($reportType === 'monthly' ? 'This month' : 'This quarter'); ?></p>
        </div>

        <!-- Previous Period Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Previous Period</h3>
                <div class="p-2 bg-gray-100 rounded-lg">
                    <i data-lucide="calendar" class="w-5 h-5 text-gray-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-700">₱<?php echo e(number_format($previousRevenue, 2)); ?></p>
            <p class="text-sm text-gray-500 mt-2"><?php echo e($reportType === 'monthly' ? 'Last month' : 'Last quarter'); ?></p>
        </div>

        <!-- Growth Rate -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Growth Rate</h3>
                <div class="p-2 <?php echo e($percentageChange >= 0 ? 'bg-green-100' : 'bg-red-100'); ?> rounded-lg">
                    <i data-lucide="<?php echo e($percentageChange >= 0 ? 'arrow-up' : 'arrow-down'); ?>" class="w-5 h-5 <?php echo e($percentageChange >= 0 ? 'text-green-600' : 'text-red-600'); ?>"></i>
                </div>
            </div>
            <p class="text-3xl font-bold <?php echo e($percentageChange >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                <?php echo e($percentageChange >= 0 ? '+' : ''); ?><?php echo e(number_format($percentageChange, 1)); ?>%
            </p>
            <p class="text-sm text-gray-500 mt-2">vs previous period</p>
        </div>
    </div>

    <!-- Revenue by Facility -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-[#0f3d3e] mb-4">Revenue by Facility</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">% of Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $revenueByFacility; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($facility->facility_name); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600"><?php echo e($facility->city_name ?? 'N/A'); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-gray-900"><?php echo e($facility->total_bookings); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-semibold text-[#0f3d3e]">₱<?php echo e(number_format($facility->total_revenue, 2)); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-gray-600"><?php echo e($currentRevenue > 0 ? number_format(($facility->total_revenue / $currentRevenue) * 100, 1) : 0); ?>%</div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                            <p>No revenue data available for the selected period.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Revenue by Payment Method -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-[#0f3d3e] mb-4">Revenue by Payment Method</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $revenueByPaymentMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600 capitalize"><?php echo e(str_replace('_', ' ', $method->payment_method)); ?></span>
                    <i data-lucide="wallet" class="w-4 h-4 text-gray-400"></i>
                </div>
                <p class="text-2xl font-bold text-[#0f3d3e]">₱<?php echo e(number_format($method->total_amount, 2)); ?></p>
                <p class="text-xs text-gray-500 mt-1"><?php echo e($method->transaction_count); ?> transaction<?php echo e($method->transaction_count > 1 ? 's' : ''); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-4 text-center py-8 text-gray-500">
                No payment data available
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-[#0f3d3e] mb-4">Recent Transactions</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Slip</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Ref</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($transaction->payment_slip_number); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600"><?php echo e($transaction->booking_reference); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e($transaction->facility_name); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($transaction->city_name ?? 'N/A'); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                <?php if($transaction->payment_method === 'cash'): ?> bg-green-100 text-green-800
                                <?php elseif($transaction->payment_method === 'gcash'): ?> bg-blue-100 text-blue-800
                                <?php elseif($transaction->payment_method === 'paymaya'): ?> bg-purple-100 text-purple-800
                                <?php else: ?> bg-gray-100 text-gray-800
                                <?php endif; ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $transaction->payment_method))); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-semibold text-[#0f3d3e]">₱<?php echo e(number_format($transaction->amount_due, 2)); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo e(\Carbon\Carbon::parse($transaction->paid_at)->format('M d, Y')); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($transaction->paid_at)->format('h:i A')); ?></div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                            <p>No transactions found for the selected period.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if($transactions->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($transactions->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleReportType(type) {
    document.getElementById('monthSelector').style.display = type === 'monthly' ? 'block' : 'none';
    document.getElementById('quarterSelector').style.display = type === 'quarterly' ? 'block' : 'none';
}

function exportReport() {
    Swal.fire({
        title: 'Export Report',
        html: `
            <div class="text-left">
                <p class="mb-4">Select export format:</p>
                <div class="space-y-2">
                    <button class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed" disabled>
                        <i data-lucide="file-spreadsheet" class="w-4 h-4 inline mr-2"></i>
                        Export as Excel (Coming Soon)
                    </button>
                    <button class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed" disabled>
                        <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>
                        Export as PDF (Coming Soon)
                    </button>
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="exportAs('csv')">
                        <i data-lucide="file" class="w-4 h-4 inline mr-2"></i>
                        Export as CSV
                    </button>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true
    });
}

function exportAs(format) {
    // Get current filter values
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type') || 'monthly';
    const month = urlParams.get('month') || '<?php echo e($selectedMonth); ?>';
    const year = urlParams.get('year') || '<?php echo e($selectedYear); ?>';
    const quarter = urlParams.get('quarter') || '';
    
    // Build export URL
    let exportUrl = '<?php echo e(route("cbd.reports.revenue.export")); ?>?format=' + format + '&type=' + type + '&month=' + month + '&year=' + year;
    if (quarter) {
        exportUrl += '&quarter=' + quarter;
    }
    
    // Trigger download
    window.location.href = exportUrl;
    
    Swal.fire({
        title: 'Exporting...',
        text: 'Your download should start shortly.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.cbd', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/cbd/reports/revenue.blade.php ENDPATH**/ ?>