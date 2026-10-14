<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email verification session library (fork, spec 0018, ADR 0008).
 *
 * Keeps what the "E-Mail-Bestätigung" remembers in the browser session: the emails verified in it (a second booking
 * or an email change with such an email needs no further link) and the Vormerkung started in it (the only one
 * "erneut senden" may send again, so no client input can trigger mails to other addresses).
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
}
