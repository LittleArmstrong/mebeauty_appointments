# Booking-Frist, Pufferzeit & Timezone-Anzeige

Status: resolved
Created: 2026-10-01
Closed: 2026-10-01
Priority: –
Depends-on: –

## Problem Statement

Der Salon arbeitet lokal und ausschließlich vor Ort; es gibt keine Online-Termine. Trotzdem hat die Buchungssteuerung (Easy!Appointments-Fork) drei Lücken:

1. Die Frist, bis zu der gebucht oder storniert werden darf, ist nur als **rollende Minuten-Vorlaufzeit** (`book_advance_timeout`) einstellbar. Es fehlt die Möglichkeit, eine **feste Uhrzeit** zu setzen (z. B. „Termine für morgen nur bis 18:00 Uhr buchbar“).
2. Zwischen zwei Terminen gibt es keine **Puffer-/Aufräumzeit**: Kunden können direkt im Anschluss an einen bestehenden Termin buchen, ohne dass Zeit für Aufräumen/Vorbereitung bleibt.
3. Die **Zeitzonen-Auswahl im Buchungs-Wizard** ist für einen rein lokalen Betrieb überflüssig und lenkt ab; sie lässt sich nicht ausblenden.

## Solution

Drei neue, in den Business Settings konfigurierbare Funktionen:

- **Frist-Modus (Entweder/Oder):** Der Betreiber wählt zwischen der bestehenden Minuten-Vorlaufzeit und einer **festen Uhrzeit mit einstellbarem Tagesabstand** (Frist für Termintag D = Tag D−N um HH:MM in Provider-Zeitzone). Nach Ablauf der Frist ist der gesamte Tag D gesperrt — für Neubuchungen **und** für Storno/Umbuchen über den Kunden-Link. Die Sperrseite nennt die konkrete Frist (Datum + Uhrzeit).
- **Pufferzeit:** Eine globale Pufferzeit in Minuten blockiert Slots direkt nach bestehenden Terminen (Service-Dauer + Puffer); optional gilt sie auch am Tagesende (letzter buchbarer Slot = Arbeitsende − Dauer − Puffer). 0 = deaktiviert.
- **Timezone-Anzeige:** Ein Select steuert, wo die Zeitzone im Buchungs-Wizard erscheint (`dropdown` / nur Bestätigungs-Frame / `hidden`); ein zweites Select legt fest, welche Zeitzone bei ausgeblendetem Dropdown verwendet wird (Firmen-Default oder Browser).

Backend-Kalender und Admin-Freiheiten bleiben unverändert: Frist und Puffer wirken nur kundenseitig (Booking-Flow + API).

## User Stories

