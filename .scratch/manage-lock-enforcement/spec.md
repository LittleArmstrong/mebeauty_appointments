# Umbuchungs-/Stornierungsfrist serverseitig durchsetzen

Status: ready-for-agent

## Problem Statement

Die Umbuchungs-/Stornierungsfrist (Manage-Frist) wird bei einer Umbuchung nur geprüft, wenn der Kunde den Manage-Link **öffnet**. Beim **Absenden** der Umbuchung prüft der Server die Frist nicht mehr. Wer die Seite vor Fristablauf öffnet und erst danach absendet, bucht trotz Sperre um. Wer die Anfrage manipuliert, umgeht die Frist komplett.

Schwerer wiegt: Der Buchungs-Endpunkt übernimmt eine mitgeschickte Termin-ID ungeprüft, auch ohne Manage-Modus. Wer eine Termin-ID kennt oder errät, kann mit einer manipulierten Anfrage einen fremden Termin überschreiben (Zeitpunkt, Service, Dienstleister, verknüpfter Kunde). Die ID ist an nichts gebunden, insbesondere nicht an den Hash des Manage-Links.

Die Stornierung ist nicht betroffen: Sie lädt den Termin beim Absenden über den Hash und prüft die Frist dabei.

## Solution

Der Buchungs-Endpunkt behandelt eine Anfrage nur dann als Umbuchung, wenn sie den Hash des Manage-Links mitbringt und dieser Hash zu genau dem Termin gehört, dessen ID übermittelt wird. In jedem anderen Fall wird eine mitgeschickte Termin-ID verworfen; die Anfrage ist dann eine Neubuchung.

Bei einer gültigen Umbuchung lädt der Server den ursprünglichen Termin aus der Datenbank und prüft die Manage-Frist gegen dessen **gespeicherten** Beginn in der Zeitzone des Dienstleisters. Ist sie abgelaufen, wird die Umbuchung mit derselben Sperrmeldung abgelehnt, die der Kunde beim Öffnen des Manage-Links sehen würde. Der neue Zeitpunkt unterliegt weiterhin der Buchungsfrist (Status quo, siehe Glossar „Umbuchung“).

## User Stories

1. Als Salonbetreiberin will ich, dass eine Umbuchung nach Ablauf der Manage-Frist abgelehnt wird, auch wenn der Kunde die Seite vorher geöffnet hat, damit die Frist verlässlich gilt.
2. Als Salonbetreiberin will ich, dass maßgeblich der in der Datenbank gespeicherte Beginn des Original-Termins ist, damit manipulierte Startzeiten in der Anfrage die Frist nicht aushebeln.
3. Als Salonbetreiberin will ich, dass die Frist in der Zeitzone des Dienstleisters berechnet wird, so wie beim Öffnen des Manage-Links, damit beide Prüfungen dasselbe Ergebnis liefern.
4. Als Kunde will ich, der die Seite rechtzeitig geöffnet, aber zu spät abgeschickt hat, eine verständliche Meldung sehen, warum die Umbuchung nicht mehr möglich ist, damit ich weiß, dass ich den Salon kontaktieren muss.
5. Als Kunde will ich innerhalb der Frist unverändert umbuchen können, damit sich für reguläre Umbuchungen nichts ändert.
6. Als Kunde will ich, dass mein Termin nicht von Dritten überschrieben werden kann, die nur seine ID kennen, damit meine Buchung sicher ist.
7. Als Salonbetreiberin will ich, dass eine Anfrage ohne gültigen Manage-Hash immer als Neubuchung behandelt wird, damit kein bestehender Termin versehentlich oder böswillig verändert wird.
8. Als Salonbetreiberin will ich, dass ein Hash, der zu einem anderen Termin gehört als die übermittelte ID, zur Ablehnung führt, damit Hash und ID nicht kombiniert werden können.
9. Als Salonbetreiberin will ich, dass ein unbekannter oder bereits gelöschter Termin bei einer Umbuchung zu einer Fehlermeldung führt und nicht zu einem neuen Termin, damit Kunden keine Doppelbuchungen erzeugen.
10. Als Salonbetreiberin will ich, dass das Personal im Backend weiterhin frei umbuchen kann, damit die Frist nur kundenseitig wirkt (ADR 0003).
11. Als Kunde will ich bei einer Umbuchung auf einen neuen Zeitpunkt weiterhin nur Slots wählen können, die die Buchungsfrist erfüllen, damit sich Umbuchung und Neubuchung beim Zielslot gleich verhalten.
12. Als Entwickler will ich, dass Sperrprüfung beim Öffnen und beim Absenden dieselbe Fristlogik verwenden, damit beide Pfade nicht auseinanderlaufen.

## Implementation Decisions

- Der Booking-Flow schickt beim Absenden im Manage-Modus den Hash des Termins mit, der beim Öffnen des Manage-Links ohnehin in den Seitendaten liegt.
- Der Buchungs-Endpunkt bestimmt den Manage-Modus nicht mehr allein aus dem Client-Flag: Umbuchung = Flag gesetzt **und** Hash vorhanden **und** Termin per Hash gefunden **und** dessen ID gleich der übermittelten ID.
- Ohne gültige Umbuchung wird die Termin-ID aus den Anfragedaten entfernt, bevor gespeichert wird (Neubuchung).
- Flag gesetzt, aber Hash fehlt oder passt nicht zur ID → Ablehnung mit generischer Fehlermeldung (kein Hinweis, ob die ID existiert).
- Die Fristprüfung nutzt die vorhandene Sperrlogik (Manage-Variante) mit gespeichertem Beginn und Zeitzone des Dienstleisters; die Sperrmeldung wird wie beim Öffnen aus Meldungstext und Grenzwert gebildet.
- Die Prüfung erfolgt vor Captcha-unabhängigen Seiteneffekten (Kunde speichern, Consents, Termin speichern, Benachrichtigungen).
- Die Stornierung bleibt unverändert.
- Keine Schemaänderung.

## Testing Decisions

- Gute Tests prüfen das von außen sichtbare Verhalten (Antwort des Endpunkts, gespeicherter Termin), nicht interne Aufrufe.
- Der Controller-Pfad wird über ein neues HTTP-Smoke-Skript nach dem Vorbild der bestehenden Smoke-Skripte geprüft:
  - POST mit Termin-ID ohne Manage-Modus → es entsteht ein neuer Termin, der bestehende bleibt unverändert.
  - POST im Manage-Modus mit falschem Hash → abgelehnt.
  - POST im Manage-Modus für einen Termin mit abgelaufener Frist → abgelehnt mit Sperrmeldung.
  - POST im Manage-Modus innerhalb der Frist → Umbuchung erfolgreich.
- Die Fristberechnung selbst bekommt Unit-Tests erst mit der Extraktion der Fristlogik (Spec `booking-policy-extraction`).

## Out of Scope

- Weitere Felder, die Kunden heute mitschicken dürfen (Status, Unavailability-Flag, Kunden-ID); sie werden bereits serverseitig überschrieben. Eine Bereinigung der erlaubten Felder ist ein eigenes Thema.
- Änderungen an der Stornierung oder an der Backend-Bearbeitung.
- Extraktion der Fristlogik in eine eigene Library.

## Further Notes

- Fehler im Upstream (Easy!Appointments 1.6.0): Die Termin-ID ist dort ebenfalls frei übergebbar. Der Fix sollte möglichst lokal bleiben, damit spätere Upstream-Merges wenig Konflikte erzeugen (ADR 0005).
- Bezug: ADR 0003 (Manage-Frist wirkt nur kundenseitig), Glossar „Umbuchung“.
