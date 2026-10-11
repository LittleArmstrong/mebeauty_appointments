---
name: easyappointments-ci3
description: Development knowledge for mebeauty_appointments, an Easy!Appointments 1.6.0 fork on CodeIgniter 3.1.11. Use when implementing, debugging or reviewing PHP code, settings, migrations, API v1 endpoints, availability/booking logic or views in this repository. Repository code, EA_* overrides and the fork's ADRs take precedence over generic Easy!Appointments or CodeIgniter documentation.
---

# Easy!Appointments 1.6.0 / CodeIgniter 3 — mebeauty_appointments

This fork runs Easy!Appointments 1.6.0 on CodeIgniter 3.1.11 (PHP ≥ 8.2). **The code in this repository is the source of truth.** The bundled CodeIgniter 3 documentation describes the generic framework; this repo overrides many stock classes via `EA_*` classes in `application/core/`, so check the code before assuming stock behaviour.

## Fork rules first

Before changing code, read what applies:

- `AGENTS.md` — validation commands, commit checkpoints, where docs live.
- `docs/agents/development-conventions.md` — scope, migrations, API, security.
- `docs/agents/git-workflow.md` — branches (`staging` → PR → `main`), never push/merge without request.
- `CODING_STANDARDS.md` — brand values from settings, asset roles.
- `GLOSSARY.md` — domain vocabulary (Buchungsfrist, Umbuchungs-/Stornierungsfrist, Pufferzeit, Arbeitsende, Manage-Link, Theme, Site-Chrome …).
- `docs/adr/` — fork decisions, notably 0002/0003 (deadlines, buffer, slot capacity), 0005 (upstream sync, migration numbering), 0006 (site chrome per theme), 0007 (PHP versions).
- `.scratch/INDEX.md` — specs and tickets (schema in `docs/agents/issue-tracker.md`).

All PHP and Composer commands run inside the `php-fpm` container (`docker compose exec -T php-fpm …`); the host `php` is a shim.

## Where things live

| Concern | Location | Notes |
|---|---|---|
| Controllers | `application/controllers/` | Extend `EA_Controller`; thin — business logic belongs in libraries/models |
| API v1 | `application/controllers/api/v1/*_api_v1.php` | 13 controllers, bearer token via the `Api` library; document changes in `openapi.yml` |
| Models | `application/models/` | Extend `EA_Model`; `validate()` throws `InvalidArgumentException` |
| Business logic | `application/libraries/` | e.g. `Availability`, `Notifications`, `Synchronization`, `Email_messages`, `Api`. There is **no** separate service layer directory. |
| Helpers | `application/helpers/` | Procedural, loaded globally (`setting()`, `lang()`, `e()`, `request()`, …) |
| Core overrides | `application/core/EA_*.php` | `EA_Controller`, `EA_Model`, `EA_Migration`, `EA_Router`, `EA_Email`, … |
| Migrations | `application/migrations/NNN_name.php` | Gapless sequence (currently 001–070); see ADR 0005 |
| Views | `application/views/{layouts,pages,components,emails,errors}` | `extend()`, `section()`, `slot()`, `component()` |
| Frontend | `assets/js/`, `assets/css/` (SCSS, built by gulp) | Themes in `assets/css/themes/<theme>.scss` |
| Tests | `tests/Unit/` (PHPUnit), `tests/smoke/*.sh` (HTTP smoke against the running site) | |

## Verified patterns

**Request handling in controllers** (`application/helpers/http_helper.php`):

```php
public function save(): void
{
    try {
        method('post');                                   // abort unless POST

        if (cannot('edit', PRIV_SYSTEM_SETTINGS)) {       // permission_helper: can/cannot(action, resource, ?user_id)
            throw new RuntimeException('You do not have the required permissions for this task.');
        }

        check('booking_settings', 'array|null');          // type check of a request value
        $settings = request('booking_settings', []);

        // … work …

        response();                                       // empty 200
    } catch (Throwable $e) {
        json_exception($e);                               // JSON error response
    }
}
```

`json_response(array $content = [], int $status = 200)` returns JSON; `abort(int $code, string $message = '')` stops with an HTTP error.

**Models** (`application/core/EA_Model.php`): `save()` → `validate()` → `insert()`/`update()`; `find($id)`, `get($where, $limit, $offset, $order_by)`, plus `cast(&$record)`, `only(&$record, $fields)`, `optional(&$record, $fields)`, and API field mapping via `$api_resource` / `api_encode()` / `api_decode()` in each model.

**Settings** (`application/helpers/setting_helper.php`): `setting('name', $default)` reads one value (one DB query per call); `setting(['name' => $value])` writes through `Settings_model::save()`. Every write — backend pages and the settings API — passes `Settings_model::validate()`.

**New setting** — add a numbered migration with the next free number (ADR 0005):

```php
class Migration_Add_my_setting extends EA_Migration
{
    public function up(): void
    {
        if (!$this->db->get_where('settings', ['name' => 'my_setting'])->num_rows()) {
            $this->db->insert('settings', ['name' => 'my_setting', 'value' => '1']);
        }
    }

    public function down(): void
    {
        $this->db->delete('settings', ['name' => 'my_setting']);
    }
}
```

Then expose it on the matching settings page (`data-field="my_setting"`), add translations (`application/language/{german,english}/translations_lang.php`) and validate the value server-side. `composer check:migrations` validates numbering and class names.

**API v1 endpoint** — mirror an existing controller: `$this->load->library('api'); $this->api->auth();` then `request_keyword()`, `request_limit()`, `request_offset()`, `request_order_by()`, `request_fields()`, `request_with()`; encode records with the model's `api_encode()`.

**Views** — escape every dynamic value with `e()`; translations via `lang('key')`; brand values via `setting('company_name', DEFAULT_COMPANY_NAME)` (see `CODING_STANDARDS.md`). Layout variables come from `html_vars()` (PHP) and `script_vars()` (exposed to JS as `vars('…')`).

**Booking flow** — `application/controllers/Booking.php` renders the wizard (`index()`, manage mode via `reschedule($hash)`), registers bookings (`register()`), and serves availability (`get_available_hours()`, `get_unavailable_dates()`); slot computation lives in `application/libraries/Availability.php`. The appointment hash (manage link) is the only customer credential for rescheduling and cancelling.

## CodeIgniter 3 reference

`references/documentation/easyappointments-ci3_docs/` mirrors the CodeIgniter 3.1.11 documentation (query builder, forge, sessions, email, libraries, helpers). Use it for framework-level behaviour only, and verify against the `EA_*` overrides before relying on it.

## Common mistakes

- Trusting generic CI3 docs for `EA_*` classes — read the override first.
- Calling `can()` with the arguments swapped — it is `can($action, $resource)`.
- Editing settings in the database by hand instead of a migration.
- Unescaped output in views, or brand literals instead of settings.
- Formatting whole upstream files (keeps the upstream diff small; see `docs/agents/development-conventions.md`).
- Running PHP on the host instead of the `php-fpm` container.

## Maintenance

An earlier version of this skill bundled a generated codebase analysis (Skill Seekers). It was removed because it went stale with every change and contained local absolute paths; regenerate it with Skill Seekers if needed, but do not commit it. Keep this file factual and verify statements against the code when the repository changes.
