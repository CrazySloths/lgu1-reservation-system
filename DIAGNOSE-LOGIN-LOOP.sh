#!/bin/bash
# ============================================
# Diagnostic Tool for Citizen Login Loop
# ============================================

echo "🔍 CITIZEN LOGIN LOOP DIAGNOSTIC TOOL"
echo "======================================"
echo ""

cd /home/facilities.local-government-unit-1-ph.com/public_html || exit

echo "1️⃣ CHECKING SESSION CONFIGURATION..."
echo "-----------------------------------"
php artisan tinker --execute="echo 'Session Driver: ' . config('session.driver') . PHP_EOL;"
echo ""

echo "2️⃣ CHECKING SESSION DIRECTORY..."
echo "-----------------------------------"
ls -la storage/framework/sessions/ | head -10
SESSION_COUNT=$(ls -1 storage/framework/sessions/ | wc -l)
echo "Total files in sessions directory: $SESSION_COUNT"
echo ""

echo "3️⃣ CHECKING SESSION PERMISSIONS..."
echo "-----------------------------------"
ls -ld storage/framework/sessions/
echo ""

echo "4️⃣ CHECKING IF USER EXISTS IN DATABASE..."
echo "-----------------------------------"
php artisan tinker --execute="
\$user = \\App\\Models\\User::where('email', '1hawkeye101010101@gmail.com')->first();
if (\$user) {
    echo 'User FOUND in database:' . PHP_EOL;
    echo 'ID: ' . \$user->id . PHP_EOL;
    echo 'Name: ' . \$user->name . PHP_EOL;
    echo 'Email: ' . \$user->email . PHP_EOL;
    echo 'Role: ' . \$user->role . PHP_EOL;
} else {
    echo 'User NOT FOUND in database!' . PHP_EOL;
}
"
echo ""

echo "5️⃣ CLEARING OLD LOGS AND PREPARING FOR FRESH TEST..."
echo "-----------------------------------"
echo "" > storage/logs/laravel.log
echo "Laravel log cleared. File size now:"
ls -lh storage/logs/laravel.log
echo ""

echo "6️⃣ CHECKING PHP-FPM/WEB SERVER USER..."
echo "-----------------------------------"
ps aux | grep -E 'php-fpm|apache|nginx' | grep -v grep | head -5
echo ""

echo "7️⃣ CHECKING .ENV SESSION SETTINGS..."
echo "-----------------------------------"
grep SESSION .env
echo ""

echo "8️⃣ TESTING SESSION WRITE PERMISSIONS..."
echo "-----------------------------------"
TEST_FILE="storage/framework/sessions/test_$(date +%s).txt"
if touch "$TEST_FILE" 2>/dev/null; then
    echo "✅ CAN write to sessions directory"
    rm "$TEST_FILE"
else
    echo "❌ CANNOT write to sessions directory - PERMISSION PROBLEM!"
fi
echo ""

echo "======================================"
echo "🎯 NEXT STEPS:"
echo "======================================"
echo "1. Now try to LOGIN as citizen in your browser"
echo "2. After login attempt, run this command:"
echo "   tail -100 storage/logs/laravel.log | grep -A 5 -B 5 'CITIZEN'"
echo "3. Also check if new session files were created:"
echo "   ls -lt storage/framework/sessions/ | head -5"
echo ""

