# Branding-Konsolidierung: Favicon, Titel, Logo, Backend-Header, Erfolgsseite & Social Card

Status: resolved
Created: 2026-10-02
Closed: 2026-10-02
Priority: –
Depends-on: –

## Problem Statement

Der App (Easy!Appointments-Fork) sieht man an mehreren Stellen noch die Herkunft als Easy!Appointments an, obwohl sie als „MeBeauty“ auftreten soll:

1. **Seitentitel** enden auf `| Easy!Appointments` (Backend, Account, Installation, vier E-Mail-Templates, drei Fehlerseiten); nur Booking- und Message-Layout sind bereits auf „MeBeauty“.
2. Das **App-Logo** (`logo.png`, quadratisch 128×128) ist noch das grüne Easy!Appointments-Logo und taucht im Backend-Header, auf Login/Recovery/Logout/Password-Reset, in der About-Seite und als eingebettetes E-Mail-Logo auf.
3. Der **Backend-Header** zeigt hart „EASY!APPOINTMENTS“ + „Online Appointment Scheduler“ und ignoriert das vorhandene `company_logo`-Setting.
4. Die **Erfolgsseite** „Termin erfolgreich gebucht“ ist in Bootstrap-Grün (`text-success`) statt in der MeBeauty-Theme-Farbe (Gold).
5. Die **MeBeauty-Assets** (`assets/img/mebeauty/`) existieren bereits, werden aber inkonsistent referenziert: zwei byte-identische Favicon-Kopien (`favicon.ico` und `mebeauty/favicon.ico`), gemischte 192×192-Icon-Quellen.
6. Das **Open-Graph-Vorschaubild** (`og:image`) zeigt aktuell auf `mebeauty/logo.png` statt auf die Social-Card, und die vorhandene Social-Card ist noch die alte Easy!Appointments-Grafik.

Der Nutzer wünscht **minimalen Eingriff**: Wo möglich per Datei-Ersatz statt Referenz-Umbau, und eine einzige Quelle pro Asset.

## Solution

Konsistente MeBeauty-Marke mit minimalem Eingriff:

- **Eine Favicon-Quelle** (root `assets/img/favicon.ico`); die zweite Kopie wird entfernt.
- **App-Logo per Datei-Ersatz:** `logo.png` wird durch ein quadratisches MeBeauty-Logo ersetzt, sodass alle bestehenden Verweise automatisch folgen.
- **Dynamische Titel + Backend-Header** über die Settings `company_name`/`company_logo` (multi-company-fähig).
- **Erfolgsseite** auf Theme-Gold umfärben.
- **Social Card:** `og:image` wieder auf `social-card.png`; die Datei bleibt vorerst, bis der Nutzer eine fertige MeBeauty-Social-Card liefert.
- **Lizenz-Attribution erhalten:** Easy!Appointments + Alex Tselegidis + GPL-3.0 im Backend-Footer bleiben unangetastet.

## User Stories

