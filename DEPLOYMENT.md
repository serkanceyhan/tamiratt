# Production Deployment Guide

## Deployment Steps

### 1. SSH Connection
```bash
ssh user@your-server-ip
cd /path/to/project
```

### 2. Pull Latest Changes
```bash
git pull origin test
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Seed Service Images
```bash
php artisan db:seed --class=ServiceImageSeeder
```

### 5. Clear Caches
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### 6. Verify Images
Check that these directories exist and contain images:
- `/public/services/hero-images/` (13 JPG files)
- `/public/services/icons/` (22 SVG files)
- `/public/storage/2/` (before-new.jpg, after-new.jpg, etc.)

## Expected Results
✅ 22 services updated with hero images and SVG icons
✅ Homepage before/after images from local files
✅ Service page slider with local images
✅ All icons 6rem vertical layout

## Rollback (if needed)
```bash
git reset --hard HEAD~1
php artisan migrate:rollback
```
