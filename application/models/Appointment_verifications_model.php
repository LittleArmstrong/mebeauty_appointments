<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Appointment verifications model (fork, spec 0018, ADR 0008).
 *
 * Stores the email verifications of appointments. A row with purpose "booking" marks its appointment as
 * "Vormerkung": the appointment holds its slot but has no customer until the link is clicked. Only the SHA-256 hash
 * of the token is stored. The restore data (service, provider, start, end, notes, customer form data) stays when the
 * appointment is deleted (foreign key ON DELETE SET NULL); only the expiry cleanup marks it "restorable", so a
 * Vormerkung deleted any other way (staff, API, deleted provider) cannot come back by a late click. The rules live
 * in the Email_verification_rules library.
 *
 * The model writes customer-less appointments of `appointments` directly (create_vormerkung(), delete_vormerkung(),
 * delete_expired()): Appointments_model::save() requires a customer, and the deletes must only hit appointments
 * that still have none (a concurrent click may have confirmed them), which Appointments_model::delete() cannot
 * express.
 *
 * @package Models
 */
class Appointment_verifications_model extends EA_Model
{
    /**
     * @var array
     */
    protected array $casts = [
        'id' => 'integer',
        'id_appointments' => 'integer',
        'send_count' => 'integer',
        'id_services' => 'integer',
        'id_users_provider' => 'integer',
        'restorable' => 'boolean'
    ];

    /**
     * Appointment_verifications_model constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('appointment_contacts_model');
    }

    /**
     * Store a Vormerkung: the appointment without customer, its contact and its verification, in one transaction.
     *
     * The appointment is not validated by Appointments_model::save() (which requires a customer); the caller has
     * checked its availability and completed it like a regular online booking.
     *
     * @param array $appointment Appointment fields without customer.
     * @param array $contact Appointment contact (see Appointment_contact_rules::FIELDS).
     * @param array $verification Verification record (see Email_verification_rules::booking_verification()).
     *
     * @return int Returns the ID of the new appointment.
     *
     * @throws Throwable
     */
    public function create_vormerkung(array $appointment, array $contact, array $verification): int
    {
        $now = date('Y-m-d H:i:s');

        unset($appointment['id']);

        $appointment['id_users_customer'] = null;
        $appointment['book_datetime'] = $now;
        $appointment['create_datetime'] = $now;
        $appointment['update_datetime'] = $now;
        $appointment['hash'] = random_string('alnum', 12);

        $this->db->trans_begin();

        try {
            if (!$this->db->insert('appointments', $appointment)) {
                throw new RuntimeException('Could not insert the Vormerkung.');
            }

            $appointment_id = (int) $this->db->insert_id();

            $this->appointment_contacts_model->save_for_appointment($appointment_id, $contact);

            $this->insert(['id_appointments' => $appointment_id] + $verification);

            $this->db->trans_commit();
        } catch (Throwable $e) {
            $this->db->trans_rollback();

            throw $e;
        }

        return $appointment_id;
    }

    /**
     * Insert a verification record.
     *
     * @param array $verification Verification record.
     *
     * @return int Returns the ID of the new record.
     *
     * @throws RuntimeException
     */
    public function insert(array $verification): int
    {
        // A booking verification brings its submit time (the limit of its slot hold, spec 0018).
        $verification['create_datetime'] ??= date('Y-m-d H:i:s');
        $verification['update_datetime'] = date('Y-m-d H:i:s');

        if (!$this->db->insert('appointment_verifications', $verification)) {
            throw new RuntimeException('Could not insert the appointment verification.');
        }

        return (int) $this->db->insert_id();
    }

    /**
     * Find the verification with a token hash.
     *
     * @param string $token_hash SHA-256 hash of the token.
     *
     * @return array|null Returns the verification or null.
     */
    public function find_by_token_hash(string $token_hash): ?array
    {
        return $this->first($this->db->where('token_hash', $token_hash));
    }

    /**
     * Find the verification with a token hash and lock it until the current transaction ends.
     *
     * A concurrent click, resend or expiry cleanup waits for the lock, so the state checked by the caller stays
     * true until it commits.
     *
     * @param string $token_hash SHA-256 hash of the token.
     *
     * @return array|null Returns the verification or null.
     */
    public function lock_by_token_hash(string $token_hash): ?array
    {
        $verification = $this->db
            ->query(
                'SELECT * FROM `' .
                    $this->db->dbprefix('appointment_verifications') .
                    '` WHERE `token_hash` = ? FOR UPDATE',
                [$token_hash]
            )
            ->row_array();

        if (!$verification) {
            return null;
        }

        $this->cast($verification);

        return $verification;
    }

