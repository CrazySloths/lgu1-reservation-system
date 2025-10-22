#!/bin/bash
# ============================================
# Deploy Citizen Login Loop Fix to Production
# ============================================

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /home/crazysloths/public_html/facilities || exit

echo "📥 Pulling latest changes from fix branch..."
git pull origin fix

echo "🗂️ Creating sessions directory..."
mkdir -p storage/framework/sessions

echo "🔓 Setting permissions..."
chmod -R 775 storage/framework/sessions
chown -R crazysloths:crazysloths storage/framework/sessions

echo "🧹 Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "✅ Deployment complete!"
echo ""
echo "🧪 Test citizen login:"
echo "1. Clear browser cookies"
echo "2. Go to: https://local-government-unit-1-ph.com/public/login.php"
echo "3. Login as citizen"
echo "4. Should stay on citizen dashboard (no loop)"

