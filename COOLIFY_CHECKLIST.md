# 🚀 Coolify Deployment Checklist - Alanya TEKMER

## ✅ Yapılması Gerekenler

### 1. **Environment Variables (EN ÖNEMLİ!)**

Coolify → Application → Environment Variables bölümüne şunları ekleyin:

```bash
# Application
APP_ENV=production
DEBUG_MODE=false
BASE_URL=https://test-sms-link.com.tr

# Upload Path
UPLOAD_PATH=/app/uploads

# Database (Coolify PostgreSQL service'den alın)
DATABASE_URL=postgres://user:pass@postgres-service:5432/dbname

# Redis (Coolify Redis service'den alın)
REDIS_URL=redis://default:pass@redis-service:6379/0

# Security
SESSION_SECRET=sRoIWvBV5rZGr1hPgkWPo6leFBKoY3IUNvZ3oGUKCyt0owhMSLOaHyikeRIYmzpuVzAA09aZ4RU74bwco52nctOoMeDfhf819U64
CSRF_SECRET=4Hy51u0zgjZU9ec7tiuUs93ykCvAtbHs1Jaok66JhI3kuE3sNWcoS2MMsM3GYbhKg1tQEm4kOJHIRkQkWjsP9uIZ2J9k9TO33R9O

# Cloudflare Turnstile
TURNSTILE_SITE_KEY=0x4AAAAAACBPmcvE_UmW1aqN
TURNSTILE_SECRET_KEY=0x4AAAAAACBPmfo6JxA9KgEnv8te59PujtU

# Admin
ADMIN_PATH=ee9Y0hc8rx7yTACaaoXhSh9cOOhrVB7aXCfEzhaC3XAIrsgoi1

# Upload Limits
MAX_PDF_SIZE=5242880
MAX_IMAGE_SIZE=5242880
MAX_IMAGES_PER_EVENT=10

# Rate Limiting
RATE_LIMIT_GENERAL=60
RATE_LIMIT_APPLICATION=3
RATE_LIMIT_ADMIN=10

# Cache TTL
CACHE_TTL_STATIC=86400
CACHE_TTL_DYNAMIC=900
CACHE_TTL_SETTINGS=3600
```

### 2. **Health Check Ayarları** ⚠️ SORUN BURADA!

Coolify → Application → Health Check:

```
✅ Health Check Enabled: YES
✅ Health Check URL: /health
✅ Health Check Method: GET
✅ Health Check Interval: 30
✅ Health Check Timeout: 10
✅ Health Check Retries: 3
✅ Health Check Start Period: 30
```

**ÖNEMLİ:** Health Check URL **mutlaka** `/health` olmalı!

### 3. **Storage (Persistent Volume)**

Coolify → Application → Storage → Add Storage:

```
Name: uploads
Source: (Coolify auto)
Destination: /app/uploads
```

### 4. **Port**

Coolify → Application → Ports:

```
Port: 3000 (otomatik algılanır)
```

### 5. **Database Schema**

İlk deployment başarılı olduktan sonra:

```bash
# Coolify terminal veya local'den
psql $DATABASE_URL < sql/schema.sql
```

### 6. **Domain**

Coolify → Application → Domains:

```
Domain: test-sms-link.com.tr
```

## 🔍 Troubleshooting

### "No available server" Hatası

**Neden:** Health check başarısız

**Çözüm:**
1. Health Check URL'in `/health` olduğundan emin olun
2. Environment variables'ın set olduğunu kontrol edin (özellikle DATABASE_URL)
3. Logs'a bakın: Database bağlantı hatası var mı?

### Test Komutları

```bash
# Health check test
curl https://test-sms-link.com.tr/health

# Response olmalı:
# {"status":"healthy","timestamp":1700220000}
```

## 📝 Deployment Sonrası

1. ✅ Database schema yükleyin
2. ✅ Admin paneline giriş yapın: `https://test-sms-link.com.tr/{ADMIN_PATH}`
3. ✅ Default şifreyi değiştirin (admin / Admin123!@#)
4. ✅ Site ayarlarını güncelleyin
5. ✅ Test başvuru yapın

## 🎯 Şu Anki Durum

```
✅ Build başarılı
✅ PHP server çalışıyor (port 3000)
✅ /health endpoint hazır
❌ Health check başarısız - URL yanlış olabilir
❌ Environment variables eksik olabilir
```

## 💡 Hızlı Çözüm

1. Coolify'da Health Check URL'i `/health` yapın
2. Eğer hala çalışmazsa, geçici olarak health check'i disable edin
3. Logs'da hata mesajlarını kontrol edin

