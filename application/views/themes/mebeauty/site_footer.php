<?php
/**
 * Local variables.
 *
 * None.
 */
$site_url = 'https://mebeauty-koeln.de';
$whatsapp_url = 'https://wa.me/4917619256689';
$legal_notice_url = vars('legal_notice_url') ?: $site_url . '/Datenschutz';
$imprint_url = vars('imprint_url') ?: $site_url . '/Impressum';
?>
<footer class="site-footer">
    <div class="site-footer-grid">
        <div>
            <h2><a href="<?= e($site_url) ?>/">MeBeauty</a></h2>
            <div class="site-footer-social">
                <a href="https://www.instagram.com/mebeauty_koeln/" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/instagram.png') ?>" alt="Instagram">
                </a>
                <a href="https://www.tiktok.com/@mebeauty_koeln" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/tiktok.png') ?>" alt="TikTok">
                </a>
                <a href="<?= e($whatsapp_url) ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/whatsapp.png') ?>" alt="WhatsApp">
                </a>
            </div>
        </div>
        <div>
            <h3><?= lang('site_treatments') ?></h3>
            <ul class="site-footer-treatments">
                <li><a href="<?= e($site_url) ?>/Behandlungen#01_Gesichtsbehandlungen">Gesichtsbehandlungen</a></li>
                <li><a href="<?= e($site_url) ?>/Behandlungen#02_Haarentfernung">Haarentfernung</a></li>
                <li><a href="<?= e($site_url) ?>/Behandlungen#03_Plasma_Pen">Plasma Pen</a></li>
                <li><a href="<?= e($site_url) ?>/Behandlungen#04_Massagen_mit_Olga">Aroma Massagen (mit Olga)</a></li>
                <li><a href="<?= e($site_url) ?>/Behandlungen#05_Koerperbehandlungen">Körperbehandlungen</a></li>
                <li><a href="<?= e($site_url) ?>/Behandlungen#06_Zahnbleaching">Zahnbleaching</a></li>
                <li><a href="<?= e($site_url) ?>/Behandlungen#07_Manikuere_Pedikuere">Maniküre &amp; Pediküre</a></li>
                <li><a href="<?= e($site_url) ?>/Behandlungen#08_Keratinglaettung">Keratinglättung</a></li>
            </ul>
        </div>
        <div>
            <h3><?= lang('site_phone') ?></h3>
            <p><a href="tel:+4917619256689">+49 176 192 566 89</a></p>
            <p><a href="tel:+491786827117">+49 178 682 71 17 (Olga)</a></p>
            <h3><?= lang('site_email') ?></h3>
            <p><a href="mailto:info@mebeauty-koeln.de">info@mebeauty-koeln.de</a></p>
            <p><a href="mailto:olga@rebirth-of-shakti.de">olga@rebirth-of-shakti.de</a></p>
            <h3><?= lang('site_address') ?></h3>
            <a href="https://maps.apple.com/?q=Mebeauty+K%C3%B6ln+51109" target="_blank">
                <p>Fußfallstr. 25a</p>
                <p>51109 Köln</p>
            </a>
        </div>
        <div>
            <h3><?= lang('site_phone_hours') ?></h3>
            <p>10:00 - 19:00 Uhr</p>
            <p class="site-footer-note"><em><?= lang('site_by_appointment') ?></em></p>
        </div>
    </div>
    <ul class="site-footer-legal">
        <li>MeBeauty Köln</li>
        <li><a href="<?= e($imprint_url) ?>"><?= lang('site_imprint') ?></a></li>
        <li><a href="<?= e($legal_notice_url) ?>"><?= lang('site_privacy') ?></a></li>
    </ul>
</footer>
