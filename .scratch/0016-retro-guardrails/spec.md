# Retro-Guardrails: Testskript, Pre-commit, View-Escaping, Regeln

Status: resolved
Created: 2026-10-11
Closed: 2026-10-11
Priority: P2
Depends-on: –

## Problem Statement

Die Retro nach 0008–0015 hat wiederkehrende Reibungen gefunden:

- **Validierung hängt am Zustand der Umgebung:** PHPUnit braucht laufendes MySQL und `config.php`, die Smoke-Tests eine erreichbare Seite. Ob der Stack läuft, prüft niemand vorab. Die Validierung besteht aus einer Befehlsliste in `AGENTS.md`, `docs/agents/validation-and-completion.md` und der CI (`.github/workflows/ci.yml`, Job `smoke`), die lokal von Hand nachgespielt wird.
- **Pre-commit fängt zu wenig:** `scripts/git-hooks/pre-commit` prüft nur Syntax und Backup-Dateien. Formatfehler in Fork-Dateien erreichen den Commit (Extra-Commit `fe29dc5c`), ein veralteter `.scratch/INDEX.md` ebenfalls; beides meldet erst die CI. `scripts/check-format.sh:31-37` prüft alle Fork-Dateien im Arbeitsbaum, nicht nur die gestagten.
- **Ungeescapte Ausgaben in Fork-Views:** `application/views/themes/mebeauty/site_navbar.php:11`, `site_footer.php:15,23,31-38` und `application/views/components/booking_company_header.php:13` geben Variablen ohne `e()` aus. Kein Werkzeug meldet neue Fälle; der Fall `page_title` (rohe `html_vars()`-Werte) fiel nur im Review auf.
- **Fixture-Name irreführend:** `tests/smoke/manage-link-fixtures.php` wird auch von `tests/smoke/language-selector-smoke.sh:18` genutzt.
- **Ungeschriebene Regeln:** Tests, die gegen das alte Verhalten nicht rot werden, beweisen nichts (0012/01 hat das per Gegenprobe belegt, Pflicht ist es nicht). Verhaltensaussagen in Specs werden nicht per Datei belegt. `docs/agents/session-traceability.md` schreibt `opencode export` vor, das in Claude Code nicht existiert; `docs/agent-sessions/` enthält eine einzige, veraltete Datei.

## Solution

- `scripts/test.sh` fährt den Stack hoch, wartet auf MySQL und die Seite und führt die gesamte Validierung aus, mit Zusammenfassung grün/rot.
- Der Pre-commit-Hook prüft zusätzlich das Format der gestagten Fork-Dateien und den Scratch-Index und blockiert bei Fehlern.
- `composer check:views` meldet ungeescapte Ausgaben in Fork-Views; die vorhandenen Stellen bekommen `e()`.
- Die Fixture heißt `tests/smoke/fixtures.php`.
- Die Regeln stehen in `CODING_STANDARDS.md` und `docs/agents/issue-tracker.md`; Sitzungsexporte entfallen.

## User Stories

1. Als Agent will ich die gesamte Validierung mit einem Befehl starten, egal ob der Stack läuft.
2. Als Agent will ich eine klare Meldung, wenn MySQL, `config.php` oder die Seite fehlen, statt kryptischer Testfehler.
3. Als Entwickler will ich, dass ein Commit mit Formatfehler in einer gestagten Fork-Datei abgelehnt wird.
4. Als Entwickler will ich, dass ein Commit nur an dem scheitert, was er selbst enthält.
5. Als Entwickler will ich, dass ein Commit mit veraltetem Scratch-Index abgelehnt wird.
6. Als Reviewer will ich, dass ungeescapte Ausgaben in Fork-Views automatisch gemeldet werden, lokal und in der CI.
7. Als Agent will ich Fixture-Dateien unter einem Namen finden, der ihren Zweck beschreibt.
8. Als Reviewer will ich im Ticket einen Beleg sehen, dass ein neuer Test gegen das alte Verhalten rot war.
9. Als Leser einer Spec will ich jede Aussage über heutiges Verhalten per Dateiverweis nachprüfen können.
10. Als Projektverantwortlicher will ich, dass alles Wichtige in Spec, Ticket, ADR oder `docs/` steht und Sitzungen gelöscht werden können.

## Implementation Decisions

