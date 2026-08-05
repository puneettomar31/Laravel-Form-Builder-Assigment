# Design Decisions and Assumptions

## Purpose
This document explains the architecture decisions, assumptions, Part D choices, and what remains to be finished for a production-ready AI form builder.

## Core Architecture
- Laravel 13 is used for the backend and Blade/Livewire for the frontend.
- The form builder state is stored in a JSON schema (`forms.schema`) and is the single source of truth.
- Public forms are exposed via a `public_uuid` so internal IDs cannot be enumerated.
- `form_submissions` stores JSON payloads plus search-friendly text and file metadata.
- `ai_tasks` tracks prompt history, task status, model metadata, tokens, latency, and errors.

## AI Integration
- OpenAI is used via `openai-php/client`.
- The system prompt enforces `JSON only`, a top-level `fields` array, and required field attributes.
- The app validates returned schema before saving, retries once on malformed output, and does not persist invalid JSON.
- The queued job records model, tokens, latency, and error details.

## Import Strategy
- `.docx` import uses deterministic parsing.
  - Headings become section headings.
  - Lines ending with `?` become text fields.
  - comma-separated yes/no or choice text becomes checkbox options.
- `.xlsx` import supports a typed header schema with `label` and `type`.
- If the sheet lacks headers, the first column falls back to plain text labels.
- A preview screen allows users to correct type detection before saving.

## Part D choices

### 1. Docker + Deploy-ready configuration
- User problem: reproducible local setup and easy deployment.
- Implementation: added `Dockerfile`, `docker-compose.yml`, `Procfile`, and `DEPLOY.md`.
- Trade-off: this adds infrastructure files but does not fully deploy automatically.
- With two more weeks: add CI/CD and platform-specific deploy scripts for Render/Railway.

### 2. Schema-driven validation with JSON contract
- User problem: browser validation is not enough; schema must drive server validation.
- Implementation: `FormSubmissionController` builds validation rules from `forms.schema` and rejects invalid submissions.
- Trade-off: validation rules are intentionally conservative to avoid silent failure on unsupported field type combinations.
- With more time: add conditional logic, nested sections, and advanced rule mapping for multi-field dependencies.

### 3. AI editing and auditability
- User problem: users need to update existing forms with natural-language changes.
- Implementation: `/ai` supports passing `form_id` to update an existing form draft.
- Trade-off: current AI editing is best-effort and does not yet perform diff-based patching.
- With more time: support prompt-based field updates, rollback/version history, and audit trails.

## Assumptions
- The assignment expects Laravel 10/11 compatibility; Laravel 13 is acceptable as it is backwards compatible with PHP 8.3.
- A hosted public demo is available at https://laravel-form-builder-assigment.onrender.com.
- Public forms are intentionally open for fill access; authentication is not part of the core brief.
- Import parsing is deterministic first, with AI only as a possible next step for ambiguous content.

## Known limitations
- No production authentication/authorization.
- Import parsing may misclassify ambiguous Word or Excel lines.
- There is no spam/rate-limiting protection on public submission endpoints.

## What I'd build next with two more weeks
- Add form versioning and rollback.
- Implement conditional logic / branching between sections.
- Add CI/CD and a live hosted demo URL.
- Add autosave + undo/redo in the builder.
- Add richer AI prompt tuning and multi-language form generation.
