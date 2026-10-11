# Fristen und Sperren in eine eigene Booking-Policy auslagern

Status: ready-for-agent
Created: 2026-10-11
Closed: –
Priority: P2
Depends-on: 0009, 0012

## Problem Statement

Die Logik für Buchungsfrist, Manage-Frist und Terminsperren steckt in der Verfügbarkeits-Library, obwohl sie eine Buchungsregel ist und keine Slot-Berechnung. Daraus folgen mehrere Probleme:

- **Mehrere abweichende Implementierungen:** Dieselbe Regel existiert zweimal, als Filter der Slot-Liste und als Sperre eines bestehenden Termins. An der exakten Grenze des Minuten-Modus widersprechen sie sich. Beispiel bei 60 Minuten Mindestvorlaufzeit, jetzt 10:00:00: Der Slot 11:00 wird **nicht** angeboten, ein bestehender 11:00-Termin ist aber **nicht** gesperrt.
- **Duplikation:** Die Fristberechnung ist für Buchungs- und Manage-Frist nahezu identisch kopiert, ebenso die Minuten-Sperre. Die Vorlaufzeit wird an drei Stellen normalisiert.
- **Nicht testbar:** Alles hängt an der echten Uhrzeit und an Datenbank-Einstellungen. Es gibt keinen einzigen Test für Fristen.
- **Merge-Konflikte:** Die Verfügbarkeits-Library ist eine der am stärksten veränderten Upstream-Dateien (ADR 0005).
- **Viele identische DB-Abfragen:** Jeder Zugriff auf eine Einstellung ist eine eigene Datenbankabfrage. Die Puffer-Einstellungen werden pro Zeitspanne, Tag und Dienstleister erneut gelesen.
- **Doppelte Sperrmeldung:** Buchungs- und Stornierungsseite erzeugen die Meldung „Termin gesperrt“ fast identisch.

## Solution

Eine eigene Library **Booking-Policy** ist die einzige Implementierung von Fristen und Sperren. Sie beantwortet drei Fragen:
- Ab wann darf ein Slot eines Tages angeboten werden?
- Ist ein bestehender Termin für Neubuchung oder Verwaltung gesperrt, und mit welcher Meldung?
- Wann liegt die Frist eines Tages?

Uhr und Einstellungen werden ihr übergeben bzw. einmal geladen. Verfügbarkeit, Buchungsseite, Umbuchen und Stornierung fragen nur noch die Policy.

Mit dem Umbau wird die Grenze **vereinheitlicht**: Ein Termin, der genau zur Mindestvorlaufzeit beginnt, ist buchbar und verwaltbar („mindestens“). Das ist die einzige beabsichtigte Verhaltensänderung. Sonst bleibt alles gleich.

## User Stories

1. Als Salonbetreiberin will ich, dass sich Buchungsfrist, Manage-Frist und Sperrmeldungen nach dem Umbau wie vorher verhalten (bis auf die vereinheitlichte Grenze).
2. Als Kunde will ich, dass ein Termin, der genau zur Mindestvorlaufzeit beginnt, sowohl buchbar als auch verwaltbar ist, damit beide Regeln dasselbe sagen.
3. Als Salonbetreiberin will ich, dass im Festzeit-Modus der Tag bis einschließlich der Frist-Uhrzeit (z. B. 18:00:00) offen ist und danach ganz gesperrt wird, wie bisher.
4. Als Salonbetreiberin will ich, dass im Modus „übernehmen“ die Manage-Frist exakt der Buchungsfrist folgt (ADR 0003).
5. Als Entwickler will ich die Fristlogik an genau einer Stelle finden.
6. Als Entwickler will ich Fristen mit einer festgelegten Uhrzeit und vorgegebenen Einstellungen testen, ohne Datenbank.
7. Als Entwickler will ich, dass Buchungs- und Manage-Frist denselben Baustein für die Fristberechnung und für die Minuten-Sperre nutzen.
8. Als Maintainer will ich, dass die Verfügbarkeits-Library näher am Upstream bleibt.
9. Als Kunde will ich, dass die Monatsansicht schnell lädt.
10. Als Entwickler will ich die Sperrmeldung an einer Stelle erzeugen, ohne dass dieser Baustein selbst Fristen oder Berechtigungen prüft.

