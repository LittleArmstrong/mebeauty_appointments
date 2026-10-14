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
 *   already verified in this browser session. A reschedule via manage link applies right away; only a changed
 *   email waits for a link to the new address ("E-Mail-Änderung") and stays the old one until then.
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
     * Seconds a customer must wait after the last mail before the verification mail can be sent again.
     */
    public const RESEND_WAIT_SECONDS = 60;

    /**
     * Maximum number of verification mails per email within one limit window.
     */
    public const MAILS_PER_WINDOW = 5;

    /**
     * Length of the limit window in minutes; its counter starts again afterwards.
     */
    public const LIMIT_WINDOW_MINUTES = 60;

    /**
     * Send decision: the verification mail may be sent.
     */
    public const SEND_ALLOWED = 'allowed';

    /**
     * Send decision: the last mail went out less than RESEND_WAIT_SECONDS ago ("bitte warten").
     */
    public const SEND_WAIT = 'wait';

    /**
     * Send decision: MAILS_PER_WINDOW mails went to the email in the current window.
     */
    public const SEND_LIMIT = 'limit';

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
     * Decide whether the email entered during a reschedule via manage link must be verified first (spec 0018/05).
     *
     * Only a changed email waits for the link; time and the other entries of the reschedule apply right away. An
     * email verified in this browser session needs no further verification.
     *
     * @param bool $enabled Value of the setting "require_email_verification".
     * @param string|null $stored_email Email of the appointment before the reschedule (verified address).
     * @param string|null $entered_email Email entered in the reschedule.
     * @param array $verified_emails Emails verified in this browser session.
     *
     * @return bool Returns true when the entered email becomes a pending email change.
     */
    public function requires_email_change_verification(
        bool $enabled,
        ?string $stored_email,
        ?string $entered_email,
        array $verified_emails
    ): bool {
        $entered_email = $this->normalize_email($entered_email);

        return $enabled &&
            $entered_email !== '' &&
            $entered_email !== $this->normalize_email($stored_email) &&
            !in_array($entered_email, $verified_emails, true);
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
     * Build the verification record of a changed email during a reschedule (purpose "email_change").
     *
     * The record belongs to the rescheduled appointment; its expiry keeps the appointment, the email just stays the
     * old one.
     *
     * @param array $appointment Rescheduled appointment (with ID).
     * @param string $email Entered new email.
     * @param string $token Token of the link (only its hash is stored).
     * @param DateTimeImmutable $now Current time (database time zone).
     *
     * @return array Returns the record including the appointment ID.
     */
    public function email_change_verification(
        array $appointment,
        string $email,
        string $token,
        DateTimeImmutable $now
    ): array {
        return [
            'id_appointments' => (int) $appointment['id'],
            'purpose' => self::PURPOSE_EMAIL_CHANGE,
            'email' => $this->normalize_email($email),
            'token_hash' => $this->token_hash($token),
            'expires_datetime' => $now->modify('+' . self::VALIDITY_MINUTES . ' minutes')->format('Y-m-d H:i:s'),
            'last_sent_datetime' => $now->format('Y-m-d H:i:s'),
            'send_count' => 1,
            'id_services' => (int) $appointment['id_services'],
            'id_users_provider' => (int) $appointment['id_users_provider'],
            'start_datetime' => $appointment['start_datetime'],
            'end_datetime' => $appointment['end_datetime'],
            'notes' => $appointment['notes'] ?? null,
            'customer_data' => json_encode(['email' => trim($email)], JSON_UNESCAPED_UNICODE)
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
     * @return array Returns ['expired' => …, 'void' => …, 'limits' => …]: a verification with expires_datetime <=
     * 'expired' is expired, one with expires_datetime <= 'void' is void, a send limit with last_sent_datetime <=
     * 'limits' has ended: its window started before the last mail and its resend pause is over (Y-m-d H:i:s).
     */
    public function cleanup_cutoffs(DateTimeImmutable $now): array
    {
        return [
            'expired' => $now->format('Y-m-d H:i:s'),
            'void' => $now
                ->modify('+' . self::VALIDITY_MINUTES . ' minutes')
                ->modify('-' . self::RESTORE_HOURS . ' hours')
                ->format('Y-m-d H:i:s'),
            'limits' => $now->modify('-' . self::LIMIT_WINDOW_MINUTES . ' minutes')->format('Y-m-d H:i:s')
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

    /**
     * Decide whether a verification mail may be sent to an email now.
     *
     * A resend waits RESEND_WAIT_SECONDS after the last mail to the email. Every mail, the first one of a new
     * Vormerkung included, counts against MAILS_PER_WINDOW per email; the window starts with its first mail and its
     * counter starts again LIMIT_WINDOW_MINUTES later.
     *
     * @param array|null $limit Send limit of the email (window_start_datetime, window_count, last_sent_datetime) or
     * null when no mail went to it yet.
     * @param DateTimeImmutable $now Current time (database time zone).
     * @param bool $resend Whether the mail is a resend for an existing Vormerkung.
     *
     * @return array Returns ['decision' => SEND_*, 'wait_seconds' => int] with the seconds until a mail may be sent
     * (0 when allowed).
     */
    public function send_decision(?array $limit, DateTimeImmutable $now, bool $resend): array
    {
        if ($resend && !empty($limit['last_sent_datetime'])) {
            $wait_until = (new DateTimeImmutable($limit['last_sent_datetime']))->modify(
                '+' . self::RESEND_WAIT_SECONDS . ' seconds'
            );

            if ($now < $wait_until) {
                return [
                    'decision' => self::SEND_WAIT,
                    'wait_seconds' => $wait_until->getTimestamp() - $now->getTimestamp()
                ];
            }
        }

        $window_end = $this->window_end($limit);

        if ($window_end && $now < $window_end && (int) $limit['window_count'] >= self::MAILS_PER_WINDOW) {
            return [
                'decision' => self::SEND_LIMIT,
                'wait_seconds' => $window_end->getTimestamp() - $now->getTimestamp()
            ];
        }

        return ['decision' => self::SEND_ALLOWED, 'wait_seconds' => 0];
    }

    /**
     * Update the send limit of an email after a verification mail went out.
     *
     * @param array|null $limit Send limit of the email or null when no mail went to it yet.
     * @param string $email Email the mail went to.
     * @param DateTimeImmutable $now Time of the mail (database time zone).
     *
     * @return array Returns the send limit to store (email, window_start_datetime, window_count, last_sent_datetime).
     */
    public function limit_after_send(?array $limit, string $email, DateTimeImmutable $now): array
    {
        $window_end = $this->window_end($limit);

        $same_window = $window_end && $now < $window_end;

        return [
            'email' => $this->normalize_email($email),
            'window_start_datetime' => $same_window ? $limit['window_start_datetime'] : $now->format('Y-m-d H:i:s'),
            'window_count' => $same_window ? (int) $limit['window_count'] + 1 : 1,
            'last_sent_datetime' => $now->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Get the changed fields of a verification whose mail is sent again with a new link.
     *
     * The new token replaces the old one (the old link becomes invalid), and the Vormerkung holds its slot for
     * VALIDITY_MINUTES from the new mail.
     *
     * @param array $verification Stored verification record.
     * @param string $token Token of the new link (only its hash is stored).
     * @param DateTimeImmutable $now Time of the mail (database time zone).
     *
     * @return array Returns token_hash, expires_datetime, last_sent_datetime and send_count.
     */
    public function resent_verification(array $verification, string $token, DateTimeImmutable $now): array
    {
        return [
            'token_hash' => $this->token_hash($token),
            'expires_datetime' => $now->modify('+' . self::VALIDITY_MINUTES . ' minutes')->format('Y-m-d H:i:s'),
            'last_sent_datetime' => $now->format('Y-m-d H:i:s'),
            'send_count' => (int) ($verification['send_count'] ?? 0) + 1
        ];
    }

    /**
     * Get the end of the limit window of an email.
     *
     * @param array|null $limit Send limit of the email or null.
     *
     * @return DateTimeImmutable|null Returns the end or null when no window was started.
     */
    protected function window_end(?array $limit): ?DateTimeImmutable
    {
        if (empty($limit['window_start_datetime'])) {
            return null;
        }

        return (new DateTimeImmutable($limit['window_start_datetime']))->modify(
            '+' . self::LIMIT_WINDOW_MINUTES . ' minutes'
        );
    }
}
