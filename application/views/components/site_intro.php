<?php
/**
 * Local variables.
 *
 * @var string $title
 */
?>
<section class="site-intro">
    <img src="<?= asset_url('assets/img/mebeauty/hero.jpg') ?>" alt="<?= e($title) ?>" class="site-intro-image">
    <div class="site-intro-overlay"></div>
    <div class="site-intro-content">
        <h1 class="site-intro-title"><?= e($title) ?></h1>
        <div class="site-intro-underline"></div>
    </div>
</section>
