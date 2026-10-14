<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email verification library (fork, spec 0018, ADR 0008).
 *
 * Runs the "E-Mail-Bestätigung" of online bookings: stores a new booking as "Vormerkung" (an appointment without
 * customer that holds its slot), sends the verification link and confirms the Vormerkung when the link is clicked.
 * The decisions live in the pure Email_verification_rules library, the records in Appointment_verifications_model.
 * Mails, Google sync and webhooks of a confirmed booking are triggered by the caller, like for a regular booking.
 *
 * @package Libraries
 */
class Email_verification
{
    /**
     * Session key with the emails verified in this browser session.
     */
    public const SESSION_KEY = 'verified_emails';

    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    /**
     * Email_verification constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->model('appointments_model');
        $this->CI->load->model('customers_model');
        $this->CI->load->model('appointment_verifications_model');

        $this->CI->load->library('email_verification_rules');
        $this->CI->load->library('appointment_contact_rules');
        $this->CI->load->library('email_messages');
    }

    /**
     * Check whether an online booking must be stored as Vormerkung first.
     *
     * @param bool $manage_mode Whether the request is a reschedule via manage link.
     * @param string|null $email Entered email.
     *
     * @return bool
     */
    public function required(bool $manage_mode, ?string $email): bool
    {
        $verified_emails = session(self::SESSION_KEY);

        return $this->CI->email_verification_rules->requires_verification(
            filter_var(setting('require_email_verification', '0'), FILTER_VALIDATE_BOOLEAN),
            $manage_mode,
            $email,
            is_array($verified_emails) ? $verified_emails : []
        );
    }

    /**
     * Store a new online booking as Vormerkung and send the verification link to the entered email.
     *
     * No customer is assigned or changed, and no booking mail, Google sync or webhook happens until the link is
     * clicked. When the mail cannot be sent, the Vormerkung is removed again (without mail no online booking).
     *
     * @param array $appointment Completed appointment of the online booking (without customer).
     * @param array $customer Customer fields entered in the booking form (incl. language and timezone).
     * @param array $service Service of the appointment.
     * @param array $provider Provider of the appointment.
     *
     * @return int Returns the ID of the Vormerkung.
     *
     * @throws Throwable
     */
    public function start_booking(array $appointment, array $customer, array $service, array $provider): int
    {
        $email = trim((string) ($customer['email'] ?? ''));

        if ($email === '') {
            throw new InvalidArgumentException(lang('email_verification_email_required'));
        }

        $token = $this->new_token();

        $verification = $this->CI->email_verification_rules->booking_verification(
            $appointment,
            $customer,
            $token,
            new DateTimeImmutable('now')
        );

        $appointment_id = $this->CI->appointment_verifications_model->create_vormerkung(
            $appointment,
            $this->CI->appointment_contact_rules->contact($customer),
            $verification
        );

        try {
            $this->CI->email_messages->send_email_verification_link(
                site_url('booking/verify/' . $token),
                $appointment,
                $service,
                $provider,
                $email,
                $this->mail_settings()
            );
        } catch (Throwable $e) {
            log_message(
                'error',
                'Email verification mail for appointment ' . $appointment_id . ': ' . $e->getMessage()
            );

            $this->CI->appointment_verifications_model->delete_vormerkung($appointment_id);

            throw new RuntimeException(lang('email_verification_send_failed'));
        }

        return $appointment_id;
    }

    /**
     * Confirm a Vormerkung with the token from the verification link.
     *
     * Assigns the customer with the verified email (or creates it), updates the customer record by the rules of
     * spec 0017 and removes the verification, all in one transaction. The link works only once. The email counts as
     * verified in the current browser session afterwards.
     *
     * @param string $token Token from the link.
     * @param array $allowed_customer_fields Customer fields an online booking may write.
     *
     * @return array|null Returns ['appointment_id' => int, 'customer_id' => int], or null when the link is unknown,
     * already used or its Vormerkung is gone.
     *
     * @throws Throwable
     */
    public function confirm(string $token, array $allowed_customer_fields): ?array
    {
        if (!preg_match('/^[a-zA-Z0-9]{1,128}$/', $token)) {
            return null;
        }

        $verification = $this->CI->appointment_verifications_model->find_by_token_hash(
            $this->CI->email_verification_rules->token_hash($token)
        );

        if (
            !$verification ||
            $verification['purpose'] !== Email_verification_rules::PURPOSE_BOOKING ||
            empty($verification['id_appointments'])
        ) {
            return null;
        }

        $appointment = $this->CI->appointments_model->get(['id' => $verification['id_appointments']])[0] ?? null;

        if (!$appointment || !empty($appointment['id_users_customer'])) {
            return null;
        }

        $customer = $this->CI->email_verification_rules->customer_input($verification);

        $this->CI->customers_model->only($customer, $allowed_customer_fields);

        $email_customer_id = $this->CI->customers_model->exists($customer)
            ? $this->CI->customers_model->find_record_id($customer)
            : 0;

        $existing_customer = $email_customer_id ? $this->CI->customers_model->find($email_customer_id) : null;

        $customer_record = $this->CI->appointment_contact_rules->customer_record(
            $customer,
            $existing_customer,
            $email_customer_id ?: null
        );

        $this->CI->customers_model->only($customer_record, $allowed_customer_fields);

        $this->CI->db->trans_begin();

        try {
            // Taking the verification first makes a concurrent second click find nothing.
            if (!$this->CI->appointment_verifications_model->take((int) $verification['id'])) {
                $this->CI->db->trans_rollback();

                return null;
            }

            $customer_id = $email_customer_id ?: $this->CI->customers_model->save($customer_record);

            $appointment['id_users_customer'] = $customer_id;

            $this->CI->appointments_model->save($appointment);

            if ($existing_customer) {
                $this->CI->customers_model->save($customer_record);
            }

            $this->CI->db->trans_commit();
        } catch (Throwable $e) {
            $this->CI->db->trans_rollback();

            throw $e;
        }

        session([
            self::SESSION_KEY => $this->CI->email_verification_rules->remember_verified(
                session(self::SESSION_KEY),
                $verification['email']
            )
        ]);

        return ['appointment_id' => (int) $appointment['id'], 'customer_id' => (int) $customer_id];
    }

    /**
     * Get the message page variables for an unknown or used verification link (neutral wording).
     *
     * @return array Returns message_title, message_text and message_icon for the booking_message page.
     */
    public static function invalid_page_vars(): array
    {
        return [
            'message_title' => lang('email_verification_invalid_title'),
            'message_text' =>
                '<p>' .
                e(lang('email_verification_invalid_text')) .
                '</p><p><a href="' .
                e(site_url()) .
                '">' .
                e(lang('go_to_booking_page')) .
                '</a></p>',
            'message_icon' => base_url('assets/img/error.png')
        ];
    }

    /**
     * Create a random token for a verification link (256 bits, hex).
     *
     * @return string
     *
     * @throws Exception
     */
    protected function new_token(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Get the settings the mail templates use.
     *
     * @return array
     */
    protected function mail_settings(): array
    {
        $company_color = setting('company_color');

        return [
            'company_name' => setting('company_name'),
            'company_link' => setting('company_link'),
            'company_email' => setting('company_email'),
            'company_color' =>
                !empty($company_color) && $company_color != DEFAULT_COMPANY_COLOR ? $company_color : null,
            'date_format' => setting('date_format'),
            'time_format' => setting('time_format')
        ];
    }
}
