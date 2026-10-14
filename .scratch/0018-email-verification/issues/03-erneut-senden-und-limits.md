# 03: Erneut senden und Limits

**What to build:** Im Wizard-Hinweis kann die Kundin die Bestätigungsmail erneut anfordern. Das geht frühestens 60 Sekunden nach der letzten Mail und höchstens 5-mal pro E-Mail-Adresse und Stunde, damit niemand fremde Postfächer zuspammt. Ein neuer Link macht den vorherigen ungültig. Die Meldungen verraten nicht, ob eine Adresse bekannt ist.

**Blocked by:** 01

**Status:** needs-verification

- [x] Regel-Modul: Entscheidung „darf senden“ aus letzter Mail, Zähler und Zeitpunkt; Unit-Tests (Sperrfrist, Stundenlimit, Zurücksetzen nach einer Stunde)
- [x] Endpunkt „erneut senden“ für eine bestehende Vormerkung; neuer Token ersetzt den alten
- [x] Wizard-Button mit Rückmeldung (gesendet / bitte warten), Texte Deutsch/Englisch
- [x] Das Stundenlimit gilt auch für neue Vormerkungen mit derselben Adresse
- [x] `bash scripts/test.sh` grün

## Comments

**2026-10-14 (0018/03 umgesetzt):**

- Regel-Modul `Email_verification_rules`: `send_decision(limit, now, resend)` → allowed / wait / limit mit Wartesekunden,
  `limit_after_send()` (Fenster beginnt mit der ersten Mail, Zähler startet 60 Minuten später neu),
  `resent_verification()` (neuer Token-Hash, Ablauf 10 Minuten ab neuer Mail, Zähler +1), Cutoff `limits` in
  `cleanup_cutoffs()`. Die 60-Sekunden-Pause gilt nur für „erneut senden“, das Stundenlimit für jede Mail inkl. neuer
  Vormerkung.
- Sendelimit je Adresse in neuer Tabelle `email_verification_limits` (in Migration 070, Konsolidierungsprinzip), weil es
  über mehrere Vormerkungen zählt und eine bestätigte/gelöschte Vormerkung überdauern muss (ADR 0008 ergänzt).
- Endpunkt `POST booking/resend_verification` (CSRF): nimmt keine E-Mail und keine ID vom Client, sondern die Vormerkung
  aus der Browser-Sitzung (`register` merkt sich die Termin-ID). Antwort `{sent, message, wait_seconds}` ohne Hash;
  ohne offene Vormerkung eine neutrale Meldung. Mailfehler stellt den alten Link wieder her.
- Wizard-Hinweis: Button „E-Mail erneut senden“ mit Rückmeldung (gesendet / bitte warten / Limit / nicht mehr offen),
  Texte Deutsch/Englisch.
- Rot vor Fix: `EmailVerificationRulesTest` (11 neue Tests) gegen den alten Code – `Error: Undefined constant
  Email_verification_rules::SEND_WAIT` bzw. `Call to undefined method Email_verification_rules::limit_after_send()`;
  `testLimitStartsAgainOneHourAfterTheFirstMailOfTheWindow` mit simuliertem Fenster ohne Ende – `Failed asserting that
  two arrays are identical.`; `email-verification-smoke.sh` Abschnitt 9 (6 Checks) – `FAIL resend within 60 seconds
  (HTTP 404 …)`, `FAIL new Vormerkung over the limit (HTTP 200, {"verification_pending":true}, mails 3)`.
- Offen für den Nutzer (manuell): Button und Meldungen im Wizard-Hinweis ansehen (Deutsch/Englisch), Button bleibt nach
  dem Senden 60 Sekunden deaktiviert.
