<?php
// Simple log viewer for debugging SSO issues
// Place this in your public_html directory and access via browser

echo "<h1>Laravel SSO Debug Logs</h1>";
echo "<p>Latest log entries (refresh to see new entries):</p>";

$logPath = __DIR__ . '/storage/logs/laravel.log';

if (file_exists($logPath)) {
    $lines = file($logPath);
    $lines = array_slice($lines, -50); // Get last 50 lines
    
    echo "<div style='background: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; white-space: pre-wrap; max-height: 600px; overflow-y: auto;'>";
    
    foreach ($lines as $line) {
        if (stripos($line, 'sso') !== false || stripos($line, 'login') !== false) {
            echo "<div style='background: #fff3cd; padding: 5px; margin: 2px 0; border-radius: 3px;'>";
            echo htmlspecialchars($line);
            echo "</div>";
        } else {
            echo htmlspecialchars($line) . "\n";
        }
    }
    
    echo "</div>";
    
    echo "<br><p><strong>File size:</strong> " . number_format(filesize($logPath)) . " bytes</p>";
    echo "<p><strong>Last modified:</strong> " . date('Y-m-d H:i:s', filemtime($logPath)) . "</p>";
} else {
    echo "<p style='color: red;'>Log file not found at: $logPath</p>";
    
    // Try alternative locations
    $altPaths = [
        __DIR__ . '/storage/logs/',
        __DIR__ . '/../storage/logs/',
        __DIR__ . '/../../storage/logs/',
    ];
    
    echo "<p>Checking alternative locations:</p><ul>";
    foreach ($altPaths as $path) {
        $exists = is_dir($path) ? 'EXISTS' : 'NOT FOUND';
        echo "<li>$path - $exists</li>";
    }
    echo "</ul>";
}

echo "<br><p><a href='?' style='background: #007cba; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Refresh Logs</a></p>";
echo "<p style='color: #666; font-size: 12px;'>Auto-refresh every 10 seconds...</p>";
echo "<script>setTimeout(() => window.location.reload(), 10000);</script>";
?>
