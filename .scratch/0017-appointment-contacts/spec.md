# Termin-Kontaktdaten: Kontaktdaten pro Termin, Kundendatensatz geschützt

Status: resolved
Created: 2026-10-13
Closed: 2026-10-14
Priority: P1
Depends-on: 0009

## Problem Statement

Eine Online-Buchung ordnet den Kunden allein über die E-Mail-Adresse zu (`application/controllers/Booking.php:489-490`, `application/models/Customers_model.php:206`, `application/models/Customers_model.php:233`). Anschließend speichert sie die Formulardaten in diesen Kundendatensatz (`application/controllers/Booking.php:544`). Felder, die im Formular fehlen, werden vorher auf leer gesetzt (`application/controllers/Booking.php:406-424`) und löschen dadurch gespeicherte Daten. Der Kunde wird gespeichert, bevor der Termin gespeichert ist (`application/controllers/Booking.php:544` vor `:558`). Die Notizen sind nicht betroffen, sie gehören zum Termin.

Daraus ergeben sich mehrere Folgen:

- **Familie/Fremde:** Lena bucht mit Annas E-Mail. Annas Datensatz trägt danach Lenas Namen und Telefonnummer. Auch Annas frühere Termine zeigen nun Lena, denn ein Termin hat keine eigenen Kontaktdaten und zeigt immer den aktuellen Datensatz.
- **Leeres Feld:** Lässt Anna bei einer neuen Buchung das Telefonfeld leer, wird ihre gespeicherte Nummer gelöscht.
- **Umbuchung:** Bei einer Umbuchung setzt der Server erst den Kunden des Original-Termins (`application/controllers/Booking.php:441`) und überschreibt ihn danach per E-Mail-Abgleich (`application/controllers/Booking.php:489-490`). Trägt Anna Bernds E-Mail ein, wandert ihr Termin zu Bernd, und Bernds Datensatz wird überschrieben.

Die Mitarbeiter arbeiten hauptsächlich in Google Kalender. Der Google-Termin enthält den Service als Titel, die Notizen als Beschreibung und die Kundin als Teilnehmerin mit Name und E-Mail. Eine Telefonnummer fehlt (`application/libraries/Google_sync.php:203-228`). Ohne Rückfrage im Backend lässt sich die Kundin also nicht anrufen.

Das Backend nutzt dieselbe E-Mail-Zuordnung (`application/controllers/Calendar.php:302-306`). Dort arbeitet aber das Personal, und das soll Stammdaten pflegen dürfen.

## Solution

Jeder Termin mit Kunde speichert beim Anlegen seine eigenen **Termin-Kontaktdaten**: Vorname, Nachname, E-Mail und Telefon, so wie sie bei dieser Buchung angegeben wurden. Sie bleiben am Termin, auch wenn später mit derselben E-Mail anders gebucht wird. Wo ein Termin erscheint, stehen seine Kontaktdaten: Kalender-Kachel, Termin-Dialog, Salon-Mail und Google-Termin. Der Google-Termin bekommt dafür einen Kontaktblock mit Telefonnummer.

Der **Kundendatensatz** dient der Wiedererkennung und enthält den letzten Stand. Er wird erst geändert, wenn der Termin gespeichert ist, und nur mit nicht leeren Angaben. Bei einer Umbuchung über den Manage-Link bleibt der Termin beim ursprünglichen Kunden. Wer den Manage-Link hat, gilt als dieser Kunde. Eine geänderte E-Mail übernimmt der Datensatz nur, wenn kein anderer Kunde sie hat. Kundenmails gehen an die Adresse aus dem Datensatz.

Das Personal bearbeitet im Termin-Dialog die Kontaktdaten des Termins. Geänderte Felder übernimmt auch der Datensatz.

## User Stories

