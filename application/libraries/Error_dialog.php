<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Error dialog library (fork, spec 0023).
 *
 * Provides the title of the dialog for unexpected errors (`onAjaxError` in `assets/js/app.js`): the company name
 * instead of "Easy!Appointments". The title is HTML-escaped here, because the dialog renders it as HTML and not every
 * page that loads app.js also loads the string utility (the installation page does not).
 *
 * @package Libraries
 */
class Error_dialog
{
    /**
     * HTML-escaped dialog title for the current request.
     *
     * Before the installation there are no settings yet, so the default company name is used.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title_for(is_app_installed() ? setting('company_name') : null);
    }

    /**
     * HTML-escaped dialog title for a company name.
     *
     * @param string|null $company_name Value of the setting "company_name", null when there are no settings.
     *
     * @return string
     */
    public function title_for(?string $company_name): string
    {
        return e($company_name ?: DEFAULT_COMPANY_NAME);
    }
}
