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

## Versionen & Tags

Ein Tag ist ein Etikett auf einem **Commit (Hash)**, kein Bestandteil eines Branches.
Wird ein Commit auf `staging` getaggt und später per PR nach `main` gemergt, taucht
der Tag automatisch auch in `main` auf — derselbe Commit, dasselbe Etikett.

**Release-Tag setzen** (nach dem Merge in `main`):

```bash
git checkout main && git pull
git tag v1.0.0
git push origin v1.0.0        # Tags werden NICHT automatisch gepusht
git checkout staging
```

Hinweise:

- Tags werden nicht automatisch gepusht (`git push origin --tags` nötig).
- Tags triggern aktuell **keinen** Deploy (Deploy läuft bei push auf `staging` oder manuell).

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

## HTMLPurifier-Cache & rsync `--delete`

HTMLPurifier (via `pure_html()` in `application/helpers/html_helper.php`) legt
standardmäßig seinen Serializer-Cache **innerhalb von `vendor/`** an
(`vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer/HTML`
und `…/CSS`). Das ist ein Problem für den Deploy: `vendor/` wird per
`rsync --delete` als Build-Artefakt verwaltet, und die Cache-Dateien entstehen
erst zur Laufzeit durch den PHP-Prozess. Auf manitu laufen Webserver/PHP und der
Deploy-SSH-Nutzer unter **verschiedenen** Accounts (z. B. `site100033324` vs.
`ssh300011177`). Die vom PHP-Nutzer angelegten Cache-Verzeichnisse (Modus `0755`,
nur Owner schreibbar) kann der Deploy-Nutzer daher nicht löschen — `--delete`
schlägt mit `cannot delete non-empty directory` und Exit-Code 23 fehl.

Deshalb setzt `pure_html()` den Cache explizit auf
`storage/cache/htmlpurifier/`. `storage/` ist vom ersten rsync ausgeschlossen
(`--exclude '/storage/'`) und der `storage`-rsync hat kein `--delete`, daher
kollidieren die Laufzeit-Cache-Dateien nicht mehr mit dem Deploy. Ist
`storage/cache/htmlpurifier/` nicht anlegbar oder nicht beschreibbar, wird der
Cache ersatzlos deaktiviert (`Cache.DefinitionImpl = null`), statt auf den alten
`vendor/`-Pfad zurückzufallen.

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
