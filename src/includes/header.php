<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo Security::escape($metaDescription ?? getSetting('site_description')); ?>">
    <meta name="keywords" content="TEKMER, Alanya, girişimcilik, teknoloji, inovasyon, KOSGEB">
    <meta name="author" content="Alanya TEKMER">
    <title><?php echo Security::escape($pageTitle ?? 'Alanya TEKMER'); ?></title>
    
    <link rel="icon" type="image/x-icon" href="<?php echo url('logo.ico'); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo Security::escape($pageTitle ?? 'Alanya TEKMER'); ?>">
    <meta property="og:description" content="<?php echo Security::escape($metaDescription ?? getSetting('site_description')); ?>">
    
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="top-header-content">
                <div class="contact-info">
                    <a href="tel:<?php echo getSetting('contact_phone'); ?>">
                        <i class="fas fa-phone"></i> <?php echo getSetting('contact_phone'); ?>
                    </a>
                    <a href="mailto:<?php echo getSetting('contact_email'); ?>">
                        <i class="fas fa-envelope"></i> <?php echo getSetting('contact_email'); ?>
                    </a>
                    <span>
                        <i class="fas fa-map-marker-alt"></i> <?php echo Security::escape(getSetting('contact_address')); ?>
                    </span>
                </div>
                <div class="social-links">
                    <?php if (getSetting('facebook_url')): ?>
                        <a href="<?php echo Security::escape(getSetting('facebook_url')); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSetting('instagram_url')): ?>
                        <a href="<?php echo Security::escape(getSetting('instagram_url')); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSetting('linkedin_url')): ?>
                        <a href="<?php echo Security::escape(getSetting('linkedin_url')); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSetting('youtube_url')): ?>
                        <a href="<?php echo Security::escape(getSetting('youtube_url')); ?>" target="_blank" rel="noopener">
                            <i class="fab fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="<?php echo url(); ?>">
                        <img src="<?php echo asset('images/logo.png'); ?>" alt="Alanya TEKMER">
                    </a>
                </div>
                
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                
                <ul class="nav-menu" id="navMenu">
                    <li><a href="<?php echo url(); ?>" class="<?php echo ($currentPage ?? '') === 'home' ? 'active' : ''; ?>">Ana Sayfa</a></li>
                    <li class="dropdown">
                        <a href="<?php echo url('hakkimizda'); ?>" class="<?php echo ($currentPage ?? '') === 'about' ? 'active' : ''; ?>">
                            Hakkımızda <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo url('hakkimizda'); ?>">Biz Kimiz</a></li>
                            <li><a href="<?php echo url('ekibimiz'); ?>">Ekibimiz</a></li>
                            <li><a href="<?php echo url('mevzuat'); ?>">Mevzuat</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo url('hizmetlerimiz'); ?>" class="<?php echo ($currentPage ?? '') === 'services' ? 'active' : ''; ?>">Hizmetlerimiz</a></li>
                    <li><a href="<?php echo url('etkinlikler'); ?>" class="<?php echo ($currentPage ?? '') === 'events' ? 'active' : ''; ?>">Etkinlik ve Duyurular</a></li>
                    <li><a href="<?php echo url('firmalar'); ?>" class="<?php echo ($currentPage ?? '') === 'companies' ? 'active' : ''; ?>">Firmalar</a></li>
                    <li><a href="<?php echo url('basvuru'); ?>" class="<?php echo ($currentPage ?? '') === 'application' ? 'active' : ''; ?> btn-primary">Başvuru</a></li>
                    <li><a href="<?php echo url('iletisim'); ?>" class="<?php echo ($currentPage ?? '') === 'contact' ? 'active' : ''; ?>">İletişim</a></li>
                </ul>
            </nav>
        </div>
    </header>

