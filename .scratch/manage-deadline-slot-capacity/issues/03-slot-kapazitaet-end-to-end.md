# 03: Slot-Kapazität end-to-end

**What to build:** Die Betreiberin legt pro Service eine maximale Anzahl paralleler Buchungen fest (`slot_capacity`, `0` = unbegrenzt). Die Verfügbarkeit zählt überlappende Buchungen desselben Services über **alle** Provider; Wizard, Datepicker, API, „Beliebiger Anbieter" und die serverseitige Buchungsvalidierung respektieren die Kapazität. Der Backend-Kalender bleibt unverändert.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [ ] Migration 075 fügt `slot_capacity` (INT, Default `0`) auf `services` ein; `down()` entfernt sie
- [ ] Services-Modell kennt `slot_capacity` (Cast, API-Mapping `slotCapacity`, Validierung `>= 0`)
- [ ] Services-Controller und Service-Formular (Backend) speichern/laden `slot_capacity` (`min="0"`)
- [ ] Neue Zählmethode zählt überlappende Buchungen desselben Services über alle Provider (optional mit Ausschluss einer Buchung)
- [ ] Verfügbarkeits-Berechnung filtert bei `slot_capacity > 0` überbelegte Slots heraus
- [ ] Kapazität zählt Buchungen (nicht Personen) und ist unabhängig von `attendants_number`
- [ ] `0` = unbegrenzt (Verhalten unverändert)
- [ ] de/en-Übersetzungen für Feld und Hinweis
- [ ] `composer test` grün; `php -l` und `node --check` je geänderte Datei
