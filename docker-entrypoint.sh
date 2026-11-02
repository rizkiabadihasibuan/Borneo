#!/bin/bash
set -e

# Sesuaikan port Apache dengan variabel $PORT dari cloud provider (misal: Render/Railway)
if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
fi

# Jalankan storage link jika belum ada
php artisan storage:link || true

# Jalankan cache konfigurasi
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Jalankan migrasi dan seeding database jika database terhubung
php artisan migrate --force || true
php artisan db:seed --force || true

# Jalankan Apache di foreground
exec apache2-foreground
