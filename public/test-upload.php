<?php
// Test upload directory
header('Content-Type: text/plain');
echo "Upload Directory Tests:\n";
echo str_repeat("=", 50) . "\n\n";

// Check public/uploads
$publicUploads = __DIR__ . '/uploads';
echo "1. Public uploads: $publicUploads\n";
echo "   Exists: " . (file_exists($publicUploads) ? 'YES' : 'NO') . "\n";
if (file_exists($publicUploads)) {
    echo "   Writable: " . (is_writable($publicUploads) ? 'YES' : 'NO') . "\n";
    $files = @scandir($publicUploads);
    echo "   Files: " . ($files ? count($files) - 2 : 0) . "\n";
} else {
    echo "   >> DIRECTORY DOES NOT EXIST! Creating...\n";
    if (@mkdir($publicUploads, 0755, true)) {
        echo "   >> Created successfully!\n";
    } else {
        echo "   >> Failed to create!\n";
    }
}

// Check UPLOAD_PATH env
echo "\n2. UPLOAD_PATH env: ";
$uploadPath = getenv('UPLOAD_PATH');
if ($uploadPath) {
    echo "$uploadPath\n";
    echo "   Exists: " . (file_exists($uploadPath) ? 'YES' : 'NO') . "\n";
    if (file_exists($uploadPath)) {
        echo "   Writable: " . (is_writable($uploadPath) ? 'YES' : 'NO') . "\n";
        $files = @scandir($uploadPath);
        echo "   Files: " . ($files ? count($files) - 2 : 0) . "\n";
    }
} else {
    echo "NOT SET (will use public/uploads)\n";
}

echo "\n3. Recommended UPLOAD_PATH for Coolify:\n";
echo "   /app/storage/uploads\n";
echo "   (Create this in Coolify persistent storage)\n";

echo "\n4. Current working directory: " . getcwd() . "\n";
echo "5. __DIR__: " . __DIR__ . "\n";

if (file_exists($publicUploads)) {
    echo "\n6. Files in public/uploads:\n";
    $files = @scandir($publicUploads);
    if ($files) {
        $files = array_diff($files, ['.', '..']);
        if (empty($files)) {
            echo "   (empty)\n";
        } else {
            foreach ($files as $file) {
                $path = $publicUploads . '/' . $file;
                echo "   - $file (" . filesize($path) . " bytes)\n";
            }
        }
    }
}

if ($uploadPath && file_exists($uploadPath)) {
    echo "\n7. Files in UPLOAD_PATH:\n";
    $files = @scandir($uploadPath);
    if ($files) {
        $files = array_diff($files, ['.', '..']);
        if (empty($files)) {
            echo "   (empty)\n";
        } else {
            foreach ($files as $file) {
                $path = $uploadPath . '/' . $file;
                echo "   - $file (" . filesize($path) . " bytes)\n";
            }
        }
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "SOLUTION:\n";
echo "1. In Coolify, create persistent storage: /app/storage/uploads\n";
echo "2. Set environment variable: UPLOAD_PATH=/app/storage/uploads\n";
echo "3. Restart the application\n";
