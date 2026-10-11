# Upstream-Diff bereinigen

Status: ready-for-agent

## Problem Statement

Der Fork weicht in mehreren Upstream-Dateien nur durch **Formatierung** vom Original ab: geänderte Einrückung, zusätzliche Leerzeichen, fehlender Zeilenumbruch am Dateiende, ein komplett neu eingerücktes Layout. Inhaltlich steckt dahinter oft nur eine kleine Änderung. Jede dieser Abweichungen erzeugt beim nächsten Upstream-Sync (ADR 0005) unnötige Konflikte und macht die echten Fork-Änderungen in Diffs schwer erkennbar. Ein Beispiel aus der Bestätigungsseite: Ein Formatter hat den Aufruf der Captcha-URL über drei Zeilen und weit nach rechts verschoben.

Dazu kommen kleine sichtbare oder strukturelle Mängel:
- Im Nachrichten-Layout (z. B. Buchungsbestätigung, Sperrmeldung) ist „Powered by“ auskommentiert. Dadurch beginnt die Fußzeile mit einem überzähligen Trennstrich „|“ vor „Rechtliche Hinweise“.
- In der Abfrage der Service-Buchungen für die Slot-Kapazität steht der Ausschluss eines Termins vor der Abfragekette statt darin. Das ist korrekt, aber schwer lesbar.
- Der HTML-Bereinigungs-Helper prüft bei jedem Aufruf, ob sein Cache-Verzeichnis existiert, statt das einmal pro Request zu tun.

## Solution

In allen Upstream-Dateien, die der Fork nur teilweise ändert, wird die Formatierung des Upstream-Originals wiederhergestellt. Erhalten bleiben ausschließlich die inhaltlichen Fork-Änderungen. Das Nachrichten-Layout zeigt Trennstriche nur noch **zwischen** vorhandenen Links. Die beiden Code-Kleinigkeiten werden lesbarer bzw. sparsamer umgesetzt, ohne Verhaltensänderung.

## User Stories

1. Als Maintainer will ich, dass Upstream-Dateien nur dort vom Original abweichen, wo der Fork fachlich etwas ändert, damit Upstream-Syncs weniger Konflikte erzeugen.
2. Als Reviewer will ich im Diff gegen die Upstream-Basis nur inhaltliche Änderungen sehen, damit ich Fork-Änderungen schnell nachvollziehe.
3. Als Kunde will ich in der Fußzeile der Nachrichtenseiten keinen verwaisten Trennstrich sehen, damit die Seite sauber wirkt.
4. Als Salonbetreiberin will ich, dass bei nur einem Link (z. B. nur Impressum) gar kein Trennstrich erscheint.
5. Als Salonbetreiberin will ich, dass bei Rechtlichen Hinweisen und Impressum genau ein Trennstrich zwischen beiden steht.
6. Als Entwickler will ich die Abfrage der Service-Buchungen als eine zusammenhängende Kette lesen können, damit Filter nicht übersehen werden.
7. Als Entwickler will ich, dass der HTML-Bereinigungs-Helper sein Cache-Verzeichnis nur einmal pro Request prüft.
8. Als Kunde will ich, dass Bestätigungsseite und Captcha nach dem Aufräumen genauso aussehen und funktionieren wie vorher.

## Implementation Decisions

- Maßstab ist die Upstream-Basis laut `UPSTREAM.md`. Für jede vom Fork geänderte Upstream-View wird der Diff gegen die Basis geprüft. Reine Formatierungsänderungen werden zurückgesetzt, inhaltliche bleiben.
- Bekannte Kandidaten: Bestätigungsschritt des Booking-Flows (Captcha-Einrückung, doppeltes Leerzeichen in einer Klassenliste, Leerzeile nach dem PHP-Tag, Leerzeichen vor `/>`, fehlender Zeilenumbruch am Dateiende) und Nachrichten-Layout (komplett neu eingerückt). Weitere Dateien findet derselbe Diff-Check.
- Die eigenen Fork-Dateien (Site-Chrome, neue Libraries) sind nicht betroffen.
- Für Upstream-Views wird die automatische Formatierung im Editor bzw. per Formatter nicht angewendet. Ein Hinweis dazu kommt in die Entwicklungskonventionen.
- Nachrichten-Layout: Die Links der Fußzeile werden gesammelt und mit Trennstrich verbunden, statt jedem Link einen vorangestellten Trennstrich zu geben.
- Service-Buchungsabfrage: Der Ausschluss des Termins wird bedingt in die Abfragekette gezogen. Das Ergebnis bleibt identisch.
- HTML-Bereinigungs-Helper: Das Ergebnis der Verzeichnisprüfung wird pro Request statisch gecacht.
- Keine Schemaänderung.

## Testing Decisions

- Unit-Test für den HTML-Bereinigungs-Helper: Das Verhalten bleibt gleich (bestehende Helper-Tests als Vorbild und Regression).
- Diff-Prüfung: Der Diff jeder bereinigten Upstream-Datei gegen die Basis enthält keine reinen Whitespace-Hunks mehr (z. B. per `git diff -w` gegen die Basis gegenprüfen: gleiche inhaltliche Änderungen, kleinerer Roh-Diff).
- Layout-Smoke-Test bleibt grün. Manuelle Probe der Bestätigungsseite mit Captcha und der Nachrichtenseite mit 0, 1 und 2 Fußzeilen-Links.

## Out of Scope

- Inhaltliche Änderungen an Upstream-Views.
- Formatierung eigener Fork-Dateien.
- Einführung eines repo-weiten Formatters.

## Further Notes

- Bezug: ADR 0005 (Upstream-Sync per Voll-Merge). Diese Spec ist unabhängig von allen anderen und kann jederzeit umgesetzt werden. Bei Konflikten mit `input-hardening` (Escaping im Nachrichten-Layout) gilt: zuerst die Formatierung bereinigen, dann escapen.