1. Als Mitarbeiterin will ich im Google-Termin Name, Telefonnummer und E-Mail der Kundin sehen, damit ich sie ohne Backend kontaktieren kann.
2. Als Mitarbeiterin will ich die Telefonnummer im Google-Termin antippen können, damit ich direkt vom Handy anrufen kann.
3. Als Mitarbeiterin will ich an einem Termin genau die Kontaktdaten sehen, mit denen er gebucht wurde, damit ich die richtige Person erreiche.
4. Als Salonbetreiberin will ich, dass ein alter Termin seine Kontaktdaten behält, wenn später mit derselben E-Mail anders gebucht wird, damit die Historie stimmt.
5. Als Salonbetreiberin will ich, dass Lena, die mit Annas E-Mail bucht, an ihrem Termin als Lena erscheint, damit ich weiß, wer kommt.
6. Als Stammkundin will ich, dass ein leeres Telefonfeld meine gespeicherte Nummer nicht löscht, damit der Salon mich weiter erreicht.
7. Als Stammkundin will ich, dass meine neue Telefonnummer aus der Online-Buchung in meinen Datensatz übernommen wird, damit der Salon aktuelle Daten hat.
8. Als Kundin mit Manage-Link will ich, dass mein umgebuchter Termin mir gehört, auch wenn ich im Formular eine andere E-Mail eintrage, damit er nicht bei jemand anderem landet.
9. Als Kunde will ich, dass niemand meinen Datensatz übernimmt, indem er bei seiner Umbuchung meine E-Mail einträgt.
10. Als Kundin mit Manage-Link will ich bei einer Umbuchung meine E-Mail ändern können, damit künftige Mails an die neue Adresse gehen.
11. Als Kundin will ich Mails zu allen meinen Terminen an meine aktuelle Adresse bekommen, damit ich nach einer Adressänderung nichts verpasse.
12. Als Salonbetreiberin will ich, dass kein Kundendatensatz geändert wird, wenn das Speichern des Termins scheitert, damit keine halben Buchungen Daten verändern.
13. Als Mitarbeiterin will ich in der Kalender-Kachel den Namen aus den Termin-Kontaktdaten sehen, damit ich den Kalender nicht erst öffnen muss.
14. Als Mitarbeiterin will ich im Termin-Dialog genau einen Kundenblock mit den Kontaktdaten des Termins sehen, damit nichts verwechselt wird.
15. Als Mitarbeiterin will ich im Termin-Dialog einen Tippfehler in der Telefonnummer korrigieren können. Die Korrektur soll für diesen Termin und den Datensatz gelten.
16. Als Mitarbeiterin will ich einen Termin verschieben können, ohne dass sich seine Kontaktdaten ändern.
17. Als Mitarbeiterin will ich für Telefonbuchungen Termine im Backend anlegen, die ebenfalls ihre Kontaktdaten speichern, damit eine spätere Online-Buchung sie nicht verfälscht.
18. Als Mitarbeiterin will ich weiterhin einen bestehenden Kunden auswählen und seine Stammdaten pflegen können.
19. Als Salonbetreiberin will ich in der Salon-Mail die Kontaktdaten des Termins sehen.
20. Als Kundin will ich in der Bestätigungsmail meine Termindaten sehen.
21. Als Admin will ich, dass alte Termine ohne Termin-Kontaktdaten weiter den Kundendatensatz zeigen, damit nichts leer erscheint.
22. Als Mitarbeiterin will ich, dass der Kontaktblock nicht als Notiz zurück in die App wandert, wenn ich den Google-Termin bearbeite.
23. Als Mitarbeiterin will ich, dass meine Notizen im Google-Termin weiter in die App synchronisiert werden.
24. Als Entwickler will ich die Regeln „welcher Kunde, was wird gespeichert“ an einer Stelle haben, damit sie testbar sind und der Upstream-Diff klein bleibt.
25. Als Admin will ich, dass Sprache und Zeitzone der Kundin wie bisher aus der letzten Buchung übernommen werden.
26. Als Admin will ich Termine, die in Google angelegt wurden, weiterhin als Blocker ohne Kunde sehen.

## Implementation Decisions