1. Als Salonbetreiberin will ich zwischen „Minuten-Vorlaufzeit“ und „fester Uhrzeit“ als Frist-Modus wählen, so dass die Frist zu meinem Betriebsablauf passt.
2. Als Salonbetreiberin will ich eine feste Frist-Uhrzeit (z. B. 18:00) einstellen, so dass Termine für den Folgetag nur bis zu dieser Uhrzeit gebucht werden können.
3. Als Salonbetreiberin will ich den Tagesabstand einstellen (0 = gleicher Tag, 1 = Vortag, 2 = zwei Tage vorher …), so dass auch „heute nur bis 08:00 Uhr buchbar“ abbildbar ist.
4. Als Salonbetreiberin will ich, dass die Frist auch das Stornieren und Umbuchen bestehender Termine sperrt, so dass Kunden die Frist nicht durch späte Stornierung umgehen.
5. Als Salonbetreiberin will ich, dass die Frist nur kundenseitig gilt (Buchungsseite + API), so dass mein Personal im Backend weiterhin frei Termine anlegen und verschieben kann.
6. Als Salonbetreiberin will ich, dass Minuten- und Festzeit-Frist **nicht kombiniert** werden (Entweder/Oder), so dass das Verhalten eindeutig und vorhersehbar bleibt.
7. Als Salonbetreiberin will ich eine globale Pufferzeit (in Minuten) einstellen, so dass nach einem Termin nicht unmittelbar der nächste gebucht werden kann.
8. Als Salonbetreiberin will ich die Pufferzeit optional auch am Tagesende anwenden, so dass der letzte Slot die Aufräumzeit vor Feierabend berücksichtigt.
9. Als Salonbetreiberin will ich die Pufferzeit mit 0 deaktivieren können, so dass sich ohne Konfiguration nichts am bestehenden Verhalten ändert.
10. Als Salonbetreiberin will ich, dass der Puffer nur nach echten Terminen gilt und nicht nach „nicht verfügbar“-Blöcken oder gesperrten Zeiträumen, so dass manuelle Sperren nicht künstlich verlängert werden.
11. Als Salonbetreiberin will ich, dass der Puffer nur **nach** Terminen gilt (nie davor), so dass nur die Aufräumzeit, nicht auch Vorbereitungszeit abgebildet wird.
12. Als Salonbetreiberin will ich die Zeitzonen-Auswahl im Buchungs-Wizard ausblenden können, so dass der Wizard schlanker und lokal fokussiert wirkt.
13. Als Salonbetreiberin will ich die Zeitzonen-Zeile im Bestätigungs-Frame separat steuern (an/aus), so dass ich wählen kann, ob Kunden die angewandte Zeitzone sehen.
14. Als Salonbetreiberin will ich bei ausgeblendetem Dropdown wählen, ob die Firmen-Default- oder die Browser-Zeitzone des Kunden verwendet wird, so dass die angezeigten Zeiten vorhersehbar bleiben.
15. Als Salonbetreiberin will ich alle neuen Optionen in den Business Settings konfigurieren, so dass keine Code-Änderung nötig ist.
16. Als Salonbetreiberin will ich eine korrekte deutsche Beschriftung der bestehenden Minuten-Vorlaufzeit („Mindestvorlaufzeit“), so dass die Einstellung nicht mehr missverständlich benannt ist.
17. Als Kundin will ich im Buchungs-Wizard nur Zeiten sehen, die Frist und Puffer respektieren, so dass ich keine ungültigen Slots auswählen kann.
18. Als Kundin will ich Tage, deren Frist abgelaufen ist, im Datumswähler ausgegraut sehen, so dass ich gar nicht erst versuche, sie zu wählen.
19. Als Kundin will ich bei abgelaufener Frist eine Sperrseite mit der konkreten Frist (Datum + Uhrzeit) sehen, so dass ich verstehe, wann ich wieder buchen oder stornieren kann.
20. Als Kundin will ich die Zeiten konsistent angezeigt bekommen (Firmen- bzw. Browser-Zeitzone je nach Konfiguration), so dass ich zum richtigen Zeitpunkt erscheine.
21. Als Entwickler will ich, dass Frist- und Pufferlogik zentral in der Verfügbarkeits-Berechnung liegt, so dass Wizard, Datepicker, API und serverseitige Validierung automatisch konsistent sind.
22. Als Entwickler will ich, dass die serverseitige Buchungsvalidierung die gewählte Zeit erneut prüft, so dass parallele Buchungen die Regeln nicht umgehen.
23. Als Entwickler will ich die neuen Sprachschlüssel nur in Deutsch und Englisch pflegen, so dass der Diff minimal bleibt und andere Sprachen auf Englisch zurückfallen.

## Implementation Decisions

- **Settings (7 neue, nur `settings`-Tabelle, per nummerierter Migration nach bestehendem Muster):** `booking_deadline_mode` (`minutes`|`fixed_time`, Default `minutes`), `booking_deadline_time` (`18:00`), `booking_deadline_days` (`1`), `appointment_buffer_time` (`0` Minuten), `appointment_buffer_end_of_day` (`1`), `booking_timezone_display` (`dropdown`|`confirm_only`|`hidden`), `booking_timezone_source` (`company`|`browser`).
- **Frist-Semantik `fixed_time`:** Ein Termin am Tag D ist buchbar, solange „jetzt“ ≤ (Tag D−N) um HH:MM (Provider-Zeitzone). Nach diesem Zeitpunkt ist der gesamte Tag D gesperrt (auch späte Slots). Im Festzeit-Modus gilt **keine** Mindestvorlaufzeit (bewusste Konsequenz des Entweder/Oder); vergangene Stunden werden weiterhin gefiltert. Der Modus `minutes` behält die bestehende Minuten-Logik unverändert bei.
- **Geltungsbereich Frist:** Neubuchung **und** Storno/Umbuchen über den Kunden-Manage-Link (gleicher Sperr-Check-Pfad); Sperr-Message nennt die Frist als Datum + Uhrzeit im Firmen-Datumsformat. Nur kundenseitig: Der Backend-Kalender nutzt die Verfügbarkeits-Berechnung nicht und bleibt unverändert (Admins dürfen überbuchen).
- **Puffer-Semantik:** Global (nicht pro Service); gilt nur nach **echten Terminen** (Termin-Ende + Puffer blockiert nachfolgende Slots), nie davor; nicht nach Unavailabilities oder Blocked Periods; bei Multi-Attendant-Services analog über das Konfliktfenster (Fensterstart − Puffer). Tagesende-Option: letzter Slot nur buchbar, wenn Dauer + Puffer in die Arbeitsperiode passen. Eingabefeld `min="0"` ohne künstliche Obergrenze.
- **Timezone-Anzeige:** Ein einziges 3-stufiges Select statt zweier Checkboxen (verhindert widersprüchliche Kombinationen wie „Frame an“ + „Dropdown aus“, das wäre exakt `confirm_only`). Das `<select>`-Element bleibt im DOM erhalten (versteckt), damit die bestehende JS-Umrechnung weiter funktioniert; Browser-Zeitzonen-Autodetect läuft, wenn das Dropdown sichtbar ist oder die Quelle `browser` ist. Die Zeitzonen-Zeile im Bestätigungs-Frame folgt dem Anzeige-Modus.
- **UI:** Alle Optionen in den Business Settings im bestehenden Frist-Block; Modus- und Quellen-Auswahl als `<select>` (die generische `[data-field]`-Serialisierung funktioniert damit unverändert — Radio-Gruppen mit gleichem `data-field` würden doppelte Einträge erzeugen); modusabhängige Felder werden per JS ein-/ausgeblendet.
- **Sprachdateien:** Neue Keys nur in `german` + `english`; übrige Sprachen fallen auf Englisch zurück. Zusätzlich wird das deutsche Label des bestehenden Minuten-Settings von „Zeitbegrenzung einer Buchung“ zu „Mindestvorlaufzeit“ korrigiert (Hinweis-Text angepasst).
- **Domänen-Vokabular (Glossar):** Neue kanonische Begriffe **Vorlaufzeit** (bestehendes Minuten-Setting), **Buchungsfrist** (Deadline aus Uhrzeit + Tagesabstand), **Tagesabstand** (N Tage, an denen die Frist vor dem Termintag liegt) und **Pufferzeit** (globale Ruhe-/Aufräumzeit nach Terminen, optional am Tagesende).
- **ADR:** Die Entweder/Oder-Frist und die Puffer-Semantik werden als ADR festgehalten (Trade-offs: Minuten vs. Uhrzeit, global vs. pro Service, nur-nach-Terminen, Tagesende optional). Die reine Timezone-Anzeige-Konfiguration erhält bewusst keine ADR (einfach umkehrbare UI-Einstellung).

