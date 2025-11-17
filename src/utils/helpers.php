<?php
// Helper functions

function redirect($url) {
    header("Location: $url");
    exit;
}

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function getBaseUrl() {
    $baseUrl = getenv('BASE_URL');
    if ($baseUrl) {
        return rtrim($baseUrl, '/');
    }
    
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $protocol . '://' . $host;
}

function asset($path) {
    return getBaseUrl() . '/assets/' . ltrim($path, '/');
}

function url($path = '') {
    return getBaseUrl() . '/' . ltrim($path, '/');
}

function getSetting($key, $default = '') {
    static $settings = null;
    
    if ($settings === null) {
        $redis = RedisCache::getInstance();
        $cacheKey = 'tekmer:settings';
        
        $settings = $redis->get($cacheKey);
        
        if ($settings === null) {
            $db = Database::getInstance();
            $results = $db->fetchAll('SELECT key, value FROM settings');
            $settings = [];
            foreach ($results as $row) {
                $settings[$row['key']] = $row['value'];
            }
            
            $ttl = getenv('CACHE_TTL_SETTINGS') ?: 3600;
            $redis->set($cacheKey, $settings, (int)$ttl);
        }
    }
    
    return $settings[$key] ?? $default;
}

function formatDate($date, $format = 'd.m.Y') {
    if (empty($date)) {
        return '';
    }
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    return date($format, $timestamp);
}

function formatPhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($phone) === 10) {
        return '+90 ' . substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6, 2) . ' ' . substr($phone, 8, 2);
    }
    return $phone;
}

function truncate($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

function isAdmin() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect(url(getenv('ADMIN_PATH')));
    }
}

function logPageView($page) {
    try {
        $db = Database::getInstance();
        $ip = Security::getClientIp();
        $uniqueIpHash = Security::getUniqueIpHash();
        $userAgent = Security::getUserAgent();
        $referer = $_SERVER['HTTP_REFERER'] ?? null;
        
        $db->execute(
            'INSERT INTO page_views (page, ip_address, unique_ip_hash, user_agent, referer) VALUES (?, ?, ?, ?, ?)',
            [$page, $ip, $uniqueIpHash, $userAgent, $referer]
        );
    } catch (Exception $e) {
        error_log('Failed to log page view: ' . $e->getMessage());
    }
}

function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge badge-warning">Beklemede</span>',
        'reviewed' => '<span class="badge badge-info">İncelendi</span>',
        'approved' => '<span class="badge badge-success">Onaylandı</span>',
        'rejected' => '<span class="badge badge-danger">Reddedildi</span>',
        'new' => '<span class="badge badge-primary">Yeni</span>',
        'read' => '<span class="badge badge-secondary">Okundu</span>',
        'replied' => '<span class="badge badge-success">Yanıtlandı</span>',
    ];
    
    return $badges[$status] ?? $status;
}

