<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Appointment verifications model (fork, spec 0018, ADR 0008).
 *
 * Stores the email verifications of appointments. A row with purpose "booking" marks its appointment as
 * "Vormerkung": the appointment holds its slot but has no customer until the link is clicked. Only the SHA-256 hash
 * of the token is stored. The restore data (service, provider, start, end, notes, customer form data) stays when the
 * appointment is deleted (foreign key ON DELETE SET NULL). The rules live in the Email_verification_rules library.
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
        'id_users_provider' => 'integer'
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
        $verification['create_datetime'] = date('Y-m-d H:i:s');
        $verification['update_datetime'] = $verification['create_datetime'];

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
