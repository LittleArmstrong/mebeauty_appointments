/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

/**
 * Booking layout.
 *
 * This module implements the booking layout functionality.
 */
window.App.Layouts.Booking = (function () {
    const $selectLanguage = $('#select-language');

    /**
     * Initialize the module.
     */
    function initialize() {
        App.Utils.Lang.enableLanguageSelection($selectLanguage);
        initializeMobileMenu();
    }

    /**
     * Toggle the mebeauty navbar mobile menu (burger menu).
     */
    function initializeMobileMenu() {
        const $menuBtn = $('#navbar-mobile-menu-btn');
        const $menu = $('#navbar-mobile-menu');

        if (!$menuBtn.length || !$menu.length) {
            return;
        }

        $menuBtn.on('click', () => {
            const open = !$menu.hasClass('open');

            $menu.toggleClass('open', open);
            $menuBtn.toggleClass('open', open);
            $menuBtn.attr('aria-expanded', open ? 'true' : 'false');
        });
    }

    document.addEventListener('DOMContentLoaded', initialize);

    return {};
})();
