# Eingaben validieren und Ausgaben escapen

Status: ready-for-agent

## Problem Statement

Die vom Fork eingeführten Einstellungen (Buchungsfrist, Manage-Frist, Pufferzeit) werden beim Speichern im Backend ungeprüft übernommen. Ein Tippfehler wie „25:99“ als Frist-Uhrzeit, negative Tagesabstände oder Text in Zahlenfeldern landen in der Datenbank. Die Verfügbarkeitsberechnung fängt das nur teilweise ab und rechnet dann still mit falschen Werten, z. B. 0 Minuten. Für den Salon heißt das: Die Buchungsseite verhält sich anders als eingestellt, und niemand merkt es. Dasselbe gilt für die Slot-Kapazität eines Services: Ein Wert wie „abc“ wird akzeptiert und als 0 (unbegrenzt) interpretiert.

Außerdem gibt der Fork den Firmennamen und die Firmenfarbe an mehreren Stellen unescaped aus: im Seitentitel, in Social-Vorschau-Tags, im Nachrichten-Layout und in E-Mails. Die Werte pflegt zwar nur ein Admin, aber ein Name mit Sonderzeichen wie „Beauty & Co“ oder Anführungszeichen erzeugt kaputtes HTML. Upstream escaped solche Ausgaben standardmäßig.

## Solution

- Beim Speichern der Buchungseinstellungen prüft der Server die Frist- und Puffer-Einstellungen. Bei einem ungültigen Wert wird **nichts** gespeichert, und der Admin sieht eine verständliche Fehlermeldung, welches Feld betroffen ist.
- Beim Speichern eines Services wird geprüft, ob die Slot-Kapazität eine ganze Zahl ≥ 0 ist. Sonst wird der Service mit Validierungsfehler abgelehnt, im Backend wie über die API.
- Alle vom Fork eingeführten oder geänderten Ausgaben von Firmenname und Firmenfarbe werden escaped. Die Firmenfarbe in E-Mails wird nur verwendet, wenn sie ein gültiger Hex-Farbwert ist, sonst gilt die Standardfarbe.

## User Stories

1. Als Salonbetreiberin will ich eine Fehlermeldung sehen, wenn ich eine ungültige Frist-Uhrzeit eingebe, damit ich den Fehler sofort korrigieren kann.
2. Als Salonbetreiberin will ich, dass bei einem ungültigen Wert gar nichts gespeichert wird, damit keine halb übernommene Konfiguration entsteht.
3. Als Salonbetreiberin will ich, dass die Fehlermeldung das betroffene Feld nennt, damit ich es schnell finde.
4. Als Salonbetreiberin will ich, dass Tagesabstände nur ganze Zahlen ≥ 0 akzeptieren, damit eine Frist nie in der Zukunft des Termintags liegt.
5. Als Salonbetreiberin will ich, dass Vorlauf- und Pufferminuten nur ganze Zahlen ≥ 0 akzeptieren, damit die Verfügbarkeit nicht mit Phantasiewerten rechnet.
6. Als Salonbetreiberin will ich, dass die Frist-Modi nur die vorgesehenen Werte annehmen (Buchungsfrist: Minuten oder feste Uhrzeit; Manage-Frist: übernehmen, Minuten oder feste Uhrzeit), damit kein undefinierter Zustand entsteht.
7. Als Salonbetreiberin will ich, dass die Option „Puffer vor Arbeitsende“ nur an oder aus sein kann, damit das Verhalten eindeutig ist.
8. Als Salonbetreiberin will ich, dass eine Slot-Kapazität wie „abc“ oder „-1“ abgelehnt wird, damit ein Gerät nicht versehentlich unbegrenzt buchbar wird.
9. Als API-Nutzer will ich bei ungültiger Slot-Kapazität eine Validierungsfehler-Antwort bekommen, so wie bei anderen ungültigen Service-Feldern.
10. Als Salonbetreiberin will ich, dass gültige bestehende Werte weiterhin gespeichert werden können, damit sich für korrekte Konfigurationen nichts ändert.
11. Als Kunde will ich, dass Seitentitel, Social-Vorschau und E-Mails auch bei Sonderzeichen im Firmennamen korrekt angezeigt werden.
12. Als Salonbetreiberin will ich, dass eine ungültige Firmenfarbe die E-Mails nicht zerschießt, sondern die Standardfarbe verwendet wird.
13. Als Entwickler will ich die Regeln für die Settings an einer Stelle haben, damit Backend-Speichern und künftige Aufrufer dieselben Regeln nutzen.

## Implementation Decisions

- Neue reine Validierungsfunktion für die Fork-Settings der Buchungseinstellungen. Sie nimmt die zu speichernden Name/Wert-Paare entgegen und wirft bei Verstoß eine Validierungs-Exception mit Feldbezug. Regeln:
  - Frist-Uhrzeiten (Buchungs- und Manage-Frist): Format `HH:MM`, 00:00–23:59.
  - Tagesabstände, Manage-Minuten, Pufferminuten: ganze Zahl ≥ 0.
  - Buchungsfrist-Modus ∈ {Minuten, feste Uhrzeit}; Manage-Frist-Modus ∈ {übernehmen, Minuten, feste Uhrzeit}; Werte wie in der bestehenden Migration.
  - Puffer vor Arbeitsende ∈ {0, 1}.
- Settings, die nicht zum Fork gehören, bleiben ungeprüft wie bei Upstream.
- Der Buchungseinstellungs-Controller ruft die Validierung vor dem ersten Speichern auf; ein Fehler bricht den gesamten Speichervorgang ab und wird über die vorhandene JSON-Fehlerantwort an das Backend gemeldet.
- Die Validierung im Service-Model prüft die Slot-Kapazität auf ganze Zahl ≥ 0 (numerischer String oder Integer); leer bzw. nicht gesetzt bleibt erlaubt und bedeutet 0.
- Escaping mit dem vorhandenen Escape-Helper an allen vom Fork eingeführten oder geänderten Ausgaben des Firmennamens (Booking-Layout inkl. Social-Vorschau-Tags, Nachrichten-Layout, E-Mail-Templates).
- E-Mail-Templates: Die Firmenfarbe wird gegen ein Hex-Muster (`#RGB` / `#RRGGBB`) geprüft, sonst gilt die Standard-Primärfarbe.
- Keine Schemaänderung.

## Testing Decisions

- Unit-Tests für die Validierungsfunktion: gültige Grenzwerte (00:00, 23:59, 0) werden akzeptiert; ungültige Werte (24:00, 9:5, -1, „abc“, unbekannter Modus) werden mit Feldbezug abgelehnt. Vorbild: bestehende Helper-Tests unter den Unit-Tests (Validierungs-Helper).
- Unit-Test für die Slot-Kapazitätsregel, sofern sie ohne Datenbank prüfbar ist; sonst über die reine Prüffunktion, die das Model nutzt.
- Escaping: Der Layout-Smoke-Test bleibt grün. Manuelle Probe mit einem Firmennamen, der `&` und `"` enthält: Der Seitenquelltext enthält die Werte escaped.

## Out of Scope

- Validierung der Upstream-Settings.
- Client-seitige Validierung im Backend-Formular (darf ergänzt werden, ersetzt aber nicht die Serverprüfung).
- Escaping in Upstream-Views, die der Fork nicht verändert hat.

## Further Notes

- Bezug: ADR 0002, ADR 0003. Die Werte müssen zu den von der Migration gesetzten Defaults passen; die Defaults müssen die Validierung bestehen.
