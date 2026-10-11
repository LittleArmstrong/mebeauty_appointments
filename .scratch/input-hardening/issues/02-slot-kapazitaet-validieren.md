# 02: Slot-Kapazität strikt als ganze Zahl ≥ 0

**What to build:** Ein Service mit einer Slot-Kapazität, die keine ganze Zahl ≥ 0 ist, wird beim Speichern abgelehnt, im Backend und über die Service-API mit demselben Validierungsfehler. Leer oder nicht gesetzt bedeutet weiterhin unbegrenzt. Die API-Beschreibung enthält das Feld.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Die Validierung des Service-Models lehnt `abc`, `1.5` und `-1` ab; `0`, `3` und leer/nicht gesetzt sind gültig
- [ ] Backend-Speichern und Service-API liefern denselben Fehler (manuelle Probe)
- [ ] Die OpenAPI-Beschreibung enthält `slotCapacity` im Service-Schema
- [ ] Unit-Test der Regel
- [ ] `composer test` und `php -l` grün
