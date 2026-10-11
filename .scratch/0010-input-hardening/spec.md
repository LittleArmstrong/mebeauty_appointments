# Einstellungen zentral validieren, Firmenname escapen

Status: in-progress
Created: 2026-10-11
Closed: –
Priority: P1
Depends-on: 0011

## Problem Statement

Die vom Fork eingeführten Einstellungen werden beim Speichern ungeprüft übernommen: Buchungsfrist, Manage-Frist, Pufferzeit, Zeitzonen-Anzeige und Firmenkopf. Ein Tippfehler wie „25:99“ als Frist-Uhrzeit, negative Tagesabstände oder Text in Zahlenfeldern landet in der Datenbank. Die Verfügbarkeitsberechnung rechnet dann still mit falschen Werten. Das gilt im Backend wie über die Einstellungs-API.

Das Speichern ist außerdem nicht atomar. Die Einstellungen werden nacheinander gespeichert, ein Fehler mittendrin lässt einen halb übernommenen Zustand zurück.

Die **Firmenfarbe** stammt zwar aus einem Farbwähler, wird serverseitig aber nicht geprüft. Sie landet ungeprüft in einem Style-Block der Seite und in E-Mails. Über die API ließe sich damit beliebiges CSS oder HTML einschleusen.

Die **Slot-Kapazität** eines Services wird nur auf „nicht negativ“ geprüft. „abc“ wird als 0 (unbegrenzt) und „1.5“ als 1 übernommen. Die API-Beschreibung kennt das Feld nicht.

Den **Firmennamen** gibt der Fork an zehn Stellen unescaped aus (Seitentitel, Social-Vorschau, Backend-Kopf, E-Mail-Titel). Upstream hatte ihn im Booking-Layout escaped, beim Umbau ist das verloren gegangen.

## Solution

- Die Regeln für die Fork-Einstellungen und die Firmenfarbe stehen an **einer** Stelle: in der Validierung des Settings-Models, durch die alle Schreibwege laufen (Backend-Seiten und API).
- Die Backend-Seiten, die diese Einstellungen speichern (Buchungseinstellungen und Business-Einstellungen), prüfen alle übermittelten Werte, **bevor** sie den ersten speichern. Ein ungültiger Wert speichert nichts und meldet das betroffene Feld.
- Die Slot-Kapazität muss eine ganze Zahl ≥ 0 sein, im Backend wie über die API. Die API-Beschreibung wird ergänzt.
- Alle zehn unescapten Ausgaben des Firmennamens werden escaped.

## User Stories

1. Als Salonbetreiberin will ich eine Fehlermeldung mit dem betroffenen Feld sehen, wenn ich eine ungültige Frist-Uhrzeit eingebe.
2. Als Salonbetreiberin will ich, dass bei einem ungültigen Wert gar nichts gespeichert wird, damit keine halb übernommene Konfiguration entsteht.
3. Als Salonbetreiberin will ich, dass Tagesabstände, Manage-Minuten und Pufferminuten nur ganze Zahlen ≥ 0 akzeptieren.
4. Als Salonbetreiberin will ich, dass die Modi nur die vorgesehenen Werte annehmen, damit kein undefinierter Zustand entsteht.
5. Als Salonbetreiberin will ich, dass Schalter (Puffer vor Arbeitsende, Firmenkopf) nur an oder aus sein können.
6. Als API-Nutzer will ich für ungültige Einstellungswerte dieselbe Ablehnung bekommen wie im Backend.
7. Als Salonbetreiberin will ich, dass gültige und bereits gespeicherte Werte (auch „keine Firmenfarbe“ = Weiß) weiterhin gespeichert werden können.
8. Als Salonbetreiberin will ich, dass eine Firmenfarbe nur als Farbwert gespeichert werden kann, damit Seite und E-Mails nicht zerschossen werden.
9. Als Salonbetreiberin will ich, dass eine Slot-Kapazität wie „abc“, „1.5“ oder „-1“ abgelehnt wird, damit ein Gerät nicht versehentlich unbegrenzt buchbar wird.
10. Als API-Nutzer will ich die Slot-Kapazität in der API-Beschreibung finden.
11. Als Kunde will ich, dass Seitentitel, Social-Vorschau, Backend-Kopf und E-Mails auch bei Sonderzeichen im Firmennamen korrekt erscheinen.
12. Als Entwickler will ich die Regeln an einer Stelle pflegen und ohne Datenbank testen können.

