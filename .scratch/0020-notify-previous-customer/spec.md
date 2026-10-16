# Notify the previous customer when an appointment is reassigned

Status: needs-triage
Created: 2026-10-16
Closed: –
Priority: P3
Depends-on: –

## Problem Statement

When staff assign an existing appointment to a different customer in the backend appointment dialog, the save
handler loads only the appointment's new customer (`application/controllers/Calendar.php:410`) and sends the
"saved" notifications to that customer, provider, admins and secretaries
(`application/controllers/Calendar.php:427-436`). The previous customer is not notified. They still hold the booking
confirmation and assume the appointment is theirs.

The new customer gets "Appointment details have changed" (manage mode) instead of a booking confirmation.

Found during the manual test T-B-02 (2026-10-16).

## Proposal (to be triaged)

When the customer of an appointment changes in the backend, the previous customer receives a cancellation mail
(same as when the appointment is deleted), and the new customer receives a booking confirmation instead of a
"details changed" mail.

## Open Questions

- Is reassigning used to correct a wrongly selected customer (who may never have read the first mail), or to hand
  an appointment over to someone else? This decides whether the cancellation mail helps or confuses.
- Does the same apply to customer changes made through API v1?
