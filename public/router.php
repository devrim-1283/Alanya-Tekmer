<?php
// Ultra simple router - NO dependencies

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Log request
error_log("[ROUTER] Request: $uri from " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));

// Health check - PRIORITY
if ($uri === '/health' || $uri === '/health.php') {
    error_log("[ROUTER] Health check - responding OK");
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'time' => time()]);
    exit(0);
}

// Ping
if ($uri === '/ping' || $uri === '/ping.php') {
    error_log("[ROUTER] Ping - responding pong");
    header('Content-Type: text/plain');
    http_response_code(200);
    echo 'pong';
    exit(0);
}

// Static files
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    error_log("[ROUTER] Static file: $uri");
    return false; // Let PHP server handle it
}

// Debug
if ($uri === '/debug' || $uri === '/debug.php') {
    if (file_exists(__DIR__ . '/debug.php')) {
        error_log("[ROUTER] Debug page");
        require __DIR__ . '/debug.php';
        exit(0);
    }
}

// Everything else to index.php
error_log("[ROUTER] Routing to index.php");
require __DIR__ . '/index.php';
