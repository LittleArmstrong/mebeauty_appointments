# MeBeauty-Layout (Booking-Flow)

Status: ready-for-agent

## Problem Statement

Die Terminbuchungs-App (Easy!Appointments-Fork) trägt noch das Original-Branding: grünes Default-Theme, „Easy!Appointments"-Titel und Standard-Bootstrap-Layout. Besucher, die von der MeBeauty-Website (Astro, dunkles Gold-Design mit Karla/Dancing Script) zur Buchung wechseln, landen in einer optisch fremden Umgebung. Die Buchung wirkt nicht wie Teil der Marke.

## Solution

Die Buchungsseite (Booking-Flow) erhält das Layout der MeBeauty-Website als SCSS-Nachbau in Repo-Konventionen: schwarzer Seitenhintergrund, Website-Navbar (Logo, vier Links auf die Hauptdomain), Intro-Sektion mit Hero-Bild und Dancing-Script-Titel „Buchung" mit goldenem Unterstrich (gleiche Layout-Einstellungen wie die Kontaktseite der Website, aber `siteTitle` = „Buchung"), der bestehende Buchungs-Wizard als helle Card darunter, Website-Footer (Social-Icons, Behandlungsliste, Kontaktdaten, Sprechzeiten, Rechtslinks) und ein schwebender WhatsApp-Button. Das Backend und alle übrigen Seiten bleiben unverändert.

## User Stories

