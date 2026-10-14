<?php
/**
 * Confirm page of an email verification link (fork, spec 0018).
 *
 * Opening the link only shows this page; the button posts the token (with CSRF token) to confirm it, so mail
 * scanners that open links do not confirm a Vormerkung.
 */
?>
<?php extend('layouts/message_layout'); ?>

<?php section('content'); ?>

<div>
    <img id="message-icon" class="mt-0 mb-5" src="<?= e(vars('message_icon')) ?>" alt="">
</div>

<div class="mb-5">
    <h4 class="mb-4"><?= e(vars('message_title')) ?></h4>

    <p class="mb-4"><?= e(vars('message_text')) ?></p>

    <form method="post" action="<?= e(vars('confirm_url')) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(vars('csrf_token')) ?>">
        <input type="hidden" name="token" value="<?= e(vars('verification_token')) ?>">

        <button type="submit" id="confirm-verification" class="btn btn-primary">
            <?= e(vars('confirm_button')) ?>
        </button>
    </form>
</div>

<?php end_section('content'); ?>

<?php section('scripts'); ?>

<?php component('google_analytics_script', ['google_analytics_code' => vars('google_analytics_code')]); ?>
<?php component('matomo_analytics_script', [
    'matomo_analytics_url' => vars('matomo_analytics_url'),
    'matomo_analytics_site_id' => vars('matomo_analytics_site_id')
]); ?>

<?php end_section('scripts'); ?>
