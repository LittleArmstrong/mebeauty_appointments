# 03: Gemeinsamer Baustein für die Sperrmeldung

**What to build:** Buchungsseite, Stornierungsseite und die Absende-Prüfung einer Umbuchung erzeugen die Meldung „Termin gesperrt“ über denselben Baustein, mit identischem Text, Grenzwert und Seitendaten wie bisher. Der Baustein verarbeitet nur ein fertiges Sperrergebnis der Policy und prüft weder Frist noch Berechtigung.

**Blocked by:** 01 (Booking-Policy als einzige Implementierung von Fristen und Sperren)

**Status:** ready-for-agent

- [ ] Ein Baustein liefert aus einem Sperrergebnis den Meldungstext (Grenzwert eingesetzt) und die Seitendaten der Meldungsseite
- [ ] Buchungs- und Stornierungsseite nutzen die Seitendaten, die Absende-Prüfung nur den Text; die bisherigen nahezu identischen Blöcke sind entfernt
- [ ] Der Baustein ruft weder die Policy noch die Hash-Prüfung auf
- [ ] Manuelle Probe: gesperrter Manage-Link, gesperrte Stornierung und gesperrte Umbuchung zeigen die gleiche Meldung wie vorher
- [ ] `composer test`, `php -l`, Layout-Smoke-Test grün
