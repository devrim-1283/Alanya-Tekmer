# Redis Kaldırıldı - PostgreSQL Cache Kullanıyoruz

## 🔄 Değişiklikler

### Kaldırılanlar
- ❌ Redis dependency (`predis/predis`)
- ❌ `src/config/redis.php`
- ❌ Redis session handler
- ❌ `php82Extensions.redis` from nixpacks

### Eklenenler
- ✅ PostgreSQL cache table
- ✅ File-based PHP sessions
- ✅ Cache fonksiyonları PostgreSQL ile çalışıyor

## 📋 Avantajlar

1. **Daha Basit Deploy**: Sadece PostgreSQL gerekli, Redis servisi yok
2. **Daha Az Maliyet**: Tek veritabanı servisi
3. **Daha Az Karmaşa**: Dependency sayısı azaldı
4. **Coolify Uyumlu**: Nixpacks build hataları yok

## 🗄️ Yeni Cache Tablosu

```sql
CREATE TABLE IF NOT EXISTS cache (
    id SERIAL PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT NOT NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔧 Cache Kullanımı

```php
// Cache set
Cache::set('key', $data, 3600); // 1 saat

// Cache get
$data = Cache::get('key');

// Cache delete
Cache::delete('key');

// Cache clear prefix
Cache::clearPrefix('events_');

// Clean expired cache (cron ile çalıştırın)
Cache::cleanExpired();
```

## ⚙️ Session Yönetimi

- **Session Handler**: File-based (PHP default)
- **Session Path**: `/tmp` (Coolify default)
- **Session Lifetime**: 1 saat
- **Cookie Lifetime**: Browser kapanana kadar
- **Security**: HTTPOnly, Secure (HTTPS), SameSite=Strict

## 🚀 Environment Variables

Artık `REDIS_URL` gerekli değil! Sadece:

```bash
DATABASE_URL=postgres://...
SESSION_SECRET=...
CSRF_SECRET=...
```

## 📝 Sonraki Adımlar

1. **Git push**
2. **Coolify'dan REDIS_URL'i silin** (opsiyonel, zarar vermez)
3. **Deploy**
4. **Database schema yükle** (cache tablosu eklenecek)

Artık site çok daha basit ve stabil çalışacak! 🎉

