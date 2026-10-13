<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Appointment contact rules library (fork, spec 0017, ADR 0008).
 *
 * Pure functions without database access that decide which customer a booking belongs to, what is written to the
 * customer record and which "Termin-Kontaktdaten" an appointment keeps. Controllers collect the stored data, call
 * these rules and persist the result. See the glossary terms "Termin-Kontaktdaten", "Manage-Link" and "Umbuchung".
 *
 * Rules:
 * - A new booking belongs to the customer with the entered email (or a new customer). A reschedule via manage link
 *   stays with the customer of the original appointment; the email is not matched.
 * - A new customer is created with all fields. An existing customer only takes over non-empty fields; a changed
 *   email only when no other customer has it.
 * - The appointment contact is the entered first name, last name, email and phone number.
 * - In the backend only contact fields that differ from the stored appointment contact are changed.
 * - The Google event description starts with a contact block that is removed again when reading the event back.
 *
 * @package Libraries
 */
class Appointment_contact_rules
{
    /**
     * Fields of the appointment contact.
     */
    public const FIELDS = ['first_name', 'last_name', 'email', 'phone_number'];

    /**
     * First and last line of the contact block in the Google event description. The block texts are fixed German
     * literals on purpose (not translated): they must not depend on the request language, because the block is
     * recognized and removed again when the event is read back from Google.
     */
    public const BLOCK_START = '--- Kontakt ---';

    public const BLOCK_END = '---';

    /**
     * Resolve the customer of an online booking.
     *
     * @param array|null $original_appointment Stored appointment of a reschedule (manage link), null for a new booking.
     * @param int $email_customer_id ID of the customer with the entered email, 0 when there is none.
     *
     * @return int|null Returns the customer ID or null when a new customer must be created.
     */
    public function resolve_customer_id(?array $original_appointment, int $email_customer_id): ?int
    {
        if ($original_appointment !== null) {
            return (int) $original_appointment['id_users_customer'];
        }

        return $email_customer_id ?: null;
    }

    /**
     * Build the customer record to save for a booking.
     *
     * @param array $input Customer fields entered in the booking form (incl. language and timezone).
     * @param array|null $existing Stored customer record, null for a new customer.
     * @param int|null $email_owner_id ID of the customer that already has the entered email (null when the email is
     * free); only relevant when the email differs from the stored one.
     *
     * @return array Returns the complete record to save (with "id" for an existing customer).
     */
    public function customer_record(array $input, ?array $existing, ?int $email_owner_id = null): array
    {
        if ($existing === null) {
            return $input;
        }

        $record = $existing;

        foreach ($input as $field => $value) {
            if ($field === 'id' || $this->is_empty($value)) {
                continue;
            }

            if (
                $field === 'email' &&
                $email_owner_id !== null &&
                $email_owner_id !== (int) $existing['id'] &&
                strcasecmp((string) $value, (string) ($existing['email'] ?? '')) !== 0
            ) {
                continue;
            }

            $record[$field] = $value;
        }

        return $record;
    }

    /**
     * Build the appointment contact from the entered customer fields.
     *
     * @param array $input Customer fields.
     *
     * @return array Returns the four contact fields; missing or empty values are null.
     */
    public function contact(array $input): array
    {
        $contact = [];

        foreach (self::FIELDS as $field) {
            $value = trim((string) ($input[$field] ?? ''));
            $contact[$field] = $value === '' ? null : $value;
        }

        return $contact;
    }

    /**
     * Get the contact fields that the backend input changes compared to the stored appointment contact.
     *
     * @param array $stored Stored appointment contact (or the customer record for appointments without contact).
     * @param array $input Customer fields sent by the appointment dialog.
     *
     * @return array Returns the changed fields (field => new value, null for a cleared field).
     */
    public function changed_fields(array $stored, array $input): array
    {
        $stored = $this->contact($stored);
        $changed = [];

        foreach ($this->contact($input) as $field => $value) {
            if (array_key_exists($field, $input) && $value !== $stored[$field]) {
                $changed[$field] = $value;
            }
        }

        return $changed;
    }

