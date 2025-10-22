<?php
// Temporary diagnostic script - DELETE AFTER USE

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Booking;
use App\Models\PaymentSlip;
use Carbon\Carbon;

echo "<h2>Database Diagnostic Report</h2>";
echo "<pre>";

// Total bookings
echo "========================================\n";
echo "BOOKINGS OVERVIEW\n";
echo "========================================\n";
$totalBookings = Booking::count();
echo "Total Bookings: {$totalBookings}\n";

$approvedBookings = Booking::where('status', 'approved')->count();
echo "Approved Bookings: {$approvedBookings}\n";

$pendingBookings = Booking::where('status', 'pending')->count();
echo "Pending Bookings: {$pendingBookings}\n";

$rejectedBookings = Booking::where('status', 'rejected')->count();
echo "Rejected Bookings: {$rejectedBookings}\n\n";

// Bookings by month
echo "========================================\n";
echo "BOOKINGS BY MONTH\n";
echo "========================================\n";
$bookingsByMonth = Booking::selectRaw('DATE_FORMAT(event_date, "%Y-%m") as month, COUNT(*) as count, status')
    ->groupBy('month', 'status')
    ->orderBy('month', 'desc')
    ->get();

foreach ($bookingsByMonth as $stat) {
    echo "{$stat->month} ({$stat->status}): {$stat->count}\n";
}

// Current month
$currentMonth = now()->format('Y-m');
echo "\n========================================\n";
echo "CURRENT MONTH: {$currentMonth}\n";
echo "========================================\n";

$currentMonthBookings = Booking::whereRaw("DATE_FORMAT(event_date, '%Y-%m') = ?", [$currentMonth])->get();
echo "Total bookings this month: " . $currentMonthBookings->count() . "\n";
echo "Approved this month: " . $currentMonthBookings->where('status', 'approved')->count() . "\n\n";

if ($currentMonthBookings->isEmpty()) {
    echo "⚠️ NO BOOKINGS FOUND FOR CURRENT MONTH ({$currentMonth})\n";
    echo "This is why the dashboard shows low numbers!\n\n";
    
    // Show what months DO have bookings
    echo "Months that HAVE bookings:\n";
    $allBookings = Booking::orderBy('event_date', 'desc')->get();
    foreach ($allBookings as $booking) {
        echo "  - {$booking->event_name}: " . Carbon::parse($booking->event_date)->format('Y-m-d') . " ({$booking->status})\n";
    }
}

// Payment slips
echo "\n========================================\n";
echo "PAYMENT SLIPS\n";
echo "========================================\n";
$totalPayments = PaymentSlip::count();
echo "Total Payment Slips: {$totalPayments}\n";

$paidSlips = PaymentSlip::where('status', 'paid')->count();
echo "Paid Slips: {$paidSlips}\n";

$pendingPayments = PaymentSlip::where('status', 'pending')->count();
echo "Pending Payments: {$pendingPayments}\n\n";

// Revenue by month
$revenueByMonth = PaymentSlip::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, status, SUM(amount) as total')
    ->groupBy('month', 'status')
    ->orderBy('month', 'desc')
    ->get();

echo "Revenue by Month:\n";
foreach ($revenueByMonth as $rev) {
    echo "  {$rev->month} ({$rev->status}): ₱" . number_format($rev->total, 2) . "\n";
}

// Current month revenue
$currentMonthRevenue = PaymentSlip::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
    ->where('status', 'paid')
    ->sum('amount');
    
echo "\nCurrent Month Revenue ({$currentMonth}): ₱" . number_format($currentMonthRevenue, 2) . "\n";

if ($currentMonthRevenue == 0) {
    echo "⚠️ NO REVENUE FOR CURRENT MONTH!\n";
    echo "Possible reasons:\n";
    echo "  1. All payment slips are from previous months\n";
    echo "  2. Payment slips exist but status != 'paid'\n";
    echo "  3. No payment slips created yet\n";
}

echo "\n========================================\n";
echo "CONCLUSION\n";
echo "========================================\n";
echo "The dashboard shows data for: " . now()->format('F Y') . "\n";
echo "If your bookings are from August/September, they won't appear in October's stats!\n";

echo "</pre>";
?>

