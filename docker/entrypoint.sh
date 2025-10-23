#!/bin/sh

set -e

echo "🚀 Starting Waste2Product Laravel Application..."

# Wait for MySQL to be ready (simple sleep)
echo "⏳ Waiting for MySQL to initialize..."
sleep 10
echo "✅ Continuing with startup!"

# Create storage link if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

# Set proper permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear and cache configs
echo "🧹 Clearing and caching configurations..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production (if APP_ENV is production)
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Run migrations (optional - uncomment if you want auto-migration)
# echo "📊 Running migrations..."
# php artisan migrate --force

echo "✨ Application ready!"

# Execute the main command (supervisord)
exec "$@"
