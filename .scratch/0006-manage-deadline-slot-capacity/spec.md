# Umbuchungs-/Stornierungsfrist & Service-Slot-Kapazität

Status: resolved
Created: 2026-10-04
Closed: 2026-10-09
Priority: –
Depends-on: –

## Problem Statement

Der Salon will drei Dinge steuern, die der Easy!Appointments-Fork heute nicht trennen oder nicht abbilden kann:

1. **Umbuchen/Stornieren folgt derselben Frist wie die Neubuchung.** Die Buchungsfrist sperrt Neubuchung, Umbuchung **und** Stornierung gemeinsam über einen einzigen Pfad. Der Salon möchte die Möglichkeit, für Umbuchen/Stornieren eine **andere** Zeit zu setzen als für Neubuchungen — ohne dabei die heute funktionierende Standard-Logik zu verlieren.
2. **Begrenzte Ressourcen (Raum/Maschine) werden nicht respektiert.** Die Verfügbarkeit wird pro Provider berechnet. Zwei unterschiedliche Provider können denselben Service zur selben Zeit anbieten, obwohl nur ein Gerät/Raum existiert. Es gibt keine Möglichkeit zu sagen „dieser Service darf höchstens N-mal parallel gebucht werden" — unabhängig vom Provider.
3. **Der schwebende WhatsApp-Button auf der Buchungsseite ist größer als auf der Hauptseite.**

Zusätzlich liegen im Repo mehrere bereits gemergte Branches, die aufgeräumt werden sollen.

## Solution

Vier Maßnahmen:

- **Geteilte Umbuchungs-/Stornierungsfrist („Manage-Frist"):** Ein neuer Schalter mit drei Zuständen: `inherit` (Standard — übernimmt exakt die bestehende Buchungsfrist, also das heutige Verhalten), `minutes` (eigene Vorlaufzeit in Minuten) und `fixed_time` (eigene feste Uhrzeit + Tagesabstand). Umbuchen und Stornieren teilen sich **eine** Frist; getrennt ist nur die Neubuchung. Die Neubuchung bleibt vollständig bei der bestehenden Buchungsfrist.
- **Service-Slot-Kapazität:** Ein neues Feld pro Service `slot_capacity` (Standard `0` = unbegrenzt). Bei Wert > 0 darf der Service im selben Zeitfenster höchstens so oft **parallel über alle Provider** gebucht werden. Zwei Buchungen desselben Services kollidieren, sobald ihre Zeitfenster sich überlappen.
- **WhatsApp-Button:** Die gerenderte Größe des schwebenden Buttons wird an die Hauptseite angeglichen (zuerst messen, dann CSS anpassen).
- **Branch-Aufräumen:** Alle in `main` gemergten `ticket/*`- und `integration/*`-Branches sowie der verwaiste `agent/google-calendar-push-sync`-Branch werden lokal gelöscht; das Remote bleibt unangetastet.

Backend-Kalender und Admin-Freiheiten bleiben unverändert: Frist und Kapazität wirken nur kundenseitig (Buchungsseite + API).

## User Stories

1. Als Salonbetreiberin will ich, dass Umbuchen und Stornieren standardmäßig exakt der heutigen Frist folgen, so dass sich durch das Update ohne Konfiguration nichts ändert.
2. Als Salonbetreiberin will ich eine eigene Frist für Umbuchen/Stornieren setzen, so dass Kunden z. B. bis 24 h vorher stornieren dürfen, auch wenn Neubuchungen einer anderen Frist folgen.
3. Als Salonbetreiberin will ich zwischen „Frist übernehmen" und „eigene Frist" wählen, so dass ich die Trennung nur dann aktiviere, wenn ich sie brauche.
4. Als Salonbetreiberin will ich die eigene Frist als **Entweder/Oder** konfigurieren (Minuten-Vorlaufzeit **oder** feste Uhrzeit + Tagesabstand), so dass das Verhalten eindeutig bleibt und nicht kombiniert werden kann.
5. Als Salonbetreiberin will ich, dass Umbuchen und Stornieren **eine gemeinsame** Frist haben, so dass ich nicht zwei Werte pflegen muss.
6. Als Salonbetreiberin will ich, dass die Neubuchung unverändert der Buchungsfrist folgt, so dass die Trennung sauber zwischen „neuer Termin" und „Termin verwalten" verläuft.
7. Als Salonbetreiberin will ich eine Vorlaufzeit von 0 Minuten setzen können, so dass Umbuchen/Stornieren bis zum Terminbeginn möglich ist.
8. Als Salonbetreiberin will ich, dass die Frist nur kundenseitig gilt (Buchungsseite + API), so dass mein Personal im Backend weiterhin frei Termine verschieben und stornieren kann.
9. Als Salonbetreiberin will ich für einen Service eine maximale Anzahl paralleler Buchungen festlegen, so dass ein begrenzter Raum/eine Maschine nicht überbucht wird.
10. Als Salonbetreiberin will ich mit dem Wert 1 festlegen, dass ein Service nur **einmal** pro Zeitfenster gebucht werden kann — auch wenn mehrere Provider ihn anbieten, so dass das einzelne Gerät nicht doppelt vergeben wird.
11. Als Salonbetreiberin will ich mit einem Wert > 1 festlegen, wie viele Buchungen parallel möglich sind (z. B. 3 Massageliegen), so dass die tatsächliche Geräteanzahl abgebildet wird.
12. Als Salonbetreiberin will ich den Standard 0 (unbegrenzt) beibehalten, so dass Services ohne Limit sich unverändert verhalten.
13. Als Salonbetreiberin will ich, dass die Kapazität **über alle Provider** zählt, so dass sich zwei Provider nicht gegenseitig das gleiche Gerät blockieren.
14. Als Salonbetreiberin will ich, dass die Kapazität nur im Frontend greift, so dass Admins im Kalender weiterhin manuell überbuchen dürfen.
15. Als Salonbetreiberin will ich die Kapazität im Service-Formular (Backend) pflegen, so dass keine Code-Änderung nötig ist.
16. Als Salonbetreiberin will ich, dass die Kapazität Buchungen zählt (nicht Personen), so dass sie unabhängig von der bestehenden Teilnehmerzahl (`attendants_number`) funktioniert.
17. Als Salonbetreiberin will ich den schwebenden WhatsApp-Button in derselben Größe wie auf der Hauptseite, so dass die Buchungsseite einheitlich wirkt.
18. Als Salonbetreiberin will ich das Repo ohne veraltete Branches, so dass nur aktive Arbeit sichtbar bleibt.
19. Als Kundin will ich im Buchungs-Wizard nur Zeiten sehen, die die Service-Kapazität respektieren, so dass ich keinen bereits ausgebuchten Slot angeboten bekomme.
20. Als Kundin will ich bei abgelaufener Manage-Frist eine Sperrseite sehen, so dass ich verstehe, warum ich nicht mehr umbuchen oder stornieren kann.
21. Als Kundin will ich bei „Beliebiger Anbieter" keine Zeit angeboten bekommen, die über die Service-Kapazität bereits belegt ist, so dass die Zuordnung nicht auf einen Konflikt läuft.
22. Als Entwicklerin will ich, dass die Kapazitätslogik zentral in der Verfügbarkeits-Berechnung liegt, so dass Wizard, Datepicker, API und serverseitige Validierung automatisch konsistent sind.
23. Als Entwicklerin will ich, dass die serverseitige Buchungsvalidierung die Kapazität erneut prüft, so dass parallele Buchungen die Regel nicht umgehen.
24. Als Entwicklerin will ich die neuen Sprachschlüssel nur in Deutsch und Englisch pflegen, so dass der Diff minimal bleibt und andere Sprachen auf Englisch zurückfallen.

## Implementation Decisions

- **Manage-Frist-Settings (4 neue, nur `settings`-Tabelle, per nummerierter Migration):** `manage_deadline_mode` (`inherit`|`minutes`|`fixed_time`, Default `inherit`), `manage_deadline_minutes` (`0`), `manage_deadline_time` (`18:00`), `manage_deadline_days` (`1`).
- **Manage-Frist-Semantik:** `inherit` delegiert exakt an die bestehende Buchungsfrist-Logik (sowohl `minutes`- als auch `fixed_time`-Verhalten). `minutes` sperrt, sobald der Terminbeginn innerhalb von `manage_deadline_minutes` liegt (0 = nie, also bis zum Beginn möglich). `fixed_time` sperrt, sobald „jetzt" den Zeitpunkt (Tag D−N) um `manage_deadline_time` überschreitet (Provider-Zeitzone). Umbuchen und Stornieren verwenden denselben Check und dieselbe Message.
- **Trennung Buchungs- vs. Manage-Frist:** Die Buchungsfrist sperrt nach dieser Änderung **nur noch Neubuchungen**. Umbuchen/Stornieren werden ausschließlich über die Manage-Frist gesperrt. Da deren Default `inherit` ist, bleibt das heutige Verhalten ohne Konfiguration exakt erhalten.
- **Sperr-Check-Pfad:** Der bestehende Sperr-Check `get_appointment_lock()` erhält einen Aktions-Parameter (`booking`|`manage`); der Manage-Link (Umbuchen) und der Storno-Pfad rufen ihn mit `manage` auf. Die Sperr-Message nennt die konkrete Frist (Datum + Uhrzeit im Firmen-Datumsformat) bzw. die Vorlaufzeit.
- **Slot-Kapazität (Spalte, per nummerierter Migration):** `slot_capacity` (INT, Default `0`) auf der `services`-Tabelle. `0` = unbegrenzt (kein Limit).
- **Kapazitäts-Semantik:** Zählt **Buchungen** (Termine) desselben Services, die sich im Zeitfenster **überlappen**, **über alle Provider** hinweg. Unabhängig von `attendants_number` (das weiterhin Personen-pro-Slot pro Provider bedeutet). Ein Wert N bedeutet: höchstens N parallele Buchungen dieses Services im gesamten Salon.
- **Geltungsbereich Kapazität:** Nur Frontend. Die Verfügbarkeits-Berechnung filtert bei `slot_capacity > 0` überbelegte Slots heraus; damit wirkt es automatisch auf Wizard, Datepicker, API-Verfügbarkeit, „Beliebiger Anbieter" und die serverseitige Buchungsvalidierung (alle konsumieren `get_available_hours()`). Der Backend-Kalender bleibt unverändert.
- **UI:** Manage-Frist als eigener Block in den Buchungs-Einstellungen (`<select>` für den Modus, modusabhängige Felder per JS ein-/ausgeblendet — gleiche Muster wie die bestehende Frist-UI). Kapazität als Zahlenfeld (`min="0"`) im Service-Formular.
- **WhatsApp:** Erst per Playwright die **gerenderte** Größe des schwebenden Buttons messen (Buchungsseite vs. Hauptseite, Breakpoints mobil/desktop), dann `frontend.scss` anpassen und Build neu erzeugen.
- **Sprachdateien:** Neue Keys nur in `german` + `english`; übrige Sprachen fallen auf Englisch zurück.
- **Domänen-Vokabular (Glossar):** **Buchungsfrist** wird auf reine Neubuchung geschärft; neu **Umbuchungs-/Stornierungsfrist** (geteilte Frist für Ändern + Stornieren) und **Slot-Kapazität** (max. parallele Buchungen eines Services über alle Provider).
- **ADR:** Die Frist-Trennung (inherit/Entweder-Oder) und die Kapazitäts-Semantik (pro Service, überlappend, zählt Buchungen) werden als ADR festgehalten.
- **Branches:** Lokal löschen (gemerged + verwaist); keine Remote-Operationen.

## Testing Decisions

- **Primärer Seam (ein einziger):** die öffentliche Verfügbarkeits-Berechnung `get_available_hours()` — Wizard-Slots, Datepicker-Ausgrauung, API v1, „Beliebiger Anbieter" und die serverseitige Buchungsvalidierung konsumieren alle dieselbe Methode. Die Kapazität wirkt ausschließlich dort.
- **Zweiter Seam (unvermeidbar):** der Sperr-Check `get_appointment_lock()` im Umbuch-/Storno-Pfad — der einzige Manage-Frist-Pfad außerhalb der Verfügbarkeits-Berechnung.
- **Dritter Seam (visuell):** die öffentlich gerenderte Buchungsseite — WhatsApp-Button-Größe per Playwright (äußeres Verhalten, keine internen Klassendetails).
- **Was gute Tests ausmacht:** nur äußeres Verhalten prüfen — gegeben Settings, Arbeitsplan, bestehende Termine und „jetzt": welche Stunden werden zurückgegeben bzw. welche Seite erscheint bzw. welche Pixelgröße hat der Button. Keine internen Hilfsmethoden testen.
- **Prior Art:** PHPUnit-Helper-Tests (`tests/Unit/Helper/…`, Bootstrap über `Tests\TestCase`/`get_instance()`) und das HTTP-Smoke-Muster (`tests/smoke/mebeauty-layout-smoke.sh`, curl + grep); Vorgänger-Spec „Booking-Frist, Pufferzeit & Timezone" nutzt dieselben zwei Seams.
- **Kein neues Unit-Test-Setup in dieser Runde:** Für die Verfügbarkeits-Berechnung existiert keine DB-Fixture-Infrastruktur; Kapazität und Manage-Frist werden über eine manuelle Verifikations-Checkliste (alle drei Modi, Kapazität 0/1/N, Überlappung, „Beliebiger Anbieter", Storno-Lock, API) plus `composer test` und `npm run build` abgesichert. Ein Folge-Ticket kann Availability-Unit-Tests mit DB-Fixtures aufbauen.

## Out of Scope

- Durchsetzung von Frist/Kapazität im Backend-Kalender (Admins behalten Override-Freiheit).
- Eine **gemeinsame Ressource**, die über mehrere Services hinweg geteilt wird (z. B. eine Maschine für verschiedene Services) — bewusst auf „pro Service" reduziert.
- Kapazität als Summe der Teilnehmer statt als Anzahl Buchungen.
- Getrennte (unabhängige) Fristen für Umbuchen und Stornieren — bewusst eine geteilte Frist.
- Übersetzungen über Deutsch und Englisch hinaus.
- Automatisierte Unit-Tests der Verfügbarkeits-Berechnung (Folge-Ticket).
- Kombination von Minuten-Vorlaufzeit und fester Uhrzeit (Entweder/Oder bleibt).

## Further Notes

- Neueste Migration ist `071_…`; die neuen Migrationen tragen die Nummern 072 (Manage-Frist-Settings) und 073 (`slot_capacity`-Spalte). (Nach der Bereinigung der alten Theme-Migrationen, siehe ADR 0005.)
- JS-Quellen haben `.min.js`-Gegenstücke — nach Änderungen muss `npm run build` laufen.
- Die WhatsApp-Prüfung erfordert eine laufende Umgebung (`docker compose up -d --build` bzw. `ddev start mebeauty`); Playwright ist nicht installiert und wird als Wegwerf-Skript unter `/tmp` angelegt.
- Branch-Löschliste: `agent/google-calendar-push-sync`, `integration/booking-deadline-buffer-timezone`, `integration/branding`, `ticket/01-add-deadline-buffer-timezone-settings`, `ticket/02-fixed-time-deadline-availability`, `ticket/03-deadline-reschedule-cancel-lock`, `ticket/04-buffer-time-availability`, `ticket/05-business-settings-ui`, `ticket/06-timezone-display-booking-wizard`, `ticket/07-language-glossary-adr`, `ticket/fix-code-review`.
- Gemäß AGENTS.md: keine Git-Commits ohne ausdrückliche Nutzer-Freigabe; Task-Branch anlegen.
- Alle Details wurden in einer Grilling-Session mit dem Nutzer abgestimmt; es bestehen keine offenen Designfragen.
