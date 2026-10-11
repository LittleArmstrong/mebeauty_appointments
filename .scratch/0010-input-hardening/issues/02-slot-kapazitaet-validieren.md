# 02: Slot-Kapazität strikt als ganze Zahl ≥ 0

**What to build:** Ein Service mit einer Slot-Kapazität, die keine ganze Zahl ≥ 0 ist, wird beim Speichern abgelehnt, im Backend und über die Service-API mit demselben Validierungsfehler. Leer oder nicht gesetzt bedeutet weiterhin unbegrenzt. Die API-Beschreibung enthält das Feld.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Die Validierung des Service-Models lehnt `abc`, `1.5` und `-1` ab; `0`, `3` und leer/nicht gesetzt sind gültig
- [x] Backend-Speichern und Service-API liefern denselben Fehler (manuelle Probe)
- [x] Die OpenAPI-Beschreibung enthält `slotCapacity` im Service-Schema
- [x] Unit-Test der Regel
- [x] `composer test` und `php -l` grün

## Comments

- Gemeinsame Prüffunktion `validate_non_negative_integer()` (auch von den Einstellungsregeln genutzt). Service-API geprüft: `"abc"`, `1.5`, `-1` → Validierungsfehler, `2` → gespeichert. OpenAPI: `slotCapacity` in ServiceRecord und ServicePayload.

- Review: technische Obergrenze 2147483647 (Spaltentyp INT) ergänzt; vorher führte z. B. `99999999999` zu einem DB-Fehler statt einer Validierungsmeldung.
