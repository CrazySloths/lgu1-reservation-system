<?php
echo "<h1>SSO Debug Log Checker</h1>";

$logFile = __DIR__ . '/sso_debug.log';

echo "<h2>Checking for SSO Debug Log</h2>";

if (file_exists($logFile)) {
    echo "✅ Found sso_debug.log<br>";
    echo "<strong>File size:</strong> " . number_format(filesize($logFile)) . " bytes<br>";
    echo "<strong>Last modified:</strong> " . date('Y-m-d H:i:s', filemtime($logFile)) . "<br><br>";
    
    echo "<h3>Latest Log Entries (last 50 lines):</h3>";
    
    $lines = file($logFile);
    $lines = array_slice($lines, -50); // Get last 50 lines
    
    echo "<div style='background: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; white-space: pre-wrap; max-height: 600px; overflow-y: auto; font-size: 12px;'>";
    
    foreach ($lines as $lineNum => $line) {
        if (stripos($line, 'STAFF DASHBOARD') !== false || stripos($line, 'SSO REQUEST') !== false) {
            echo "<div style='background: #d4edda; padding: 3px; margin: 1px 0; border-radius: 2px;'>";
            echo htmlspecialchars($line);
            echo "</div>";
        } else {
            echo htmlspecialchars($line);
        }
    }
    
    echo "</div>";
    
    // Count recent staff dashboard requests
    $content = file_get_contents($logFile);
    $staffRequests = substr_count($content, 'STAFF DASHBOARD SSO REQUEST');
    $ssoRequests = substr_count($content, 'SSO REQUEST');
    
    echo "<h3>Summary:</h3>";
    echo "<ul>";
    echo "<li><strong>Total Staff Dashboard SSO Requests:</strong> $staffRequests</li>";
    echo "<li><strong>Total SSO Requests:</strong> $ssoRequests</li>";
    echo "</ul>";
    
    if ($staffRequests == 0) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>⚠️ Problem Found:</strong><br>";
        echo "No staff dashboard SSO requests detected. This means:<br>";
        echo "1. The external SSO system is NOT redirecting to /staff/dashboard<br>";
        echo "2. Staff users are being redirected somewhere else<br>";
        echo "3. The external SSO configuration needs to be updated<br>";
        echo "</div>";
    }
    
} else {
    echo "❌ sso_debug.log not found<br>";
    echo "This means no SSO requests have reached our Laravel application<br><br>";
    
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>⚠️ Problem:</strong><br>";
    echo "The external SSO system is not redirecting to our Laravel application at all.<br>";
    echo "Staff users are being redirected to a different URL after OTP verification.<br>";
    echo "</div>";
}

echo "<h2>Test Our Routes</h2>";
echo "<p>Test these URLs to verify our Laravel routes work:</p>";
echo "<ul>";
echo "<li><a href='/staff/dashboard?test=1' target='_blank'>Test /staff/dashboard route</a></li>";
echo "<li><a href='/sso/login?test=1' target='_blank'>Test /sso/login route</a></li>";
echo "</ul>";

echo "<h2>What to Check with Lead Programmer</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<strong>Ask your lead programmer:</strong><br>";
echo "1. Where does the SSO system redirect staff users after OTP verification?<br>";
echo "2. Is it redirecting to 'facilities.local-government-unit-1-ph.com/staff/dashboard'?<br>";
echo "3. Or is it redirecting to 'local-government-unit-1-ph.com/public/login.php'?<br>";
echo "4. The staff redirect URL needs to be changed to point to our Laravel application<br>";
echo "</div>";

echo "<br><p><a href='?' style='background: #007cba; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Refresh Check</a></p>";
?>
