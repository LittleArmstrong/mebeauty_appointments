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

**Firmenfarbe (Company Color)**:
Die in den Business Settings gespeicherte Primärfarbe, die die feste Theme-Primärfarbe zur Laufzeit überschreibt (nur wenn sie vom Standard abweicht).
_Avoid_: Markenfarbe, Theme-Farbe

**Vorlaufzeit (Lead Time)**:
Mindestabstand in Minuten zwischen dem aktuellen Zeitpunkt und dem Beginn eines buchbaren Termins.
_Avoid_: Zeitbegrenzung einer Buchung

**Buchungsfrist (Booking Deadline)**:
Zeitpunkt (feste Uhrzeit plus Tagesabstand), bis zu dem ein Termin an einem bestimmten Tag gebucht oder storniert werden kann; danach ist der Tag gesperrt.
_Avoid_: Buchungslimit, Buchungsschluss

**Tagesabstand (Day Offset)**:
Anzahl Tage N vor dem Termintag, an dem die Buchungsfrist liegt (Frist für Tag D = Tag D−N um die Frist-Uhrzeit).
_Avoid_: Vorlauftage, Offset-Tage

**Pufferzeit (Buffer Time)**:
Globale Ruhe-/Aufräumzeit nach einem Termin, in der kein neuer Termin beginnen darf; optional gilt sie auch vor dem Arbeitsende.
_Avoid_: Aufräumzeit, Abstandszeit
