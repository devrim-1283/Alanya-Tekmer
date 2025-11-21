<?php
// Admin panel router

$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = parse_url($requestUri, PHP_URL_PATH);
$requestUri = trim($requestUri, '/');

$adminPath = getenv('ADMIN_PATH');
if ($adminPath && strpos($requestUri, $adminPath) === 0) {
    $requestUri = substr($requestUri, strlen($adminPath));
    $requestUri = trim($requestUri, '/');
}

// If not logged in and not on login page, show login page
if (!isAdmin() && $requestUri !== '' && $requestUri !== 'login') {
    $requestUri = 'login';
}

switch ($requestUri) {
    case '':
    case 'login':
        require __DIR__ . '/login.php';
        break;
        
    case 'dashboard':
        requireAdmin();
        require __DIR__ . '/dashboard.php';
        break;
        
    case 'team':
    case 'team/add':
    case 'team/edit':
    case 'team/delete':
        requireAdmin();
        require __DIR__ . '/team.php';
        break;
        
    case 'events':
    case 'events/add':
    case 'events/edit':
    case 'events/delete':
        requireAdmin();
        require __DIR__ . '/events.php';
        break;
        
    case 'companies':
    case 'companies/add':
    case 'companies/edit':
    case 'companies/delete':
        requireAdmin();
        require __DIR__ . '/companies.php';
        break;
        
    case 'faq':
    case 'faq/add':
    case 'faq/edit':
    case 'faq/delete':
        requireAdmin();
        require __DIR__ . '/faq.php';
        break;
        
    case 'gallery':
    case 'gallery/add':
    case 'gallery/edit':
    case 'gallery/delete':
        requireAdmin();
        require __DIR__ . '/gallery.php';
        break;
        
    case 'applications':
    case 'applications/view':
    case 'applications/update-status':
        requireAdmin();
        require __DIR__ . '/applications.php';
        break;
        
    case 'settings':
        requireAdmin();
        require __DIR__ . '/settings.php';
        break;
        
    case 'analytics':
        requireAdmin();
        require __DIR__ . '/analytics.php';
        break;
        
    case 'notifications':
        requireAdmin();
        require __DIR__ . '/notifications.php';
        break;
        
    case 'logout':
        requireAdmin();
        require __DIR__ . '/logout.php';
        break;
        
    default:
        // Check for application detail route: application/{id}
        if (preg_match('/^application\/(\d+)$/', $requestUri, $matches)) {
            requireAdmin();
            $_GET['id'] = $matches[1];
            require __DIR__ . '/application-detail.php';
        } else {
            http_response_code(404);
            echo '404 - Page not found';
        }
        break;
}

