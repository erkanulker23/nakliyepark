#!/bin/bash
# Laravel Forge - NakliyePark Deployment Script
# Forge Site > Deployments sekmesinde bu komutları kullanın (veya Forge varsayılanı + aşağıdaki ek adımlar).
# Zero-downtime kullanıyorsanız Forge zaten $CREATE_RELEASE(), cd $FORGE_RELEASE_DIRECTORY, $ACTIVATE_RELEASE() ekler.

cd $FORGE_SITE_PATH

# Composer (production)
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# NPM & Vite build
if [ -f package.json ]; then
    npm ci
    npm run build
fi

# Laravel
$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan storage:link 2>/dev/null || true
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache
$FORGE_PHP artisan queue:restart 2>/dev/null || true

echo "NakliyePark deployment finished."
