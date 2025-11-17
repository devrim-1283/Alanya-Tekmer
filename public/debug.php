<?php
// Minimal debug page - NO database, NO dependencies

header('Content-Type: text/plain; charset=utf-8');

if (getenv('DEBUG_MODE') !== 'true') {
    http_response_code(403);
    die('Debug mode disabled');
}

echo "=== ALANYA TEKMER DEBUG ===\n\n";

echo "⏰ Time: " . date('Y-m-d H:i:s') . "\n";
echo "🐘 PHP Version: " . PHP_VERSION . "\n\n";

echo "=== ENVIRONMENT VARIABLES ===\n";
$envVars = [
    'APP_ENV', 'DEBUG_MODE', 'BASE_URL', 
    'DATABASE_URL', 'PORT', 'UPLOAD_PATH',
    'ADMIN_PATH', 'SESSION_SECRET', 'CSRF_SECRET'
];

foreach ($envVars as $var) {
    $value = getenv($var);
    if (in_array($var, ['DATABASE_URL', 'SESSION_SECRET', 'CSRF_SECRET'])) {
        $value = $value ? substr($value, 0, 20) . '...' : 'NOT SET';
    }
    echo sprintf("%-20s: %s\n", $var, $value ?: 'NOT SET');
}

echo "\n=== PHP EXTENSIONS ===\n";
$extensions = ['pdo', 'pdo_pgsql', 'mbstring', 'fileinfo', 'gd', 'json', 'session'];
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo sprintf("%-15s: %s\n", $ext, $loaded ? '✅ YES' : '❌ NO');
}

echo "\n=== SERVER INFO ===\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Server Protocol: " . ($_SERVER['SERVER_PROTOCOL'] ?? 'Unknown') . "\n";
echo "Request Method: " . ($_SERVER['REQUEST_METHOD'] ?? 'Unknown') . "\n";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "\n";
echo "Remote Address: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";

echo "\n=== FILE CHECKS ===\n";
$files = [
    'vendor/autoload.php',
    'src/config/db.php',
    'src/config/security.php',
    'src/utils/cache.php',
    'sql/schema.sql'
];

foreach ($files as $file) {
    $path = __DIR__ . '/../' . $file;
    $exists = file_exists($path);
    echo sprintf("%-30s: %s\n", $file, $exists ? '✅ EXISTS' : '❌ MISSING');
}

echo "\n=== MEMORY ===\n";
echo "Memory Usage: " . round(memory_get_usage() / 1024 / 1024, 2) . " MB\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Peak Memory: " . round(memory_get_peak_usage() / 1024 / 1024, 2) . " MB\n";

echo "\n=== END DEBUG ===\n";
