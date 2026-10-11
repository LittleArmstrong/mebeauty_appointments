# Development Conventions

## Before changing code

Before implementing a change:

1. Inspect the relevant existing implementation.
2. Search for existing patterns and similar functionality.
3. Identify the affected controller/model/service/library/view/migration.
4. Check relevant tests.
5. Check `.scratch/` for an existing spec on the same feature — a `Status: resolved`
   spec means the work is already committed, so don't redo it.
6. Check the `easyappointments-ci3` skill and repository references when applicable.
7. Before changing image/logo/favicon assets, read `docs/assets.md` to verify asset roles and usages.
8. Explain the planned approach before making substantial changes.

Prefer:

- Existing project conventions
- Existing services over new business logic in controllers
- Existing `EA_*` classes over generic CI3 classes
- Existing models and helpers
- Existing API patterns
- Existing migration conventions

Do NOT:

- Introduce unnecessary abstractions.
- Introduce new dependencies unless necessary.
- Refactor unrelated code.
- Rename or restructure unrelated files.
- Modify generated files unless required.
- Change configuration or secrets without explicit approval.

## Formatting

The repository has a Prettier configuration (`.prettierrc.json`, PHP plugin) and `.editorconfig`, but
Easy!Appointments itself is not Prettier-formatted. Rules:

- **Fork-owned files** (added since the upstream base commit in `UPSTREAM.md`) must be Prettier-clean.
  `npm run check:format` checks exactly these files (CI runs it); `npm run format` formats them.
- **Upstream files are never formatted as a whole** — no Prettier/IDE "reformat file". Changes follow the
  surrounding upstream style, and pure whitespace changes against the upstream base are reverted. This keeps
  the upstream diff and future sync conflicts small (ADR 0005).

## Scope control

Only modify files necessary for the requested task.

If you discover a separate bug or improvement:

- Do not silently fix it.
- Mention it to the user.
- Ask whether it should be handled separately.

Avoid unrelated cleanup during feature work.

## Database changes

For database schema changes:

- Use the project's existing migration system.
- Follow the existing numbered migration convention.
- Inspect recent migrations before creating a new one.
- Never modify the database schema manually when a migration is appropriate.
- Do not modify existing migrations unless explicitly requested.

## API v1 changes

For API v1 changes:

- Follow the existing `Api` library conventions.
- Follow existing REST controller patterns.
- Preserve bearer-token authentication.
- Check the repository's OpenAPI definition when relevant.
- Update API documentation/specification when required by the change.

## Security

Treat the following as sensitive:

- API keys
- Access tokens
- Passwords
- OAuth credentials
- `.env` files
- Database credentials
- Private keys
- Session secrets

NEVER commit or expose secrets.

If a task requires handling credentials, ask the user when the correct secure approach is unclear.
