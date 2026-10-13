<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Appointment contacts model (fork, spec 0017, ADR 0008).
 *
 * Stores the "Termin-Kontaktdaten" of an appointment: first name, last name, email and phone number as given for
 * this appointment. At most one row per appointment; the row is deleted together with its appointment (foreign key).
 * The rules that decide what is stored live in the Appointment_contact_rules library.
 *
 * @package Models
 */
class Appointment_contacts_model extends EA_Model
{
    /**
     * @var array
     */
    protected array $casts = [
        'id' => 'integer',
        'id_appointments' => 'integer'
    ];

    /**
     * Appointment_contacts_model constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('appointment_contact_rules');
    }

    /**
     * Find the contact of an appointment.
     *
     * @param int $appointment_id Appointment ID.
     *
     * @return array|null Returns the contact or null when the appointment has none (e.g. older appointments).
     */
    public function find_by_appointment(int $appointment_id): ?array
    {
        return $this->find_by_appointments([$appointment_id])[$appointment_id] ?? null;
    }

    /**
     * Find the contacts of several appointments.
     *
     * @param array $appointment_ids Appointment IDs.
     *
     * @return array Returns the contacts indexed by appointment ID; appointments without contact are missing.
     */
    public function find_by_appointments(array $appointment_ids): array
    {
        $appointment_ids = array_values(array_unique(array_map('intval', $appointment_ids)));

        if ($appointment_ids === []) {
            return [];
        }

        $contacts = [];

        foreach (
            $this->db->where_in('id_appointments', $appointment_ids)->get('appointment_contacts')->result_array()
            as $contact
        ) {
            $this->cast($contact);
            $contacts[$contact['id_appointments']] = $contact;
        }

        return $contacts;
    }

    /**
     * Show the appointment contact in the "customer" data of loaded appointments (fallback: customer record).
     *
     * @param array $appointments Appointments with a loaded "customer" entry.
     */
    public function apply_to_customers(array &$appointments): void
    {
        $contacts = $this->find_by_appointments(array_column($appointments, 'id'));

        foreach ($appointments as &$appointment) {
            if (!empty($appointment['customer']) && is_array($appointment['customer'])) {
                $appointment['customer'] = $this->appointment_contact_rules->display_customer(
                    $appointment['customer'],
                    $contacts[(int) $appointment['id']] ?? null
                );
            }
        }
    }

    /**
     * Get the customer data to display for one appointment: its contact over the customer record.
     *
     * @param array $appointment Appointment (an appointment without ID shows the record).
     * @param array $customer Customer record (empty without customer).
     *
     * @return array Returns the customer data to display.
     */
    public function display_customer(array $appointment, array $customer): array
    {
        if (empty($customer) || empty($appointment['id'])) {
            return $customer;
        }

        return $this->appointment_contact_rules->display_customer(
            $customer,
            $this->find_by_appointment((int) $appointment['id'])
        );
    }

    /**
     * Insert or replace the contact of an appointment.
     *
     * @param int $appointment_id Appointment ID.
     * @param array $contact Contact fields (see Appointment_contact_rules::FIELDS); other keys are ignored.
     *
     * @throws RuntimeException
     */
    public function save_for_appointment(int $appointment_id, array $contact): void
    {
        $data = array_intersect_key($contact, array_flip(Appointment_contact_rules::FIELDS));
        $data['update_datetime'] = date('Y-m-d H:i:s');

        if ($this->find_by_appointment($appointment_id)) {
            $saved = $this->db->update('appointment_contacts', $data, ['id_appointments' => $appointment_id]);
        } else {
            $data['id_appointments'] = $appointment_id;
            $data['create_datetime'] = $data['update_datetime'];
            $saved = $this->db->insert('appointment_contacts', $data);
        }

        if (!$saved) {
            throw new RuntimeException('Could not save the appointment contact.');
        }
    }

    /**
     * Delete the contact of an appointment.
     *
     * @param int $appointment_id Appointment ID.
     */
    public function delete_by_appointment(int $appointment_id): void
    {
        $this->db->delete('appointment_contacts', ['id_appointments' => $appointment_id]);
    }
}
