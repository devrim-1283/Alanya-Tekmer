<?php
// Cache utility functions

class CacheHelper {
    
    public static function getOrSet($key, $callback, $ttl = 3600) {
        $redis = RedisCache::getInstance();
        
        $cached = $redis->get($key);
        if ($cached !== null) {
            return $cached;
        }
        
        $data = $callback();
        $redis->set($key, $data, $ttl);
        
        return $data;
    }
    
    public static function clearPageCache($page) {
        $redis = RedisCache::getInstance();
        return $redis->delete("tekmer:page:{$page}");
    }
    
    public static function clearDataCache($type) {
        $redis = RedisCache::getInstance();
        return $redis->delete("tekmer:data:{$type}");
    }
    
    public static function clearAllCache() {
        $redis = RedisCache::getInstance();
        return $redis->deletePattern('tekmer:*');
    }
    
    public static function cachePage($page, $content) {
        $redis = RedisCache::getInstance();
        $ttl = getenv('CACHE_TTL_STATIC') ?: 86400;
        return $redis->set("tekmer:page:{$page}", $content, (int)$ttl);
    }
    
    public static function getCachedPage($page) {
        $redis = RedisCache::getInstance();
        return $redis->get("tekmer:page:{$page}");
    }
    
    public static function cacheData($type, $data, $ttl = null) {
        $redis = RedisCache::getInstance();
        
        if ($ttl === null) {
            // Determine TTL based on type
            switch ($type) {
                case 'companies':
                case 'team':
                    $ttl = getenv('CACHE_TTL_STATIC') ?: 86400;
                    break;
                case 'events':
                case 'announcements':
                    $ttl = getenv('CACHE_TTL_DYNAMIC') ?: 900;
                    break;
                default:
                    $ttl = 3600;
            }
        }
        
        return $redis->set("tekmer:data:{$type}", $data, (int)$ttl);
    }
    
    public static function getCachedData($type) {
        $redis = RedisCache::getInstance();
        return $redis->get("tekmer:data:{$type}");
    }
}

