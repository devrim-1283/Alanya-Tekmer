<?php
// Redis configuration and connection

// Check if vendor/autoload exists before requiring
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

class RedisCache {
    private static $instance = null;
    private $redis;
    private $enabled = true;
    
    private function __construct() {
        $redisUrl = getenv('REDIS_URL');
        
        if (!$redisUrl) {
            error_log('REDIS_URL not set, caching disabled');
            $this->enabled = false;
            return;
        }
        
        // Check if Predis is available
        if (!class_exists('Predis\Client')) {
            error_log('Predis\Client class not found, caching disabled');
            $this->enabled = false;
            return;
        }
        
        try {
            $this->redis = new \Predis\Client($redisUrl, [
                'parameters' => [
                    'database' => 0,
                    'timeout' => 5.0,
                ],
            ]);
            
            // Test connection
            $this->redis->ping();
        } catch (Exception $e) {
            error_log('Redis connection failed: ' . $e->getMessage());
            $this->enabled = false;
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function isEnabled() {
        return $this->enabled;
    }
    
    public function get($key) {
        if (!$this->enabled) {
            return null;
        }
        
        try {
            $value = $this->redis->get($key);
            return $value ? json_decode($value, true) : null;
        } catch (Exception $e) {
            error_log('Redis GET error: ' . $e->getMessage());
            return null;
        }
    }
    
    public function set($key, $value, $ttl = 3600) {
        if (!$this->enabled) {
            return false;
        }
        
        try {
            $serialized = json_encode($value);
            if ($ttl > 0) {
                return $this->redis->setex($key, $ttl, $serialized);
            } else {
                return $this->redis->set($key, $serialized);
            }
        } catch (Exception $e) {
            error_log('Redis SET error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function delete($key) {
        if (!$this->enabled) {
            return false;
        }
        
        try {
            return $this->redis->del([$key]) > 0;
        } catch (Exception $e) {
            error_log('Redis DELETE error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function deletePattern($pattern) {
        if (!$this->enabled) {
            return false;
        }
        
        try {
            $keys = $this->redis->keys($pattern);
            if (!empty($keys)) {
                return $this->redis->del($keys);
            }
            return 0;
        } catch (Exception $e) {
            error_log('Redis DELETE PATTERN error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function increment($key, $value = 1) {
        if (!$this->enabled) {
            return 0;
        }
        
        try {
            return $this->redis->incrby($key, $value);
        } catch (Exception $e) {
            error_log('Redis INCREMENT error: ' . $e->getMessage());
            return 0;
        }
    }
    
    public function expire($key, $ttl) {
        if (!$this->enabled) {
            return false;
        }
        
        try {
            return $this->redis->expire($key, $ttl);
        } catch (Exception $e) {
            error_log('Redis EXPIRE error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function exists($key) {
        if (!$this->enabled) {
            return false;
        }
        
        try {
            return $this->redis->exists($key) > 0;
        } catch (Exception $e) {
            error_log('Redis EXISTS error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function flush() {
        if (!$this->enabled) {
            return false;
        }
        
        try {
            return $this->redis->flushdb();
        } catch (Exception $e) {
            error_log('Redis FLUSH error: ' . $e->getMessage());
            return false;
        }
    }
}

