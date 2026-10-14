<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email verification library (fork, spec 0018, ADR 0008).
 *
 * Runs the "E-Mail-Bestätigung" of online bookings: stores a new booking as "Vormerkung" (an appointment without
 * customer that holds its slot), sends the verification link and confirms the Vormerkung when the link is clicked.
 * A changed email of a reschedule waits for a link to the new address ("E-Mail-Änderung", ticket 0018/05).
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
     * Session key with the appointment ID of the Vormerkung started in this browser session ("erneut senden").
     */
    public const SESSION_PENDING_KEY = 'pending_verification_appointment';

    /**
     * Result of confirm(): the booking is confirmed.
     */
    public const RESULT_CONFIRMED = 'confirmed';

    /**
     * Result of confirm(): the Vormerkung expired and its slot is taken ("Leider abgelaufen").
     */
    public const RESULT_EXPIRED = 'expired';

    /**
     * Result of confirm(): the new email of a reschedule is confirmed and taken over.
     */
    public const RESULT_EMAIL_CHANGED = 'email_changed';

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
        $this->CI->load->model('appointment_contacts_model');
        $this->CI->load->model('services_model');
        $this->CI->load->model('providers_model');

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

        $rules = $this->CI->email_verification_rules;

        $now = new DateTimeImmutable('now');

        $limit = $this->CI->appointment_verifications_model->find_limit($rules->normalize_email($email));

        if ($rules->send_decision($limit, $now, false)['decision'] !== Email_verification_rules::SEND_ALLOWED) {
            throw new RuntimeException(lang('email_verification_limit_reached'));
        }

        $token = $this->new_token();

        $verification = $this->CI->email_verification_rules->booking_verification(
            $appointment,
            $customer,
            $token,
            $now
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

        $this->CI->appointment_verifications_model->save_limit($rules->limit_after_send($limit, $email, $now));

        session([self::SESSION_PENDING_KEY => $appointment_id]);

        return $appointment_id;
    }

    /**
     * Hold back a changed email of a reschedule until the new address is verified (ticket 0018/05).
     *
     * With the setting on, an entered email that differs from the email of the appointment (its contact, else its
     * customer record) is replaced in $customer by that verified email, so the reschedule keeps it in the
     * appointment contact and the customer record and its mail goes there. Time and the other entries apply right
     * away. The caller sends the link with start_email_change() once the reschedule is saved.
     *
     * @param array $original_appointment Stored appointment of the manage link.
     * @param array $customer Customer fields entered in the reschedule (email replaced when held back).
     *
     * @return string|null Returns the entered new email when it waits for verification, otherwise null.
     *
     * @throws RuntimeException When the hourly mail limit of the new email is reached (nothing is saved then).
     */
    public function hold_back_email_change(array $original_appointment, array &$customer): ?string
    {
        $rules = $this->CI->email_verification_rules;

        $stored_email = $this->appointment_email($original_appointment);

        $verified_emails = session(self::SESSION_KEY);

        if (
            !$rules->requires_email_change_verification(
                filter_var(setting('require_email_verification', '0'), FILTER_VALIDATE_BOOLEAN),
                $stored_email,
                $customer['email'] ?? null,
                is_array($verified_emails) ? $verified_emails : []
            )
        ) {
            return null;
        }

        $email = trim((string) $customer['email']);

        $limit = $this->CI->appointment_verifications_model->find_limit($rules->normalize_email($email));

        if (
            $rules->send_decision($limit, new DateTimeImmutable('now'), false)['decision'] !==
            Email_verification_rules::SEND_ALLOWED
        ) {
            throw new RuntimeException(lang('email_verification_limit_reached'));
        }

        $customer['email'] = $stored_email;

        return $email;
    }

    /**
     * Send the verification link for the new email of a saved reschedule (purpose "email_change").
     *
     * A previous open email change of the appointment is replaced (its link becomes invalid). The reschedule stays
     * saved when the mail fails; the email then simply stays the old one.
     *
     * @param array $appointment Saved appointment of the reschedule.
     * @param string $email New email returned by hold_back_email_change().
     *
     * @return bool Returns whether the link was sent.
     */
    public function start_email_change(array $appointment, string $email): bool
    {
        $rules = $this->CI->email_verification_rules;

        $now = new DateTimeImmutable('now');

        $token = $this->new_token();

        $model = $this->CI->appointment_verifications_model;

        $model->delete_for_appointment((int) $appointment['id'], Email_verification_rules::PURPOSE_EMAIL_CHANGE);

        $verification_id = $model->insert($rules->email_change_verification($appointment, $email, $token, $now));

        try {
            $this->CI->email_messages->send_email_verification_link(
                site_url('booking/verify/' . $token),
                $appointment,
                $this->CI->services_model->find((int) $appointment['id_services']),
                $this->CI->providers_model->find((int) $appointment['id_users_provider']),
                $email,
                $this->mail_settings(),
                true
            );
        } catch (Throwable $e) {
            log_message(
                'error',
                'Email change verification mail for appointment ' . $appointment['id'] . ': ' . $e->getMessage()
            );

            $model->take($verification_id);

            return false;
        }

        $limit = $model->find_limit($rules->normalize_email($email));

        $model->save_limit($rules->limit_after_send($limit, $email, $now));

        return true;
    }

    /**
     * Send the verification mail of the Vormerkung started in this browser session again, with a new link.
     *
     * The Vormerkung comes from the browser session only, so nobody can trigger mails to other addresses, and the
     * answer never contains the manage link hash or tells whether an address is known. A resend waits 60 seconds
     * after the last mail and counts against the limit of 5 mails per email and hour. The new link replaces the old
     * one and holds the slot for 10 minutes from the new mail.
     *
     * @return array Returns ['sent' => bool, 'message' => string, 'wait_seconds' => int] for the booking wizard.
     *
     * @throws Throwable
     */
    public function resend(): array
    {
        $rules = $this->CI->email_verification_rules;

        $appointment_id = session(self::SESSION_PENDING_KEY);

        $this->cleanup_expired();

        $verification = is_int($appointment_id)
            ? $this->CI->appointment_verifications_model->find_by_appointment($appointment_id)
            : null;

        $now = new DateTimeImmutable('now');

        $appointment = $verification ? $this->CI->appointments_model->get(['id' => $appointment_id])[0] ?? null : null;

        if (
            !$appointment ||
            !empty($appointment['id_users_customer']) ||
            $verification['purpose'] !== Email_verification_rules::PURPOSE_BOOKING ||
            $rules->state($verification, $now) !== Email_verification_rules::STATE_VALID
        ) {
            return ['sent' => false, 'message' => lang('email_verification_resend_unavailable'), 'wait_seconds' => 0];
        }

        $limit = $this->CI->appointment_verifications_model->find_limit($verification['email']);

        $decision = $rules->send_decision($limit, $now, true);

        if ($decision['decision'] !== Email_verification_rules::SEND_ALLOWED) {
            return [
                'sent' => false,
                'message' =>
                    $decision['decision'] === Email_verification_rules::SEND_WAIT
                        ? sprintf(lang('email_verification_resend_wait'), $decision['wait_seconds'])
                        : lang('email_verification_limit_reached'),
                'wait_seconds' => $decision['wait_seconds']
            ];
        }

        $token = $this->new_token();

        $previous = array_intersect_key(
            $verification,
            array_flip(['token_hash', 'expires_datetime', 'last_sent_datetime', 'send_count'])
        );

        if (
            !$this->CI->appointment_verifications_model->update(
                (int) $verification['id'],
                $rules->resent_verification($verification, $token, $now)
            )
        ) {
            return ['sent' => false, 'message' => lang('email_verification_resend_unavailable'), 'wait_seconds' => 0];
        }

        try {
            $this->CI->email_messages->send_email_verification_link(
                site_url('booking/verify/' . $token),
                $appointment,
                $this->CI->services_model->find((int) $appointment['id_services']),
                $this->CI->providers_model->find((int) $appointment['id_users_provider']),
                $verification['email'],
                $this->mail_settings()
            );
        } catch (Throwable $e) {
            log_message(
                'error',
                'Email verification resend for appointment ' . $appointment_id . ': ' . $e->getMessage()
            );

            // The previous link stays valid when the new one could not be sent.
            $this->CI->appointment_verifications_model->update((int) $verification['id'], $previous);

            throw new RuntimeException(lang('email_verification_send_failed'));
        }

        $this->CI->appointment_verifications_model->save_limit(
            $rules->limit_after_send($limit, $verification['email'], $now)
        );

        return [
            'sent' => true,
            'message' => lang('email_verification_resent'),
            'wait_seconds' => Email_verification_rules::RESEND_WAIT_SECONDS
        ];
    }

    /**
     * Delete expired Vormerkungen (their slots become free) and void restore data (lazy cleanup, spec 0018).
     *
     * Runs before availability requests, on submit, when the backend calendar loads and in `console cleanup`, so
     * no cron job is needed.
     *
     * Ended send limits are deleted as well.
     *
     * @return array Returns ['appointments' => int, 'verifications' => int, 'limits' => int] with the numbers of
     * deleted rows.
     */
    public function cleanup_expired(): array
    {
        $rules = $this->CI->email_verification_rules;

        $cutoffs = $rules->cleanup_cutoffs(new DateTimeImmutable('now'));

        $deleted = $this->CI->appointment_verifications_model->delete_expired(
            $cutoffs['expired'],
            $cutoffs['void'],
            $rules->purposes_freeing_slot()
        );

        $deleted['limits'] = $this->CI->appointment_verifications_model->delete_ended_limits($cutoffs['limits']);

        return $deleted;
    }

    /**
     * Mark the Vormerkungen among loaded appointments for the backend calendar (ticket 0018/04).
     *
     * Sets "is_unconfirmed" (bool) on every appointment.
     *
     * @param array $appointments Appointments with ID.
     */
    public function mark_unconfirmed(array &$appointments): void
    {
        $unconfirmed_ids = $this->unconfirmed_ids(array_column($appointments, 'id'));

        foreach ($appointments as &$appointment) {
            $appointment['is_unconfirmed'] = in_array((int) $appointment['id'], $unconfirmed_ids, true);
        }
    }

    /**
     * Check whether an appointment is a Vormerkung (staff can delete it, but neither edit nor confirm it).
     *
     * @param int $appointment_id Appointment ID.
     *
     * @return bool
     */
    public function is_unconfirmed(int $appointment_id): bool
    {
        return $this->unconfirmed_ids([$appointment_id]) !== [];
    }

    /**
     * Remove the Vormerkungen from appointments that go to an external calendar (no sync before confirmation).
     *
     * @param array $appointments Appointments with ID.
     *
     * @return array Returns the confirmed appointments.
     */
    public function without_unconfirmed(array $appointments): array
    {
        $unconfirmed_ids = $this->unconfirmed_ids(array_column($appointments, 'id'));

        return array_values(
            array_filter(
                $appointments,
                fn(array $appointment): bool => !in_array((int) $appointment['id'], $unconfirmed_ids, true)
            )
        );
    }

    /**
     * Delete the verifications of an appointment before staff deletes it, so no link restores it afterwards.
     *
     * @param int $appointment_id Appointment ID.
     */
    public function forget_appointment(int $appointment_id): void
    {
        $this->CI->appointment_verifications_model->delete_by_appointment($appointment_id);
    }

    /**
     * Get the IDs of the Vormerkungen among appointments.
     *
     * @param array $appointment_ids Appointment IDs.
     *
     * @return array Returns the IDs (int) of the appointments with a booking verification.
     */
    protected function unconfirmed_ids(array $appointment_ids): array
    {
        return $this->CI->email_verification_rules->unconfirmed_appointment_ids(
            $this->CI->appointment_verifications_model->find_by_appointments($appointment_ids)
        );
    }

    /**
     * Confirm a Vormerkung with the token from the verification link.
     *
     * Assigns the customer with the verified email (or creates it), updates the customer record by the rules of
     * spec 0017 and removes the verification, all in one transaction. The link works only once. The email counts as
     * verified in the current browser session afterwards.
     *
     * A late click (Vormerkung expired, restore data still there) books the appointment again from the restore data
     * when $is_available confirms its slot is still free; otherwise the result is "expired".
     *
     * The link of an email change (reschedule) takes the new email over while it is valid, see
     * confirm_email_change().
     *
     * @param string $token Token from the link.
     * @param array $allowed_customer_fields Customer fields an online booking may write.
     * @param callable $is_available Availability check of the booking flow: fn(array $appointment): bool.
     *
     * @return array|null Returns ['result' => RESULT_CONFIRMED|RESULT_EMAIL_CHANGED, 'appointment_id' => int,
     * 'customer_id' => int], ['result' => RESULT_EXPIRED], or null when the link is unknown, used, void or its
     * Vormerkung is gone.
     *
     * @throws Throwable
     */
    public function confirm(string $token, array $allowed_customer_fields, callable $is_available): ?array
    {
        if (!preg_match('/^[a-zA-Z0-9]{1,128}$/', $token)) {
            return null;
        }

        $this->cleanup_expired();

        $rules = $this->CI->email_verification_rules;

        $verification = $this->CI->appointment_verifications_model->find_by_token_hash($rules->token_hash($token));

        if ($verification && $verification['purpose'] === Email_verification_rules::PURPOSE_EMAIL_CHANGE) {
            return $this->confirm_email_change($verification, $allowed_customer_fields);
        }

        if (!$verification || $verification['purpose'] !== Email_verification_rules::PURPOSE_BOOKING) {
            return null;
        }

        $state = $rules->state($verification, new DateTimeImmutable('now'));

        if ($state === Email_verification_rules::STATE_VALID && !empty($verification['id_appointments'])) {
            $appointment = $this->CI->appointments_model->get(['id' => $verification['id_appointments']])[0] ?? null;

            if (!$appointment || !empty($appointment['id_users_customer'])) {
                return null;
            }
        } elseif ($state === Email_verification_rules::STATE_RESTORABLE && empty($verification['id_appointments'])) {
            $appointment = $this->restored_appointment($verification);

            if (!$is_available($appointment)) {
                return ['result' => self::RESULT_EXPIRED];
            }
        } else {
            return null;
        }

        $customer = $rules->customer_input($verification);

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

            $appointment_id = $this->CI->appointments_model->save($appointment);

            if (empty($verification['id_appointments'])) {
                $this->CI->appointment_contacts_model->save_for_appointment(
                    $appointment_id,
                    $this->CI->appointment_contact_rules->contact($customer)
                );
            }

            if ($existing_customer) {
                $this->CI->customers_model->save($customer_record);
            }

            $this->CI->db->trans_commit();
        } catch (Throwable $e) {
            $this->CI->db->trans_rollback();

            throw $e;
        }

        session([
            self::SESSION_KEY => $rules->remember_verified(session(self::SESSION_KEY), $verification['email'])
        ]);

        return [
            'result' => self::RESULT_CONFIRMED,
            'appointment_id' => (int) $appointment_id,
            'customer_id' => (int) $customer_id
        ];
    }

    /**
     * Take over the new email of a reschedule (ticket 0018/05).
     *
     * Only a valid link works; an expired one changes nothing (the appointment stays with the old email). The
     * appointment contact gets the new email; the customer record only when no other customer has it (spec 0017).
     *
     * @param array $verification Stored verification with purpose "email_change".
     * @param array $allowed_customer_fields Customer fields an online booking may write.
     *
     * @return array|null Returns ['result' => RESULT_EMAIL_CHANGED, 'appointment_id' => int, 'customer_id' => int]
     * or null when the link is expired or the appointment is gone.
     *
     * @throws Throwable
     */
    protected function confirm_email_change(array $verification, array $allowed_customer_fields): ?array
    {
        $rules = $this->CI->email_verification_rules;
        $contact_rules = $this->CI->appointment_contact_rules;

        if (
            empty($verification['id_appointments']) ||
            $rules->state($verification, new DateTimeImmutable('now')) !== Email_verification_rules::STATE_VALID
        ) {
            return null;
        }

        $appointment = $this->CI->appointments_model->get(['id' => $verification['id_appointments']])[0] ?? null;

        if (!$appointment || empty($appointment['id_users_customer'])) {
            return null;
        }

        $email = trim((string) ($rules->customer_input($verification)['email'] ?? $verification['email']));

        $customer_id = (int) $appointment['id_users_customer'];

        $existing_customer = $this->CI->customers_model->find($customer_id);

        $email_owner_id = $this->CI->customers_model->exists(['email' => $email])
            ? $this->CI->customers_model->find_record_id(['email' => $email])
            : null;

        $customer_record = $contact_rules->customer_record(['email' => $email], $existing_customer, $email_owner_id);

        $this->CI->customers_model->only($customer_record, $allowed_customer_fields);

        $stored_contact = $this->CI->appointment_contacts_model->find_by_appointment((int) $appointment['id']);

        $contact = $contact_rules->contact(['email' => $email] + ($stored_contact ?? $existing_customer));

        $this->CI->db->trans_begin();

        try {
            if (!$this->CI->appointment_verifications_model->take((int) $verification['id'])) {
                $this->CI->db->trans_rollback();

                return null;
            }

            $this->CI->appointment_contacts_model->save_for_appointment((int) $appointment['id'], $contact);

            $this->CI->customers_model->save($customer_record);

            $this->CI->db->trans_commit();
        } catch (Throwable $e) {
            $this->CI->db->trans_rollback();

            throw $e;
        }

        session([self::SESSION_KEY => $rules->remember_verified(session(self::SESSION_KEY), $email)]);

        return [
            'result' => self::RESULT_EMAIL_CHANGED,
            'appointment_id' => (int) $appointment['id'],
            'customer_id' => $customer_id
        ];
    }

    /**
     * Get the message page variables for an expired Vormerkung whose slot is taken ("Leider abgelaufen").
     *
     * @return array Returns message_title, message_text and message_icon for the booking_message page.
     */
    public static function expired_page_vars(): array
    {
        return [
            'message_title' => lang('email_verification_expired_title'),
            'message_text' =>
                '<p>' .
                e(lang('email_verification_expired_text')) .
                '</p><p><a href="' .
                e(site_url()) .
                '">' .
                e(lang('go_to_booking_page')) .
                '</a></p>',
            'message_icon' => base_url('assets/img/error.png')
        ];
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
     * Build the appointment of an expired Vormerkung again from its restore data, completed like an online booking.
     *
     * @param array $verification Stored verification record.
     *
     * @return array Returns the appointment fields without customer.
     */
    protected function restored_appointment(array $verification): array
    {
        $service = $this->CI->services_model->find((int) $verification['id_services']);

        $status_options = json_decode(setting('appointment_status_options', '[]'), true) ?? [];

        $appointment = $this->CI->email_verification_rules->restored_appointment(
            $verification,
            $service,
            $status_options[0] ?? null
        );

        if (setting('jitsi_enabled') === '1') {
            $this->CI->load->library('jitsi_client');

            $appointment['meeting_link'] = $this->CI->jitsi_client->generate_link();
        }

        return $appointment;
    }

    /**
     * Get the verified email of an appointment: the email of its contact, else of its customer record.
     *
     * @param array $appointment Stored appointment.
     *
     * @return string|null
     */
    protected function appointment_email(array $appointment): ?string
    {
        $contact = $this->CI->appointment_contacts_model->find_by_appointment((int) $appointment['id']);

        if (!empty($contact['email'])) {
            return $contact['email'];
        }

        $customer = empty($appointment['id_users_customer'])
            ? null
            : $this->CI->customers_model->find((int) $appointment['id_users_customer']);

        return $customer['email'] ?? null;
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
