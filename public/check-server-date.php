<?php
// Check what date the server thinks is "today"

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Carbon\Carbon;
use App\Models\Booking;

header('Content-Type: text/html; charset=utf-8');
echo "<h2>Server Date Check</h2>";
echo "<pre>";

echo "========================================\n";
echo "SERVER DATE/TIME INFO\n";
echo "========================================\n\n";

echo "PHP date(): " . date('Y-m-d H:i:s') . "\n";
echo "PHP time(): " . time() . "\n";
echo "Carbon::now(): " . Carbon::now()->toDateTimeString() . "\n";
echo "Carbon::now()->toDateString(): " . Carbon::now()->toDateString() . "\n";
echo "Carbon::today(): " . Carbon::today()->toDateString() . "\n\n";

echo "Timezone: " . date_default_timezone_get() . "\n";
echo "App Timezone: " . config('app.timezone') . "\n\n";

echo "========================================\n";
echo "BOOKINGS FOR 'TODAY' (SERVER DATE)\n";
echo "========================================\n\n";

$todayDate = Carbon::now()->toDateString();
echo "Searching for bookings on: {$todayDate}\n\n";

$todaysBookings = Booking::where('status', 'approved')
    ->whereDate('event_date', $todayDate)
    ->get();

echo "Approved bookings found: " . $todaysBookings->count() . "\n\n";

foreach ($todaysBookings as $booking) {
    echo "- {$booking->event_name} (ID: {$booking->id}) on {$booking->event_date}\n";
}

echo "\n========================================\n";
echo "ALL APPROVED BOOKINGS (NEXT 7 DAYS)\n";
echo "========================================\n\n";

$weekBookings = Booking::where('status', 'approved')
    ->whereBetween('event_date', [
        Carbon::now()->toDateString(),
        Carbon::now()->addDays(7)->toDateString()
    ])
    ->orderBy('event_date')
    ->get();

foreach ($weekBookings as $booking) {
    echo "{$booking->event_date}: {$booking->event_name}\n";
}

echo "\n========================================\n";
echo "DASHBOARD QUERY SIMULATION\n";
echo "========================================\n\n";

$dashboardCount = Booking::where('status', 'approved')
    ->whereDate('event_date', Carbon::now()->toDateString())
    ->count();

echo "Dashboard 'Today's Events' count: {$dashboardCount}\n";
echo "This is what the dashboard shows!\n";

echo "</pre>";
?>

