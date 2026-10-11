# 03: Gemeinsamer Baustein für die Sperrmeldung

**What to build:** Buchungsseite, Stornierungsseite und die Absende-Prüfung einer Umbuchung erzeugen die Meldung „Termin gesperrt“ über denselben Baustein, mit identischem Text, Grenzwert und Seitendaten wie bisher. Der Baustein verarbeitet nur ein fertiges Sperrergebnis der Policy und prüft weder Frist noch Berechtigung.

**Blocked by:** 01 (Booking-Policy als einzige Implementierung von Fristen und Sperren)

**Status:** resolved

- [x] Ein Baustein liefert aus einem Sperrergebnis den Meldungstext (Grenzwert eingesetzt) und die Seitendaten der Meldungsseite
- [x] Buchungs- und Stornierungsseite nutzen die Seitendaten, die Absende-Prüfung nur den Text; die bisherigen nahezu identischen Blöcke sind entfernt
- [x] Der Baustein ruft weder die Policy noch die Hash-Prüfung auf
- [x] Manuelle Probe: gesperrter Manage-Link, gesperrte Stornierung und gesperrte Umbuchung zeigen die gleiche Meldung wie vorher
- [x] `composer test`, `php -l`, Layout-Smoke-Test grün

## Comments

- `Booking_policy::lock_message()` (Text) und `lock_page_vars()` (Titel, Text, Icon) sind reine statische Funktionen; die seitenspezifischen Daten (Seitentitel, Analytics, Links, Firmenfarbe) ergänzen die Controller. Live geprüft: Öffnen des Manage-Links und Stornierung zeigen dieselbe Meldung mit Grenzwert und Icon; Absende-Prüfung per Smoke.