## Implementation Decisions

- **Ort der Regeln:** die Validierung des Settings-Models. Sie läuft bei jedem Speichern einer Einstellung, also bei den Backend-Seiten und bei der API über den Settings-Helper. Die Regeln selbst stehen in einer reinen Funktion (Name + Wert → Fehler oder ok), die ohne Datenbank testbar ist.
- **Regeln** (nur aus vorhandener UI, Fachlogik und Migration abgeleitet, ohne künstliche Obergrenzen):
  - Frist-Uhrzeiten (Buchungs- und Manage-Frist): `HH:MM`, 00:00–23:59 (Eingabefeld vom Typ Uhrzeit)
  - Tagesabstände, Manage-Minuten, Pufferminuten: ganze Zahl ≥ 0 (Eingabefelder mit Minimum 0; die Verfügbarkeit rechnet ohnehin mit ≥ 0)
  - Buchungsfrist-Modus ∈ {minutes, fixed_time}; Manage-Frist-Modus ∈ {inherit, minutes, fixed_time}; Zeitzonen-Anzeige ∈ {dropdown, confirm_only, hidden}; Zeitzonen-Quelle ∈ {company, browser}
  - Puffer vor Arbeitsende, Firmenkopf anzeigen ∈ {0, 1}
  - Firmenfarbe: `#RGB` oder `#RRGGBB` (die Formen, die die Farbberechnung verarbeitet); Weiß bleibt der Wert für „nicht gesetzt“
- Alle Defaults der bestehenden Migration erfüllen die Regeln. Staging und Produktion nutzen keine Firmenfarbe (Weiß), es gibt also keine Bestandsdaten, die die Regeln verletzen.
- Die Einstellungen anderer Upstream-Bereiche bleiben ungeprüft.
- **Atomar speichern:** Die Speicher-Aktionen der Buchungs- und Business-Einstellungen validieren erst alle übermittelten Werte und speichern dann. Fehler laufen über die vorhandene JSON-Fehlerantwort.
- **Slot-Kapazität:** Die Validierung des Service-Models verlangt eine ganze Zahl ≥ 0 (Ziffernfolge oder Integer); leer bzw. nicht gesetzt bedeutet weiter 0. Backend und Service-API nutzen dasselbe Model. Die API-Beschreibung (OpenAPI) bekommt das Feld im Service-Schema.
- **Escaping:** Der vorhandene Escape-Helper an allen Ausgaben des Firmennamens in HTML-Text und Attributen:
  - Booking-Layout (Titel und zwei Social-Vorschau-Tags)
  - Backend-, Konto- und Nachrichten-Layout (Titel)
  - Backend-Kopf
  - Titel der vier E-Mail-Vorlagen (Termin gespeichert, Termin storniert, Passwort zurücksetzen, Konto-Wiederherstellung)
- Für die Firmenfarbe im Style-Block reicht Escaping nicht. Dort schützt die Formatprüfung beim Speichern.
- Keine Schemaänderung.

## Testing Decisions

- Unit-Tests der reinen Regel-Funktion: gültige Grenzwerte (`00:00`, `23:59`, `0`, `#fff`, `#d1ae5e`, `#ffffff`) und ungültige Werte (`24:00`, `9:5`, `-1`, `abc`, unbekannter Modus, `red;}`) mit Feldbezug. Ein Test prüft, dass alle Migrations-Defaults gültig sind. Vorbild: die bestehenden Helper-Unit-Tests.
- Unit-Test der Slot-Kapazitätsregel.
- Escaping: Probe mit dem Firmennamen `Beauty & "Co" <b>` auf Booking-, Backend-, Konto- und Nachrichtenseite sowie in den E-Mails (Mailpit); Layout-Smoke-Test und E-Mail-Logo-Smoke-Test bleiben grün.

## Out of Scope

- Validierung von Upstream-Einstellungen außer der Firmenfarbe.
- Client-seitige Validierung (darf ergänzt werden, ersetzt die Serverprüfung nicht).
- Escaping in Upstream-Views, die der Fork nicht verändert hat.

## Further Notes

- Bezug: ADR 0002, ADR 0003, Glossar „Firmenfarbe“.
- Das Nachrichten-Layout ändert auch `0011/02`. Erst dort die Formatierung bereinigen, dann hier escapen.
- Die Spec `0014-theme-site-chrome` (Einstellung „Sprachauswahl anzeigen“) nutzt denselben Regel-Mechanismus.
