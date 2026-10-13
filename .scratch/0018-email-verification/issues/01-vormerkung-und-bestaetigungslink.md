# 01: Einstellung, Vormerkung und Bestätigungslink (Normalfall)

**What to build:** In den Buchungseinstellungen gibt es „E-Mail-Bestätigung erforderlich“, standardmäßig aus. Ist sie an, legt eine Online-Neubuchung eine Vormerkung an. Das ist ein Termin, der den Slot hält, noch ohne Kunde. Die Termin-Kontaktdaten tragen die Eingaben. An die eingegebene Adresse geht ein Bestätigungslink, und der Wizard zeigt „Termin 10 Minuten reserviert, bitte Link in der Mail klicken“. Der Klick funktioniert auch auf einem anderen Gerät. Er ordnet den Kunden zu bzw. legt ihn an, aktualisiert den Datensatz nach 0017, entfernt die Markierung und startet Kunden- und Salon-Mail, Google-Sync und Webhook. Danach erscheint die bestehende Erfolgsseite. Die Adresse gilt in dieser Browser-Sitzung als bestätigt, eine weitere Buchung damit läuft direkt. Ist die Einstellung aus, verhält sich alles wie in 0017.

**Blocked by:** 0017/02

**Status:** ready-for-agent

- [ ] Einstellung `require_email_verification` (0/1, Default 0) in Migration 070 (idempotent), Regel im zentralen Einstellungs-Mechanismus (Unit-Test), pflegbar in den Buchungseinstellungen mit Hilfetext zum Mailversand
- [ ] Fork-Tabelle `appointment_verifications` in 070 (Bezug Termin, Zweck, zu bestätigende E-Mail, Token-Hash, Ablaufzeit, Zeitpunkt der letzten Mail, Zähler, Wiederherstellungsdaten); Token nur gehasht gespeichert, einmal verwendbar
- [ ] Vormerkung: Termin ohne Kunde, blockiert Slot und Slot-Kapazität; keine Kunden-/Salon-Mail, kein Google-Sync, kein Webhook bis zur Bestätigung
- [ ] Bestätigungsmail im Stil der vorhandenen Mails, Texte Deutsch/Englisch über die Übersetzungen
- [ ] Öffentlicher Bestätigungs-Endpunkt: gültiger Token → bestätigt und Erfolgsseite; zweiter Klick → neutrale Meldung „ungültig“
- [ ] Wizard zeigt nach dem Absenden den Hinweis statt der Erfolgsseite
- [ ] Sitzung merkt bestätigte Adressen; zweite Buchung damit ohne Vormerkung
- [ ] Smoke-Abschnitt (Einstellung 1): register → Vormerkung ohne Kunde, Slot belegt; Fixture setzt bekannten Token; Link → Termin mit Kunde, Markierung weg; zweiter Klick ungültig
- [ ] Deployment-Doku nennt das Einschalten für MeBeauty (bereits vorbereitet, prüfen)
- [ ] `bash scripts/test.sh` grün
