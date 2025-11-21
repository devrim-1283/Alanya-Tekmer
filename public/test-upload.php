<?php
// Test upload directory
echo "Upload Directory Tests:\n\n";

// Check public/uploads
$publicUploads = __DIR__ . '/uploads';
echo "1. Public uploads: $publicUploads\n";
echo "   Exists: " . (file_exists($publicUploads) ? 'YES' : 'NO') . "\n";
echo "   Writable: " . (is_writable($publicUploads) ? 'YES' : 'NO') . "\n";
echo "   Files: " . count(scandir($publicUploads)) - 2 . "\n\n";

// Check UPLOAD_PATH env
$uploadPath = getenv('UPLOAD_PATH');
echo "2. UPLOAD_PATH env: " . ($uploadPath ?: 'NOT SET') . "\n";
if ($uploadPath) {
    echo "   Exists: " . (file_exists($uploadPath) ? 'YES' : 'NO') . "\n";
    echo "   Writable: " . (is_writable($uploadPath) ? 'YES' : 'NO') . "\n";
    if (file_exists($uploadPath)) {
        echo "   Files: " . count(scandir($uploadPath)) - 2 . "\n";
    }
}

echo "\n3. Files in public/uploads:\n";
if (file_exists($publicUploads)) {
    $files = array_diff(scandir($publicUploads), ['.', '..']);
    foreach ($files as $file) {
        echo "   - $file\n";
    }
}

if ($uploadPath && file_exists($uploadPath)) {
    echo "\n4. Files in UPLOAD_PATH:\n";
    $files = array_diff(scandir($uploadPath), ['.', '..']);
    foreach ($files as $file) {
        echo "   - $file\n";
    }
}

