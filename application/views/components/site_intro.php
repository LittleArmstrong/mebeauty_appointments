<?php
/**
 * Local variables.
 *
 * @var string $title
 */
?>
<section class="site-intro">
    <picture>
        <source srcset="<?= asset_url('assets/img/mebeauty/hero.webp') ?>" type="image/webp">
        <img src="<?= asset_url('assets/img/mebeauty/hero.jpg') ?>" alt="<?= e($title) ?>" class="site-intro-image"
             width="3400" height="2240" fetchpriority="high">
    </picture>
    <div class="site-intro-overlay"></div>
    <div class="site-intro-content">
        <h1 class="site-intro-title"><?= e($title) ?></h1>
        <div class="site-intro-underline"></div>
    </div>
</section>
