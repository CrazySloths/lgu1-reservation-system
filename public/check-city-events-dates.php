<?php
// Check City Events dates in database vs what calendar shows
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>CITY EVENTS DATE CHECK</h2>\n\n";

// Get City Events from database
$cityEvents = DB::table('bookings')
    ->leftJoin('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
    ->where(function($query) {
        $query->where('bookings.user_name', 'City Government')
              ->orWhere('bookings.event_name', 'LIKE', '%City Event%')
              ->orWhere('bookings.event_name', 'LIKE', '%CITY EVENT%')
              ->orWhere('bookings.applicant_name', 'City Mayor Office');
    })
    ->select('bookings.*', 'facilities.name as facility_name')
    ->orderBy('bookings.event_date', 'desc')
    ->get();

echo "<h3>Database Records (What City Events Page Shows):</h3>\n";
echo "<table border='1' cellpadding='10'>\n";
echo "<tr><th>ID</th><th>Event Name</th><th>Facility</th><th>Date</th><th>Start Time</th><th>End Time</th><th>Status</th></tr>\n";

foreach ($cityEvents as $event) {
    $startTime = date('g:i A', strtotime($event->start_time));
    $endTime = date('g:i A', strtotime($event->end_time));
    $eventDate = date('M d, Y', strtotime($event->event_date));
    
    echo "<tr>";
    echo "<td>{$event->id}</td>";
    echo "<td>{$event->event_name}</td>";
    echo "<td>{$event->facility_name}</td>";
    echo "<td><strong>{$eventDate}</strong></td>";
    echo "<td><strong>{$startTime}</strong></td>";
    echo "<td><strong>{$endTime}</strong></td>";
    echo "<td>{$event->status}</td>";
    echo "</tr>\n";
}

echo "</table>\n\n";

echo "<hr>\n";
echo "<h3>Raw Database Values:</h3>\n";
echo "<pre>\n";
foreach ($cityEvents as $event) {
    echo "Event: {$event->event_name}\n";
    echo "  - event_date: {$event->event_date}\n";
    echo "  - start_time: {$event->start_time}\n";
    echo "  - end_time: {$event->end_time}\n";
    echo "  - status: {$event->status}\n\n";
}
echo "</pre>\n";

