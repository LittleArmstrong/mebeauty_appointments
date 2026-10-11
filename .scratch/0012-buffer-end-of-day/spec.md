# Tagesende-Puffer nur vor dem effektiven Arbeitsende

Status: ready-for-agent
Created: 2026-10-11
Closed: –
Priority: P1
Depends-on: –

## Problem Statement

Nach jedem echten Termin gibt es bereits eine **Pufferzeit**, in der kein neuer Termin beginnen darf. Die optionale Einstellung „Puffer vor Arbeitsende“ soll festlegen, ob dieser Puffer zusätzlich vor dem Arbeitsende eingehalten werden muss: Der letzte Termin des Tages endet dann so früh, dass bis Feierabend noch Aufräumzeit bleibt (ADR 0002).

Die Verfügbarkeitsberechnung zieht diesen Tagesende-Puffer aber vom Ende **jeder** freien Zeitspanne ab. Freie Spannen enden nicht nur am Arbeitsende, sondern auch an Pausen, an Terminbeginnen, an Unavailabilities und an Blocked Periods. Mit der Standardeinstellung (an) wirkt der Puffer deshalb auch vor Pausen, vor Unavailabilities und vor Terminen. Das widerspricht ADR 0002 („nie vor einem Termin“, nicht bei Unavailabilities/Blocked Periods).

Für den Salon heißt das: Lücken vor der Mittagspause, vor einem bestehenden Termin oder vor einer manuellen Sperre werden nicht angeboten, obwohl sie buchbar sind. Betroffen sind Einzeltermine und Services mit mehreren Teilnehmern.

## Solution

Der Tagesende-Puffer wirkt nur noch auf die freie Spanne, die am **effektiven Arbeitsende** des Dienstleisters endet (Glossar „Arbeitsende“: Ende des Tagesplans, bei einer Arbeitsplan-Ausnahme deren Ende).

- Option an: `Terminende + Pufferzeit ≤ effektives Arbeitsende` (Gleichheit erlaubt)
- Option aus: `Terminende ≤ effektives Arbeitsende`
- Vor Pausen, bestehenden Terminen, Unavailabilities und Blocked Periods gilt nur `Terminende ≤ Beginn der Unterbrechung`, ohne Puffer.
- Der Puffer **nach** Terminen bleibt unverändert. Er verschiebt den Beginn freier Spannen, der Tagesende-Puffer betrifft nur deren Ende. Damit wird der Puffer nicht doppelt angewendet.

## User Stories

1. Als Kunde will ich einen Slot buchen können, der bündig vor der Pause des Dienstleisters endet.
2. Als Kunde will ich einen Slot buchen können, der bündig vor einem bestehenden Termin endet (der Puffer gilt nur nach Terminen, ADR 0002).
3. Als Salonbetreiberin will ich, dass vor Unavailabilities und Blocked Periods kein Puffer gilt, auch wenn sie bis zum Arbeitsende reichen.
4. Als Salonbetreiberin will ich, dass bei aktivierter Option der letzte Termin spätestens so endet, dass bis zum Arbeitsende die Pufferzeit frei bleibt; ein Ende genau „Arbeitsende minus Puffer“ ist erlaubt.
5. Als Salonbetreiberin will ich, dass bei deaktivierter Option der letzte Termin bis zum Arbeitsende reichen darf.
6. Als Salonbetreiberin will ich, dass bei einer Arbeitsplan-Ausnahme (länger oder verkürzt) deren Ende als Arbeitsende gilt.
7. Als Salonbetreiberin will ich, dass bei einem Tag mit Pausen nur das letzte Teilintervall ein Arbeitsende hat.
8. Als Salonbetreiberin will ich, dass jeder Dienstleister gegen sein eigenes Arbeitsende gerechnet wird.
9. Als Salonbetreiberin will ich, dass sich der Puffer nach Terminen nicht ändert und nicht doppelt wirkt.
10. Als Salonbetreiberin will ich, dass Services mit mehreren Teilnehmern dieselbe Regel befolgen.
11. Als Entwickler will ich die Slot-Erzeugung ohne Datenbank testen können.

