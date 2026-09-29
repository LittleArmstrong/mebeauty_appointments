# MeBeauty Reskin Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Die öffentlichen Seiten und E-Mails der Terminbuchungs-App im dunklen MeBeauty-Gold-Design (Website-Look) darstellen, ohne das Backend funktional zu verändern.

**Architecture:** Ein neues Bootstrap-5-SCSS-Theme „mebeauty“ (Gold #d1ae5e, Dark-Scheme via `data-bs-theme="dark"`), selbst gehostete Fonts, neue gemeinsame Navbar-/Footer-Komponenten für alle öffentlichen Layouts, zentrale MeBeauty-Config und eine Migration für Deutsch-Default + Rechtslinks. Backend behält Theme-Setting und Layout; nur Logo-Asset und Titel werden global getauscht.

**Tech Stack:** PHP 8.2+/CodeIgniter 3 (EA-Fork), Bootstrap 5.3.8 (SCSS über gulp-sass), jQuery, Docker Compose (php-fpm/nginx/mysql/mailpit), PHPUnit, bash+curl für Smoke-Tests.

## Global Constraints

- Bootstrap-SCSS-Themes liegen unter `assets/css/themes/`, werden per `npx gulp compile` gebaut; `assets/css/**/*.css` und `*.min.css` sind gitignored (NIE committen).
- Öffentliche Layouts laden das mebeauty-Theme fest; das Backend-Layout behält `setting('theme', 'default')` – Backend-Theme-Setting/-Dropdown unangetastet.
- JS-vertrag einhalten: `#steps`, `#step-1`…`#step-4`, `.book-step`, `.active-step`, `.display-booking-selection` müssen erhalten bleiben (booking.js:238-249, 692-698); `#select-language` darf öffentlich nicht mehr gerendert werden.
- Keine neuen Abhängigkeiten (kein npm/composer-Paket hinzufügen).
- Neue UI-Strings in `german` + `english` + `turkish` Sprachdateien pflegen (vor der letzten `];` einfügen).
- Deutsch als Default: `default_language=german` (Migration) und `LANGUAGE = 'german'` in `config-sample.php`.
- Rechtslinks nur über die bestehenden Settings `legal_notice_url`/`imprint_url` (Migration vorbefüllt, nur falls leer).
- Workflow: Änderungen im Repo, Build/Verifikation im Container: `docker compose exec php-fpm <cmd>`; Tests: `docker compose exec php-fpm composer test`.
- Nach jedem Task committen (Repo-Stil: `feat(…):`, `fix(…):`, `docs(…):`, `test(…):`). Commit nur, wenn vom Nutzer beauftragt.
- `BASE_URL=http://localhost`, App ist nicht installiert (nur `ea_migrations`-Tabelle) → Installation gehört zur Verifikation von Task 1.

---

### Task 1: MeBeauty-Fundament + Buchungsseite-Basis

**Files:**
- Create: `assets/css/themes/mebeauty.scss`, `assets/fonts/karla-400.woff2`, `assets/fonts/karla-500.woff2`, `assets/fonts/karla-600.woff2`, `assets/fonts/karla-700.woff2`, `assets/fonts/dancing-script-400.woff2`, `assets/fonts/dancing-script-600.woff2`, `assets/fonts/dancing-script-700.woff2`, `assets/img/mebeauty/logo.png`, `assets/img/mebeauty/apple-touch-icon.png`, `assets/img/mebeauty/instagram.png`, `assets/img/mebeauty/tiktok.png`, `assets/img/mebeauty/whatsapp.png`, `application/config/mebeauty.php`, `application/migrations/070_add_mebeauty_branding.php`
- Replace (kopieren): `assets/img/logo.png` ← `/home/baris/www/mebeauty/src/images/logo3trans.png`; `assets/img/favicon.ico` ← `/home/baris/www/mebeauty/public/favicon.ico`; `assets/img/social-card.png` ← `/home/baris/www/mebeauty/public/Logo.png`
- Modify: `application/config/autoload.php:104`, `application/views/layouts/booking_layout.php`, `application/views/layouts/account_layout.php`, `application/views/layouts/message_layout.php`, `application/views/layouts/backend_layout.php` (nur Titel), `config-sample.php:34`

**Interfaces:**
- Consumes: bestehende Template-Helper `component()`, `slot()`, `vars()`, `config()`, `asset_url()`, `setting()`.
- Produces: `config('mebeauty')` = Array mit Keys `website_url`, `multilang`, `nav`, `social`, `contact`, `phone_hours`, `appointment_note`; Theme-Name `mebeauty` (Datei `assets/css/themes/mebeauty.min.css` nach Build); Font-Dateien unter `assets/fonts/*.woff2`; Migration `070` setzt `default_language=german` und befüllt `legal_notice_url`/`imprint_url` nur falls leer.

- [ ] **Step 1: Fonts (WOFF2) herunterladen** – im Container ausführen:

```bash
docker compose exec php-fpm bash -lc '
mkdir -p assets/fonts && cd assets/fonts &&
UA="Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120 Safari/537.36" &&
fetch() { curl -fsS -A "$UA" "https://fonts.googleapis.com/css2?family=$1&display=swap" | grep -o "https://[^)]*\.woff2" | while read -r u; do curl -fsS -o "$2" "$u" && break; done; } &&
fetch "Karla:wght@400" karla-400.woff2 &&
fetch "Karla:wght@500" karla-500.woff2 &&
fetch "Karla:wght@600" karla-600.woff2 &&
fetch "Karla:wght@700" karla-700.woff2 &&
fetch "Dancing+Script:wght@400" dancing-script-400.woff2 &&
fetch "Dancing+Script:wght@600" dancing-script-600.woff2 &&
fetch "Dancing+Script:wght@700" dancing-script-700.woff2 &&
ls -la'
```
Erwartung: 7 WOFF2-Dateien > 10 KB. (Fallback bei Blockierung: Dateien manuell von https://fonts.google.com/specimen/Karla und /specimen/Dancing+Script laden.)

- [ ] **Step 2: Assets kopieren** (auf dem Host):

```bash
cp /home/baris/www/mebeauty/src/images/logo3trans.png assets/img/logo.png
cp /home/baris/www/mebeauty/src/images/logo3trans.png assets/img/mebeauty/logo.png
cp /home/baris/www/mebeauty/public/favicon.ico assets/img/favicon.ico
cp /home/baris/www/mebeauty/public/Logo.png assets/img/social-card.png
cp /home/baris/www/mebeauty/src/images/instagram-logo.png assets/img/mebeauty/instagram.png
cp /home/baris/www/mebeauty/src/images/TikTok_Icon_Black_Square.png assets/img/mebeauty/tiktok.png
cp /home/baris/www/mebeauty/src/images/Whatsapp_Icon.png assets/img/mebeauty/whatsapp.png
```

- [ ] **Step 3: Apple-Touch-Icon generieren** (GD im Container):

```bash
docker compose exec -T php-fpm php -r '
$s = imagecreatefrompng("assets/img/mebeauty/logo.png");
$w = imagesx($s); $h = imagesy($s);
$t = imagecreatetruecolor(180, 180);
imagealphablending($t, false); imagesavealpha($t, true);
imagecopyresampled($t, $s, 0, 0, 0, 0, 180, 180, $w, $h);
imagepng($t, "assets/img/mebeauty/apple-touch-icon.png");'
```

- [ ] **Step 4: Theme-SCSS anlegen** – `assets/css/themes/mebeauty.scss`:

```scss
// MeBeauty Theme - dark + gold
@font-face { font-family: 'Karla'; font-style: normal; font-weight: 400; font-display: swap; src: url('../../fonts/karla-400.woff2') format('woff2'); }
@font-face { font-family: 'Karla'; font-style: normal; font-weight: 500; font-display: swap; src: url('../../fonts/karla-500.woff2') format('woff2'); }
@font-face { font-family: 'Karla'; font-style: normal; font-weight: 600; font-display: swap; src: url('../../fonts/karla-600.woff2') format('woff2'); }
@font-face { font-family: 'Karla'; font-style: normal; font-weight: 700; font-display: swap; src: url('../../fonts/karla-700.woff2') format('woff2'); }
@font-face { font-family: 'Dancing Script'; font-style: normal; font-weight: 400; font-display: swap; src: url('../../fonts/dancing-script-400.woff2') format('woff2'); }
@font-face { font-family: 'Dancing Script'; font-style: normal; font-weight: 600; font-display: swap; src: url('../../fonts/dancing-script-600.woff2') format('woff2'); }
@font-face { font-family: 'Dancing Script'; font-style: normal; font-weight: 700; font-display: swap; src: url('../../fonts/dancing-script-700.woff2') format('woff2'); }

$white: #fff !default;
$gray-100: #f3f4f6 !default;
$gray-200: #e5e7eb !default;
$gray-300: #d1d5db !default;
$gray-500: #6b7280 !default;
$gray-600: #4b5563 !default;
$gray-700: #424245 !default;
$gray-800: #2d2d2d !default;
$gray-900: #1d1d1f !default;
$black: #000 !default;
$blue: #e4c375 !default;
$red: #ef4444 !default;
$orange: #ca8a04 !default;
$yellow: #eab308 !default;
$green: #34c759 !default;
$teal: #5ac8fa !default;
$cyan: #32ade6 !default;

$primary: #d1ae5e !default;
$secondary: #26241e !default;
$success: $green !default;
$info: $teal !default;
$warning: $orange !default;
$danger: $red !default;
$light: $gray-100 !default;
$dark: $gray-900 !default;

$body-bg: $black !default;
$body-bg-dark: $black !default;
$body-color: $white !default;
$body-color-dark: $white !default;
$body-secondary-color: $gray-300 !default;

$font-family-sans-serif: 'Karla', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif !default;
$font-family-base: $font-family-sans-serif !default;
$headings-font-family: $font-family-sans-serif !default;
$headings-font-weight: 600 !default;

$link-color: $primary !default;
$link-hover-color: lighten($primary, 10%) !default;

$enable-dark-mode: true !default;
$enable-rounded: true !default;
$enable-shadows: true !default;
$min-contrast-ratio: 2.5 !default;
$border-radius: .5rem !default;
$btn-border-radius: .5rem !default;
$input-border-radius: .5rem !default;
$card-border-radius: .5rem !default;

$component-active-bg: $primary !default;
$component-active-color: $black !default;

$btn-primary-color: $black !default;
$btn-primary-hover-color: $black !default;

$input-bg: $black !default;
$input-border-color: $primary !default;
$input-color: $white !default;
$input-focus-border-color: lighten($primary, 10%) !default;

$nav-link-color: $white !default;
$nav-link-hover-color: $primary !default;

@import '../../../node_modules/bootstrap/scss/bootstrap';

.font-dancing-script { font-family: 'Dancing Script', cursive; }

body { background: $black; }
#book-appointment-wizard { background: #111; border: 1px solid rgba(209, 174, 94, .35); }
#book-appointment-wizard .book-step { border: 1px solid $primary; color: $white; }
#book-appointment-wizard .book-step.active-step { background: $primary !important; }
#book-appointment-wizard .book-step.active-step strong { color: $black !important; }
#book-appointment-wizard #available-hours .selected-hour { color: $black !important; }
.form-control, .form-select { border-color: $primary; color: $primary; background-color: $black; }
.form-control:focus, .form-select:focus { color: $white; }
```

- [ ] **Step 5: Build verifizieren**

```bash
docker compose exec php-fpm npx gulp compile
ls -la assets/css/themes/mebeauty.css assets/css/themes/mebeauty.min.css
```
Erwartung: beide Dateien existieren, kein Compile-Fehler.

- [ ] **Step 6: Config `application/config/mebeauty.php`**

```php
<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['mebeauty'] = [
    'website_url' => 'https://mebeauty-koeln.de',
    'multilang' => false,
    'nav' => [
        ['label' => 'Home', 'url' => 'https://mebeauty-koeln.de/'],
        ['label' => 'Behandlungen', 'url' => 'https://mebeauty-koeln.de/Behandlungen'],
        ['label' => 'Preise', 'url' => 'https://mebeauty-koeln.de/Preise'],
        ['label' => 'Kontakt', 'url' => 'https://mebeauty-koeln.de/Kontakt'],
    ],
    'social' => [
        ['label' => 'Instagram', 'url' => 'https://www.instagram.com/mebeauty_koeln/', 'icon' => 'assets/img/mebeauty/instagram.png'],
        ['label' => 'TikTok', 'url' => 'https://www.tiktok.com/@mebeauty_koeln', 'icon' => 'assets/img/mebeauty/tiktok.png'],
        ['label' => 'WhatsApp', 'url' => 'https://wa.me/4917619256689', 'icon' => 'assets/img/mebeauty/whatsapp.png'],
    ],
    'contact' => [
        'tel' => ['label' => '+49 176 192 566 89', 'href' => 'tel:+4917619256689'],
        'tel2' => ['label' => '+49 178 682 71 17 (Olga)', 'href' => 'tel:+491786827117'],
        'email' => ['label' => 'info@mebeauty-koeln.de', 'href' => 'mailto:info@mebeauty-koeln.de'],
        'email2' => ['label' => 'olga@rebirth-of-shakti.de', 'href' => 'mailto:olga@rebirth-of-shakti.de'],
        'address' => ['label' => 'Fußfallstr. 25a, 51109 Köln', 'href' => 'https://maps.apple.com/?q=Mebeauty+Köln+51109'],
    ],
    'phone_hours' => '10:00 - 19:00 Uhr',
    'appointment_note' => 'Termin nach Vereinbarung!',
];
```

- [ ] **Step 7: Autoload** – `application/config/autoload.php:104`:
  `$autoload['config'] = ['app', 'google', 'email'];` → `$autoload['config'] = ['app', 'google', 'email', 'mebeauty'];`

- [ ] **Step 8: Migration `application/migrations/070_add_mebeauty_branding.php`** (Muster von `069_add_altcha_settings.php`):

```php
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_mebeauty_branding extends CI_Migration
{
    public function up(): void
    {
        $language = $this->db->get_where('settings', ['name' => 'default_language'])->row_array();

        if (empty($language)) {
            $this->db->insert('settings', ['name' => 'default_language', 'value' => 'german']);
        } else {
            $this->db->where('name', 'default_language')->update('settings', ['value' => 'german']);
        }

        $legal = [
            'legal_notice_url' => 'https://mebeauty-koeln.de/Datenschutz',
            'imprint_url' => 'https://mebeauty-koeln.de/Impressum',
        ];

        foreach ($legal as $name => $value) {
            if (!$this->db->get_where('settings', ['name' => $name])->num_rows()) {
                $this->db->insert('settings', ['name' => $name, 'value' => $value]);
            }
        }
    }

    public function down(): void
    {
        $this->db->where('name', 'default_language')->update('settings', ['value' => 'english']);
        $this->db->delete('settings', ['name' => 'legal_notice_url']);
        $this->db->delete('settings', ['name' => 'imprint_url']);
    }
}
```

- [ ] **Step 9: Layouts anpassen** (exakte Ersetzungen):
  - `booking_layout.php:2`: `<html lang="<?= config('language_code') ?>">` → `<html lang="<?= config('language_code') ?>" data-bs-theme="dark">`
  - `booking_layout.php:7`: `content="#35A768"` → `content="#d1ae5e"` (auch account:7, message:7)
  - `booking_layout.php:10`: og:title → `<?= lang('page_title') ?> | MeBeauty`; `:11` og:description → `Termine online buchen – MeBeauty Köln`
  - `booking_layout.php:18`: `<title><?= lang('page_title') . ' ' . e(vars('company_name')) ?> | Easy!Appointments</title>` → `<title><?= lang('page_title') ?> | MeBeauty</title>`
  - `booking_layout.php:21`: zweite Icon-Zeile → `<link rel="apple-touch-icon" href="<?= asset_url('assets/img/mebeauty/apple-touch-icon.png') ?>">`
  - `booking_layout.php:26`: `'assets/css/themes/' . vars('theme') . '.css'` → `'assets/css/themes/mebeauty.css'`
  - `account_layout.php:12`: `| Easy!Appointments` → `| MeBeauty`; `:17-19`: `setting('theme', 'default')` → `mebeauty` (fest); `:15`: zweite Icon-Zeile → apple-touch (wie oben)
  - `message_layout.php:12`: `| Easy!Appointments` → `| MeBeauty`; `:17-18`: Theme → fest `mebeauty`; `:15`: apple-touch
  - `backend_layout.php:12`: `| Easy!Appointments` → `| MeBeauty` (nur diese Zeile)
  - `config-sample.php:34`: `const LANGUAGE = 'english';` → `const LANGUAGE = 'german';` (lokale `config.php` ebenfalls auf `german` setzen – gitignored)

- [ ] **Step 10: App installieren** (DB ist leer). Entweder Wizard im Browser, oder:

```bash
JAR=/tmp/opencode/ea.jar
curl -fsS -c "$JAR" http://localhost/index.php/installation -o /tmp/opencode/install.html
TOKEN=$(grep -oP '"csrf_token"\s*:\s*"\K[^"]+' /tmp/opencode/install.html | head -1)
curl -fsS -b "$JAR" -X POST http://localhost/index.php/installation/perform \
  --data-urlencode "csrf_token=$TOKEN" \
  --data-urlencode "admin[first_name]=MeBeauty" \
  --data-urlencode "admin[last_name]=Admin" \
  --data-urlencode "admin[email]=info@mebeauty-koeln.de" \
  --data-urlencode "admin[username]=admin" \
  --data-urlencode "admin[password]=mebeauty2026" \
  --data-urlencode "admin[language]=german" \
  --data-urlencode "company[company_name]=MeBeauty Köln" \
  --data-urlencode "company[company_email]=info@mebeauty-koeln.de" \
  --data-urlencode "company[company_link]=https://mebeauty-koeln.de"
```

- [ ] **Step 11: Verifizieren**

```bash
docker compose exec -T mysql mysql -uuser -ppassword easyappointments -N -e \
  "SELECT name, value FROM settings WHERE name IN ('default_language','legal_notice_url','imprint_url')"
curl -fsS http://localhost/index.php/booking | grep -oE 'themes/mebeauty\.(min\.)?css|data-bs-theme="dark"|lang="de"|\| MeBeauty' | sort -u
docker compose exec php-fpm composer test
```
Erwartung: `default_language=german`, beide URLs gesetzt; Booking-Seite enthält Theme-Link, dark-Attribut, `lang="de"`, Titel `MeBeauty`; PHPUnit grün.

- [ ] **Step 12: Commit** (nur auf Ansage): `git add` der geänderten/neuen Dateien (ohne generierte `assets/css/**/*.css`!) → `git commit -m "feat(theme): add mebeauty dark/gold theme, branding assets and german defaults"`

---

### Task 2: Website-Navbar, Stepper-Leiste und Footer (Buchungsseite)

**Files:**
- Create: `application/views/components/site_navbar.php`, `application/views/components/booking_steps.php`, `application/views/components/site_footer.php`
- Delete: `application/views/components/booking_header.php`, `application/views/components/booking_footer.php`
- Modify: `application/views/layouts/booking_layout.php` (Struktur), `assets/css/frontend.scss:21-49` (Stepper-Styles), `assets/js/pages/booking.js:73-86` (Cookieconsent), `application/language/german/translations_lang.php`, `application/language/english/translations_lang.php`, `application/language/turkish/translations_lang.php`

**Interfaces:**
- Consumes: `config('mebeauty')`, `setting('legal_notice_url')`, `setting('imprint_url')`, `vars('display_login_button')`, `session('user_id')`, `asset_url()`, `lang('footer_phone'|'footer_email'|'footer_address'|'footer_phone_hours')`.
- Produces: Komponenten `site_navbar`, `booking_steps`, `site_footer`; Footer-Strings in de/en/tr.

- [ ] **Step 1: `site_navbar.php`**

```php
<?php $mebeauty = config('mebeauty', []); ?>
<header class="w-100">
    <nav class="navbar navbar-expand-lg py-2" style="border-bottom: 1px solid #d1ae5e; background: rgba(0,0,0,0.7);">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center" href="<?= e($mebeauty['website_url'] ?? 'https://mebeauty-koeln.de') ?>">
                <img src="<?= asset_url('assets/img/mebeauty/logo.png') ?>" alt="MeBeauty" width="80">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mebeauty-navbar"
                    aria-controls="mebeauty-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mebeauty-navbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php foreach (($mebeauty['nav'] ?? []) as $item): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= e($item['url']) ?>"><?= e($item['label']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
```

- [ ] **Step 2: `booking_steps.php`** (ersetzt `booking_header.php`; IDs/Klassen bleiben!)

```php
<div id="steps" class="d-flex justify-content-center align-items-center gap-0 py-3"
     style="border-bottom: 1px solid #d1ae5e; background: rgba(0,0,0,0.6);">
    <div id="step-1" class="book-step active-step rounded-circle d-flex align-items-center justify-content-center me-3"
         data-tippy-content="<?= lang('service_and_provider') ?>" style="width: 45px; height: 45px;">
        <strong class="fs-5">1</strong>
    </div>
    <div id="step-2" class="book-step rounded-circle d-flex align-items-center justify-content-center me-3"
         data-tippy-content="<?= lang('appointment_date_and_time') ?>" style="width: 35px; height: 35px; opacity: .6;">
        <strong class="small">2</strong>
    </div>
    <div id="step-3" class="book-step rounded-circle d-flex align-items-center justify-content-center me-3"
         data-tippy-content="<?= lang('customer_information') ?>" style="width: 35px; height: 35px; opacity: .6;">
        <strong class="small">3</strong>
    </div>
    <div id="step-4" class="book-step rounded-circle d-flex align-items-center justify-content-center me-3"
         data-tippy-content="<?= lang('appointment_confirmation') ?>" style="width: 35px; height: 35px; opacity: .6;">
        <strong class="small">4</strong>
    </div>
    <span class="display-booking-selection small text-white-50 ms-3 d-none d-md-inline"></span>
</div>
```

- [ ] **Step 3: `site_footer.php`**

```php
<?php
$mebeauty = config('mebeauty', []);
$legal_notice_url = setting('legal_notice_url') ?: 'https://mebeauty-koeln.de/Datenschutz';
$imprint_url = setting('imprint_url') ?: 'https://mebeauty-koeln.de/Impressum';
?>
<footer class="w-100 pt-5 pb-3 mt-auto" style="border-top: 1px solid #d1ae5e; background: #000;">
    <div class="container-xl">
        <div class="row g-4 text-center text-md-start">
            <div class="col-12 col-md-6 col-lg-4">
                <h2 class="font-dancing-script fs-3 fst-italic text-primary">MeBeauty</h2>
                <div class="d-flex justify-content-center justify-content-md-start gap-4 mt-3">
                    <?php foreach (($mebeauty['social'] ?? []) as $social): ?>
                        <a href="<?= e($social['url']) ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?= asset_url($social['icon']) ?>" alt="<?= e($social['label']) ?>" width="32" height="32">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <h3 class="font-dancing-script fs-4 text-primary"><?= lang('footer_phone') ?></h3>
                <p class="mb-1"><a class="link-light text-decoration-none" href="<?= e($mebeauty['contact']['tel']['href'] ?? '') ?>"><?= e($mebeauty['contact']['tel']['label'] ?? '') ?></a></p>
                <p class="mb-1"><a class="link-light text-decoration-none" href="<?= e($mebeauty['contact']['tel2']['href'] ?? '') ?>"><?= e($mebeauty['contact']['tel2']['label'] ?? '') ?></a></p>
                <h3 class="font-dancing-script fs-4 text-primary mt-4"><?= lang('footer_email') ?></h3>
                <p class="mb-1"><a class="link-light text-decoration-none" href="<?= e($mebeauty['contact']['email']['href'] ?? '') ?>"><?= e($mebeauty['contact']['email']['label'] ?? '') ?></a></p>
                <p class="mb-1"><a class="link-light text-decoration-none" href="<?= e($mebeauty['contact']['email2']['href'] ?? '') ?>"><?= e($mebeauty['contact']['email2']['label'] ?? '') ?></a></p>
                <h3 class="font-dancing-script fs-4 text-primary mt-4"><?= lang('footer_address') ?></h3>
                <p><a class="link-light text-decoration-none" target="_blank" href="<?= e($mebeauty['contact']['address']['href'] ?? '') ?>"><?= e($mebeauty['contact']['address']['label'] ?? '') ?></a></p>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <h3 class="font-dancing-script fs-4 text-primary"><?= lang('footer_phone_hours') ?></h3>
                <p><?= e($mebeauty['phone_hours'] ?? '') ?></p>
                <p class="fst-italic fs-5"><?= e($mebeauty['appointment_note'] ?? '') ?></p>
            </div>
        </div>
        <div class="text-center mt-5 pt-3" style="border-top: 1px solid #d1ae5e;">
            <ul class="list-inline mb-2">
                <li class="list-inline-item">MeBeauty Köln</li>
                <li class="list-inline-item"><a class="link-light text-decoration-none" href="<?= e($imprint_url) ?>">Impressum</a></li>
                <li class="list-inline-item"><a class="link-light text-decoration-none" href="<?= e($legal_notice_url) ?>">Datenschutz</a></li>
            </ul>
            <?php if (vars('display_login_button')): ?>
                <a class="badge bg-primary text-decoration-none px-2 py-1" href="<?= session('user_id') ? site_url('calendar') : site_url('login') ?>">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    <?= session('user_id') ? lang('backend_section') : lang('login') ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</footer>
```

- [ ] **Step 4: Sprachstrings** – vor der letzten `];` in den drei Sprachdateien einfügen:

```php
$lang['footer_phone'] = 'Telefon';        // german
$lang['footer_phone'] = 'Phone';          // english
$lang['footer_phone'] = 'Telefon';        // turkish
$lang['footer_email'] = 'E-Mail';         // german
$lang['footer_email'] = 'Email';          // english
$lang['footer_email'] = 'E-Posta';        // turkish
$lang['footer_address'] = 'Adresse';      // german
$lang['footer_address'] = 'Address';      // english
$lang['footer_address'] = 'Adres';        // turkish
$lang['footer_phone_hours'] = 'Telefonsprechzeiten'; // german
$lang['footer_phone_hours'] = 'Phone Hours';         // english
$lang['footer_phone_hours'] = 'Telefon Görüşme Saatleri'; // turkish
```

- [ ] **Step 5: `booking_layout.php` Struktur** – Zeilen 36-55 ersetzen:

```php
<div id="main" class="min-vh-100 d-flex flex-column">
    <?php component('site_navbar'); ?>

    <div class="container-xl flex-grow-1">
        <div class="row justify-content-center py-3 py-md-4">
            <div id="book-appointment-wizard" class="col-12 col-lg-10 col-xl-8 col-xxl-7 p-0">
                <?php component('booking_steps'); ?>

                <?php slot('content'); ?>
            </div>
        </div>
    </div>

    <?php component('site_footer'); ?>
</div>
```

- [ ] **Step 6: Stepper-Styles** – `assets/css/frontend.scss:21-49` ersetzen durch:

```scss
#book-appointment-wizard .book-step.active-step {
    background: var(--bs-primary) !important;
    color: #000 !important;
}
#book-appointment-wizard .book-step.active-step strong { color: #000 !important; font-size: 21px !important; }
#book-appointment-wizard .book-step:not(.active-step) { background: transparent !important; border: 1px solid var(--bs-primary); }
#book-appointment-wizard .book-step:not(.active-step) strong { color: var(--bs-primary) !important; font-size: 12px !important; }
```

- [ ] **Step 7: Cookieconsent-Gold** – `assets/js/pages/booking.js:73-86`: `background: '#429a82'` → `'#d1ae5e'`, `text: '#ffffff'` → `'#000000'`, popup `background: '#ffffffbd'` → `'#000000'`, `text: '#666666'` → `'#ffffff'`.

- [ ] **Step 8: Build + Verifizieren**

```bash
docker compose exec php-fpm npx gulp compile
curl -fsS http://localhost/index.php/booking > /tmp/opencode/booking.html
grep -c 'mebeauty-koeln.de/Behandlungen' /tmp/opencode/booking.html
grep -c 'id="steps"' /tmp/opencode/booking.html
grep -c 'id="select-language"' /tmp/opencode/booking.html || true   # Erwartung: 0
grep -c 'mebeauty-koeln.de/Impressum' /tmp/opencode/booking.html
grep -c 'Datenschutz' /tmp/opencode/booking.html
```
Erwartung: Nav-Links, Stepper vorhanden; kein Sprach-Badge; Impressum/Datenschutz-Links vorhanden.

- [ ] **Step 9: Commit** (auf Ansage): `git commit -m "feat(booking): website navbar, gold step bar and 3-column footer"`

---

### Task 3: Account- & Meldungsseiten im selben Design

**Files:**
- Modify: `application/views/layouts/account_layout.php`, `application/views/layouts/message_layout.php`, `application/views/pages/login.php`, `application/views/pages/logout.php`, `application/views/pages/recovery.php`, `application/views/pages/password_reset.php`

**Interfaces:**
- Consumes: `site_navbar`, `site_footer` (aus Task 2), `data-bs-theme="dark"`/Theme aus Task 1.
- Produces: einheitlicher öffentlicher Rahmen für Account-/Meldungsseiten.

- [ ] **Step 1: `account_layout.php` Body** (Zeilen 24-41) ersetzen:

```php
<body>
<?php component('site_navbar'); ?>

<div class="container-xl d-flex justify-content-center my-5">
    <div class="card w-100 shadow-sm" style="max-width: 500px;">
        <div class="card-body p-5">
            <?php slot('content'); ?>
        </div>
    </div>
</div>

<?php component('site_footer'); ?>
```
(„Powered by Easy!Appointments“-Footer entfällt; Scripts/JS-Sektion unverändert lassen.)

- [ ] **Step 2: `message_layout.php` Body** (Zeilen 25-62) ersetzen:

```php
<body>
<?php component('site_navbar'); ?>

<div class="container-xl d-flex justify-content-center my-5">
    <div id="message-frame" class="col-12 col-md-8 col-lg-6 text-center rounded shadow p-4 p-md-5 bg-body">
        <?php slot('content'); ?>
    </div>
</div>

<?php component('site_footer'); ?>
```

- [ ] **Step 3: Logo-Alt-Texte** – in `login.php`, `logout.php`, `recovery.php`, `password_reset.php`: `alt="Easy!Appointments"` → `alt="MeBeauty"` (die `assets/img/logo.png`-Referenz zeigt dank Task 1 bereits das MeBeauty-Logo).

- [ ] **Step 4: Verifizieren**

```bash
for p in login recovery password_reset; do
  curl -fsS "http://localhost/index.php/$p" | grep -oE 'themes/mebeauty\.(min\.)?css|data-bs-theme="dark"|\| MeBeauty|mebeauty-koeln\.de' | sort -u | tr '\n' ' '; echo " <= $p"
done
docker compose exec php-fpm composer test
```
Erwartung: Theme, Dark-Attribut, MeBeauty-Titel und Website-Links auf allen Seiten; PHPUnit grün.

- [ ] **Step 5: Commit** (auf Ansage): `git commit -m "feat(account): apply mebeauty design to account and message pages"`

---

### Task 4: Fehlerseiten mit MeBeauty-Branding

**Files:**
- Modify: `application/views/errors/html/error_404.php`, `error_general.php`, `error_exception.php`, `error_db.php`, `error_php.php`

**Interfaces:** keine neuen. Consumes: Task-1-Assets.

- [ ] **Step 1: Titel** – in allen 5 Dateien `<title>… | Easy!Appointments</title>` → `<title>… | MeBeauty</title>`.

- [ ] **Step 2: Styles** – in allen 5 Dateien den `<style>`-Block durch dieses dunkle Schema ersetzen (Platzhalter `$heading`/`$message` unangetastet lassen):

```css
#error-container {
    background: #111;
    min-width: 450px;
    max-width: 600px;
    margin: auto;
    border: 1px solid #d1ae5e;
    border-radius: .5rem;
    font: 15px/22px 'Karla', Helvetica, Arial, sans-serif;
    color: #fff;
    padding: 24px;
}
#error-container a { color: #d1ae5e; }
#error-container h1 { color: #d1ae5e; margin-top: 0; }
body { background: #000; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
```

- [ ] **Step 3: Verifizieren**

```bash
curl -s -o /tmp/opencode/404.html -w "%{http_code}\n" http://localhost/index.php/dieseseitegibtesnicht
grep -c 'MeBeauty' /tmp/opencode/404.html
grep -c 'Easy!Appointments' /tmp/opencode/404.html || true   # Erwartung: 0
```
Erwartung: HTTP 404, MeBeauty vorhanden, kein EA-Branding.

- [ ] **Step 4: Commit** (auf Ansage): `git commit -m "feat(errors): brand error pages with mebeauty"`

---

### Task 5: E-Mail-Templates mit MeBeauty-Branding

**Files:**
- Modify: `application/libraries/Email_messages.php` (Zeile ~302), `application/views/emails/appointment_saved_email.php`, `appointment_deleted_email.php`, `password_reset_email.php`, `account_recovery_email.php`

**Interfaces:**
- Consumes: `setting('company_logo')` (Base64-Data-URI oder leer), `assets/img/logo.png` (MeBeauty seit Task 1).
- Produces: E-Mail-Embedded-Logo `cid:logo.png` unverändert referenzierbar.

- [ ] **Step 1: Logo-Fallback** – `application/libraries/Email_messages.php`, die Zeile `$php_mailer->addEmbeddedImage(FCPATH . 'assets/img/logo.png', 'logo.png', 'logo.png', 'base64', 'image/png');` ersetzen durch:

```php
$company_logo = setting('company_logo');

if (!empty($company_logo) && str_starts_with((string) $company_logo, 'data:image')) {
    [$meta, $data] = explode(',', (string) $company_logo, 2);
    $mime = str_replace(['data:', ';base64'], '', $meta);
    $php_mailer->addEmbeddedImage(base64_decode($data), 'logo.png', 'logo.png', 'base64', $mime);
} else {
    $php_mailer->addEmbeddedImage(FCPATH . 'assets/img/logo.png', 'logo.png', 'logo.png', 'base64', 'image/png');
}
```

- [ ] **Step 2: Farben/Branding in allen 4 Templates** (suche & ersetze):
  - `#429A82` → `#d1ae5e` (Button-Hintergrund)
  - `#34495e` → `#ca8a04` (Hover)
  - `| Easy!Appointments` → `| MeBeauty` (Titel)
  - `Powered by`-Blöcke: `Easy!Appointments`-Text → `MeBeauty`

```bash
grep -n "Easy!Appointments\|#429A82\|#34495e" application/views/emails/*.php   # Ist-Stand
```

- [ ] **Step 3: E-Mail auslösen & prüfen** (Passwort-Reset an Admin):

```bash
JAR=/tmp/opencode/ea2.jar
curl -fsS -c "$JAR" http://localhost/index.php/recovery -o /tmp/opencode/recovery.html
TOKEN=$(grep -oP '"csrf_token"\s*:\s*"\K[^"]+' /tmp/opencode/recovery.html | head -1)
curl -fsS -b "$JAR" -X POST http://localhost/index.php/recovery/perform \
  --data-urlencode "csrf_token=$TOKEN" \
  --data-urlencode "email=info@mebeauty-koeln.de" -o /dev/null
sleep 2
MSG=$(curl -fsS http://localhost:8025/api/v1/messages | grep -o '"ID":"[^"]*"' | head -1 | cut -d'"' -f4)
curl -fsS "http://localhost:8025/api/v1/message/$MSG" | grep -c "MeBeauty"
curl -fsS "http://localhost:8025/api/v1/message/$MSG" | grep -ci "d1ae5e"
```
Erwartung: MeBeauty vorhanden (≥1), Gold-Farbe `d1ae5e` im HTML. (Falls der Endpunkt anders heißt: `grep "public function" application/controllers/Recovery.php` prüfen.)

- [ ] **Step 4: Commit** (auf Ansage): `git commit -m "feat(emails): mebeauty branding with company logo fallback"`

---

### Task 6: HTTP-Smoke-Test für den Reskin

**Files:**
- Create: `tests/smoke/reskin-smoke.sh`
- Modify: `docs/docker.md` (Abschnitt „Development Commands“)

**Interfaces:**
- Consumes: laufender Docker-Stack, öffentliche Seiten aus Task 1-4, E-Mails aus Task 5.
- Produces: `bash tests/smoke/reskin-smoke.sh` mit Exit-Code 0/1 und „OK/FAIL“-Zeilen.

- [ ] **Step 1: Skript** – `tests/smoke/reskin-smoke.sh`:

```bash
#!/usr/bin/env bash
set -uo pipefail

BASE="${BASE_URL:-http://localhost}"
FAIL=0

check() {
    local name="$1" url="$2" pattern="$3"
    local body
    body=$(curl -fsS "$url" 2>/dev/null) || { echo "FAIL $name (HTTP-Fehler)"; FAIL=1; return; }
    if echo "$body" | grep -q "$pattern"; then echo "OK   $name"; else echo "FAIL $name (Muster fehlt: $pattern)"; FAIL=1; fi
}

check_missing() {
    local name="$1" url="$2" pattern="$3"
    local body
    body=$(curl -fsS "$url" 2>/dev/null) || { echo "FAIL $name (HTTP-Fehler)"; FAIL=1; return; }
    if echo "$body" | grep -q "$pattern"; then echo "FAIL $name (Muster darf nicht vorkommen: $pattern)"; FAIL=1; else echo "OK   $name"; fi
}

check "booking-theme"            "$BASE/index.php/booking" 'themes/mebeauty\.min\.css'
check "booking-dark"             "$BASE/index.php/booking" 'data-bs-theme="dark"'
check "booking-lang-de"          "$BASE/index.php/booking" 'lang="de"'
check "booking-title"            "$BASE/index.php/booking" '| MeBeauty'
check "booking-favicon"          "$BASE/index.php/booking" 'favicon\.ico'
check "booking-steps"            "$BASE/index.php/booking" 'id="step-1"'
check "booking-nav"              "$BASE/index.php/booking" 'mebeauty-koeln\.de/Behandlungen'
check "booking-footer-legal"     "$BASE/index.php/booking" 'mebeauty-koeln\.de/Impressum'
check_missing "booking-no-lang-badge" "$BASE/index.php/booking" 'id="select-language"'

check "login-theme"  "$BASE/index.php/login"  'themes/mebeauty\.min\.css'
check "login-title"  "$BASE/index.php/login"  '| MeBeauty'
check "login-nav"    "$BASE/index.php/login"  'mebeauty-koeln\.de/Kontakt'

code=$(curl -s -o /tmp/opencode/smoke-404.html -w "%{http_code}" "$BASE/index.php/diese-seite-gibt-es-nicht")
if [ "$code" = "404" ]; then echo "OK   404-status"; else echo "FAIL 404-status ($code)"; FAIL=1; fi
grep -q 'MeBeauty' /tmp/opencode/smoke-404.html && ! grep -q 'Easy!Appointments' /tmp/opencode/smoke-404.html \
  && echo "OK   404-branding" || { echo "FAIL 404-branding"; FAIL=1; }

if [ "$FAIL" -ne 0 ]; then echo "SMOKE-TEST FEHLGESCHLAGEN"; exit 1; fi
echo "SMOKE-TEST OK"
```

- [ ] **Step 2: E-Mail-Check ergänzen** (Mailpit, vor der Erfolgszeile einfügen):

```bash
MSG=$(curl -fsS http://localhost:8025/api/v1/messages | grep -o '"ID":"[^"]*"' | head -1 | cut -d'"' -f4)
if [ -n "$MSG" ]; then
    curl -fsS "http://localhost:8025/api/v1/message/$MSG" | grep -qi 'MeBeauty' && echo "OK   email-branding" || { echo "FAIL email-branding"; FAIL=1; }
fi
```

- [ ] **Step 3: Doku** – in `docs/docker.md` unter „Development Commands“ ergänzen:

```markdown
bash tests/smoke/reskin-smoke.sh            # HTTP-Smoke-Test für das MeBeauty-Design
```

- [ ] **Step 4: Lauf & Verifikation**

```bash
mkdir -p /tmp/opencode
bash tests/smoke/reskin-smoke.sh
docker compose exec php-fpm composer test
```
Erwartung: alle Zeilen `OK`, Exit 0; PHPUnit grün.

- [ ] **Step 5: Commit** (auf Ansage): `git commit -m "test(smoke): add reskin http smoke test"`

---

## Self-Review (Spec-Coverage)

- Spec-Abschnitte → Tasks: Theme/Tokens/Fonts/Assets/Migration/Config/Titel/Meta → Task 1; Navbar/Stepper/Footer/Cookieconsent/Sprachbadge weg → Task 2; Account+Message → Task 3; Fehlerseiten → Task 4; E-Mails + Logo-Fallback → Task 5; Smoke-Test + Mailpit → Task 6.
- Keine Platzhalter: alle Snippets/Kommandos konkret.
- Typkonsistenz: `config('mebeauty')`-Keys werden in Task 2 exakt so konsumiert, wie in Task 1 definiert; `cid:logo.png` bleibt über Task 5 stabil; `#steps`/`.book-step`-Vertrag bleibt über Task 2 erhalten (frontend.scss + booking_steps).
- `company_color`/Backend-Theme bleiben unangetastet (Global Constraints).
