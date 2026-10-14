# 01: Einstellung, Vormerkung und Bestätigungslink (Normalfall)

**What to build:** In den Buchungseinstellungen gibt es „E-Mail-Bestätigung erforderlich“, standardmäßig aus. Ist sie an, legt eine Online-Neubuchung eine Vormerkung an. Das ist ein Termin, der den Slot hält, noch ohne Kunde. Die Termin-Kontaktdaten tragen die Eingaben. An die eingegebene Adresse geht ein Bestätigungslink, und der Wizard zeigt „Termin 10 Minuten reserviert, bitte Link in der Mail klicken“. Der Klick funktioniert auch auf einem anderen Gerät. Er ordnet den Kunden zu bzw. legt ihn an, aktualisiert den Datensatz nach 0017, entfernt die Markierung und startet Kunden- und Salon-Mail, Google-Sync und Webhook. Danach erscheint die bestehende Erfolgsseite. Die Adresse gilt in dieser Browser-Sitzung als bestätigt, eine weitere Buchung damit läuft direkt. Ist die Einstellung aus, verhält sich alles wie in 0017.

**Blocked by:** 0017/02

**Status:** needs-verification

- [x] Einstellung `require_email_verification` (0/1, Default 0) in Migration 070 (idempotent), Regel im zentralen Einstellungs-Mechanismus (Unit-Test), pflegbar in den Buchungseinstellungen mit Hilfetext zum Mailversand
- [x] Fork-Tabelle `appointment_verifications` in 070 (Bezug Termin, Zweck, zu bestätigende E-Mail, Token-Hash, Ablaufzeit, Zeitpunkt der letzten Mail, Zähler, Wiederherstellungsdaten); Token nur gehasht gespeichert, einmal verwendbar
- [x] Vormerkung: Termin ohne Kunde, blockiert Slot und Slot-Kapazität; keine Kunden-/Salon-Mail, kein Google-Sync, kein Webhook bis zur Bestätigung
- [x] Bestätigungsmail im Stil der vorhandenen Mails, Texte Deutsch/Englisch über die Übersetzungen
- [x] Öffentlicher Bestätigungs-Endpunkt: gültiger Token → bestätigt und Erfolgsseite; zweiter Klick → neutrale Meldung „ungültig“
- [x] Wizard zeigt nach dem Absenden den Hinweis statt der Erfolgsseite
- [x] Sitzung merkt bestätigte Adressen; zweite Buchung damit ohne Vormerkung
- [x] Smoke-Abschnitt (Einstellung 1): register → Vormerkung ohne Kunde, Slot belegt; Fixture setzt bekannten Token; Link → Termin mit Kunde, Markierung weg; zweiter Klick ungültig
- [x] Deployment-Doku nennt das Einschalten für MeBeauty (bereits vorbereitet, prüfen)
- [x] `bash scripts/test.sh` grün

## Comments

- Einstellung `require_email_verification` (Default 0) und Tabelle `appointment_verifications` in 070 (idempotent, Rerun-Check grün). FK auf den Termin `ON DELETE SET NULL`, damit die Wiederherstellungsdaten (Service, Dienstleister, Beginn, Ende, Notizen, Formular-Kundendaten als JSON in `customer_data`) einen gelöschten Vormerkungs-Termin überleben (02). Token: 256 Bit zufällig, gespeichert nur als SHA-256 (`token_hash`, unique); Bestätigung löscht die Zeile zuerst (`take()`, einmal verwendbar, auch bei parallelem Klick).
- Regel-Modul `Email_verification_rules` (rein: `requires_verification`, `remember_verified`, `booking_verification`, `customer_input`, `token_hash`), Ablauf `Email_verification` (`required`, `start_booking`, `confirm`), Model `Appointment_verifications_model` (`create_vormerkung` legt Termin ohne Kunde, Termin-Kontaktdaten und Bestätigung in einer Transaktion an).
- `Booking::register` verzweigt nach der Verfügbarkeitsprüfung: Vormerkung + Bestätigungsmail, Antwort `{"verification_pending": true}` ohne Termin-Hash (der Hash ist der Manage-Link). Scheitert die Mail, wird die Vormerkung wieder gelöscht und eine Fehlermeldung gezeigt. Neuer Endpunkt `GET booking/verify/<token>`; Sync, Mails und Webhook laufen über `after_appointment_saved()`, das `register` jetzt mitbenutzt. Die Adresse wird in der Sitzung des klickenden Browsers als bestätigt gemerkt.
- Abweichung: `Email_messages` (Upstream) bekommt `send_email_verification_link()`, weil `get_php_mailer()` privat ist; das Mail-Template ist die neue Fork-View `emails/email_verification_email.php`.
- Bekannt, Ticket 04: Der Backend-Kalender lädt den Kunden jedes Termins (`Calendar.php:707`, `:864`) und scheitert, solange eine Vormerkung ohne Kunde im angezeigten Zeitraum liegt. Einstellung erst nach 04 produktiv einschalten.
- Rot vor Fix: `SettingValidationTest` – 2 Failures „Failed asserting that null is not null“ für `require_email_verification` = `yes` / `''`.
- Rot vor Fix: `EmailVerificationRulesTest` – Error „Failed opening required …/Email_verification_rules.php“.
- Rot vor Fix: Smoke `email-verification` gegen das alte `Booking.php` – „FAIL register with verification (HTTP 200, {"appointment_id":157,"appointment_hash":"…"})“ (direkt gebucht, Hash ausgeliefert), „FAIL verification record … null“. Danach 8/8 OK.
- Manuelle Sichtprüfung offen: Wizard-Hinweis nach dem Absenden, Bestätigungsmail (Deutsch/Englisch) in Mailpit, Schalter mit Hilfetext in den Buchungseinstellungen.
- Deployment-Doku geprüft: `docs/deployment.md` nennt das Einschalten für MeBeauty mit Mail-Voraussetzung.
- `bash scripts/test.sh` grün.
