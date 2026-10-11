# MeBeauty Appointments

Terminbuchungs-Anwendung (Easy!Appointments-Fork) für den MeBeauty-Salon; die Marketing-Website (Astro) liefert das Design.

## Language

**Booking-Flow**:
Der öffentliche Buchungsbereich der App (Buchungsseite mit Wizard); einziger Bereich mit Website-Layout.
_Avoid_: Buchungsseite, Frontend-Bereich

**Wizard-Card**:
Die helle Bootstrap-Card mit dem 4-Schritte-Buchungsformular (Service, Termin, Daten, Bestätigung).
_Avoid_: Buchungsformular-Card, Book-Appointment-Wizard-Container

**mebeauty-Site**:
Die Astro-Website unter mebeauty-koeln.de; Design-Quelle der Wahrheit für die App.
_Avoid_: Website, Hauptdomain, Marketing-Site

**Buchungs-Domain**:
Die Subdomain, unter der die Terminbuchungs-App erreichbar ist (Test: booking-test.mebeauty-koeln.de, Prod: booking.mebeauty-koeln.de).
_Avoid_: App-URL, Buchungsseite (das ist der Booking-Flow)

**Firmenfarbe (Company Color)**:
Die in den Business Settings gespeicherte Primärfarbe, die die feste Theme-Primärfarbe zur Laufzeit überschreibt (nur wenn sie vom Standard abweicht).
_Avoid_: Markenfarbe, Theme-Farbe

**Vorlaufzeit (Lead Time)**:
Mindestabstand in Minuten zwischen dem aktuellen Zeitpunkt und dem Beginn eines buchbaren Termins.
_Avoid_: Zeitbegrenzung einer Buchung

**Buchungsfrist (Booking Deadline)**:
Zeitpunkt (feste Uhrzeit plus Tagesabstand oder Mindestvorlaufzeit), bis zu dem ein **neuer** Termin an einem bestimmten Tag gebucht werden kann; danach ist der Tag für Neubuchungen gesperrt.
_Avoid_: Buchungslimit, Buchungsschluss

**Umbuchungs-/Stornierungsfrist (Manage Deadline)**:
Frist, bis zu der ein bestehender Termin umgebucht oder storniert werden kann; gilt für Umbuchen und Stornieren gemeinsam. Standardmäßig wird die Buchungsfrist übernommen, optional lässt sich eine eigene Zeit (Minuten oder feste Uhrzeit plus Tagesabstand) setzen.
_Avoid_: Änderungsfrist, Stornofrist

**Slot-Kapazität (Slot Capacity)**:
Maximale Anzahl paralleler Buchungen eines Services über alle Dienstleister hinweg (0 = unbegrenzt); bildet begrenzte Ressourcen wie Räume oder Geräte ab.
_Avoid_: Parallel-Limit, Geräte-Limit

**Tagesabstand (Day Offset)**:
Anzahl Tage N vor dem Termintag, an dem die Buchungsfrist liegt (Frist für Tag D = Tag D−N um die Frist-Uhrzeit).
_Avoid_: Vorlauftage, Offset-Tage

**Pufferzeit (Buffer Time)**:
Globale Ruhe-/Aufräumzeit nach einem Termin, in der kein neuer Termin beginnen darf; optional gilt sie auch vor dem Arbeitsende — nie vor einem Termin, einer Pause oder einer Unavailability.
_Avoid_: Aufräumzeit, Abstandszeit

**Arbeitsende**:
Ende des effektiven Tagesplans eines Dienstleisters, einschließlich einer Arbeitsplan-Ausnahme für diesen Tag; Pausen und Terminbeginne sind kein Arbeitsende.
_Avoid_: Feierabend, Periodenende, Tagesende (ohne Bezug zum Dienstleister)

**Umbuchung**:
Verschieben eines bestehenden Termins über den Manage-Link; ob sie erlaubt ist, entscheidet die Umbuchungs-/Stornierungsfrist des ursprünglichen Termins, der neue Zeitpunkt unterliegt wie jede Neubuchung der Buchungsfrist.
_Avoid_: Terminänderung, Reschedule (im Deutschen)

**Theme**:
Kundenspezifisches Erscheinungsbild der App aus Bootstrap/SCSS-Styles und optionalem Site-Chrome; je Installation ist genau ein Theme aktiv.
_Avoid_: Skin, Design (allein)

**Site-Chrome**:
Navbar, Seitenkopf (Intro), Footer und WhatsApp-Button, die im Booking-Flow die Website des Kunden nachbilden; gehört zum Theme, nicht zum Kern der App.
_Avoid_: Website-Header/-Footer, Layout

**App-Logo (Wordmark)**:
Das rechteckige MeBeauty-Logo (`assets/img/mebeauty/logo.png`), verwendet in der Buchungs-Navbar und als Social-Vorschau (og:image).
_Avoid_: quadratisches App-Logo, Icon

**Favicon**:
Das Browser-Tab-Icon (`assets/img/favicon.ico`, 16×16).
_Avoid_: App-Icon, Logo

**Sekundäres Logo**:
Das quadratische Standard-Logo (`assets/img/logo.png`), verwendet als App-Logo-Fallback (Backend-Header, E-Mail-Logo) und auf Login/Recovery/Logout/About.
_Avoid_: App-Logo, Favicon

**Fork-Version**:
Die eigenständige MeBeauty-Produktversion (`application/config/app.php` → `version`), unabhängig von der Easy!Appointments-Versionsnummer.
_Avoid_: Upstream-Version, EA-Version

**Upstream-Basis**:
Das integrierte Easy!Appointments-Release (Tag plus vollständige Commit-ID), dokumentiert in `UPSTREAM.md`.
_Avoid_: Basis-Version (ohne Kontext), Master-Branch