1. Als Besucher, der von der Website zur Buchung wechselt, will ich dort dasselbe dunkle Design mit Gold-Akzenten sehen, so dass die Buchung wie ein Teil der Website wirkt.
2. Als Besucher will ich die Hauptnavigation (Home, Behandlungen, Preise, Kontakt) mit Links auf die Website sehen, so dass ich jederzeit zurück navigieren kann.
3. Als Besucher will ich das MeBeauty-Logo in der Navigation sehen, so dass ich das Branding wiedererkenne.
4. Als Besucher auf dem Smartphone will ich die Navigation über ein Hamburger-Menü bedienen, so dass sie auf kleinen Bildschirmen nutzbar ist.
5. Als Besucher will ich eine Intro-Sektion mit Hero-Bild und dem Titel „Buchung" im Website-Stil (Dancing Script, goldener Unterstrich) sehen, so dass die Buchungsseite wie die Unterseiten der Website wirkt.
6. Als Besucher will ich das Buchungsformular (Wizard mit Schritt-Indikator) unter der Intro-Sektion nutzen, so dass der Buchungsablauf unverändert funktioniert.
7. Als Besucher will ich den Sprachumschalter und den Login-/Backend-Link des Buchungs-Wizards weiterhin nutzen, so dass keine bestehende Funktion verloren geht.
8. Als Besucher will ich im Footer Telefonnummern, E-Mail-Adressen und die Anschrift finden, so dass ich den Salon direkt kontaktieren kann.
9. Als Besucher will ich im Footer die Telefonsprechzeiten (inkl. „Termin nach Vereinbarung!") sehen, so dass ich weiß, wann ich anrufen kann.
10. Als Besucher will ich im Footer die Social-Media-Profile (Instagram, TikTok, WhatsApp) verlinkt sehen, so dass ich dem Salon folgen kann.
11. Als Besucher will ich im Footer die Behandlungsliste mit Links auf die Website-Behandlungsseiten sehen, so dass ich das Angebot erkunden kann, ohne die App zu verlassen.
12. Als Besucher will ich im Footer Links zu Impressum und Datenschutz, so dass ich die rechtlichen Seiten erreiche.
13. Als Besucher will ich einen schwebenden WhatsApp-Button, so dass ich direkt einen Chat starten kann.
14. Als Besucher will ich im Browser-Tab den Titel „Buchung | MeBeauty – Termin online buchen" und MeBeauty-Meta-Tags, so dass die App einheitlich gebrandet ist.
15. Als Betreiber will ich, dass die Rechtslinks (Impressum/Datenschutz) über die bestehenden URL-Einstellungen gepflegt werden mit den Website-Seiten als Fallback, so dass keine URLs im Code hartkodiert sind.
16. Als Betreiber will ich ein eigenes Bootstrap-Theme „mebeauty" mit zentralen Design-Tokens (Gold, Schwarz, Karla, Dancing Script), so dass Farben und Schriften an einer Stelle änderbar sind.
17. Als Betreiber will ich selbst gehostete Schriften ohne Google-Fonts-Requests, so dass die Seite ohne externe Abhängigkeiten lädt.
18. Als Betreiber will ich, dass das Backend und dessen Theme-Auswahl unverändert bleiben, so dass der Admin-Bereich stabil bleibt.
19. Als Entwickler will ich einen HTTP-Smoke-Test für die Buchungsseite, so dass das Layout automatisiert verifizierbar ist.
20. Als Entwickler will ich, dass der Gulp-Asset-Build fehlerfrei läuft, so dass Deployments nicht brechen.
21. Als Entwickler will ich eine ADR, die begründet, warum das Astro-Design als SCSS nachgebaut (und nicht als kompiliertes CSS vendored) wird, so dass spätere Leser die Entscheidung verstehen.

## Implementation Decisions

- Kein Vendoring der kompilierten Astro-CSS: Das Design wird als Bootstrap-Theme (`mebeauty`) plus Komponenten-Styles in SCSS nachgebaut; der bestehende Gulp-Build kompiliert alles. Optisches Ziel ist 1:1-Gleichheit mit der Website (Layout wie `Kontakt.astro`, `siteTitle` „Buchung").
- Design-Tokens: Gold `#d1ae5e` (Primary), Goldenrod `#FFCC66`, Seitenhintergrund Schwarz, Basisschrift Karla, Akzentschrift Dancing Script; Breakpoints wie Tailwind (md 768px, lg 1024px).
- Die Buchungsseite lädt das mebeauty-Theme fest; das Backend behält seine eigene Theme-Auswahl.
- Neue View-Komponenten: Website-Navbar, Intro-Hero, Website-Footer, WhatsApp-Button. Der bisherige Buchungs-Kopfbereich (Firmenlogo/-name) entfällt; der Schritt-Indikator wandert in eine eigene Komponente; der Card-Footer mit Sprachwahl und Login bleibt.
- Navbar: Logo + vier Links absolut auf die Hauptdomain (Home, Behandlungen, Preise, Kontakt), absolute Positionierung über dem Hero, Hamburger-Menü mit Vanilla-JS.
- Intro: Hero-Bild, Gradient-Overlay (rgba(38,35,29,0.6) → #26241e), Titel in Dancing Script mit goldenem Unterstrich, kein zusätzlicher Button (wie auf der Kontaktseite).
- Footer: vier Spalten (Marke + Social-Icons, Behandlungen, Kontaktdaten, Sprechzeiten) + untere Zeile (MeBeauty Köln, Impressum, Datenschutz). Kontaktdaten werden aus der Website übernommen (hartcodiert), Behandlungen verlinken auf die Website-Anker, Rechtslinks aus den bestehenden Einstellungen mit Website-Fallback.
- Schriften: Karla (400/500/600/700) und Dancing Script (400/600/700) als WOFF2 selbst gehostet; Abhaya Libre entfällt (auf der Website nirgends genutzt).
- Bild-Assets (Logo, Hero, Social-Icons) werden aus dem Website-Projekt übernommen.
- Wizard-Card bleibt hell (Bootstrap-Standard); keine dunkle Umgestaltung der Formular-Styles in diesem Spec.
- Browser-Titel und Open-Graph-Meta werden auf MeBeauty angepasst.
- Vorarbeiten: Hard-Reset des Arbeitsbranches auf den vereinbarten Basis-Commit (verwirft den früheren Reskin-Versuch).

## Testing Decisions

- Ein einziger Test-Seam: die öffentliche HTTP-Schnittstelle der Buchungsseite (curl-Smoke-Script). Gute Tests prüfen nur äußeres Verhalten: HTTP-Status und gerenderte HTML-Marker (geladenes Theme-CSS, Navbar-Links, Hero-Titel, Footer-Kontaktdaten, WhatsApp-Link) — keine internen DOM-/Klassendetails.
- Kein neuer Unit-Test-Seam: Views sind im Projekt nicht unit-getestet; die bestehende PHPUnit-Suite deckt nur Helper ab.
- Prior Art: das Smoke-Script-Muster des früheren Reskin-Branches (`tests/smoke/reskin-smoke.sh`, curl + grep-Checks).
- Zusätzliche Build-/Syntaxprüfungen: `php -l` auf geänderte Views, Gulp-Build ohne Fehler.

## Out of Scope

- Bestätigungs-, Stornierungs- und Nachrichtenseiten (message_layout) sowie Login/Account — behalten ihr bisheriges Aussehen.
- Backend (bleibt unberührt; keine Titel-/Logo-Änderungen).
- E-Mail-Templates und Fehlerseiten.
- Dunkle Umgestaltung der Wizard-Card/Formulare (hell bleibt vorerst).
- Inhalts-Duplikate: Behandlungen/Preise werden nur auf der Website gepflegt, die App verlinkt.
- Änderungen am Astro-Website-Projekt selbst.

## Further Notes

- Die Website bleibt die Design-Quelle der Wahrheit; Anpassungen dort müssen manuell im SCSS nachgezogen werden (in der ADR festgehalten).
- GLOSSARY.md wird um die Begriffe Booking-Flow, Wizard-Card und mebeauty-Site ergänzt.
- Der abschließende Session-Report folgt AGENTS.md (git status/diff, kein Commit ohne Freigabe).
