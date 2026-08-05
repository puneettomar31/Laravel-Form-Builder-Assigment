# AI-Powered Form Builder

**Live demo:** https://laravel-form-builder-assigment.onrender.com

**AI page:** https://laravel-form-builder-assigment.onrender.com/ai

## What this project delivers

This repository implements a Laravel + Livewire form builder with:

- Manual form creation, inline editing, reorder/duplicate/delete controls
- JSON schema as the single source of truth, with raw editor sync
- Public form fill URLs using UUIDs
- Server-side schema-driven validation
- Submission storage, search, pagination, and CSV export
- Word and Excel import preview + editable mapping
- Queued AI form generation with visible task status and error logging
- Docker configuration for local development and deployment

## What still needs live deployment

- A hosted public demo URL is not yet available
- Production auth / role management is not implemented


## Setup

1. Copy `.env.example` to `.env` and generate the app key:

```bash
cp .env.example .env
php artisan key:generate
```

2. Install dependencies:

```bash
composer install
npm install
npm run build
```

3. Configure `.env` for MySQL and queue:

```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_form_builder
DB_USERNAME=root
DB_PASSWORD=root
QUEUE_CONNECTION=database
OPENAI_API_KEY=your_openai_api_key
OPENAI_MODEL=gpt-4o-mini
FILESYSTEM_DISK=public
```

4. Run migrations and seeders:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

5. Start local services:

```bash
php artisan serve --host=127.0.0.1 --port=8000
php artisan queue:work
```

## Docker local launch

```bash
docker compose up --build
```

This will migrate the database, seed the sample contact form, create the storage link, and start the app on `http://localhost:8000`.

## Deployment plan

This project is ready to deploy on Render or Railway. The recommended approach is:

1. Push the repository to GitHub.
2. Create a new Web Service on Render or Railway.
3. Set the build commands:

```bash
composer install --no-interaction --optimize-autoloader
npm install
npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

4. Set the start command:

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

5. Add these environment variables:

- `APP_URL` set to the deployed URL
- `DB_CONNECTION=mysql`
- `DB_HOST` and `DB_PORT` from the managed MySQL service
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `QUEUE_CONNECTION=database`
- `OPENAI_API_KEY`
- `OPENAI_MODEL=gpt-4o-mini`
- `FILESYSTEM_DISK=public`

6. Add a worker service with the command:

```bash
php artisan queue:work --sleep=3 --tries=3
```

7. Use `storage:link` in deployment either on build or startup so uploaded files are served correctly.

If a live demo URL is not available yet, the app is deploy-ready with documented steps and Docker support.

## Usage

- `/` — list saved forms
- `/forms/create` — build a new form manually
- `/import` — upload `.docx` or `.xlsx` and preview import
- `/ai` — queue AI generation and see task status
- public fill link on each form card
- submissions page with search and CSV export

## Supported field types

- text, textarea, number, email, phone, url, date
- dropdown, radio, checkbox, file upload, heading, rating

## Import formats

### Excel import

Use an `.xlsx` file with a header row containing at least:

- `label`
- `type`

Optional columns:

- `key`
- `placeholder`
- `required` (`true`/`false` or `yes`/`no`)
- `options` (`Option A|Option B|Option C`)

### Word import

- Headings in `.docx` are parsed as section headings
- Questions ending in `?` become text fields
- Yes/no or comma-separated choice text becomes checkbox options

## AI prompt strategy

- System prompt enforces `JSON only`, a top-level `fields` array, and field contract
- The app validates AI output before saving
- If the AI response is invalid, it retries once and requests valid JSON again
- `AiTask` records `model`, `tokens`, `latency_ms`, and errors

## Database model summary

- `forms`: stores title, slug, description, `public_uuid`, JSON `schema`, status, timestamps
- `form_submissions`: stores form_id, JSON `submission_data`, `search_text`, user metadata, file paths, timestamps
- `ai_tasks`: tracks queued AI prompt requests, status, schema output, model, tokens, latency, and errors

## Indexes and performance

- `forms.slug` and `forms.public_uuid` are unique
- `forms.status` is indexed for published form filtering
- `form_submissions.form_id` and `search_text` are indexed for fast lookup
- `ai_tasks.form_id` and `ai_tasks.status` are indexed for background task monitoring

## Sample files

- `samples/import-sample.xlsx`
- `samples/import-sample.docx`

## Available commands

```bash
php artisan migrate
php artisan db:seed
php artisan queue:work
npm run build
docker compose up --build
```

## Known limitations

- No hosted live demo URL in this repo yet
- No production authentication or authorization
- Import parsing is deterministic and may need stronger AI-assisted ambiguity handling
- No rate-limiting / spam protection for public forms

## Deliverables

- `README.md` with setup, architecture, AI strategy, and limitations
- `DECISIONS.md` with assumptions, trade-offs, and Part D ideas
- `database/seeders` and `database/dump.sql`
- `samples/` with import examples
- `Dockerfile`, `docker-compose.yml`, `Procfile`, and `DEPLOY.md`

## Handoff guidance

Deliver this repository as the deployment-ready source package. Include:

- the full project source code
- `README.md`, `DEPLOY.md`, and `DECISIONS.md`
- sample import files in `samples/`
- database migrations and seeders

If the client needs a live demo, note that the app is deploy-ready but does not yet have a public hosted URL.

## License

MIT

