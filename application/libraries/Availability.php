<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.4.0
 * ---------------------------------------------------------------------------- */

/**
 * Availability library.
 *
 * Handles availability related functionality.
 *
 * @package Libraries
 */
class Availability
{
    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    /**
     * @var int|null Global buffer time in minutes (read once per instance).
     */
    protected ?int $buffer_time = null;

    /**
     * @var bool|null Whether the buffer also applies before the working day end (read once per instance).
     */
    protected ?bool $end_of_day_buffer_enabled = null;

    /**
     * Availability constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->model('admins_model');
        $this->CI->load->model('appointments_model');
        $this->CI->load->model('providers_model');
        $this->CI->load->model('secretaries_model');
        $this->CI->load->model('secretaries_model');
        $this->CI->load->model('settings_model');
        $this->CI->load->model('unavailabilities_model');
        $this->CI->load->model('blocked_periods_model');
        $this->CI->load->model('working_plan_exceptions_model');

        $this->CI->load->library('ics_file');
        $this->CI->load->library('booking_policy');
    }

    /**
     * Get the available hours of a provider.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $service Service data.
     * @param array $provider Provider data.
     * @param int|null $exclude_appointment_id Exclude an appointment from the availability generation.
     *
     * @return array
     *
     * @throws Exception
     */
    public function get_available_hours(
        string $date,
        array $service,
        array $provider,
        ?int $exclude_appointment_id = null,
    ): array {
        if ($this->CI->blocked_periods_model->is_entire_date_blocked($date)) {
            return [];
        }

        if ($service['attendants_number'] > 1) {
            $available_hours = $this->consider_multiple_attendants($date, $service, $provider, $exclude_appointment_id);
        } else {
            $available_periods = $this->get_available_periods($date, $provider, $exclude_appointment_id);

            $available_hours = $this->generate_available_hours(
                $date,
                $service,
                $available_periods,
                $this->get_working_day_end($date, $provider),
            );
        }

        $available_hours = $this->consider_slot_capacity($date, $service, $available_hours, $exclude_appointment_id);

        $available_hours = $this->consider_book_advance_timeout($date, $available_hours, $provider);

        return $this->consider_future_booking_limit($date, $available_hours, $provider);
    }

    /**
     * Remove available hours that exceed the service slot capacity across providers.
     *
     * When a service has a slot capacity greater than zero, at most that many appointments may overlap at once
     * regardless of the provider. This covers limited shared resources such as rooms or devices.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $service Service data.
     * @param array $available_hours Already generated available hours.
     * @param int|null $exclude_appointment_id Exclude an appointment from the capacity count.
     *
     * @return array Returns the filtered available hours.
     *
     * @throws Exception
     */
    protected function consider_slot_capacity(
        string $date,
        array $service,
        array $available_hours,
        ?int $exclude_appointment_id = null,
    ): array {
        $slot_capacity = (int) ($service['slot_capacity'] ?? 0);

        if ($slot_capacity <= 0) {
            return $available_hours;
        }

        if (empty($available_hours)) {
            return [];
        }

        $slots = [];
        $window_start = null;
        $window_end = null;

        foreach ($available_hours as $available_hour) {
            $slot_start = new DateTime($date . ' ' . $available_hour);
            $slot_end = clone $slot_start;
            $slot_end->add(new DateInterval('PT' . (int) $service['duration'] . 'M'));

            $slot_start_string = $slot_start->format('Y-m-d H:i:s');
            $slot_end_string = $slot_end->format('Y-m-d H:i:s');

            $slots[] = [
                'hour' => $available_hour,
                'start' => $slot_start_string,
                'end' => $slot_end_string,
            ];

            if ($window_start === null || $slot_start_string < $window_start) {
                $window_start = $slot_start_string;
            }

            if ($window_end === null || $slot_end_string > $window_end) {
                $window_end = $slot_end_string;
            }
        }

        // Fetch every appointment of the service that overlaps the whole slot window once, then count overlaps in PHP.
        $bookings = $this->CI->appointments_model->get_service_slot_bookings(
            (int) $service['id'],
            $window_start,
            $window_end,
            $exclude_appointment_id,
        );

        $filtered_hours = [];

        foreach ($slots as $slot) {
            if (self::count_overlapping_bookings($bookings, $slot['start'], $slot['end']) < $slot_capacity) {
                $filtered_hours[] = $slot['hour'];
            }
        }

        return $filtered_hours;
    }

