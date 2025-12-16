<!-- Dashboard -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-caption text-gray-400 uppercase tracking-wider mb-gr-xs">Main</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('citizen.dashboard')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-gr-xs" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('citizen.browse-facilities')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-gr-xs" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"></path>
                </svg>
                Browse Facilities
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('citizen.facility-calendar')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-gr-xs" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
                Facility Calendar
            </a>
        </li>
    </ul>
</div>

<!-- My Reservations -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-caption text-gray-400 uppercase tracking-wider mb-gr-xs">My Reservations</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('citizen.reservations')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-gr-xs" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                </svg>
                My Reservations
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('citizen.reservation.history')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-gr-xs" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
                Reservation History
            </a>
        </li>
    </ul>
</div>

<!-- Payments & Billing -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-caption text-gray-400 uppercase tracking-wider mb-gr-xs">Payments</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('citizen.payment-slips')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200">
                <span class="w-5 h-5 mr-gr-xs flex items-center justify-center font-bold text-body">₱</span>
                Payment Slips
            </a>
        </li>
    </ul>
</div>

<!-- Community -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-caption text-gray-400 uppercase tracking-wider mb-gr-xs">Community</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('citizen.bulletin')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-gr-xs">
                    <path d="M3 7V5c0-1.1.9-2 2-2h2"/><path d="M17 3h2c1.1 0 2 .9 2 2v2"/><path d="M21 17v2c0 1.1-.9 2-2 2h-2"/><path d="M7 21H5c-1.1 0-2-.9-2-2v-2"/><rect width="7" height="5" x="7" y="7" rx="1"/><rect width="7" height="5" x="10" y="12" rx="1"/>
                </svg>
                Bulletin Board
            </a>
        </li>
    </ul>
</div>

<!-- Account Settings -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-caption text-gray-400 uppercase tracking-wider mb-gr-xs">Account</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('citizen.profile')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-gr-xs" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                Profile Settings
            </a>
        </li>
    </ul>
</div>
<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/components/sidebar/citizen-menu.blade.php ENDPATH**/ ?>