

<?php $__env->startSection('title', 'Budget Analysis - CBD'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div>
            <h2 class="text-2xl font-bold text-[#0f3d3e]">Budget Analysis Report</h2>
            <p class="text-gray-600 mt-1">Coming Soon - Comprehensive budget analysis and forecasting</p>
        </div>
    </div>

    <!-- Placeholder Content -->
    <div class="bg-white rounded-lg shadow-sm p-12">
        <div class="text-center">
            <i data-lucide="construction" class="w-24 h-24 mx-auto mb-4 text-gray-400"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Under Development</h3>
            <p class="text-gray-600 mb-6">This report is currently being developed and will be available soon.</p>
            <a href="<?php echo e(route('cbd.dashboard')); ?>" class="inline-flex items-center px-6 py-3 bg-[#0f3d3e] text-white rounded-lg hover:bg-opacity-90 transition-all">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.cbd', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/cbd/reports/budget-analysis.blade.php ENDPATH**/ ?>