<?php
// Main entry point

// Check if this is a health check request
if ($_SERVER['REQUEST_URI'] === '/health' || $_SERVER['REQUEST_URI'] === '/health.php') {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['status' => 'healthy', 'timestamp' => time()]);
    exit;
}

// Load vendor autoload first
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Graceful error handling for missing dependencies
try {
    require_once __DIR__ . '/../src/config/db.php';
    require_once __DIR__ . '/../src/config/redis.php';
    require_once __DIR__ . '/../src/config/security.php';
    require_once __DIR__ . '/../src/utils/helpers.php';
    require_once __DIR__ . '/../src/utils/upload_path.php';
    require_once __DIR__ . '/../src/utils/validation.php';
    require_once __DIR__ . '/../src/utils/upload.php';
    require_once __DIR__ . '/../src/utils/cache.php';
} catch (Exception $e) {
    $errorMsg = 'Initialization error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    error_log($errorMsg);
    
    if (getenv('DEBUG_MODE') === 'true') {
        http_response_code(500);
        echo '<pre>' . $errorMsg . "\n\n" . $e->getTraceAsString() . '</pre>';
        exit;
    } else {
        http_response_code(500);
        die('Application initialization failed. Please check logs.');
    }
}

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
        
    case 'galeri':
        require __DIR__ . '/../src/pages/gallery.php';
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
    
    case (preg_match('/^firma\/(\d+)$/', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        require __DIR__ . '/../src/pages/company-detail.php';
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
        $adminPath = getenv('ADMIN_PATH');
        if ($adminPath && strpos($requestUri, $adminPath) === 0) {
            require __DIR__ . '/../src/admin/index.php';
        } else {
            http_response_code(404);
            require __DIR__ . '/../src/pages/404.php';
        }
        break;
}

