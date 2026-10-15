# Manuelle Tests

Checkliste für den manuellen Durchlauf vor jedem Release. Sie deckt das ab, was man im Browser, in Mails oder im
Kalender sehen muss. Grenzwerte und Regeln, die PHPUnit oder die Smoke-Tests (`bash scripts/test.sh`) schon prüfen,
stehen nur als Verweis „automatisiert“ da.

**Pflege:** Jede Spec mit sichtbarem Verhalten ergänzt hier ihre Testfälle (ein Testfall je beobachtbarem
Akzeptanzkriterium, mit Ticket-Verweis). Die Häkchen setzt du nur in deiner lokalen Kopie, committet wird die leere
Vorlage.

**Rückmeldung:** Fehlschläge mit Testfall-ID melden, z. B. „T-EB-03 schlägt fehl: …“. Bestandene Abschnitte
verifizieren die Tickets in ihrer Überschrift.

Dauer: ca. 1–2 Stunden für einen kompletten Durchlauf.

## 0 Vorbereitung

Lokaler Docker-Stack (`docs/docker.md`). Testfälle mit **[Testserver]** brauchen den Google-Sync und laufen auf
`booking-test.mebeauty-koeln.de` mit dem dort verbundenen Google-Konto.

- [ ] **T-0-01** Stack starten und Validierung grün:

  ```bash
  bash scripts/test.sh
  ```

