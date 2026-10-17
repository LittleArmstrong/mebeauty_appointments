<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Booking refusal library.
 *
 * Answers an expected refusal of the booking form (spec 0023): the customer already has an appointment at that time,
 * or the requested hour is no longer available. The answer is marked, so the booking page shows it in a calm dialog
 * with its own title instead of the dialog for unexpected errors (assets/js/utils/booking_refusal.js).
 *
 * The title comes from the server, so languages without a fork translation get the English fallback of lang().
 *
 * @package Libraries
 */
class Booking_refusal
{
    /**
     * HTTP status of an expected refusal: the request conflicts with the current state of the appointments.
     */
    public const HTTP_STATUS = 409;

    /**
     * Send the marked refusal as JSON response.
     *
     * @param string $message Text shown to the customer.
     */
    public function respond(string $message): void
    {
        json_response(
            [
                'success' => false,
                'booking_refused' => true,
                'title' => lang('appointment_not_possible'),
                'message' => $message
            ],
            self::HTTP_STATUS
        );
    }
}
