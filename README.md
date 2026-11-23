# BaryoHub

BaryoHub is a barangay information and resident services capstone project at the University of Cebu – Lapu-Lapu and Mandaue. The portal lets residents request documents, check pickup status, and read barangay announcements.

## Run BaryoHub locally on Windows

The application uses **Laravel 12** and Laravel Breeze. Install Git, Composer, PHP 8.2 or newer with the MySQL PDO driver, MySQL, and Node.js with npm. Vite requires Node.js 20.19+ on the 20.x line or 22.12+. Use `php -v` and `node -v` in PowerShell to check your versions.

1. Clone the working branch and open the project folder.

   ```powershell
   cd C:\xampp\htdocs
   git clone --branch setup/laravel-13-breeze https://github.com/uclm-school-JD/baryohub.git
   cd baryohub
   code .
   ```

2. On a fresh checkout, install dependencies and create your local configuration.

   ```powershell
   composer install
   npm.cmd ci
   Copy-Item .env.example .env
   php artisan key:generate
   ```

3. Start MySQL in XAMPP and create a database named `baryohub_laravel`. Set these values in `.env`, using the username and password for your local MySQL account.

   ```dotenv
   APP_NAME=BaryoHub
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=baryohub_laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Create the tables, build the frontend, and start the application.

   ```powershell
   php artisan migrate
   npm.cmd run build
   php artisan serve
   ```

5. Open the address printed by Artisan and register a test account. Open the verification link in `storage/logs/laravel.log` in the same browser to reach the dashboard. The default `MAIL_MAILER=log` also writes password reset links to that file. To deliver email, set `MAIL_MAILER=smtp` and configure the `MAIL_*` connection settings and sender address for your SMTP server. Set `APP_URL` to the exact address you use to open the app. Keep each teammate's `.env` outside version control.

For development, `composer dev` starts Laravel, the queue worker, and Vite. Run `composer logs` in a separate terminal on platforms with PHP's PCNTL extension. `composer test` runs the test suite with SQLite in memory and stubbed frontend assets.

## Resident services

The tables for requests, account roles, and announcements are included in the application migrations. Run `php artisan migrate` after updating an existing checkout.

| Role | Access |
| --- | --- |
| Resident | Request a barangay clearance, certificate of residency, or certificate of indigency. Track personal requests and read published barangay updates. |
| Barangay staff | Use resident services, open the office request queue, and update the status of a request. |
| Admin | Process requests, grant or revoke staff access, and publish barangay updates. |

New accounts receive resident access. Register and verify the first administrator's account, then grant admin access from the application's terminal. Replace the example with the account's email.

```powershell
php artisan baryohub:grant-admin admin@example.test
```

To check the workflow, submit a request as a resident and record its reference. Sign in as the administrator and use **Manage staff access** to grant another verified account staff access. As staff, find the reference in the **Office request queue** and mark it **Ready for pickup**. Sign back in as the resident and check **Track a request**. Administrators can publish announcements from **Publish barangay updates**.
