# PHP-Versionen angleichen, Repo entrümpeln, Hero-Bild verkleinern

Status: ready-for-agent

## Problem Statement

**PHP-Versionen passen nicht zusammen.** Produktiv laufen bei manitu der Webserver mit PHP 8.3 und die Kommandozeile per SSH mit 8.2. Lokal (Docker) und in der CI wird aber ausschließlich mit PHP 8.5 getestet. Der Deploy-Build installiert die Abhängigkeiten mit 8.2, ignoriert dabei aber ausdrücklich alle Plattform-Anforderungen. Die CI-Tests installieren die Abhängigkeiten außerdem per „update“: Sie ignorieren die Lock-Datei und testen damit andere Paketversionen als die, die deployt werden. Code oder Pakete, die PHP ≥ 8.4 voraussetzen, laufen deshalb überall grün durch und fallen erst in Produktion auf. Die Syntaxprüfung in der CI läuft außerdem Datei für Datei seriell und ist unnötig langsam.

**Generierte Dateien im Repo.** Ein Commit hat die projektlokalen OpenCode-Skills „entfernt“. Das Verzeichnis mit 435 Dateien, darunter rund 48 000 Zeilen generierter Analyse-JSON, und die zugehörige Skills-Lock-Datei sind aber weiterhin versioniert. Das bläht Diffs, Suchergebnisse und Klone auf.

**Hero-Bild zu groß.** Das Fallback-JPG des Hero-Bilds im Booking-Flow ist 1,8 MB groß, die WebP-Variante 175 KB. Browser ohne WebP laden fast 2 MB. Dem Bild fehlen außerdem Breite, Höhe und eine Lade-Priorität. Das verschlechtert Ladezeit (LCP) und Layout-Stabilität (CLS).

## Solution

- Verbindliche PHP-Untergrenze 8.2 für die Abhängigkeitsauflösung. Die CI testet gegen 8.2 und 8.3 und installiert exakt die Lock-Datei. Der Deploy-Build prüft Plattform-Anforderungen wieder. Docker läuft wie der Produktiv-Webserver mit 8.3. Die Syntaxprüfung läuft parallel.
- Die OpenCode-Skills und die Skills-Lock-Datei werden aus der Versionierung genommen und ignoriert. Lokal bleiben sie erhalten.
- Das Fallback-JPG wird auf etwa 200–300 KB komprimiert. Das Hero-Bild bekommt Breite, Höhe und hohe Lade-Priorität.

## User Stories

1. Als Entwickler will ich, dass die CI mit genau den PHP-Versionen testet, die produktiv laufen (8.2 CLI, 8.3 Web), damit Inkompatibilitäten vor dem Deploy auffallen.
2. Als Entwickler will ich, dass die Abhängigkeiten nie für eine höhere PHP-Version als 8.2 aufgelöst werden, damit kein Paket in Produktion fehlschlägt.
3. Als Entwickler will ich, dass die CI exakt die Paketversionen aus der Lock-Datei testet, damit Test und Deploy dieselben Pakete verwenden.
4. Als Entwickler will ich, dass der Deploy-Build abbricht, wenn ein Paket die PHP-Version nicht unterstützt, statt ein kaputtes Artefakt auszuliefern.
5. Als Entwickler will ich lokal mit derselben PHP-Version wie der Produktiv-Webserver arbeiten, damit sich Verhalten lokal und produktiv gleicht.
6. Als Entwickler will ich eine schnellere Syntaxprüfung in der CI, damit Feedback früher kommt.
7. Als Entwickler will ich, dass generierte Skill-Analysen nicht im Repo liegen, damit Diffs, Suche und Klone schlank bleiben.
8. Als Entwickler will ich, dass die lokalen Skill-Dateien beim Entfernen aus der Versionierung erhalten bleiben, damit meine Werkzeuge weiter funktionieren.
9. Als Kunde mit einem Browser ohne WebP will ich, dass die Buchungsseite schnell lädt.
10. Als Kunde will ich, dass die Seite beim Laden des Hero-Bilds nicht springt.
11. Als Salonbetreiberin will ich, dass das Hero-Bild nach der Komprimierung weiterhin hochwertig aussieht.

## Implementation Decisions

- Composer-Konfiguration: Die Plattform-PHP-Version ist auf 8.2 fixiert. Danach wird die Lock-Datei neu aufgelöst. Weichen Paketversionen ab, wird das im Commit dokumentiert.
- CI-Testjob: Matrix über PHP 8.2 und 8.3; Installation per „install“ aus der Lock-Datei; Syntaxprüfung parallel (mehrere Prozesse).
- CI-Smoke-Job: Er nutzt das Docker-Image und läuft damit automatisch mit 8.3.
- Deploy-Build: Das Ignorieren der Plattform-Anforderungen entfällt. Die Build-PHP-Version bleibt 8.2.
- Docker-Image für php-fpm: Basis auf PHP 8.3.
- Die Dokumentation zu Docker und Deployment nennt die Versionsmatrix (Prod Web 8.3, Prod CLI 8.2, Untergrenze 8.2).
- Versionierung: Das OpenCode-Skills-Verzeichnis und die Skills-Lock-Datei werden aus dem Index entfernt (nicht aus dem Dateisystem) und in die Ignore-Liste aufgenommen.
- Hero-Bild: Das JPG wird verlustbehaftet neu komprimiert (Zielgröße ≤ 300 KB, gleiche Abmessungen). Das Bild-Element bekommt die echten Pixelmaße als Breite/Höhe und hohe Lade-Priorität. Die WebP-Quelle bleibt.
- Keine Schemaänderung.

## Testing Decisions

- Der CI-Lauf selbst ist der Test: Beide Matrix-Zweige (8.2, 8.3) laufen grün, der Smoke-Job ebenfalls.
- Lokal: Docker-Image neu bauen, danach PHPUnit-Suite und Layout-Smoke-Test grün.
- Ein Probe-Build mit dem Deploy-Build-Task läuft ohne Plattform-Fehler durch.
- Nach dem Untracken: Die Versionierung listet keine Datei unter dem Skills-Verzeichnis mehr, die Dateien existieren lokal weiter.
- Hero-Bild: Dateigröße ≤ 300 KB. Der Layout-Smoke-Test prüft weiter das Vorhandensein des Bilds. Manuelle Sichtprüfung der Qualität.

## Out of Scope

- Umstellung der Produktiv-CLI auf 8.3 (kann später die Untergrenze anheben).
- Ein Upgrade der Paketversionen über das hinaus, was die Fixierung auf 8.2 erzwingt.
- Weitere Bildoptimierungen jenseits des Hero-Bilds.

## Further Notes

- Bezug: ADR 0004 (Build vollständig in GitHub Actions, Deploy per rsync).
- Das Untracken großer Verzeichnisse erzeugt einen großen Lösch-Diff. Am besten als eigener Commit, getrennt von den CI-Änderungen.
