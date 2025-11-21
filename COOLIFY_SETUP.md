# Coolify Deployment Setup

## Persistent Storage Yapılandırması

### 1. Persistent Storage Oluşturma

Coolify'da Application Settings > Storages kısmından:

```
Source: /app/storage/uploads
Destination: /app/storage/uploads
```

**Önemli:** Bu storage'ı oluşturmadan deploy etmeyin!

### 2. Environment Variables

Application Settings > Environment Variables:

```bash
# Upload Path (Persistent Storage)
UPLOAD_PATH=/app/storage/uploads

# Database
DATABASE_URL=postgresql://user:pass@host:5432/dbname
DATABASE_HOST=postgres_host
DATABASE_PORT=5432
DATABASE_NAME=dbname
DATABASE_USER=user
DATABASE_PASSWORD=password

# Redis
REDIS_HOST=redis_host
REDIS_PORT=6379
REDIS_PASSWORD=redis_password

# Security
ADMIN_PATH=your-secret-admin-path
TURNSTILE_SITE_KEY=your_turnstile_key
TURNSTILE_SECRET_KEY=your_turnstile_secret

# Optional
DEBUG_MODE=false
MAX_PDF_SIZE=5242880
MAX_IMAGE_SIZE=5242880
CACHE_TTL_SETTINGS=3600
```

### 3. Deploy Sonrası

Deploy tamamlandıktan sonra:

1. Test endpoint'i kontrol edin:
   ```
   https://your-domain.com/test-upload.php
   ```

2. Upload dizinlerinin oluştuğunu doğrulayın

3. Test başvurusu yapın

### 4. Dosya Yolları

**Upload Yolu:** `/app/storage/uploads` (persistent)
**Public Yolu:** `/app/public/uploads` (geçici, deploy'da silinir)

**Önemli:** Tüm dosyalar `/app/storage/uploads` altına kaydedilir. Bu dizin persistent storage olarak yapılandırılmalıdır.

### 5. Nginx/Caddy Yapılandırması (Opsiyonel)

Coolify otomatik olarak Caddy kullanır, ancak ek yapılandırma gerekirse:

```caddy
# Uploads için özel route
/uploads/* {
    root /app/storage/uploads
}
```

### 6. Sorun Giderme

**Dosyalar görünmüyor mu?**

1. Persistent storage doğru yapılandırıldı mı?
2. UPLOAD_PATH environment variable set edildi mi?
3. `/test-upload.php` çalıştırarak kontrol edin
4. Container içinde dizin izinlerini kontrol edin:
   ```bash
   ls -la /app/storage/uploads
   ```

**Hata mesajları:**
- "Directory not found" → Persistent storage oluşturulmamış
- "Permission denied" → Dizin izinleri yanlış (chmod 777 gerekli)
- "File not found" → UPLOAD_PATH yanlış

### 7. Komutlar

```bash
# Container'a bağlan
docker exec -it container_name bash

# Upload dizinini kontrol et
ls -la /app/storage/uploads

# İzinleri düzelt
chmod -R 777 /app/storage/uploads

# PHP test script çalıştır
php /app/create-uploads.php
```

### 8. Git İçin

```bash
# Geliştirme ortamında uploads dizinini oluştur
mkdir -p public/uploads
chmod 777 public/uploads

# .gitignore'da uploads içeriği ignore edilir
public/uploads/*
!public/uploads/.gitkeep
```

## Deployment Checklist

- [ ] Persistent storage oluşturuldu: `/app/storage/uploads`
- [ ] Environment variables set edildi
- [ ] Database bağlantısı test edildi
- [ ] Redis bağlantısı test edildi
- [ ] Deploy yapıldı
- [ ] `/test-upload.php` kontrol edildi
- [ ] Test başvurusu yapıldı
- [ ] PDF indirme test edildi
- [ ] Fotoğraf yükleme test edildi

## Notlar

- Persistent storage olmadan deploy edilirse, her deploy'da dosyalar silinir
- UPLOAD_PATH environment variable **mutlaka** set edilmelidir
- İzinler 777 olmalı (Coolify container'ında web server farklı user ile çalışır)
- Veritabanı migration'ları deploy öncesi çalıştırılmalıdır

