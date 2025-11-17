<?php
// Upload path helper for serving files

function getUploadPath() {
    return getenv('UPLOAD_PATH') ?: __DIR__ . '/../../public/uploads';
}

function getUploadUrl($filename) {
    if (empty($filename)) {
        return '';
    }
    
    // If using Coolify persistent storage, serve through PHP
    if (getenv('UPLOAD_PATH') && strpos(getenv('UPLOAD_PATH'), '/app/') === 0) {
        return url('serve-file.php?f=' . urlencode(basename($filename)));
    }
    
    // Otherwise, serve directly from public/uploads
    return url('uploads/' . basename($filename));
}

