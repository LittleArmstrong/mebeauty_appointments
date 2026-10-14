<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email verification session library (fork, spec 0018, ADR 0008).
 *
 * Keeps what the "E-Mail-Bestätigung" remembers in the browser session: the emails verified in it (a second booking
 * or an email change with such an email needs no further link), the Vormerkung started in it (the only one
 * "erneut senden" may send again, so no client input can trigger mails to other addresses) and the verifications
 * started in it (their links opened in this session confirm right away, without the button).
 *
 * @package Libraries
 */
class Email_verification_session
{
    /**
     * Session key with the emails verified in this browser session (normalized list).
     */
    public const VERIFIED_KEY = 'verified_emails';

    /**
     * Session key with the appointment ID of the Vormerkung started in this browser session.
     */
    public const PENDING_KEY = 'pending_verification_appointment';

    /**
     * Session key with the IDs of the verifications started in this browser session.
     */
    public const OWN_KEY = 'own_verifications';

    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    /**
     * Email_verification_session constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->library('email_verification_rules');
    }

    /**
     * Get the emails verified in this browser session.
     *
     * @return array Returns the normalized emails.
     */
    public function verified_emails(): array
    {
        $verified_emails = session(self::VERIFIED_KEY);

        return is_array($verified_emails) ? $verified_emails : [];
    }

    /**
     * Remember an email as verified in this browser session.
     *
     * @param string $email Verified email.
     */
    public function remember_verified(string $email): void
    {
        session([
            self::VERIFIED_KEY => $this->CI->email_verification_rules->remember_verified(
                session(self::VERIFIED_KEY),
                $email
            )
        ]);
    }

    /**
     * Get the appointment ID of the Vormerkung started in this browser session.
     *
     * @return int|null
     */
    public function pending_appointment_id(): ?int
    {
        $appointment_id = session(self::PENDING_KEY);

        return is_int($appointment_id) ? $appointment_id : null;
    }

    /**
     * Remember the Vormerkung started in this browser session (for "erneut senden").
     *
     * @param int $appointment_id Appointment ID of the Vormerkung.
     */
    public function remember_pending(int $appointment_id): void
    {
        session([self::PENDING_KEY => $appointment_id]);
    }

    /**
     * Check whether a verification was started in this browser session (its link then confirms right away).
     *
     * @param array $verification Verification of the link.
     *
     * @return bool
     */
    public function owns(array $verification): bool
    {
        return $this->CI->email_verification_rules->confirms_on_open($verification, session(self::OWN_KEY));
    }

    /**
     * Remember a verification as started in this browser session.
     *
     * @param int $verification_id ID of the verification.
     */
    public function remember_own(int $verification_id): void
    {
        session([
            self::OWN_KEY => $this->CI->email_verification_rules->remember_own_verification(
                session(self::OWN_KEY),
                $verification_id
            )
        ]);
    }
}
