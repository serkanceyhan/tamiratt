#!/bin/bash
# Tamiratt Production Deployment Script
# Run this after SSH connection: ssh -p 2232 root@89.252.185.69

echo "=== Starting Deployment ==="

# Step 1: Find project directory
echo "Step 1: Finding project directory..."
cd ~ && find . -name "tamiratt" -type d 2>/dev/null | head -5
# Or try common paths:
# cd /home/tamiratt.com
# cd /root/tamiratt.com
# cd ~/domains/tamiratt.com/public_html

# Step 2: Navigate to project (EDIT THIS PATH!)
PROJECT_PATH="/path/to/tamiratt"  # UPDATE THIS!
cd $PROJECT_PATH || exit

# Step 3: Check current branch
echo "Step 2: Current git status..."
git branch
git status

# Step 4: Pull latest changes from test branch
echo "Step 3: Pulling latest changes..."
git pull origin test

# Step 5: Run migrations
echo "Step 4: Running migrations..."
php artisan migrate --force

# Step 6: Seed service images
echo "Step 5: Seeding service images..."
php artisan db:seed --class=ServiceImageSeeder --force

# Step 7: Clear caches
echo "Step 6: Clearing caches..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# Step 8: Verify
echo "Step 7: Verification..."
echo "Checking services table..."
php artisan tinker --execute="echo 'Services with images: ' . DB::table('services')->whereNotNull('hero_image')->count() . PHP_EOL;"

echo "Checking image directories..."
ls -la public/services/hero-images/ | head -10
ls -la public/services/icons/ | head -10

echo "=== Deployment Complete! ==="