## Implementation Decisions

- Neue Library **Booking_policy** (Logik gehört nach Projektkonvention in Libraries; Controller bleiben schlank). Öffentliche Schnittstelle:
  - Sperre eines Termins: Beginn, Zeitzone, Art (Neubuchung | Verwaltung) → gesperrt ja/nein, Grenzwert-Text, Meldungstext
  - Frist eines Tages für Neubuchungen: Datum, Zeitzone → Zeitpunkt oder „keine“ (Minuten-Modus)
  - Frühester buchbarer Zeitpunkt eines Tages (für den Slot-Filter der Verfügbarkeit)
- **Uhr injizierbar** (Default: echte Zeit), **Einstellungsquelle injizierbar** (Default: Settings-Helper), Einstellungen einmal je Instanz gelesen.
- Interne Bausteine ersetzen die Duplikate: Festzeit-Frist (Uhrzeit + Tagesabstand), Minuten-Grenze, ganzzahlige Einstellung ≥ 0.
- **Eine Grenzsemantik für beide Verwendungen:**
  - Minuten-Modus: Ein Termin bzw. Slot ist zulässig, wenn `Beginn ≥ jetzt + Vorlaufzeit` (vereinheitlicht, vorher Slot-Filter `>`, Sperre `≥`)
  - Festzeit-Modus: Der Tag ist offen, solange `jetzt ≤ Frist` (unverändert)
  - Vergangene Uhrzeiten werden weiterhin gefiltert.
- Die Verfügbarkeits-Library enthält danach keine Frist- oder Sperrberechnung mehr; die zukünftige Buchungsgrenze (Upstream) bleibt dort.
- Die Prüfung beim Absenden einer Umbuchung (Spec `0009-manage-lock-enforcement`) nutzt die Policy.
- Die Puffer-Einstellungen in der Verfügbarkeits-Library werden einmal je Instanz gelesen (eigenes Ticket, unabhängig).
- **Sperrmeldung:** Ein gemeinsamer Baustein erzeugt aus einem **fertigen** Sperrergebnis der Policy den Meldungstext (mit eingesetztem Grenzwert) bzw. die Seitendaten. Er berechnet keine Frist und prüft keine Berechtigung. Buchungs- und Stornierungsseite nutzen die Seitendaten, die Absende-Prüfung (JSON) nutzt nur den Text.
- Sprachschlüssel und Texte bleiben unverändert. Keine Schemaänderung.

## Testing Decisions

- Haupt-Seam ist die öffentliche Schnittstelle der Booking-Policy. Unit-Tests mit fester Uhr und vorgegebenen Einstellungen:
  - Festzeit, Frist 18:00, Tagesabstand 1: Termin am Folgetag um 18:00:00 offen, um 18:00:01 gesperrt
  - Festzeit, Tagesabstand 0: Frist am selben Tag
  - Minuten-Modus, 60 Minuten, jetzt 10:00:00: Beginn 11:00 zulässig (Slot angeboten, Termin nicht gesperrt); Beginn 10:59 gesperrt bzw. nicht angeboten
  - Manage „inherit“ = Buchungsfrist; Manage „minutes“ und „fixed_time“ unabhängig von der Buchungsfrist
  - Zeitzone des Dienstleisters abweichend von der Server-Zeitzone
  - Sommerzeitwechsel zwischen Frist und Termintag
  - Frühester buchbarer Zeitpunkt im Festzeit-Modus nach Fristablauf → ganzer Tag gesperrt
- Gute Tests prüfen nur Rückgaben der öffentlichen Schnittstelle.
- Regression: Smoke-Skript aus `0009-manage-lock-enforcement` und Layout-Smoke-Test.

## Out of Scope

- Weitere Verhaltensänderungen an Fristen oder Sperren.
- Ein globaler Cache für alle Einstellungen der App.
- Zukünftige Buchungsgrenze (Upstream-Einstellung).

## Further Notes

- Reihenfolge: nach `0009/02` und `0012/02`, damit die Fixes auf dem alten Stand klein bleiben.
- Bezug: ADR 0002, ADR 0003, ADR 0005; Glossar „Vorlaufzeit“, „Buchungsfrist“, „Umbuchungs-/Stornierungsfrist“.
