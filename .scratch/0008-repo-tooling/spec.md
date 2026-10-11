# Projekt-Skill für alle Agenten, PHP-Versionen absichern, Hero-Bild verkleinern

Status: ready-for-agent
Created: 2026-10-11
Closed: –
Priority: P2
Depends-on: –

## Problem Statement

**Projekt-Skill nur für OpenCode.** Der projektspezifische Skill `easyappointments-ci3` liegt im OpenCode-Ordner. Bei der Bereinigung der allgemeinen Skills (die jetzt global installiert sind) wurde er bewusst im Repo behalten. Claude Code findet ihn dort aber nicht. Die Repo-Datei `skills-lock.json` listet noch die früher projektlokal installierten allgemeinen Skills. Diese verwaltet inzwischen die globale Lock-Datei, die Repo-Datei hat also keine Funktion mehr.

Der Skill selbst ist ein generierter Schnappschuss vom August:
- 3,4 MB automatisch erzeugte Code-Analyse mit absoluten lokalen Pfaden, die bei jeder Änderung veraltet
- ein „Konflikt-Report“ mit 207 Scraping-Artefakten
- nachweislich falsche Aussagen (z. B. ein „Service Layer mit 18 Klassen“, den es nicht gibt; die Logikschicht sind die Libraries)
- keine Verweise auf die Fork-Konventionen, ADRs und das Glossar

**PHP-Versionen passen nicht zusammen.** Produktiv laufen die CLI per SSH mit PHP 8.2 und der Webserver mit 8.3 (bei manitu 8.2–8.5 wählbar). Lokal und in der CI wird nur mit 8.5 getestet, und die CI installiert per `update` statt aus der Lock-Datei. Der Deploy-Build installiert mit 8.2, schaltet die Plattformprüfung aber aus (Upstream-Einstellung). Code oder Pakete, die mehr als 8.2 verlangen, fallen deshalb erst in Produktion auf. Eine Einschränkung kommt dazu: Die Testbibliothek PHPUnit 12 (auch so im Upstream) verlangt mindestens PHP 8.3.

**Hero-Bild zu groß.** Das Fallback-JPG des Hero-Bilds ist 1,8 MB groß (WebP: 175 KB) und hat weder Abmessungen noch eine Lade-Priorität.

## Solution

- Der Projekt-Skill zieht nach `.claude/skills/`. Claude Code und OpenCode lesen diesen Ort beide nativ, sodass weder Symlink noch Sync-Skript oder Doppelpflege nötig sind. `skills-lock.json` entfällt. In einem zweiten Schritt wird der Skill gekürzt und korrigiert.
- **PHP:** Die Mindestversion bleibt 8.2 (wegen der Produktiv-CLI). Getestet wird auf 8.3, das ist zugleich die Version von Produktiv-Webserver und Docker. Ein zusätzlicher CI-Job stellt sicher, dass Laufzeitcode und Laufzeitpakete mit 8.2 lauffähig bleiben. PHPUnit 12 bleibt, es gibt kein Downgrade und keine globale Plattform-Fixierung. Der Deploy prüft die Plattform wieder.
- Das Hero-JPG wird komprimiert und das Bild-Element ergänzt.

## User Stories

1. Als Entwickler will ich, dass Claude Code und OpenCode denselben Projekt-Skill automatisch laden, ohne dass ich ihn doppelt pflege.
2. Als Entwickler will ich, dass der Skill versioniert bleibt und jeder Klon ihn sofort hat.
3. Als Agent will ich im Skill korrekte Aussagen über die Projektstruktur und Verweise auf die Fork-Konventionen finden.
4. Als Entwickler will ich keine veralteten, generierten Analysen mit lokalen Pfaden im Repo.
5. Als Entwickler will ich, dass keine funktionslosen Lock-Dateien im Repo liegen.
6. Als Entwickler will ich, dass die Tests mit der Version laufen, die der Produktiv-Webserver nutzt.
7. Als Entwickler will ich, dass die CI meldet, wenn Code oder ein Laufzeitpaket mehr als PHP 8.2 verlangt, weil die Produktiv-CLI 8.2 nutzt.
8. Als Entwickler will ich, dass die CI exakt die Paketversionen aus der Lock-Datei testet.
9. Als Entwickler will ich, dass der Deploy-Build abbricht, wenn ein Laufzeitpaket die PHP-Version nicht unterstützt.
10. Als Entwickler will ich PHPUnit 12 behalten, damit composer.json nicht vom Upstream abweicht.
11. Als Entwickler will ich lokal mit derselben Version wie der Produktiv-Webserver arbeiten.
12. Als Entwickler will ich eine schnellere Syntaxprüfung in der CI.
13. Als Kunde mit einem Browser ohne WebP will ich, dass die Buchungsseite schnell lädt und beim Laden nicht springt.

