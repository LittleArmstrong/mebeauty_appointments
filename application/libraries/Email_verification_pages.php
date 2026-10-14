<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email verification pages library (fork, spec 0018, ADR 0008).
 *
 * Builds and shows the public pages of a verification link: the page with the confirm button (opening the link
 * changes nothing, so mail scanners that open links do not confirm) and the outcome messages ("Link ungültig",
 * "Leider abgelaufen", "nicht gebucht").
 *
 * @package Libraries
 */
class Email_verification_pages
{
    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    /**
     * Email_verification_pages constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->library('email_verification_rules');
    }

    /**
     * Get the page with the confirm button of a verification link.
     *
     * @param string $token Token from the link.
     * @param string $purpose Purpose of the verification (booking or email change).
     *
     * @return array Returns the page (view and its variables) for show().
     */
    public function confirm(string $token, string $purpose): array
    {
        $prefix =
            $purpose === Email_verification_rules::PURPOSE_EMAIL_CHANGE
                ? 'email_verification_change_'
                : 'email_verification_';

        return [
            'view' => 'pages/booking_verification_confirm',
            'message_title' => lang($prefix . 'subject'),
            'message_text' => lang($prefix . 'confirm_text'),
            'message_icon' => base_url('assets/img/success.png'),
            'confirm_button' => lang($prefix . 'button'),
            'confirm_url' => site_url('booking/confirm_verification'),
            'verification_token' => $token
        ];
    }

    /**
     * Get the message page for an unknown, used or void verification link (neutral wording).
     *
     * @return array Returns the page for show().
     */
    public function invalid(): array
    {
        return $this->message('email_verification_invalid_title', 'email_verification_invalid_text');
    }

    /**
     * Get the message page for an expired Vormerkung whose slot is taken ("Leider abgelaufen").
     *
     * @return array Returns the page for show().
     */
    public function expired(): array
    {
        return $this->message('email_verification_expired_title', 'email_verification_expired_text');
    }

    /**
     * Get the message page for a Vormerkung that cannot be booked for the customer of the email (neutral wording).
     *
     * @return array Returns the page for show().
     */
    public function not_booked(): array
    {
        return $this->message('email_verification_not_booked_title', 'email_verification_not_booked_text');
    }

    /**
     * Show a page of the verification link with the variables of the booking message pages.
     *
     * @param array $page Page from confirm(), invalid(), expired() or not_booked().
     */
    public function show(array $page): void
    {
        $view = $page['view'];

        unset($page['view']);

        html_vars([
            'show_message' => true,
            'page_title' => lang('page_title') . ' ' . e(setting('company_name')),
            'google_analytics_code' => setting('google_analytics_code'),
            'matomo_analytics_url' => setting('matomo_analytics_url'),
            'matomo_analytics_site_id' => setting('matomo_analytics_site_id'),
            'display_login_button' => setting('display_login_button'),
            'legal_notice_url' => setting('legal_notice_url'),
            'imprint_url' => setting('imprint_url'),
            ...$page
        ]);

        $this->CI->load->view($view);
    }

    /**
     * Get a message page with a link to the booking page.
     *
     * @param string $title_key Translation key of the title.
     * @param string $text_key Translation key of the text.
     *
     * @return array Returns the page for show().
     */
    protected function message(string $title_key, string $text_key): array
    {
        return [
            'view' => 'pages/booking_message',
            'message_title' => lang($title_key),
            'message_text' =>
                '<p>' .
                e(lang($text_key)) .
                '</p><p><a href="' .
                e(site_url()) .
                '">' .
                e(lang('go_to_booking_page')) .
                '</a></p>',
            'message_icon' => base_url('assets/img/error.png')
        ];
    }
}
