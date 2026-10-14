# Issue tracker: Local Markdown

Issues and specs for this repo live as markdown files in `.scratch/`.

## Conventions

- One feature per directory: `.scratch/<NNNN>-<feature-slug>/`. `NNNN` is a four-digit, gapless, never-reused sequence number (like `docs/adr/`); a new spec takes the next free number.
- The spec is `.scratch/<NNNN>-<feature-slug>/spec.md`
- Implementation issues are one file per ticket at `.scratch/<NNNN>-<feature-slug>/issues/<NN>-<slug>.md`, numbered from `01`, never a single combined tickets file
- Triage state is recorded as a `Status:` line near the top of each issue file (see `triage-labels.md` for the role strings)
- Comments and conversation history append to the bottom of the file under a `## Comments` heading
- Every statement about current behaviour in a spec's problem section cites its evidence as `path:line` (e.g. `application/controllers/Booking.php:489`), checked against the code before the spec is published.
- Cross-references: a spec is referenced by its number (`0009`), a ticket in another spec as `NNNN/NN` (e.g. `0009/02`), a ticket in the same spec by `NN`.

## Spec header

Every `spec.md` starts with the title followed directly by these lines (one `Key: value` per line, no blank lines in between):

```text
# <Title>

Status: ready-for-agent
Created: 2026-10-11
Closed: –
Priority: P2
Depends-on: 0009, 0012
```

| Key | Required | Values |
|---|---|---|
| `Status` | yes | a value from `triage-labels.md` (triage labels plus `in-progress`, `needs-verification`, `resolved`) |
| `Created` | yes | `YYYY-MM-DD`, date of the spec's first commit |
| `Closed` | yes | `YYYY-MM-DD` when `resolved`/`wontfix`, otherwise `–` |
| `Priority` | yes | `P1` (security/correctness), `P2` (functionality/maintainability), `P3` (clean-up); `–` allowed for closed specs |
| `Depends-on` | yes | comma-separated spec numbers, or `–` |

A spec may only be `resolved` when none of its tickets is open.

## Index

`.scratch/INDEX.md` lists all specs (open and closed) with status, priority, dates, dependencies and ticket counts. It is **generated** — never edit it by hand:

```bash
docker compose exec -T php-fpm composer scratch:index   # regenerate INDEX.md
docker compose exec -T php-fpm composer check:scratch   # validate headers, numbering, INDEX (also runs in CI)
```

Regenerate the index whenever a spec header or a ticket status changes.

## When a skill says "publish to the issue tracker"

Create a new directory `.scratch/<NNNN>-<feature-slug>/` with the next free number (creating it if needed), write the spec with the header above, then regenerate the index.

## When a skill says "fetch the relevant ticket"

Read the file at the referenced path. The user will normally pass the path or the issue number directly.

## Wayfinding operations

Used by `/wayfinder`. The **map** is a file with one **child** file per ticket.

- **Map**: `.scratch/<NNNN>-<effort>/map.md` (the Notes / Decisions-so-far / Fog body). It carries the same header as a spec (see *Spec header*) and is listed in the index like one.
- **Child ticket**: `.scratch/<NNNN>-<effort>/issues/NN-<slug>.md`, numbered from `01`, with the question in the body. A `Type:` line records the ticket type (`research`/`prototype`/`grilling`/`task`); a `Status:` line records `claimed`/`resolved` (`claimed` counts as open in the index).
- **Blocking**: a `Blocked by: NN, NN` line near the top. A ticket is unblocked when every file it lists is `resolved`.
- **Frontier**: scan `.scratch/<NNNN>-<effort>/issues/` for files that are open, unblocked, and unclaimed; first by number wins.
- **Claim**: set `Status: claimed` and save before any work.
- **Resolve**: append the answer under an `## Answer` heading, set `Status: resolved`, then append a context pointer (gist + link) to the map's Decisions-so-far in `map.md`.
