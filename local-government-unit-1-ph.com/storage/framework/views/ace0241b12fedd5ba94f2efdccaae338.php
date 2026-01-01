<!-- MAIN SECTION -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Main</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>
</div>

<!-- BOOKING MANAGEMENT SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Booking Management</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.bookings.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.bookings.*') ? 'active' : ''); ?>">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>All Bookings</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.calendar')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.calendar') ? 'active' : ''); ?>">
                <i data-lucide="calendar-days" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Calendar View</span>
            </a>
        </li>
    </ul>
</div>

<!-- FINANCIAL MANAGEMENT SUBMODULE (Coming Soon) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Financial</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.analytics.revenue-report')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.revenue-report') ? 'active' : ''); ?>">
                <i data-lucide="trending-up" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Revenue Reports</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Payment Analytics'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Payment Analytics</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Transaction History'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <span class="w-5 h-5 mr-gr-xs flex-shrink-0 flex items-center justify-center font-bold text-body">₱</span>
                <span>Transactions</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>

<!-- FACILITIES MANAGEMENT SUBMODULE (Coming Soon) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Facilities</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="#" onclick="showComingSoon('Manage Facilities'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="building-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Manage Facilities</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Equipment Inventory'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="package" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Equipment</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Pricing Management'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="tag" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Pricing</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>

<!-- USER MANAGEMENT SUBMODULE (Coming Soon) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Users</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="#" onclick="showComingSoon('Staff Management'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="users" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Staff Accounts</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Citizen Management'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="user-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Citizens</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>

<!-- COMMUNICATIONS SUBMODULE (Coming Soon) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Communications</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="#" onclick="showComingSoon('Email Notifications'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="mail" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Email Settings</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('SMS Notifications'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="message-square" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>SMS Settings</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>

<!-- REPORTS & ANALYTICS SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Reports & Analytics</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.analytics.booking-statistics')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.booking-statistics') ? 'active' : ''); ?>">
                <i data-lucide="bar-chart-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Booking Statistics</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.analytics.facility-utilization')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.facility-utilization') ? 'active' : ''); ?>">
                <i data-lucide="pie-chart" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Facility Utilization</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.analytics.citizen-analytics')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.citizen-analytics') ? 'active' : ''); ?>">
                <i data-lucide="users-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Citizen Analytics</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Audit Logs'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="shield-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Audit Trail</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>

<!-- SYSTEM SETTINGS SUBMODULE (Coming Soon) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">System</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="#" onclick="showComingSoon('System Settings'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="settings" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Settings</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Backup & Restore'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="database" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Backup</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>
<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/components/sidebar/admin-menu.blade.php ENDPATH**/ ?>