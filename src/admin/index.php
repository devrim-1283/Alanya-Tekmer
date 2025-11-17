<?php
// Admin panel router

$requestUri = $_SERVER['REQUEST_URI'];
$adminPath = getenv('ADMIN_PATH');
$requestUri = str_replace($adminPath, '', $requestUri);
$requestUri = trim($requestUri, '/');
$requestUri = strtok($requestUri, '?');

// If not logged in and not on login page, redirect to login
if (!isAdmin() && $requestUri !== '' && $requestUri !== 'login') {
    redirect(url($adminPath));
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
        
    case 'logout':
        requireAdmin();
        require __DIR__ . '/logout.php';
        break;
        
    default:
        http_response_code(404);
        echo '404 - Page not found';
        break;
}

