#!/bin/sh
set -e

# Check if vendor folder exists, if not, the app source wasn't copied to the volume
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Initializing application..."
    
    # Copy from build stage (stored in /app-build)
    if [ -d "/app-build" ]; then
        cp -r /app-build/* /var/www/html/
        echo "Application files copied successfully"
    else
        echo "ERROR: /app-build directory not found!"
        exit 1
    fi
fi

# Set correct permissions
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Generate key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application ready!"

# Start PHP-FPM
exec php-fpm
