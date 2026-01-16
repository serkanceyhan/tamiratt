# Tamiratt Production Deployment - Manual Steps

## 1. SSH Bağlantısı
```bash
ssh -p 2232 root@89.252.185.69
```

## 2. Proje Dizinini Bul (birini dene)
```bash
# Seçenek 1: Find ile ara
find ~ -name "tamiratt" -type d 2>/dev/null

# Seçenek 2: Yaygın yerler
ls -la ~/domains/
ls -la ~/public_html/
ls -la /home/
cd /home/tamiratt.com/public_html
```

## 3. Proje Dizinine Git (yukarıda bulduğun path)
```bash
cd /path/to/tamiratt  # Bulunan path'i kullan!
pwd  # Doğru yerde olduğunu kontrol et
```

## 4. Git Pull (test branch)
```bash
git branch  # Hangi branch'te olduğunu gör
git pull origin test
```

## 5. Migration
```bash
php artisan migrate --force
```

## 6. Seeder
```bash
php artisan db:seed --class=ServiceImageSeeder --force
```

## 7. Cache Temizle
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

## 8. Kontrol
```bash
# 22 hizmet güncellenmiş mi?
php artisan tinker --execute="echo DB::table('services')->whereNotNull('hero_image')->count();"

# Görseller var mı?
ls -la public/services/hero-images/
ls -la public/services/icons/
```

## Beklenen Sonuçlar
- ✅ 22 services updated
- ✅ Hero images dizininde 13+ JPG
- ✅ Icons dizininde 22+ SVG