## Testing Decisions

- **Primärer Seam (ein einziger):** die öffentliche Verfügbarkeits-Berechnung `get_available_hours()` — Wizard-Slots, Datepicker-Ausgrauung, API v1 und die serverseitige Buchungsvalidierung konsumieren alle dieselbe Methode. Deadline und Puffer wirken ausschließlich dort; Tests über diese Methode decken das gesamte Feature ab.
- **Zweiter Seam (unvermeidbar):** der Manage-Mode-Sperrcheck im Booking-Controller (Frist für Storno/Umbuchen + Sperr-Message) — der einzige Frist-Pfad außerhalb der Verfügbarkeits-Berechnung.
- **Was gute Tests ausmacht:** nur äußeres Verhalten prüfen — gegeben Settings, Arbeitsplan, bestehende Termine und „jetzt“: welche Stunden werden zurückgegeben bzw. welche Seite erscheint. Keine internen Hilfsmethoden testen.
- **Prior Art:** PHPUnit-Helper-Tests (`tests/Unit/Helper/…`, Bootstrap über `Tests\TestCase`/`get_instance()`) und das HTTP-Smoke-Muster (`tests/smoke/mebeauty-layout-smoke.sh`, curl + grep).
- **Kein neues Unit-Test-Setup in dieser Runde:** Für die Verfügbarkeits-Berechnung existiert keine DB-Fixture-Infrastruktur; Frist/Puffer werden über eine manuelle Verifikations-Checkliste (beide Frist-Modi, Puffer mit/ohne Tagesende, TZ-Modi, Storno-Lock, API) plus `composer test` und `npm run build` abgesichert. Ein Folge-Ticket kann Availability-Unit-Tests mit DB-Fixtures aufbauen.

## Out of Scope

- Durchsetzung von Frist/Puffer im Backend-Kalender (Admins behalten Override-Freiheit).
- Pufferzeit pro Service statt global.
- Puffer vor Terminen sowie Puffer nach Unavailabilities/Blocked Periods.
- Übersetzungen über Deutsch und Englisch hinaus.
- Automatisierte Unit-Tests der Verfügbarkeits-Berechnung (Folge-Ticket).
- Kombination von Minuten-Vorlaufzeit und fester Uhrzeit.

## Further Notes

- Die neue Migration trägt die Nummer 070 und fügt ausschließlich Settings-Rows ein. (Nach der Bereinigung der alten Theme-Migrationen, siehe ADR 0005.)
- JS-Quellen haben `.min.js`-Gegenstücke — nach Änderungen muss `npm run build` laufen.
- Gemäß AGENTS.md: keine Git-Commits ohne ausdrückliche Nutzer-Freigabe; Task-Branch anlegen.
- Alle Details wurden in einer Grilling-Session mit dem Nutzer abgestimmt; es bestehen keine offenen Designfragen.