## Implementation Decisions

- Die Slot-Erzeugung aus freien Spannen wird eine reine, öffentlich testbare Funktion der Verfügbarkeits-Library (Vorbild: die bereits statische Überlappungszählung). Eingaben: Datum, freie Spannen, Service-Dauer, Slot-Intervall, Pufferzeit, Option „Puffer vor Arbeitsende“, effektives Arbeitsende. Ausgabe: Slot-Startzeiten. Keine Settings- oder Datenbankzugriffe.
- Das effektive Arbeitsende ist das Ende des Tagesplans, den die Periodenberechnung ohnehin ermittelt (Wochentagsplan oder Arbeitsplan-Ausnahme). Der Aufrufer übergibt es, es wird nicht neu geladen. Easy!Appointments kennt pro Tag genau ein Arbeitsintervall; Teilintervalle entstehen nur durch Pausen.
- Erkennung: Eine Spanne „endet am Arbeitsende“, wenn ihr Ende gleich dem effektiven Arbeitsende ist. Endet eine Unavailability, Blocked Period oder ein Termin erst danach, endet die Spanne vorher und bekommt keinen Puffer.
- Der Mehrteilnehmer-Pfad nutzt dieselbe Regel (möglichst dieselbe Prüffunktion). Das Vorziehen des Konfliktfensters um den Puffer nach Terminen bleibt unverändert.
- Die Ergebnisse dürfen von der Buchungsreihenfolge abhängen. Das ist gewollt: Ein neuer Termin darf bündig vor einem bestehenden enden, umgekehrt nicht (Spec `0003-booking-deadline-buffer-timezone`, „Puffer vor Terminen“ ist Out of Scope).
- Keine Schemaänderung, keine neuen Einstellungen.

## Testing Decisions

- Unit-Tests gegen die reine Slot-Funktion. Szenarien mit Dauer 60, Intervall 15, Puffer 15:
  - Arbeitsende 18:00, Option an → letzter Slot 16:45 (16:45 + 60 + 15 = 18:00, Gleichheit erlaubt)
  - Option aus → letzter Slot 17:00
  - Verkürzter Tag per Ausnahme bis 14:00, Option an → letzter Slot 12:45
  - Pause 12:00–13:00 → Slot 11:00 wird angeboten
  - Bestehender Termin ab 15:00 → Slot 14:00 wird angeboten
  - Blocked Period 17:00–18:00 → Slot 16:00 wird angeboten
  - Unavailability bis zum Arbeitsende → kein Puffer davor
  - Bestehender Termin bis 10:00 → erster Slot 10:15 (nur Puffer nach dem Termin, nicht doppelt)
  - Spanne kürzer als Dauer + Puffer am Arbeitsende → keine Slots
- Gute Tests prüfen nur die erzeugten Slots, nicht die interne Schleife.
- Der Mehrteilnehmer-Pfad hängt an Datenbankabfragen und wird manuell geprüft (Ergebnis im Ticket dokumentiert).

## Out of Scope

- Puffer pro Service, Puffer vor Terminen, Puffer nach Unavailabilities/Blocked Periods (ADR 0002 verworfen).
- Performance der Datenbankabfragen pro Slot im Mehrteilnehmer-Pfad.
- Auslagerung der Fristlogik (Spec `0013-booking-policy-extraction`).

## Further Notes

- Bezug: ADR 0002, Glossar „Pufferzeit“ und „Arbeitsende“. Der Code wird an die ADR angepasst.
- Die neue Slot-Funktion liest keine Einstellungen; die spätere Zwischenspeicherung der Puffer-Einstellungen (Spec `0013-booking-policy-extraction`, Ticket 02) ist davon unabhängig.
