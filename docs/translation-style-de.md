# German Translation Style Guide

Reference for every German text in `application/language/german/translations_lang.php`, the email templates and
the theme `site_*` keys. Terms follow [`GLOSSARY.md`](../GLOSSARY.md).

## Principles

- **"Sie" everywhere.** Booking flow, emails, backend and theme texts (WhatsApp button, site chrome) address the
  reader with "Sie". Never "du"/"dich".
  Example: "Schreiben Sie uns auf WhatsApp", not "Chatte mit uns auf WhatsApp".
- **Function over word-for-word.** Translate what the screen does, not the English key. Read the view that uses the
  key before wording it. Example: a cancellation reason prompt shown to the customer is
  "Möchten Sie uns einen Grund nennen? (optional)", not "Bitte geben Sie den Grund für das Löschen des Termins an".
- **Natural German.** Short sentences, active voice, no mechanical noun chains, no anglicisms where a common German
  word exists ("Service" → "Dienstleistung").
- **Industry-neutral core.** The core texts contain no beauty vocabulary ("Behandlung", "Kosmetikerin", "Salon").
  Industry wording comes from the data (service names, categories) and the theme `site_*` keys.
- **Hint and button agree.** A hint that names a button uses the button's exact label.

## Terms

| English (upstream)  | German                                                         | Avoid                          |
|---------------------|----------------------------------------------------------------|--------------------------------|
| provider            | Mitarbeiterin / Mitarbeiter; plural and lists: Mitarbeitende   | Anbieter, Dienstleister        |
| secretary           | Assistenz                                                      | Sekretär, Bearbeiter           |
| service             | Dienstleistung                                                 | Service                        |
| customer            | Kundin / Kunde; plural: Kundinnen und Kunden or Kunden         | Klient                         |
| appointment         | Termin                                                         | Buchung (for the appointment)  |
| reschedule          | umbuchen / Umbuchung                                           | ändern, verschieben (as term)  |
| cancel (appointment)| stornieren / Stornierung                                       | abbrechen, löschen             |
| book                | buchen                                                         | reservieren                    |

In the booking flow the person is chosen by a question, not by a job title: "Bei wem möchten Sie buchen?" with
the option "Egal bei wem" (not "Jeder Anbieter").

## Verbs

- **buchen, umbuchen, stornieren** for what the customer does with an appointment.
- **abbrechen** only for leaving a dialog or process without saving ("Abbrechen" on a modal).
- **löschen** only for removing records in the backend or deleting personal data, never for a customer's
  cancellation.

## Own keys instead of rewording shared ones

Upstream reuses some keys for different functions (e.g. `cancel` for "close dialog" and for the cancellation
context; `delete` for "delete personal data" and "cancel my appointment"). When a shared key cannot be worded
correctly for one screen:

1. Add a fork key (descriptive name, e.g. `cancel_appointment_button`) to **both** `german` and `english`
   `translations_lang.php`; other languages fall back to English via `lang()`.
2. Use the new key only on that screen; the upstream key keeps its text for all other places.

`tests/Unit/TranslationKeyParityTest.php` fails when a key exists in only one of the two languages.

English is changed only where it is functionally wrong (e.g. a "Cancel" hint next to a "Delete" button), not for
style.
