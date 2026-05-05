# AI Agent Guidance for laravel13-mhc9-erp

## Project Summary
- Laravel 13 application using PHP 8.3.
- Includes Laravel Passport (OAuth) and Sanctum (SPA auth) for authentication.
- **Database: MySQL** (not SQLite — configured for `mysql5-phpmyadmin-db-1:3306`).
- Frontend: Vite + Tailwind + Alpine.js + Inertia.js.

## Key commands
- `npm run setup` — install dependencies, create `.env`, generate key, migrate, build assets.
- `npm run dev` — start: `php artisan serve`, queue listener, `php artisan pail`, and Vite.
- `npm run test` — run PHPUnit via `php artisan test`.
- `vendor/bin/pint` — run Laravel Pint code style checks.
- `php artisan passport:install` — required after setup to configure OAuth keys.

## Known issues (avoid these)
- **"Table already exists" migrations**: If migrations fail, drop tables first: `php artisan migrate:fresh` or manually drop affected tables in MySQL, then re-migrate.
- **Vite manifest missing**: Always run `npm run build` before serving pages. Error: `Vite manifest not found at /public/build/manifest.json`.
- **Passport OAuth errors**: Run `php artisan passport:install` after first `composer install`. Missing bindings cause "AuthorizationViewResponse not instantiable" and "Class App\Models\Passport\Client not found".

## Important files
- `routes/api.php` — API routes.
- `app/Http/Controllers/` — controllers.
- `app/Models/` — Eloquent models.
- `.env` — database config (MySQL), not SQLite.
- `database/` — migrations, seeders (SQLite fallback at `database/database.sqlite`).

## Development conventions
- Use `routes/api.php` for API endpoints; keep controllers thin.
- Apply code style: `vendor/bin/pint` before committing.
- When testing OAuth flows, ensure Passport keys exist in `.env`: `PASSPORT_PRIVATE_KEY`, `PASSPORT_PUBLIC_KEY`.

## References
- See `GEMINI.md` for architecture details.
- See `composer.json` scripts and `package.json` for tooling.