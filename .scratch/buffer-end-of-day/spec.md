# Pufferzeit am Arbeitsende nur vor dem echten Arbeitsende

Status: ready-for-agent

## Problem Statement

ADR 0002 und das Glossar legen fest: Die Pufferzeit gilt nach echten Terminen, **nie davor**. Optional gilt sie zusätzlich vor dem **Arbeitsende**. Die Verfügbarkeitsberechnung zieht den Tagesende-Puffer aber vom Ende **jeder** freien Zeitspanne ab. Freie Spannen enden nicht nur am Arbeitsende, sondern auch dort, wo ein Termin beginnt, eine Pause anfängt oder eine Unavailability bzw. Blocked Period liegt. Mit der Standardeinstellung (Puffer vor Arbeitsende an) gibt es deshalb faktisch Puffer **vor** Terminen, vor Pausen und vor Unavailabilities.

Für den Salon bedeutet das: Lücken direkt vor einem bestehenden Termin oder vor der Mittagspause werden nicht angeboten, obwohl sie laut Regel buchbar sind. Welche Slots ein Kunde sieht, hängt außerdem von der Reihenfolge der Buchungen ab. Bucht jemand zuerst den späteren Termin, fehlt danach der Slot davor. Im umgekehrten Fall bleibt er erhalten.

Das betrifft beide Berechnungswege: Dienstleister mit einem Teilnehmer pro Slot und Services mit mehreren Teilnehmern.

## Solution

Der Tagesende-Puffer wirkt nur noch auf die freie Zeitspanne, deren Ende mit dem **Arbeitsende** des Dienstleisters an diesem Tag zusammenfällt. Arbeitsende ist das Ende des effektiven Tagesplans, also inklusive einer Arbeitsplan-Ausnahme für den Tag (Glossar „Arbeitsende“). Endet eine Spanne an einem Terminbeginn, einer Pause, einer Unavailability oder einer Blocked Period, gilt dort kein Puffer. Ein Slot darf dann bündig davor enden.

Der Puffer **nach** Terminen bleibt unverändert.

## User Stories

1. Als Kunde will ich einen Slot buchen können, der direkt vor einem bestehenden Termin endet, damit vorhandene Lücken genutzt werden.
2. Als Kunde will ich einen Slot buchen können, der direkt vor der Pause des Dienstleisters endet, damit der Vormittag voll ausgelastet werden kann.
3. Als Salonbetreiberin will ich, dass vor Unavailabilities und Blocked Periods kein Puffer gilt, wie in ADR 0002 festgelegt.
4. Als Salonbetreiberin will ich, dass bei aktivierter Option der letzte Slot des Tages so endet, dass bis zum Arbeitsende die Pufferzeit frei bleibt, damit ich nach dem letzten Kunden aufräumen kann.
5. Als Salonbetreiberin will ich, dass bei deaktivierter Option der letzte Slot bis zum Arbeitsende reichen darf.
6. Als Salonbetreiberin will ich, dass bei einer Arbeitsplan-Ausnahme (z. B. Donnerstag bis 20:00 statt 18:00) der Puffer vor 20:00 gilt und nicht vor 18:00.
7. Als Salonbetreiberin will ich, dass der Puffer je Dienstleister gegen dessen eigenes Arbeitsende gerechnet wird, damit unterschiedliche Arbeitszeiten korrekt berücksichtigt werden.
8. Als Kunde will ich dieselben freien Slots sehen, egal in welcher Reihenfolge andere Kunden zuvor gebucht haben, damit die Verfügbarkeit nachvollziehbar ist.
9. Als Salonbetreiberin will ich, dass sich der Puffer nach einem Termin nicht ändert, damit die Aufräumzeit nach Kunden erhalten bleibt.
10. Als Salonbetreiberin will ich, dass Services mit mehreren Teilnehmern dieselbe Regel befolgen wie Einzeltermine.
11. Als Salonbetreiberin will ich, dass eine Unavailability, die bis zum Arbeitsende reicht, keinen zusätzlichen Puffer davor erzeugt, damit freie Zeit vor einer frühen Schließung nicht verloren geht.
12. Als Entwickler will ich die Slot-Erzeugung ohne Datenbank testen können, damit Pufferregeln schnell und deterministisch geprüft werden.

## Implementation Decisions

- Die Slot-Erzeugung aus freien Zeitspannen wird eine reine, öffentlich testbare Funktion der Verfügbarkeits-Library. Eingaben: Datum, freie Spannen, Service-Dauer, Slot-Intervall, Tagesende-Puffer in Minuten, Arbeitsende des Tages. Ausgabe: Liste der Slot-Startzeiten. Sie liest keine Settings und keine Datenbank. Vorbild ist die bereits statische Überlappungszählung.
- Der Tagesende-Puffer wird nur auf eine Spanne angewendet, deren Ende gleich dem Arbeitsende ist.
- Das Arbeitsende wird aus dem effektiven Tagesplan bestimmt, der für die Periodenberechnung ohnehin ermittelt wird (Arbeitsplan oder Ausnahme des Tages). Es wird vom Aufrufer übergeben, nicht neu geladen.
- Der Mehrteilnehmer-Pfad bekommt dieselbe Regel: Die Schleifenbedingung berücksichtigt den Tagesende-Puffer nur für die Spanne, die am Arbeitsende endet. Die bestehende Verschiebung des Konfliktfensters um den Puffer nach Terminen bleibt unverändert (laut Spec `booking-deadline-buffer-timezone` gewollt).
- Die Behandlung des Puffers nach Terminen bleibt unverändert.
- Keine Schemaänderung, keine neuen Settings.

## Testing Decisions

- Unit-Tests gegen die neue reine Slot-Funktion (Vorbild: bestehende Verfügbarkeits-Tests zur Überlappungszählung). Szenarien mit Dauer 60, Intervall 15, Puffer 15, Arbeitsende 18:00:
  - Spanne endet am Arbeitsende, Option an → letzter Slot 16:45.
  - Spanne endet am Arbeitsende, Option aus → letzter Slot 17:00.
  - Spanne endet an einem Terminbeginn (z. B. 12:00) → letzter Slot 11:00.
  - Spanne endet am Pausenbeginn → Slot bündig vor der Pause.
  - Arbeitsplan-Ausnahme bis 20:00 → Puffer vor 20:00, nicht vor 18:00.
  - Spanne zu kurz für Dauer plus Puffer am Arbeitsende → keine Slots.
- Gute Tests prüfen nur die erzeugten Slots, nicht die interne Schleife.
- Der Mehrteilnehmer-Pfad hängt an Datenbankabfragen und wird manuell geprüft: Ein Slot vor einem Termin desselben Dienstleisters wird angeboten.
- Manuelle Gegenprobe: Zwei Termine in beiden Buchungsreihenfolgen anlegen → identische freie Slots.

## Out of Scope

- Pufferzeit pro Service (in ADR 0002 verworfen).
- Puffer nach Unavailabilities oder Blocked Periods (in ADR 0002 verworfen).
- Performance der Datenbankabfragen pro Slot im Mehrteilnehmer-Pfad.
- Auslagern der Fristlogik (Spec `booking-policy-extraction`).

## Further Notes

- Bezug: ADR 0002, Glossar „Pufferzeit“ und „Arbeitsende“. Der Code wird an die ADR angepasst, nicht umgekehrt.
- Spec `booking-policy-extraction` verschiebt später das Lesen der Settings. Diese Spec ist davon unabhängig, weil die neue Slot-Funktion keine Settings liest.
