#!/bin/sh

# ponytail: dynamic port binding for Render.com using native sed
if [ -n "$PORT" ]; then
    sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/*.conf /etc/apache2/ports.conf
fi

# Create storage directories if they do not exist
mkdir -p /var/www/html/storage/app/public/sponsors /var/www/html/storage/app/public/posters /var/www/html/storage/app/public/documents

# Ensure physical public/storage symlink is removed so Apache passes media requests to Laravel
rm -rf /var/www/html/public/storage

# Set directory permissions to 755 and file permissions to 644
find /var/www/html/storage -type d -exec chmod 755 {} +
find /var/www/html/storage -type f -exec chmod 644 {} +
find /var/www/html/bootstrap/cache -type d -exec chmod 755 {} +

# Ensure web server user (www-data) owns storage, public, and bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/public /var/www/html/bootstrap/cache

# Run database migrations & clear view cache automatically on every Docker deployment
echo "Running automatic database migrations..."
php artisan migrate --force

echo "Clearing view cache..."
php artisan view:clear

# Start Apache in the foreground
exec apache2-foreground
