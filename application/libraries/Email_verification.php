<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email verification library (fork, spec 0018, ADR 0008).
 *
 * Runs the "E-Mail-Bestätigung" of online bookings: stores a new booking as "Vormerkung" (an appointment without
 * customer that holds its slot), sends the verification link and confirms the Vormerkung when the button behind the
 * link is pressed. A changed email of a reschedule waits for a link to the new address ("E-Mail-Änderung").
 * The decisions live in the pure Email_verification_rules library, the records in Appointment_verifications_model,
 * the browser session in Email_verification_session, the link pages in Email_verification_pages and the backend
 * and external calendar handling of Vormerkungen in Email_verification_calendar. Mails, Google sync and webhooks of
 * a confirmed booking are triggered by the caller, like for a regular booking.
 *
 * @package Libraries
 */
class Email_verification
{
    /**
     * Format of a link token (new_token() creates 64 hex characters).
     */
    protected const TOKEN_PATTERN = '/^[a-zA-Z0-9]{1,128}$/';

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
        $this->CI->load->library('email_verification_session');
        $this->CI->load->library('email_verification_pages');
        $this->CI->load->library('appointment_contact_rules');
        $this->CI->load->library('customer_overlap');
        $this->CI->load->library('email_messages');
    }

    /**
     * Check whether an online booking must be stored as Vormerkung first.
     *
     * The caller then also leaves the check for another appointment of the email's customer at the same time to the
     * confirmation, so the answer before it does not tell whether the email belongs to a known customer.
     *
     * @param bool $manage_mode Whether the request is a reschedule via manage link.
     * @param string|null $email Entered email.
     *
     * @return bool
     */
    public function required(bool $manage_mode, ?string $email): bool
    {
        return $this->CI->email_verification_rules->requires_verification(
            $this->enabled(),
            $manage_mode,
            $email,
            $this->CI->email_verification_session->verified_emails()
        );
    }

    /**
     * Store a new online booking as Vormerkung and send the verification link to the entered email.
     *
     * No customer is assigned or changed, and no booking mail, Google sync or webhook happens until the link is
     * confirmed. When the mail cannot be sent, the Vormerkung is removed again (without mail no online booking); the
     * attempt still counts against the hourly limit.
     *
     * @param array $appointment Completed appointment of the online booking (without customer).
     * @param array $customer Customer fields entered in the booking form (incl. language and timezone).
     *
     * @return int Returns the ID of the Vormerkung.
     *
     * @throws Throwable
     */
    public function start_booking(array $appointment, array $customer): int
    {
        $email = trim((string) ($customer['email'] ?? ''));

        if ($email === '') {
            throw new InvalidArgumentException(lang('email_verification_email_required'));
        }

        $now = new DateTimeImmutable('now');

        if ($this->reserve_mail($email, false, $now)['decision'] !== Email_verification_rules::SEND_ALLOWED) {
            throw new RuntimeException(lang('email_verification_limit_reached'));
        }

        $token = $this->new_token();

        $appointment_id = $this->CI->appointment_verifications_model->create_vormerkung(
            $appointment,
            $this->CI->appointment_contact_rules->contact($customer),
            $this->CI->email_verification_rules->booking_verification($appointment, $customer, $token, $now)
        );

        try {
            $this->send_link($token, $appointment, $email, false);
        } catch (Throwable $e) {
            log_message(
                'error',
                'Email verification mail for appointment ' . $appointment_id . ': ' . $e->getMessage()
            );

            $this->CI->appointment_verifications_model->delete_vormerkung($appointment_id);

            throw new RuntimeException(lang('email_verification_send_failed'));
        }

        $this->CI->email_verification_session->remember_pending($appointment_id);

        $this->CI->email_verification_session->remember_own(
            (int) $this->CI->appointment_verifications_model->find_by_appointment($appointment_id)['id']
        );

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
     * @param array|null $original_appointment Stored appointment of the manage link (null for a new booking).
     * @param array $customer Customer fields entered in the reschedule (email replaced when held back).
     *
     * @return string|null Returns the entered new email when it waits for verification, otherwise null.
     */
    public function hold_back_email_change(?array $original_appointment, array &$customer): ?string
    {
        if ($original_appointment === null) {
            return null;
        }

        $stored_email = $this->appointment_email($original_appointment);

        if (
            !$this->CI->email_verification_rules->requires_email_change_verification(
                $this->enabled(),
                $stored_email,
                $customer['email'] ?? null,
                $this->CI->email_verification_session->verified_emails()
            )
        ) {
            return null;
        }

        $email = trim((string) $customer['email']);

        $customer['email'] = $stored_email;

        return $email;
    }

    /**
     * Send the verification link for the new email of a saved reschedule (purpose "email_change").
     *
     * A previous open email change of the appointment is replaced (its link becomes invalid). The reschedule stays
     * saved when no link goes out (hourly limit of the new email reached, mail failure); the email then simply stays
     * the old one.
     *
     * @param array $appointment Saved appointment of the reschedule.
     * @param string|null $email New email returned by hold_back_email_change() (null: nothing to do).
     *
     * @return bool Returns whether the link was sent.
     */
    public function start_email_change(array $appointment, ?string $email): bool
    {
        if ($email === null) {
            return false;
        }

        $now = new DateTimeImmutable('now');

        if ($this->reserve_mail($email, false, $now)['decision'] !== Email_verification_rules::SEND_ALLOWED) {
            log_message('info', 'Email change of appointment ' . $appointment['id'] . ' not sent: hourly limit.');

            return false;
        }

        $token = $this->new_token();

        $model = $this->CI->appointment_verifications_model;

        $model->delete_for_appointment((int) $appointment['id'], Email_verification_rules::PURPOSE_EMAIL_CHANGE);

        $verification_id = $model->insert(
            $this->CI->email_verification_rules->email_change_verification($appointment, $email, $token, $now)
        );

        try {
            $this->send_link($token, $appointment, $email, true);
        } catch (Throwable $e) {
            log_message(
                'error',
                'Email change verification mail for appointment ' . $appointment['id'] . ': ' . $e->getMessage()
            );

            $model->take($verification_id);

            return false;
        }

        $this->CI->email_verification_session->remember_own($verification_id);

        return true;
    }

    /**
     * Send the verification mail of the Vormerkung started in this browser session again, with a new link.
     *
     * The Vormerkung comes from the browser session only, so nobody can trigger mails to other addresses, and the
     * answer never contains the manage link hash or tells whether an address is known. A resend waits 60 seconds
     * after the last mail and counts against the limit of 5 mails per email and hour. The new link replaces the old
     * one; the Vormerkung holds its slot 10 minutes from the new mail, at most 20 minutes after the submit.
     *
     * @return array Returns ['sent' => bool, 'message' => string, 'wait_seconds' => int] for the booking wizard.
     *
     * @throws Throwable
     */
    public function resend(): array
    {
        $rules = $this->CI->email_verification_rules;

        $appointment_id = $this->CI->email_verification_session->pending_appointment_id();

        $this->cleanup_expired();

        $verification = $appointment_id
            ? $this->CI->appointment_verifications_model->find_by_appointment($appointment_id)
            : null;

        $appointment = $verification ? $this->find_appointment($appointment_id) : null;

        $now = new DateTimeImmutable('now');

        if (
            !$verification ||
            $rules->confirm_action($verification, $appointment, $now) !== Email_verification_rules::CONFIRM_HELD
        ) {
            return ['sent' => false, 'message' => lang('email_verification_resend_unavailable'), 'wait_seconds' => 0];
        }

        $decision = $this->reserve_mail($verification['email'], true, $now);

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
            $this->send_link($token, $appointment, $verification['email'], false);
        } catch (Throwable $e) {
            log_message(
                'error',
                'Email verification resend for appointment ' . $appointment_id . ': ' . $e->getMessage()
            );

            // The previous link stays valid when the new one could not be sent.
            $this->CI->appointment_verifications_model->update((int) $verification['id'], $previous);

            throw new RuntimeException(lang('email_verification_send_failed'));
        }

        return [
            'sent' => true,
            'message' => lang('email_verification_resent'),
            'wait_seconds' => Email_verification_rules::RESEND_WAIT_SECONDS
        ];
    }

    /**
     * Delete expired Vormerkungen (their slots become free, their links restorable) and void restore data.
     *
     * Runs before availability requests, on submit, when the backend calendar loads and in `console cleanup`, so
     * no cron job is needed. Ended send limits are deleted as well.
     *
     * @return array Returns ['appointments' => int, 'verifications' => int, 'limits' => int] with the numbers of
     * deleted rows.
     *
     * @throws Throwable
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
     * Open a verification link: the confirm button, the confirmation itself, or "Link ungültig".
     *
     * Opening the link changes nothing (mail scanners open links) and only shows the button, see confirm(). Only in
     * the browser session that started this verification does opening the link confirm right away, with the same
     * outcomes as the button.
     *
     * @param string $token Token from the link.
     * @param array $allowed_customer_fields Customer fields an online booking may write.
     * @param callable $is_available Availability check of the booking flow: fn(array $appointment): bool.
     *
     * @return array Returns the outcome of confirm(), or ['page' => array] with the button or "Link ungültig".
     *
     * @throws Throwable
     */
    public function open_link(string $token, array $allowed_customer_fields, callable $is_available): array
    {
        $rules = $this->CI->email_verification_rules;

        $verification = preg_match(self::TOKEN_PATTERN, $token)
            ? $this->CI->appointment_verifications_model->find_by_token_hash($rules->token_hash($token))
            : null;

        if (
            !$verification ||
            $rules->state($verification, new DateTimeImmutable('now')) === Email_verification_rules::STATE_VOID
        ) {
            return ['page' => $this->CI->email_verification_pages->invalid()];
        }

        if ($this->CI->email_verification_session->owns($verification)) {
            return $this->confirm($token, $allowed_customer_fields, $is_available);
        }

        return ['page' => $this->CI->email_verification_pages->confirm($token, $verification['purpose'])];
    }

    /**
     * Confirm a verification with the token from the link (the confirm button).
     *
     * A Vormerkung that holds its slot is booked: the customer with the verified email is assigned (or created) and
     * updated by the rules of spec 0017. A Vormerkung released by the expiry cleanup is booked again when
     * $is_available confirms its slot is still free (late click), otherwise the page "Leider abgelaufen" follows.
     * When the email's customer already has an appointment at that time, the Vormerkung is dropped and a neutral
     * page follows (checked only now, so the booking form never told whether the email is known). The link of an
     * email change takes the new email over while it is valid.
     *
     * The verification is locked for the whole check and write, so a concurrent click, resend or expiry cleanup
     * cannot change it in between, and the link works only once. The email counts as verified in the current
     * browser session afterwards.
     *
     * @param mixed $token Token from the request.
     * @param array $allowed_customer_fields Customer fields an online booking may write.
     * @param callable $is_available Availability check of the booking flow: fn(array $appointment): bool.
     *
     * @return array Returns ['page' => array] for Email_verification_pages::show(), or the confirmed appointment:
     * ['appointment_id' => int, 'customer_id' => int, 'manage_mode' => bool, 'notify' => bool] with the arguments of
     * the booking flow's follow-up (a confirmed email change is a reschedule whose mails went out already).
     *
     * @throws Throwable
     */
    public function confirm(mixed $token, array $allowed_customer_fields, callable $is_available): array
    {
        if (!is_string($token) || !preg_match(self::TOKEN_PATTERN, $token)) {
            return ['page' => $this->CI->email_verification_pages->invalid()];
        }

        $this->cleanup_expired();

        $rules = $this->CI->email_verification_rules;

        $this->CI->db->trans_begin();

        try {
            $verification = $this->CI->appointment_verifications_model->lock_by_token_hash($rules->token_hash($token));

            $appointment = !empty($verification['id_appointments'])
                ? $this->find_appointment((int) $verification['id_appointments'])
                : null;

            $action = $verification
                ? $rules->confirm_action($verification, $appointment, new DateTimeImmutable('now'))
                : Email_verification_rules::CONFIRM_INVALID;

            $outcome = match ($action) {
                Email_verification_rules::CONFIRM_HELD => $this->book(
                    $verification,
                    $appointment,
                    $allowed_customer_fields
                ),
                Email_verification_rules::CONFIRM_RESTORE => $this->restore(
                    $verification,
                    $allowed_customer_fields,
                    $is_available
                ),
                Email_verification_rules::CONFIRM_EMAIL_CHANGE => $this->take_email_change(
                    $verification,
                    $appointment,
                    $allowed_customer_fields
                ),
                default => ['page' => $this->CI->email_verification_pages->invalid()]
            };

            $this->CI->db->trans_commit();
        } catch (Throwable $e) {
            $this->CI->db->trans_rollback();

            throw $e;
        }

        if (!isset($outcome['page'])) {
            $this->CI->email_verification_session->remember_verified($verification['email']);
        }

        return $outcome;
    }

    /**
     * Get the settings the mail templates of the booking flow use.
     *
     * @return array
     */
    public function mail_settings(): array
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

    /**
     * Book a Vormerkung for the customer of its verified email (inside the transaction of confirm()).
     *
     * @param array $verification Locked verification record.
     * @param array $appointment The held Vormerkung, or the appointment restored from the verification (no ID).
     * @param array $allowed_customer_fields Customer fields an online booking may write.
     *
     * @return array Returns the outcome of confirm().
     *
     * @throws Throwable
     */
    protected function book(array $verification, array $appointment, array $allowed_customer_fields): array
    {
        $model = $this->CI->appointment_verifications_model;

        $customer = $this->CI->email_verification_rules->customer_input($verification);

        $this->CI->customers_model->only($customer, $allowed_customer_fields);

        $email_customer_id = $this->CI->customers_model->exists($customer)
            ? $this->CI->customers_model->find_record_id($customer)
            : 0;

        // The check of the booking form runs here for a Vormerkung (spec 0018, overlap rule spec 0023): the
        // Vormerkung is dropped.
        if (
            $email_customer_id &&
            $this->CI->customer_overlap->exists(
                $email_customer_id,
                $appointment['start_datetime'],
                $appointment['end_datetime'],
                empty($appointment['id']) ? null : (int) $appointment['id']
            )
        ) {
            if (empty($appointment['id'])) {
                $model->take((int) $verification['id']);
            } else {
                $model->delete_vormerkung((int) $appointment['id']);
            }

            return ['page' => $this->CI->email_verification_pages->not_booked()];
        }

        $existing_customer = $email_customer_id ? $this->CI->customers_model->find($email_customer_id) : null;

        $customer_record = $this->CI->appointment_contact_rules->customer_record(
            $customer,
            $existing_customer,
            $email_customer_id ?: null
        );

        $this->CI->customers_model->only($customer_record, $allowed_customer_fields);

        $model->take((int) $verification['id']);

        $is_restored = empty($appointment['id']);

        $customer_id = $email_customer_id ?: $this->CI->customers_model->save($customer_record);

        $appointment['id_users_customer'] = $customer_id;

        $appointment_id = $this->CI->appointments_model->save($appointment);

        if ($is_restored) {
            $this->CI->appointment_contacts_model->save_for_appointment(
                $appointment_id,
                $this->CI->appointment_contact_rules->contact($customer)
            );
        }

        if ($existing_customer) {
            $this->CI->customers_model->save($customer_record);
        }

        return [
            'appointment_id' => (int) $appointment_id,
            'customer_id' => (int) $customer_id,
            'manage_mode' => false,
            'notify' => true
        ];
    }

    /**
     * Book a Vormerkung released by the expiry cleanup again when its slot is still free (late click).
     *
     * The availability check runs inside the transaction of confirm(); the restore data stays when the slot is taken.
     *
     * @param array $verification Locked verification record.
     * @param array $allowed_customer_fields Customer fields an online booking may write.
     * @param callable $is_available Availability check of the booking flow: fn(array $appointment): bool.
     *
     * @return array Returns the outcome of confirm().
     *
     * @throws Throwable
     */
    protected function restore(array $verification, array $allowed_customer_fields, callable $is_available): array
    {
        $appointment = $this->restored_appointment($verification);

        if (!$is_available($appointment)) {
            return ['page' => $this->CI->email_verification_pages->expired()];
        }

        return $this->book($verification, $appointment, $allowed_customer_fields);
    }

    /**
     * Take over the new email of a reschedule (inside the transaction of confirm(), ticket 0018/05).
     *
     * The appointment contact gets the new email; the customer record only when no other customer has it (0017).
     *
     * @param array $verification Locked verification with purpose "email_change".
     * @param array $appointment Appointment of the verification (with customer).
     * @param array $allowed_customer_fields Customer fields an online booking may write.
     *
     * @return array Returns the outcome of confirm().
     *
     * @throws Throwable
     */
    protected function take_email_change(array $verification, array $appointment, array $allowed_customer_fields): array
    {
        $contact_rules = $this->CI->appointment_contact_rules;

        $email = trim(
            (string) ($this->CI->email_verification_rules->customer_input($verification)['email'] ??
                $verification['email'])
        );

        $customer_id = (int) $appointment['id_users_customer'];

        $existing_customer = $this->CI->customers_model->find($customer_id);

        $email_owner_id = $this->CI->customers_model->exists(['email' => $email])
            ? $this->CI->customers_model->find_record_id(['email' => $email])
            : null;

        $customer_record = $contact_rules->customer_record(['email' => $email], $existing_customer, $email_owner_id);

        $this->CI->customers_model->only($customer_record, $allowed_customer_fields);

        $stored_contact = $this->CI->appointment_contacts_model->find_by_appointment((int) $appointment['id']);

        $contact = $contact_rules->contact(['email' => $email] + ($stored_contact ?? $existing_customer));

        $this->CI->appointment_verifications_model->take((int) $verification['id']);

        $this->CI->appointment_contacts_model->save_for_appointment((int) $appointment['id'], $contact);

        $this->CI->customers_model->save($customer_record);

        return [
            'appointment_id' => (int) $appointment['id'],
            'customer_id' => $customer_id,
            'manage_mode' => true,
            'notify' => false
        ];
    }

    /**
     * Check and record a verification mail against the send limit of its email in one step.
     *
     * @param string $email Recipient.
     * @param bool $resend Whether the mail is a resend for an existing Vormerkung (60 seconds pause).
     * @param DateTimeImmutable $now Time of the mail.
     *
     * @return array Returns the decision ['decision' => SEND_*, 'wait_seconds' => int]; an allowed mail is counted.
     */
    protected function reserve_mail(string $email, bool $resend, DateTimeImmutable $now): array
    {
        $rules = $this->CI->email_verification_rules;

        return $this->CI->appointment_verifications_model->reserve_send($rules->normalize_email($email), function (
            ?array $limit
        ) use ($rules, $email, $resend, $now): array {
            $decision = $rules->send_decision($limit, $now, $resend);

            return [
                'decision' => $decision,
                'limit' =>
                    $decision['decision'] === Email_verification_rules::SEND_ALLOWED
                        ? $rules->limit_after_send($limit, $email, $now)
                        : null
            ];
        });
    }

    /**
     * Send a verification link mail.
     *
     * @param string $token Token of the link.
     * @param array $appointment Appointment (service, provider, times).
     * @param string $email Recipient.
     * @param bool $email_change Whether the link confirms a changed email of a reschedule.
     *
     * @throws Exception
     */
    protected function send_link(string $token, array $appointment, string $email, bool $email_change): void
    {
        $this->CI->email_messages->send_email_verification_link(
            site_url('booking/verify/' . $token),
            $appointment,
            $this->CI->services_model->find((int) $appointment['id_services']),
            $this->CI->providers_model->find((int) $appointment['id_users_provider']),
            $email,
            $this->mail_settings(),
            $email_change
        );
    }

    /**
     * Find a stored appointment.
     *
     * @param int $appointment_id Appointment ID.
     *
     * @return array|null Returns the appointment or null when it is gone.
     */
    protected function find_appointment(int $appointment_id): ?array
    {
        return $this->CI->appointments_model->get(['id' => $appointment_id])[0] ?? null;
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
     * Check whether the setting "require_email_verification" is on.
     *
     * @return bool
     */
    protected function enabled(): bool
    {
        return filter_var(setting('require_email_verification', '0'), FILTER_VALIDATE_BOOLEAN);
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
}
