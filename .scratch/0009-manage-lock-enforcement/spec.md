# Manage-Link serverseitig durchsetzen: Berechtigung und Umbuchungs-/Stornierungsfrist

Status: resolved
Created: 2026-10-11
Closed: 2026-10-11
Priority: P1
Depends-on: –

## Problem Statement

Wer einen bestehenden Termin verwalten darf, entscheidet der **Manage-Link** mit dem Termin-Hash. Der Buchungs-Endpunkt prüft diese Berechtigung beim Absenden aber gar nicht:

- **Termin überschreiben:** Eine Buchungsanfrage mit einer beliebigen Termin-ID ändert diesen Termin (Zeit, Service, Dienstleister, Kunde), auch ohne Manage-Modus und ohne Hash. Termin-IDs sind fortlaufende Zahlen und leicht zu erraten.
- **Kunden überschreiben:** Eine mitgeschickte Kunden-ID mit einer noch unbekannten E-Mail-Adresse überschreibt die Stammdaten dieses fremden Kunden.
- **Verfügbarkeit mit fremdem Ausschluss:** Die Prüfung, ob der gewählte Slot noch frei ist, liest die Termin-ID erneut ungeprüft aus der Anfrage und rechnet den so benannten Termin heraus.
- **Frist nur beim Öffnen:** Die Umbuchungs-/Stornierungsfrist (Manage-Frist) wird nur beim Öffnen des Manage-Links geprüft. Wer die Seite vor Fristablauf öffnet und danach absendet, bucht trotz Sperre um.
- **Verfügbarkeitsabfragen:** Die öffentlichen Abfragen für freie Zeiten und gesperrte Tage akzeptieren im Manage-Modus eine beliebige Termin-ID als Ausschluss. Ein fremder Termin erscheint dadurch als frei. Wer IDs durchprobiert, erfährt außerdem, wann welcher Dienstleister einen Termin mit welcher ID hat.

Die Stornierung ist nicht betroffen. Sie lädt den Termin beim Absenden über den Hash und prüft dabei die Frist.

## Solution

Der vorhandene Termin-Hash (Bestandteil jedes Manage-Links) wird beim Absenden und bei den Verfügbarkeitsabfragen als Berechtigung verwendet. Es wird kein neues Token-System eingeführt.

- **Neubuchung** (keine Termin-ID, kein Manage-Modus): Verhalten unverändert. Eine mitgeschickte Kunden-ID wird ignoriert, die Zuordnung zu bestehenden Kunden erfolgt wie bisher über die E-Mail-Adresse.
- **Umbuchung** (Manage-Modus oder Termin-ID vorhanden): Nur mit gültigem Hash, der zu genau dieser Termin-ID gehört. Termin- und Kunden-ID übernimmt der Server aus dem gespeicherten Termin. Fehlt der Hash oder passt er nicht, wird die Anfrage **abgelehnt**. Sie wird nicht stillschweigend in eine Neubuchung umgewandelt.
- Bei einer gültigen Umbuchung prüft der Server die Manage-Frist erneut gegen den **gespeicherten** Beginn des Original-Termins. Der neue Zeitpunkt unterliegt weiterhin der Buchungsfrist (Glossar „Umbuchung“).
- Die Verfügbarkeitsabfragen schließen einen Termin nur aus, wenn der Hash mitgeschickt wird. Der Server ermittelt die ID dann selbst.

## User Stories

1. Als Kunde will ich, dass mein Termin nur über meinen Manage-Link verändert werden kann, damit Dritte ihn nicht über eine erratene ID verschieben.
2. Als Kunde will ich, dass meine Stammdaten nicht durch fremde Buchungen überschrieben werden, die meine Kunden-ID mitschicken.
3. Als Kunde ohne Termin will ich wie bisher neu buchen können, ohne dass sich für mich etwas ändert.
4. Als Kunde mit Manage-Link will ich innerhalb der Frist wie bisher umbuchen können.
5. Als Salonbetreiberin will ich, dass eine Umbuchung mit fehlendem oder falschem Hash abgelehnt wird, statt unbemerkt als zusätzlicher Termin gespeichert zu werden, damit keine Doppeltermine entstehen.
6. Als Salonbetreiberin will ich, dass ein Hash, der zu einem anderen Termin gehört als die übermittelte ID, zur Ablehnung führt.
7. Als Salonbetreiberin will ich, dass eine Umbuchung nach Ablauf der Manage-Frist abgelehnt wird, auch wenn die Seite vorher geöffnet wurde.
8. Als Salonbetreiberin will ich, dass für die Frist der gespeicherte Beginn des Original-Termins zählt, nicht der Wert aus der Anfrage.
9. Als Salonbetreiberin will ich, dass die Frist in der Zeitzone des ursprünglichen Dienstleisters berechnet wird, so wie beim Öffnen des Links.
10. Als Kunde will ich bei einer abgelehnten Umbuchung eine verständliche Meldung sehen (Sperre mit Grenzwert bzw. ungültiger Link).
11. Als Kunde will ich beim Umbuchen nur Zielzeiten sehen und buchen können, die die Buchungsfrist erfüllen.
12. Als Salonbetreiberin will ich, dass bei einer abgelehnten Anfrage nichts gespeichert wird: kein Kunde, keine Einwilligung, kein Termin, keine Benachrichtigung, kein Kalender-Sync, kein Webhook.
13. Als Salonbetreiberin will ich, dass die Abfrage freier Zeiten fremde Termine nicht als frei anzeigt, damit niemand über die Abfrage Terminbelegungen ausforschen kann.
14. Als Kunde mit Manage-Link will ich meinen eigenen alten Zeitpunkt beim Umbuchen weiterhin als frei sehen.
15. Als Salonbetreiberin will ich, dass das Personal im Backend weiterhin frei umbuchen kann (ADR 0003).

