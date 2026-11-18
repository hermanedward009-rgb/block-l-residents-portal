# BLOCK L RESIDENTS PORTAL

Full-stack resident management platform for **10 rooms** in Block L — built with **Laravel 13**, **Tailwind CSS 4**, and **MySQL** (SQLite supported for local development).

## Features

- Registration with **admin approval** and one resident per room (optional multi-occupant per room)
- Dashboard with **live database statistics**
- Profiles, cleaning timetable, electricity payments & verification
- Advice & suggestions with comments and acknowledgements
- Announcements and **in-app + Web Push** notifications
- Administrator tools: residents, cleaning, electricity periods, moderation, PDF/CSV reports

## Requirements

- PHP 8.3+
- Composer
- Node.js 20+
- MySQL 8+ (production) or SQLite (local)

## Quick start (local)

```bash
# From project root (composer.phar included)
php composer.phar install
cp .env.example .env   # Windows: copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan webpush:vapid   # writes VAPID keys to .env
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

### Default administrator

| Field | Value |
|--------|--------|
| Email | `admin@blockl.local` |
| Password | `BlockLAdmin2026!` |

Change this password immediately after first login in production.

## MySQL configuration

In `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=block_l_portal
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

Create the database, then run `php artisan migrate --seed`.

## Web Push notifications

1. Run `php artisan webpush:vapid` to generate `VAPID_PUBLIC_KEY` and `VAPID_PRIVATE_KEY` in `.env`.
2. Residents click **Enable push alerts** in the header (browser permission required).
3. In-app notifications always work; push works on supported browsers when subscribed.

## Project structure

- `app/Http/Controllers` — resident and `Admin/*` controllers
- `app/Services/PortalNotifier.php` — in-app + push fan-out
- `database/migrations` — schema for rooms, payments, cleaning, advice, notifications
- `resources/views` — Blade UI (navy / white / grey / green accents)

## Development

```bash
php artisan serve
npm run dev
```

## Security notes

- CSRF protection on all forms
- Password hashing, role-based access (`admin` middleware)
- File uploads stored on `public` disk (`avatars/`, `receipts/`)
- Only **approved** accounts access the main portal

## Deploy to Render (one click)

[![Deploy to Render](https://render.com/images/deploy-to-render-button.svg)](https://render.com/deploy?repo=https://github.com/hermanedward009-rgb/block-l-residents-portal)

After the first deploy, set **APP_URL** in the Render dashboard to your service URL (`https://….onrender.com`), save, and redeploy.

## License

MIT — for Block L internal resident use.