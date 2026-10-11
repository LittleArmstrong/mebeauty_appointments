# Nachvollziehbares lokales Tracking: Nummern, Kopfzeilen, INDEX

Status: in-progress
Created: 2026-10-11
Closed: –
Priority: P2
Depends-on: –

## Problem Statement

Specs und Tickets liegen als Markdown unter `.scratch/<slug>/`. Der Dateimanager sortiert die Ordner alphabetisch. Daraus lässt sich nicht ablesen:

- wann eine Spec angelegt wurde und in welcher Reihenfolge die Specs entstanden sind,
- welche Specs offen und welche abgeschlossen sind,
- welche Spec zuerst umgesetzt werden soll und welche von welcher abhängt.

Die vorhandenen Status-Angaben sind uneinheitlich und teilweise falsch:
- Abgeschlossene Specs tragen `done`, `resolved`, `ready-for-agent` oder `in-progress`.
- `0005-mebeauty-dynamic-branding` steht mit sieben Tickets auf offen, obwohl der zugehörige Commit existiert.
- `0002-phpunit-cli-bootstrap` hat gar keine Spec-Datei.
- Bei mehreren Specs sind alle Tickets erledigt, die Spec selbst steht aber auf `ready-for-agent`.

Niemand prüft die Angaben, sie veralten also unbemerkt.

## Solution

- **Fortlaufende Nummer** vor jedem Spec-Ordner, vierstellig wie bei den ADRs: `.scratch/0009-manage-lock-enforcement/`. Bestehende Specs werden rückwirkend nach dem Datum ihres ersten Commits nummeriert.
- **Einheitliche Kopfzeilen** in jeder Spec: Status, Created, Closed, Priority, Depends-on.
- **Generierte Übersicht** `.scratch/INDEX.md`: Ein PHP-Skript erzeugt sie aus den Kopfzeilen und dem Ticket-Status, getrennt nach offen und abgeschlossen. Ein Composer-Befehl erzeugt sie, ein zweiter prüft sie, und die CI schlägt fehl, wenn Kopfzeilen fehlen, ungültig sind oder der INDEX veraltet ist.
- Der Altbestand wird geprüft und auf den tatsächlichen Stand gesetzt.

## User Stories

1. Als Projektverantwortlicher will ich die Specs in der Reihenfolge ihrer Entstehung sehen, damit ich den Verlauf nachvollziehen kann.
2. Als Projektverantwortlicher will ich auf einen Blick sehen, welche Specs offen und welche abgeschlossen sind.
3. Als Projektverantwortlicher will ich je Spec sehen, wann sie angelegt und wann sie abgeschlossen wurde.
4. Als Projektverantwortlicher will ich die Priorität offener Specs sehen, damit ich entscheiden kann, was als Nächstes umgesetzt wird.
5. Als Projektverantwortlicher will ich sehen, welche Spec auf welche andere wartet.
6. Als Projektverantwortlicher will ich je Spec sehen, wie viele Tickets offen und wie viele erledigt sind.
7. Als Agent will ich eine Spec über eine kurze, eindeutige Nummer referenzieren können.
8. Als Agent will ich beim Anlegen einer Spec wissen, welche Nummer und welche Kopfzeilen sie bekommt, damit ich das Schema einhalte.
9. Als Agent will ich, dass die Skills (`/to-spec`, `/to-tickets`, `/implement-spec`, `/triage`) das Schema über die Tracker-Doku kennen.
10. Als Entwickler will ich, dass die CI eine Spec mit fehlenden oder ungültigen Kopfzeilen meldet.
11. Als Entwickler will ich, dass die CI einen veralteten INDEX meldet, damit die Übersicht nie lügt.
12. Als Entwickler will ich den INDEX mit einem Befehl neu erzeugen können.
13. Als Projektverantwortlicher will ich, dass der Status alter Specs dem tatsächlichen Umsetzungsstand entspricht.
14. Als Projektverantwortlicher will ich, dass nach dem Umbenennen alle Querverweise weiter funktionieren.

## Implementation Decisions

- **Ordnername:** `NNNN-<slug>`, vierstellig, fortlaufend, nie wiederverwendet. Neue Specs bekommen die nächste freie Nummer.
- **Backfill:** Nummern nach dem Zeitpunkt des ersten Commits des Ordners. Specs aus demselben Commit werden nach der empfohlenen Umsetzungsreihenfolge nummeriert. Daraus ergibt sich:

  | Nr. | Spec | Created |
  |---|---|---|
  | 0001 | mebeauty-layout | 2026-09-30 |
  | 0002 | phpunit-cli-bootstrap | 2026-09-30 |
  | 0003 | booking-deadline-buffer-timezone | 2026-10-01 |
  | 0004 | branding | 2026-10-02 |
  | 0005 | mebeauty-dynamic-branding | 2026-10-03 |
  | 0006 | manage-deadline-slot-capacity | 2026-10-04 |
  | 0007 | consolidate-migrations | 2026-10-10 |
  | 0008 | repo-tooling | 2026-10-11 |
  | 0009 | manage-lock-enforcement | 2026-10-11 |
  | 0010 | input-hardening | 2026-10-11 |
  | 0011 | upstream-diff-hygiene | 2026-10-11 |
  | 0012 | buffer-end-of-day | 2026-10-11 |
  | 0013 | booking-policy-extraction | 2026-10-11 |
  | 0014 | theme-site-chrome | 2026-10-11 |
  | 0015 | scratch-tracking (diese Spec) | 2026-10-11 |

