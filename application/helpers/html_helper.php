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

if (!function_exists('e')) {
    /**
     * HTML escape function for templates.
     *
     * Use this helper function to easily escape all the outputted HTML markup.
     *
     * Example:
     *
     * <?= e($string) ?>
     *
     * @param mixed $string Provide anything that can be converted to a string.
     */
    function e(mixed $string): string
    {
        return htmlspecialchars((string) $string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('component')) {
    /**
     * Render a component from the "views/components/*.php" directory.
     *
     * Use this helper function to easily include components into your HTML markup.
     *
     * Any loaded template variables will also be available at the component template, but you may also specify
     * additional values by adding values to the $params parameter.
     *
     * Example:
     *
     * echo component('timezones_dropdown', ['attributes' => 'class"form-control"'], TRUE);
     *
     * @param string $component Component template file name.
     * @param array $vars Additional parameters for the component.
     * @param bool $return Whether to return the HTML or echo it directly.
     *
     * @return string|object Return the HTML if the $return argument is TRUE or NULL.
     */
    function component(string $component, array $vars = [], bool $return = false): string|object
    {
        /** @var EA_Controller $CI */
        $CI = get_instance();

        return $CI->load->view('components/' . $component, $vars, $return);
    }
}

if (!function_exists('extend')) {
    /**
     * Use this function at the top of view files to mark the layout you are extending from.
     *
     * @param $layout
     */
    function extend($layout): void
    {
        config([
            'layout' => [
                'filename' => $layout,
                'sections' => [],
                'tmp' => [],
            ],
        ]);
    }
}

if (!function_exists('section')) {
    /**
     * Use this function in view files to mark the beginning and/or end of a layout section.
     *
     * Sections will only be used if the view file extends a layout and will be ignored otherwise.
     *
     * Example:
     *
     * <?php section('content') ?>
     *
     *   <!-- Section Starts -->
     *
     *   <p>This is the content of the section.</p>
     *
     *   <!-- Section Ends -->
     *
     * <?php section('content') ?>
     *
     * @param string $name
     */
    function section(string $name): void
    {
        $layout = config('layout');

        if (array_key_exists($name, $layout['tmp'])) {
            $layout['sections'][$name][] = ob_get_clean();

            unset($layout['tmp'][$name]);

            config(['layout' => $layout]);

            return;
        }

        if (empty($layout['sections'][$name])) {
            $layout['sections'][$name] = [];
        }

        $layout['tmp'][$name] = '';

        config(['layout' => $layout]);

        ob_start();
    }
}

if (!function_exists('end_section')) {
    /**
     * Use this function in view files to mark the end of a layout section.
     *
     * Sections will only be used if the view file extends a layout and will be ignored otherwise.
     *
     * Example:
     *
     * <?php section('content') ?>
     *
     *   <!-- Section Starts -->
     *
     *   <p>This is the content of the section.</p>
     *
     *   <!-- Section Ends -->
     *
     * <?php end_section('content') ?>
     *
     * @param string $name
     */
    function end_section(string $name): void
    {
        $layout = config('layout');

        if (array_key_exists($name, $layout['tmp'])) {
            $layout['sections'][$name][] = ob_get_clean();

            unset($layout['tmp'][$name]);

            config(['layout' => $layout]);
        }
    }
}

if (!function_exists('slot')) {
    /**
     * Use this function in view files to mark a slot that sections can populate from within child templates.
     *
     * @param string $name
     */
    function slot(string $name): void
    {
        $layout = config('layout');

        $section = $layout['sections'][$name] ?? null;

        if (!$section) {
            return;
        }

        foreach ($section as $content) {
            echo $content;
        }
    }
}

if (!function_exists('pure_html')) {
    /**
     * Use this function in order to render HTML that comes from a text editor or similar, but strip the JS from it.
     *
     * @param string $markup
     *
     * @return string
     */
    function pure_html(string $markup): string
    {
        $config = HTMLPurifier_Config::createDefault();

        // The directory check runs once per path and request (the helper may be called for every rich text field).
        static $usable_cache_paths = [];

        $cache_path = rtrim(config('cache_path'), '/') . '/htmlpurifier';

        $usable_cache_paths[$cache_path] ??=
            (is_dir($cache_path) || @mkdir($cache_path, 0775, true)) && is_writable($cache_path);

        if ($usable_cache_paths[$cache_path]) {
            $config->set('Cache.SerializerPath', $cache_path);
        } else {
            $config->set('Cache.DefinitionImpl', null);
        }

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($markup);
    }
}

if (!function_exists('theme_name')) {
    /**
     * Sanitize a theme name so it can be used as a directory name (no path traversal).
     *
     * @param mixed $theme Theme name.
     *
     * @return string
     */
    function theme_name(mixed $theme): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', is_scalar($theme) ? (string) $theme : '');
    }
}

if (!function_exists('theme_view')) {
    /**
     * Get the view path of a component provided by the active theme.
     *
     * Themes may ship their own site chrome (navbar, intro, footer, …) in application/views/themes/<theme>/
     * (ADR 0006). The active theme is the sanitized "theme" view variable.
     *
     * @param string $component Component name (e.g. "site_navbar").
     *
     * @return string|null Returns the view path, or null when the active theme does not provide the component.
     */
    function theme_view(string $component): ?string
    {
        $theme = theme_name(vars('theme'));

        if ($theme === '' || !preg_match('/^[a-z0-9_]+$/', $component)) {
            return null;
        }

        $view = 'themes/' . $theme . '/' . $component;

        return is_file(VIEWPATH . $view . '.php') ? $view : null;
    }
}

if (!function_exists('theme_component')) {
    /**
     * Render a component of the active theme when the theme provides it.
     *
     * @param string $component Component name (e.g. "site_footer").
     * @param array $vars Additional variables passed to the component.
     *
     * @return bool Returns whether the component was rendered.
     */
    function theme_component(string $component, array $vars = []): bool
    {
        $view = theme_view($component);

        if ($view === null) {
            return false;
        }

        /** @var EA_Controller $CI */
        $CI = get_instance();

        $CI->load->view($view, $vars);

        return true;
    }
}

if (!function_exists('theme_config')) {
    /**
     * Get the server-side defaults declared by a theme (application/views/themes/<theme>/theme.php).
     *
     * @param string|null $theme Theme name (null: the "theme" setting).
     *
     * @return array Returns the declared values, or an empty array when the theme declares none.
     */
    function theme_config(?string $theme = null): array
    {
        $theme = theme_name($theme ?? setting('theme', 'default'));

        $path = VIEWPATH . 'themes/' . $theme . '/theme.php';

        if ($theme === '' || !is_file($path)) {
            return [];
        }

        $config = include $path;

        return is_array($config) ? $config : [];
    }
}

if (!function_exists('brand_colors')) {
    /**
     * Resolve the main color for server-rendered output (browser theme color, emails).
     *
     * Order: a valid company color → the theme's default color → the given upstream fallback (ADR 0006).
     *
     * @param string|null $company_color Company color setting (white means "no company color").
     * @param string|null $theme Theme name (null: the "theme" setting).
     * @param array $fallback Upstream colors: ['primary' => '#…', 'hover' => '#…'].
     *
     * @return array Returns ['primary' => '#…', 'hover' => '#…'].
     */
    function brand_colors(?string $company_color, ?string $theme, array $fallback): array
    {
        $company_color = (string) $company_color;

        if (
            $company_color !== '' &&
            strtolower($company_color) !== DEFAULT_COMPANY_COLOR &&
            validate_setting_value('company_color', $company_color) === null
        ) {
            return ['primary' => $company_color, 'hover' => $company_color];
        }

        $config = theme_config($theme);
        $theme_color = (string) ($config['primary_color'] ?? '');

        if ($theme_color !== '' && validate_setting_value('company_color', $theme_color) === null) {
            $hover = (string) ($config['primary_color_hover'] ?? '');

            return [
                'primary' => $theme_color,
                'hover' => validate_setting_value('company_color', $hover) === null ? $hover : $theme_color,
            ];
        }

        return $fallback;
    }
}
