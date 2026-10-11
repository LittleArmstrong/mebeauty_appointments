# Fristen und Sperren in eine eigene Booking-Policy auslagern

Status: ready-for-agent

## Problem Statement

Die Logik für Buchungsfrist, Manage-Frist und Sperren eines Termins steckt in der Verfügbarkeits-Library, obwohl sie fachlich eine **Buchungsregel** ist und keine Slot-Berechnung. Das hat vier Folgen:

- **Merge-Konflikte:** Die Verfügbarkeits-Library ist eine der am stärksten veränderten Upstream-Dateien. Jeder Upstream-Sync (ADR 0005) wird dadurch teurer.
- **Duplikation:** Die Berechnung der Fristen ist für Buchungs- und Manage-Frist nahezu identisch kopiert, ebenso die Minuten-Sperre. Das Normalisieren der Vorlaufzeit gibt es dreimal. Eine Korrektur muss an mehreren Stellen nachgezogen werden.
- **Nicht testbar:** Die Fristen hängen an der echten Uhrzeit und an Settings aus der Datenbank. Darum gibt es keinen einzigen Test dafür, obwohl es reine Datumsrechnung ist.
- **Viele DB-Abfragen:** Jeder Settings-Zugriff ist eine Datenbankabfrage. Pufferzeit und Frist-Settings werden pro Zeitspanne, pro Tag und pro Dienstleister erneut gelesen. Bei der Monatsansicht ergibt das viele identische Abfragen.

Außerdem erzeugen die Buchungsseite und die Stornierungsseite fast identisch die Meldung „Termin gesperrt“.

## Solution

Eine eigene Library **Booking-Policy** beantwortet alle Fragen zu Fristen und Sperren: Bis wann ist ein Tag für Neubuchungen offen? Ist ein Termin für Neubuchung, Umbuchung oder Stornierung gesperrt, und mit welcher Meldung? Die aktuelle Zeit und die Settings werden ihr übergeben bzw. einmal geladen. So lässt sie sich vollständig ohne Datenbank und ohne echte Uhr testen. Die Verfügbarkeits-Library fragt die Policy nur noch, ab wann Slots eines Tages angeboten werden dürfen. Buchungs- und Stornierungsseite rufen die Policy für die Sperre auf und erzeugen die Sperrmeldung über einen gemeinsamen Baustein.

Das Verhalten für Kunden und Salon bleibt unverändert. Es handelt sich um einen reinen Umbau.

## User Stories

1. Als Salonbetreiberin will ich, dass sich Buchungsfrist, Manage-Frist und Sperrmeldungen nach dem Umbau exakt wie vorher verhalten, damit kein Kunde etwas davon merkt.
2. Als Entwickler will ich die Fristlogik an einer einzigen Stelle finden, damit ich Regeln schnell verstehe und ändere.
3. Als Entwickler will ich Fristen mit einer festgelegten „jetzt“-Zeit testen, damit Tests deterministisch sind und nicht von der Uhrzeit abhängen.
4. Als Entwickler will ich Fristen mit vorgegebenen Settings testen, damit ich keine Datenbank brauche.
5. Als Entwickler will ich, dass die Fristberechnung für Buchungs- und Manage-Frist denselben Baustein nutzt, damit Korrekturen überall gleichzeitig greifen.
6. Als Entwickler will ich, dass die Minuten-Sperre für beide Fristarten denselben Baustein nutzt.
7. Als Entwickler will ich, dass die Vorlaufzeit-Minuten an einer Stelle normalisiert werden.
8. Als Maintainer will ich, dass die Verfügbarkeits-Library möglichst nah am Upstream bleibt, damit Upstream-Syncs weniger Konflikte erzeugen.
9. Als Kunde will ich, dass die Monatsansicht der Buchungsseite schnell lädt, damit ich zügig einen Tag wählen kann.
10. Als Entwickler will ich die Sperrmeldung für Buchungs- und Stornierungsseite an einer Stelle erzeugen, damit Texte und Variablen konsistent bleiben.
11. Als Salonbetreiberin will ich, dass im Modus „übernehmen“ die Manage-Frist exakt der Buchungsfrist folgt, wie in ADR 0003 festgelegt.
12. Als Salonbetreiberin will ich, dass im Festzeit-Modus der ganze Tag nach Fristablauf gesperrt ist, ohne zusätzliche Vorlaufzeit, wie in ADR 0002 festgelegt.
13. Als Entwickler will ich, dass die Prüfung beim Absenden einer Umbuchung (Spec `manage-lock-enforcement`) dieselbe Policy nutzt wie beim Öffnen.

