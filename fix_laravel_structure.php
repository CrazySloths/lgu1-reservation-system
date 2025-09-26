<?php
// Laravel Structure Fix Script
// This script moves Laravel's public directory contents to root
// and creates a new index.php that boots Laravel correctly

echo "<h1>Laravel Structure Fix</h1>";

$sourceDir = __DIR__ . '/public';
$targetDir = __DIR__;

echo "<h2>Step 1: Check Current Structure</h2>";

if (is_dir($sourceDir)) {
    echo "✅ Found Laravel public directory<br>";
    
    $publicFiles = scandir($sourceDir);
    echo "<strong>Files in public/:</strong><br>";
    foreach ($publicFiles as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "- $file<br>";
        }
    }
} else {
    echo "❌ Laravel public directory not found<br>";
    exit;
}

echo "<h2>Step 2: Create New index.php</h2>";

$newIndexContent = '<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define(\'LARAVEL_START\', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.\'/storage/framework/maintenance.php\')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We\'ll simply require it
| into the script here so we don\'t need to manually load our classes.
|
*/

require __DIR__.\'/vendor/autoload.php\';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application\'s HTTP kernel. Then, we will send the response back
| to this client\'s browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.\'/bootstrap/app.php\';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
';

if (file_put_contents(__DIR__ . '/index_new.php', $newIndexContent)) {
    echo "✅ Created new index.php<br>";
} else {
    echo "❌ Failed to create new index.php<br>";
}

echo "<h2>Step 3: Copy Public Assets</h2>";

$filesToCopy = ['favicon.ico', '.htaccess'];
$copiedFiles = 0;

foreach ($filesToCopy as $file) {
    $sourcePath = $sourceDir . '/' . $file;
    $targetPath = $targetDir . '/' . $file;
    
    if (file_exists($sourcePath)) {
        if (copy($sourcePath, $targetPath)) {
            echo "✅ Copied $file<br>";
            $copiedFiles++;
        } else {
            echo "❌ Failed to copy $file<br>";
        }
    } else {
        echo "⚠️ $file not found in public/<br>";
    }
}

echo "<h2>Step 4: Update .htaccess</h2>";

$htaccessContent = '<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Angular and other front-end routes...
    RewriteCond %{REQUEST_URI} ^/(.+)$
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>';

if (file_put_contents(__DIR__ . '/.htaccess_new', $htaccessContent)) {
    echo "✅ Created new .htaccess<br>";
} else {
    echo "❌ Failed to create new .htaccess<br>";
}

echo "<h2>Step 5: Manual Steps Required</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<strong>⚠️ Important: You need to manually:</strong><br>";
echo "1. Backup your current index.php<br>";
echo "2. Replace index.php with index_new.php<br>";
echo "3. Replace .htaccess with .htaccess_new<br>";
echo "4. Test your site after the changes<br>";
echo "</div>";

echo "<h2>Step 6: Verification Commands</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<strong>After making changes, test these URLs:</strong><br>";
echo "- <a href='/sso/login?test=1' target='_blank'>https://facilities.local-government-unit-1-ph.com/sso/login?test=1</a><br>";
echo "- <a href='/staff/dashboard' target='_blank'>https://facilities.local-government-unit-1-ph.com/staff/dashboard</a><br>";
echo "- <a href='/admin/dashboard' target='_blank'>https://facilities.local-government-unit-1-ph.com/admin/dashboard</a><br>";
echo "</div>";

echo "<br><p><strong>Current Status:</strong> Prepared fix files. Manual deployment needed.</p>";
?>
