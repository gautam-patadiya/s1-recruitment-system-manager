## Recruitment Management System

S1 Recruitment System Manager is an applicant tracking system built for HR and consulting companies. It manages job postings for multiple companies, candidate registrations, job applications, document uploads, and interviews with configurable stages and statuses.

The application is a Laravel API with a Vue single-page frontend, so users can move through the system without full page refreshes.

## Features

- Responsive layout
- Candidate registration and authentication
- Multi-company job posting
- Job application management
- Multi-stage interview management
- Advanced filters
- CSV and PDF export
- Secure authentication with Laravel Passport

## Requirements

- PHP 7.2.5 or higher
- Composer
- MySQL
- Node.js and npm
- Common Laravel PHP extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, and BCMath

## Installation

Install the backend dependencies:

```bash
composer install
```

Create and configure the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your local settings, especially:

```dotenv
APP_URL=http://localhost:8000
FRONT_APP_URL=http://localhost:8080/

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Run the database migrations and seeders:

```bash
php artisan migrate --seed
```

Install Laravel Passport keys and clients:

```bash
php artisan passport:install
```

Create the storage symlink and make Laravel writable directories accessible:

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

Install frontend dependencies:

```bash
cd front
npm install
```

## Running Locally

Start the Laravel API from the project root:

```bash
php artisan serve
```

By default, the backend runs at:

```text
http://localhost:8000
```

In another terminal, start the Vue development server:

```bash
cd front
npm run serve
```

By default, the frontend runs at:

```text
http://localhost:8080
```

The Vue dev server proxies API requests to `http://localhost:8000`.

Optional development helper:

```bash
php artisan ats:make-symbolic-link
```

This links `front/public` to `public/public` for Vue development assets.

## Production Build

Build the Vue frontend:

```bash
cd front
npm run build
```

The production build writes compiled assets to `public/` and updates the Laravel Blade shell at `resources/views/index.blade.php`.

For deployment, configure your web server document root to the Laravel `public/` directory.

## Default Credentials

After running `php artisan migrate --seed`, use these seeded credentials:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@admin.com | password |

Admin panel path:

```text
/home
```

Candidate landing page:

```text
/
```

## Configuration Notes

- App branding and runtime settings are stored in `public/settings.json`.
- During frontend development, default settings are also available in `front/public/settings.json`.
- `job_application_number_prefix` controls generated job application numbers.
- `user_has_to_verify_email_after_register` controls whether candidates must verify email after registration.
- Uploaded documents are stored under `storage/app/documents`.
- Mail settings in `.env` are required for email verification and password reset emails.

## License

The script is open-source licensed under the [MIT license](https://opensource.org/licenses/MIT).

[Contact me](https://www.linkedin.com/in/gautampatadiya/) for query.