    /**
     * Count the bookings that overlap the given period.
     *
     * A booking overlaps when it starts before the period end and ends after the period start. Boundaries are
     * exclusive: a booking that ends exactly at the period start, or starts exactly at the period end, does not
     * overlap.
     *
     * @param array $bookings Bookings with `start_datetime` and `end_datetime` keys (Y-m-d H:i:s).
     * @param string $start Period start (Y-m-d H:i:s).
     * @param string $end Period end (Y-m-d H:i:s).
     *
     * @return int Returns the number of overlapping bookings.
     */
    public static function count_overlapping_bookings(array $bookings, string $start, string $end): int
    {
        $count = 0;

        foreach ($bookings as $booking) {
            if ($booking['start_datetime'] < $end && $booking['end_datetime'] > $start) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get multiple attendants hours.
     *
     * This method will add the additional appointment hours whenever a service accepts multiple attendants.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $service Service data.
     * @param array $provider Provider data.
     * @param int|null $exclude_appointment_id Exclude an appointment from the availability generation.
     *
     * @return array Returns the available hours array.
     *
     * @throws Exception
     */
    protected function consider_multiple_attendants(
        string $date,
        array $service,
        array $provider,
        ?int $exclude_appointment_id = null,
    ): array {
        $unavailability_events = $this->CI->unavailabilities_model->get([
            'is_unavailability' => true,
            'DATE(start_datetime) <=' => $date,
            'DATE(end_datetime) >=' => $date,
            'id_users_provider' => $provider['id'],
        ]);

        $working_plan = json_decode($provider['settings']['working_plan'], true);

        $working_plan_exceptions = $this->CI->working_plan_exceptions_model->get_by_provider($provider['id']);

        $working_day = strtolower(date('l', strtotime($date)));

        $date_working_plan = $working_plan[$working_day] ?? null;

        // Search if the $date is a custom availability period added outside the normal working plan.
        if (array_key_exists($date, $working_plan_exceptions)) {
            $date_working_plan = $working_plan_exceptions[$date];
        }

        if (!$date_working_plan) {
            return [];
        }

        $periods = [
            [
                'start' => new DateTime($date . ' ' . $date_working_plan['start']),
                'end' => new DateTime($date . ' ' . $date_working_plan['end']),
            ],
        ];

        $blocked_periods = $this->CI->blocked_periods_model->get_for_period($date, $date);

        $periods = $this->remove_breaks($date, $periods, $date_working_plan['breaks']);
        $periods = $this->remove_unavailability_events($periods, $unavailability_events);
        $periods = $this->remove_unavailability_events($periods, $blocked_periods);

        $hours = [];

        $interval_value = !empty($service['slot_interval']) ? $service['slot_interval'] : 15;
        $interval = new DateInterval('PT' . (int) $interval_value . 'M');
        $duration = new DateInterval('PT' . (int) $service['duration'] . 'M');

        foreach ($periods as $period) {
            $slot_start = clone $period['start'];
            $slot_end = clone $slot_start;
            $slot_end->add($duration);

            $buffer_time = $this->get_buffer_time();

            // The end-of-day buffer only applies to the period that ends at the working day end.
            $end_of_day_buffer = self::end_of_day_buffer_for_period(
                $period['end']->format('H:i'),
                date('H:i', strtotime($date_working_plan['end'])),
                $this->get_end_of_day_buffer(),
            );

            while ($slot_end->getTimestamp() + $end_of_day_buffer * 60 <= $period['end']->getTimestamp()) {
                // The buffer after existing appointments shifts the conflict window backwards.
                $conflict_start = clone $slot_start;

                if ($buffer_time > 0) {
                    $conflict_start->modify('-' . $buffer_time . ' minutes');
                }

                // Make sure there is no other service appointment for this time slot.
                $other_service_attendants_number = $this->CI->appointments_model->get_other_service_attendants_number(
                    $conflict_start,
                    $slot_end,
                    $service['id'],
                    $provider['id'],
                    $exclude_appointment_id,
                );

                if ($other_service_attendants_number > 0) {
                    $slot_start->add($interval);
                    $slot_end->add($interval);
                    continue;
                }

                // Check reserved attendants for this time slot and see if current attendants fit.
                $appointment_attendants_number = $this->CI->appointments_model->get_attendants_number_for_period(
                    $conflict_start,
                    $slot_end,
                    $service['id'],
                    $provider['id'],
                    $exclude_appointment_id,
                );

                if ($appointment_attendants_number < $service['attendants_number']) {
                    $hours[] = $slot_start->format('H:i');
                }

                $slot_start->add($interval);
                $slot_end->add($interval);
            }
        }

        return $hours;
    }

    /**
     * Remove breaks from available time periods.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $periods Empty periods.
     * @param array $breaks Array of breaks.
     *
     * @return array Returns the available time periods without the breaks.
     *
     * @throws Exception
     */
    public function remove_breaks(string $date, array $periods, array $breaks): array
    {
        if (!$breaks) {
            return $periods;
        }

        foreach ($breaks as $break) {
            $break_start = new DateTime($date . ' ' . $break['start']);

            $break_end = new DateTime($date . ' ' . $break['end']);

            foreach ($periods as &$period) {
                $period_start = $period['start'];

                $period_end = $period['end'];

                if ($break_start <= $period_start && $break_end >= $period_start && $break_end <= $period_end) {
                    // left
                    $period['start'] = $break_end;
                    continue;
                }

                if (
                    $break_start >= $period_start &&
                    $break_start <= $period_end &&
                    $break_end >= $period_start &&
                    $break_end <= $period_end
                ) {
                    // middle
                    $period['end'] = $break_start;
                    $periods[] = [
                        'start' => $break_end,
                        'end' => $period_end,
                    ];
                    continue;
                }

                if ($break_start >= $period_start && $break_start <= $period_end && $break_end >= $period_end) {
                    // right
                    $period['end'] = $break_start;
                    continue;
                }

                if ($break_start <= $period_start && $break_end >= $period_end) {
                    // break contains period
                    $period['start'] = $break_end;
                }
            }
        }

        return $periods;
    }

    /**
     * Remove the unavailability entries from the available time periods of the selected date.
     *
     * @param array $periods Available time periods.
     * @param array $unavailability_events Unavailability events of the current date.
     *
     * @return array Returns the available time periods without the unavailability events.
     *
     * @throws Exception
     */
    public function remove_unavailability_events(array $periods, array $unavailability_events): array
    {
        foreach ($unavailability_events as $unavailability_event) {
            $unavailability_start = new DateTime($unavailability_event['start_datetime']);

            $unavailability_end = new DateTime($unavailability_event['end_datetime']);

            foreach ($periods as &$period) {
                $period_start = $period['start'];

                $period_end = $period['end'];

                if (
                    $unavailability_start <= $period_start &&
                    $unavailability_end >= $period_start &&
                    $unavailability_end <= $period_end
                ) {
                    // Left
                    $period['start'] = $unavailability_end;
                    continue;
                }

                if (
                    $unavailability_start >= $period_start &&
                    $unavailability_start <= $period_end &&
                    $unavailability_end >= $period_start &&
                    $unavailability_end <= $period_end
                ) {
                    // Middle
                    $period['end'] = $unavailability_start;
                    $periods[] = [
                        'start' => $unavailability_end,
                        'end' => $period_end,
                    ];
                    continue;
                }

                if (
                    $unavailability_start >= $period_start &&
                    $unavailability_start <= $period_end &&
                    $unavailability_end >= $period_end
                ) {
                    // Right
                    $period['end'] = $unavailability_start;
                    continue;
                }

                if ($unavailability_start <= $period_start && $unavailability_end >= $period_end) {
                    // Unavailability contains period
                    $period['start'] = $unavailability_end;
                }
            }
        }

        return $periods;
    }

    /**
     * Get an array containing the free time periods (start - end) of a selected date.
     *
     * This method is very important because there are many cases where the system needs to know when a provider is
     * available for an appointment. It will return an array that belongs to the selected date and contains values that
     * have the start and the end time of an available time period.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $provider Provider data.
     * @param int|null $exclude_appointment_id Exclude an appointment from the availability generation.
     *
     * @return array Returns an array with the available time periods of the provider.
     *
     * @throws Exception
     */
    public function get_available_periods(string $date, array $provider, ?int $exclude_appointment_id = null): array
    {
        // Get the service, provider's working plan and provider appointments.
        $working_plan = json_decode($provider['settings']['working_plan'], true);

        // Get the provider's working plan exceptions from the new table.
        $working_plan_exceptions = $this->CI->working_plan_exceptions_model->get_by_provider($provider['id']);

        // Validate and sanitize inputs before building query
        $provider_id = (int) $provider['id'];

        // Validate date format to prevent SQL injection
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new InvalidArgumentException('Invalid date format provided.');
        }

        // Build query using array-based where clause for safety
        $where = [
            'id_users_provider' => $provider_id,
            'DATE(start_datetime) <=' => $date,
            'DATE(end_datetime) >=' => $date,
        ];

        // Sometimes it might be necessary to exclude an appointment from the calculation (e.g. when editing an
        // existing appointment).
        if ($exclude_appointment_id) {
            $where['id !='] = (int) $exclude_appointment_id;
        }

        $buffer_time = $this->get_buffer_time();

        $appointments = array_values($this->CI->appointments_model->get($where));

        if ($buffer_time > 0) {
            // The buffer reserves clean-up time after each real appointment. Unavailabilities and
            // blocked periods are explicit blocks and are not extended.
            foreach ($appointments as &$appointment) {
                $appointment_end = new DateTime($appointment['end_datetime']);

                $appointment_end->modify('+' . $buffer_time . ' minutes');

                $appointment['end_datetime'] = $appointment_end->format('Y-m-d H:i:s');
            }

            unset($appointment);
        }

        $appointments = array_values(
            array_merge(
                $appointments,
                $this->CI->unavailabilities_model->get($where),
                $this->CI->blocked_periods_model->get_for_period($date, $date),
            ),
        );

        // Find the empty spaces on the plan. The first split between the plan is due to a break (if any). After that
        // every reserved appointment is considered to be a taken space in the plan.
        $working_day = strtolower(date('l', strtotime($date)));

        $date_working_plan = $working_plan[$working_day] ?? null;

        // Search if the $date is a custom availability period added outside the normal working plan.
        if (array_key_exists($date, $working_plan_exceptions)) {
            $date_working_plan = $working_plan_exceptions[$date];
        }

        if (!$date_working_plan) {
            return [];
        }

        $periods = [];

        if (isset($date_working_plan['breaks'])) {
            $periods[] = [
                'start' => $date_working_plan['start'],
                'end' => $date_working_plan['end'],
            ];

            $day_start = new DateTime($date_working_plan['start']);
            $day_end = new DateTime($date_working_plan['end']);

            // Split the working plan to available time periods that do not contain the breaks in them.
            foreach ($date_working_plan['breaks'] as $break) {
                $break_start = new DateTime($break['start']);
                $break_end = new DateTime($break['end']);

                if ($break_start < $day_start) {
                    $break_start = $day_start;
                }

                if ($break_end > $day_end) {
                    $break_end = $day_end;
                }

                if ($break_start >= $break_end) {
                    continue;
                }

                foreach ($periods as $key => $period) {
                    $period_start = new DateTime($period['start']);
                    $period_end = new DateTime($period['end']);

                    $remove_current_period = false;

                    if ($break_start > $period_start && $break_start < $period_end && $break_end > $period_start) {
                        $periods[] = [
                            'start' => $period_start->format('H:i'),
                            'end' => $break_start->format('H:i'),
                        ];

                        $remove_current_period = true;
                    }

                    if ($break_start < $period_end && $break_end > $period_start && $break_end < $period_end) {
                        $periods[] = [
                            'start' => $break_end->format('H:i'),
                            'end' => $period_end->format('H:i'),
                        ];

                        $remove_current_period = true;
                    }

                    if ($break_start == $period_start && $break_end == $period_end) {
                        $remove_current_period = true;
                    }

                    if ($remove_current_period) {
                        unset($periods[$key]);
                    }
                }
            }
        }

        // Break the empty periods with the reserved appointments.
        foreach ($appointments as $appointment) {
            foreach ($periods as $index => &$period) {
                $appointment_start = new DateTime($appointment['start_datetime']);
                $appointment_end = new DateTime($appointment['end_datetime']);

                if ($appointment_start >= $appointment_end) {
                    continue;
                }

                $period_start = new DateTime($date . ' ' . $period['start']);
                $period_end = new DateTime($date . ' ' . $period['end']);

                if (
                    $appointment_start <= $period_start &&
                    $appointment_end <= $period_end &&
                    $appointment_end <= $period_start
                ) {
                    // The appointment does not belong in this time period, so we  will not change anything.
                    continue;
                } else {
                    if (
                        $appointment_start <= $period_start &&
                        $appointment_end <= $period_end &&
                        $appointment_end >= $period_start
                    ) {
                        // The appointment starts before the period and finishes somewhere inside. We will need to break
                        // this period and leave the available part.
                        $period['start'] = $appointment_end->format('H:i');
                    } else {
                        if ($appointment_start >= $period_start && $appointment_end < $period_end) {
                            // The appointment is inside the time period, so we will split the period into two new
                            // others.
                            unset($periods[$index]);

                            $periods[] = [
                                'start' => $period_start->format('H:i'),
                                'end' => $appointment_start->format('H:i'),
                            ];

                            $periods[] = [
                                'start' => $appointment_end->format('H:i'),
                                'end' => $period_end->format('H:i'),
                            ];
                        } elseif ($appointment_start == $period_start && $appointment_end == $period_end) {
                            unset($periods[$index]); // The whole period is blocked so remove it from the available periods array.
                        } else {
                            if (
                                $appointment_start >= $period_start &&
                                $appointment_end >= $period_start &&
                                $appointment_start <= $period_end
                            ) {
                                // The appointment starts in the period and finishes out of it. We will need to remove
                                // the time that is taken from the appointment.
                                $period['end'] = $appointment_start->format('H:i');
                            } else {
                                if (
                                    $appointment_start >= $period_start &&
                                    $appointment_end >= $period_end &&
                                    $appointment_start >= $period_end
                                ) {
                                    // The appointment does not belong in the period so do not change anything.
                                    continue;
                                } else {
                                    if (
                                        $appointment_start <= $period_start &&
                                        $appointment_end >= $period_end &&
                                        $appointment_start <= $period_end
                                    ) {
                                        // The appointment is bigger than the period, so this period needs to be removed.
                                        unset($periods[$index]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return array_values($periods);
    }

    /**
     * Calculate the available appointment hours.
     *
     * Calculate the available appointment hours for the given date. The empty spaces are broken down to 15 min and if
     * the service fit in each quarter then a new available hour is added to the "$available_hours" array.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $service Service data.
     * @param array $empty_periods Empty periods array.
     * @param string|null $working_day_end End of the provider's effective working plan of the day (H:i).
     *
     * @return array Returns an array with the available hours for the appointment.
     *
     * @throws Exception
     */
    protected function generate_available_hours(
        string $date,
        array $service,
        array $empty_periods,
        ?string $working_day_end = null,
    ): array {
        return self::generate_hours_for_periods(
            $date,
            $empty_periods,
            (int) $service['duration'],
            !empty($service['slot_interval']) ? (int) $service['slot_interval'] : 15,
            $this->get_end_of_day_buffer(),
            $working_day_end,
        );
    }

    /**
     * Generate the slot start times that fit into the given free periods.
     *
     * A slot fits when it ends no later than its period. The end-of-day buffer additionally applies only to the
     * period that ends at the effective working day end — never before breaks, appointments, unavailabilities or
     * blocked periods (ADR 0002, glossary "Arbeitsende").
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $periods Free periods with "start"/"end" (H:i).
     * @param int $duration Service duration in minutes.
     * @param int $interval Slot interval in minutes.
     * @param int $end_of_day_buffer Buffer in minutes before the working day end (0 = disabled).
     * @param string|null $working_day_end End of the effective working plan of the day (H:i), if known.
     *
     * @return array Returns the slot start times (H:i).
     *
     * @throws Exception
     */
    public static function generate_hours_for_periods(
        string $date,
        array $periods,
        int $duration,
        int $interval,
        int $end_of_day_buffer,
        ?string $working_day_end,
    ): array {
        $available_hours = [];

        foreach ($periods as $period) {
            $current_hour = new DateTime($date . ' ' . $period['start']);

            $end_hour = new DateTime($date . ' ' . $period['end']);

            $buffer = self::end_of_day_buffer_for_period(
                $end_hour->format('H:i'),
                $working_day_end,
                $end_of_day_buffer,
            );

            $last_end = $end_hour->getTimestamp() - $buffer * 60;

            while ($current_hour->getTimestamp() + $duration * 60 <= $last_end) {
                $available_hours[] = $current_hour->format('H:i');

                $current_hour->add(new DateInterval('PT' . $interval . 'M'));
            }
        }

        return $available_hours;
    }

    /**
     * Get the end-of-day buffer that applies to a free period.
     *
     * @param string $period_end End of the free period (H:i).
     * @param string|null $working_day_end End of the effective working plan of the day (H:i).
     * @param int $end_of_day_buffer Configured end-of-day buffer in minutes.
     *
     * @return int Returns the buffer in minutes, or 0 when the period does not end at the working day end.
     */
    public static function end_of_day_buffer_for_period(
        string $period_end,
        ?string $working_day_end,
        int $end_of_day_buffer,
    ): int {
        return $working_day_end !== null && $period_end === $working_day_end ? $end_of_day_buffer : 0;
    }

    /**
     * Get the end of the provider's effective working plan for a date (working plan exception or weekday plan).
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $provider Provider data.
     *
     * @return string|null Returns the end time (H:i), or null when the provider does not work on that date.
     */
    protected function get_working_day_end(string $date, array $provider): ?string
    {
        return self::working_day_end(
            $date,
            json_decode($provider['settings']['working_plan'], true) ?: [],
            $this->CI->working_plan_exceptions_model->get_by_provider($provider['id']),
        );
    }

    /**
     * Get the end of the effective working plan for a date: a working plan exception wins over the weekday plan.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $working_plan Weekday working plan (e.g. ['monday' => ['start' => '09:00', 'end' => '18:00']]).
     * @param array $working_plan_exceptions Working plan exceptions keyed by date (Y-m-d).
     *
     * @return string|null Returns the end time (H:i), or null when there is no working plan for the date.
     */
    public static function working_day_end(string $date, array $working_plan, array $working_plan_exceptions): ?string
    {
        $date_working_plan = $working_plan[strtolower(date('l', strtotime($date)))] ?? null;

        if (array_key_exists($date, $working_plan_exceptions)) {
            $date_working_plan = $working_plan_exceptions[$date];
        }

        return !empty($date_working_plan['end']) ? date('H:i', strtotime($date_working_plan['end'])) : null;
    }

    /**
     * Get the normalized global buffer time in minutes.
     *
     * @return int
     */
    protected function get_buffer_time(): int
    {
        // Read once per instance: the availability calculation asks for every period, day and provider.
        if ($this->buffer_time === null) {
            $buffer_time = setting('appointment_buffer_time', 0);

            $this->buffer_time = is_numeric($buffer_time) ? max(0, (int) $buffer_time) : 0;
        }

        return $this->buffer_time;
    }

    /**
     * Get the buffer time that applies at the end of the day.
     *
     * Returns the global buffer time when the end-of-day buffer is enabled, otherwise zero.
     *
     * @return int
     */
    protected function get_end_of_day_buffer(): int
    {
        $this->end_of_day_buffer_enabled ??= setting('appointment_buffer_end_of_day', '1') === '1';

        return $this->end_of_day_buffer_enabled ? $this->get_buffer_time() : 0;
    }

    /**
     * Consider the book advance timeout and remove available hours that have passed the threshold.
     *
     * If the selected date is today, remove past hours. It is important  include the timeout before booking
     * that is set in the back-office the system. Normally we might want the customer to book an appointment
     * that is at least half or one hour from now. The setting is stored in minutes.
     *
     * @param string $date The selected date.
     * @param array $available_hours Already generated available hours.
     * @param array $provider Provider information.
     *
     * @return array Returns the updated available hours.
     *
     * @throws Exception
     */
    protected function consider_book_advance_timeout(string $date, array $available_hours, array $provider): array
    {
        $provider_timezone = new DateTimeZone($provider['timezone']);

        // Booking deadline and minimum lead time are decided by the booking policy (null = whole day locked).
        $earliest_start = $this->CI->booking_policy->get_earliest_booking_start($date, $provider['timezone']);

        if ($earliest_start === null) {
            return [];
        }

        return self::filter_hours_from($date, $available_hours, $earliest_start, $provider_timezone);
    }

    /**
     * Keep only the hours that start at or after the earliest allowed start (a start exactly at it is allowed).
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $available_hours Hours (H:i).
     * @param DateTimeInterface $earliest_start Earliest allowed start.
     * @param DateTimeZone $timezone Timezone of the hours.
     *
     * @return array Returns the remaining hours, sorted.
     *
     * @throws Exception
     */
    public static function filter_hours_from(
        string $date,
        array $available_hours,
        DateTimeInterface $earliest_start,
        DateTimeZone $timezone,
    ): array {
        $available_hours = array_filter(
            $available_hours,
            fn(string $hour) => (new DateTime($date . ' ' . $hour, $timezone)) >= $earliest_start,
        );

        sort($available_hours, SORT_STRING);

        return array_values($available_hours);
    }

    /**
     * Remove times if succeed the future booking limit.
     *
     * @param string $selected_date
     * @param array $available_hours
     * @param array $provider
     *
     * @return array
     *
     * @throws Exception
     */
    protected function consider_future_booking_limit(
        string $selected_date,
        array $available_hours,
        array $provider,
    ): array {
        $provider_timezone = new DateTimeZone($provider['timezone']);

        $future_booking_limit = setting('future_booking_limit', 90); // in days
        $future_booking_limit = is_numeric($future_booking_limit) ? max(0, (int) $future_booking_limit) : 90;

        $threshold = new DateTime('now', $provider_timezone);

        $threshold->modify('+' . $future_booking_limit . ' days');

        $selected_date_time = new DateTime($selected_date);

        if ($threshold < $selected_date_time) {
            return [];
        }

        return $threshold > $selected_date_time ? $available_hours : [];
    }
}
