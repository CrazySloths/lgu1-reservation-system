<?php
// Diagnostic script to check City Events filtering mismatch
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CITY EVENTS DIAGNOSTIC ===\n\n";

// 1. City Events page filtering (WITHOUT proper grouping)
echo "1. CITY EVENTS PAGE (Current Logic):\n";
echo "   Query: WHERE user_name = 'City Government' OR event_name LIKE '%City Event%' OR applicant_name = 'City Mayor Office'\n\n";

$cityEventsPage = DB::table('bookings')
    ->where('user_name', 'City Government')
    ->orWhere('event_name', 'LIKE', '%City Event%')
    ->orWhere('applicant_name', 'City Mayor Office')
    ->orderBy('event_date', 'desc')
    ->get();

echo "   Found " . $cityEventsPage->count() . " events:\n";
foreach ($cityEventsPage as $event) {
    echo "   - {$event->event_name} ({$event->event_date})\n";
    echo "     User: {$event->user_name} | Applicant: {$event->applicant_name} | Status: {$event->status}\n\n";
}

echo "\n" . str_repeat("=", 60) . "\n\n";

// 2. Calendar filtering (WITH proper grouping)
echo "2. CALENDAR (With Payment Filter + City Events):\n";
echo "   Query: WHERE status IN ('approved','pending') AND (\n";
echo "          (payment_slip.status = 'paid') OR \n";
echo "          user_name = 'City Government' OR \n";
echo "          applicant_name = 'City Mayor Office' OR \n";
echo "          event_name LIKE '%CITY EVENT%'\n";
echo "   )\n\n";

$calendarEvents = DB::table('bookings')
    ->leftJoin('payment_slips', 'bookings.id', '=', 'payment_slips.booking_id')
    ->whereIn('bookings.status', ['approved', 'pending'])
    ->where(function($query) {
        $query->where('payment_slips.status', 'paid')
              ->orWhere('bookings.user_name', 'City Government')
              ->orWhere('bookings.applicant_name', 'City Mayor Office')
              ->orWhere('bookings.event_name', 'LIKE', '%CITY EVENT%');
    })
    ->select('bookings.*', 'payment_slips.status as payment_status')
    ->orderBy('bookings.event_date', 'desc')
    ->get();

echo "   Found " . $calendarEvents->count() . " events:\n";
foreach ($calendarEvents as $event) {
    $paymentInfo = $event->payment_status ? "Payment: {$event->payment_status}" : "No payment slip";
    echo "   - {$event->event_name} ({$event->event_date})\n";
    echo "     User: {$event->user_name} | Applicant: {$event->applicant_name} | {$paymentInfo}\n\n";
}

echo "\n" . str_repeat("=", 60) . "\n\n";

// 3. Show ALL bookings for comparison
echo "3. ALL BOOKINGS (for reference):\n\n";
$allBookings = DB::table('bookings')
    ->orderBy('event_date', 'desc')
    ->get();

echo "   Total: " . $allBookings->count() . " bookings\n";
foreach ($allBookings as $event) {
    echo "   - {$event->event_name} ({$event->event_date})\n";
    echo "     User: {$event->user_name} | Applicant: {$event->applicant_name} | Status: {$event->status}\n\n";
}

echo "\n=== END DIAGNOSTIC ===\n";

