<?php
// Debug endpoint - sadece DEBUG_MODE=true iken çalışır

header('Content-Type: text/plain');

if (getenv('DEBUG_MODE') !== 'true') {
    http_response_code(403);
    die('Debug mode disabled');
}

echo "=== Alanya TEKMER Debug Info ===\n\n";

echo "PHP Version: " . PHP_VERSION . "\n\n";

echo "=== Environment Variables ===\n";
$envVars = ['DATABASE_URL', 'DEBUG_MODE', 'APP_ENV', 'BASE_URL', 'UPLOAD_PATH', 'PORT'];
foreach ($envVars as $var) {
    $value = getenv($var);
    if ($var === 'DATABASE_URL') {
        // Hide passwords
        $value = preg_replace('/:[^:@]+@/', ':****@', $value);
    }
    echo "$var: " . ($value ? $value : 'NOT SET') . "\n";
}

echo "\n=== File Checks ===\n";
echo "vendor/autoload.php exists: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'YES' : 'NO') . "\n";
echo "src/config/db.php exists: " . (file_exists(__DIR__ . '/../src/config/db.php') ? 'YES' : 'NO') . "\n";
echo "src/config/redis.php exists: " . (file_exists(__DIR__ . '/../src/config/redis.php') ? 'YES' : 'NO') . "\n";

echo "\n=== Extensions ===\n";
echo "PDO: " . (extension_loaded('pdo') ? 'YES' : 'NO') . "\n";
echo "pdo_pgsql: " . (extension_loaded('pdo_pgsql') ? 'YES' : 'NO') . "\n";
echo "gd: " . (extension_loaded('gd') ? 'YES' : 'NO') . "\n";
echo "mbstring: " . (extension_loaded('mbstring') ? 'YES' : 'NO') . "\n";
echo "fileinfo: " . (extension_loaded('fileinfo') ? 'YES' : 'NO') . "\n";

echo "\n=== Database Test ===\n";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../src/config/db.php';
    $db = Database::getInstance();
    echo "Database connection: SUCCESS\n";
    echo "Database version: ";
    $result = $db->fetchOne('SELECT version()');
    echo $result['version'] . "\n";
} catch (Exception $e) {
    echo "Database connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Cache Test (PostgreSQL) ===\n";
try {
    require_once __DIR__ . '/../src/utils/cache.php';
    Cache::set('test_key', 'test_value', 60);
    $value = Cache::get('test_key');
    echo "Cache write/read: " . ($value === 'test_value' ? 'SUCCESS' : 'FAILED') . "\n";
    Cache::delete('test_key');
} catch (Exception $e) {
    echo "Cache test: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Memory ===\n";
echo "Memory usage: " . round(memory_get_usage() / 1024 / 1024, 2) . " MB\n";
echo "Memory limit: " . ini_get('memory_limit') . "\n";

echo "\n=== END ===\n";

