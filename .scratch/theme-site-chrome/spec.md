# Site-Chrome pro Theme und mehrsprachige Site-Chrome-Texte

Status: ready-for-agent

## Problem Statement

Navbar, Seitenkopf, Footer und WhatsApp-Button der mebeauty-Site (das Site-Chrome) sind fest in das Layout des Booking-Flows eingebaut, unabhängig vom aktiven Theme. Wählt ein Admin ein anderes Theme, z. B. eines der Upstream-Themes, erscheinen trotzdem MeBeauty-Logo, -Telefonnummer, -Adresse und -Social-Links. Der Fork soll später weitere Kunden bedienen. Jeder Kunde braucht dann ein eigenes Site-Chrome, das seine eigene Website nachbildet. Heute geht das nur durch Änderungen an Kern-Views.

Außerdem sind die Texte im Site-Chrome fest auf Deutsch geschrieben („Buchung“, „Menü öffnen“, Footer-Überschriften). Der Booking-Flow hat aber einen Sprachumschalter. Stellt ein Kunde auf Englisch um, wird die Wizard-Card englisch, Navbar und Footer bleiben deutsch. Die mebeauty-Site ist derzeit nur deutsch, deshalb ist ein Sprachwechsel für MeBeauty im Moment ohnehin nicht stimmig.

Die Browser-Theme-Farbe des Booking-Flows ist zudem fest auf das MeBeauty-Gold gesetzt und folgt weder der Firmenfarbe noch dem Theme.

## Solution

Das Site-Chrome gehört zum Theme (ADR 0006). Ein Theme bringt optional eigene Site-Chrome-Bausteine mit. Das Booking-Layout zeigt das Site-Chrome nur, wenn das aktive Theme welche hat. Andernfalls zeigt es das Upstream-Layout des Booking-Flows. Das MeBeauty-Site-Chrome zieht in das Theme `mebeauty`. Die Kundendaten bleiben dort direkt im Markup.

Alle Texte im Site-Chrome laufen über die Übersetzungen (Deutsch und Englisch). Eine neue Einstellung „Sprachauswahl anzeigen“ blendet den Sprachumschalter im Booking-Flow aus. Standard ist „anzeigen“, so wie bei Upstream. Für MeBeauty wird sie ausgeschaltet, bis die mebeauty-Site mehrsprachig ist.

Die Browser-Theme-Farbe des Booking-Flows folgt der Firmenfarbe. Ist keine Firmenfarbe gesetzt, gilt die Standard-Primärfarbe.

## User Stories

1. Als Salonbetreiberin will ich, dass der Booking-Flow mit dem Theme `mebeauty` genauso aussieht wie heute (Navbar, Seitenkopf, Footer, WhatsApp-Button), damit sich für meine Kunden nichts ändert.
2. Als Admin will ich, dass bei einem Upstream-Theme kein MeBeauty-Branding erscheint, damit Themes sich nicht vermischen.
3. Als Entwickler will ich für einen neuen Kunden ein neues Theme mit eigenem Site-Chrome anlegen können, ohne Kern-Views zu ändern.
4. Als Entwickler will ich, dass ein Theme ohne Site-Chrome automatisch das Upstream-Layout des Booking-Flows bekommt, damit nicht jedes Theme Site-Chrome braucht.
5. Als Entwickler will ich, dass ein Theme einzelne Site-Chrome-Bausteine weglassen kann (z. B. ohne WhatsApp-Button), damit Kunden nur nutzen, was ihre Website hat.
6. Als Salonbetreiberin will ich, dass meine Kontaktdaten im Site-Chrome stehen bleiben, wie sie sind, damit die Seite meiner Website entspricht.
7. Als Kunde will ich, dass Navbar und Footer in derselben Sprache erscheinen wie die Wizard-Card, sobald Mehrsprachigkeit angeboten wird.
8. Als Salonbetreiberin will ich den Sprachumschalter im Booking-Flow ausblenden können, solange meine Website nur deutsch ist, damit Kunden keine halb übersetzte Seite sehen.
9. Als Salonbetreiberin will ich den Sprachumschalter später wieder einschalten können, ohne dass Code geändert werden muss.
10. Als Admin einer Installation ohne Anpassung will ich, dass der Sprachumschalter standardmäßig angezeigt wird, damit sich das Upstream-Verhalten nicht ändert.
11. Als Kunde mit Screenreader will ich, dass die versteckten Beschriftungen (z. B. „Menü öffnen“) in meiner Sprache vorgelesen werden.
12. Als Salonbetreiberin will ich, dass die Browser-Leiste auf Mobilgeräten meine Firmenfarbe zeigt, damit die Seite einheitlich wirkt.
13. Als Admin will ich, dass ein per URL gewähltes Theme auch das passende Site-Chrome zeigt, damit Vorschauen konsistent sind.
14. Als Entwickler will ich, dass der Smoke-Test weiter prüft, dass das MeBeauty-Site-Chrome mit dem Theme `mebeauty` erscheint.

