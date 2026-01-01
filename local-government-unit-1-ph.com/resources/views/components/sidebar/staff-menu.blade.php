<!-- MAIN SECTION -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Main</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ route('staff.dashboard') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>
</div>

<!-- BOOKING VERIFICATION SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Booking Verification</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ route('staff.verification-queue') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('staff.verification-queue') ? 'active' : '' }}">
                <i data-lucide="clock" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Verification Queue</span>
            </a>
        </li>
        <li>
            <a href="{{ route('staff.bookings.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>All Bookings</span>
            </a>
        </li>
        <li>
            <a href="{{ route('staff.calendar') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('staff.calendar') ? 'active' : '' }}">
                <i data-lucide="calendar-days" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Calendar View</span>
            </a>
        </li>
    </ul>
</div>

<!-- FACILITIES INFORMATION SUBMODULE (Read-Only Access) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Facilities</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="#" onclick="showComingSoon('View Facilities'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="building-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>View Facilities</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Equipment List'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="package" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Equipment List</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Pricing Information'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="tag" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Pricing Info</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>

<!-- REPORTS & ANALYTICS SUBMODULE (Limited Access) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Reports</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="#" onclick="showComingSoon('Verification Reports'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="file-bar-chart" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>My Statistics</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Activity Log'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="file-text" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Activity Log</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>

<!-- COMMUNICATIONS SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Communications</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="#" onclick="showComingSoon('Send Notifications'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="send" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Send Notification</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Message Templates'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="mail" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Templates</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>
