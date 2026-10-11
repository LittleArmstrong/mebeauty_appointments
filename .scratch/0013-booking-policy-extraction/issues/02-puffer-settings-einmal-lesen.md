# 02: Puffer-Einstellungen einmal je Berechnung lesen

**What to build:** Die Verfügbarkeitsberechnung liest Pufferzeit und „Puffer vor Arbeitsende“ einmal je Instanz statt pro Zeitspanne, Tag und Dienstleister. Die Monatsansicht erzeugt dadurch weniger identische Datenbankabfragen. Die angebotenen Slots bleiben identisch.

**Blocked by:** None (can start immediately; wegen Code-Nähe nach 0012/01 empfohlen)

**Status:** resolved

- [x] Die Puffer-Einstellungen werden je Instanz einmal gelesen und wiederverwendet
- [x] Die Slots eines Beispieltags sind vorher und nachher identisch (Unit-Tests der Slot-Funktion bzw. manuelle Probe)
- [x] `composer test`, `php -l`, Layout-Smoke-Test grün

## Comments

- Pufferzeit und Tagesende-Option werden je Instanz einmal gelesen. Slots unverändert (Montag 2026-10-26: mit Puffer 15 letzter Slot 17:15, ohne 17:30, 14:00 vor der Pause angeboten); Unit- und Smoke-Tests grün.
