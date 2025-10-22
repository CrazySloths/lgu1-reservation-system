<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>October 2025 Booking Analysis</h2>";

// All bookings in October 2025
$allOctoberBookings = \App\Models\Booking::with(['facility', 'paymentSlip'])
    ->whereYear('event_date', 2025)
    ->whereMonth('event_date', 10)
    ->get();

echo "<h3>ALL Bookings in October 2025: " . $allOctoberBookings->count() . "</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Event Name</th><th>Date</th><th>Facility</th><th>Status</th><th>Payment Status</th></tr>";

foreach ($allOctoberBookings as $booking) {
    $paymentStatus = $booking->paymentSlip ? $booking->paymentSlip->status : 'No Payment Slip';
    echo "<tr>";
    echo "<td>{$booking->id}</td>";
    echo "<td>{$booking->event_name}</td>";
    echo "<td>{$booking->event_date}</td>";
    echo "<td>" . ($booking->facility ? $booking->facility->name : 'N/A') . "</td>";
    echo "<td><strong>{$booking->status}</strong></td>";
    echo "<td><strong>{$paymentStatus}</strong></td>";
    echo "</tr>";
}
echo "</table>";

// Approved bookings in October
$approvedOctober = $allOctoberBookings->where('status', 'approved');
echo "<h3>APPROVED Bookings in October: " . $approvedOctober->count() . "</h3>";

// Paid bookings in October
$paidOctober = $allOctoberBookings->filter(function($booking) {
    return $booking->paymentSlip && $booking->paymentSlip->status === 'paid';
});
echo "<h3>PAID Bookings in October: " . $paidOctober->count() . "</h3>";

// Approved AND Paid bookings in October
$approvedAndPaid = $allOctoberBookings->filter(function($booking) {
    return $booking->status === 'approved' && $booking->paymentSlip && $booking->paymentSlip->status === 'paid';
});
echo "<h3>APPROVED + PAID Bookings in October: " . $approvedAndPaid->count() . "</h3>";

echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<ul>";
echo "<li><strong>Monthly Reports</strong> shows: Approved bookings = {$approvedOctober->count()}</li>";
echo "<li><strong>Calendar</strong> should show: Paid bookings = {$paidOctober->count()}</li>";
echo "<li><strong>Discrepancy?</strong> " . ($approvedOctober->count() !== $paidOctober->count() ? "YES!" : "No") . "</li>";
echo "</ul>";
?>

