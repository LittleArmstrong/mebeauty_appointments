<?php
/**
 * Notice after submitting a booking that needs email verification (fork, spec 0018): the appointment is reserved as
 * Vormerkung until the link in the mail is clicked. Hidden until the booking HTTP client shows it.
 */
?>

<div id="wizard-frame-verification" class="p-3 p-md-4" style="display: none">
    <div class="frame-container py-3 text-center" style="min-height: 500px">
        <div
            class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mx-auto my-4"
            style="width: 100px; height: 100px"
        >
            <i class="fas fa-envelope-open-text fa-3x text-primary"></i>
        </div>

        <h2 class="frame-title fw-light mb-4"><?= lang('email_verification_pending_title') ?></h2>

        <p class="fs-5 text-muted mx-auto mb-3" style="max-width: 630px">
            <?= lang('email_verification_pending_text') ?>
        </p>

        <p class="text-muted small mb-4">
            <?= lang('check_spam_folder') ?>
        </p>
    </div>
</div>
