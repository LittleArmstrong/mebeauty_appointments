<?php
/**
 * Local variables.
 *
 * None.
 */
$site_url = 'https://mebeauty-koeln.de';
?>
<header class="site-navbar">
    <nav>
        <div class="site-navbar-inner">
            <a href="<?= $site_url ?>/" class="site-navbar-logo">
                <img src="<?= asset_url('assets/img/mebeauty/logo.png') ?>" alt="MeBeauty logo">
            </a>
            <div class="site-navbar-toggle">
                <button id="navbar-mobile-menu-btn" type="button" aria-controls="navbar-mobile-menu" aria-expanded="false">
                    <span class="visually-hidden">Menü öffnen</span>
                    <svg class="svg-burger" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="svg-close" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div id="navbar-mobile-menu" class="site-navbar-menu">
                <ul>
                    <li><a href="<?= $site_url ?>/">Home</a></li>
                    <li><a href="<?= $site_url ?>/Behandlungen">Behandlungen</a></li>
                    <li><a href="<?= $site_url ?>/Preise">Preise</a></li>
                    <li><a href="<?= $site_url ?>/Kontakt">Kontakt</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
