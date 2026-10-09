# Deployment

Die Terminbuchungs-App läuft auf **manitu Managed Hosting** (klassisch LAMP, kein
Docker). Der Build passiert in GitHub Actions, das Ergebnis wird per
`rsync`-over-SSH deployt. Es gibt zwei Umgebungen:

| Umgebung | Domain | Trigger |
|----------|--------|---------|
| Test | `booking-test.mebeauty-koeln.de` | push auf `staging` (automatisch) |
| Prod | `booking.mebeauty-koeln.de` | `workflow_dispatch` (manuell) |

## GitHub-Secrets

Alle Zugangs- und Pfad-Daten liegen als GitHub-Secrets (Repo → Settings →
Secrets and variables → Actions). Die Werte selbst stehen nicht im Repo.

| Secret | Woher bekomme ich den Wert? |
|--------|------------------------------|
| `SSH_HOST` | manitu-Panel → SSH-Zugang → Hostname (z. B. `<host>.manitu.net`) |
| `SSH_USER` | manitu-Panel → der angelegte Deploy-SSH-User |
| `SSH_KEY` | Inhalt des privaten Deploy-Keys (der zugehörige Public Key liegt in manitu) |
| `SSH_PORT` | `22` |
| `TEST_PATH` | Dokument-Root der Test-Subdomain (manitu-Panel → Subdomain, oder per SSH `pwd` im Zielordner) |
| `PROD_PATH` | Dokument-Root der Prod-Subdomain (wie oben, für `booking.mebeauty-koeln.de`) |

## Umgebungs-spezifische Dateien

`config.php` ist gitignored und liegt **nur auf dem Server** (eine pro Umgebung).
Der Deploy überschreibt sie nie (rsync schließt `config.php` aus). Ebenso wird der
Inhalt von `storage/` (Logs, Uploads, Sessions) vom Deploy nicht angefasst — nur
das Grundgerüst (`index.html`/`.htaccess`) wird beim ersten Deploy angelegt.

## Erstinstallation (Test, einmalig)

1. Deploy auslösen (push auf `staging`).
2. Per SSH `config.php` im Zielordner anlegen:
   `BASE_URL = 'https://booking-test.mebeauty-koeln.de'` (ohne Trailing-Slash),
   `DB_*` mit den MariaDB-Zugangsdaten, `LANGUAGE = 'german'`.
3. Im Browser `https://booking-test.mebeauty-koeln.de` öffnen → Setup-Wizard:
   Admin-Konto + Firmendaten.
4. Im Backend Branding setzen: **Theme `mebeauty`**, Firmenname `MeBeauty`, Logo,
   Sprache `Deutsch`.

## Updates (nach Erstinstallation)

Bei jedem Deploy neue Migrationen einspielen:

```bash
ssh <benutzer>@<host> \
  "php <test-pfad>/index.php console migrate"
```

oder im Backend `…/index.php/backend/update`.

## Prod

Gleiche Schritte mit `booking.mebeauty-koeln.de` (Pfad `<prod-pfad>`). Vor jedem
Prod-Deploy ein Backup:

```bash
ssh <benutzer>@<host> \
  "php <prod-pfad>/index.php console backup"
```
