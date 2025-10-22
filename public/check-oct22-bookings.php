<?php
// Temporary diagnostic - Check Oct 22, 2025 bookings

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Booking;
use Carbon\Carbon;

header('Content-Type: text/html; charset=utf-8');
echo "<h2>October 22, 2025 Bookings Check</h2>";
echo "<pre>";

$today = '2025-10-22';

echo "========================================\n";
echo "BOOKINGS FOR OCTOBER 22, 2025\n";
echo "========================================\n\n";

$bookings = Booking::with('facility')
    ->whereDate('event_date', $today)
    ->get();

echo "Total bookings found: " . $bookings->count() . "\n\n";

if ($bookings->count() > 0) {
    foreach ($bookings as $booking) {
        echo "ID: {$booking->id}\n";
        echo "Event: {$booking->event_name}\n";
        echo "Facility: " . ($booking->facility ? $booking->facility->name : 'N/A') . "\n";
        echo "Time: {$booking->start_time} - {$booking->end_time}\n";
        echo "Status: {$booking->status}\n";
        echo "Created: {$booking->created_at}\n";
        echo "----------------------------------------\n\n";
    }
}

echo "\n========================================\n";
echo "APPROVED BOOKINGS ONLY\n";
echo "========================================\n\n";

$approvedToday = Booking::with('facility')
    ->where('status', 'approved')
    ->whereDate('event_date', $today)
    ->get();

echo "Approved bookings for Oct 22: " . $approvedToday->count() . "\n\n";

foreach ($approvedToday as $booking) {
    echo "✓ {$booking->event_name} at " . ($booking->facility ? $booking->facility->name : 'N/A') . " ({$booking->start_time})\n";
}

echo "\n========================================\n";
echo "CHECK FOR DUPLICATES\n";
echo "========================================\n\n";

$duplicateCheck = Booking::whereDate('event_date', $today)
    ->selectRaw('event_name, facility_id, start_time, COUNT(*) as count')
    ->groupBy('event_name', 'facility_id', 'start_time')
    ->havingRaw('COUNT(*) > 1')
    ->get();

if ($duplicateCheck->count() > 0) {
    echo "⚠️ DUPLICATES FOUND:\n";
    foreach ($duplicateCheck as $dup) {
        echo "- {$dup->event_name}: {$dup->count} copies\n";
    }
} else {
    echo "No duplicates found.\n";
}

echo "</pre>";
?>