## Implementation Decisions

- Neue Library **Booking_policy** mit kleiner öffentlicher Schnittstelle:
  - Sperre eines Termins: Eingaben sind der Beginn des Termins, die Zeitzone und die Art (Neubuchung oder Manage). Rückgabe wie bisher: ob gesperrt, Grenzwert-Text und Meldungstext.
  - Frist eines Tages für Neubuchungen: Datum und Zeitzone → Fristzeitpunkt oder „keine Frist“ (Minuten-Modus).
  - Frühester buchbarer Zeitpunkt eines Tages: kombiniert Festzeit-Frist und Minuten-Vorlaufzeit. Die Verfügbarkeit nutzt ihn, um Slots abzuschneiden.
- Testbarkeit: Die „jetzt“-Zeit ist injizierbar (Default: echte Zeit). Die Settings werden über eine injizierbare Quelle gelesen (Default: Settings-Helper), und zwar einmal je Instanz, also gecacht.
- Interne Helfer ersetzen die Duplikate: ein Baustein für die Festzeit-Frist (Uhrzeit + Tagesabstand), einer für die Minuten-Sperre, einer für das Lesen eines ganzzahligen Settings ≥ 0.
- Die Verfügbarkeits-Library entfernt ihre Frist- und Sperrmethoden und ruft stattdessen die Policy auf. Die öffentliche Sperrmethode bleibt höchstens als dünne Weiterleitung erhalten, falls weitere Aufrufer existieren; sonst entfällt sie.
- Pufferzeit-Settings in der Verfügbarkeits-Library werden einmal pro Instanz gelesen, nicht mehr pro Zeitspanne.
- Buchungs- und Stornierungsseite erzeugen die Sperrmeldung über einen gemeinsamen Baustein (privater Helper oder Trait), der die Seitendaten aus der Sperre der Policy aufbaut.
- Sprachschlüssel und Meldungstexte bleiben unverändert.
- Keine Schemaänderung.

## Testing Decisions

- Haupt-Seam ist die öffentliche Schnittstelle der Booking-Policy (vom Nutzer bestätigt). Unit-Tests mit festgelegter „jetzt“-Zeit und vorgegebenen Settings:
  - Festzeit-Modus: Frist 18:00, Tagesabstand 1 → Termin am Folgetag ist um 17:59 offen und um 18:00 gesperrt.
  - Festzeit-Modus, Tagesabstand 0 → Frist am selben Tag.
  - Minuten-Modus: Vorlaufzeit 120 → Termin in 119 Minuten gesperrt, in 121 Minuten offen.
  - Manage „übernehmen“ → gleiches Ergebnis wie Buchungsfrist.
  - Manage „Minuten“ und Manage „feste Uhrzeit“ → eigene Frist, unabhängig von der Buchungsfrist.
  - Zeitzonen: Dienstleister in einer anderen Zeitzone als der Server → Frist in der Zeitzone des Dienstleisters.
  - Sommerzeitwechsel: Frist über die Umstellung hinweg wird korrekt berechnet.
  - Frühester buchbarer Zeitpunkt im Festzeit-Modus nach Fristablauf → ganzer Tag gesperrt.
- Gute Tests prüfen nur Rückgaben der öffentlichen Schnittstelle, keine internen Helfer.
- Vorbild: bestehende Unit-Tests der Verfügbarkeits-Library.
- Regression: Layout-Smoke-Test und das Smoke-Skript aus Spec `manage-lock-enforcement` bleiben grün.

## Out of Scope

- Verhaltensänderungen an Fristen oder Sperren.
- Ein globaler Cache für alle Settings der App.
- Slot-Kapazität und Pufferregeln (Spec `buffer-end-of-day`).

## Further Notes

- Reihenfolge: nach `manage-lock-enforcement` und `buffer-end-of-day`, damit die Fixes auf dem alten Stand klein bleiben und der Umbau sie mitnimmt.
- Bezug: ADR 0002, ADR 0003, ADR 0005 (Upstream-Sync).
