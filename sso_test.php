<?php
// Simple SSO test page to verify if our Laravel route is working
// Place this in your public_html directory

echo "<h1>SSO Route Test</h1>";

// Test if we can reach the SSO route directly
$ssoTestUrl = 'https://facilities.local-government-unit-1-ph.com/sso/login?user_id=1&username=Staff-Facilities123&role=staff&sig=test123&ts=' . time();

echo "<h2>Testing SSO Route</h2>";
echo "<p><strong>Test URL:</strong></p>";
echo "<code style='background: #f5f5f5; padding: 10px; display: block; word-break: break-all;'>$ssoTestUrl</code>";

echo "<p><a href='$ssoTestUrl' target='_blank' style='background: #007cba; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Test SSO Route</a></p>";

// Check Laravel application status
echo "<h2>Laravel Application Check</h2>";

$publicPath = __DIR__ . '/public';
$indexPath = __DIR__ . '/public/index.php';

if (file_exists($indexPath)) {
    echo "✅ Laravel public/index.php found<br>";
} else {
    echo "❌ Laravel public/index.php NOT FOUND<br>";
    echo "Looking for index.php in current directory...<br>";
    if (file_exists(__DIR__ . '/index.php')) {
        echo "✅ Found index.php in root directory<br>";
    } else {
        echo "❌ No index.php found<br>";
    }
}

// Check storage directory
$storagePaths = [
    __DIR__ . '/storage',
    __DIR__ . '/storage/logs',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../../storage/logs',
];

echo "<h3>Storage Directory Check</h3>";
foreach ($storagePaths as $path) {
    if (is_dir($path)) {
        echo "✅ Found: $path<br>";
        if (is_writable($path)) {
            echo "&nbsp;&nbsp;&nbsp;📝 Writable<br>";
        } else {
            echo "&nbsp;&nbsp;&nbsp;🔒 NOT Writable<br>";
        }
        
        // List files if it's a logs directory
        if (strpos($path, 'logs') !== false) {
            $files = glob($path . '/*');
            if ($files) {
                echo "&nbsp;&nbsp;&nbsp;📁 Files: " . implode(', ', array_map('basename', $files)) . "<br>";
            } else {
                echo "&nbsp;&nbsp;&nbsp;📁 No files<br>";
            }
        }
    } else {
        echo "❌ Not found: $path<br>";
    }
}

// Check .env file
echo "<h3>Environment Check</h3>";
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    echo "✅ .env file found<br>";
    $envContent = file_get_contents($envPath);
    if (strpos($envContent, 'APP_URL=') !== false) {
        preg_match('/APP_URL=(.*)/', $envContent, $matches);
        echo "&nbsp;&nbsp;&nbsp;🌐 APP_URL: " . trim($matches[1]) . "<br>";
    }
    if (strpos($envContent, 'LOG_CHANNEL=') !== false) {
        preg_match('/LOG_CHANNEL=(.*)/', $envContent, $matches);
        echo "&nbsp;&nbsp;&nbsp;📋 LOG_CHANNEL: " . trim($matches[1]) . "<br>";
    }
} else {
    echo "❌ .env file NOT FOUND<br>";
}

// Create a simple log test
echo "<h3>Manual Log Test</h3>";
$testLogPath = __DIR__ . '/sso_debug.log';

$logEntry = "[" . date('Y-m-d H:i:s') . "] SSO Test Page Accessed\n";
if (file_put_contents($testLogPath, $logEntry, FILE_APPEND | LOCK_EX)) {
    echo "✅ Successfully wrote to: $testLogPath<br>";
    if (file_exists($testLogPath)) {
        echo "&nbsp;&nbsp;&nbsp;📄 File size: " . filesize($testLogPath) . " bytes<br>";
        echo "&nbsp;&nbsp;&nbsp;📄 Content: " . htmlspecialchars(file_get_contents($testLogPath)) . "<br>";
    }
} else {
    echo "❌ Failed to write to: $testLogPath<br>";
}

echo "<h3>Current Directory Contents</h3>";
$files = scandir(__DIR__);
echo "<ul>";
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $isDir = is_dir(__DIR__ . '/' . $file) ? ' [DIR]' : '';
        echo "<li>$file$isDir</li>";
    }
}
echo "</ul>";

echo "<br><p><a href='?' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Refresh Check</a></p>";
?>
