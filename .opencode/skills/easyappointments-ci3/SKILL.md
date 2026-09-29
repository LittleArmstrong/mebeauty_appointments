---
name: easyappointments-ci3
description: Development knowledge for mebeauty_appointments, an Easy!Appointments 1.6.0 application based on CodeIgniter 3. Combines the official Easy!Appointments Framework documentation with the complete mebeauty_appointments codebase. Repository-specific architecture and conventions take precedence over generic framework guidance.
---

# Easyappointments-Ci3

Development knowledge for **mebeauty_appointments**, an **Easy!Appointments 1.6.0** application built on **CodeIgniter 3** (CI 3.1.11). This skill combines the official Easy!Appointments Framework documentation with the complete mebeauty_appointments codebase. Repository-specific architecture and conventions take precedence over generic framework guidance.

This is a **single-repository, multi-source** skill: it synthesizes what the *official docs say* with what the *code actually does* in this fork. When the two disagree, the codebase wins — but knowing both perspectives is what makes the synthesis useful.

## 📚 Sources

This skill synthesizes knowledge from **multiple sources**:

| Source | Type | Location | Confidence |
|--------|------|----------|------------|
| Official Easy!Appointments/CI3 Framework docs | documentation | `references/documentation/easyappointments-ci3_docs/` (10 pages, 173 doc pages) | high |
| Merged API reference | api | `references/api/merged_api.md` | high |
| Codebase analysis (architecture, API, patterns) | codebase_analysis | `references/codebase_analysis/` (61 files) | high/medium |
| GitHub repository (README, repo metadata) | github | `references/github/LittleArmstrong_mebeauty_appointments/` | high |
| Conflict report | report | `references/conflicts.md` (207 flagged items) | — |

### Source Agreements

Where all sources agree, you can trust the fact without re-verification:

- ✅ **Project identity** — All sources agree the project is an Easy!Appointments fork on CodeIgniter 3 (CI 3.1.11 per docs; `CI_*` core classes confirmed in the codebase analysis).
- ✅ **MVC structure** — Confirmed both by the official docs (controllers/models/views conventions) and by codebase analysis (confidence 0.95, evidenced by `application/controllers/` 44 files, `application/models/` 20+, `application/views/`).
- ✅ **Language stack** — PHP is the dominant language (89.2%), matching the PHP/CI3 stack the docs describe; JS 9.1%, SCSS 1.6%, HTML 0.2% (build tooling only).
- ✅ **Helper conventions** — `$this->load->helper('...')` loading and `MY_`-prefix extension files appear in both the official docs and the repo's `application/helpers/` (40 helper files, incl. repo-specific `permission_helper.php`).
- ✅ **Migration framework** — The docs describe CI3 migrations (`EA_Migration` extends `CI_Migration`); the codebase confirms 69 numbered migration classes (001–069) with `up()`/`down()`.
- ✅ **Service Layer** — Detected by codebase analysis at confidence 0.75 (18 service classes); consistent with the architecture the docs describe for large CI3 apps.

### Source Priorities

When sources disagree, trust in this order:

1. **Codebase analysis** — ground truth: what the code actually does (`references/codebase_analysis/`)
2. **Official documentation** — intended API and usage patterns (`references/documentation/`, `references/api/`)
3. **GitHub README/metadata** — real-world usage and project context (`references/github/`)

The API reference files are *extracted signatures* from the real source files — treat a signature found there as exact for this repo. The docs describe the *generic* CI3 framework; the repo overrides stock behavior via `EA_*` classes, so always check the codebase reference when a feature touches an `EA_*` class.

## 📦 About

MeBeauty Appointments forked from Easy!Appointments — Self Hosted Appointment Scheduler.

