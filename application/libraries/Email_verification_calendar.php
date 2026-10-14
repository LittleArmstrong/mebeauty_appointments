<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email verification calendar library (fork, spec 0018/04, ADR 0008).
 *
 * Handles Vormerkungen (appointments without customer that wait for the customer's verification link) where staff
 * and external calendars see appointments: the backend calendar marks them "unbestätigt", staff can delete but not
 * edit or confirm them, their deletion is not announced, and Google/CalDAV sync leaves them out until confirmed.
 *
 * @package Libraries
 */
class Email_verification_calendar
{
    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    /**
     * Email_verification_calendar constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->model('customers_model');
        $this->CI->load->model('appointment_verifications_model');

        $this->CI->load->library('email_verification_rules');
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
     * Check whether an appointment is a Vormerkung.
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
     * Refuse a staff change of a Vormerkung: staff can delete it, but neither edit nor confirm it.
     *
     * @param array|null $appointment Stored appointment (null for a new one).
     *
     * @throws RuntimeException When the appointment is a Vormerkung.
     */
    public function check_staff_editable(?array $appointment): void
    {
        if ($appointment && $this->is_unconfirmed((int) $appointment['id'])) {
            throw new RuntimeException(lang('appointment_unconfirmed_not_editable'));
        }
    }

    /**
     * Check whether the deletion of an appointment is announced (notifications, webhook).
     *
     * A Vormerkung was never confirmed, so nobody learns about its deletion. Call it before deleting.
     *
     * @param int $appointment_id Appointment ID.
     *
     * @return bool
     */
    public function announces_deletion(int $appointment_id): bool
    {
        return !$this->is_unconfirmed($appointment_id);
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
     * Get the customer record of an appointment; a Vormerkung has none yet.
     *
     * @param array $appointment Appointment with id_users_customer.
     *
     * @return array Returns the customer record or an empty array.
     */
    public function customer_of(array $appointment): array
    {
        return empty($appointment['id_users_customer'])
            ? []
            : $this->CI->customers_model->find((int) $appointment['id_users_customer']);
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
}
