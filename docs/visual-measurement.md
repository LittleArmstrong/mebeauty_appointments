# Visuelle Messung: Buchungsseite vs. mebeauty-Website

Zweck: Die Buchungsseite (Booking-Flow) soll das Layout der Website (https://mebeauty-koeln.de) **1:1** nachbauen. Dieses Dokument beschreibt, wie man das gerendert misst, statt zu raten.

## Voraussetzungen

- Docker (kein sudo, keine Host-Installation nötig)
- Netzwerkzugriff auf `https://mebeauty-koeln.de` und die laufende App (`http://localhost`, via Docker-Compose-Stack)

## Setup (checked in)

Playwright is an exact devDependency (`playwright@1.53.0`, `package.json`). The npm package downloads no browsers:
the browsers come from the Docker image `mcr.microsoft.com/playwright:v1.53.0-noble`, whose version must match the
package exactly. So nothing is installed in `/tmp` and nothing on the host besides `node_modules`:

```bash
npm ci                                               # once per checkout; a worktree may link the main node_modules
docker pull mcr.microsoft.com/playwright:v1.53.0-noble
```

CI and deploy run `npm ci` as before and only get the package; Playwright never runs there (it needs the stack).

## Booking-Flow measurement

```bash
npm run measure:booking                              # both viewports, both themes
npm run measure:booking -- --viewport=390x844 --theme=mebeauty
npm run measure:booking -- --timezone=UTC --locale=en-US --json
```

`scripts/measure-booking-flow.sh` runs `tests/playwright/booking-flow.js` in the image with host networking against
`BASE_URL` (default `http://localhost`, the main checkout's stack; it must be running). The script:

- creates its own test customers and appointments with `tests/smoke/fixtures.php setup` (emails
  `smoke-…@example.com`) and removes them on exit, also when the run fails; it never uses existing customers. A run
  killed hard can leave them behind: `fixtures.php purge` removes every smoke customer;
- loads `/index.php/booking?theme=<theme>`, picks the fixture's service, provider and date, and clicks "next"
  1→2→3→4 and "back" 4→3→2→1 like a customer (button scrolled into view first, then clicked, then waiting until
  the fade has ended). It fills only the required customer fields with the fixture customer and never submits, so
  no booking and no mail is created;
- then opens the preselected link `?service=<id>&provider=<id>` (automatic "next" to step 2);
- runs the browser in `Europe/Berlin` / `de-DE` by default, like the salon's customers; the booking page takes the
  browser timezone, so a UTC browser sees other hours.

Options (repeat `--viewport`/`--theme` for several values): `--viewport=<w>x<h>` (default `1280x800` and
`390x844`), `--theme=<name>` (default `mebeauty` and `default`), `--timezone=<tz>`, `--locale=<locale>`, `--json`.

Reading the output: one row per theme and viewport. Each cell is the Wizard-Card `#book-appointment-wizard`
`top/bottom` in px relative to the viewport after the change; `(no move)` means the scroll position did not change.
"Load" adds the scroll position `y`, "Preselected link" the active step, `y` and the card top. With `--json` every
change is one line with `top`, `bottom`, `scrollY`, `step`, `moved` and the values `before` the click. Bottoms depend
on the content (free hours, customer name), tops on the scroll rule (spec 0020: 16 px, clamped at 0).

Last run (2026-10-17, reproduces the table of ticket 0020/01; the step-4 bottom on the phone differs by the length
of the customer data):

| Theme / viewport | Load | 1→2 | 2→3 | 3→4 | 4→3 | 3→2 | 2→1 | Preselected link |
|---|---|---|---|---|---|---|---|---|
| mebeauty 1280×800 | 408/1277, y 0 | 16/885 | 16/910 (no move) | 16/885 | 16/910 (no move) | 16/885 | 16/885 (no move) | step 2, y 0, top 408 |
| mebeauty 390×844 | 428/1263, y 0 | 16/1145 | 16/1211 | 16/867 | 16/1211 (no move) | 16/1145 | 16/851 | step 2, y 0, top 428 |
| default 1280×800 | 16/891, y 0 | 16/891 (no move) | 16/936 (no move) | 16/891 | 16/936 (no move) | 16/891 | 16/891 (no move) | step 2, y 0, top 16 |
| default 390×844 | 1/843, y 0 | 0/1142 (no move) | 0/1243 | 1/843 | 0/1243 (no move) | 0/1142 | 1/843 | step 2, y 0, top 0 |

## Mess-Scripts

`measure.js` misst Navbar + Hero (Desktop 1280×800, Mobil 390×844); `measure-footer.js` den Footer (Desktop). `measure-rest.js` misst Hero + geöffnetes Mobile-Menü. Kernmuster (Fallstrick: `page.evaluate` muss eine **echte Funktion** bekommen, String-`() => {}` liefert in Playwright 1.53 `undefined`):

```js
const { chromium } = require('playwright');

const TARGETS = [
  { name: 'website', url: 'https://mebeauty-koeln.de/Kontakt/', sel: {
      nav: 'header nav', inner: 'header nav > div', logoImg: 'header a img',
      link1: '#navbar-mobile-menu a', ul: '#navbar-mobile-menu ul', menu: '#navbar-mobile-menu',
      toggle: '#navbar-mobile-menu-btn', hero: '.relative.flex', title: '.relative.flex h1',
      underline: '.mt-5.inline-block', footer: 'footer', fh2: 'footer h2', fh3: 'footer h3',
      fp: 'footer p', fgrid: 'footer > div' } },
  { name: 'local', url: 'http://localhost/index.php/booking', sel: {
      nav: '.site-navbar > nav', inner: '.site-navbar-inner', logoImg: '.site-navbar-logo img',
      link1: '.site-navbar-menu a', ul: '.site-navbar-menu ul', menu: '#navbar-mobile-menu',
      toggle: '#navbar-mobile-menu-btn', hero: '.site-intro', title: '.site-intro-title',
      underline: '.site-intro-underline', footer: '.site-footer', fh2: '.site-footer h2',
      fh3: '.site-footer h3', fp: '.site-footer p', fgrid: '.site-footer-grid' } },
];

function measure(sel) {
  const rect = (el) => {
    if (!el) return null;
    const r = el.getBoundingClientRect();
    const cs = getComputedStyle(el);
    return { x: +r.x.toFixed(1), y: +r.y.toFixed(1), width: +r.width.toFixed(1), height: +r.height.toFixed(1),
      fontSize: cs.fontSize, lineHeight: cs.lineHeight, fontFamily: cs.fontFamily.slice(0, 50),
      fontWeight: cs.fontWeight, fontStyle: cs.fontStyle, letterSpacing: cs.letterSpacing };
  };
  const q = (s) => document.querySelector(s);
  return Object.fromEntries(Object.entries(sel).map(([k, s]) => [k, rect(q(s))]));
}

(async () => {
  const browser = await chromium.launch();
  for (const t of TARGETS) {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const page = await ctx.newPage();
    await page.goto(t.url, { waitUntil: 'networkidle', timeout: 60000 });
    await page.waitForTimeout(1200);
    console.log(JSON.stringify({ target: t.name, metrics: await page.evaluate(measure, t.sel) }));
    await ctx.close();
  }
  await browser.close();
})();
```

Ad-hoc scripts like this one go to the scratchpad (not the repo) and run in the image with the checkout mounted,
so `require('playwright')` resolves from its `node_modules` (`<script-dir>` holds `measure.js`):

```bash
docker run --rm --init --network host -v "$PWD:$PWD:ro" -v <script-dir>:/scripts:ro -w "$PWD" \
  -e NODE_PATH="$PWD/node_modules" mcr.microsoft.com/playwright:v1.53.0-noble node /scripts/measure.js
```

`--network host` lets the container reach `http://localhost` (the app). The browsers live in the image. In a worktree
whose `node_modules` is a link to the main checkout, mount the main checkout instead.

## Referenzwerte (letzte Messung, 2026-09-30)

### Navbar (Desktop 1280px, Website = lokal)

| Metrik | Wert |
|---|---|
| Höhe `nav` | 85px |
| Logo | 80×64px (CSS `width: 5rem; height: 4rem`) |
| Menü-Position | zentriert (nicht rechtsbündig!); `x ≈ 463.5` |
| Link-Text | 20px / Zeilenabstand 28px, Weight 500, Karla |
| Hamburger (Mobil) | 40×40px bei `x=334, y=22` |

### Hero/Intro

| Metrik | Wert |
|---|---|
| Höhe | 45vh (Desktop 800px → 360px) |
| Titel | Dancing Script, italic, Weight 400, `line-height: 1`, Letter-Spacing 0.1em, 48px (Mobil) / 72px (md) / 96px (lg) |
| Unterstrich | 2px Gold, Breite 112px / 144px / 224px, margin-top 20px |

### Footer (Desktop)

| Metrik | Wert |
|---|---|
| Gesamthöhe | 473px |
| Text | 20px / Zeilenabstand 28px, Karla |
| `h2` (MeBeauty) | 36px / Zeilenabstand 40px, Dancing Script, italic |
| `h3` | 30px / 36px, Dancing Script |
| `p`-Abstände | 0 (keine Bottom-Margins; Note: `margin: 1rem 0 0.75rem`) |

### Mobile-Menü (geöffnet, 390px)

| Metrik | Wert |
|---|---|
| Menü | `x=16, y=74, w=358` |
| Link-Höhe | 44px (Padding 8/16/8/12, Text 20px/28px) |
| Aktives `li` | hat 1px Gold-Border (Buchungsseite: kein aktives Item → 44px statt 45px, korrekt) |

## Fallstricke

1. **`page.evaluate` mit String**: `'function() {...}'` liefert in dieser Playwright-Version `undefined` — immer eine echte Funktion übergeben (siehe Script).
2. **Versions-Kopplung**: `playwright`-npm-Paket-Version muss zur Browser-Revision des Docker-Images passen. `v1.53.0-noble` ↔ `playwright@1.53.0`.
3. **Host-Installation**: Auf dem WSL-Host fehlen Browser-System-Libs (libasound etc.); `npx playwright install-deps` bräuchte sudo. Der Docker-Image-Weg umgeht das vollständig.
4. **Wortbreiten**: Textbreiten nur mit demselben Wort vergleichen („Kontakt" vs. „Buchung" haben unterschiedliche Glyphenbreiten in Dancing Script).
5. **Vergleich nur gegen Live-Stand**: Die Website ist die Design-Quelle der Wahrheit; nach Website-Änderungen hier neu messen.