- **Begriff:** „Termin-Kontaktdaten“ (Glossar) sind Vorname, Nachname, E-Mail und Telefon eines Termins. Adresse, Stadt, PLZ, Bundesland, Zusatzfelder, Sprache und Zeitzone stehen nur im Kundendatensatz.
- **Schema:** neue Fork-Tabelle `appointment_contacts`, je Termin höchstens eine Zeile, verknüpft über `id_appointments`. Echte Spalten für die vier Felder, Zeitstempel für Anlegen und Ändern. Wird der Termin gelöscht, wird die Zeile mitgelöscht (Fremdschlüssel). Die Upstream-Tabelle `appointments` bleibt unverändert.
- **Migration:** Bis zum ersten Prod-Release fließt alles in Migration 070. 070 nimmt die Tabelle auf und übernimmt die Einstellung `display_language_selector` aus 071. Die Datei 071 entfällt. 070 wird idempotent: Tabelle und Einstellungen werden nur angelegt, wenn sie fehlen. Auf Test und lokal setzt der Betreiber danach `migrations.version` auf 69 und führt `console migrate` aus. ADR 0005 bekommt dazu einen Absatz.
- **Model:** ein kleines Fork-Model für `appointment_contacts` (finden pro Termin, für mehrere Termine, speichern, löschen).
- **Regel-Modul (Seam):** eine Fork-Library mit reinen Funktionen ohne Datenbank, nach dem Muster der Booking-Policy (0013). Sie entscheidet:
  - die **Kundenauflösung**: Neubuchung über die E-Mail (bestehender oder neuer Kunde). Umbuchung über den Kunden des Original-Termins, ohne E-Mail-Abgleich.
  - die **Datensatz-Änderung**: neuer Kunde mit allen Feldern. Bestehender Kunde nur mit nicht leeren Feldern. Eine geänderte E-Mail bei der Umbuchung nur, wenn kein anderer Kunde sie hat. Sprache und Zeitzone wie bei Upstream.
  - die **Termin-Kontaktdaten** aus den Eingaben.
  - im Backend die **geänderten Felder** gegenüber den gespeicherten Termin-Kontaktdaten. Nur diese gehen in den Datensatz.
  - den **Google-Kontaktblock** (erzeugen und aus einer Beschreibung entfernen).
- **Online-Buchung und Umbuchung:** Der Controller sammelt die Daten aus der Datenbank (bestehender Kunde, Kunde mit der neuen E-Mail, Original-Termin) und ruft das Regel-Modul auf. Termin, Termin-Kontaktdaten und Datensatz-Änderung speichert er in einer Transaktion, den Termin zuerst. Sync, Mails und Webhook laufen erst nach dem Commit. Die leeren Defaults für fehlende Felder entfallen, wo sie Daten löschen würden. Ein neuer Kunde wird weiterhin mit allen Pflichtfeldern angelegt.
- **Backend-Kalender:**
  - Beim Anlegen eines Termins mit Kunde entstehen Termin-Kontaktdaten aus dem Dialog.
  - Beim Bearbeiten werden sie nur geändert, wenn das Personal Kontaktfelder ändert. Die geänderten Felder übernimmt auch der Datensatz.
  - Der Dialog zeigt einen Kundenblock. Er ist mit den Termin-Kontaktdaten vorbelegt, ohne sie mit dem Datensatz.
  - Die Kundenauswahl und die Pflege weiterer Stammdaten (Adresse usw.) bleiben, wie sie sind.
- **Anzeige:** Kalender-Kachel, Termin-Dialog und Salon-Mail zeigen die Termin-Kontaktdaten. Fehlen sie, zeigen sie den Datensatz wie bisher. Die Kundenmail geht an die E-Mail des Datensatzes, ihr Inhalt bleibt unverändert.
- **Google:**
  - Die Beschreibung des Google-Termins beginnt mit einem klar abgegrenzten Kontaktblock (Name, Telefon im Format `tel:`-tauglich, E-Mail), danach folgen die Notizen.
  - Liest der Sync Google-Termine zurück, wird der Block vor dem Vergleich mit den lokalen Notizen entfernt. Die Notizen bleiben so frei vom Block, und der Vergleich meldet keine Scheinänderung.
  - Den Teilnehmer bildet der Sync aus den Termin-Kontaktdaten.
