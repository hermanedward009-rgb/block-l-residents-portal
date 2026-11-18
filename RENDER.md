# Deploy Block L to Render

Your project is **Docker-ready** for [Render](https://render.com) with a free **PostgreSQL** database.

## What you get on Render

- Public **HTTPS URL** (e.g. `https://block-l-residents-portal.onrender.com`)
- **Postgres** database (migrations + seeder run on each deploy)
- Default **admin** after first deploy: `admin@blockl.local` / `BlockLAdmin2026!`

## Steps (one-time)

### 1. Put the code on GitHub

Render deploys from Git. From this folder:

```bash
git init
git add .
git commit -m "Block L portal for Render"
```

Create a repo on GitHub, then:

```bash
git remote add origin https://github.com/YOUR_USER/block-l-portal.git
git push -u origin main
```

(Use your real GitHub username and repo name.)

### 2. Create the stack on Render

1. Open [dashboard.render.com](https://dashboard.render.com)
2. **New** → **Blueprint**
3. Connect your **GitHub** account and select the repo
4. Render reads `render.yaml` and creates:
   - Web service (Docker)
   - PostgreSQL database
5. After deploy starts, open the **web service** → **Environment**
6. Set **APP_URL** to your service URL, e.g.  
   `https://block-l-residents-portal.onrender.com`  
   (exact name appears on Render)
7. **Save** and trigger **Manual Deploy** (or wait for redeploy)

### 3. First login

- URL: your Render service URL + `/login`
- Email: `admin@blockl.local`
- Password: `BlockLAdmin2026!`

Change the admin password after first login when you add that feature.

## Notes

- **Free tier** web services **sleep** when idle; first load may take 30–60 seconds.
- **Uploads** (avatars, receipts) on free tier use ephemeral disk; for production, add **Render Disk** or external storage (S3/Blob).
- **VAPID** keys for push: add `VAPID_PUBLIC_KEY` / `VAPID_PRIVATE_KEY` in Render env if you enable web push.

## Without Blueprint (manual)

1. **New** → **PostgreSQL** (free) → note **Internal Database URL**
2. **New** → **Web Service** → connect repo → **Runtime: Docker**
3. Add env: `DATABASE_URL`, `DB_CONNECTION=pgsql`, `APP_KEY` (from `php artisan key:generate --show` locally), `APP_URL`

## Local Docker test (optional)

```bash
docker build -t block-l .
docker run -p 8080:10000 -e PORT=10000 -e APP_KEY=base64:... -e DATABASE_URL=... block-l
```