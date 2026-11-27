# Deployment checklist

Working branch: feature/capstone-completion. Framework: Laravel 12. PHP: 8.2 or later.

Local setup: composer install; npm ci; copy .env.example to .env if missing; php artisan key:generate; configure database; php artisan migrate; npm run build; php artisan test; php artisan serve.

Railway: select the working branch in a web service and provision a MySQL service. Configure APP_ENV=production, APP_DEBUG=false, APP_KEY (generate securely), APP_URL (actual HTTPS URL), database connection values using Railway's service references, and real SMTP settings. Set the web root to public. Use a supported PHP build/runtime, run composer install and npm ci && npm run build during build, and php artisan migrate --force as a controlled deployment step. Verify database backups, HTTPS, email, permissions and application health. Do not commit credentials. Deployment is not verified until a live URL and successful tests are recorded.