- [ ] **T-0-02** Schema aktuell: Ist die Dev-DB älter als der letzte Stand von Migration `070`, in phpMyAdmin
  (<http://localhost:8080>, `root`/`secret`) `ea_migrations.version` auf `69` setzen und dann:

  ```bash
  docker compose exec -T php-fpm php index.php console migrate
  ```

- [ ] **T-0-03** Testdaten im Backend (<http://localhost/index.php/calendar>), sofern noch nicht vorhanden:
  - zwei Dienstleister **A** und **B**, Arbeitsplan Mo–Fr 09:00–18:00 mit Pause 12:00–13:00
  - Dienstleistung **S60** (60 min) und **S30** (30 min), beide A und B zugeordnet
- [ ] **T-0-04** Mailpit offen: <http://localhost:8025>. Alle Mails der App landen dort.

Ausgangszustand der Einstellungen für alle Abschnitte, sofern ein Testfall nichts anderes sagt:
Theme `mebeauty`, Frist-Modus „Minuten-Vorlaufzeit“ mit 0, Pufferzeit 0, Umbuchungs-/Stornierungsfrist „Buchungsfrist
übernehmen“, E-Mail-Bestätigung aus, Footer an. Nach jedem Abschnitt zurückstellen.

Zeitabhängige Tests: Statt zu warten, Frist-Uhrzeiten wenige Minuten in die Zukunft legen bzw. die Ablaufzeit in der
Datenbank zurücksetzen (jeweils beim Testfall beschrieben).

## 1 Basis-Durchlauf (Upstream-Grundfunktion)

- [ ] **T-B-01** Booking-Flow (<http://localhost>): S60 bei A buchen. Erfolgsseite erscheint, Kunden- und Salon-Mail
  in Mailpit, Termin im Backend-Kalender.
- [ ] **T-B-02** Im Backend-Kalender einen Termin für einen bestehenden Kunden anlegen, verschieben und löschen.

## 2 Theme, Branding, Site-Chrome — 0001, 0004, 0005, 0014

- [ ] **T-TH-01** Theme `mebeauty` (Einstellungen → Allgemeine Einstellungen): Booking-Flow zeigt schwarze Seite,
  Navbar mit Logo und vier Links auf die mebeauty-Site, Intro „Buchung“ mit Hero-Bild, Wizard-Card, Website-Footer,
  WhatsApp-Button.
- [ ] **T-TH-02** Handy-Breite (DevTools, 375 px): Hamburger-Menü öffnet und schließt die Navigation; nichts scrollt
  horizontal.
- [ ] **T-TH-03** Footer-Links zu Impressum und Datenschutz führen auf die in den Rechtseinstellungen hinterlegten
  URLs (ohne Eintrag: Seiten der mebeauty-Site).
- [ ] **T-TH-04** Theme `default`: Booking-Flow im Upstream-Layout, **kein** MeBeauty-Chrome (Navbar, Footer,
  WhatsApp-Button fehlen). Danach zurück auf `mebeauty`.
- [ ] **T-TH-05** Firmenname auf `Test & „Co“ <b>` ändern: Browser-Tab, Backend-Kopf und Betreff einer
  Buchungsmail zeigen den Namen wörtlich, nichts wird fett. Danach zurück auf „MeBeauty“.
- [ ] **T-TH-06** Firmenlogo hochladen: erscheint im Backend-Kopf und in einer neuen Buchungsmail. Ohne Logo:
  generisches Logo.
- [ ] **T-TH-07** „Firmenkopf auf Buchungsseite anzeigen“ (Buchungseinstellungen) an: Logo und Name im Kopf der
  Wizard-Card; aus: Kopf entfällt.
- [ ] **T-TH-08** Farbkette: ohne Firmenfarbe (Weiß) sind Erfolgsseite, Schritt-Indikator und Mail-Buttons gold; mit
  Firmenfarbe z. B. Blau sind sie blau; mit Theme `default` und ohne Firmenfarbe grün.
- [ ] **T-TH-09** Fehlerseite (<http://localhost/index.php/gibtsnicht>) zeigt einen Marken-Titel, kein
  „Easy!Appointments“.
- [ ] **T-TH-10** Sprache auf Englisch (`?language=english`): Navbar- und Footer-Texte sind englisch.

## 3 Buchungsfrist, Puffer, Zeitzone — 0003, 0012

- [ ] **T-FR-01** Frist-Modus „Feste Uhrzeit“, Frist-Uhrzeit = jetzt + 5 min, Tagesabstand 1: Morgen ist buchbar.
  Nach Ablauf der 5 min Seite neu laden: Morgen ist im Datumswähler ausgegraut.
- [ ] **T-FR-02** Tagesabstand 0, Frist-Uhrzeit = vor einer Minute: Heute ist gesperrt, morgen buchbar.
- [ ] **T-FR-03** Frist-Modus „Minuten-Vorlaufzeit“ mit 120: Heute werden erst Zeiten ab jetzt + 2 h angeboten.
- [ ] **T-FR-04** Im Backend-Kalender lässt sich trotz abgelaufener Frist ein Termin für heute anlegen.
- [ ] **T-PU-01** Pufferzeit 30 (Unternehmerische Logik), Termin S60 bei A morgen 10:00: A bietet frühestens 11:30
  an; 09:00 (endet bündig vor dem Termin) bleibt buchbar.
- [ ] **T-PU-02** Bündig vor der Pause: S60 um 11:00 bei A wird angeboten.
- [ ] **T-PU-03** „Puffer auch am Tagesende anwenden“ an: letzter S60-Slot bei A ist 16:30; aus: 17:00.
- [ ] **T-PU-04** Arbeitsplan-Ausnahme für A morgen bis 15:00, Option an: letzter S60-Slot 13:30.
- [ ] **T-PU-05** Nichtverfügbarkeit bei A morgen 15:00–18:00, Option an: S60 um 14:00 bleibt buchbar.
- [ ] **T-TZ-01** „Zeitzonen-Anzeige“ = „Dropdown und Bestätigungs-Frame“: Dropdown im Terminschritt und Zeile im
  Bestätigungsschritt.
- [ ] **T-TZ-02** „Nur im Bestätigungs-Frame“: kein Dropdown, Zeile in der Bestätigung.
- [ ] **T-TZ-03** „Komplett ausblenden“ mit Quelle „Browser-Zeitzone“: DevTools → Sensors → Location „Tokyo“:
  angebotene Zeiten sind in Tokio-Zeit verschoben. Mit Quelle „Firmen-Zeitzone“: Zeiten wie in der Firmenzeitzone.

Automatisiert: Frist-, Puffer- und Arbeitsende-Grenzfälle (PHPUnit `Booking_policy`, Availability).

## 4 Slot-Kapazität — 0006

- [ ] **T-KA-01** S60 Slot-Kapazität 1 (Dienstleistungen): S60 morgen 10:00 bei A buchen. Danach bietet B für S60
  um 10:00 (und überlappend 09:30) nichts an; bei „Beliebiger Anbieter“ ebenfalls nicht.
- [ ] **T-KA-02** Kapazität 2: B bietet 10:00 noch an; nach der zweiten Buchung nicht mehr.
- [ ] **T-KA-03** Kapazität 0: unbegrenzt wie Upstream.
- [ ] **T-KA-04** Im Backend-Kalender lässt sich trotz voller Kapazität ein dritter S60 um 10:00 anlegen.
- [ ] **T-KA-05** Kapazität „abc“, „1.5“ oder „-1“ speichern: Fehlermeldung, nichts gespeichert.

## 5 Manage-Link: Umbuchen und Stornieren — 0006, 0009

Manage-Link = der Termin-Link aus der Kunden-Mail eines Termins (Mailpit).

- [ ] **T-MA-01** Innerhalb der Frist: Manage-Link öffnen, auf eine andere Zeit umbuchen. Der alte Zeitpunkt wird
  dabei als frei angeboten; danach existiert genau ein Termin (kein Doppeltermin).
- [ ] **T-MA-02** Stornieren über den Manage-Link: Termin weg, Stornomail in Mailpit.
- [ ] **T-MA-03** Umbuchungs-/Stornierungsfrist „eigene Frist“ in Minuten = 1440, Termin in weniger als 24 h:
  Manage-Link zeigt die Sperrseite mit konkretem Grenzwert. Termin in mehr als 24 h: umbuchbar.
- [ ] **T-MA-04** Manage-Frist „Feste Uhrzeit“ jetzt + 3 min, Tagesabstand 1, Termin morgen: Manage-Link öffnen und
  offen lassen, Frist ablaufen lassen, dann absenden: abgelehnt mit Sperrmeldung, Termin unverändert.
- [ ] **T-MA-05** Manage-Link mit verändertem Hash (letztes Zeichen ändern): „Termin nicht gefunden.“, keine Umbuchung.
- [ ] **T-MA-06** Im Backend lässt sich der gesperrte Termin weiterhin verschieben.

Automatisiert: Hash-/ID-Bindung und Abfragen ohne Hash (PHPUnit, 0009).

## 6 Einstellungsvalidierung — 0010

- [ ] **T-VA-01** Buchungseinstellungen: Frist-Uhrzeit „25:00“ speichern → Fehlermeldung am Feld, **auch die
  übrigen** geänderten Werte sind nicht gespeichert (Seite neu laden).
- [ ] **T-VA-02** Tagesabstand „-1“ bzw. Pufferzeit „x“ → Fehlermeldung, nichts gespeichert.

Automatisiert: alle Regeln und der API-Weg (PHPUnit, 0010).

## 7 Termin-Kontaktdaten — 0017/04, 0017/05

- [ ] **T-KO-01** Online mit `anna@example.com`, Name „Anna Alt“, Telefon 111 buchen. Dann erneut mit derselben
  E-Mail, Name „Lena Neu“, Telefon leer buchen. Im Kalender zeigt der erste Termin „Anna Alt“, der zweite
  „Lena Neu“; der Kundendatensatz behält Telefon 111.
- [ ] **T-KO-02** Salon-Mail des zweiten Termins zeigt „Lena Neu“.
- [ ] **T-KO-03** Termin-Dialog (Backend): genau ein Kundenblock, vorbelegt mit den Kontaktdaten des Termins.
- [ ] **T-KO-04** Im Dialog des ersten Termins Telefon auf 222 ändern und speichern: Termin und Kundendatensatz
  zeigen 222; der zweite Termin bleibt unverändert.
- [ ] **T-KO-05** Termin per Drag & Drop verschieben: Kontaktdaten bleiben.
- [ ] **T-KO-06** Backend-Termin für einen neuen Kunden anlegen: Kachel zeigt dessen Namen.
- [ ] **T-KO-07** Manage-Link des ersten Termins: andere E-Mail `fremd@example.com` eintragen und umbuchen (ohne
  E-Mail-Bestätigung). Der Termin gehört weiter Anna; nutzt kein anderer Kunde die Adresse, übernimmt Annas Datensatz
  sie.
- [ ] **T-KO-08** **[Testserver]** Google-Termin einer Online-Buchung beginnt mit dem Block `--- Kontakt ---` (Name,
  Telefon, E-Mail); auf dem Handy ist die Nummer antippbar.
- [ ] **T-KO-09** **[Testserver]** Im Google-Termin unter dem Block eine Notiz ergänzen, Sync auslösen: Die Notiz
  steht im App-Termin, der Kontaktblock nicht.

## 8 E-Mail-Bestätigung und Vormerkung — 0018/01–05

Einstellung „E-Mail-Bestätigung erforderlich“ (Buchungseinstellungen) an.

- [ ] **T-EB-01** Online buchen: Hinweis „Bitte bestätigen Sie Ihre E-Mail-Adresse“ mit „E-Mail erneut senden“. In
  Mailpit nur die Bestätigungsmail, keine Kunden- oder Salon-Mail. Im Backend-Kalender erscheint der Termin als
  „unbestätigt“.
- [ ] **T-EB-02** Während der Vormerkung bietet ein zweites Browserfenster (privat) den Slot nicht an.
- [ ] **T-EB-03** Link in derselben Browser-Sitzung öffnen: Erfolgsseite direkt, ohne Schaltfläche. Danach Kunden-
  und Salon-Mail in Mailpit, Termin ohne „unbestätigt“.
- [ ] **T-EB-04** Neue Buchung, Link in einem privaten Fenster (anderes Gerät) öffnen: Seite „Termin bestätigen“ mit
  Schaltfläche. Klick → Erfolgsseite.
- [ ] **T-EB-05** Denselben Link ein zweites Mal öffnen: „Link ungültig“.
- [ ] **T-EB-06** Nach der Bestätigung in derselben Sitzung ein zweites Mal mit derselben E-Mail buchen: sofort
  gebucht, keine Bestätigungsmail.
- [ ] **T-EB-07** Unbestätigten Termin im Backend öffnen: Hinweis, nur Löschen möglich. Löschen verschickt keine Mail.
- [ ] **T-EB-08** Erneut senden: direkt nach dem Absenden → Wartehinweis mit Sekunden. Nach 60 s → neue Mail, der
  Link der alten Mail ist danach „ungültig“.
- [ ] **T-EB-09** Später Klick, Slot frei: Vormerkung anlegen, in phpMyAdmin
  `UPDATE ea_appointment_verifications SET expires_datetime = NOW() - INTERVAL 1 MINUTE;`, Booking-Flow neu laden
  (räumt auf, Slot wieder frei, unbestätigter Termin verschwindet aus dem Kalender). Dann Link öffnen und bestätigen
  → Termin gebucht.
- [ ] **T-EB-10** Später Klick, Slot vergeben: wie T-EB-09, aber vor dem Klick den Slot in einem anderen Fenster
  buchen → Seite „Leider abgelaufen“ mit Link zur Buchung.
- [ ] **T-EB-11** Nach 24 h ungültig: wie T-EB-09, zusätzlich
  `last_sent_datetime = NOW() - INTERVAL 25 HOUR` → „Link ungültig“.
- [ ] **T-EB-12** Mit Englisch buchen: Bestätigungsmail und Seiten auf Englisch.
- [ ] **T-EB-13** Umbuchung ohne E-Mail-Änderung über den Manage-Link: sofort umgebucht, keine Bestätigungsmail.
- [ ] **T-EB-14** Umbuchung mit neuer E-Mail (privates Fenster): neue Zeit gilt sofort, Umbuchungsmail geht an die
  **alte** Adresse, Bestätigungsmail „neue E-Mail-Adresse“ an die neue. Nach Bestätigung zeigen Termin und Datensatz
  die neue Adresse.
- [ ] **T-EB-15** **[Testserver]** Vormerkung erscheint nicht im Google-Kalender, erst nach der Bestätigung.
- [ ] **T-EB-16** Aufräumen per Konsole: abgelaufene Vormerkung (wie T-EB-09) verschwindet nach

  ```bash
  docker compose exec -T php-fpm php index.php console cleanup
  ```

Automatisiert: Stundenlimit (5 Mails/h), Sperrfrist, Haltezeit 20 min, Neutralität der Fehlermeldungen (PHPUnit
`Email_verification_rules`).

## 9 Footer und Sprachauswahl — 0014, 0019/01

- [ ] **T-FO-01** „Sprachauswahl im Buchungsformular anzeigen“ aus: kein Sprachumschalter im Booking-Flow.
- [ ] **T-FO-02** Footer an, aber keine Rechts-URL, Sprachauswahl aus, Login-Button aus: Unter der Wizard-Card ist kein
  Footer und keine Trennlinie.
- [ ] **T-FO-03** Footer an mit Login-Button an: Footer mit Login-Button sichtbar.
- [ ] **T-FO-04** „Footer anzeigen“ aus (Login-Button an): kein Footer. Der Website-Footer des Themes bleibt. Das
  Backend ist über <http://localhost/index.php/login> erreichbar.
