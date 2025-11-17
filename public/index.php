<?php
// Main entry point

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/config/redis.php';
require_once __DIR__ . '/../src/config/security.php';
require_once __DIR__ . '/../src/utils/helpers.php';
require_once __DIR__ . '/../src/utils/validation.php';
require_once __DIR__ . '/../src/utils/upload.php';
require_once __DIR__ . '/../src/utils/cache.php';

// Set security headers
Security::setSecurityHeaders();

// Start session
Security::startSession();

// Get request URI
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = parse_url($requestUri, PHP_URL_PATH);
$requestUri = trim($requestUri, '/');

// Remove query string
$requestUri = strtok($requestUri, '?');

// Route handling
switch ($requestUri) {
    case '':
    case 'index.php':
        require __DIR__ . '/../src/pages/home.php';
        break;
        
    case 'hakkimizda':
        require __DIR__ . '/../src/pages/about.php';
        break;
        
    case 'ekibimiz':
        require __DIR__ . '/../src/pages/team.php';
        break;
        
    case 'mevzuat':
        require __DIR__ . '/../src/pages/mevzuat.php';
        break;
        
    case 'hizmetlerimiz':
        require __DIR__ . '/../src/pages/services.php';
        break;
        
    case 'etkinlikler':
    case 'duyurular':
        require __DIR__ . '/../src/pages/events.php';
        break;
        
    case 'firmalar':
        require __DIR__ . '/../src/pages/companies.php';
        break;
        
    case 'basvuru':
        require __DIR__ . '/../src/pages/application.php';
        break;
        
    case 'iletisim':
        require __DIR__ . '/../src/pages/contact.php';
        break;
        
    case 'gizlilik-sozlesmesi':
        require __DIR__ . '/../src/pages/privacy.php';
        break;
        
    case 'kullanici-sozlesmesi':
        require __DIR__ . '/../src/pages/terms.php';
        break;
        
    case 'kvkk':
        require __DIR__ . '/../src/pages/kvkk.php';
        break;
        
    default:
        // Check if it's admin panel
        if (strpos($requestUri, getenv('ADMIN_PATH')) === 0) {
            require __DIR__ . '/../src/admin/index.php';
        } else {
            http_response_code(404);
            require __DIR__ . '/../src/pages/404.php';
        }
        break;
}