- **Testskript (`scripts/test.sh`, Host):** `docker compose up -d` (alle Dienste; Einschränkung erst, wenn es zu lange dauert), Warten auf MySQL (Schleife wie im CI-Job `smoke`, maximal 60 s), Prüfung `config.php` und `vendor/autoload.php`, Warten auf `http://localhost/`. Danach alle Prüfungen, auch nach einem Fehlschlag weiter: `composer test`, `check:format`, `check:migrations`, `check:assets`, `check:scratch`, `check:views`, alle Smoke-Skripte. Am Ende Tabelle grün/rot, Exit-Code 1 bei mindestens einem Rot. Fehlende Voraussetzungen brechen sofort ab. `docker compose down` bleibt manuell. `docker-compose.yml` bleibt unverändert (kein `restart` für weitere Dienste).
- **Pre-commit:** `check-format.sh --staged` prüft die Schnittmenge aus Fork-Dateien und gestagten Dateien. Der Hook ruft es auf, wenn Fork-Dateien gestagt sind, und `php scripts/scratch-index.php check` im Container, wenn Dateien unter `.scratch/` gestagt sind. Beides blockiert; fehlt das Werkzeug (Container, `npx`), warnt der Hook und überspringt die Prüfung wie heute bei PHP.
- **`check:views`:** PHP-Validator im Stil von `scripts/validate-asset-references.php` mit reiner, testbarer Funktion. Geprüft werden nur Fork-Views unter `application/views/` (seit dem Upstream-Basis-Commit aus `UPSTREAM.md` hinzugefügt, wie in `check-format.sh`); Upstream-Views bleiben unberührt (ADR 0005). Gemeldet wird jede Ausgabe `<?= …` deren Ausdruck mit `$`, `setting(`, `vars(` oder `html_vars(` beginnt. Keine Ausnahmeliste. Composer-Befehl, CI-Schritt im Job `build-test`.
- **Fixture:** Umbenennen nach `tests/smoke/fixtures.php` samt Aufrufen und Kopfkommentar; abgeschlossene Specs bleiben als Historie unverändert.
- **Regeln:** „Tests must go red“ in `CODING_STANDARDS.md` (gilt für Tickets, die Verhalten ändern; Beleg-Zeile `Rot vor Fix: …` im Ticket-Kommentar; Testdaten stellen den behaupteten Grenzfall her). Reviewer-Regel zu rohen `html_vars()`-Werten. Belegpflicht `pfad:zeile` für Verhaltensaussagen im Problem-Abschnitt einer Spec in `docs/agents/issue-tracker.md`, ohne maschinelle Prüfung. `docs/agents/session-traceability.md` und `docs/agent-sessions/` entfallen; `AGENTS.md` nennt stattdessen die Regel, dass Sitzungen und Handoffs Wegwerfware sind. Nach einem Push: CI-Ergebnis mit `gh run list --branch <branch>` prüfen (`docs/agents/validation-and-completion.md`).

## Testing Decisions

- `check:views`: Unit-Tests der reinen Funktion (testgetrieben): `$`-Ausgabe, `setting(`, `vars(`, `html_vars(` werden gemeldet; `e(…)`, `lang(…)`, `asset_url(…)` und Ausgaben mit führenden Leerzeichen bzw. ohne Leerzeichen nach `<?=` korrekt behandelt; Zeilennummer im Fehler. Vorbild `tests/Unit/AssetReferenceValidatorTest.php`. Integrationsprobe: vor dem Fix der drei Views rot, danach grün.
- `check-format.sh --staged`: Probe mit einer absichtlich falsch formatierten, gestagten Fork-Datei (rot) und einer ungestagten (grün).
- Pre-commit: Probe-Commits für Format und veralteten INDEX werden abgelehnt.
- `test.sh`: Lauf gegen laufenden und gestoppten Stack, beide Male vollständige Zusammenfassung.
- Fixture: Smoke `manage-link` und `language-selector` grün.

## Out of Scope

- `shellcheck` (neues Werkzeug auf Host und CI).
- `restart`-Richtlinien in `docker-compose.yml`.
- Escaping in Upstream-Views.
- Skill-Quellen außerhalb des Repos (`~/.agents/skills` ist bereits geleert).
- Kundenzuordnung per E-Mail (jetzt 0017 und 0018) und `composer audit` (jetzt 0020; 0019 ist der Booking-Footer).

## Further Notes

- Herkunft: Retro nach 0014, entschieden per Grilling am 2026-10-11.
- Branch: `agent/retro-guardrails` (von `agent/review-followups`), damit die Review-Branch unverändert in die CI gehen kann.

## Review (Standards + Spec, gegen `agent/review-followups`)

- Behoben: `test.sh` wartet auf `${BASE_URL:-http://localhost}` wie die Smoke-Tests (statt `NGINX_PORT`); `check-format.sh --staged` warnt bei teilweise gestagten Dateien (geprüft wird der Arbeitsbaum); Hook-Helfer `staged_matches` statt doppeltem `printf | grep` mit Wortaufspaltung; Grenze des Validators (nur Ausdrucksanfang) im Docblock; `CODING_STANDARDS.md` nennt `Booking::index()` statt Zeilennummer; Voraussetzungen in `validation-and-completion.md` positiv formuliert, doppelter `down`-Hinweis entfernt; Test für mehrzeilige Ausgabe (sichert Bestehendes, sofort grün).
- Bewusst belassen: Fork-Dateiliste existiert in `check-format.sh` und `validate-view-escaping.php` doppelt (Bash/PHP; Zusammenlegen wäre ein eigener Umbau); MySQL-Zugangsdaten im Testskript wie im CI-Job `smoke`; „Tests go red“ bleibt in `CODING_STANDARDS.md` (Review-Regel, so entschieden).
- Abschluss: `bash scripts/test.sh` 10/10 grün, PHPUnit 113 Tests.
