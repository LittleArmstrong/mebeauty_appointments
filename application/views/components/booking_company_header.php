<?php
/**
 * Local variables.
 *
 * @var string $company_name
 * @var string $company_logo
 */

$company_name = $company_name ?: setting('company_name', DEFAULT_COMPANY_NAME);
$company_logo = $company_logo ?: '';
?>
<div id="company-header" class="overflow-hidden p-3 p-md-4 d-flex align-items-center bg-primary">
    <img src="<?= $company_logo ?: base_url('assets/img/logo.png') ?>" alt="logo"
         class="me-3" style="max-height: 56px;">
    <span class="text-white fs-4 fw-light" style="line-height: 1.4;"><?= e($company_name) ?></span>
</div>
