#!/usr/bin/env php
<?php
/**
 * Create uploads directory if it doesn't exist
 * Run this after deployment
 */

echo "Creating uploads directory...\n";

$publicUploads = __DIR__ . '/public/uploads';
$uploadPath = getenv('UPLOAD_PATH');

// Create public/uploads if it doesn't exist
if (!file_exists($publicUploads)) {
    if (mkdir($publicUploads, 0755, true)) {
        echo "✓ Created: $publicUploads\n";
    } else {
        echo "✗ Failed to create: $publicUploads\n";
    }
} else {
    echo "✓ Already exists: $publicUploads\n";
}

// Create UPLOAD_PATH if set and doesn't exist
if ($uploadPath) {
    if (!file_exists($uploadPath)) {
        if (mkdir($uploadPath, 0755, true)) {
            echo "✓ Created: $uploadPath\n";
        } else {
            echo "✗ Failed to create: $uploadPath\n";
        }
    } else {
        echo "✓ Already exists: $uploadPath\n";
    }
}

// Create .gitkeep files
$gitkeepContent = "# Keep this directory in git\n";

if (file_exists($publicUploads)) {
    file_put_contents($publicUploads . '/.gitkeep', $gitkeepContent);
    echo "✓ Created .gitkeep in public/uploads\n";
}

if ($uploadPath && file_exists($uploadPath)) {
    file_put_contents($uploadPath . '/.gitkeep', $gitkeepContent);
    echo "✓ Created .gitkeep in UPLOAD_PATH\n";
}

echo "\nDone!\n";