    /**
     * Find the verification of an appointment.
     *
     * @param int $appointment_id Appointment ID.
     *
     * @return array|null Returns the verification or null when the appointment has none.
     */
    public function find_by_appointment(int $appointment_id): ?array
    {
        return $this->find_by_appointments([$appointment_id])[$appointment_id] ?? null;
    }

    /**
     * Find the verifications of several appointments (e.g. to mark Vormerkungen in a calendar).
     *
     * @param array $appointment_ids Appointment IDs.
     *
     * @return array Returns the verifications indexed by appointment ID.
     */
    public function find_by_appointments(array $appointment_ids): array
    {
        $appointment_ids = array_values(array_unique(array_map('intval', $appointment_ids)));

        if ($appointment_ids === []) {
            return [];
        }

        $verifications = [];

        foreach (
            $this->db->where_in('id_appointments', $appointment_ids)->get('appointment_verifications')->result_array()
            as $verification
        ) {
            $this->cast($verification);
            $verifications[$verification['id_appointments']] = $verification;
        }

        return $verifications;
    }

    /**
     * Find the newest verification of an email.
     *
     * @param string $email Normalized email.
     *
     * @return array|null Returns the verification or null.
     */
    public function find_newest_by_email(string $email): ?array
    {
        return $this->first($this->db->where('email', $email)->order_by('id', 'DESC'));
    }

    /**
     * Take a verification for confirmation: delete it, so its link works only once.
     *
     * @param int $verification_id Verification ID.
     *
     * @return bool Returns false when the verification was already gone (e.g. a concurrent click took it).
     */
    public function take(int $verification_id): bool
    {
        $this->db->delete('appointment_verifications', ['id' => $verification_id]);

        return $this->db->affected_rows() === 1;
    }

    /**
     * Delete the verifications of an appointment with a purpose (e.g. a replaced email change).
     *
     * @param int $appointment_id Appointment ID.
     * @param string $purpose Purpose of the verifications.
     */
    public function delete_for_appointment(int $appointment_id, string $purpose): void
    {
        $this->db->delete('appointment_verifications', ['id_appointments' => $appointment_id, 'purpose' => $purpose]);
    }

    /**
     * Delete a Vormerkung with its verification (only an appointment without customer).
     *
     * @param int $appointment_id Appointment ID.
     */
    public function delete_vormerkung(int $appointment_id): void
    {
        $this->db->delete('appointment_verifications', ['id_appointments' => $appointment_id]);
        $this->db->delete('appointments', ['id' => $appointment_id, 'id_users_customer' => null]);
    }

    /**
     * Delete expired Vormerkungen and void verifications (cleanup, spec 0018).
     *
     * An expired verification with a purpose in $purposes_freeing_slot loses its appointment (only one without
     * customer) and becomes "restorable": it stays as restore data for a late click (foreign key ON DELETE SET
     * NULL). The rows are locked first, so a concurrent click either confirms before or finds them released. A
     * void verification is deleted.
     *
     * @param string $expired_cutoff Verifications with expires_datetime <= this are expired (Y-m-d H:i:s).
     * @param string $void_cutoff Verifications with last_sent_datetime <= this are void (Y-m-d H:i:s).
     * @param array $purposes_freeing_slot Purposes whose expiry deletes the appointment.
     *
     * @return array Returns ['appointments' => int, 'verifications' => int] with the numbers of deleted rows.
     *
     * @throws Throwable
     */
    public function delete_expired(string $expired_cutoff, string $void_cutoff, array $purposes_freeing_slot): array
    {
        $deleted = ['appointments' => 0, 'verifications' => 0];

        if ($purposes_freeing_slot !== []) {
            $this->db->trans_begin();

            try {
                $expired = $this->db
                    ->query(
                        'SELECT `v`.`id`, `v`.`id_appointments` FROM `' .
                            $this->db->dbprefix('appointment_verifications') .
                            '` `v` JOIN `' .
                            $this->db->dbprefix('appointments') .
                            '` `a` ON `a`.`id` = `v`.`id_appointments` AND `a`.`id_users_customer` IS NULL ' .
                            'WHERE `v`.`expires_datetime` <= ? AND `v`.`purpose` IN ? FOR UPDATE',
                        [$expired_cutoff, array_values($purposes_freeing_slot)]
                    )
                    ->result_array();

                if ($expired !== []) {
                    $this->db
                        ->where_in('id', array_map('intval', array_column($expired, 'id')))
                        ->update('appointment_verifications', ['restorable' => 1]);

                    $this->db
                        ->where_in('id', array_map('intval', array_column($expired, 'id_appointments')))
                        ->where('id_users_customer IS NULL')
                        ->delete('appointments');

                    $deleted['appointments'] = $this->db->affected_rows();
                }

                $this->db->trans_commit();
            } catch (Throwable $e) {
                $this->db->trans_rollback();

                throw $e;
            }
        }

        $this->db->where('last_sent_datetime <=', $void_cutoff)->delete('appointment_verifications');

        $deleted['verifications'] = $this->db->affected_rows();

        return $deleted;
    }

