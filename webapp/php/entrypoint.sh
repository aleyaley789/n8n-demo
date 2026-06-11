#!/bin/sh
set -e

if [ ! -f /var/www/html/vendor/autoload.php ]; then
    echo "==> Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --no-progress --working-dir=/var/www/html
fi

chmod -R 777 /var/www/html/var 2>/dev/null || mkdir -p /var/www/html/var && chmod -R 777 /var/www/html/var

echo "==> Running database migrations..."
php /var/www/html/bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

exec "$@"