## Implementation Decisions

- Theme-Bausteine: Jedes Theme hat einen optionalen View-Ordner für Site-Chrome. Darin liegen Navbar, Seitenkopf, Footer und WhatsApp-Button, jeweils optional. Der Ordnerkonvention folgt das Theme-Asset-Schema (Theme-Name = Ordnername).
- Das Booking-Layout ermittelt das aktive Theme (es wird dem Layout schon heute als Seitenvariable übergeben) und bindet die Site-Chrome-Bausteine nur ein, wenn die jeweilige Datei für dieses Theme existiert. Der Theme-Name ist bereits bereinigt, Pfade werden nur aus ihm gebildet.
- Ohne Site-Chrome rendert das Booking-Layout den Wizard wie im Upstream-Layout, ohne Site-Seitenklasse und ohne Seitenkopf. Die Site-spezifischen Styles hängen an der Site-Seitenklasse, damit sie ohne Chrome nicht greifen.
- Die vier bestehenden Site-Chrome-Komponenten ziehen unverändert in den Ordner des Themes `mebeauty`. Danach gibt es keine Site-Chrome-Komponenten mehr im allgemeinen Komponenten-Ordner.
- Neue Übersetzungsschlüssel für alle Site-Chrome-Texte, auch den Seitentitel „Buchung“, in Deutsch und Englisch. Andere Sprachen fallen auf Englisch zurück, wie es die App für fehlende Schlüssel vorsieht. Ist das nicht so, werden die Schlüssel in allen Sprachdateien mit dem englischen Text angelegt.
- Neue Einstellung „Sprachauswahl anzeigen“ (Werte 0/1, Default 1) per neuer Migration mit der nächsten freien Nummer (ADR 0005). Sie wird in den Buchungseinstellungen gepflegt und von der Validierung aus Spec `input-hardening` als 0/1 geprüft. Der Booking-Flow-Footer blendet den Umschalter bei 0 aus.
- Für MeBeauty wird die Einstellung auf 0 gesetzt, als Konfigurationsschritt im Deployment und nicht per Migration (Migrationen behalten Upstream-kompatible Defaults).
- Browser-Theme-Farbe im Booking-Layout: Firmenfarbe, sonst Standard-Primärfarbe.

## Testing Decisions

- Der Layout-Smoke-Test ist die Haupt-Seam (HTTP, öffentliche Seite):
  - Theme `mebeauty` → Navbar, Footer und Seitenkopf des Site-Chrome vorhanden (bestehende Prüfungen bleiben).
  - Theme per URL auf ein Upstream-Theme → kein MeBeauty-Site-Chrome im HTML.
  - Einstellung Sprachauswahl 0 → kein Sprachumschalter im HTML. Bei 1 ist er vorhanden.
- Die Migration prüft der bestehende Migrations-Validator in den Unit-Tests.
- Gute Tests prüfen gerendertes HTML, nicht interne Pfadauflösung.
- Manuell: Sichtprüfung des Booking-Flows mit `mebeauty` (unverändert) und mit `default` (Upstream-Optik).

## Out of Scope

- Ein generisches, im Backend pflegbares Site-Chrome (in ADR 0006 verworfen).
- Mehrsprachigkeit der mebeauty-Site selbst.
- Site-Chrome für Backend-, Konto- oder Nachrichten-Layouts.
- Anlegen eines zweiten Kunden-Themes.

## Further Notes

- Bezug: ADR 0001 (Theme als SCSS-Nachbau), ADR 0006 (Site-Chrome pro Theme), Glossar „Theme“ und „Site-Chrome“.
- Ersetzt Review-Punkt 8 (hartcodierte Geschäftsdaten). Die Daten bleiben bewusst im Theme.
- Der Smoke-Test setzt `theme=mebeauty` in der Datenbank voraus (siehe AGENTS.md). Die Prüfung mit einem Upstream-Theme nutzt den URL-Parameter, damit die Datenbank nicht umgestellt werden muss.
