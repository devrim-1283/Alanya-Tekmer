<?php
// PHP Built-in Server Router
// This file handles routing for PHP built-in server

// Get request URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Log all requests (for debugging)
error_log("Router: Request to $uri from " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));

// Health check endpoint (PRIORITY - fast response, no dependencies)
if ($uri === '/health' || $uri === '/health.php') {
    error_log("Router: Health check request");
    header('Content-Type: application/json');
    header('Cache-Control: no-cache');
    http_response_code(200);
    echo json_encode(['status' => 'healthy', 'timestamp' => time(), 'uri' => $uri]);
    exit(0);
}

// Debug endpoint (if DEBUG_MODE is true)
if ($uri === '/debug.php' || $uri === '/debug') {
    error_log("Router: Debug request");
    if (file_exists(__DIR__ . '/debug.php')) {
        require __DIR__ . '/debug.php';
        exit(0);
    }
}

// Serve static files directly (assets, images, etc)
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    error_log("Router: Static file $uri");
    return false; // Let PHP server handle it
}

// Set security headers for all other requests
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Route everything else through index.php
error_log("Router: Routing $uri to index.php");
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';

