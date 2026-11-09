# Project Overview: laravel13-mhc9-erp

This is a fresh **Laravel 13** project named **mhc9-erp**. It is intended to be part of the MHC9 system.

## 🏗️ Architecture & Core Technologies
- **Framework:** Laravel 13.0
- **PHP Version:** 8.3
- **Database:** SQLite (default for development)
- **Testing:** PHPUnit 12.5 (Default)

## 🚀 Building and Running

### Prerequisites
- PHP 8.3+
- Composer (via Docker)
- Node.js & NPM

### Key Commands
- **Setup Project:** `npm run setup` (Installs dependencies, generates APP_KEY, runs migrations, and builds assets)
- **Start Development Server:** `npm run dev` (Runs Artisan serve, Vite, and Pail concurrently)
- **Run Tests:** `npm run test` or `php artisan test`
- **Linting:** `vendor/bin/pint` (Laravel Pint for code style)

## 🛠️ Development Conventions

### API Implementation
- **Routing:** API routes are defined in `routes/api.php`.
- **Controllers:** Business logic is located in `app/Http/Controllers/`.
- **Formatting:** Adheres to Laravel's standard naming conventions. Code style is managed by **Laravel Pint**.

### Testing
- Tests are located in the `tests/` directory.
- **PHPUnit** is currently the default testing framework.

## 📁 Key File Map
- `routes/api.php`: API endpoint definitions.
- `app/Http/Controllers/`: Request handling logic.
- `app/Models/`: Eloquent models.
- `composer.json`: Project dependencies and automation scripts.
