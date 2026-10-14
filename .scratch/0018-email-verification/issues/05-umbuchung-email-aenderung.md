# 05: Umbuchung mit E-Mail-Änderung per Link

**What to build:** Ändert eine Kundin bei aktiver Bestätigung während einer Umbuchung ihre E-Mail, gelten neue Zeit und übrige Angaben sofort (0017/03). An die neue Adresse geht ein Bestätigungslink. Erst nach dem Klick kommt die neue E-Mail in die Termin-Kontaktdaten und in den Kundendatensatz, und zwar nur, wenn kein anderer Kunde sie hat. Bis dahin gehen Mails an die alte, bestätigte Adresse. Eine Umbuchung ohne E-Mail-Änderung braucht keine Bestätigung.

**Blocked by:** 01, 0017/03

**Status:** needs-verification

- [x] Umbuchung mit geänderter E-Mail legt eine Bestätigung mit Zweck „E-Mail-Änderung“ an; Umbuchungsmail an die alte Adresse
- [x] Klick übernimmt die E-Mail in Termin-Kontaktdaten und Datensatz (Belegt-Regel aus 0017)
- [x] Ablauf ändert nichts am Termin
- [x] Umbuchung ohne E-Mail-Änderung unverändert ohne Bestätigung
- [x] Smoke-Fall: Umbuchung mit neuer E-Mail → Zeit sofort geändert, E-Mail erst nach Link
- [x] `bash scripts/test.sh` grün

## Comments

**2026-10-14 (0018/05 umgesetzt):**

- Regel-Modul `Email_verification_rules`: `requires_email_change_verification(enabled, gespeicherte E-Mail, neue E-Mail,
  in der Sitzung bestätigte E-Mails)` (nur bei Einstellung an, nicht leerer und – ohne Groß/Klein – geänderter E-Mail,
  die nicht schon in dieser Browser-Sitzung bestätigt ist) und `email_change_verification()` (Zweck `email_change`,
  am umgebuchten Termin, 10 Minuten gültig, nur Token-Hash).
- `Email_verification::hold_back_email_change()` ersetzt in `register` vor dem Speichern die neue E-Mail durch die
  bestätigte alte (Termin-Kontaktdaten, sonst Kundendatensatz). Die Umbuchung läuft danach unverändert (0017/03), die
  Umbuchungsmail geht an die alte Adresse. Ist das Stundenlimit der neuen Adresse erreicht, wird die Umbuchung mit
  `email_verification_limit_reached` abgelehnt (nichts gespeichert). Nach dem Speichern sendet `start_email_change()`
  den Link (eigene Mail-Texte „neue E-Mail-Adresse bestätigen“, de/en) und zählt ihn im Stundenlimit; eine offene
  frühere E-Mail-Änderung desselben Termins wird ersetzt. Mailfehler: Umbuchung bleibt, E-Mail bleibt die alte (Log).
- Klick (`booking/verify/<token>`): nur ein gültiger Link übernimmt die E-Mail in die Termin-Kontaktdaten und – nach der
  Belegt-Regel aus 0017 – in den Kundendatensatz; danach Google-Sync und Webhook, keine erneuten Mails, Weiterleitung
  auf die Erfolgsseite. Ein abgelaufener Link zeigt „Link ungültig“ und ändert nichts; der Termin bleibt (Ablauf löscht
  bei `email_change` keinen Termin, siehe 02).
- ADR 0008 ergänzt.
- Rot vor Fix: `EmailVerificationRulesTest` (6 neue Tests) – `Error: Call to undefined method
  Email_verification_rules::requires_email_change_verification()` bzw. `::email_change_verification()`;
  `email-verification-smoke.sh` Abschnitt 10 (6 Checks) gegen den alten Code – u. a. `FAIL reschedule with a changed
  email (... verification null)`, `FAIL before the click: contact {... "email":"smoke-verify-change-…"}, … mails old 0
  (before 0), new 1` (Umbuchungsmail ging an die neue Adresse), `FAIL email change link`. Danach 25 Checks OK.
- Offen für den Nutzer (manuell): Bestätigungsmail für die E-Mail-Änderung ansehen (Texte Deutsch/Englisch). Die
  Erfolgsseite nach der Umbuchung zeigt keinen Hinweis, dass ein Link an die neue Adresse ging (nicht Teil des Tickets).
- Für 04: `find_by_appointments()` liefert auch Markierungen mit Zweck `email_change` an Terminen mit Kunde; als
  „unbestätigt“ nur Zweck `booking` anzeigen.

