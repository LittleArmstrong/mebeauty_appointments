# 02: Puffer-Einstellungen einmal je Berechnung lesen

**What to build:** Die Verfügbarkeitsberechnung liest Pufferzeit und „Puffer vor Arbeitsende“ einmal je Instanz statt pro Zeitspanne, Tag und Dienstleister. Die Monatsansicht erzeugt dadurch weniger identische Datenbankabfragen. Die angebotenen Slots bleiben identisch.

**Blocked by:** None (can start immediately; wegen Code-Nähe nach 0012/01 empfohlen)

**Status:** ready-for-agent

- [ ] Die Puffer-Einstellungen werden je Instanz einmal gelesen und wiederverwendet
- [ ] Die Slots eines Beispieltags sind vorher und nachher identisch (Unit-Tests der Slot-Funktion bzw. manuelle Probe)
- [ ] `composer test`, `php -l`, Layout-Smoke-Test grün
