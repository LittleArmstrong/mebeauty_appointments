<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Booking footer rules library (fork, spec 0019).
 *
 * Pure function without database access that decides whether the footer of the booking wizard card
 * (`application/views/components/booking_footer.php`) is rendered. The booking layout collects the footer variables
 * and the setting, calls this rule and only includes the component when it returns true.
 *
 * Rules:
 * - Setting `display_booking_footer` other than '1' hides the footer always.
 * - Otherwise the footer is shown when at least one of its elements is shown: legal notice link, imprint link,
 *   language selector or login button. An empty footer (including its border) is not rendered.
 *
 * @package Libraries
 */
class Booking_footer_rules
{
    /**
     * Footer variables that render an element in the booking footer component.
     */
    public const ELEMENTS = ['legal_notice_url', 'imprint_url', 'display_language_selector', 'display_login_button'];

    /**
     * Whether the booking footer is rendered.
     *
     * @param string $display_booking_footer Value of the setting "display_booking_footer" ('0' or '1').
     * @param array $footer_vars Variables passed to the booking footer component.
     *
     * @return bool Returns true when the footer component is to be included.
     */
    public function is_shown(string $display_booking_footer, array $footer_vars): bool
    {
        if ($display_booking_footer !== '1') {
            return false;
        }

        foreach (self::ELEMENTS as $element) {
            if (!empty($footer_vars[$element])) {
                return true;
            }
        }

        return false;
    }
}
