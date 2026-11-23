# BaryoHub

BaryoHub is a barangay information and resident services capstone project at the University of Cebu – Lapu-Lapu and Mandaue. Its resident portal helps community members request barangay documents and track when they are ready for pickup.


[Browse the Laravel application source](https://github.com/uclm-school-JD/baryohub/tree/setup/laravel-13-breeze)

## Run BaryoHub locally on Windows

The `setup/laravel-13-breeze` branch uses **Laravel 12** and Laravel Breeze. Its Composer configuration requires PHP `^8.2`. Install Git, Composer, Node.js with npm, and MySQL. Use `php -v` in the VS Code terminal to check which PHP installation PowerShell is using.

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

5. Open the address printed by Artisan. Register a test account, sign in, and open the dashboard. Each teammate should keep their own local `.env` outside version control.

## Resident Services add-on

The working branch provides the Breeze dashboard and profile routes. To enable document requests, tracking, and office roles, integrate the BaryoHub Resident Services add-on using its included `START-HERE.md`. The guide explains how to connect the routes, apply the migrations, and assign the first administrator to an existing account.

| Role | Access after installing the add-on |
| --- | --- |
| Resident | Request a barangay clearance, certificate of residency, or certificate of indigency. Track personal requests and read published barangay updates. |
| Barangay staff | Use resident services, open the office request queue, and update the status of a request. |
| Admin | Process requests and use **Manage staff access** to grant or revoke staff privileges. |

New accounts receive resident access. To check the workflow, submit a request as a resident and record its number. Sign in as staff, find the same number, and mark it **Ready for pickup**. Sign back in as the resident and confirm the status under **Track a request**. Only admins should be able to open **Manage staff access**.
