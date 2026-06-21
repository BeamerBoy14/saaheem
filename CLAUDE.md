# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 10 fan/artist site for SAAHEEM (music content creator). No authentication, no database — purely a static-ish front-end served by Laravel. All content is file-system driven (images/videos placed in `public/photos` and `public/videos`).

## Commands

```bash
# Install dependencies
composer install
npm install

# Start dev server (Vite hot reload)
npm run dev

# Build assets for production
npm run build

# Run all tests
php artisan test

# Run a single test file
php artisan test tests/Feature/ExampleTest.php

# Lint PHP (Laravel Pint)
./vendor/bin/pint

# Fix PHP code style
./vendor/bin/pint --fix

# Clear caches
php artisan cache:clear && php artisan view:clear && php artisan config:clear
```

## Architecture

### No Controllers
All route logic lives as closures in [routes/web.php](routes/web.php). There are no dedicated controller classes — the file handles filesystem scanning, media mapping, and view data directly.

### View Structure
- `resources/views/layouts/site.blade.php` — single master layout; all pages extend it via `@yield`
- `resources/views/pages/` — one file per route (about, moments, actualites, merch)
- `resources/views/partials/` — reusable fragments included into the layout (header, cursor, lang-switch, retro-theme, dark-page-head, soon-progress)
- `resources/views/welcome.blade.php` — homepage, standalone (does not use `layouts/site`)

### i18n
Two locales: `fr` (default) and `en`. Locale is stored in the session and applied globally by `App\Http\Middleware\SetLocale`. All translatable strings live in `lang/fr/site.php` and `lang/en/site.php`. Switch locale via `GET /locale/{fr|en}`.

### Media
- `public/photos/` — images shown on the **About** page (step images) and the **Moments** gallery
- `public/videos/` — video files shown in the **Moments** gallery; `bk.mp4` is reserved as a background video and is excluded from the gallery
- Media is discovered at request time via `Illuminate\Support\Facades\File`; no database or manifest

### CSS / JS
Vanilla CSS embedded inline in Blade templates and in the layout. `resources/css/app.css` and `resources/js/app.js` are Vite entry points but hold minimal code — most styling is in the Blade files themselves. No JS framework.

### "Coming Soon" Pages
`/actualites` and `/merch` use `Route::view()` and render a progress/coming-soon partial. No backend logic.

### Key Design Token
Brand colour `--magenta: #e4007c` is defined as a CSS custom property in `layouts/site.blade.php` and reused across all partials.
