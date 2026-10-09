# Deployment

Die Terminbuchungs-App läuft auf **manitu Managed Hosting** (klassisch LAMP, kein
Docker). Der Build passiert in GitHub Actions, das Ergebnis wird per
`rsync`-over-SSH deployt. Es gibt zwei Umgebungen:

| Umgebung | Domain | Trigger |
|----------|--------|---------|
| Test | `booking-test.mebeauty-koeln.de` | push auf `staging` (automatisch) |
| Prod | `booking.mebeauty-koeln.de` | `workflow_dispatch` (manuell) |

## Workflow

Zwei Branches, zwei Umgebungen:

- **`staging`** = Test. Jeder Push deployt automatisch auf `booking-test.mebeauty-koeln.de`.
- **`main`** = Prod. Wird nur per Pull Request aus `staging` aktualisiert, wenn der Test gut ist.

### Entwickeln & Testen

1. Auf `staging` arbeiten (für größere Features: Feature-Branch aus `staging`, später per PR in `staging` zurückführen).
2. Committen und pushen:

   ```bash
   git checkout staging
   git push origin staging
   ```

   Der Test-Deploy läuft automatisch.
3. Auf `https://booking-test.mebeauty-koeln.de` prüfen.

### Checks

Vor dem Push lokal prüfen:

```bash
docker compose exec -T php-fpm composer test    # PHPUnit
docker compose exec -T php-fpm php -l <datei>   # PHP-Syntax (je geänderte Datei)
node --check <datei>                             # JS-Syntax (je geänderte Datei)
```

CI (`ci.yml`) läuft zusätzlich automatisch bei jedem Push. Nach einem Deploy die
Buchungsseite per Smoke gegen die Live-URL prüfen:

```bash
BASE_URL=https://booking-test.mebeauty-koeln.de bash tests/smoke/mebeauty-layout-smoke.sh
```

### Release zu Prod (Pull Request)

Wenn der Test gut ist und die Änderungen nach Prod sollen:

1. GitHub öffnen → Banner **„staging had recent pushes" → „Compare & pull request"** klicken
   (oder Tab **Pull requests → New pull request**, `base: main` ← `compare: staging`).
2. Titel/Beschreibung eintragen → **Create pull request**.
3. **Merge pull request** klicken → die Commits aus `staging` landen in `main`.
4. Vor dem Prod-Deploy ein Backup:

   ```bash
   ssh <benutzer>@<host> "php <prod-pfad>/index.php console backup"
   ```

5. Prod-Deploy auslösen: GitHub → **Actions → Deploy → Run workflow → environment: `prod`**.

> **Was ist ein Pull Request?** Nur eine Übersicht „diese Commits aus `staging`
> fehlen in `main`" mit einem Merge-Button. Bis zum Merge bleibt `main` unverändert.
> Du machst das komplett im Browser — nichts auf der Kommandozeile nötig.

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
