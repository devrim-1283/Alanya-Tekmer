<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo Security::escape($metaDescription ?? getSetting('site_description')); ?>">
    <meta name="keywords" content="TEKMER, Alanya, girişimcilik, teknoloji, inovasyon, KOSGEB">
    <meta name="author" content="Alanya TEKMER">
    <title><?php echo Security::escape($pageTitle ?? 'Alanya TEKMER'); ?></title>
    
    <link rel="icon" type="image/x-icon" href="<?php echo url('favicon.ico'); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo Security::escape($pageTitle ?? 'Alanya TEKMER'); ?>">
    <meta property="og:description" content="<?php echo Security::escape($metaDescription ?? getSetting('site_description')); ?>">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Critical CSS - Inline for instant load */
        body { margin: 0; font-family: 'Inter', sans-serif; }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        * { box-sizing: border-box; }
    </style>
    
    <?php if (isset($additionalCss)): ?>
        <?php foreach ((array)$additionalCss as $css): ?>
            <link rel="stylesheet" href="<?php echo asset('css/' . $css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Cookie Consent Banner -->
    <div id="cookieConsent" class="cookie-consent" style="display: none;">
        <div class="cookie-content">
            <p>Web sitemizde size en iyi deneyimi sunabilmek için çerezleri kullanıyoruz. Detaylı bilgi için <a href="<?php echo url('gizlilik-sozlesmesi'); ?>">Gizlilik Sözleşmesi</a>ni inceleyebilirsiniz.</p>
            <button onclick="acceptCookies()" class="btn btn-primary">Kabul Et</button>
        </div>
    </div>

    <!-- Top Header (Hidden on mobile) -->
    <div class="top-header">
        <div class="container">
            <div class="top-header-content">
                <div class="contact-info">
                    <a href="tel:<?php echo getSetting('contact_phone'); ?>" class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <span><?php echo getSetting('contact_phone'); ?></span>
                    </a>
                    <a href="mailto:<?php echo getSetting('contact_email'); ?>" class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span><?php echo getSetting('contact_email'); ?></span>
                    </a>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo Security::escape(getSetting('contact_address')); ?></span>
                    </div>
                </div>
                <div class="social-links">
                    <?php if (getSetting('facebook_url')): ?>
                        <a href="<?php echo Security::escape(getSetting('facebook_url')); ?>" target="_blank" rel="noopener" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSetting('instagram_url')): ?>
                        <a href="<?php echo Security::escape(getSetting('instagram_url')); ?>" target="_blank" rel="noopener" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSetting('linkedin_url')): ?>
                        <a href="<?php echo Security::escape(getSetting('linkedin_url')); ?>" target="_blank" rel="noopener" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSetting('youtube_url')): ?>
                        <a href="<?php echo Security::escape(getSetting('youtube_url')); ?>" target="_blank" rel="noopener" class="social-link" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header with Scroll Effect -->
    <header class="main-header" id="mainHeader">
        <div class="container">
            <nav class="navbar">
                <div class="logo-wrapper">
                    <a href="<?php echo url(); ?>" class="logo">
                        <img src="<?php echo asset('images/logo.png'); ?>" alt="Alanya TEKMER" class="logo-img">
                        <div class="logo-text">
                            <span class="logo-title">Alanya TEKMER</span>
                            <span class="logo-subtitle desktop-only">Teknoloji Geliştirme Merkezi</span>
                        </div>
                    </a>
                </div>
                
                <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleMobileMenu()" aria-label="Menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item dropdown" data-dropdown>
                        <a href="#" class="nav-link dropdown-toggle <?php echo in_array(($currentPage ?? ''), ['about', 'team', 'mevzuat', 'gallery']) ? 'active' : ''; ?>" onclick="return false;">
                            <i class="fas fa-info-circle"></i>
                            <span>Hakkımızda</span>
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo url('hakkimizda'); ?>"><i class="fas fa-building"></i> Bizi Tanıyın</a></li>
                            <li><a href="<?php echo url('ekibimiz'); ?>"><i class="fas fa-users"></i> Ekibimiz</a></li>
                            <li><a href="<?php echo url('galeri'); ?>"><i class="fas fa-images"></i> Galeri</a></li>
                            <li><a href="<?php echo url('mevzuat'); ?>"><i class="fas fa-gavel"></i> Mevzuat</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo url('hizmetlerimiz'); ?>" class="nav-link <?php echo ($currentPage ?? '') === 'services' ? 'active' : ''; ?>">
                            <i class="fas fa-briefcase"></i>
                            <span>Hizmetlerimiz</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo url('etkinlikler'); ?>" class="nav-link <?php echo ($currentPage ?? '') === 'events' ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Etkinlikler</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo url('firmalar'); ?>" class="nav-link <?php echo ($currentPage ?? '') === 'companies' ? 'active' : ''; ?>">
                            <i class="fas fa-industry"></i>
                            <span>Firmalar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo url('iletisim'); ?>" class="nav-link <?php echo ($currentPage ?? '') === 'contact' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i>
                            <span>İletişim</span>
                        </a>
                    </li>
                    <li class="nav-item cta-item">
                        <a href="<?php echo url('basvuru'); ?>" class="nav-link btn-cta <?php echo ($currentPage ?? '') === 'application' ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt"></i>
                            <span>Başvuru Yap</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        
        <!-- Mobile Menu Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
    </header>

