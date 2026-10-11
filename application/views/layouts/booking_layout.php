<!doctype html>
<html lang="<?= config('language_code') ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="theme-color" content="#d1ae5e">
    <meta name="google" content="notranslate">

    <meta property="og:title" content="<?= lang('site_booking') ?> | <?= e(setting('company_name', DEFAULT_COMPANY_NAME)) ?>"/>
    <meta property="og:description" content="<?= lang('site_book_online') ?> – <?= e(setting('company_name', DEFAULT_COMPANY_NAME)) ?>"/>
    <meta property="og:url" content="<?= base_url() ?>">
    <meta property="og:image" content="<?= base_url('assets/img/social-card.png') ?>"/>
    <meta property="og:type" content="website">

    <?php slot('meta'); ?>

    <title><?= lang('site_booking') ?> | <?= e(setting('company_name', DEFAULT_COMPANY_NAME)) ?></title>

    <link rel="icon" type="image/x-icon" href="<?= asset_url('assets/img/favicon.ico') ?>">

    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/vendor/cookieconsent/cookieconsent.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/vendor/flatpickr/flatpickr.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/vendor/flatpickr/material_green.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/themes/' . vars('theme') . '.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/general.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/frontend.css') ?>">

    <?php component('company_color_style', ['company_color' => vars('company_color')]); ?>

    <?php slot('styles'); ?>
</head>

<?php
// Site chrome (navbar, intro, footer, …) belongs to the theme (ADR 0006). Without it the booking page keeps the
// upstream layout: wizard centered on the page.
$has_site_chrome = theme_view('site_navbar') !== null || theme_view('site_intro') !== null;
?>

<body<?= $has_site_chrome ? ' class="site-page"' : '' ?>>
<?php theme_component('site_navbar'); ?>
<?php theme_component('site_intro', ['title' => lang('site_booking')]); ?>

<div id="main" class="<?= $has_site_chrome ? 'container my-5' : 'container min-vh-100' ?>">
    <div class="<?= $has_site_chrome
        ? 'row wrapper justify-content-center'
        : 'row wrapper min-vh-100 justify-content-center align-items-center py-0 py-md-3' ?>">
        <div id="book-appointment-wizard"
             class="col-12 col-lg-10 col-xl-8 col-xxl-7 bg-body overflow-hidden p-0<?= $has_site_chrome ? '' : ' my-auto' ?>">

            <?php if (setting('booking_show_company_header', '1') === '1'): ?>
                <?php component('booking_company_header', [
                    'company_name' => vars('company_name'),
                    'company_logo' => vars('company_logo'),
                ]); ?>
            <?php endif; ?>

            <?php component('booking_steps'); ?>

            <?php slot('content'); ?>

            <?php component('booking_footer', [
                'display_login_button' => vars('display_login_button'),
                'legal_notice_url' => vars('legal_notice_url'),
                'imprint_url' => vars('imprint_url'),
            ]); ?>

        </div>
    </div>
</div>

<?php theme_component('site_footer'); ?>
<?php theme_component('site_whatsapp'); ?>

<?php if (vars('display_cookie_notice') === '1'): ?>
    <?php component('cookie_notice_modal', ['cookie_notice_content' => vars('cookie_notice_content')]); ?>
<?php endif; ?>

<?php if (vars('display_terms_and_conditions') === '1'): ?>
    <?php component('terms_and_conditions_modal', [
        'terms_and_conditions_content' => vars('terms_and_conditions_content'),
    ]); ?>
<?php endif; ?>

<?php if (vars('display_privacy_policy') === '1'): ?>
    <?php component('privacy_policy_modal', ['privacy_policy_content' => vars('privacy_policy_content')]); ?>
<?php endif; ?>

<script src="<?= asset_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/cookieconsent/cookieconsent.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/@popperjs-core/popper.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/bootstrap/bootstrap.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/moment/moment.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/moment-timezone/moment-timezone-with-data.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/@fortawesome-fontawesome-free/fontawesome.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/@fortawesome-fontawesome-free/solid.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/tippy.js/tippy-bundle.umd.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/flatpickr/flatpickr.min.js') ?>"></script>

<script src="<?= asset_url('assets/js/app.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/date.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/file.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/http.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/lang.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/message.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/string.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/url.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/validation.js') ?>"></script>
<?php if (setting('altcha_enabled') === '1'): ?>
<script src="<?= asset_url('assets/js/utils/altcha.js') ?>"></script>
<?php endif; ?>
<script src="<?= asset_url('assets/js/layouts/booking_layout.js') ?>"></script>
<script src="<?= asset_url('assets/js/http/localization_http_client.js') ?>"></script>

<?php component('js_vars_script'); ?>
<?php component('js_lang_script'); ?>

<?php component('google_analytics_script', ['google_analytics_code' => vars('google_analytics_code')]); ?>
<?php component('matomo_analytics_script', [
    'matomo_analytics_url' => vars('matomo_analytics_url'),
    'matomo_analytics_site_id' => vars('matomo_analytics_site_id'),
]); ?>

<?php slot('scripts'); ?>

</body>
</html>
