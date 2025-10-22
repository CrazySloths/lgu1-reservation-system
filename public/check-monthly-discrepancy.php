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
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background: #333; color: white;'><th>ID</th><th>Event Name</th><th>User Name</th><th>Applicant Name</th><th>Date</th><th>Facility</th><th>Status</th><th>Payment Status</th><th>Type</th></tr>";

foreach ($allOctoberBookings as $booking) {
    $paymentStatus = $booking->paymentSlip ? $booking->paymentSlip->status : 'No Payment Slip';
    
    // Check if it's a City Event
    $isCityEvent = $booking->user_name === 'City Government' || 
                   $booking->applicant_name === 'City Mayor Office' || 
                   stripos($booking->event_name, 'CITY EVENT') !== false;
    
    $rowColor = $isCityEvent ? 'background: #E9D5FF;' : ''; // Purple for city events
    
    echo "<tr style='{$rowColor}'>";
    echo "<td>{$booking->id}</td>";
    echo "<td>{$booking->event_name}</td>";
    echo "<td>" . ($booking->user_name ?: 'NULL') . "</td>";
    echo "<td>" . ($booking->applicant_name ?: 'NULL') . "</td>";
    echo "<td>{$booking->event_date}</td>";
    echo "<td>" . ($booking->facility ? $booking->facility->name : 'N/A') . "</td>";
    echo "<td><strong>{$booking->status}</strong></td>";
    echo "<td><strong>{$paymentStatus}</strong></td>";
    echo "<td><strong>" . ($isCityEvent ? '🏛️ CITY EVENT' : '👤 Citizen') . "</strong></td>";
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

// City Events check
$cityEvents = $allOctoberBookings->filter(function($booking) {
    return $booking->user_name === 'City Government' || 
           $booking->applicant_name === 'City Mayor Office' || 
           stripos($booking->event_name, 'CITY EVENT') !== false;
});
echo "<h3>CITY EVENTS in October: " . $cityEvents->count() . "</h3>";
if ($cityEvents->count() > 0) {
    echo "<ul>";
    foreach ($cityEvents as $event) {
        echo "<li><strong>{$event->event_name}</strong> - Status: {$event->status} - Fee: ₱{$event->total_fee}</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'><strong>⚠️ NO CITY EVENTS FOUND! This might be why the count is 3 instead of 4.</strong></p>";
}

echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<ul>";
echo "<li><strong>Total Bookings</strong> in October: {$allOctoberBookings->count()}</li>";
echo "<li><strong>Approved Bookings</strong> (Monthly Reports shows): {$approvedOctober->count()}</li>";
echo "<li><strong>Paid Bookings</strong> (Calendar should show): {$paidOctober->count()}</li>";
echo "<li><strong>City Events</strong>: {$cityEvents->count()}</li>";
echo "<li><strong>Expected in Calendar</strong>: Paid citizen bookings + City Events = " . ($paidOctober->count() + $cityEvents->where('status', 'approved')->count()) . "</li>";
echo "</ul>";

echo "<h3 style='color: blue;'>📊 Analysis:</h3>";
if ($approvedOctober->count() === 3 && $cityEvents->count() === 0) {
    echo "<p style='color: red;'><strong>ISSUE:</strong> There's no City Event in the database yet! That's why Monthly Reports shows 3 instead of 4.</p>";
    echo "<p><strong>SOLUTION:</strong> Create a City Event for October 2025 in the system, or check if the existing 4th event is actually a City Event.</p>";
} else if ($approvedOctober->count() === 3 && $cityEvents->count() === 1) {
    $cityEventStatus = $cityEvents->first()->status ?? 'unknown';
    echo "<p style='color: orange;'><strong>ISSUE:</strong> The City Event exists but status is '<strong>{$cityEventStatus}</strong>' instead of 'approved'!</p>";
    echo "<p><strong>SOLUTION:</strong> Update the City Event status to 'approved'.</p>";
} else {
    echo "<p style='color: green;'><strong>Everything looks correct!</strong> All events are properly counted.</p>";
}
?>

