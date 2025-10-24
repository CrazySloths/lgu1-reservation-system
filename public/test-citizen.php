<?php
// Simple test to check if citizen user exists
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test URL params
$userId = $_GET['user_id'] ?? null;
$username = $_GET['username'] ?? null;
$email = $_GET['email'] ?? null;

echo "<h1>Citizen User Test</h1>";
echo "<p>URL Parameters:</p>";
echo "<ul>";
echo "<li>user_id: " . ($userId ?? 'not set') . "</li>";
echo "<li>username: " . ($username ?? 'not set') . "</li>";
echo "<li>email: " . ($email ?? 'not set') . "</li>";
echo "</ul>";

// Try to find user
$user = \App\Models\User::where(function($query) use ($userId, $email, $username) {
    if ($userId) $query->orWhere('id', $userId)->orWhere('external_id', $userId);
    if ($email) $query->orWhere('email', $email);
    if ($username) $query->orWhere('name', $username);
})->first();

echo "<h2>User Lookup Result:</h2>";
if ($user) {
    echo "<pre>";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "</pre>";
} else {
    echo "<p style='color:red;'>❌ No user found!</p>";
    
    // Show all citizens
    echo "<h3>All Citizens in Database:</h3>";
    $citizens = \App\Models\User::where('role', 'citizen')->get();
    if ($citizens->count() > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
        foreach ($citizens as $c) {
            echo "<tr>";
            echo "<td>" . $c->id . "</td>";
            echo "<td>" . $c->name . "</td>";
            echo "<td>" . $c->email . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No citizens found in database!</p>";
    }
}

