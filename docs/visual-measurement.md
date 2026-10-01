# Visuelle Messung: Buchungsseite vs. mebeauty-Website

Zweck: Die Buchungsseite (Booking-Flow) soll das Layout der Website (https://mebeauty-koeln.de) **1:1** nachbauen. Dieses Dokument beschreibt, wie man das gerendert misst, statt zu raten.

## Voraussetzungen

- Docker (kein sudo, keine Host-Installation nötig)
- Netzwerkzugriff auf `https://mebeauty-koeln.de` und die laufende App (`http://localhost`, via Docker-Compose-Stack)

## Einmaliges Setup (in einem Temp-Verzeichnis, NICHT im Repo)

```bash
mkdir -p /tmp/opencode/pw && cd /tmp/opencode/pw
npm init -y
npm i playwright@1.53.0          # exakt gepinnt: muss zur Browser-Revision des Images passen
docker pull mcr.microsoft.com/playwright:v1.53.0-noble
```

> Hinweis: `/tmp` ist flüchtig — nach Reboot dieses Setup wiederholen. Die Scripts unten sind bewusst klein gehalten.

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

Ausführen (Script liegt in `/tmp/opencode/pw/`):

```bash
docker run --rm --network host \
  -v /tmp/opencode:/out \
  -e PLAYWRIGHT_BROWSERS_PATH=/ms-playwright \
  mcr.microsoft.com/playwright:v1.53.0-noble node /out/pw/measure.js
```

`--network host` ist nötig, damit der Container `http://localhost` (App) erreicht. Browser liegen im Image unter `/ms-playwright` (Env gesetzt), das npm-Paket kommt aus dem gemounteten `/tmp/opencode/pw/node_modules`.

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
