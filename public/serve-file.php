<?php
// Serve files from persistent storage
// Used when UPLOAD_PATH is outside public directory (Coolify)

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config/security.php';
require_once __DIR__ . '/../src/utils/helpers.php';

$filename = $_GET['f'] ?? '';

if (empty($filename)) {
    http_response_code(404);
    exit('File not found');
}

// Security: Only allow alphanumeric, dots, underscores, hyphens
$filename = basename($filename);
if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
    http_response_code(403);
    exit('Invalid filename');
}

$uploadDir = getenv('UPLOAD_PATH') ?: __DIR__ . '/uploads';
$filepath = $uploadDir . '/' . $filename;

if (!file_exists($filepath)) {
    http_response_code(404);
    exit('File not found');
}

// Get MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filepath);
finfo_close($finfo);

// Set appropriate headers
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: public, max-age=31536000'); // 1 year cache

// For PDFs, allow inline display
if ($mimeType === 'application/pdf') {
    header('Content-Disposition: inline; filename="' . $filename . '"');
} else {
    header('Content-Disposition: attachment; filename="' . $filename . '"');
}

// Output file
readfile($filepath);
exit;

