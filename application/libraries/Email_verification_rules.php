<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email verification rules library (fork, spec 0018, ADR 0008).
 *
 * Pure functions without database access for the "E-Mail-Bestätigung": whether an online booking must first be
 * stored as a "Vormerkung", which emails count as verified in the browser session and what a verification record
 * stores. The Email_verification library persists the records and sends the mails.
 *
 * Rules:
 * - With the setting "require_email_verification" a new online booking becomes a Vormerkung, unless its email was
 *   already verified in this browser session. A reschedule via manage link needs no verification.
 * - A verification link is valid for 10 minutes; the data to restore the booking is kept for 24 hours.
 * - Only the SHA-256 hash of the token is stored; the token itself only travels in the link.
 *
 * @package Libraries
 */
class Email_verification_rules
{
    /**
     * Purpose of a verification: a new online booking (Vormerkung).
     */
    public const PURPOSE_BOOKING = 'booking';

    /**
     * Purpose of a verification: a changed email during a reschedule (ticket 0018/05).
     */
    public const PURPOSE_EMAIL_CHANGE = 'email_change';

    /**
     * Minutes a Vormerkung holds its slot and a link is valid.
     */
    public const VALIDITY_MINUTES = 10;

    /**
     * Hours the data to restore an expired Vormerkung is kept.
     */
    public const RESTORE_HOURS = 24;

    /**
     * Decide whether an online booking must be verified by email before it is booked.
     *
     * @param bool $enabled Value of the setting "require_email_verification".
     * @param bool $manage_mode Whether the request is a reschedule via manage link.
     * @param string|null $email Entered email.
     * @param array $verified_emails Emails verified in this browser session.
     *
     * @return bool Returns true when the booking becomes a Vormerkung.
     */
    public function requires_verification(
        bool $enabled,
        bool $manage_mode,
        ?string $email,
        array $verified_emails
    ): bool {
        if (!$enabled || $manage_mode) {
            return false;
        }

        $email = $this->normalize_email($email);

        return $email === '' || !in_array($email, $verified_emails, true);
    }

    /**
     * Add an email to the emails verified in this browser session.
     *
     * @param mixed $verified_emails Stored list (anything else than an array counts as empty).
     * @param string $email Verified email.
     *
     * @return array Returns the list with the normalized email added once.
     */
    public function remember_verified(mixed $verified_emails, string $email): array
    {
        $verified_emails = is_array($verified_emails) ? array_values(array_filter($verified_emails, 'is_string')) : [];

        $email = $this->normalize_email($email);

        if ($email !== '' && !in_array($email, $verified_emails, true)) {
            $verified_emails[] = $email;
        }

        return $verified_emails;
    }

    /**
     * Build the verification record of a new online booking (Vormerkung).
     *
     * @param array $appointment Appointment to book (start, end, service, provider, notes).
     * @param array $customer Customer fields entered in the booking form (incl. language and timezone).
     * @param string $token Token of the link (only its hash is stored).
     * @param DateTimeImmutable $now Current time (database time zone).
     *
     * @return array Returns the record without the appointment ID.
     */
    public function booking_verification(
        array $appointment,
        array $customer,
        string $token,
        DateTimeImmutable $now
    ): array {
        return [
            'purpose' => self::PURPOSE_BOOKING,
            'email' => $this->normalize_email($customer['email'] ?? null),
            'token_hash' => $this->token_hash($token),
            'expires_datetime' => $now->modify('+' . self::VALIDITY_MINUTES . ' minutes')->format('Y-m-d H:i:s'),
            'last_sent_datetime' => $now->format('Y-m-d H:i:s'),
            'send_count' => 1,
            'id_services' => (int) $appointment['id_services'],
            'id_users_provider' => (int) $appointment['id_users_provider'],
            'start_datetime' => $appointment['start_datetime'],
            'end_datetime' => $appointment['end_datetime'],
            'notes' => $appointment['notes'] ?? null,
            'customer_data' => json_encode($customer, JSON_UNESCAPED_UNICODE)
        ];
    }

    /**
     * Get the customer fields entered in the booking form from a stored verification.
     *
     * @param array $verification Stored verification record.
     *
     * @return array Returns the customer fields (empty when the stored data is unreadable).
     */
    public function customer_input(array $verification): array
    {
        $customer = json_decode((string) ($verification['customer_data'] ?? ''), true);

        return is_array($customer) ? $customer : [];
    }

    /**
     * Hash a token for storage and lookup.
     *
     * @param string $token Token from the link.
     *
     * @return string Returns the hex SHA-256 hash (64 characters).
     */
    public function token_hash(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * Normalize an email for comparisons (trimmed, lower case).
     *
     * @param string|null $email Email.
     *
     * @return string Returns the normalized email, '' for an empty one.
     */
    public function normalize_email(?string $email): string
    {
        return strtolower(trim((string) $email));
    }
}
