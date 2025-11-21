<?php
// File upload and image processing utilities

class FileUpload {
    
    public static function uploadPdf($file, $prefix = 'application') {
        $maxSize = getenv('MAX_PDF_SIZE') ?: 5242880; // 5MB default
        
        $validation = Security::validateFileUpload($file, ['application/pdf'], $maxSize);
        if (!$validation['success']) {
            return $validation;
        }
        
        $filename = self::generateUniqueFilename($prefix, 'pdf');
        $uploadDir = getenv('UPLOAD_PATH') ?: __DIR__ . '/../../public/uploads';
        
        // Create upload directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $uploadPath = $uploadDir . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            error_log('PDF uploaded successfully: ' . $uploadPath);
            chmod($uploadPath, 0644); // Set readable permissions
            return ['success' => true, 'filename' => $filename];
        }
        
        error_log('PDF upload failed: ' . ($uploadDir . '/' . $filename) . ' - ' . (error_get_last()['message'] ?? 'Unknown error'));
        return ['success' => false, 'error' => 'Dosya yüklenemedi. Lütfen tekrar deneyin.'];
    }
    
    public static function uploadImage($file, $prefix = 'image', $maxWidth = 1920, $convertToWebp = true) {
        $maxSize = getenv('MAX_IMAGE_SIZE') ?: 5242880; // 5MB default
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        
        $validation = Security::validateFileUpload($file, $allowedTypes, $maxSize);
        if (!$validation['success']) {
            return $validation;
        }
        
        try {
            $sourceImage = self::createImageFromFile($file['tmp_name'], $file['type']);
            if (!$sourceImage) {
                return ['success' => false, 'error' => 'Resim işlenemedi'];
            }
            
            $originalWidth = imagesx($sourceImage);
            $originalHeight = imagesy($sourceImage);
            
            // Resize if needed
            if ($originalWidth > $maxWidth) {
                $ratio = $maxWidth / $originalWidth;
                $newWidth = $maxWidth;
                $newHeight = (int)($originalHeight * $ratio);
            } else {
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            }
            
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            
            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            $uploadDir = getenv('UPLOAD_PATH') ?: __DIR__ . '/../../public/uploads';
            
            // Save as WebP
            if ($convertToWebp && function_exists('imagewebp')) {
                $filename = self::generateUniqueFilename($prefix, 'webp');
                $uploadPath = $uploadDir . '/' . $filename;
                imagewebp($resizedImage, $uploadPath, 85);
            } else {
                // Fallback to JPEG
                $filename = self::generateUniqueFilename($prefix, 'jpg');
                $uploadPath = $uploadDir . '/' . $filename;
                imagejpeg($resizedImage, $uploadPath, 85);
            }
            
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
            
            return ['success' => true, 'filename' => $filename];
            
        } catch (Exception $e) {
            error_log('Image upload error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Resim yüklenirken bir hata oluştu'];
        }
    }
    
    public static function uploadMultipleImages($files, $prefix = 'image', $maxCount = 10) {
        if (!is_array($files['name'])) {
            return ['success' => false, 'error' => 'Geçersiz dosya formatı'];
        }
        
        $fileCount = count($files['name']);
        if ($fileCount > $maxCount) {
            return ['success' => false, 'error' => "En fazla {$maxCount} resim yükleyebilirsiniz"];
        }
        
        $uploadedFiles = [];
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }
            
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];
            
            $result = self::uploadImage($file, $prefix);
            if ($result['success']) {
                $uploadedFiles[] = $result['filename'];
            }
        }
        
        return ['success' => true, 'files' => $uploadedFiles];
    }
    
    public static function deleteFile($filename) {
        if (empty($filename)) {
            return false;
        }
        
        $uploadDir = getenv('UPLOAD_PATH') ?: __DIR__ . '/../../public/uploads';
        $filepath = $uploadDir . '/' . basename($filename);
        
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }
    
    private static function createImageFromFile($filepath, $mimeType) {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($filepath);
            case 'image/png':
                return imagecreatefrompng($filepath);
            default:
                return false;
        }
    }
    
    private static function generateUniqueFilename($prefix, $extension) {
        return $prefix . '_' . uniqid() . '_' . time() . '.' . $extension;
    }
}

