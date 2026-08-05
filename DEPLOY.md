# Deployment Guide

## Local Deployment

1. Copy environment variables:

```bash
cp .env.example .env
php artisan key:generate
```

2. Configure `.env`:

- `APP_URL=http://localhost:8000`
- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=laravel_form_builder`
- `DB_USERNAME=root`
- `DB_PASSWORD=`
- `QUEUE_CONNECTION=database`
- `OPENAI_API_KEY=your-openai-key`
- `OPENAI_MODEL=gpt-4o-mini`

3. Install dependencies:

```bash
composer install
npm install
npm run build
```

4. Run migrations and storage link:

```bash
php artisan migrate
php artisan storage:link
```

5. Start app and queue worker:

```bash
php artisan serve --host=127.0.0.1 --port=8000
php artisan queue:work
```

## Recommended Live Deploy Targets

- Render
- Railway
- DigitalOcean App Platform
- Azure App Service

## Deployment Strategy

The fastest path to a live demo is Render or Railway because they can host both the web service and a worker service with a managed MySQL database.

### Key steps

1. Push the repository to GitHub.
2. Create a new Web Service for the Laravel app.
3. Use a managed MySQL add-on and configure DB credentials.
4. Set the build commands:

```bash
composer install --no-interaction --optimize-autoloader
npm install
npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

5. Set the start command:

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

6. Add a separate queue worker service with:

```bash
php artisan queue:work --sleep=3 --tries=3
```

7. Make sure environment variables are configured and real API keys are never committed.

## Render Deployment Example

1. Create a new Web Service in Render.
2. Connect with GitHub repository.
3. Set build commands:

```bash
composer install --no-interaction --optimize-autoloader
npm install
npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

4. Set start command:

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

5. Add environment variables in Render dashboard.
   - If using Supabase or another managed Postgres provider, set `DATABASE_URL` with the full Postgres connection string.
   - Do not leave `DB_CONNECTION=mysql` hard-coded when the database URL is Postgres.
   - Ensure the runtime image supports `pdo_pgsql`.
6. Add a separate Worker service with start command:

```bash
php artisan queue:work --sleep=3 --tries=3
```

## Railway / DigitalOcean

Same concept: web service + queue worker + MySQL database + storage link.

## Important Notes

- Do not commit real API keys.
- Use `.env.example` only.
- Ensure `QUEUE_CONNECTION=database` for queued AI tasks.
- Ensure `storage/app/public` is writable.
