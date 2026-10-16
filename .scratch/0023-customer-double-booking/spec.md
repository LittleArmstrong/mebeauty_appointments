# Customer double booking: catch every overlap, tell the customer clearly

Status: ready-for-agent
Created: 2026-10-16
Closed: –
Priority: P1
Depends-on: 0018

## Problem Statement

A customer may not hold two appointments at the same time. The booking form only rejects a new appointment that
lies completely inside an existing one of the same customer: the query requires the existing start at or before
the new start and the existing end at or after the new end (`application/controllers/Booking.php:494-505`). The
confirmation of a Vormerkung repeats the same containment query (`application/libraries/Email_verification.php:629-636`).
A customer who already has 10:00–11:00 can therefore book 09:30–10:30 or 10:30–11:30 with another provider
("any provider") without any message.

When the containment case does hit, the customer sees an upstream dialog meant for unexpected errors:

- the title is the hard-coded product name "Easy!Appointments";
- the heading says that an unexpected problem occurred;
- below it comes the raw server message (`assets/js/app.js:33-43`): "Dieser Kunde hat bereits einen Termin während
  des angefragten Reservierungszeitraums." (`application/language/german/translations_lang.php:553`).

The message talks about "this customer" in back-office language, although the customer is reading it. Found in
the manual test on 2026-10-16.

## Solution

A booking is rejected whenever the customer already has an appointment that overlaps the requested time by any
amount. Back-to-back appointments stay possible. The customer sees a calm dialog with a fitting title and a text
addressed to them, telling them to choose another time. Unexpected errors keep their generic dialog, but it shows
the company name instead of "Easy!Appointments".

## User Stories

1. As a customer, I want the booking to be refused when it overlaps my existing appointment even partly, so that I
   do not end up with two appointments at once.
2. As a customer, I want to book an appointment that starts exactly when my other one ends, so that I can combine
   treatments back to back.
3. As a customer, I want to book an appointment that ends exactly when my other one starts, so that back-to-back
   works in both directions.
4. As a customer choosing "any provider", I want the overlap check to apply regardless of the provider, so that a
   free colleague does not let me double-book myself.
5. As a customer rescheduling via the Manage-Link, I want my own appointment being moved not to count as a
   conflict, so that I can shift it by a few minutes.
6. As a customer rescheduling via the Manage-Link, I want a move onto another of my appointments to be refused, so
   that rescheduling cannot create a double booking.
7. As a customer confirming a Vormerkung by email, I want the same overlap rule to apply at confirmation, so that a
   double booking cannot slip through the email link.
8. As a customer whose Vormerkung overlaps, I want the existing neutral page of the email confirmation, so that
   the confirmation does not reveal more than before (0018).
9. As a customer, I want the refusal dialog to have a title that says what happened (e.g. "Termin nicht möglich"),
   so that I immediately understand it is about my booking.
10. As a customer, I want the text to address me directly and tell me what to do ("Sie haben zu dieser Zeit bereits
    einen Termin bei uns. Bitte wählen Sie eine andere Uhrzeit."), so that I can continue on my own.
11. As a customer, I want no "unexpected problem" wording for an expected refusal, so that I do not think the page
    is broken.
12. As a customer, I want the same refusal dialog when my chosen time was taken in the meantime, so that all
    expected refusals look alike.
13. As a customer using the English booking page, I want the same title and text in English, so that I understand
    it in my language.
14. As a customer, I want the dialog to keep my entered data, so that I can go back and pick another time without
    retyping.
15. As the salon owner, I want unexpected errors to show the company name as title instead of "Easy!Appointments",
    so that the page stays on brand even when something breaks.
16. As staff in the backend, I want unexpected error dialogs titled with the company name as well, so that the
    product name of the upstream project never appears.
17. As the salon owner, I want staff to keep booking overlapping appointments for a customer in the backend
    calendar, so that special cases stay possible.

## Implementation Decisions

- **Overlap rule.** Two appointments overlap when the existing one starts before the requested end and ends after
  the requested start (strict comparisons, so touching edges do not overlap). The rule applies:
  - only to appointments of the same customer;
  - never to unavailabilities;
  - excluding the appointment being rescheduled.
- **One shared check.** The booking form and the Vormerkung confirmation call one fork-owned check instead of two
  copies of the query (upstream rule, ADR 0005). The upstream controller only calls it.
- **Where it applies.** Online booking, Manage-Link reschedule and Vormerkung confirmation. Backend calendar and
  API v1 are unchanged.
- **Vormerkung.** The order stays as in 0018: with email verification the check runs only at confirmation, and an
  overlap leads to the existing neutral page.
- **Expected refusals.**
  - The server marks expected booking refusals so the booking page can tell them from unexpected errors: the
    customer overlap, and the requested hour no longer available.
  - The booking page shows them in the existing message dialog with a fitting title ("Termin nicht möglich" /
    "Appointment not possible") and the plain text, without the generic heading and card.
  - The wizard keeps all entered data.
- **Wording.** The customer overlap text becomes "Sie haben zu dieser Zeit bereits einen Termin bei uns. Bitte
  wählen Sie eine andere Uhrzeit." / "You already have an appointment with us at this time. Please choose another
  time." in German and English; other languages keep the upstream text.
- **Unexpected errors.** The generic dialog title becomes the company name, as the page already shows it. The
  heading and the card with the server message stay.

## Testing Decisions

- A good test drives the real HTTP booking endpoint and checks the response and the stored appointments, not
  internal calls.
- **Booking form.** HTTP smoke cases on the existing booking smoke fixtures. Prior art: `tests/smoke/manage-link-smoke.sh`
  and `tests/smoke/appointment-contacts-smoke.sh`. Cases:
  - partial overlap at the start → refused, marked as expected;
  - partial overlap at the end → refused;
  - containment both ways → refused;
  - back-to-back before and after → booked;
  - other provider ("any provider") → refused;
  - Manage-Link shift of the own appointment by a few minutes → allowed;
  - Manage-Link move onto another own appointment → refused.
- **Vormerkung confirmation.** A partial overlap at confirmation leads to the neutral page and books nothing.
  Prior art: `tests/smoke/email-verification-smoke.sh`.
- **Red evidence.** The partial-overlap cases must fail against the current containment query.
- **Dialogs.** Checked visually by the user, using new manual test cases in `docs/manual-tests.md`: the expected
  refusal dialog in German and English, and an unexpected error showing the company name.

## Out of Scope

- Overlap rules for staff bookings in the backend and for API v1.
- Changing the availability calculation (which slots are offered).
- Timezone configuration of the salon and the timezone dropdown (separate question).
- Hiding from the booking form whether an email has appointments when email verification is off.

## Further Notes

- Origin: manual test on 2026-10-16. The refusal was triggered by a timezone shift: provider and company in UTC
  locally, display in Europe/Berlin. The partial-overlap gap was found while analysing it.
- Glossary: Booking-Flow, Manage-Link, Umbuchung, Vormerkung, E-Mail-Bestätigung.