- **Altbestand:** kein Backfill, alte Termine zeigen weiter den Datensatz.
- **Kundenmail-Adresse:** Sie bleibt die Adresse des Datensatzes. Damit erreicht eine bei der Umbuchung geänderte E-Mail alle Termine der Kundin.
- **Upstream-Diff (ADR 0005):** Die Logik steht im Regel-Modul und im Fork-Model. In Upstream-Dateien gibt es nur Aufrufe und Anzeigeanpassungen.
- **ADR 0008** hält fest: Termin-Kontaktdaten am Termin, Kundendatensatz als letzter Stand, Begründung (Bestellungs-Muster, Google als Hauptarbeitsmittel). Verworfen wurden das Überschreiben (Upstream), ein Datensatz, der nur zuordnet, eine JSON-Spalte und neue Spalten in `appointments`.

## Testing Decisions

- Gute Tests prüfen beobachtbares Verhalten: die Entscheidung des Regel-Moduls für gegebene Eingaben und den gespeicherten Zustand nach einer echten HTTP-Buchung. Interne Aufrufe werden nicht geprüft.
- **Unit-Tests (Seam 1)** für das Regel-Modul, nach dem Vorbild der Booking-Policy-Tests:
  - neuer Kunde, bestehender Kunde, leeres Feld löscht nicht
  - Umbuchung behält den Kunden
  - E-Mail-Wechsel frei bzw. belegt
  - Backend-Diff
  - Kontaktblock erzeugen, entfernen und Rundlauf
  - Notizen ohne Block bleiben unverändert
- **HTTP-Smoke (Seam 2)**, neues Skript nach dem Muster des Manage-Link-Smokes mit den gemeinsamen Fixtures, über die öffentlichen Buchungs-Endpunkte:
  - Buchung mit der E-Mail einer anderen Person: Die Termin-Kontaktdaten sind die eingegebenen. Nicht leere Felder sind im Datensatz übernommen, leere sind erhalten.
  - Umbuchung mit der E-Mail eines anderen Kunden: Der Termin bleibt beim Original-Kunden, der andere Kunde ist unverändert, der Datensatz behält seine E-Mail.
  - Umbuchung mit einer freien neuen E-Mail: Der Datensatz übernimmt sie.
- „Tests go red“: Unit-Tests und Smoke laufen vor der Umsetzung gegen das alte Verhalten rot. Der Beleg steht im Ticket.
- Die Migration prüft der bestehende Migrations-Validator.
- Backend-Anzeige, Kachel und Google-Termin prüft der Nutzer manuell. Es gibt keinen Browser-Test.

## Out of Scope

- E-Mail-Bestätigung und Vormerkung (0018).
- Termine über die REST-API v1: Sie bekommen keine Termin-Kontaktdaten, die Anzeige fällt auf den Datensatz zurück. Das ist eine Folgeidee.
- CalDAV-Sync: unverändert, er wird bei MeBeauty nicht genutzt.
- Ein Backfill alter Termine.
- Die Zusammenführung doppelter Kunden.
- Eine Mail an die gespeicherte Adresse „Daten übernehmen?“ (durch 0018 überholt).

## Further Notes

- **Bekanntes Restrisiko:** Solange die E-Mail-Bestätigung aus ist, kann jemand mit fremder E-Mail den aktuellen Datensatz eines anderen Kunden ändern (nicht leere Felder), und die Bestätigung geht an die fremde Adresse. Die Termine behalten ihre eigenen Kontaktdaten. 0018 schließt das, wenn die Einstellung an ist.
- Herkunft: Restrisiko aus 0009, entschieden per Grilling am 2026-10-11 bis 2026-10-13.
- Upstream (Unreleased, kein 1.6.1/1.6.2) hat nichts zur Kundenzuordnung. Ein verwandter CalDAV-Fix verhindert, dass ein generierter Kontaktblock zurück in die Notizen gelangt.
- Bezug: ADR 0003, ADR 0005, ADR 0008 (neu), Glossar „Manage-Link“, „Umbuchung“, „Termin-Kontaktdaten“.
