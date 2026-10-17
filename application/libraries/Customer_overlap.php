<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Customer overlap library.
 *
 * Single check whether a customer already has an appointment during a requested time (spec 0023). The booking form
 * (Booking::register(), also for a Manage-Link reschedule) and the confirmation of a Vormerkung
 * (Email_verification) call it; the backend calendar and API v1 do not.
 *
 * Two appointments overlap when the existing one starts before the requested end and ends after the requested start.
 * The comparisons are strict, so back-to-back appointments do not overlap. Only appointments of the same customer
 * count, never unavailabilities, and never the appointment being rescheduled.
 *
 * @package Libraries
 */
class Customer_overlap
{
    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    /**
     * Customer_overlap constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->model('appointments_model');
    }

    /**
     * Check whether the customer has an appointment that overlaps the requested time.
     *
     * @param int $customer_id Customer ID.
     * @param string $start_datetime Requested start (Y-m-d H:i:s).
     * @param string $end_datetime Requested end (Y-m-d H:i:s).
     * @param int|null $exclude_appointment_id The appointment being rescheduled, if any.
     *
     * @return bool
     */
    public function exists(
        int $customer_id,
        string $start_datetime,
        string $end_datetime,
        ?int $exclude_appointment_id = null
    ): bool {
        $where = [
            'id_users_customer' => $customer_id,
            'start_datetime <' => $end_datetime,
            'end_datetime >' => $start_datetime
        ];

        if ($exclude_appointment_id) {
            $where['id !='] = $exclude_appointment_id;
        }

        // Appointments_model::get() leaves out unavailabilities.
        return $this->CI->appointments_model->get($where, 1) !== [];
    }
}