1. Als Salonbetreiberin will ich, dass alle Seiten (Booking-Flow, Backend, Account, Installation, E-Mail, Fehlerseiten) einen MeBeauty-Titel statt „Easy!Appointments“ zeigen, so dass die Marke überall stimmt.
2. Als Salonbetreiberin will ich, dass der Titel automatisch aus dem Firmenname-Setting (`company_name`) stammt, so dass bei einer Umbenennung oder Nutzung für einen anderen Betrieb keine Code-Änderung nötig ist.
3. Als Salonbetreiberin will ich, dass der öffentliche Buchungsseiten-Titel auf „Buchung | MeBeauty“ vereinfacht wird (Tagline „– Termin online buchen“ entfällt), so dass der Titel der Vorgabe „nach | einfach MeBeauty“ entspricht.
4. Als Salonbetreiberin will ich, dass auch Installation und Fehlerseiten einen gültigen Marken-Titel zeigen, so dass selbst im Fehlerfall kein „Easy!Appointments“ auftaucht.
5. Als Salonbetreiberin will ich, dass es nur noch eine Favicon-Quelle gibt (root `favicon.ico`), so dass keine doppelte Datei gepflegt werden muss.
6. Als Salonbetreiberin will ich, dass das App-Logo (`logo.png`) ein quadratisches MeBeauty-Logo ist, so dass Backend-Header, Login/Recovery/Logout/Password-Reset, About und E-Mail-Logo nicht mehr das alte Easy!Appointments-Logo zeigen.
7. Als Salonbetreiberin will ich, dass der Logo-Ersatz durch Datei-Austausch erfolgt und alle bestehenden Verweise unverändert bleiben, so dass der Eingriff minimal und die Rücknahme trivial ist.
8. Als Salonbetreiberin will ich, dass der Backend-Header den Firmennamen und ein Firmen-Logo dynamisch zeigt (hochgeladenes Logo überschreibt das Standard-Logo), so dass ein Logo-/Namenswechsel ohne Code-Eingriff möglich ist.
9. Als Salonbetreiberin will ich im Backend-Header einen passenden Untertitel („Kosmetik & Beauty Studio“), so dass der alte Slogan „Online Appointment Scheduler“ ersetzt wird.
10. Als Salonbetreiberin will ich, dass die Erfolgsseite „Termin erfolgreich gebucht“ in der Theme-Farbe (Gold) statt in Grün erscheint, so dass sie zum MeBeauty-Design passt.
11. Als Salonbetreiberin will ich, dass die Erfolgsseiten-Überschrift trotz Gold gut lesbar bleibt (dunklerer Goldton), so dass der Kontrast auf hellem Grund ausreicht.
12. Als Salonbetreiberin will ich, dass die Social-Media-Vorschau (`og:image`) wieder auf die Social-Card zeigt, so dass geteilte Buchungslinks ein passendes Vorschaubild haben.
13. Als Salonbetreiberin will ich, dass die vorhandene Social-Card zunächst unverändert bleibt, bis ich eine fertige MeBeauty-Social-Card nachliefere, so dass nichts kaputtgeht und die Umstellung später nur ein Datei-Austausch ist.
14. Als Salonbetreiberin will ich, dass die Lizenz-Attribution (Easy!Appointments + Alex Tselegidis + GPL-3.0) im Backend-Footer erhalten bleibt, so dass die GPL-/CC-BY-Pflichten erfüllt sind.
15. Als Entwickler will ich, dass der Begriff „Firmenfarbe (Company Color)“ im Glossar dokumentiert wird, so dass Theme-Primärfarbe und dynamisch überschreibbare Firmenfarbe klar getrennt sind.
16. Als Entwickler will ich, dass die Erfolgsseiten-Färbung über Bootstrap-Klassen (`text-primary`/`bg-primary`) erfolgt, so dass sie automatisch der Firmenfarbe folgt.
17. Als Entwickler will ich, dass der Firmenname-Wert in der Datenbank von „Test“ auf „MeBeauty“ korrigiert wird, so dass die dynamischen Titel tatsächlich „MeBeauty“ zeigen.
18. Als Entwickler will ich, dass keine JS-/SCSS-Quelle geändert werden muss, so dass kein `npm run build` erforderlich ist.
19. Als Entwickler will ich, dass die wirkungslose Tippfehler-Klasse `min-vh-100-` auf der Erfolgsseite zu `min-vh-100` korrigiert wird, so dass die Layout-Klasse greift.

## Implementation Decisions

