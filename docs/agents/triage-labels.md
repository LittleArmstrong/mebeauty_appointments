# Triage Labels

The skills speak in terms of five canonical triage roles. This file maps those roles to the actual label strings used in this repo's issue tracker.

| Label in mattpocock/skills | Label in our tracker | Meaning                                  |
| -------------------------- | -------------------- | ---------------------------------------- |
| `needs-triage`             | `needs-triage`       | Maintainer needs to evaluate this issue  |
| `needs-info`               | `needs-info`         | Waiting on reporter for more information |
| `ready-for-agent`          | `ready-for-agent`    | Fully specified, ready for an AFK agent  |
| `ready-for-human`          | `ready-for-human`    | Requires human implementation            |
| `wontfix`                  | `wontfix`            | Will not be actioned                     |

Besides the triage labels, specs and tickets use three lifecycle values:

| Value                | Meaning                                                                    |
| -------------------- | -------------------------------------------------------------------------- |
| `in-progress`        | Work has started but is not finished                                       |
| `needs-verification` | Implemented and automatically validated; a manual check by the user is open |
| `resolved`           | Implemented (or answered) and verified                                     |

A ticket whose acceptance criteria include a manual check by the user (visual check, Google Calendar, …)
ends the agent's work as `needs-verification`, with the open check named in its comment; its spec stays
`needs-verification` too. The user sets both to `resolved` after the check.

No other values (e.g. `done`) are allowed; `composer check:scratch` rejects them for specs.

When a skill mentions a role (e.g. "apply the AFK-ready triage label"), use the corresponding label string from this table.

Edit the right-hand column to match whatever vocabulary you actually use.
