<?php
// Security configuration and functions

class Security {
    
    // Start secure session with Redis
    public static function startSession() {
        // Check if session already started
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }
        
        // Check if headers already sent
        if (headers_sent()) {
            error_log('Cannot start session: headers already sent');
            return;
        }
        
        // Try Redis session handler only if PHP redis extension is available
        $redis = RedisCache::getInstance();
        if ($redis->isEnabled() && extension_loaded('redis')) {
            $redisUrl = getenv('REDIS_URL');
            if ($redisUrl) {
                @ini_set('session.save_handler', 'redis');
                @ini_set('session.save_path', $redisUrl);
            }
        }
        
        // Session configuration
        @ini_set('session.cookie_httponly', 1);
        @ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
        @ini_set('session.cookie_samesite', 'Strict');
        @ini_set('session.use_strict_mode', 1);
        
        // Use session secret from environment
        $sessionSecret = getenv('SESSION_SECRET');
        if ($sessionSecret) {
            @ini_set('session.hash_function', 'sha256');
        }
        
        session_name('TEKMER_SESSION');
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } else if (time() - $_SESSION['created'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }
    
    // Generate CSRF token
    public static function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            // Use CSRF secret for additional entropy if available
            $csrfSecret = getenv('CSRF_SECRET') ?: '';
            $randomBytes = random_bytes(32);
            
            if ($csrfSecret) {
                // Combine random bytes with secret for stronger token
                $_SESSION['csrf_token'] = hash_hmac('sha256', $randomBytes, $csrfSecret);
            } else {
                $_SESSION['csrf_token'] = bin2hex($randomBytes);
            }
        }
        return $_SESSION['csrf_token'];
    }
    
    // Validate CSRF token
    public static function validateCsrfToken($token) {
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        // Use timing-safe comparison
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    // XSS protection - sanitize output
    public static function escape($string) {
        if ($string === null) {
            return '';
        }
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    // Clean input
    public static function cleanInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'cleanInput'], $data);
        }
        return trim(strip_tags($data));
    }
    
    // Validate email
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    // Validate TC number (11 digits)
    public static function validateTcNumber($tc) {
        $tc = preg_replace('/[^0-9]/', '', $tc);
        return strlen($tc) === 11 && ctype_digit($tc);
    }
    
    // Normalize phone number
    public static function normalizePhone($phone) {
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Remove +90 or 0 prefix
        $phone = preg_replace('/^\+?90/', '', $phone);
        $phone = ltrim($phone, '0');
        
        return $phone;
    }
    
    // Validate phone number
    public static function validatePhone($phone) {
        $normalized = self::normalizePhone($phone);
        return strlen($normalized) === 10 && ctype_digit($normalized);
    }
    
    // Rate limiting check
    public static function checkRateLimit($identifier, $limit, $window, $action = 'general') {
        $redis = RedisCache::getInstance();
        
        if (!$redis->isEnabled()) {
            return true; // Allow if Redis is not available
        }
        
        $key = "rate_limit:{$action}:{$identifier}";
        $current = $redis->get($key);
        
        if ($current === null) {
            $redis->set($key, 1, $window);
            return true;
        }
        
        if ($current >= $limit) {
            return false;
        }
        
        $redis->increment($key);
        return true;
    }
    
    // Get client IP
    public static function getClientIp() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        // Check for proxies (Cloudflare)
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
        }
        
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }
    
    // Get unique IP hash for analytics
    public static function getUniqueIpHash() {
        $ip = self::getClientIp();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return hash('sha256', $ip . $userAgent . date('Y-m-d'));
    }
    
    // Get user agent
    public static function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }
    
    // Verify Cloudflare Turnstile
    public static function verifyTurnstile($token) {
        $secretKey = getenv('TURNSTILE_SECRET_KEY');
        
        if (!$secretKey || !$token) {
            return false;
        }
        
        $data = [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => self::getClientIp()
        ];
        
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
                'timeout' => 10
            ]
        ];
        
        $context = stream_context_create($options);
        $response = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
        
        if ($response === false) {
            error_log('Turnstile verification failed: Unable to connect');
            return false;
        }
        
        $result = json_decode($response, true);
        return isset($result['success']) && $result['success'] === true;
    }
    
    // Set security headers
    public static function setSecurityHeaders() {
        // Don't set headers if already sent
        if (headers_sent()) {
            return;
        }
        
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // More permissive CSP for better compatibility with Cloudflare Turnstile and modern browsers
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://challenges.cloudflare.com https://cdnjs.cloudflare.com https://unpkg.com https://static.cloudflareinsights.com https://*.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com https://unpkg.com",
            "img-src 'self' data: https: blob:",
            "font-src 'self' data: https://cdnjs.cloudflare.com https://fonts.gstatic.com",
            "connect-src 'self' https://challenges.cloudflare.com https://*.cloudflare.com https://cloudflareinsights.com",
            "frame-src 'self' https://challenges.cloudflare.com https://*.cloudflare.com https://www.google.com",
            "media-src 'self' https:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'"
        ];
        
        header("Content-Security-Policy: " . implode("; ", $csp));
        
        // Permissions Policy for better privacy
        header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    }
    
    // Hash password
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    // Verify password
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    // Validate file upload
    public static function validateFileUpload($file, $allowedTypes, $maxSize) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'error' => 'Dosya yüklenemedi'];
        }
        
        if ($file['size'] > $maxSize) {
            $maxMB = round($maxSize / 1048576, 2);
            return ['success' => false, 'error' => "Dosya boyutu {$maxMB}MB'den küçük olmalıdır"];
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'error' => 'Geçersiz dosya tipi'];
        }
        
        // Check magic bytes for common file types
        $handle = fopen($file['tmp_name'], 'rb');
        $magicBytes = fread($handle, 8);
        fclose($handle);
        
        $validMagicBytes = false;
        if (in_array('application/pdf', $allowedTypes) && strpos($magicBytes, '%PDF') === 0) {
            $validMagicBytes = true;
        }
        if (in_array('image/jpeg', $allowedTypes) && (bin2hex(substr($magicBytes, 0, 3)) === 'ffd8ff')) {
            $validMagicBytes = true;
        }
        if (in_array('image/png', $allowedTypes) && (bin2hex(substr($magicBytes, 0, 8)) === '89504e470d0a1a0a')) {
            $validMagicBytes = true;
        }
        
        if (!$validMagicBytes) {
            return ['success' => false, 'error' => 'Dosya içeriği geçersiz'];
        }
        
        return ['success' => true];
    }
}