- **Favicon — eine Quelle:** Alle Layouts verweisen auf root `assets/img/favicon.ico`. Die Doppel-Kopie `assets/img/mebeauty/favicon.ico` wird gelöscht; der eine abweichende Verweis im Booking-Layout wird auf root zurückgestellt. Die 192×192-Icon-Verweise bleiben unverändert (`logo.png` wird durch den Datei-Ersatz automatisch zum quadratischen MeBeauty-Logo; `booking` verweist weiter auf `mebeauty/icon-192.png`).
- **App-Logo — Datei-Ersatz statt Referenz-Umbau:** `logo.png` (128×128, quadratisch) wird durch ein quadratisches MeBeauty-Logo ersetzt, erzeugt durch Resize aus `assets/img/mebeauty/icon-192.png` (192×192) auf 128×128. Dadurch folgen Backend-Header, Login/Recovery/Logout/Password-Reset, About, E-Mail-Logo und die 192×192-Icons ohne Referenz-Änderung. Das rechteckige Marketing-Wordmark `mebeauty/logo.png` bleibt für Navbar/`og:image`-Fallback unangetastet. Falls das resizte Logo nicht gefällt, liefert der Nutzer ein eigenes quadratisches Logo nach.
- **Titel dynamisch:** Backend-, Account-, Message- und Booking-Layout sowie die vier E-Mail-Templates verwenden `setting('company_name', 'MeBeauty')` als Marken-Suffix nach dem `|`. Installation und die drei Fehlerseiten bleiben statisch „MeBeauty“, weil dort keine Datenbank garantiert ist (Installation vor der Settings-Befüllung; `error_db` bei defekter DB). Der Buchungsseiten-Titel wird auf „Buchung | <Firmenname>“ vereinfacht; `og:title` folgt derselben Logik.
- **Backend-Header dynamisch:** Firmenname aus `setting('company_name', 'MeBeauty')`, Logo aus `setting('company_logo')` mit Fallback auf `logo.png` (nun MeBeauty), Untertitel „Kosmetik & Beauty Studio“. Das `company_logo`-Setting liegt als Data-URI (Base64) vor und wird unverändert direkt als `src` verwendet.
- **Erfolgsseite Gold:** Kreis `bg-primary bg-opacity-10`, Icon `text-primary`, Überschrift `text-primary-emphasis` (dunklerer Goldton für Kontrast). Die `text-muted`-Absätze bleiben unverändert.
- **Social Card:** `og:image` im Booking-Layout zeigt wieder auf `assets/img/social-card.png`. Die Datei bleibt vorerst die alte Easy!Appointments-Grafik; der Nutzer liefert später eine MeBeauty-Social-Card, die dann nur als Datei ersetzt wird. `social-card.png` wird daher **nicht** gelöscht.
- **Firmenname-Wert:** Einmalige Änderung des Settings-Werts `company_name` von „Test“ auf „MeBeauty“ (Daten-Änderung, keine Schema-Migration).
- **Glossar:** Neuer kanonischer Begriff **Firmenfarbe (Company Color)** — die über die Business Settings dynamisch überschreibbare Primärfarbe (via `company_color_style`), getrennt von der festen Theme-Primärfarbe des `mebeauty`-Themes (Gold `#d1ae5e`, vgl. ADR-0001). Die Farb-Umstellung nutzt bewusst Bootstrap-`primary`-Klassen, damit sie der Firmenfarbe folgt.
- **Lizenz-Attribution:** Backend-Footer (Easy!Appointments + Alex Tselegidis + GPL-3.0-Link samt `logo-16x16.png`/`alextselegidis-logo-16x16.png`) bleibt unangetastet.

## Testing Decisions

- **Primärer Seam: die gerenderte HTML-Ausgabe** der Layouts/Seiten — `<title>`, Favicon-`href`, Logo-`src` und `og:image`. Verifiziert per HTTP-Smoke (curl + grep auf die erwarteten Werte), nach dem Muster des bestehenden `tests/smoke/mebeauty-layout-smoke.sh`.
- **Zweiter Seam: die Asset-Dateien selbst** — Favicon/Logo/Icon per Prüfsumme und Dimensionen (z. B. `file`/`md5sum`), um sicherzustellen, dass der Datei-Ersatz stattgefunden hat und quadratisch/transparent ist.
- **Kein neuer Unit-Test:** Es wird keine Geschäftslogik geändert; die dynamische Backend-Header-Verdrahtung ist eine triviale `setting()`-Expression, die derselbe HTTP-Smoke abdeckt.
- **Was gute Tests ausmacht:** nur äußeres Verhalten prüfen — welcher Titel, welches Bild, welche `src`/`href` am Ende ausgeliefert werden — nicht die interne Hilfsimplementierung.

## Out of Scope

- Ersatz/Entfernen der „About Easy!Appointments“-Seite (`about.php`) — dort wird nur das Logo-Image über den `logo.png`-Datei-Ersatz mitgezogen, der Inhalt bleibt.
- Backend-Footer-Attribution ändern oder entfernen (Lizenzpflicht).
- `twitter:card`-Tags ergänzen.
- Dynamischer Firmenname auf Installations-/Fehlerseiten.
- Account-Seiten auf dynamisches `company_logo` umstellen (folgen automatisch über den Datei-Ersatz).
- Finale MeBeauty-Social-Card (liefert der Nutzer nach).
- Installations-Seed von `company_name` ändern (der generische Install-Standard bleibt).

## Further Notes

- Keine JS-/SCSS-Änderung, daher kein `npm run build` nötig.
- Keine Schema-Migration; lediglich eine einmalige Settings-Wert-Änderung (`company_name`).
- Im Working Tree existieren bereits uncomittete Nutzer-Änderungen an `application/language/german/translations_lang.php`, `application/views/layouts/message_layout.php` und `application/views/pages/booking_confirmation.php` — diese müssen erhalten bleiben.
- Gemäß AGENTS.md: keine Git-Commits ohne ausdrückliche Nutzer-Freigabe; vor Umsetzung einen Task-Branch anlegen.
- Für die spätere Verifikation der Social-Vorschau sind öffentlich erreichbare URLs nötig (Facebook/Meta Sharing Debugger, LinkedIn Post Inspector); lokal via Tunnel (`ngrok`/`cloudflared`).