    /**
     * Change the fields of a verification (e.g. a new link after a resend).
     *
     * @param int $verification_id Verification ID.
     * @param array $fields Changed fields.
     *
     * @return bool Returns false when the verification is gone.
     */
    public function update(int $verification_id, array $fields): bool
    {
        $fields['update_datetime'] = date('Y-m-d H:i:s');

        $this->db->update('appointment_verifications', $fields, ['id' => $verification_id]);

        return $this->db->affected_rows() === 1;
    }

    /**
     * Find the send limit of an email (spec 0018/03).
     *
     * @param string $email Normalized email.
     *
     * @return array|null Returns email, window_start_datetime, window_count and last_sent_datetime, or null when no
     * mail went to the email in a current window.
     */
    public function find_limit(string $email): ?array
    {
        $limit = $this->db
            ->select('email, window_start_datetime, window_count, last_sent_datetime')
            ->get_where('email_verification_limits', ['email' => $email], 1)
            ->row_array();

        if (!$limit) {
            return null;
        }

        $limit['window_count'] = (int) $limit['window_count'];

        return $limit;
    }

    /**
     * Check and record a verification mail against the send limit of an email in one step (spec 0018/03).
     *
     * A named database lock per email serializes concurrent requests, so two of them cannot both pass the check
     * before either records its mail.
     *
     * @param string $email Normalized email.
     * @param callable $decide fn(?array $limit): array returning ['decision' => array, 'limit' => ?array]: the
     * decision for the caller and the limit to store (null stores nothing).
     *
     * @return array Returns the decision.
     *
     * @throws RuntimeException When the lock cannot be taken.
     */
    public function reserve_send(string $email, callable $decide): array
    {
        $lock = 'ea_email_verification_' . sha1($email);

        if ((int) $this->db->query('SELECT GET_LOCK(?, 10) AS `locked`', [$lock])->row()->locked !== 1) {
            throw new RuntimeException('Could not lock the email verification send limit.');
        }

        try {
            $result = $decide($this->find_limit($email));

            if ($result['limit'] !== null) {
                $this->db->replace('email_verification_limits', $result['limit']);
            }

            return $result['decision'];
        } finally {
            $this->db->query('SELECT RELEASE_LOCK(?)', [$lock]);
        }
    }

    /**
     * Delete send limits that count no more (window ended, resend pause over).
     *
     * @param string $cutoff Limits with last_sent_datetime <= this have ended (Y-m-d H:i:s).
     *
     * @return int Returns the number of deleted limits.
     */
    public function delete_ended_limits(string $cutoff): int
    {
        $this->db->where('last_sent_datetime <=', $cutoff)->delete('email_verification_limits');

        return $this->db->affected_rows();
    }

    /**
     * Get the first verification of a prepared query.
     *
     * @param CI_DB_query_builder $query Query with where/order conditions.
     *
     * @return array|null Returns the verification or null.
     */
    protected function first(CI_DB_query_builder $query): ?array
    {
        $verification = $query->get('appointment_verifications', 1)->row_array();

        if (!$verification) {
            return null;
        }

        $this->cast($verification);

        return $verification;
    }
}
