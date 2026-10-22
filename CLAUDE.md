# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

See also `AGENTS.md` for repository conventions (naming, commit style, PR expectations).

## What this is

A Filament 3 admin panel (Laravel 12, PHP 8.3, SQLite) for editing the content of a separate [portfolio](https://github.com/alwoodm/portfolio) site. This app does **not** store portfolio content itself. It reads JSON from the portfolio's content API and posts edits back to it. The local database only holds users, admin settings, sessions/cache, and the activity log.

## Commands

```bash
composer run setup          # first-time init (.env, key, migrate, npm install, build)
composer run dev            # server + queue + pail logs + vite, concurrently
composer test               # clears config cache, then runs Pest
php artisan test --filter="test name"         # single test by name
php artisan test tests/Feature/ExampleTest.php  # single file
composer run lint           # duster lint (PHP) + eslint resources/js
composer run format         # duster fix (PHP) + prettier resources/js, resources/css
composer run format:check
npm run build               # production assets via Vite
docker-compose up --build   # production-style container (see docker/entrypoint.sh)
```

CaptainHook runs `composer lint` and `composer format:check` as a pre-commit hook (`captainhook.json`), so a commit fails if either one fails. Duster wraps Pint and other PHP linters. Use `composer run format`, not bare Pint, to match what the hook checks.

Tests use in-memory SQLite (`phpunit.xml`). The suite is currently just the example tests.

## Required env

`PORTFOLIO_URL`, `PORTFOLIO_ADMIN_TOKEN`, `PORTFOLIO_CONTENT_PREFIX` (default `/api/content`), read through `config/portfolio.php`. Content URLs are built as `{PORTFOLIO_URL}{prefix}/{file}`, for example `/api/content/projects`. Writes send the token in the `x-admin-token` header.

## Architecture

### Content editor pages (`app/Filament/Pages/*ContentPage.php`)
Each portfolio section (home, about, skills, career, projects, contact, seo) is a Filament page that extends `App\Filament\Pages\Content\BaseContentPage`. A subclass sets `$contentFile` (the remote file name), `$slug` (`content/<name>`) and the navigation metadata. It then overrides:
- `contentFormSchema()`: the Filament form schema, which must match the remote JSON shape (state path `data`).
- `prepareFormData()` / `transformPayload()`: optional hooks that reshape remote JSON into form state and back before the POST.

The base class handles fetch on `mount()`, `save()` (POST of the whole document to the same URL), notifications, the read-only state, and writing an `ActivityLog` row after a successful save. All pages share the `filament.pages.content-editor` view. To add a section, add a new subclass. Panel discovery picks it up automatically. Also add its display name to `ActivityLog::$pageNames`.

`App\Services\PortfolioApiService` duplicates part of the URL and HTTP logic. The dashboard widgets (`app/Filament/Widgets`) use it for health checks and content counts. Note that `BaseContentPage::fetchContent()` sends GET requests **without** the admin token, while the service sends the token.

### Auth and viewer mode
- Real users (`App\Models\User`) can always edit. There are no roles, because the role column was dropped in a migration. 2FA and the profile page come from the `filament-breezy` plugin, configured in `AdminPanelProvider` together with the custom `MyProfilePage`.
- **Viewer mode** is a read-only guest session that an admin turns on with the `AdminSetting.viewer_mode_enabled` toggle. This is a single-row model, accessed through `AdminSetting::current()`. `/admin/viewer` (`ViewerModeController`) logs out and sets `session('viewer_mode')`. The custom `App\Http\Middleware\FilamentAuthenticate` then injects a non-persisted `App\Support\ViewerUser` into the Filament guard in place of normal authentication.
- Permission checks rely on duck-typed methods on the user object: `canEditContent()` and `isAdmin()`, which exist on both `User` and `ViewerUser`, plus `ViewerMode::isViewer()`. Admin-only pages (Settings, Users, Profile) gate on `! ViewerMode::isViewer()`. If you add a method that the panel calls on `auth()->user()`, implement it on `ViewerUser` too.

### Routing
`/` redirects to `/admin`. Almost all UI is Filament under `/admin`. `routes/web.php` only holds the viewer-mode entry and exit routes.

### Production
`docker/entrypoint.sh` creates the SQLite file, runs `migrate --force --seed`, publishes Filament assets, caches config, routes and views, and then runs `php artisan serve` on port 8000. `AppServiceProvider` forces HTTPS in production, because the app runs behind Cloudflare. It also registers `resources/svg` as the `icon` Blade icon set.
