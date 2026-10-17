<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.3.0
 * ---------------------------------------------------------------------------- */

/**
 * Assets URL helper function.
 *
 * This function will create an asset file URL that includes a cache busting parameter in order
 * to invalidate the browser cache in case of an update.
 *
 * @param string $uri Relative URI (just like the one used in the base_url helper).
 * @param string|null $protocol Valid URI protocol.
 *
 * @return string Returns the final asset URL.
 */
function asset_url(string $uri = '', ?string $protocol = null): string
{
    $debug = config('debug');

    // Fork (spec 0022): served file and its cache-busting token come from Asset_cache_busting.
    $CI = &get_instance();
    $CI->load->library('asset_cache_busting');

    $uri = $CI->asset_cache_busting->served_uri($uri, (bool) $debug);

    $cache_busting_token = '?' . $CI->asset_cache_busting->token(FCPATH, $uri, config('cache_busting_token'));

    return base_url($uri . $cache_busting_token, $protocol);
}
