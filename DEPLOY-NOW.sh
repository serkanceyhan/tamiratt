# Production Deployment Commands
# Project Path: /home/test.tamiratt.com/public_html

# 1. Navigate to project
cd /home/test.tamiratt.com/public_html

# 2. Check current status
git branch
git status

# 3. Pull latest changes from test branch
git pull origin test

# 4. Run migrations
php artisan migrate --force

# 5. Seed service images (22 services)
php artisan db:seed --class=ServiceImageSeeder --force

# 6. Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# 7. Verify deployment
echo "Checking updated services..."
php artisan tinker --execute="echo 'Services with hero_image: ' . DB::table('services')->whereNotNull('hero_image')->count() . PHP_EOL;"

echo "Checking image files..."
ls -la public/services/hero-images/ | wc -l
ls -la public/services/icons/ | wc -l

echo "Deployment complete!"
