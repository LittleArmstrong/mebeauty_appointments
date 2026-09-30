<?php
/**
 * Local variables.
 *
 * @var string $legal_notice_url
 * @var string $imprint_url
 */
$legal_notice_url = vars('legal_notice_url') ?: 'https://mebeauty-koeln.de/Impressum';
$imprint_url = vars('imprint_url') ?: 'https://mebeauty-koeln.de/Datenschutz';
?>
<footer class="site-footer">
    <div class="site-footer-grid">
        <div>
            <h2><a href="https://mebeauty-koeln.de/">MeBeauty</a></h2>
            <div class="site-footer-social">
                <a href="https://www.instagram.com/mebeauty_koeln/" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/instagram.png') ?>" alt="Instagram">
                </a>
                <a href="https://www.tiktok.com/@mebeauty_koeln" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/tiktok.png') ?>" alt="TikTok">
                </a>
                <a href="https://wa.me/4917619256689" target="_blank" rel="noopener noreferrer">
                    <img src="<?= asset_url('assets/img/mebeauty/whatsapp.png') ?>" alt="WhatsApp">
                </a>
            </div>
        </div>
        <div>
            <h3>Behandlungen</h3>
            <ul class="site-footer-treatments">
                <li><a href="https://mebeauty-koeln.de/Behandlungen#01_Gesichtsbehandlungen">Gesichtsbehandlungen</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#02_Haarentfernung">Haarentfernung</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#03_Plasma_Pen">Plasma Pen</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#04_Massagen_mit_Olga">Aroma Massagen (mit Olga)</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#05_Koerperbehandlungen">Körperbehandlungen</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#06_Zahnbleaching">Zahnbleaching</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#07_Manikuere_Pedikuere">Maniküre &amp; Pediküre</a></li>
                <li><a href="https://mebeauty-koeln.de/Behandlungen#08_Keratinglaettung">Keratinglättung</a></li>
            </ul>
        </div>
        <div>
            <h3>Telefon</h3>
            <p><a href="tel:+4917619256689">+49 176 192 566 89</a></p>
            <p><a href="tel:+491786827117">+49 178 682 71 17 (Olga)</a></p>
            <h3 class="mt-4">E-Mail</h3>
            <p><a href="mailto:info@mebeauty-koeln.de">info@mebeauty-koeln.de</a></p>
            <p><a href="mailto:olga@rebirth-of-shakti.de">olga@rebirth-of-shakti.de</a></p>
            <h3 class="mt-4">Adresse</h3>
            <a href="https://maps.apple.com/?q=Mebeauty+K%C3%B6ln+51109" target="_blank">
                <p>Fußfallstr. 25a</p>
                <p>51109 Köln</p>
            </a>
        </div>
        <div>
            <h3>Telefonsprechzeiten</h3>
            <p>10:00 - 19:00 Uhr</p>
            <em><p class="site-footer-note">Termin nach Vereinbarung!</p></em>
        </div>
    </div>
    <ul class="site-footer-legal">
        <li>MeBeauty Köln</li>
        <li><a href="<?= e($legal_notice_url) ?>">Impressum</a></li>
        <li><a href="<?= e($imprint_url) ?>">Datenschutz</a></li>
    </ul>
</footer>
