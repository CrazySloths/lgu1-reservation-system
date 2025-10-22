<?php
/**
 * WEB-BASED LOGIN DIAGNOSTIC TOOL
 * Access this at: https://facilities.local-government-unit-1-ph.com/diagnostic.php
 * 
 * This runs in the WEB context (not CLI), so it can check:
 * - Database connectivity
 * - Session configuration
 * - User authentication
 * - File permissions
 */

// Security: Only allow access from specific IPs (add yours)
$allowed_ips = ['127.0.0.1', '::1']; // Add your IP here
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips) && php_sapi_name() !== 'cli') {
    // Comment out this line to allow access from any IP (ONLY FOR TESTING!)
    // die('Access denied. Add your IP to $allowed_ips in diagnostic.php');
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Login Diagnostic Tool</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1a1a1a; color: #0f0; }
    .success { color: #0f0; }
    .error { color: #f00; }
    .warning { color: #ff0; }
    .info { color: #0ff; }
    h2 { border-bottom: 2px solid #0f0; padding-bottom: 10px; }
    pre { background: #000; padding: 10px; border-left: 3px solid #0f0; overflow-x: auto; }
</style></head><body>";

echo "<h1>🔍 CITIZEN LOGIN DIAGNOSTIC TOOL</h1>";
echo "<p class='info'>Running in WEB context (PHP-FPM/Apache) - " . date('Y-m-d H:i:s') . "</p>";

// Load Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Start Laravel
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h2>1️⃣ CHECKING ENVIRONMENT</h2>";
echo "<pre>";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Laravel Version: " . app()->version() . "\n";
echo "Environment: " . config('app.env') . "\n";
echo "Debug Mode: " . (config('app.debug') ? 'ON' : 'OFF') . "\n";
echo "</pre>";

echo "<h2>2️⃣ CHECKING DATABASE CONNECTION</h2>";
try {
    $db = DB::connection();
    $db->getPdo();
    echo "<p class='success'>✅ Database connection SUCCESSFUL</p>";
    echo "<pre>";
    echo "Driver: " . $db->getDriverName() . "\n";
    echo "Database: " . $db->getDatabaseName() . "\n";
    echo "Host: " . config('database.connections.mysql.host') . "\n";
    echo "</pre>";
    
    // Test query
    $userCount = DB::table('users')->count();
    echo "<p class='success'>✅ Can query database: Found {$userCount} users</p>";
    
    // Check for citizen users
    $citizenCount = DB::table('users')->where('role', 'citizen')->count();
    echo "<p class='info'>📊 Citizen users: {$citizenCount}</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Database connection FAILED</p>";
    echo "<pre class='error'>" . $e->getMessage() . "</pre>";
}

echo "<h2>3️⃣ CHECKING SESSION CONFIGURATION</h2>";
echo "<pre>";
echo "Session Driver: " . config('session.driver') . "\n";
echo "Session Lifetime: " . config('session.lifetime') . " minutes\n";
echo "Session Path: " . config('session.files') . "\n";
echo "Session Cookie: " . config('session.cookie') . "\n";
echo "</pre>";

// Check if session directory exists and is writable
$sessionPath = storage_path('framework/sessions');
if (file_exists($sessionPath)) {
    echo "<p class='success'>✅ Session directory exists</p>";
    if (is_writable($sessionPath)) {
        echo "<p class='success'>✅ Session directory is WRITABLE</p>";
    } else {
        echo "<p class='error'>❌ Session directory is NOT writable</p>";
        echo "<pre>chmod 775 " . $sessionPath . "</pre>";
    }
    
    // Count session files
    $sessionFiles = glob($sessionPath . '/*');
    $count = count($sessionFiles);
    echo "<p class='info'>📁 Active sessions: {$count} files</p>";
    
} else {
    echo "<p class='error'>❌ Session directory DOES NOT EXIST</p>";
    echo "<pre>mkdir -p " . $sessionPath . "\nchmod 775 " . $sessionPath . "</pre>";
}

echo "<h2>4️⃣ TESTING SESSION WRITE</h2>";
try {
    session_start();
    $_SESSION['diagnostic_test'] = time();
    echo "<p class='success'>✅ Can write to session</p>";
    echo "<pre>Session ID: " . session_id() . "</pre>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Cannot write to session</p>";
    echo "<pre class='error'>" . $e->getMessage() . "</pre>";
}

echo "<h2>5️⃣ CHECKING AUTH CONFIGURATION</h2>";
echo "<pre>";
echo "Default Guard: " . config('auth.defaults.guard') . "\n";
echo "Default Provider: " . config('auth.defaults.passwords') . "\n";
echo "User Model: " . config('auth.providers.users.model') . "\n";
echo "</pre>";

// Check if we can load the User model
try {
    $userModel = config('auth.providers.users.model');
    $testUser = $userModel::first();
    if ($testUser) {
        echo "<p class='success'>✅ Can load User model</p>";
        echo "<pre>First user: " . $testUser->name . " (" . $testUser->email . ")</pre>";
    } else {
        echo "<p class='warning'>⚠️ User table is empty</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Cannot load User model</p>";
    echo "<pre class='error'>" . $e->getMessage() . "</pre>";
}

echo "<h2>6️⃣ SIMULATING CITIZEN LOGIN</h2>";
try {
    // Find a citizen user
    $citizen = DB::table('users')->where('role', 'citizen')->first();
    
    if ($citizen) {
        echo "<p class='success'>✅ Found citizen user: {$citizen->name}</p>";
        echo "<pre>";
        echo "ID: {$citizen->id}\n";
        echo "Name: {$citizen->name}\n";
        echo "Email: {$citizen->email}\n";
        echo "Role: {$citizen->role}\n";
        echo "External ID: {$citizen->external_id}\n";
        echo "</pre>";
        
        // Try to auth this user
        $user = App\Models\User::find($citizen->id);
        if ($user) {
            Auth::login($user);
            if (Auth::check()) {
                echo "<p class='success'>✅ Laravel Auth::login() SUCCESSFUL</p>";
                echo "<pre>Authenticated User ID: " . Auth::id() . "</pre>";
                echo "<pre>Authenticated User Name: " . Auth::user()->name . "</pre>";
            } else {
                echo "<p class='error'>❌ Auth::login() called but Auth::check() is FALSE</p>";
            }
        } else {
            echo "<p class='error'>❌ Cannot load User model for citizen</p>";
        }
        
    } else {
        echo "<p class='warning'>⚠️ No citizen users found in database</p>";
        echo "<p>Create a test citizen:</p>";
        echo "<pre>";
        echo "INSERT INTO users (name, email, password, role, status, created_at, updated_at) \n";
        echo "VALUES ('Test Citizen', 'test@citizen.com', '', 'citizen', 'active', NOW(), NOW());\n";
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Login simulation FAILED</p>";
    echo "<pre class='error'>" . $e->getMessage() . "</pre>";
}

echo "<h2>7️⃣ CHECKING MIDDLEWARE</h2>";
echo "<pre>";
$middlewareGroups = config('app.middleware', []);
echo "Registered Middleware:\n";
print_r($middlewareGroups);
echo "</pre>";

echo "<h2>8️⃣ RECENT LARAVEL LOGS</h2>";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = file($logFile);
    $recentLogs = array_slice($logs, -50); // Last 50 lines
    
    echo "<pre>";
    foreach ($recentLogs as $line) {
        if (stripos($line, 'error') !== false || stripos($line, 'exception') !== false) {
            echo "<span class='error'>" . htmlspecialchars($line) . "</span>";
        } elseif (stripos($line, 'citizen') !== false || stripos($line, 'sso') !== false) {
            echo "<span class='info'>" . htmlspecialchars($line) . "</span>";
        } else {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
} else {
    echo "<p class='warning'>⚠️ No log file found</p>";
}

echo "<hr><p class='info'>Diagnostic complete. Check results above for any ❌ errors.</p>";
echo "<p class='warning'>⚠️ DELETE THIS FILE after troubleshooting: rm public/diagnostic.php</p>";
echo "</body></html>";

