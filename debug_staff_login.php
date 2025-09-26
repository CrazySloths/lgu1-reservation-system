<?php
// Debug script for staff login issues
// Place this in your public_html directory and access via browser

echo "<h1>Staff Login Debug</h1>";

// Check if we can connect to database
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=faci_facility",
        "faci_facility",
        "cristian123"
    );
    echo "<p style='color: green;'>✅ Database connection: SUCCESS</p>";
    
    // Check if staff user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ? OR email = ?");
    $stmt->execute(['Staff-Facilities123', 'staff-facilities123@sso.local']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p style='color: green;'>✅ Staff user found in database</p>";
        echo "<pre>";
        print_r([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'NOT SET',
            'status' => $user['status'] ?? 'NOT SET',
            'sso_token' => isset($user['sso_token']) ? 'COLUMN EXISTS' : 'MISSING',
            'sso_token_expires_at' => isset($user['sso_token_expires_at']) ? 'COLUMN EXISTS' : 'MISSING'
        ]);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Staff user NOT found in database</p>";
        
        // Show all users
        $stmt = $pdo->query("SELECT id, name, email, role FROM users LIMIT 10");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Existing users:</h3><pre>";
        print_r($users);
        echo "</pre>";
    }
    
    // Check table structure
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Users table structure:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Column</th><th>Type</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Default']}</td></tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Check PHP extensions
echo "<h3>PHP Extensions:</h3>";
if (extension_loaded('pdo_mysql')) {
    echo "<p style='color: green;'>✅ pdo_mysql extension loaded</p>";
} else {
    echo "<p style='color: red;'>❌ pdo_mysql extension NOT loaded</p>";
}

// Simulate SSO callback URL
echo "<h3>Test SSO Callback URL:</h3>";
echo "<p>Try this URL to test direct callback:</p>";
echo "<code>https://facilities.local-government-unit-1-ph.com/sso/login?user_id=1&username=Staff-Facilities123&role=staff&ts=" . time() . "&sig=test123</code>";

?>