## Implementation Decisions

- **Skill-Ablage:** Der Skill liegt unter `.claude/skills/easyappointments-ci3/`. Verifiziert: OpenCode durchsucht `.claude/skills/` und `.agents/skills/` global und projektweit (vom Arbeitsverzeichnis bis zur Worktree-Wurzel), abschaltbar per Umgebungsvariable. Claude Code lädt Projekt-Skills aus `.claude/skills/`. Verworfen wurden `.agents/skills/` mit Symlink (zwei Pfade, Symlink-Probleme unter Windows), eine Kopie (Drift) und ein Installer (vergessener Schritt). Der Verschiebe-Commit enthält keine inhaltliche Änderung.
- `skills-lock.json` wird entfernt (die globale Lock-Datei des Skill-Installers hat die Funktion übernommen). Lokale, nicht versionierte OpenCode-Dateien bleiben unberührt.
- **Skill kürzen:** Es bleiben `SKILL.md` und die offline gespiegelte CI3-Dokumentation. Entfernt werden generierte Code-Analyse, Konflikt-Report, gemischte API-Referenz und die Kopie der README. In `SKILL.md`:
  - Quellen, Navigation und bekannte Abweichungen bereinigen
  - der Code ist die Quelle der Wahrheit
  - Hinweis, dass die Analyse bei Bedarf mit Skill Seekers neu erzeugt werden kann
  - falsche Aussagen korrigieren
  - Abschnitt mit Verweisen auf AGENTS.md, die Agenten-Doku, das Glossar und die ADRs
- **PHP:**
  - `composer.json` bleibt unverändert (`php >=8.2`, PHPUnit ^12, keine Plattform-Fixierung). Eine Fixierung auf 8.2 würde auch für die Entwicklungspakete gelten und PHPUnit 12 unmöglich machen.
  - CI-Testjob auf 8.3 mit Installation aus der Lock-Datei, parallele Syntaxprüfung, PHPUnit, Validatoren.
  - Neuer CI-Job auf 8.2: Plattformprüfung der Lock-Datei ohne Entwicklungspakete und Syntaxprüfung des Laufzeitcodes.
  - Deploy-Build: Installation ohne Entwicklungspakete und **mit** Plattformprüfung auf 8.2.
  - Docker auf 8.3, Produktiv-Webserver bleibt auf 8.3.
  - Die Doku enthält eine Übersicht der Versionen.
  - Restrisiko, bewusst akzeptiert: Laufzeitunterschiede zwischen 8.2 und 8.3, die über die Syntax hinausgehen, decken die Tests für die CLI nicht ab.
- **Hero-Bild:** verlustbehaftet komprimiert (≤ 300 KB, gleiche Abmessungen); Bild-Element mit echten Pixelmaßen und hoher Lade-Priorität; WebP-Quelle unverändert.
- Keine Schemaänderung.

## Testing Decisions

- Skill: Nach dem Verschieben und nach dem Kürzen listen Claude Code und OpenCode den Skill im Projekt.
- CI: Testjob (8.3), Laufzeit-Job (8.2) und Smoke-Job grün. Gegenprobe für den 8.2-Job: Eine Datei mit PHP-8.3-Syntax (z. B. typisierte Klassenkonstante) lässt ihn fehlschlagen.
- Deploy: Ein Build mit PHP 8.2 ohne `--ignore-platform-reqs` läuft durch, obwohl die Lock-Datei Entwicklungspakete mit PHP ≥ 8.3 enthält.
- Lokal: Nach dem Neubau des Docker-Images PHPUnit und Layout-Smoke-Test grün.
- Hero-Bild: Dateigröße ≤ 300 KB, Sichtprüfung, Layout-Smoke-Test grün.

## Out of Scope

- Umstellung der Produktiv-CLI auf eine höhere Version (würde später die Mindestversion anheben).
- Paket-Upgrades über den bestehenden Lock-Stand hinaus.
- Weitere Bildoptimierungen.
- Ablage der global installierten allgemeinen Skills.

## Further Notes

- Bezug: ADR 0004 (Build in GitHub Actions, Deploy per rsync), ADR 0005 (Upstream-Sync), ADR 0007 (PHP-Versionen).
- Agenten-Dateien werden nicht deployt (der Build kopiert nur ausgewählte Verzeichnisse).
- Die Extensions bei manitu (Web per `phpinfo()`, CLI per `php -m`) prüft der Betreiber manuell gegen die Composer-Anforderungen.
