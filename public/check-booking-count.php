<?php
// Diagnostic script to check booking count
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Booking;

echo "<h2>Booking Count Analysis</h2>";
echo "<hr>";

// Get all bookings
$bookings = Booking::orderBy('id', 'asc')->get();

echo "<h3>Total Bookings: " . $bookings->count() . "</h3>";
echo "<hr>";

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th>ID</th>";
echo "<th>Event Name</th>";
echo "<th>Applicant Name</th>";
echo "<th>Event Date</th>";
echo "<th>Status</th>";
echo "<th>Created At</th>";
echo "</tr>";

foreach ($bookings as $booking) {
    echo "<tr>";
    echo "<td>{$booking->id}</td>";
    echo "<td>{$booking->event_name}</td>";
    echo "<td>{$booking->applicant_name}</td>";
    echo "<td>{$booking->event_date}</td>";
    echo "<td>{$booking->status}</td>";
    echo "<td>{$booking->created_at}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>Breakdown by Status:</h3>";
echo "<ul>";
echo "<li>Approved: " . Booking::where('status', 'approved')->count() . "</li>";
echo "<li>Pending: " . Booking::where('status', 'pending')->count() . "</li>";
echo "<li>Rejected: " . Booking::where('status', 'rejected')->count() . "</li>";
echo "<li>Cancelled: " . Booking::where('status', 'cancelled')->count() . "</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>Check for Duplicates:</h3>";
$duplicates = Booking::select('event_name', 'event_date', 'facility_id', \DB::raw('count(*) as count'))
    ->groupBy('event_name', 'event_date', 'facility_id')
    ->having('count', '>', 1)
    ->get();

if ($duplicates->count() > 0) {
    echo "<p style='color: red;'>Found " . $duplicates->count() . " potential duplicates:</p>";
    foreach ($duplicates as $dup) {
        echo "<p>Event: {$dup->event_name}, Date: {$dup->event_date}, Facility: {$dup->facility_id}, Count: {$dup->count}</p>";
    }
} else {
    echo "<p style='color: green;'>No duplicates found.</p>";
}

