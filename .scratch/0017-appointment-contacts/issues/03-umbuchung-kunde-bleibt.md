# 03: Umbuchung: Kunde bleibt, E-Mail-Regel

**What to build:** Bei einer Umbuchung über den Manage-Link bleibt der Termin beim Kunden des Original-Termins. Der E-Mail-Abgleich entfällt. Die Termin-Kontaktdaten werden aus den neuen Eingaben gesetzt. Den Kundendatensatz aktualisieren die nicht leeren Angaben wie in 02. Eine geänderte E-Mail übernimmt der Datensatz nur, wenn kein anderer Kunde sie hat. Beispiel: Anna trägt bei der Umbuchung Bernds E-Mail ein. Der Termin bleibt bei Anna, Bernd bleibt unverändert, Annas Datensatz behält ihre E-Mail.

**Blocked by:** 02

**Status:** resolved

- [x] Regel-Modul: Umbuchung löst den Kunden über den Original-Termin auf; E-Mail-Wechsel nur bei freier Adresse
- [x] Ein anderer Kunde wird bei der Umbuchung weder zugeordnet noch geändert
- [x] Termin-Kontaktdaten des umgebuchten Termins entsprechen den neuen Eingaben
- [x] Unit-Tests (rot gegen altes Verhalten, Beleg hier)
- [x] Smoke-Fälle: Umbuchung mit E-Mail eines anderen Kunden (Termin bleibt, anderer Kunde unverändert, E-Mail des Datensatzes unverändert); Umbuchung mit freier neuer E-Mail (Datensatz übernimmt sie)
- [x] `bash scripts/test.sh` grün

## Comments

- Umbuchung löst den Kunden über `resolve_customer_id($original_appointment, …)` auf; `customer_record` übernimmt eine geänderte E-Mail nur, wenn kein anderer Kunde sie hat (Unit-Tests `testRescheduleKeepsTheEmailWhenAnotherCustomerHasIt`, `testRescheduleTakesOverAFreeNewEmail`).
- Rot vor Umsetzung (Smoke gegen altes `Booking.php`): „FAIL reschedule with another customer's email: appointment customer 90“ (Termin wanderte zu Anna) und „FAIL reschedule with a free new email“. Danach OK.
- Die Doppelbuchungsprüfung „customer_is_already_booked“ greift jetzt auch bei Umbuchungen (mit dem Original-Kunden, eigener Termin ausgenommen).
- `bash scripts/test.sh` grün.
