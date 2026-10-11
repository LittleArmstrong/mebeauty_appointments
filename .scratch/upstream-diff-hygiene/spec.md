# Upstream-Diff klein halten: Formatter-Strategie, Whitespace-Bereinigung, Kleinkram

Status: ready-for-agent

## Problem Statement

Das Projekt hat eine Prettier-Konfiguration (inkl. PHP-Plugin) und ein Skript `check:format`, aber keine verbindliche Regel, **welche** Dateien damit formatiert werden. Die CI prüft es nicht. Eine Messung zeigt:

- 39 von 57 vom Fork geänderten oder neu angelegten Dateien entsprechen nicht der Prettier-Konfiguration.
- Auch 28 von 45 geprüften **Upstream-Originalen** entsprechen ihr nicht. Upstream ist also selbst nicht Prettier-formatiert.
- Wo ganze Upstream-Dateien formatiert wurden, besteht der Fork-Diff überwiegend aus Whitespace. Beispiele: Nachrichten-Layout 107 geänderte Zeilen, davon 7 inhaltlich (Prettier-Lauf); Bestätigungsschritt 20 → 6; Bestätigungsseite 22 → 8.

Jede dieser Abweichungen erzeugt beim Upstream-Sync (ADR 0005) unnötige Konflikte und verdeckt die echten Fork-Änderungen. Gleichzeitig sind 8 vom Fork neu angelegte Dateien nicht konform, obwohl dort nichts dagegen spricht.

Dazu kommen kleine Mängel:
- **Nachrichten-Layout:** „Powered by“ ist auskommentiert. Dadurch beginnt die Fußzeile mit einem überzähligen Trennstrich („| Rechtliche Hinweise“). Das betrifft die Meldungsseiten der Buchungsseite (Buchung deaktiviert, Termin nicht gefunden, Termin gesperrt), sobald eine URL für Rechtliche Hinweise oder Impressum gesetzt ist.
- Die Abfrage der Service-Buchungen für die Slot-Kapazität ist schwer lesbar (Filter vor der Abfragekette).
- Der HTML-Bereinigungs-Helper prüft sein Cache-Verzeichnis bei jedem Aufruf.

## Solution

Eine dokumentierte **Formatter-Strategie**:
1. **Fork-eigene Dateien** (seit der Upstream-Basis neu angelegt): Prettier ist verbindlich. `check:format` prüft automatisch genau diese Dateien, und die CI führt den Check aus.
2. **Upstream-Dateien:** Sie werden nie als Ganzes formatiert. Eigene Änderungen folgen dem Stil der Umgebung, reine Whitespace-Änderungen gegenüber der Basis werden zurückgesetzt.

Danach werden die betroffenen Upstream-Dateien bereinigt, die Trennstriche korrigiert und die zwei Kleinigkeiten erledigt.

## User Stories

1. Als Maintainer will ich, dass Upstream-Dateien nur dort von der Basis abweichen, wo der Fork fachlich etwas ändert, damit Syncs weniger Konflikte erzeugen.
2. Als Reviewer will ich im Diff gegen die Upstream-Basis nur inhaltliche Änderungen sehen.
3. Als Entwickler will ich eindeutig wissen, welche Dateien ich mit Prettier formatieren darf und welche nicht.
4. Als Entwickler will ich, dass die CI nicht konforme Fork-eigene Dateien meldet, ohne dass ich eine Dateiliste pflegen muss.
5. Als Agent will ich die Regel in den Entwicklungskonventionen finden, damit ich Upstream-Dateien nicht versehentlich komplett formatiere.
6. Als Kunde will ich in der Fußzeile der Meldungsseiten keinen verwaisten Trennstrich sehen.
7. Als Salonbetreiberin will ich, dass bei einem Link kein Trennstrich und bei zwei Links genau einer dazwischen steht.
8. Als Entwickler will ich die Service-Buchungsabfrage als zusammenhängende Kette lesen.
9. Als Entwickler will ich, dass der HTML-Bereinigungs-Helper sein Verzeichnis nur einmal pro Request prüft.
10. Als Kunde will ich, dass alle betroffenen Seiten nach dem Aufräumen genauso aussehen und funktionieren.

## Implementation Decisions

- **Bestehende Konfiguration bleibt:**
  - `.editorconfig`: LF, Endzeilenumbruch, 4 Leerzeichen
  - Prettier-Konfiguration: 120 Zeichen, PHP-Plugin
  - Pre-commit-Hook: Syntaxprüfung
- **Fork-eigene Dateien** = Dateien, die seit dem Upstream-Basis-Commit (laut Upstream-Dokumentation im Repo) neu angelegt wurden. Das Format-Skript ermittelt sie per Git selbst, eine gepflegte Liste gibt es nicht. Es prüft nur die Pfade, die `check:format` schon heute abdeckt.
- Der Format-Check wird als eigener Schritt in die CI aufgenommen.
- Die 8 derzeit nicht konformen Fork-eigenen Dateien werden einmalig formatiert:
  - die konsolidierte Migration
  - die Site-Navbar
  - das MeBeauty-Theme-SCSS
  - vier Unit-Tests
  - das E-Mail-Smoke-Hilfsskript
- **Upstream-Dateien mit Whitespace-Anteil:** Bestätigungsschritt, Bestätigungsseite, Booking-Layout, Booking-JavaScript, E-Mail-Nachrichten-Library, Business-Einstellungsseite, Verfügbarkeits-Library. Die reinen Whitespace-Hunks werden zurückgesetzt, inhaltliche Änderungen bleiben.
- **Nachrichten-Layout:** Upstream-Formatierung wiederherstellen; Trennstrich nur zwischen zwei vorhandenen Links, nach dem Muster, das der Booking-Footer bereits verwendet.
- **Service-Buchungsabfrage:** Der Ausschluss eines Termins wird bedingt in die Kette gezogen, das Ergebnis bleibt identisch.
- **HTML-Bereinigungs-Helper:** Das Ergebnis der Verzeichnisprüfung wird pro Request statisch gecacht.
- Die Regel wird in den Entwicklungskonventionen dokumentiert. Sie ist dort leichter zu finden als in einer ADR und dort auch einfach änderbar.

## Testing Decisions

- `check:format` grün für alle Fork-eigenen Dateien. Gegenprobe: Eine absichtlich falsch formatierte neue Datei lässt den Check fehlschlagen; eine Upstream-Datei wird nicht geprüft.
- Je bereinigter Upstream-Datei: Der rohe Diff gegen die Basis hat so viele geänderte Zeilen wie der Diff ohne Whitespace.
- Nachrichten-Layout: Smoke-Prüfung (URL für Rechtliche Hinweise gesetzt, unbekannten Manage-Hash aufrufen) → Fußzeile beginnt nicht mit „|“. Der Fehler ist aus dem Code abgeleitet, aber noch nicht live reproduziert. Lässt er sich bei laufendem Stack nicht reproduzieren, wird das Ticket auf `needs-info` gesetzt.
- Helper: bestehende Helper-Tests, ggf. ergänzt um wiederholte Aufrufe.
- Layout-Smoke-Test grün; Sichtprüfung von Bestätigungsseite (mit Captcha) und Buchungsseite.

## Out of Scope

- Inhaltliche Änderungen an Upstream-Views.
- Formatierung von Upstream-Dateien mit Prettier.
- Ein anderer Formatter oder neue Regeln.

## Further Notes

- Bezug: ADR 0005.
- `input-hardening/03` escaped den Firmennamen im Nachrichten-Layout und folgt deshalb nach Ticket 02.
