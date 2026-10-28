# German translation revision: natural, function-based wording

Status: needs-verification
Created: 2026-10-28
Closed: –
Priority: P2
Depends-on: –

## Problem Statement

The German translation is largely the upstream community translation. It is industry-neutral, but often a
word-for-word rendering of the English key instead of what the screen actually does, and it is inconsistent:

- The Manage-Link banner tells the customer to click "Abbrechen" to remove the appointment
  (`application/language/german/translations_lang.php:30`), but the button next to it is labelled "Löschen"
  (`application/views/components/booking_cancellation_frame.php:24`, key `delete`,
  `application/language/german/translations_lang.php:250`). The English hint has the same mismatch
  (`application/language/english/translations_lang.php:30`). For the customer the action is a cancellation
  ("stornieren"), not a deletion.
- The cancellation dialog asks for "den Grund für das Löschen des Termins"
  (`application/language/german/translations_lang.php:111`), back-office wording shown to the customer.
- Upstream reuses one key for different functions, e.g. `cancel` for "close this dialog" and the cancellation
  context, and `delete` for both "delete personal data" and "cancel my appointment"
  (`application/views/components/booking_cancellation_frame.php:24,37`). Such places cannot be translated
  naturally without a separate key.
- Staff are called "Anbieter" (24×) and "Dienstleister" (5×) in the same file, e.g.
  `application/language/german/translations_lang.php:6,47,310`; the booking step offers "Jeder Anbieter"
  (`:310`). The secretary role is "Bearbeiter" (`:131`) and "Sekretär Save" (`:454`); services are
  "Dienstleistung" and "Service" (`:448`).
- The core addresses the reader with "Sie", while the theme says "Chatte mit uns auf WhatsApp"
  (`application/language/german/translations_lang.php:665`). The mebeauty-Site uses "Sie" in all neutral texts.
- All email templates declare `<html lang="en">` regardless of the language they are rendered in
  (`application/views/emails/appointment_saved_email.php:30`, `appointment_deleted_email.php:23`,
  `email_verification_email.php:26`).

The repository is public and GPL-3.0 licensed (`LICENSE:1-2`), but it also contains MeBeauty brand assets
(`assets/img/mebeauty/logo.png`) and MeBeauty texts without any note that these are not covered by the GPL.

## Solution

German texts are rewritten per screen according to their function, so they read like a native German booking
page rather than a translation. The core stays industry-neutral (no beauty vocabulary; that comes from the data and
the theme), uses "Sie" everywhere and the terms in `GLOSSARY.md`. Where one upstream key serves different
functions, the fork introduces its own key. English texts are corrected where they are wrong in the same way.
A `NOTICE` file states that brand assets and MeBeauty texts are not GPL-licensed.

## User Stories

1. As a customer, I want the booking steps to read naturally in German, so that the page feels made for me.
2. As a customer, I want to be asked "Bei wem möchten Sie buchen?" with an option "Egal bei wem", so that I choose
   a person without a job title I do not know.
3. As a customer, I want the Manage-Link page to say "stornieren" and the button to match the hint, so that I know
   which button cancels my appointment.
4. As a customer, I want the cancellation reason prompt addressed to me and optional in tone, so that I do not feel
   interrogated.
5. As a customer, I want the confirmation, change, cancellation and verification emails to read naturally and use
   "Sie", so that they match the website.
6. As a customer, I want the WhatsApp button and the site chrome texts to use "Sie", so that the tone is the same
   everywhere.
7. As staff, I want the backend to call providers "Mitarbeiterin/Mitarbeiter" (list: "Mitarbeitende") and
   secretaries "Assistenz", so that the roles match our salon.
8. As staff, I want backend messages without leftovers like "Sekretär Save", so that the backend looks finished.
9. As an English-speaking customer, I want the English texts that had the same functional errors fixed too, so
   that both languages describe the same buttons.
10. As the owner of a public repository, I want a `NOTICE` that logo, brand name and MeBeauty texts are not under
    the GPL, so that publishing the code does not license the brand.

## Implementation Decisions

- **Style guide first.** A short German style guide in `docs/` (tone "Sie", glossary terms, no beauty vocabulary
  in the core, function over word-for-word, preferred verbs such as "stornieren", "umbuchen", "buchen") is the
  reference for every later ticket.
- **Terms.** As in `GLOSSARY.md`: provider → "Mitarbeiterin/Mitarbeiter", plural "Mitarbeitende"; secretary →
  "Assistenz"; service → "Dienstleistung". In the booking flow the person is addressed by a question instead of a
  title ("Bei wem möchten Sie buchen?", "Egal bei wem").
- **Own keys for overloaded upstream keys.** Where a shared key (`cancel`, `delete`, …) cannot be worded right for
  one screen, the fork adds a new key in German and English and uses it only there; upstream keys keep their text
  for all other places. Other languages fall back to English via `lang()`.
- **English.** Only corrected where the English text is functionally wrong (e.g. the "Cancel" hint next to a
  "Delete" button); no stylistic rewrite.
- **Order.** Booking flow → emails → Manage-Link page → backend, one ticket each, so the customer-facing part can
  be verified and deployed first.
- **Email `lang` attribute.** Email templates declare the rendering language instead of a fixed `en`.
- **No core beauty vocabulary.** Beauty wording stays in the theme (`site_*` keys) and in the data (service names,
  categories).
- **NOTICE.** Root `NOTICE` file; README links to it. Code stays GPL-3.0.

## Testing Decisions

- Wording is checked visually by the user, screen by screen, using new manual test cases in
  `docs/manual-tests.md`.
- A PHPUnit test asserts that the German and English `translations_lang.php` define the same set of keys, so a new
  fork key cannot be missing in one of them. It goes red when a ticket adds a key to only one language.
- Pure text changes record `Rot vor Fix: entfällt – text-only change, checked visually`.

## Out of Scope

- Site-Chrome structure (navbar, footer, slim header): parked in `.scratch/IDEAS.md`.
- Moving the `site_*` keys into a theme-owned language file (part of the Site-Chrome idea).
- Languages other than German and English.
- Beauty-specific wording in the core.
