# 03: Umbuchung: Kunde bleibt, E-Mail-Regel

**What to build:** Bei einer Umbuchung über den Manage-Link bleibt der Termin beim Kunden des Original-Termins. Der E-Mail-Abgleich entfällt. Die Termin-Kontaktdaten werden aus den neuen Eingaben gesetzt. Den Kundendatensatz aktualisieren die nicht leeren Angaben wie in 02. Eine geänderte E-Mail übernimmt der Datensatz nur, wenn kein anderer Kunde sie hat. Beispiel: Anna trägt bei der Umbuchung Bernds E-Mail ein. Der Termin bleibt bei Anna, Bernd bleibt unverändert, Annas Datensatz behält ihre E-Mail.

**Blocked by:** 02

**Status:** ready-for-agent

- [ ] Regel-Modul: Umbuchung löst den Kunden über den Original-Termin auf; E-Mail-Wechsel nur bei freier Adresse
- [ ] Ein anderer Kunde wird bei der Umbuchung weder zugeordnet noch geändert
- [ ] Termin-Kontaktdaten des umgebuchten Termins entsprechen den neuen Eingaben
- [ ] Unit-Tests (rot gegen altes Verhalten, Beleg hier)
- [ ] Smoke-Fälle: Umbuchung mit E-Mail eines anderen Kunden (Termin bleibt, anderer Kunde unverändert, E-Mail des Datensatzes unverändert); Umbuchung mit freier neuer E-Mail (Datensatz übernimmt sie)
- [ ] `bash scripts/test.sh` grün
