# Admin Panel Giriş Sorunu - Çözüm Kılavuzu

## ✅ Yapılan Düzeltmeler

### 1. CSP (Content Security Policy) İyileştirmesi
**Sorun**: Private Access Token ve script-src hatası  
**Çözüm**: Güvenlik başlıkları güncellendi (`src/config/security.php`)

Değişiklikler:
- ✅ `unsafe-eval` eklendi (Cloudflare Turnstile için gerekli)
- ✅ `*.cloudflare.com` wildcard domain desteği
- ✅ Google Maps iframe desteği
- ✅ Headers already sent kontrolü
- ✅ Permissions-Policy eklendi

### 2. Turnstile CAPTCHA İyileştirmeleri
**Sorun**: CAPTCHA yüklenemediğinde giriş yapılamıyor  
**Çözüm**: Fallback mekanizması eklendi

Değişiklikler:
- ✅ DEBUG_MODE'da CAPTCHA bypass
- ✅ `BYPASS_TURNSTILE` environment variable desteği
- ✅ CAPTCHA yükleme hatası için JavaScript handler
- ✅ 5 saniye sonra CAPTCHA kontrolü
- ✅ Yüklenmezse uyarı mesajı gösterme

### 3. Gelişmiş Hata Ayıklama
**Sorun**: Hatanın sebebi belli değil  
**Çözüm**: Detaylı hata logları ve mesajları

Değişiklikler:
- ✅ DEBUG_MODE'da detaylı hata mesajları
- ✅ Stack trace logging
- ✅ Kullanıcı bulunamadı/şifre hatalı ayrımı
- ✅ Activity log hatasında giriş başarısız olmuyor
- ✅ Console log'ları

### 4. Kullanıcı Deneyimi İyileştirmeleri
- ✅ Form validasyonu (boş alan kontrolü)
- ✅ Hata mesajları 10 saniye sonra otomatik kapanıyor
- ✅ Daha açıklayıcı hata mesajları

## 🔧 Nasıl Test Edilir?

### Adım 1: Environment Variables Kontrol
`.env` dosyanızda şunları kontrol edin:

```env
# Debug mode (geliştirme için)
DEBUG_MODE=true

# CAPTCHA bypass (geliştirme için)
BYPASS_TURNSTILE=true

# Cloudflare Turnstile keys
TURNSTILE_SITE_KEY=your_site_key
TURNSTILE_SECRET_KEY=your_secret_key

# Admin path
ADMIN_PATH=your_admin_path
```

### Adım 2: Veritabanı Kontrol
Admin kullanıcısının olduğundan emin olun:

```sql
SELECT * FROM admin_users WHERE is_active = true;
```

Eğer yoksa, SQL scriptini çalıştırın:
```bash
psql -U your_username -d your_database -f sql/update_admin_password.sql
```

### Adım 3: Test Girişi
1. Admin panel URL'sine gidin: `https://yoursite.com/[ADMIN_PATH]`
2. Kullanıcı adı ve şifreyi girin
3. CAPTCHA yüklenmediyse uyarı mesajını göreceksiniz
4. "Giriş Yap" butonuna tıklayın

### Adım 4: Hata Durumunda

**Browser Console'u Açın** (F12)
Şu hataları kontrol edin:
- CSP violations
- Turnstile loading errors
- Network errors

**Server Logs'u Kontrol Edin**
```bash
tail -f logs/error.log
# veya
tail -f /var/log/php-error.log
```

## 🐛 Sık Karşılaşılan Sorunlar ve Çözümleri

### Sorun 1: "Captcha doğrulaması başarısız"
**Çözüm**:
```env
# .env dosyasına ekleyin
BYPASS_TURNSTILE=true
DEBUG_MODE=true
```

### Sorun 2: "Kullanıcı adı veya şifre hatalı"
**Çözüm**:
1. Şifreyi sıfırlayın:
```sql
-- sql/update_admin_password.sql dosyasını kullanın
-- veya
UPDATE admin_users 
SET password_hash = '$2y$12$...' 
WHERE username = 'admin';
```

2. Kullanıcının aktif olduğunu kontrol edin:
```sql
UPDATE admin_users SET is_active = true WHERE username = 'admin';
```

### Sorun 3: "Giriş işlemi sırasında bir hata oluştu"
**Çözüm**:
1. DEBUG_MODE açın ve detaylı hatayı görün
2. Veritabanı bağlantısını kontrol edin
3. `activity_log` tablosunun olduğundan emin olun:
```sql
-- Eğer yoksa oluşturun
CREATE TABLE IF NOT EXISTS activity_log (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES admin_users(id),
    action VARCHAR(100),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Sorun 4: CSP Hataları
**Çözüm**: Tarayıcıyı yenileyin veya cache'i temizleyin
```
Ctrl + Shift + Delete (Chrome/Firefox)
```

## 📝 Üretim (Production) Ortamı İçin

Üretim ortamında şunları değiştirin:

```env
# .env production settings
DEBUG_MODE=false
BYPASS_TURNSTILE=false

# Güvenli session
SESSION_SECRET=your_random_secret_here
CSRF_SECRET=another_random_secret_here

# HTTPS zorla
FORCE_HTTPS=true
```

## 🔐 Güvenlik Notları

1. **DEBUG_MODE** üretimde KAPALI olmalı
2. **BYPASS_TURNSTILE** üretimde KAPALI olmalı
3. Güçlü şifreler kullanın (en az 12 karakter)
4. Rate limiting aktif (5 dakikada 10 deneme)
5. Tüm girişler loglanıyor

## 📞 Hala Sorun Yaşıyorsanız

1. Server log'larını kontrol edin
2. Browser console'u kontrol edin
3. Network tab'ında failed request'leri görün
4. `.env` dosyasını kontrol edin
5. Veritabanı bağlantısını test edin

## ✨ Yeni Özellikler

- ✅ Gelişmiş CSP politikası
- ✅ Private Access Token desteği
- ✅ CAPTCHA bypass modu
- ✅ Detaylı hata ayıklama
- ✅ Otomatik hata mesajı gizleme
- ✅ Form validasyonu
- ✅ Activity log hata toleransı

---

**Not**: Değişiklikleri uyguladıktan sonra sunucuyu yeniden başlatmanız gerekebilir.

```bash
# PHP-FPM restart
sudo systemctl restart php-fpm
# veya
sudo systemctl restart php8.2-fpm

# Apache restart
sudo systemctl restart apache2

# Nginx restart
sudo systemctl restart nginx
```