    /**
     * Decide what an appointment dialog save in the backend writes.
     *
     * Editing the customer of an existing appointment changes only the contact fields that differ from the stored
     * appointment contact (or, without one, from the customer record); those changed fields also go to the record.
     * In every other case (new appointment, other customer selected) the dialog input becomes the appointment contact
     * and is saved to the customer record as entered.
     *
     * @param array $input Customer fields sent by the appointment dialog.
     * @param array|null $record Customer record of the appointment being edited, null for a new appointment or when
     * another customer is selected.
     * @param array|null $stored_contact Stored appointment contact, null when the appointment has none.
     *
     * @return array Returns ['customer' => fields to save to the record, 'contact' => appointment contact to save or
     * null to keep the stored one].
     */
    public function backend_save(array $input, ?array $record, ?array $stored_contact): array
    {
        if ($record === null) {
            return ['customer' => $input, 'contact' => $this->contact($input)];
        }

        $baseline = $this->contact($stored_contact ?? $record);
        $changes = $this->changed_fields($baseline, $input);

        return [
            'customer' => array_merge($input, array_intersect_key($record, array_flip(self::FIELDS)), $changes),
            'contact' => $changes ? array_merge($baseline, $changes) : null
        ];
    }

    /**
     * Get the customer data to display for an appointment: the appointment contact over the customer record.
     *
     * @param array $customer Customer record.
     * @param array|null $contact Appointment contact, null for appointments without contact (they show the record).
     *
     * @return array Returns the customer record with the contact fields of the appointment.
     */
    public function display_customer(array $customer, ?array $contact): array
    {
        if ($contact === null) {
            return $customer;
        }

        foreach (self::FIELDS as $field) {
            $customer[$field] = $contact[$field] ?? null;
        }

        return $customer;
    }

    /**
     * Build the Google event description: the contact block followed by the notes.
     *
     * @param array|null $contact Appointment contact (or customer record), null without customer.
     * @param string|null $notes Appointment notes.
     *
     * @return string Returns the description.
     */
    public function event_description(?array $contact, ?string $notes): string
    {
        $notes = (string) $notes;
        $lines = [];

        if ($contact !== null) {
            $name = trim(
                trim((string) ($contact['first_name'] ?? '')) . ' ' . trim((string) ($contact['last_name'] ?? ''))
            );
            $phone = $this->dialable_phone_number((string) ($contact['phone_number'] ?? ''));
            $email = trim((string) ($contact['email'] ?? ''));

            $lines = array_filter([
                $name,
                $phone !== '' ? 'Tel: ' . $phone : '',
                $email !== '' ? 'E-Mail: ' . $email : ''
            ]);
        }

        if ($lines === []) {
            return $notes;
        }

        $block = implode("\n", [self::BLOCK_START, ...$lines, self::BLOCK_END]);

        return $notes === '' ? $block : $block . "\n\n" . $notes;
    }

    /**
     * Remove the contact block from a Google event description.
     *
     * Google may return the description as HTML after an edit in Google Calendar (line breaks as <br>, phone numbers
     * as links), so line breaks are matched in both forms. A description without block is returned unchanged.
     *
     * @param string|null $description Google event description.
     *
     * @return string Returns the notes without contact block.
     */
    public function strip_contact_block(?string $description): string
    {
        $description = (string) $description;
        $break = '(?:\r?\n|<br\s*\/?>)';

        $pattern =
            '/^' .
            preg_quote(self::BLOCK_START, '/') .
            $break .
            '.*?' .
            $break .
            preg_quote(self::BLOCK_END, '/') .
            '(?=' .
            $break .
            '|$)/s';

        if (!preg_match($pattern, $description, $match)) {
            return $description;
        }

        return (string) preg_replace('/^(?:\s|<br\s*\/?>)+/', '', substr($description, strlen($match[0])));
    }

    /**
     * Normalize a phone number so it can be dialed via a tel: link (digits and a leading plus only).
     */
    protected function dialable_phone_number(string $phone_number): string
    {
        $phone_number = trim($phone_number);
        $plus = str_starts_with($phone_number, '+') ? '+' : '';
        $digits = preg_replace('/\D/', '', $phone_number);

        return $digits === '' ? '' : $plus . $digits;
    }

    /**
     * Whether a form value counts as empty (missing, null or only whitespace).
     */
    protected function is_empty(mixed $value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '');
    }
}
