# Nakuru Schools — Website & Admin CMS

Public school website plus an `/admin` content-management panel, built with
**Laravel 11 · Blade · Bootstrap 5 (SCSS) · MySQL**. All frontend content
(sliders, about, news, gallery, resources, FAQs, contact details, social links,
homepage text, SEO, logo) is managed from the admin panel.

## Requirements

- PHP 8.2+ with `pdo_mysql`, `mbstring`, `fileinfo`, `gd` (for image resizing)
- Composer 2, Node.js 18+
- MySQL 8 / MariaDB 10.4+

## Setup

```bash
composer install
npm install
cp .env.example .env          # set DB_* and ADMIN_EMAIL / ADMIN_PASSWORD
php artisan key:generate
php artisan migrate
php artisan db:seed           # admin user + sample content with placeholder images/PDFs
php artisan storage:link
npm run build                 # or `npm run dev` while developing
php artisan serve
```

> **Legacy migrations.** `database/migrations/2024_01_01_*` belong to an earlier
> build and conflict with the current schema. Delete them before running
> `php artisan migrate` on a fresh database. Until then, migrate with explicit
> paths (framework `0001_*` files plus the `2026_*` files). The test suite does
> this automatically; see `tests/TestCase.php`.

## Signing in

`/login` → `/admin/dashboard`

The seeder creates **System Administrator** using `ADMIN_EMAIL` and
`ADMIN_PASSWORD` from `.env` (fallback: `admin@example.com` / `ChangeMe@2026`).
These are development credentials. **Change the password after the first sign-in**
(top-right menu → My Profile) and never commit real credentials.

## Replacing placeholder content

Seeded images are stylised SVG landscapes and the documents are simple sample PDFs.
Before going live, replace them:

| What | Where in admin |
|---|---|
| Logo, favicon, tagline, homepage welcome & CTA text, SEO defaults, share image | Settings |
| Phone, email, address, office hours, Google Map | Contact Information |
| Facebook / Instagram / X / YouTube / TikTok / LinkedIn / WhatsApp | Social Media |
| Hero slides | Sliders |
| Introduction, mission, vision, core values, history, extra sections | About Us |
| “Why Choose Us” cards and key figures (e.g. 1,200+ learners) | Why Choose Us |
| Articles, photos, downloadable documents, FAQs | News · Gallery · Resources · FAQs |

## Architecture notes

- **Routes:** `routes/web.php` (public + login) and `routes/admin.php`, which is
  loaded under the `/admin` prefix with `auth` and `active` middleware.
- **Site-wide data** (settings, contact details, social links) is served by
  `App\Services\SiteData`. It is cached and flushed automatically when those
  models change.
- **Uploads:** images are validated, renamed randomly and downscaled or re-encoded
  by `App\Services\ImageService` (gallery images also get thumbnails). Resource
  documents are stored on the **private** `local` disk and streamed through
  `/resources/{slug}/download`, so they are never directly reachable.
- **Rich text** (CKEditor 5, loaded only on admin pages that use it) is cleaned
  with an allow-list by `App\Services\HtmlSanitizer` before saving.
- **Security:** CSRF on all forms, login throttling, inactive users are signed
  out, users cannot deactivate or delete themselves (`UserPolicy`), the contact
  form is rate-limited and has a spam honeypot, and passwords are hashed through
  the model’s `hashed` cast.
- **Brand colours** live in `resources/scss/_brand.scss`. Both stylesheets and
  the Bootstrap theme colours derive from them.
- Lazy loading is disabled outside production, so N+1 queries fail loudly during
  development.

## Tests

```bash
php artisan test
```

The tests use a separate MySQL database, `nakuru_schools_testing` (see
`phpunit.xml`). Create it once before the first run.
