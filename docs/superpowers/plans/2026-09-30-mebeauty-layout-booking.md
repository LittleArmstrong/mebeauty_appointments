# MeBeauty Layout Booking-Flow Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Die Buchungsseite erhält das MeBeauty-Website-Layout (Navbar, Intro-Hero „Buchung", Footer, WhatsApp-Button) als SCSS-Nachbau in Repo-Konventionen.

**Architecture:** Bootstrap-Theme `mebeauty` (SCSS) + Komponenten-Styles in `frontend.scss`, vier neue PHP-View-Komponenten, Buchungs-Layout umgebaut. Gulp kompiliert wie bisher.

**Tech Stack:** PHP/CodeIgniter 3, Bootstrap 5 (SCSS), Gulp/Sass, jQuery, Docker Compose.

## Global Constraints

- 1:1-Look zur Website (Layout wie `Kontakt.astro`, `siteTitle` „Buchung"); Website = Design-Quelle der Wahrheit
- Farben: Gold `#d1ae5e`, Goldenrod `#FFCC66`, Seitenhintergrund `#000`, Overlay `rgba(38,35,29,0.6)` → `#26241e`
- Fonts selbst gehostet (WOFF2): Karla 400/500/600/700, Dancing Script 400/600/700; keine Google-Fonts-Requests; Abhaya Libre entfällt
- Breakpoints: md=768px, lg=1024px
- Nur Booking-Flow; Backend, message_layout, account_layout, E-Mails, Fehlerseiten unverändert
- Wizard-Card bleibt hell; `booking_footer` (Sprachwahl/Login) bleibt; `booking_header` entfällt, Steps wandern in eigene Komponente
- Navbar-Links absolut auf `https://mebeauty-koeln.de`; Rechtslinks via `vars('legal_notice_url')`/`vars('imprint_url')` mit Website-Fallback
- Kein Commit/Push ohne explizite Nutzer-Freigabe (AGENTS.md); Commits im Plan sind Vorschläge
- Smoke-Test gegen `http://localhost/index.php/booking` (`BASE_URL` überschreibbar)

---

### Task 0: Umgebung vorbereiten und Doku veröffentlichen

**Files:**
- Create: `.scratch/mebeauty-layout/spec.md`, `.scratch/mebeauty-layout/issues/01-…-05-….md` (Spec+Tickets aus der Session)
- Create: `docs/superpowers/plans/2026-09-30-mebeauty-layout-booking.md` (dieser Plan)

- [ ] **Step 1: Hard-Reset auf den vereinbarten Stand**

```bash
git reset --hard df6b4e3dd4130d443f6b9ff4e4b57f2bc1bc972f
git rev-parse HEAD   # erwartet: df6b4e3dd4130d443f6b9ff4e4b57f2bc1bc972f
```

- [ ] **Step 2: Dependencies + Docker**

```bash
npm ci
docker compose up -d --build
curl -fsS -o /dev/null -w "%{http_code}\n" http://localhost/index.php/booking   # erwartet: 200 (falls Setup-Wizard: Installation einmal durchführen)
```

- [ ] **Step 3: Spec, Tickets und Plandatei anlegen** — Inhalte wie in der Session abgestimmt; Ticket-Status-Zeile `Status: ready-for-agent`.

- [ ] **Step 4: Kein Commit** (Doku-Freigabe durch Nutzer abwarten)

---

### Task 1: Theme-Fundament + Assets (Ticket 01)

**Files:**
- Create: `assets/fonts/{karla-400,karla-500,karla-600,karla-700,dancing-script-400,dancing-script-600,dancing-script-700}.woff2`
- Create: `assets/img/mebeauty/{logo.png,hero.jpg,instagram.png,tiktok.png,whatsapp.png}`
- Create: `assets/css/themes/mebeauty.scss`
- Modify: `application/views/layouts/booking_layout.php` (Theme-Link, Body-Klasse)
- Test: `tests/smoke/mebeauty-layout-smoke.sh` (neu, erste Checks)

**Interfaces:**
- Produces: Theme-CSS `assets/css/themes/mebeauty.css`/`.min.css` (via Gulp), Assets unter `assets/img/mebeauty/`, Fonts unter `assets/fonts/`; CSS-Klasse `site-page` auf `<body>`

- [ ] **Step 1: Fonts herunterladen (WOFF2, latin-Subset)**

```bash
mkdir -p assets/fonts assets/img/mebeauty
UA="Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36"
curl -s -A "$UA" "https://fonts.googleapis.com/css2?family=Karla:wght@400;500;600;700&display=swap" -o /tmp/karla.css
curl -s -A "$UA" "https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&display=swap" -o /tmp/dancing.css
python3 - <<'EOF'
import re, urllib.request, pathlib
def grab(css_file, prefix):
    css = open(css_file).read()
    for subset, body in re.findall(r'/\*\s*([\w-]+)\s*\*/\s*@font-face\s*{([^}]+)}', css):
        if subset != 'latin':
            continue
        w = re.search(r'font-weight:\s*(\d+)', body).group(1)
        u = re.search(r'url\((\S+?)\)', body).group(1)
        pathlib.Path(f'assets/fonts/{prefix}-{w}.woff2').write_bytes(urllib.request.urlopen(u).read())
grab('/tmp/karla.css', 'karla')
grab('/tmp/dancing.css', 'dancing-script')
EOF
ls -la assets/fonts/   # erwartet: 7 woff2-Dateien
```

- [ ] **Step 2: Bilder kopieren**

```bash
cp /home/baris/www/mebeauty/src/images/logo3trans.png assets/img/mebeauty/logo.png
cp /home/baris/www/mebeauty/src/images/Hero_upscaled.jpg assets/img/mebeauty/hero.jpg
cp /home/baris/www/mebeauty/src/images/instagram-logo.png assets/img/mebeauty/instagram.png
cp /home/baris/www/mebeauty/src/images/TikTok_Icon_Black_Square.png assets/img/mebeauty/tiktok.png
cp /home/baris/www/mebeauty/src/images/Whatsapp_Icon.png assets/img/mebeauty/whatsapp.png
```

- [ ] **Step 3: Smoke-Script mit ersten Checks schreiben (RED)**

```bash
mkdir -p tests/smoke
```

```bash
#!/usr/bin/env bash
set -uo pipefail

BASE="${BASE_URL:-http://localhost}"
FAIL=0

check() {
    local name="$1" url="$2" pattern="$3"
    local body
    body=$(curl -fsS "$url" 2>/dev/null) || { echo "FAIL $name (HTTP-Fehler)"; FAIL=1; return; }
    if grep -q "$pattern" <<< "$body"; then echo "OK   $name"; else echo "FAIL $name (Muster fehlt: $pattern)"; FAIL=1; fi
}

check "booking-theme"     "$BASE/index.php/booking" 'themes/mebeauty\.css'
check "booking-black-page" "$BASE/index.php/booking" 'site-page'

exit $FAIL
```

- [ ] **Step 4: Smoke laufen lassen → muss FAILEN**

```bash
bash tests/smoke/mebeauty-layout-smoke.sh
```
Erwartet: beide Checks FAIL (Theme existiert noch nicht).

- [ ] **Step 5: `assets/css/themes/mebeauty.scss` schreiben**

```scss
/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

// MeBeauty Theme - dark booking pages with gold accents.
// Design mirrors the mebeauty-koeln.de website (Astro).

$theme: 'mebeauty' !default;

//
// Color system
//

$white: #fff !default;
$gray-100: #f8f9fa !default;
$gray-200: #e9ecef !default;
$gray-300: #dee2e6 !default;
$gray-400: #ced4da !default;
$gray-500: #adb5bd !default;
$gray-600: #6c757d !default;
$gray-700: #495057 !default;
$gray-800: #343a40 !default;
$gray-900: #212529 !default;
$black: #000 !default;

$blue: #0d6efd !default;
$indigo: #6610f2 !default;
$purple: #6f42c1 !default;
$pink: #d63384 !default;
$red: #dc3545 !default;
$orange: #fd7e14 !default;
$yellow: #ffc107 !default;
$green: #198754 !default;
$teal: #20c997 !default;
$cyan: #0dcaf0 !default;

$gold: #d1ae5e !default;
$goldenrod: #ffcc66 !default;

$primary: $gold !default;
$secondary: $gray-600 !default;
$success: $green !default;
$info: $cyan !default;
$warning: $yellow !default;
$danger: $red !default;
$light: $gray-100 !default;
$dark: $gray-900 !default;

$min-contrast-ratio: 4.5 !default;

//
// Typography
//

$font-family-sans-serif: 'Karla', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif !default;
$font-family-base: $font-family-sans-serif !default;

$link-color: $gold !default;
$link-hover-color: $goldenrod !default;

@import '../../../node_modules/bootstrap/scss/bootstrap';

@font-face {
    font-family: 'Karla';
    font-style: normal;
    font-weight: 400;
    font-display: swap;
    src: url('../../fonts/karla-400.woff2') format('woff2');
}

@font-face {
    font-family: 'Karla';
    font-style: normal;
    font-weight: 500;
    font-display: swap;
    src: url('../../fonts/karla-500.woff2') format('woff2');
}

@font-face {
    font-family: 'Karla';
    font-style: normal;
    font-weight: 600;
    font-display: swap;
    src: url('../../fonts/karla-600.woff2') format('woff2');
}

@font-face {
    font-family: 'Karla';
    font-style: normal;
    font-weight: 700;
    font-display: swap;
    src: url('../../fonts/karla-700.woff2') format('woff2');
}

@font-face {
    font-family: 'Dancing Script';
    font-style: normal;
    font-weight: 400;
    font-display: swap;
    src: url('../../fonts/dancing-script-400.woff2') format('woff2');
}

@font-face {
    font-family: 'Dancing Script';
    font-style: normal;
    font-weight: 600;
    font-display: swap;
    src: url('../../fonts/dancing-script-600.woff2') format('woff2');
}

@font-face {
    font-family: 'Dancing Script';
    font-style: normal;
    font-weight: 700;
    font-display: swap;
    src: url('../../fonts/dancing-script-700.woff2') format('woff2');
}

// Booking flow page shell: black background like the website.
// The wizard card keeps the default light $body-bg on purpose.
.site-page {
    background-color: $black;
}
```

- [ ] **Step 6: `booking_layout.php` anpassen** — Zeile

```php
<link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/themes/' . vars('theme') . '.css') ?>">
```

ersetzen durch

```php
<link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/themes/mebeauty.css') ?>">
```

und `<body>` → `<body class="site-page">`.

- [ ] **Step 7: SCSS kompilieren**

```bash
npx gulp styles
```
Erwartet: erzeugt `assets/css/themes/mebeauty.css` und `mebeauty.min.css` ohne Fehler.

- [ ] **Step 8: Smoke → muss PASSEN**

```bash
bash tests/smoke/mebeauty-layout-smoke.sh
```
Erwartet: beide Checks OK.

- [ ] **Step 9: Commit (nach Freigabe)**

```bash
git add assets/fonts assets/img/mebeauty assets/css/themes/mebeauty.scss assets/css/themes/mebeauty.css assets/css/themes/mebeauty.min.css application/views/layouts/booking_layout.php tests/smoke/mebeauty-layout-smoke.sh
git commit -m "feat(theme): add mebeauty bootstrap theme and website assets"
```

---

### Task 2: Website-Navbar (Ticket 02)

**Files:**
- Create: `application/views/components/site_navbar.php`
- Modify: `application/views/layouts/booking_layout.php` (Navbar einbinden)
- Modify: `assets/css/frontend.scss` (`.site-navbar`-Styles, zunächst statisch)
- Modify: `assets/js/layouts/booking_layout.js` (Mobile-Menü)
- Test: `tests/smoke/mebeauty-layout-smoke.sh` (Navbar-Checks)

**Interfaces:**
- Consumes: Assets aus Task 1
- Produces: Komponente `site_navbar` (ohne Parameter), JS-Toggle für `#navbar-mobile-menu`

- [ ] **Step 1: Smoke-Checks ergänzen (RED)** — vor `exit $FAIL` einfügen:

```bash
check "booking-navbar-logo"  "$BASE/index.php/booking" 'mebeauty/logo\.png'
check "booking-nav-links"    "$BASE/index.php/booking" 'mebeauty-koeln\.de/Behandlungen'
check "booking-mobile-menu"  "$BASE/index.php/booking" 'navbar-mobile-menu'
```

- [ ] **Step 2: Smoke laufen lassen → muss FAILEN**

```bash
bash tests/smoke/mebeauty-layout-smoke.sh
```

- [ ] **Step 3: `site_navbar.php` anlegen**

```php
<header class="site-navbar">
    <nav>
        <div class="site-navbar-inner">
            <a href="https://mebeauty-koeln.de/" class="site-navbar-logo">
                <img src="<?= asset_url('assets/img/mebeauty/logo.png') ?>" alt="MeBeauty logo">
            </a>
            <div class="site-navbar-toggle">
                <button id="navbar-mobile-menu-btn" type="button" aria-controls="navbar-mobile-menu" aria-expanded="false">
                    <span class="visually-hidden">Menü öffnen</span>
                    <svg class="svg-burger" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="svg-close" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div id="navbar-mobile-menu" class="site-navbar-menu">
                <ul>
                    <li><a href="https://mebeauty-koeln.de/">Home</a></li>
                    <li><a href="https://mebeauty-koeln.de/Behandlungen">Behandlungen</a></li>
                    <li><a href="https://mebeauty-koeln.de/Preise">Preise</a></li>
                    <li><a href="https://mebeauty-koeln.de/Kontakt">Kontakt</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
```

- [ ] **Step 4: Navbar in `booking_layout.php` einbinden** — direkt nach `<body class="site-page">`:

```php
    <?php component('site_navbar'); ?>
```

- [ ] **Step 5: `.site-navbar`-Styles in `frontend.scss` anhängen** (statisch; Task 3 stellt auf absolute um)

```scss
/* MeBeauty website layout (booking flow) */

.site-navbar {
    position: static;
    left: 0;
    right: 0;
    z-index: 50;
}

.site-navbar > nav {
    border-bottom: 1px solid #d1ae5e;
    background: rgba(0, 0, 0, 0.7);
    padding: 0.625rem 1rem;
}

@media (min-width: 1024px) {
    .site-navbar > nav {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
}

.site-navbar-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    max-width: 80rem;
    margin: 0 auto;
}

.site-navbar-logo img {
    width: 5rem;
    height: auto;
    margin-right: 0.75rem;
}

.site-navbar-toggle {
    display: flex;
    align-items: center;
    order: 2;
}

.site-navbar-toggle button {
    display: inline-flex;
    align-items: center;
    margin-left: 0.25rem;
    padding: 0.5rem;
    border: 0;
    border-radius: 0.5rem;
    background: transparent;
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.site-navbar-toggle button:hover {
    background: rgba(209, 174, 94, 0.1);
}

.site-navbar-toggle button:focus {
    outline: none;
    box-shadow: 0 0 0 2px #d1ae5e;
}

.site-navbar-toggle svg {
    width: 1.5rem;
    height: 1.5rem;
    color: #d1ae5e;
    fill: currentColor;
}

.site-navbar-toggle .svg-close {
    display: none;
}

.site-navbar-toggle button.open .svg-burger {
    display: none;
}

.site-navbar-toggle button.open .svg-close {
    display: block;
}

@media (min-width: 1024px) {
    .site-navbar-toggle {
        display: none;
    }
}

.site-navbar-menu {
    display: none;
    order: 1;
    width: 100%;
}

.site-navbar-menu.open {
    display: block;
}

@media (min-width: 1024px) {
    .site-navbar-menu {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: auto;
    }
}

.site-navbar-menu ul {
    display: flex;
    flex-direction: column;
    margin: 1rem 0 0;
    padding: 0;
    list-style: none;
    font-weight: 500;
}

@media (min-width: 1024px) {
    .site-navbar-menu ul {
        flex-direction: row;
        margin-top: 0;
        column-gap: 2rem;
    }
}

.site-navbar-menu a {
    display: block;
    padding: 0.5rem 1rem 0.5rem 0.75rem;
    color: #fff;
    text-decoration: none;
    text-shadow: 1px 0 0 currentColor;
}

@media (min-width: 1024px) {
    .site-navbar-menu a {
        padding: 0 0.25rem;
    }

    .site-navbar-menu a:hover {
        color: #d1ae5e;
    }
}
```

- [ ] **Step 6: Mobile-Menü-JS in `assets/js/layouts/booking_layout.js`** — `initialize()` ergänzen und Funktion hinzufügen:

```js
function initialize() {
    App.Utils.Lang.enableLanguageSelection($selectLanguage);
    initializeMobileMenu();
}

function initializeMobileMenu() {
    const $menuBtn = $('#navbar-mobile-menu-btn');
    const $menu = $('#navbar-mobile-menu');

    if (!$menuBtn.length || !$menu.length) {
        return;
    }

    $menuBtn.on('click', () => {
        $menu.toggleClass('open');
        $menuBtn.toggleClass('open');
    });
}
```

- [ ] **Step 7: Build + Smoke**

```bash
npx gulp styles
bash tests/smoke/mebeauty-layout-smoke.sh
```
Erwartet: alle Checks OK.

- [ ] **Step 8: Commit (nach Freigabe)**

```bash
git add application/views/components/site_navbar.php application/views/layouts/booking_layout.php assets/css/frontend.scss assets/css/frontend.css assets/css/frontend.min.css assets/js/layouts/booking_layout.js tests/smoke/mebeauty-layout-smoke.sh
git commit -m "feat(booking): add mebeauty website navbar with mobile menu"
```

---

### Task 3: Intro-Hero „Buchung" (Ticket 03)

**Files:**
- Create: `application/views/components/site_intro.php`
- Modify: `application/views/layouts/booking_layout.php` (Intro einbinden)
- Modify: `assets/css/frontend.scss` (`.site-navbar` auf absolute; `.site-intro`-Styles)
- Test: `tests/smoke/mebeauty-layout-smoke.sh` (Hero-Checks)

**Interfaces:**
- Consumes: Navbar aus Task 2, `assets/img/mebeauty/hero.jpg` aus Task 1
- Produces: Komponente `site_intro` mit Parameter `title` (string)

- [ ] **Step 1: Smoke-Checks ergänzen (RED)**

```bash
check "booking-hero"        "$BASE/index.php/booking" 'site-intro'
check "booking-hero-title"  "$BASE/index.php/booking" '>Buchung</h1>'
```

- [ ] **Step 2: Smoke → muss FAILEN**

- [ ] **Step 3: `site_intro.php` anlegen**

```php
<?php
/**
 * Local variables.
 *
 * @var string $title
 */
?>
<section class="site-intro">
    <img src="<?= asset_url('assets/img/mebeauty/hero.jpg') ?>" alt="<?= e($title) ?>" class="site-intro-image">
    <div class="site-intro-overlay"></div>
    <div class="site-intro-content">
        <h1 class="site-intro-title"><?= e($title) ?></h1>
        <div class="site-intro-underline"></div>
    </div>
</section>
```

- [ ] **Step 4: In `booking_layout.php` einbinden** — nach dem Navbar-Component:

```php
    <?php component('site_intro', ['title' => 'Buchung']); ?>
```

- [ ] **Step 5: CSS in `frontend.scss`** — `.site-navbar { position: static; }` → `position: absolute;` ändern und anhängen:

```scss
.site-intro {
    position: relative;
    display: flex;
    align-items: center;
    height: 45vh;
    background-color: #000;
}

.site-intro-image {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: 50% 0;
}

@media (min-width: 1024px) {
    .site-intro-image {
        object-position: 50% 20%;
    }
}

.site-intro-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(38, 35, 29, 0.6), #26241e);
}

.site-intro-content {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin: 0 auto;
    padding: 0.75rem;
    text-align: center;
}

.site-intro-title {
    width: 100%;
    margin: 2rem 0 0;
    font-family: 'Dancing Script', cursive;
    font-size: 3rem;
    font-style: italic;
    letter-spacing: 0.1em;
    color: #fff;
}

@media (min-width: 768px) {
    .site-intro-title {
        margin: 0 0 0.5rem;
        font-size: 4.5rem;
    }
}

@media (min-width: 1024px) {
    .site-intro-title {
        font-size: 6rem;
    }
}

.site-intro-underline {
    display: inline-block;
    width: 7rem;
    margin-top: 1.25rem;
    border-top: 2px solid #d1ae5e;
}

@media (min-width: 768px) {
    .site-intro-underline {
        width: 9rem;
    }
}

@media (min-width: 1024px) {
    .site-intro-underline {
        width: 14rem;
    }
}
```

- [ ] **Step 6: Build + Smoke → PASS**

```bash
npx gulp styles
bash tests/smoke/mebeauty-layout-smoke.sh
```

- [ ] **Step 7: Commit (nach Freigabe)**

```bash
git add application/views/components/site_intro.php application/views/layouts/booking_layout.php assets/css/frontend.scss assets/css/frontend.css assets/css/frontend.min.css tests/smoke/mebeauty-layout-smoke.sh
git commit -m "feat(booking): add mebeauty intro hero with Buchung title"
```

---

### Task 4: Footer + WhatsApp-Button (Ticket 04)

**Files:**
- Create: `application/views/components/site_footer.php`
- Create: `application/views/components/site_whatsapp.php`
- Modify: `application/views/layouts/booking_layout.php` (Footer/WhatsApp einbinden)
- Modify: `assets/css/frontend.scss` (`.site-footer`, `.site-whatsapp`)
- Test: `tests/smoke/mebeauty-layout-smoke.sh` (Footer-Checks)

**Interfaces:**
- Consumes: Assets/Social-Icons aus Task 1; `vars('legal_notice_url')`, `vars('imprint_url')`
- Produces: Komponenten `site_footer`, `site_whatsapp` (ohne Parameter)

- [ ] **Step 1: Smoke-Checks ergänzen (RED)**

```bash
check "booking-footer-treatments" "$BASE/index.php/booking" 'mebeauty-koeln\.de/Behandlungen#01_Gesichtsbehandlungen'
check "booking-footer-contact"     "$BASE/index.php/booking" 'Fußfallstr\. 25a'
check "booking-footer-legal"       "$BASE/index.php/booking" 'mebeauty-koeln\.de/Impressum'
check "booking-whatsapp"           "$BASE/index.php/booking" 'wa\.me/4917619256689'
```

- [ ] **Step 2: Smoke → muss FAILEN**

- [ ] **Step 3: `site_footer.php` anlegen**

```php
<?php
/**
 * Local variables.
 *
 * @var string $legal_notice_url
 * @var string $imprint_url
 */
$legal_notice_url = vars('legal_notice_url') ?: 'https://mebeauty-koeln.de/Impressum';
$imprint_url = vars('imprint_url') ?: 'https://mebeauty-koeln.de/Datenschutz';
?>
<footer class="site-footer">
    <div class="site-footer-grid">
        <div>
            <h2><a href="https://mebeauty-koeln.de/">MeBeauty</a></h2>
            <div class="site-footer-social">
                <a href="https://www.instagram.com/mebeauty_koeln/" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/instagram.png') ?>" alt="Instagram">
                </a>
                <a href="https://www.tiktok.com/@mebeauty_koeln" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/tiktok.png') ?>" alt="TikTok">
                </a>
                <a href="https://wa.me/4917619256689" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/whatsapp.png') ?>" alt="WhatsApp">
                </a>
            </div>
        </div>
        <div>
            <h3>Behandlungen</h3>
            <ul class="site-footer-treatments">
                <li><a href="https://mebeauty-koeln.de/Behandlungen#01_Gesichtsbehandlungen">Gesichtsbehandlungen</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#02_Haarentfernung">Haarentfernung</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#03_Plasma_Pen">Plasma Pen</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#04_Massagen_mit_Olga">Aroma Massagen (mit Olga)</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#05_Koerperbehandlungen">Körperbehandlungen</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#06_Zahnbleaching">Zahnbleaching</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#07_Manikuere_Pedikuere">Maniküre &amp; Pediküre</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#08_Keratinglaettung">Keratinglättung</a></li>
            </ul>
        </div>
        <div>
            <h3>Telefon</h3>
            <p><a href="tel:+4917619256689">+49 176 192 566 89</a></p>
            <p><a href="tel:+491786827117">+49 178 682 71 17 (Olga)</a></p>
            <h3 class="mt-4">E-Mail</h3>
            <p><a href="mailto:info@mebeauty-koeln.de">info@mebeauty-koeln.de</a></p>
            <p><a href="mailto:olga@rebirth-of-shakti.de">olga@rebirth-of-shakti.de</a></p>
            <h3 class="mt-4">Adresse</h3>
            <a href="https://maps.apple.com/?q=Mebeauty+K%C3%B6ln+51109" target="_blank">
                <p>Fußfallstr. 25a</p>
                <p>51109 Köln</p>
            </a>
        </div>
        <div>
            <h3>Telefonsprechzeiten</h3>
            <p>10:00 - 19:00 Uhr</p>
            <em><p class="site-footer-note">Termin nach Vereinbarung!</p></em>
        </div>
    </div>
    <ul class="site-footer-legal">
        <li>MeBeauty Köln</li>
        <li><a href="<?= e($legal_notice_url) ?>">Impressum</a></li>
        <li><a href="<?= e($imprint_url) ?>">Datenschutz</a></li>
    </ul>
</footer>
```

- [ ] **Step 4: `site_whatsapp.php` anlegen**

```php
<a href="https://wa.me/4917619256689" target="_blank" rel="noopener noreferrer" class="site-whatsapp" aria-label="Chatte mit uns auf WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M20.52 3.48A11.999 11.999 0 0 0 12 0C5.373 0 0 5.373 0 12a11.948 11.948 0 0 0 1.8 6.327L0 24l6.061-1.799A11.95 11.95 0 0 0 12 24c6.627 0 12-5.373 12-12 0-3.193-1.247-6.188-3.48-8.52zM12 22c-1.986 0-3.908-.52-5.57-1.504l-.4-.24-3.572 1.057 1.072-3.466-.26-.413A9.97 9.97 0 0 1 2 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10zm5.2-7.504c-.28-.14-1.654-.817-1.91-.91-.257-.094-.444-.14-.63.14-.187.28-.72.91-.88 1.1-.16.187-.327.21-.607.07s-.985-.364-1.874-1.16c-.693-.62-1.16-1.387-1.297-1.624-.14-.237-.015-.364.104-.485.107-.107.24-.28.36-.42.12-.14.16-.237.24-.396.08-.16.04-.3-.02-.42s-.63-1.52-.86-2.06c-.227-.54-.47-.467-.64-.467-.167 0-.36-.007-.553-.007s-.508.073-.774.36c-.267.286-1.016.993-1.016 2.423 0 1.43 1.04 2.81 1.185 3.004.147.194 2.045 3.117 4.96 4.28.693.298 1.232.476 1.653.61.694.22 1.324.19 1.823.114.556-.084 1.654-.677 1.887-1.33.233-.653.233-1.213.163-1.33-.07-.117-.257-.186-.536-.326z"></path>
    </svg>
</a>
```

- [ ] **Step 5: In `booking_layout.php` einbinden** — nach dem Container-Div (vor den Modals):

```php
    <?php component('site_footer'); ?>
    <?php component('site_whatsapp'); ?>
```

- [ ] **Step 6: CSS in `frontend.scss` anhängen**

```scss
.site-footer {
    border-top: 1px solid #d1ae5e;
    background: #000;
    color: #fff;
    padding: 3rem 1rem 0.5rem;
}

.site-footer-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    width: 100%;
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    text-align: center;
}

@media (min-width: 768px) {
    .site-footer-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        text-align: left;
    }
}

@media (min-width: 1024px) {
    .site-footer-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

.site-footer h2 {
    margin: 0;
    font-family: 'Dancing Script', cursive;
    font-size: 2.25rem;
    font-weight: 600;
    font-style: italic;
    letter-spacing: 0.1em;
    color: #d1ae5e;
}

.site-footer h2 a {
    color: inherit;
    text-decoration: none;
}

.site-footer h3 {
    margin: 0 0 0.75rem;
    padding-bottom: 0.25rem;
    font-family: 'Dancing Script', cursive;
    font-size: 1.875rem;
    font-weight: 600;
    color: #d1ae5e;
}

.site-footer a {
    color: inherit;
    text-decoration: none;
    transition: color 0.15s ease-in-out;
}

.site-footer a:hover {
    color: #d1ae5e;
}

.site-footer-social {
    display: flex;
    justify-content: center;
    column-gap: 1.75rem;
    margin-top: 1rem;
}

@media (min-width: 768px) {
    .site-footer-social {
        justify-content: flex-start;
    }
}

.site-footer-social img {
    width: 32px;
    height: 32px;
    transition: transform 0.15s ease-in-out;
}

.site-footer-social img:hover {
    transform: scale(1.05);
}

.site-footer-treatments {
    margin: 0.75rem 0 0;
    padding: 0;
    list-style: none;
}

.site-footer-treatments li + li {
    margin-top: 0.25rem;
}

.site-footer p {
    margin: 0 0 1rem;
}

.site-footer .site-footer-note {
    margin: 1rem 0 0;
    padding-bottom: 0.25rem;
    font-size: 1.25rem;
    font-weight: 600;
    font-style: italic;
}

.site-footer-legal {
    display: flex;
    justify-content: center;
    column-gap: 1.75rem;
    margin: 2rem 0 0;
    padding: 0;
    list-style: none;
}

.site-whatsapp {
    position: fixed;
    right: 1.5rem;
    bottom: 1.5rem;
    z-index: 40;
    padding: 0.75rem;
    border-radius: 9999px;
    background: #d1ae5e;
    color: #000;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transition: transform 0.15s ease-in-out;
}

.site-whatsapp:hover {
    color: #000;
    transform: scale(1.1);
}

.site-whatsapp svg {
    display: block;
    width: 1.5rem;
    height: 1.5rem;
    fill: currentColor;
}

@media (min-width: 640px) {
    .site-whatsapp svg {
        width: 2.5rem;
        height: 2.5rem;
    }
}
```

- [ ] **Step 7: Build + Smoke → PASS**

```bash
npx gulp styles
bash tests/smoke/mebeauty-layout-smoke.sh
```

- [ ] **Step 8: Commit (nach Freigabe)**

```bash
git add application/views/components/site_footer.php application/views/components/site_whatsapp.php application/views/layouts/booking_layout.php assets/css/frontend.scss assets/css/frontend.css assets/css/frontend.min.css tests/smoke/mebeauty-layout-smoke.sh
git commit -m "feat(booking): add mebeauty website footer and whatsapp button"
```

---

### Task 5: Feinschliff + Smoke + Doku (Ticket 05)

**Files:**
- Modify: `application/views/layouts/booking_layout.php` (booking_header raus, booking_steps rein, Titel/OG-Meta/Theme-Farbe)
- Create: `application/views/components/booking_steps.php` (Steps aus booking_header extrahiert)
- Create: `GLOSSARY.md`, `docs/adr/0001-mebeauty-scss-theme.md`
- Create: `docs/agent-sessions/2026-09-30-session.md`
- Test: `tests/smoke/mebeauty-layout-smoke.sh` (Finale Checks)

- [ ] **Step 1: Smoke-Checks ergänzen (RED)** — inkl. `check_missing`-Helper:

```bash
check_missing() {
    local name="$1" url="$2" pattern="$3"
    local body
    body=$(curl -fsS "$url" 2>/dev/null) || { echo "FAIL $name (HTTP-Fehler)"; FAIL=1; return; }
    if grep -q "$pattern" <<< "$body"; then echo "FAIL $name (Muster darf nicht vorkommen: $pattern)"; FAIL=1; else echo "OK   $name"; fi
}

check        "booking-steps"       "$BASE/index.php/booking" 'id="step-1"'
check        "booking-title"       "$BASE/index.php/booking" 'Buchung | MeBeauty'
check        "booking-lang-badge"  "$BASE/index.php/booking" 'id="select-language"'
check_missing "booking-no-ea-brand" "$BASE/index.php/booking" 'Easy!Appointments'
check_missing "booking-no-header"   "$BASE/index.php/booking" 'id="company-name"'
```

- [ ] **Step 2: Smoke → muss teilweise FAILEN**

- [ ] **Step 3: `booking_steps.php` anlegen** (Steps aus `booking_header.php` extrahiert, zentriert):

```php
<div class="overflow-hidden p-3 p-md-4 d-flex justify-content-center">
    <div id="steps" class="d-inline-block overflow-hidden">
        <div id="step-1" class="book-step active-step d-inline-block float-start rounded text-center bg-white"
             data-tippy-content="<?= lang('service_and_provider') ?>"
             style="height: 45px; width: 45px; padding: 7px; margin-right: 13px; transition: all 0.3s linear;">
            <strong class="d-block text-primary" style="font-size: 21px; cursor: default;">1</strong>
        </div>
        <div id="step-2" class="book-step d-inline-block float-start rounded" data-bs-toggle="tooltip"
             data-tippy-content="<?= lang('appointment_date_and_time') ?>"
             style="height: 35px; width: 35px; background: rgba(0,0,0,0.2); padding: 8px; margin-right: 12px; margin-top: 6px; transition: all 0.3s linear;">
            <strong class="d-block text-center text-white-50" style="font-size: 12px; cursor: default;">2</strong>
        </div>
        <div id="step-3" class="book-step d-inline-block float-start rounded" data-bs-toggle="tooltip"
             data-tippy-content="<?= lang('customer_information') ?>"
             style="height: 35px; width: 35px; background: rgba(0,0,0,0.2); padding: 8px; margin-right: 12px; margin-top: 6px; transition: all 0.3s linear;">
            <strong class="d-block text-center text-white-50" style="font-size: 12px; cursor: default;">3</strong>
        </div>
        <div id="step-4" class="book-step d-inline-block float-start rounded" data-bs-toggle="tooltip"
             data-tippy-content="<?= lang('appointment_confirmation') ?>"
             style="height: 35px; width: 35px; background: rgba(0,0,0,0.2); padding: 8px; margin-right: 0; margin-top: 6px; transition: all 0.3s linear;">
            <strong class="d-block text-center text-white-50" style="font-size: 12px; cursor: default;">4</strong>
        </div>
    </div>
</div>
```

- [ ] **Step 4: `booking_layout.php` finalisieren** — Head:

```php
    <meta name="theme-color" content="#d1ae5e">
```

```php
    <meta property="og:title" content="Buchung | MeBeauty"/>
    <meta property="og:description" content="Termin online buchen – MeBeauty Köln"/>
    <meta property="og:url" content="<?= base_url() ?>">
    <meta property="og:image" content="<?= base_url('assets/img/mebeauty/logo.png') ?>"/>
    <meta property="og:type" content="website">
```

```php
    <title>Buchung | MeBeauty – Termin online buchen</title>
```

Body: `<body class="site-page">`, Navbar, Intro; im Wizard-Card-Block:

```php
                <?php component('booking_header', [
                    'company_name' => vars('company_name'),
                    'company_logo' => vars('company_logo'),
                ]); ?>
```

ersetzen durch `<?php component('booking_steps'); ?>`. Danach `php -l` auf alle geänderten/neuen Views.

- [ ] **Step 5: Build + Smoke → PASS**

```bash
php -l application/views/layouts/booking_layout.php
php -l application/views/components/booking_steps.php
npx gulp build
bash tests/smoke/mebeauty-layout-smoke.sh
```

- [ ] **Step 6: `GLOSSARY.md` anlegen**

```md
# MeBeauty Appointments

Terminbuchungs-Anwendung (Easy!Appointments-Fork) für den MeBeauty-Salon; die Marketing-Website (Astro) liefert das Design.

## Language

**Booking-Flow**:
Der öffentliche Buchungsbereich der App (Buchungsseite mit Wizard); einziger Bereich mit Website-Layout.
_Avoid_: Buchungsseite, Frontend-Bereich

**Wizard-Card**:
Die helle Bootstrap-Card mit dem 4-Schritte-Buchungsformular (Service, Termin, Daten, Bestätigung).
_Avoid_: Buchungsformular-Card, Book-Appointment-Wizard-Container

**mebeauty-Site**:
Die Astro-Website unter mebeauty-koeln.de; Design-Quelle der Wahrheit für die App.
_Avoid_: Website, Hauptdomain, Marketing-Site
```

- [ ] **Step 7: `docs/adr/0001-mebeauty-scss-theme.md` anlegen**

```md
# MeBeauty-Design wird als SCSS/Bootstrap-Theme nachgebaut statt als kompilierte Astro-CSS übernommen

Die Buchungsseite soll exakt wie die mebeauty-Website aussehen. Das Astro-Projekt liefert kompiliertes Tailwind-CSS (inkl. Preflight), das mit den Bootstrap-5-Widgets der App kollidiert und dem Gulp/SCSS-Build widerspricht. Deshalb wird das Design als Bootstrap-Theme `mebeauty` plus Komponenten-Styles in SCSS nachgebaut.

Consequences: Das Design existiert in zwei Codebasen; Änderungen auf der Website müssen manuell im SCSS nachgezogen werden. Die Website bleibt die Design-Quelle der Wahrheit.
```

- [ ] **Step 8: Session-Report** — `docs/agent-sessions/2026-09-30-session.md` (Ziel, Dateien, Entscheidungen, Befehle, Validierung, offene Punkte) + `opencode export`.

- [ ] **Step 9: Abschluss-Report** — `git status`, `git diff --stat`, Zusammenfassung; **kein Commit ohne Freigabe**. Vorschlag: `git commit -m "feat(booking): mebeauty website layout for booking flow"`

---

## Self-Review

- Spec-Coverage: User Stories 1–21 → Tasks 0–5; keine Lücken.
- Keine Platzhalter; alle Code-Snippets vollständig.
- Namenskonsistenz: `.site-navbar`/`.site-intro`/`.site-footer`/`.site-whatsapp`, IDs `navbar-mobile-menu-btn`/`navbar-mobile-menu`, Komponenten `site_navbar`/`site_intro`/`site_footer`/`site_whatsapp`/`booking_steps`.
- Ausdrücklich ausgelassen: message_layout/account_layout, Backend, E-Mails, Fehlerseiten, dunkle Wizard-Card.