## Implementation Decisions

- **Berechtigung = Termin-Hash.** Er wird beim Anlegen eines Termins erzeugt, steht im Manage-Link der E-Mail und wird der Buchungsseite im Manage-Modus bereits mit den Termindaten übergeben. Der Booking-Flow schickt ihn zusätzlich beim Absenden und bei den Verfügbarkeitsabfragen mit.
- Der Hash wird wie bei der Stornierung auf das Format geprüft (nur alphanumerische Zeichen) und über die vorhandene Termin-Abfrage per Hash aufgelöst.
- Den Datenbank-Termin aus dem Hash verwenden Register-Pfad, Verfügbarkeitsprüfung und Fristprüfung gemeinsam. Die Verfügbarkeitsprüfung beim Absenden bekommt den bereinigten Termin übergeben, statt die Anfrage erneut zu lesen.
- Ohne gültige Umbuchung werden Termin-ID und Kunden-ID aus den Anfragedaten entfernt, bevor gespeichert wird.
- Fehlerantworten nennen nicht, ob eine ID existiert (gleiche Meldung für „Hash fehlt“, „Hash unbekannt“ und „Hash passt nicht“).
- Die Fristprüfung nutzt die vorhandene Sperrlogik in der Manage-Variante. Mit der Spec `0013-booking-policy-extraction` wandert sie später in die Booking-Policy.
- Die Verfügbarkeitsabfragen (freie Zeiten, gesperrte Tage) erwarten im Manage-Modus den Hash statt der Termin-ID. Ohne gültigen Hash rechnen sie ohne Ausschluss.
- Keine Schemaänderung.
- **Bewusst beibehalten (Upstream):**
  - Termin-Hashes bleiben 12 alphanumerische Zeichen (≈ 2⁷⁰ Möglichkeiten). Kollisionen und Erraten sind bei Salon-Volumen praktisch ausgeschlossen. Eine Umstellung auf kryptografischen Zufall wurde erwogen und zurückgestellt.
  - Die Verfügbarkeitsabfragen bleiben POST-Anfragen. Damit wird nicht gecacht, und der Hash erscheint nicht in URLs oder Server-Logs.
  - Die veraltete Weiterleitungsroute für Links aus Versionen vor 1.5 bleibt unverändert. Sie antwortet heute auf jede Eingabe mit 400 bzw. 404 und greift nicht auf Daten zu.

## Testing Decisions

- Gute Tests prüfen von außen sichtbares Verhalten: die Antwort des Endpunkts und den Datenbankzustand danach, nicht interne Aufrufe.
- Neues HTTP-Smoke-Skript nach dem Muster der bestehenden Smoke-Skripte (curl gegen die laufende Anwendung, Testdaten per Datenbank vorbereitet):
  - Neubuchung ohne ID → gespeichert.
  - Fremde Termin-ID ohne Hash → abgelehnt, fremder Termin unverändert.
  - Gültiger Hash, aber fremde ID → abgelehnt.
  - Fremde Kunden-ID bei Neubuchung → fremder Kunde unverändert.
  - Gültige Umbuchung innerhalb der Frist → gespeichert.
  - Umbuchung nach Ablauf der Manage-Frist → abgelehnt mit Sperrmeldung.
  - Zielzeit nach Ablauf der Buchungsfrist → abgelehnt.
  - Verfügbarkeitsabfrage mit fremder Termin-ID und ohne Hash → fremder Termin bleibt belegt.
- Unit-Tests der Fristberechnung folgen mit der Booking-Policy (Spec `0013-booking-policy-extraction`).

## Out of Scope

- Änderungen an Stornierung und Backend-Bearbeitung.
- Weitere frei übergebbare Felder (Status, Unavailability-Flag), die der Server heute schon überschreibt.
- Neues Token-System oder längere Hashes.
- Umstellung der Verfügbarkeitsabfragen auf GET.
- Ausschluss des eigenen Termins bei „beliebiger Dienstleister“ (Upstream-Verhalten).

## Further Notes

- Die Schwächen stammen aus Easy!Appointments 1.6.0. Die Änderungen bleiben lokal im Buchungs-Controller und im Booking-Flow (ADR 0005, kleiner Upstream-Diff).
- Bezug: ADR 0003, Glossar „Manage-Link“, „Umbuchung“, „Umbuchungs-/Stornierungsfrist“.
