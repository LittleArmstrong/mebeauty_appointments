# MeBeauty-Branding dynamisieren: ursprüngliche Easy!Appointments-Oberflächen wieder settings-getrieben

Status: resolved
Created: 2026-10-03
Closed: 2026-10-03
Priority: –
Depends-on: –

## Problem Statement

Der Fork hat die ursprünglichen Easy!Appointments-Oberflächen — Backend-Header, E-Mail-Versand, Wizard-Card (Buchungsformular), Seitentitel und Fehlerseiten — fest auf „MeBeauty" gebrandet. Dadurch ist für eine Wiederverwendung (anderer Kunde) oder eine Umbenennung ein Code-Eingriff nötig; hochgeladene Firmenwerte (`company_logo`, `company_color`) wirken nur unvollständig.

Konkret hart verdrahtet bzw. mit „MeBeauty"-Fallback:

- `theme`-Default ist `mebeauty` (Migration `070`) statt des 1.6.0-Standards `default`. (Migration 070 und ihre Umkehrung 072 wurden später entfernt — Theme-Default blieb `default`, siehe ADR 0005.)
- `assets/img/logo.png` wurde per Datei-Ersatz durch ein MeBeauty-Logo ersetzt; das generische Logo fehlt.
- Backend-Header: Fallback `assets/img/mebeauty/logo-128.png`, `setting('company_name', 'MeBeauty')`, Untertitel „Kosmetik & Beauty Studio" hart.
- E-Mail-Logo: fest `assets/img/mebeauty/logo-192.png`; hochgeladenes `company_logo` wird in E-Mails ignoriert.
- Seitentitel (Layouts + E-Mail-Templates) verwenden `setting('company_name', 'MeBeauty')`.
- Fehlerseiten zeigen statisch „MeBeauty".
- Wizard-Card zeigt das Firmenlogo/-name nicht mehr (der 1.6.0-Kopf wurde zugunsten der Website-Navbar entfernt).

Die Website-Komponenten (Navbar, Intro-Hero, Footer, WhatsApp-Button) sind davon **getrennt** zu betrachten: sie sind ein je-Kunde-Prozess und nicht Teil dieser Dynamisierung.

## Solution

Die ursprünglichen EA-Oberflächen werden wieder settings-getrieben, mit 1.6.0-Defaults; der MeBeauty-Look entsteht ausschließlich über die bestehenden Settings (`theme`, `company_name`, `company_logo`, `company_color`) plus ein neues Schalter-Setting für den Wizard-Card-Kopf. Fehlerseiten (ohne DB-Zugriff) lesen eine Config-Konstante statt Harttext. Das E-Mail-Logo wird über das `company_logo`-Setting angebunden, ohne Temp-Datei und ohne Umstellung des Base64-Speicherformats.

## User Stories