- **Kopfzeilen** direkt unter dem Titel, je eine Zeile `Schlüssel: Wert`, im Format der bestehenden `Status:`-Zeilen, damit es mit den Skills kompatibel bleibt:
  - `Status:` (Pflicht) ∈ {needs-triage, needs-info, ready-for-agent, ready-for-human, in-progress, resolved, wontfix}. `done` entfällt.
  - `Created:` (Pflicht) als `YYYY-MM-DD`, Datum des ersten Commits.
  - `Closed:` `YYYY-MM-DD` bei resolved/wontfix, sonst `–`.
  - `Priority:` P1 (Sicherheit/Korrektheit), P2 (Wartbarkeit/Funktion), P3 (Aufräumen); bei abgeschlossenen Specs optional (`–`).
  - `Depends-on:` kommagetrennte Spec-Nummern oder `–`.
- **Tickets** behalten ihr Format (`**Status:**`, `**Blocked by:**`). Ticket-Verweise über Specs hinweg lauten `NNNN/NN`, z. B. `0009/02`.
- **Konsistenzregeln** (vom Prüfbefehl erzwungen):
  - Pflichtfelder vorhanden und Werte gültig.
  - Ordnernummern eindeutig und lückenlos.
  - `Depends-on` verweist nur auf existierende Specs.
  - Eine Spec mit Status resolved hat keine offenen Tickets.
  - Der INDEX entspricht der generierten Fassung.
- **INDEX.md:** zwei Abschnitte (offen, abgeschlossen), je eine Tabelle mit Nummer (verlinkt), Titel, Status, Priorität, Created/Closed, Depends-on und „Tickets offen/gesamt“. Offene Specs sind nach Priorität und Nummer sortiert, abgeschlossene nach Nummer. Kopfzeile: „generiert, nicht von Hand bearbeiten“.
- **Werkzeug:** ein PHP-Skript im Stil der vorhandenen Validatoren unter `scripts/`, mit einem Modus zum Erzeugen und einem zum Prüfen. Dazu zwei Composer-Befehle nach dem Muster der vorhandenen `check:*`-Befehle und ein CI-Schritt im Testjob. Die Parse- und Prüflogik ist eine reine, per Unit-Test prüfbare Klasse bzw. Funktion (Vorbild: Migrations- und Asset-Validator samt Tests).
- **Doku:** Die Tracker-Doku der Agenten beschreibt Nummern, Kopfzeilen, Status-Werte, Verweise und die Befehle. Die Triage-Label-Doku nennt die Lebenszyklus-Werte in-progress und resolved. AGENTS.md verweist auf INDEX und Prüfbefehl.
- **Querverweise:** Beim Umbenennen werden alle Verweise auf alte Ordnernamen in `.scratch/`, `docs/`, `AGENTS.md`, `CODING_STANDARDS.md` und Skills angepasst. Historische Commit-Nachrichten bleiben unverändert.

## Testing Decisions

- Unit-Tests für die Parse- und Prüflogik mit Beispiel-Specs als Fixtures:
  - gültige Spec
  - fehlender Status
  - unbekannter Status
  - ungültiges Datum
  - Depends-on auf eine nicht existierende Spec
  - resolved mit offenem Ticket
  - doppelte Nummer
  - veralteter INDEX
- Vorbild: die bestehenden Unit-Tests der Migrations- und Asset-Validatoren.
- Integrationsprobe: Der Prüfbefehl läuft auf dem echten `.scratch/` grün. Eine absichtlich entfernte Kopfzeile lässt ihn fehlschlagen.
- Nach dem Umbenennen findet `grep` keine Verweise auf alte Ordnernamen mehr (außer in der Git-Historie).

## Out of Scope

- Ein externes Werkzeug (Backlog.md, GitHub Issues) oder ein Kanban-Board.
- Zeitstempel oder Felder für Tickets über Status und Blocked-by hinaus.
- Automatisches Setzen von Closed bzw. Status beim Commit.
- Anpassungen an den global installierten Skills selbst (sie lesen das Schema aus der Projekt-Doku).

## Further Notes

- Bezug: Tracker-Doku und Triage-Label-Doku unter `docs/agents/`, ADR-Nummerierung als Vorbild.
- Diese Spec benennt sich bei der Umsetzung selbst in `0015-scratch-tracking` um.
- Der Prüfbefehl ergänzt die bestehenden Repo-Guardrails (Migrationen, Asset-Referenzen).
