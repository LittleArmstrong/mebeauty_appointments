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
 * - A verification link is valid for 10 minutes; the data to restore the booking is kept for 24 hours after the
 *   mail. An expired Vormerkung (purpose "booking") is deleted and frees its slot; a later click books it again when
 *   the slot is still free. An expired email change keeps its appointment.
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
     * State of a verification: the link is valid and its Vormerkung holds the slot.
     */
    public const STATE_VALID = 'valid';

    /**
     * State of a verification: expired, but a click may still restore the booking.
     */
    public const STATE_RESTORABLE = 'restorable';

    /**
     * State of a verification: the link is void and the restore data is deleted.
     */
    public const STATE_VOID = 'void';

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

    /**
     * Get the state of a verification at a point in time.
     *
     * Valid until its expiry (10 minutes after the last mail), restorable until 24 hours after the last mail, void
     * afterwards.
     *
     * @param array $verification Verification record with expires_datetime.
     * @param DateTimeImmutable $now Point in time (database time zone).
     *
     * @return string Returns one of the STATE_* constants.
     */
    public function state(array $verification, DateTimeImmutable $now): string
    {
        $expires = new DateTimeImmutable($verification['expires_datetime']);

        if ($now < $expires) {
            return self::STATE_VALID;
        }

        $void = $expires
            ->modify('-' . self::VALIDITY_MINUTES . ' minutes')
            ->modify('+' . self::RESTORE_HOURS . ' hours');

        return $now < $void ? self::STATE_RESTORABLE : self::STATE_VOID;
    }

    /**
     * Get the cutoffs of the cleanup at a point in time, consistent with state().
     *
     * @param DateTimeImmutable $now Point in time (database time zone).
     *
     * @return array Returns ['expired' => …, 'void' => …]: a verification with expires_datetime <= 'expired' is
     * expired, one with expires_datetime <= 'void' is void (Y-m-d H:i:s).
     */
    public function cleanup_cutoffs(DateTimeImmutable $now): array
    {
        return [
            'expired' => $now->format('Y-m-d H:i:s'),
            'void' => $now
                ->modify('+' . self::VALIDITY_MINUTES . ' minutes')
                ->modify('-' . self::RESTORE_HOURS . ' hours')
                ->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Get the purposes whose expiry deletes the appointment and frees its slot.
     *
     * @return array Returns the purposes (only a new booking; an email change keeps its appointment).
     */
    public function purposes_freeing_slot(): array
    {
        return [self::PURPOSE_BOOKING];
    }

    /**
     * Check whether the expiry of a verification deletes its appointment.
     *
     * @param string $purpose Purpose of the verification.
     *
     * @return bool
     */
    public function expiry_frees_slot(string $purpose): bool
    {
        return in_array($purpose, $this->purposes_freeing_slot(), true);
    }

    /**
     * Build the appointment of an expired Vormerkung again from its restore data (late click).
     *
     * @param array $verification Stored verification record.
     * @param array $service Service of the appointment (color, location).
     * @param string|null $status Status of a new online booking.
     *
     * @return array Returns the appointment fields without customer.
     */
    public function restored_appointment(array $verification, array $service, ?string $status): array
    {
        return [
            'id_services' => (int) $verification['id_services'],
            'id_users_provider' => (int) $verification['id_users_provider'],
            'start_datetime' => $verification['start_datetime'],
            'end_datetime' => $verification['end_datetime'],
            'notes' => $verification['notes'] ?? null,
            'location' => $service['location'] ?? null,
            'color' => $service['color'] ?? null,
            'status' => $status,
            'is_unavailability' => false
        ];
    }
}