1. Als Betreiber will ich, dass eine frische Installation wieder wie Easy!Appointments 1.6.0 aussieht (generisches Theme, generisches Logo, generischer Name), so dass „MeBeauty" nicht fest eingebaut ist.
2. Als Betreiber will ich, dass ich den MeBeauty-Look allein über die Einstellungen herstellen kann (Theme `mebeauty`, Firmenname, Firmenlogo, Firmenfarbe), so dass kein Code-Eingriff nötig ist.
3. Als Betreiber will ich, dass das Default-Theme wieder `default` ist und `mebeauty` als Option wählbar bleibt, so dass die Theme-Auswahl wieder den 1.6.0-Zustand abbildet.
4. Als Betreiber will ich, dass das Standard-App-Logo wieder das generische Easy!Appointments-Logo ist, so dass ohne Firmenlogo keine MeBeauty-Grafik erscheint.
5. Als Betreiber will ich, dass der Backend-Header den Firmennamen und ein hochgeladenes Firmenlogo dynamisch zeigt (generisches Logo als Fallback), so dass ein Namens-/Logo-Wechsel ohne Code möglich ist.
6. Als Betreiber will ich, dass der Backend-Header-Untertitel wieder den 1.6.0-Text („Online Appointment Scheduler") zeigt, so dass kein MeBeauty-Slogan hart eingebaut ist.
7. Als Betreiber will ich, dass E-Mails das hochgeladene Firmenlogo verwenden (generisches Logo als Fallback), so dass die Marke auch in Benachrichtigungs-E-Mails stimmt.
8. Als Betreiber will ich, dass E-Mail-Betreffzeilen/-Titel den Firmennamen aus den Einstellungen verwenden, so dass sie sich automatisch mit dem Firmennamen ändern.
9. Als Betreiber will ich, dass alle Seiten- und E-Mail-Titel den Firmennamen dynamisch verwenden (generischer Fallback statt „MeBeauty"), so dass Umbenennungen überall durchschlagen.
10. Als Betreiber will ich, dass Fehlerseiten (auch bei defekter DB) einen gültigen Marken-Namen zeigen, der zentral konfigurierbar ist (Config-Konstante statt drei Harttexte), so dass ich ihn für einen anderen Kunden an einer Stelle ändern kann.
11. Als Besucher will ich, dass die Wizard-Card wieder das Firmenlogo und den Firmennamen im Kopf anzeigen kann (1.6.0-Verhalten), so dass die Buchungsformular-Karte eigenständig gebrandet ist.
12. Als Betreiber will ich, dass ich den Wizard-Card-Kopf (Logo + Name) per Einstellung ausblenden kann, so dass die Card bei aktivem Website-Layout kompakt bleibt (aktueller Look).
13. Als Betreiber will ich, dass die Wizard-Card-Farben (Schritt-Indikator, Auswahl-Highlights) der Theme-/Firmenfarbe folgen, so dass sie ohne zusätzliche Einstellung konsistent eingefärbt sind.
14. Als Betreiber will ich, dass die Erfolgsseite („Termin erfolgreich gebucht") weiterhin der Theme-/Firmenfarbe folgt, so dass sie ohne Mehraufwand zur Marke passt.
15. Als Entwickler will ich, dass neue Settings über die bestehende Migrations-Konvention hinzugefügt werden, so dass Deployments deterministisch bleiben.
16. Als Entwickler will ich, dass der Backend-Header unter dem `mebeauty`-Theme schwarz erscheint und die Firmenfarbe den Header nicht mehr einfärbt, so dass Theme-Vorrang vor Firmenfarbe gilt.
17. Als Entwickler will ich, dass nginx erst nach php-fpm startet und sich bei Startreihenfolge-Problemen selbst neu startet, so dass `localhost` zuverlässig erreichbar ist.

## Implementation Decisions

- **Theme-Default:** Der `theme`-Standard wird wieder `default`; `mebeauty` bleibt eine wählbare Option. Die Umkehrung der Migration `070` erfolgte über eine neue Migration. (Später wurden Migration 070 und ihre Umkehrung 072 entfernt — Theme-Default blieb `default`, siehe ADR 0005.)
- **Generisches Logo:** `assets/img/logo.png` wird wieder das generische Easy!Appointments-Logo; MeBeauty-Logos bleiben nur unter `assets/img/mebeauty/`.
- **Backend-Header:** Firmenname aus `company_name` (generischer Fallback), Logo aus `company_logo` mit Fallback auf das generische Logo; Untertitel zurück auf den 1.6.0-Text.
- **E-Mail-Logo:** Wenn `company_logo` als Base64-Data-URI vorliegt, wird es per `addStringEmbeddedImage` (PHPMailer) direkt als `cid:logo.png` eingebettet (kein Temp-File); sonst Fallback auf die generische Logo-Datei. Die E-Mail-Templates referenzieren weiterhin `cid:logo.png`.
- **Titel:** Layouts und E-Mail-Templates verwenden den Firmennamen mit generischem Fallback statt „MeBeauty".
- **Fehlerseiten:** Eine zentrale Konfigurationskonstante (analog `DEFAULT_COMPANY_COLOR`) liefert den Marken-Namen ohne DB-Zugriff; die drei Fehlerseiten lesen diese Konstante statt Harttext.
- **Wizard-Card-Kopf:** Der Kopf mit Logo + Name wird wiederhergestellt und über ein neues Boolean-Setting gesteuert; Default ist „anzeigen" (1.6.0-Verhalten), „ausblenden" ergibt den aktuellen kompakten Look. Farben bleiben über `--bs-primary` dynamisch.
- **Header-Farbe (Theme-Vorrang):** Eine CSS-Variable mit Fallback-Kette (Theme-explizit → Firmenfarbe → Theme-Primärfarbe) steuert die Backend-Header-Farbe; das `mebeauty`-Theme pinnt Schwarz. Die Firmenfarbe färbt den Header nicht mehr.
- **Migration:** Eine neue Migration fügt das Wizard-Card-Schalter-Setting hinzu (und seedet dessen Default); sie folgt der bestehenden nummerierten Konvention.

## Testing Decisions

- **Primärer Seam: die gerenderte HTML-Ausgabe** der Seiten/E-Mails — `<title>`, Logo-`src`, Header-Inhalt, Wizard-Card-Kopf, `og:image`. Verifiziert per HTTP-Smoke (curl + grep), nach dem Muster des bestehenden `tests/smoke/mebeauty-layout-smoke.sh`.
- **Zweiter Seam: Asset-Dateien** — Logo per Prüfsumme/Dimension, um den generischen Datei-Ersatz zu belegen.
- **Build-/Syntaxprüfungen:** `npx gulp styles` (SCSS), `php -l` je geänderter PHP-Datei, `docker compose exec -T php-fpm composer test` (PHPUnit-Suite).
- **Was gute Tests ausmacht:** nur äußeres Verhalten prüfen (welcher Titel, welches Bild, welche `src`/`href`), nicht die interne Hilfsimplementierung.
- **Kein neuer Unit-Test:** Es wird keine Geschäftslogik geändert; die Verdrahtung sind triviale `setting()`/Konfigurations-Ausdrücke, die der HTTP-Smoke abdeckt.

## Out of Scope

- Website-Komponenten (`site_navbar`, `site_intro`, `site_footer`, `site_whatsapp`) und deren `frontend.scss`-Styles — separater, je-Kunde-Prozess bzw. späterer „Slot"-Mechanismus.
- Umstellung von `company_logo` von Base64 auf eine echte Datei (das Speicherformat bleibt unverändert).
- Ersatz der Social-Card-Grafik (`social-card.png`).
- Inhalt der „About Easy!Appointments"-Seite.
- Backend-Footer-Lizenz-Attribution (Easy!Appointments / Alex Tselegidis / GPL-3.0).
- `twitter:card`-Tags.
- Navbar-Menüpunkte und Behandlungsliste der Website (Seitenstruktur).

## Further Notes

- Es existieren unkommittierte Nutzer-Änderungen an `assets/img/mebeauty/logo-128.png`, `assets/img/mebeauty/logo-192.png` sowie die neuen Dateien `logo-128_old.png`/`logo-192_old.png` — diese müssen unangetastet bleiben.
- Gemäß AGENTS.md: keine Git-Commits ohne ausdrückliche Nutzer-Freigabe.
- Die Website bleibt die Design-Quelle der Wahrheit für den MeBeauty-Look (vgl. ADR-0001); dieser Spec ändert nur die Verdrahtung der ursprünglichen EA-Oberflächen, nicht das MeBeauty-Design selbst.
