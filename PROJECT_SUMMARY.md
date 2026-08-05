# AI-Powered Form Builder

## Project Overview

This is a Laravel + Livewire form builder assignment that includes:

- Manual form creation with field builder, inline editing, field reorder, duplicate, and delete.
- JSON schema-driven forms as the single source of truth.
- Public form fill URLs using UUIDs.
- Server-side validation derived from schema.
- Form submission storage, pagination, search, and CSV export.
- AI-powered form generation and editing using OpenAI.
- Word (`.docx`) and Excel (`.xlsx`) import with preview and editable mapping.
- Docker and deployment readiness.

## Live Demo

- Public demo: https://laravel-form-builder-assigment.onrender.com
- AI page: https://laravel-form-builder-assigment.onrender.com/ai

## What was built for the assignment

### Part A — Core Form Builder

- Added fields by click-to-add and drag-and-drop reorder.
- Implemented at least ten field types: text, textarea, number, email, phone, date, dropdown, radio, checkbox, file upload, heading, rating.
- Provided per-field configuration for label, key, placeholder, help text, default, required flag, options, and validation rules.
- JSON schema is stored in `forms.schema` and is editable via a raw JSON editor with two-way sync.
- Clean MySQL schema with indexes on `forms.slug`, `forms.public_uuid`, `form_submissions.form_id`, `form_submissions.search_text`, `ai_tasks.form_id`, and `ai_tasks.status`.
- Each form gets a public fill URL served by `public_uuid`.
- Submission validation is built from the schema on the server and not trusted from the browser.
- Stored submissions in `form_submissions`, provided search, pagination, and CSV export.

### Part B — AI Form Generation

- Added an AI page at `/ai` for prompt-based form generation and editing.
- AI generation runs as a queued job with visible task status.
- Stored AI task metadata in `ai_tasks`, including model, token count, latency, and errors.
- Implemented validation of AI JSON output and retry on malformed responses.
- Documented prompt strategy and handling of invalid schema in `README.md` and `DECISIONS.md`.

### Part C — Import from Word & Excel

- `.docx` files are parsed using deterministic logic: headings become section headings and questions become fields.
- `.xlsx` files support a typed header layout with `label`, `type`, `key`, `placeholder`, `required`, and `options` columns.
- If Excel lacks headers, the first column is parsed as plain text labels.
- Import preview screen allows editing parsed fields before saving.
- Sample import files are committed in `samples/import-sample.xlsx` and `samples/import-sample.docx`.

### Part D — Additional Notes

- Docker support is included with `Dockerfile`, `docker-compose.yml`, and `Procfile`.
- Deployment instructions are in `DEPLOY.md`.
- `README.md` contains setup steps, architecture overview, schema/ERD summary, API endpoints, prompt strategy, limitations, and live demo URL.
- `DECISIONS.md` documents assumptions, implementation choices, trade-offs, and future work.

## Project Architecture

- Laravel 13 backend with Blade and Livewire.
- `forms` table stores form metadata and JSON schema.
- `form_submissions` table stores submission data and search text.
- `ai_tasks` table tracks AI generation prompts and status.
- Livewire components manage the form builder and import preview UI.
- Vite builds frontend assets served by Laravel.
- Docker ensures consistent local and deployable environments.

## Important files

- `README.md` — setup, demo, architecture, prompt strategy, limitations.
- `DECISIONS.md` — assumptions, Part D ideas, trade-offs, next steps.
- `DEPLOY.md` — deployment instructions for Render / Railway.
- `routes/web.php` — app routes, including public fill and AI endpoints.
- `app/Http/Livewire/FormBuilder.php` — main form builder logic.
- `app/Services/AiFormGenerator.php` — AI schema generation and validation.
- `app/Services/FormImportParser.php` — Word/Excel import parsing.
- `app/Http/Controllers/FormSubmissionController.php` — server-side submission validation and storage.
- `database/migrations/2026_08_04_053754_create_forms_table.php` — form table schema.
- `database/migrations/2026_08_04_053755_create_form_submissions_table.php` — submissions schema.
- `database/migrations/2026_08_04_053756_create_ai_tasks_table.php` — AI tasks schema.

## How to explain it in an interview

1. Start with the problem statement:
   - "I built an AI-powered form builder with Laravel, Livewire, and MySQL."
   - "It supports manual form creation, AI prompt generation, import from Word/Excel, and public form filling."

2. Explain the architecture:
   - "I used a JSON schema as the single source of truth for form structure and validation."
   - "The form builder UI is Livewire-based for a reactive experience without a separate SPA."
   - "The backend stores forms, submissions, and AI tasks in MySQL."

3. Talk through each part:
   - Part A: builder features, field config, schema editor, public fill URLs, submission storage.
   - Part B: AI page, queued generation, schema validation, retry logic, task tracking.
   - Part C: deterministic Word/Excel import, preview/mapping screen, sample files.

4. Mention deployment:
   - "The project is Docker-ready and documented for Render/Railway deployment."
   - "The live demo is available at the hosted URL."

5. Be honest about limitations:
   - "I did not add production auth, spam/rate-limiting, or automated test coverage before the deadline."
   - "Import parsing is deterministic now; with more time I would add AI-assisted ambiguity handling."

## How it runs

- Manual builder: `/forms/create` and `/forms/{form}/edit`
- Public form: `/forms/{public_uuid}`
- Submissions list: `/forms/{form}/submissions`
- Import: `/import` and `/import/preview`
- AI generation: `/ai` and `/ai/generate`

## Known limitations

- No auth / authorization.
- No spam protection or rate limiting.
- Import parsing is deterministic only.
- No test suite / CI configured.

## Converting to PDF

This file is in Markdown. If you need a PDF, open `PROJECT_SUMMARY.md` in an editor and export it, or use a tool like Pandoc.