**Repository:** [LittleArmstrong/mebeauty_appointments](https://github.com/LittleArmstrong/mebeauty_appointments)
**Homepage:** https://easyappointments.org
**License:** GNU General Public License v3.0
**Stars:** 0 (private/personal fork)
**Last Updated:** 2026-08-08

### Languages

- **PHP:** 89.2%
- **JavaScript:** 9.1%
- **SCSS:** 1.6%
- **HTML:** 0.2%
- **Dockerfile / Shell:** 0.0%

### Tech Stack (from codebase analysis)

- PHP (CodeIgniter 3.1.11), MySQL, Apache/Nginx
- Docker Compose dev environment (`docker-compose.yml`, `docker/php-fpm/Dockerfile`)
- JS build tooling: `package.json`, `babel.config.json`, `.prettierrc.json`, `composer.json`
- API contract: `openapi.yml` (1289 settings)
- CI/CD: `.github/workflows/ci.yml`

## 💡 When to Use This Skill

**Use this skill when** you are working *in or against this repository* (`mebeauty_appointments`), or when you need repository-accurate answers about it. Concrete triggers:

- **Implement, extend, or debug features** in mebeauty_appointments — controllers, models, migrations, settings, API endpoints.
- **Understand Easy!Appointments 1.6.0 architecture** — the `EA_*` core classes, the service layer, the API v1 endpoints, the booking/calendar flow.
- **Write or debug CodeIgniter 3 code in this project** — helpers, libraries, database query builder, sessions, form validation, email, migrations.
- **Find concrete code examples before implementing** — real API signatures extracted from the actual codebase, not just framework theory.
- **Create database migrations** — 69 numbered migrations (001–069) provide the exact template for how this project evolves its schema.
- **Consume or extend the REST API** — the API v1 controllers (`application/controllers/api/v1/*`) and the `Api` library (bearer-token auth).
- **Gate controller actions by role** — the `can()`/`cannot()` permission helper.
- **Check design patterns and architecture** — how the codebase is organized, where the service layer lives, which patterns the analysis detected.
- **Navigate the official documentation quickly** — categorized references to the CI3 3.1.11 docs in `references/documentation/`.
- **Debug something that behaves differently from CI3 docs** — the repo overrides stock classes (`EA_Controller`, `EA_Model`, `EA_Email`, `EA_Calendar`); check the codebase reference first.
- **Integrations** — Google Calendar sync (`Google_sync`, `Caldav`), webhooks (`Webhooks_client`), Jitsi, LDAP, Altcha.

**When NOT to use this skill:**

- General CodeIgniter 3 questions **unrelated to this repository** (use the CI3 docs references directly instead — e.g. generic query-builder or helper questions).
- Frontend-only work that does not touch application PHP code (assets, SCSS, JS views).
- Writing a different Easy!Appointments fork — this skill documents *this* fork's overrides.

## 🎯 Quick Reference

### Codebase patterns (high confidence — real usage, extracted from code)

**Pattern 1: Custom model base class (`EA_Model` extends `CI_Model`)** — all repo models inherit a CRUD scaffold with field casting and API/db field mapping. From `references/codebase_analysis/.../api_reference/EA_Model.md`:

```php
// application/core/EA_Model.php — methods available on every model
$record_id = $this->appointments_model->add($record);      // int — insert, returns new ID
$row   = $this->appointments_model->get_row($record_id);   // array — single record
$batch = $this->appointments_model->get_batch($where, 20, 0, 'start_datetime'); // array
$value = $this->appointments_model->get_value('customer_id', $record_id); // string

// record hygiene helpers (mutate by reference)
$this->appointments_model->cast($record);              // cast fields to correct types
$this->appointments_model->only($record, ['id', 'name']); // keep only listed fields
$this->appointments_model->optional($record, ['notes']);   // remove null fields

// field mapping between API and DB names
$this->appointments_model->db_field('appointmentId');  // ?string — 'id' or NULL
```

**Pattern 2: Permission helper (`can` / `cannot`)** — role-based access checks used across controllers. From `references/codebase_analysis/.../api_reference/permission_helper.md`:

```php
// application/helpers/permission_helper.php
if (can('appointments', 'update', $user_id)) {
    // current user may update the resource
}

if (cannot('customers', 'delete')) {
    // deny the action
}
```

**Pattern 3: API library — bearer token authentication and query parameters.** From `references/codebase_analysis/.../api_reference/Api.md`:

```php
// inside an API v1 controller (extends EA_Controller)
$this->load->library('api');
$this->api->model('appointments_model');  // bind the model
$this->api->auth();                       // verify bearer token, 401 if missing/invalid

$keyword  = $this->api->request_keyword();   // ?string — 'q' query param
$limit    = $this->api->request_limit();     // ?int
$offset   = $this->api->request_offset();    // ?int
$order_by = $this->api->request_order_by();  // ?string
$fields   = $this->api->request_fields();    // ?array
$with     = $this->api->request_with();      // ?array
```

**Pattern 4: API v1 controller shape — RESTful resource verbs.** From `references/codebase_analysis/.../api_reference/Appointments_api_v1.md` (same shape in all 13 `*_api_v1` controllers):

```php
// application/controllers/api/v1/Appointments_api_v1.php — extends EA_Controller
class Appointments_api_v1 extends EA_Controller
{
    public function index()          { /* GET /api/v1/appointments  — list  */ }
    public function show($id = null) { /* GET /api/v1/appointments/1  — read  */ }
    public function store()          { /* POST /api/v1/appointments   — create */ }
    public function update($id)      { /* PUT  /api/v1/appointments/1 — update */ }
    public function destroy($id)     { /* DELETE /api/v1/appointments/1       */ }
}
```

**Pattern 5: Database migrations** — every migration extends `EA_Migration` and defines `up()`/`down()`. 69 migrations (001–069) follow this shape (verified class names + inheritance in `api_reference/NNN_*.md`):

```php
// application/migrations/002_add_google_analytics_setting.php
class Migration_Add_google_analytics_setting extends EA_Migration
{
    public function up()
    {
        // $this->dbforge->add_column(...) / $this->db->insert('settings', [...])
    }

    public function down()
    {
        // reverse of up()
    }
}
```

*(Exact class shape verified from the migration references; the 001–069 series covers settings rows, table renames, new tables like `blocked_periods`/`webhooks`, and column additions. Naming convention: `NNN_snake_case_description.php`.)*

**Pattern 6: Core controller (`EA_Controller` extends `CI_Controller`)** — every controller inherits session/language/timezone setup. From `references/codebase_analysis/.../api_reference/EA_Controller.md`:

```php
class My_controller extends EA_Controller
{
    public function __construct()
    {
        parent::__construct();
        // EA_Controller already: ensure_user_exists(), configure_language(),
        // load_common_html_vars(), load_common_script_vars(),
        // configure_timezone(), check_storage_writable()
    }
}
```

**Pattern 7: Google Calendar sync library.** From `references/codebase_analysis/.../api_reference/Google_sync.md`:

```php
// application/libraries/Google_sync.php
$google_sync = new Google_sync();
$auth_url  = $google_sync->get_auth_url($state);      // string — OAuth consent URL
$tokens    = $google_sync->authenticate($code);       // array  — exchange code for tokens
$google_sync->refresh_token($refresh_token);          // void
$event = $google_sync->add_appointment($appointment, $provider, $service, $customer, $settings); // Event
```

### Official documentation patterns (high confidence — CI3 3.1.11)

**Pattern 8: Loading helpers** — helpers are procedural functions loaded before use; extend with an `MY_` prefix file. From official docs:

```php
$this->load->helper('email');          // load one helper
$this->load->helper(['form', 'url']);  // load several at once
// application/helpers/MY_array_helper.php extends the array helper
```

**Pattern 9: Email helper** — validate an e-mail address. From official docs:

```php
$this->load->helper('email');

if (valid_email('email@somesite.com')) {
    echo 'email is valid';
} else {
    echo 'email is not valid';
}
```

**Pattern 10: Form helper** — generate form elements and escape output. From official docs:

```php
$this->load->helper('form');
echo form_open('email/send');  // open form posting to the 'email/send' route

$string = 'Here is a string containing "quoted" text.';
?>
<input type="text" name="myfield" value="<?php echo $string; ?>" />
```

**Pattern 11: Query Builder** — the CI3 database class (docs describe the full API; the repo's `EA_DB_query_builder` extends it, so these calls work in this project too):

```php
$query = $this->db->get('appointments');                 // SELECT * FROM appointments
$query = $this->db->get_where('appointments', ['id' => 5]);  // with WHERE clause
$this->db->select('first_name, last_name')
         ->from('users')
         ->where('role_id', 3)
         ->order_by('first_name', 'ASC')
         ->limit(10, 20);                                // LIMIT 10 OFFSET 20
```

**Pattern 12: Sessions** — store/read flash data for one-request messages (standard CI3, used throughout the app):

```php
$this->session->set_flashdata('message', 'Appointment saved!');
// on next request:
echo $this->session->flashdata('message');

$this->session->set_userdata('username', 'john');   // persistent userdata
$name = $this->session->userdata('username');
```

**Pattern 13: Benchmarking class** — measure execution time between marked points. From official docs:

```php
$this->benchmark->mark('code_start');
// Some code happens here
$this->benchmark->mark('code_end');
echo $this->benchmark->elapsed_time('code_start', 'code_end');
```

**Pattern 14: Output class** — control finalized output sent to the browser. From official docs:

```php
$this->output->parse_exec_vars = FALSE;   // disable {elapsed_time} / {memory_usage} parsing
```

### Development workflow (from GitHub README)

```bash
# Local development (Docker Compose)
git clone https://github.com/LittleArmstrong/mebeauty_appointments.git
docker compose up                      # start the environment
docker compose exec app bash           # second terminal, app shell
npm install && composer install        # dependencies
npm start                              # development watcher
npm run build                          # build production assets
```

**Production installation** (from README): Apache/Nginx + PHP 8.2+ + MySQL; upload the app folder, make `storage` writable, rename `config-sample.php` → `config.php`, update configuration values, open the app in a browser and follow the setup wizard.

### Design Patterns Detected

*From C3.1 codebase analysis (confidence > 0.7):*

- **Observer**: 7 instances
- **Builder**: 2 instances
- **Factory**: 1 instance

*Total: 10 high-confidence patterns*

The unfiltered pattern scan (see `ARCHITECTURE.md`) reports many more low-confidence hits (Adapter 138, Observer 62, TemplateMethod 17, Builder 16, Factory 10, Command 7, Strategy 4) — treat these as suggestive only; the >0.7 list above is the reliable signal. The Observer/Builder/Factory hits on `Google_sync`, `Notifications`, and the CI core classes (`CI_DB_query_builder`, `CI_Email`, `CI_Zip`) are the most credible.

## 🧪 Code Examples

**Example 1 — Full API v1 controller** (synthesized from `Appointments_api_v1.md` + `Api.md` + `EA_Controller.md`):

```php
<?php defined('BASEPATH') or exit('No direct script access allowed');

// application/controllers/api/v1/Customers_api_v1.php
class Customers_api_v1 extends EA_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('api');
        $this->api->model('customers_model');
    }

    public function index()
    {
        try {
            $this->api->auth();   // bearer token required
            $keyword  = $this->api->request_keyword();
            $limit    = $this->api->request_limit();
            $offset   = $this->api->request_offset();
            $order_by = $this->api->request_order_by();
            $where    = $keyword ? ['first_name LIKE' => "%$keyword%"] : null;
            $customers = $this->customers_model->get_batch($where, $limit, $offset, $order_by);
            json_response(['customers' => $customers]);
        } catch (Throwable $e) {
            json_response(['message' => $e->getMessage()], 400);
        }
    }
}
```

**Example 2 — Add a new setting the project's way** (pattern from migrations 002/003/004/017/053):

```php
// application/migrations/NNN_add_my_setting.php
class Migration_Add_my_setting extends EA_Migration
{
    public function up()
    {
        $this->db->insert('settings', [
            'name' => 'my_setting',
            'value' => 'default'
        ]);
    }

    public function down()
    {
        $this->db->where('name', 'my_setting')->delete('settings');
    }
}
```

**Example 3 — Model CRUD with hygiene helpers** (from `EA_Model.md`):

```php
$this->load->model('appointments_model');

$record = ['id_customer' => 1, 'start_datetime' => '2026-09-01 10:00:00'];
$id = $this->appointments_model->add($record);        // int

$row = $this->appointments_model->get_row($id);       // array
$this->appointments_model->cast($row);                // normalize types
$this->appointments_model->optional($row, ['notes']); // drop null fields
echo $this->appointments_model->get_value('start_datetime', $id); // '2026-09-01 10:00:00'
```

**Example 4 — Gate a controller action by role** (from `permission_helper.md`):

```php
public function delete($id)
{
    if (cannot('appointments', 'delete')) {
        show_error('Forbidden', 403);
        return;
    }
    // ... perform deletion
}
```

**Example 5 — CI3 query builder in this repo** (docs API; note the repo extends it via `EA_DB_query_builder`):

```php
$query = $this->db->select('id, start_datetime')
                  ->from('appointments')
                  ->where('id_customer', $customer_id)
                  ->where('start_datetime >=', date('Y-m-d'))
                  ->order_by('start_datetime', 'ASC')
                  ->get();
$rows = $query->result_array();
```

**Example 6 — Send email with the CI3 Email library** (docs; the repo extends it via `EA_Email`):

```php
$this->load->library('email');
$this->email->from('no-reply@example.com', 'MeBeauty');
$this->email->to('customer@example.com');
$this->email->subject('Booking confirmation');
$this->email->message('Your appointment has been confirmed.');
$this->email->send();
```

## 🔧 API Reference

*Extracted from codebase analysis (C2.5)* — full signatures for the most useful classes. Every scanned file has a matching reference in `references/codebase_analysis/LittleArmstrong_mebeauty_appointments/api_reference/`.

### EA_Model (application/core/EA_Model.php — extends CI_Model)

| Method | Signature | Returns |
|--------|-----------|---------|
| `get_value` | `(field: string, record_id: int)` | `string` |
| `get_row` | `(record_id: int)` | `array` |
| `get_batch` | `(where = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null)` | `array` |
| `add` | `(record: array)` | `int` |
| `cast` | `(&$record: array)` | `void` |
| `only` | `(&$record: array, fields: array)` | `void` |
| `optional` | `(&$record: array, fields: array)` | `void` |
| `db_field` | `(api_field: string)` | `?string` |
| `quote_order_by` | `(order_by: ?string)` | `?string` |

### Api (application/libraries/Api.php)

| Method | Returns |
|--------|---------|
| `model(model: string)` | `void` |
| `auth()` | `void` |
| `get_bearer_token()` | `?string` |
| `get_authorization_header()` | `?string` |
| `request_authentication()` | `void` |
| `request_keyword()` | `?string` |
| `request_limit()` | `?int` |
| `request_offset()` | `?int` |
| `request_order_by()` | `?string` |
| `request_fields()` | `?array` |
| `request_with()` | `?array` |

### EA_Controller (application/core/EA_Controller.php — extends CI_Controller)

| Method | Returns |
|--------|---------|
| `ensure_user_exists()` | — |
| `configure_language()` | — |
| `load_common_html_vars()` | — |
| `load_common_script_vars()` | — |
| `configure_timezone()` | `void` |
| `check_storage_writable()` | `void` |

### Appointments_api_v1 (application/controllers/api/v1/Appointments_api_v1.php — extends EA_Controller)

| Method | Signature | Returns |
|--------|-----------|---------|
| `index` | `()` | `void` — list |
| `aggregates` | `(&$appointment: array)` | `void` |
| `show` | `(id: ?int = null)` | `void` — read |
| `store` | `()` | `void` — create |
| `notify_and_sync_appointment` | `(appointment: array, action: string = 'store')` | `void` |
| `update` | `(id: int)` | `void` |
| `destroy` | `(id: int)` | `void` |

### Google_sync (application/libraries/Google_sync.php)

| Method | Signature | Returns |
|--------|-----------|---------|
| `get_client_id` | `()` | `string` |
| `get_client_secret` | `()` | `string` |
| `initialize_clients` | `()` | `void` |
| `get_auth_url` | `(state: ?string = null)` | `string` |
| `authenticate` | `(code: string)` | `array` |
| `refresh_token` | `(refresh_token: string)` | `void` |
| `add_appointment` | `(appointment: array, provider: array, service: array, customer: array, settings: array)` | `Event` |

### permission_helper (application/helpers/permission_helper.php)

| Function | Signature | Returns |
|----------|-----------|---------|
| `can` | `(action: string, resource: string, user_id: ?int = null)` | `bool` |
| `cannot` | `(action: string, resource: string, user_id: ?int = null)` | `bool` |

### Other verified signatures (per file reference)

- **Models** (all extend `EA_Model`): `Appointments_model` — `save(appointment: array): int`, `validate(appointment: array): void`, `get(where = null, limit = null, offset = null, order_by = null): array`, `insert(appointment: array): int`; plus `Customers_model`, `Providers_model`, `Services_model`, `Settings_model`, `Webhooks_model`, `Blocked_periods_model`, `Working_plan_exceptions_model`, `Roles_model`, `Users_model`, `Consents_model`, `Secretaries_model`, `Service_categories_model`, `Unavailabilities_model`.
- **API v1 controllers** (all extend `EA_Controller`): `Admins_api_v1`, `Availabilities_api_v1`, `Settings_api_v1`, `Webhooks_api_v1`, `Blocked_periods_api_v1`, `Unavailabilities_api_v1`, `Working_plan_exceptions_api_v1`, `Secretaries_api_v1`, `Service_categories_api_v1` — each with the `index/show/store/update/destroy` REST shape.
- **Libraries**: `Availability`, `Calendar`, `Caldav`, `Notifications`, `Webhooks_client`, `Cleanup`, `Jitsi_client`, `Altcha_client`, `Ldap_client`.
- **CI3 internals** (from `system/`): `DB_query_builder`, `DB_forge`, `Email`, `Encryption`, `Session`, `form_helper`, `url_helper`, `array_helper`, plus database drivers/utilities.

## 📖 Reference Documentation

### `references/api/` — Merged API Reference (api source, **high confidence**)

`merged_api.md` cross-references every API mentioned in the official documentation against the codebase. **Almost every entry is flagged "documented but not found in codebase"** — see the Known Discrepancies section below for how to interpret this. Use it to *check* whether an API exists in this repo, not as the primary source of signatures.

### `references/documentation/easyappointments-ci3_docs/` — Official Docs (documentation source, **high confidence**)

173 pages of the CodeIgniter 3.1.11 documentation as mirrored at https://developers.easyappointments.org/framework/. Organized as:

- `framework.md` (358 KB) — the bulk: core classes, libraries, helpers, database
- `api.md` — DB driver reference, query builder, utilities
- `database.md` — query builder, forge, utilities, sessions, caching
- `libraries.md` — session, calendar, email, encryption, pagination, upload
- `helpers.md` — helper loading, extending with `MY_` prefix
- `tutorials.md` — coding style, general concepts
- `overview.md`, `installation.md` — CI overview and installation

### `references/codebase_analysis/` — Codebase Analysis (codebase_analysis source, **high/medium confidence**)

Automated analysis of the actual repository code:

- `index.md` — navigation hub per repository
- `LittleArmstrong_mebeauty_appointments/ARCHITECTURE.md` — MVC (0.95) and Service Layer (0.75) detection, design patterns, config files, directory structure, tech stack
- `.../api_reference/` — **435 files**: one signature reference per scanned PHP file (core classes, models, controllers, libraries, helpers, migrations, CI system internals)
- `.../patterns/` — per-file design-pattern detections with confidence scores (`detected_patterns.json` has the raw data)
- `.../architecture_details/` — MVC/service-layer evidence with components (`architectural_patterns.json`, `index.md`)
- `.../configuration/` — 10 config files (`composer.json`, `package.json`, `docker-compose.yml`, `openapi.yml`, `.github/workflows/ci.yml`, Dockerfile, …) with `config_patterns.json`
- `.../dependencies/` — module dependency graph (1120 modules)

### `references/github/LittleArmstrong_mebeauty_appointments/` — GitHub (github source, **high confidence**)

`README.md` — the repo README (features, Docker quick start, production installation, license). `index.md` — repo metadata (0 stars, GPL v3.0, last updated 2026-08-08).

### `references/conflicts.md` — Conflict Report (report source)

207 high-severity "conflicts" — all of the same class (documented API not found in codebase scan). See Known Discrepancies for the interpretation rule.

## 🛠️ Working with This Skill

### Navigation tips for multi-source references

1. **Start with the Quick Reference above** — it covers the 14 most common operations.
2. **For a class/method signature**: search `references/codebase_analysis/LittleArmstrong_mebeauty_appointments/api_reference/` for `<ClassName>.md` (or `<file_name>.md`).
3. **For how a feature *should* work**: read the relevant page in `references/documentation/easyappointments-ci3_docs/framework.md` or `libraries.md`.
4. **For how this repo actually does it**: check `ARCHITECTURE.md` first, then look at the matching controller/model in the api_reference.
5. **For migration history / schema evolution**: read the 001–069 migration references in order.
6. **For the project overview and setup**: see the GitHub README reference.
7. **For the machine-readable data**: `patterns/detected_patterns.json`, `architecture_details/architectural_patterns.json`, `configuration/config_patterns.json`.

### Beginner

- Start with the **Quick Reference** patterns 8–14 (helpers, form, email, query builder, sessions, benchmark, output) — these are pure CI3 and match the official docs.
- Use the GitHub README quick start (Docker) to run the app locally.
- Read `ARCHITECTURE.md` for the big picture (MVC, service layer, directory layout).
- Understand the `EA_*` classes: they are the project's customization layer over stock CI3 (`EA_Controller`, `EA_Model`, `EA_Email`, `EA_Calendar`).

### Intermediate

- Use patterns 1–7 and the **API Reference tables**: extend `EA_Model` subclasses, add controllers extending `EA_Controller`, gate access with `can()`/`cannot()`, authenticate API endpoints with `$this->api->auth()`.
- Add settings the way the project does: via a numbered migration inserting into the `settings` table (see migrations 002, 003, 004, 017… for the canonical pattern).
- When debugging, remember the docs describe generic CI3 while the codebase may override behavior via `EA_*` classes — check the codebase reference first.

### Advanced

- Build new API v1 endpoints by mirroring `application/controllers/api/v1/*` + the `Api` library pattern (bearer token, keyword/limit/offset/order_by/fields/with query params) — see Example 1 above.
- Extend the service layer (18 service classes) rather than putting business logic in controllers.
- Implement webhooks/CalDAV/Google sync by studying `Webhooks_client`, `Caldav`, `Google_sync` references and the 040/041 (webhooks) and 055–056/059 (CalDAV) migrations.
- Use `references/codebase_analysis/.../patterns/detected_patterns.json` for a raw, confidence-scored map of where design patterns were detected.
- Extend stock CI3 behavior with new `EA_*` classes in `application/core/` — that is the established extension mechanism.

### Resolving conflicts

Follow the **Source Priorities** list: codebase analysis > official docs > GitHub README. If a documented API is not in the codebase references, treat the docs entry as *generic framework guidance* and verify against the actual code before using it.

### Common Mistakes

- **Trusting docs over code for `EA_*` classes** — `EA_Controller`, `EA_Model`, `EA_Email`, `EA_Calendar`, `EA_DB_query_builder` override stock CI3 behavior. Always check `api_reference/EA_*.md` before assuming stock behavior.
- **Believing the 207 "conflicts" mean broken APIs** — they are documentation-scraping artifacts (see Known Discrepancies). `xss_clean`, `form_open`, `where` etc. exist at runtime; the scan only covers what this repo's code references.
- **Writing raw SQL when the query builder exists** — the repo uses `$this->db` (extended by `EA_DB_query_builder`) everywhere; use it for escaping and portability.
- **Adding settings by editing the DB by hand** — the project convention is numbered migrations against the `settings` table.
- **Skipping `$this->api->auth()` on new API endpoints** — all API v1 endpoints are bearer-token protected; new ones should follow the same pattern.
- **Putting business logic in controllers** — the project's service layer (18 classes) is the intended home for it.

## 🧩 Key Concepts

- **`EA_` prefix classes** — mebeauty_appointments extends stock CI3 classes (`CI_Controller`, `CI_Model`, `CI_Email`, `CI_Calendar`) with `EA_` subclasses that add project-wide behavior (timezone config, language config, storage checks, CRUD scaffolding, field casting).
- **MVC (confidence 0.95)** — controllers in `application/controllers/` (44 files), models in `application/models/` (20+), views/pages under `application/views/`.
- **Service Layer (confidence 0.75)** — 18 service classes encapsulate business logic; controllers stay thin.
- **API v1** — REST endpoints under `application/controllers/api/v1/` secured via the `Api` library bearer-token auth, with a matching `openapi.yml` (1289 settings). Resource conventions: `index` (list), `show` (read), `store` (create), `update`, `destroy`.
- **Settings system** — application configuration stored in the `settings` table and added/modified via numbered migrations (e.g. `002_add_google_analytics_setting`, `017_add_api_token_setting`, `053_add_default_language_setting`).
- **Migrations** — sequential `application/migrations/NNN_*.php` files extending `EA_Migration`; 069 exist, including reverts (`046`, `047`, `059`).
- **Domain model files** — appointments, customers, providers, secretaries, admins, services, service categories, unavailabilities, blocked periods, working plan exceptions, consents, webhooks.
- **Integrations** — Google Calendar sync (`Google_sync`, `Caldav`), Google/Matomo analytics, Jitsi, LDAP, Altcha (captcha), webhooks.
- **`permission_helper`** — `can()`/`cannot()` role/permission checks used by controllers.
- **Helpers** (CI3 concept) — procedural function collections loaded via `$this->load->helper()`; the repo adds its own in `application/helpers/` and can extend stock helpers via `MY_`-prefixed files.
- **Framework/library vs repo conventions** — the docs describe generic CI3; this repo wraps/overrides stock pieces with `EA_*` classes and adds its own helpers/libraries. When in doubt, the codebase reference is authoritative.

## ⚠️ Known Discrepancies

The conflict report (`references/conflicts.md`) lists **207 high-severity "conflicts"**, but they are **all the same single class of discrepancy**:

> **API documented in the official framework docs but not found in the mebeauty_appointments codebase analysis.**

Examples: `xss_clean`, `html_escape`, `form_open`, `get_csrf_token_name`, `get_csrf_hash`, `set_status_header`, `where`, `select`, `get`, `post`, `anchor`, `password_hash`, `uri_string`…

**How to interpret these:**

- This is expected: the official docs describe the *full* CodeIgniter 3 framework, while the codebase analysis scanned what this repo *actually uses/implements*. Many entries are also documentation-scraping noise (single words like `a`, `for`, `foo`, `bar`, `case`, `it`, `name`).
- These are **not** evidence the framework APIs are broken or missing — most are plain PHP or stock CI3 functionality (e.g. `password_hash`, `xss_clean`, `form_open`) that exists in the runtime.
- **Resolution rule**: the codebase analysis is ground truth for *this repository's* code. When implementing, check `references/codebase_analysis/.../api_reference/` for the file you're touching; use the official docs only for framework-level behavior (query builder, sessions, helpers) that the analysis doesn't contradict.
- The "documented but not found" flag on an API means: don't assume this repo has a wrapper for it — call the standard CI3/PHP function directly.

**Minor inconsistency:** the design-pattern counts differ between the Quick Reference (confidence > 0.7: Observer 7, Builder 2, Factory 1) and `ARCHITECTURE.md` (unfiltered: Adapter 138, Observer 62, …). Prefer the >0.7 counts for reliability; treat the raw counts as upper bounds.

**Other noted inconsistencies (transparent, low impact):**

- The `ARCHITECTURE.md` "Frameworks & Libraries" line lists ASP.NET/Rails/React/Vue.js/Express — these are **false positives** of the automated detector (this is a PHP/CI3 project). Ignore them; the languages list (PHP/JS/SCSS/HTML) is accurate.
- The README badges/links point to the upstream `alextselegidis/easyappointments` repo; treat README content as upstream-derived, with repo metadata (`index.md`) as the fork's own data.
- Some codebase examples in the Quick Reference (Patterns 1–3, 6, 7) are canonical *usages* constructed from verified signatures in the `api_reference/` files; the signatures themselves (names, params, returns) are exact, but surrounding code is illustrative. Patterns 8–14 are verbatim from the official docs.

## 🌐 Repository Scope

Single repository (`LittleArmstrong/mebeauty_appointments`) — standard synthesis applies. All codebase references live under `references/codebase_analysis/LittleArmstrong_mebeauty_appointments/`; there are no other repositories to cross-reference.

---

*Synthesized from official documentation, codebase analysis, and GitHub repository metadata by Skill Seekers.*
