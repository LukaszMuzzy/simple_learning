#!/bin/sh
set -e

# Fix permissions
chown -R www:www /var/www/storage /var/www/bootstrap/cache /var/www/.env 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Run as www user
exec su-exec www "$@"
