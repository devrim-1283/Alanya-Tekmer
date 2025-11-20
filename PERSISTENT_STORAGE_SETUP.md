# 📦 Persistent Storage Kurulumu - Coolify

## 🎯 Amaç

Container yeniden başlatıldığında veya yeniden deploy edildiğinde yüklenen dosyaların **kaybolmamasını** sağlamak.

## 📁 Persistent Olması Gereken Dizinler

### 1. `/app/uploads` - **ÇOK ÖNEMLİ!**
- Kullanıcı yüklediği PDF'ler (başvurular)
- Firma logoları
- Ekip fotoğrafları
- Etkinlik fotoğrafları
- Galeri medyaları

### 2. `/app/logs` - **Opsiyonel**
- Uygulama logları
- Error logları
- Access logları

## 🚀 Coolify'da Kurulum

### Adım 1: Storage Ekleme

1. Coolify dashboard'a gidin
2. Application'ınızı seçin
3. **Storage** sekmesine gidin
4. **Add Storage** butonuna tıklayın

### Adım 2: Uploads Storage Ayarları

```
Name: uploads-storage
Source Path: (boş bırakın, Coolify otomatik oluşturur)
Destination Path: /app/uploads
Mount Type: Volume (önerilen) veya Bind Mount
Is Directory: ✅ Yes
```

**Önemli:**
- `Destination Path` mutlaka `/app/uploads` olmalı
- **Kaydet** butonuna tıklayın

### Adım 3: Logs Storage Ayarları (Opsiyonel)

```
Name: logs-storage
Source Path: (boş bırakın)
Destination Path: /app/logs
Mount Type: Volume
Is Directory: ✅ Yes
```

### Adım 4: Environment Variables

Coolify → Application → Environment Variables:

```bash
# Upload Path - Persistent storage kullanıyorsak
UPLOAD_PATH=/app/uploads
```

### Adım 5: Redeploy

Storage ekledikten sonra **mutlaka redeploy** edin:

```bash
Coolify → Application → Deploy
```

## 🔍 Doğrulama

### Test 1: Dosya Yükleme

1. Admin panele giriş yapın
2. Galeri'ye bir resim yükleyin
3. Container'ı yeniden başlatın:
   ```bash
   Coolify → Application → Restart
   ```
4. Yüklediğiniz resmin hala görünüyor olması gerekir

### Test 2: SSH ile Kontrol

Coolify terminal'den:

```bash
# Uploads dizinine gir
cd /app/uploads

# İçeriği listele
ls -la

# Dosya sayısını kontrol et
ls -1 | wc -l

# Permissions'ı kontrol et (777 olmalı)
ls -ld /app/uploads
# Çıktı: drwxrwxrwx ... /app/uploads
```

### Test 3: Dosya Yazma Testi

```bash
# Test dosyası oluştur
touch /app/uploads/test.txt

# Yazılabilir mi kontrol et
echo "test" > /app/uploads/test.txt

# Oku
cat /app/uploads/test.txt

# Sil
rm /app/uploads/test.txt
```

## 🐛 Sorun Giderme

### Sorun 1: "Permission denied" Hatası

**Çözüm:**

```bash
# SSH ile bağlan
cd /app
chmod -R 777 uploads
chown -R www-data:www-data uploads
```

Veya `nixpacks.toml` dosyasında zaten var:

```toml
[phases.install]
cmds = [
    "mkdir -p /app/uploads /app/logs",
    "chmod -R 777 /app/uploads",
    "chmod -R 755 /app/logs"
]
```

### Sorun 2: Dosyalar Hala Kayboluyor

**Kontrol listesi:**

1. ✅ Storage eklendi mi?
   ```bash
   Coolify → Application → Storage → uploads-storage var mı?
   ```

2. ✅ Destination path doğru mu?
   ```bash
   Destination: /app/uploads (başında / olmalı)
   ```

3. ✅ Redeploy yapıldı mı?
   ```bash
   Storage değişikliğinden sonra mutlaka redeploy!
   ```

4. ✅ Environment variable set edildi mi?
   ```bash
   UPLOAD_PATH=/app/uploads
   ```

### Sorun 3: Dosyalar Görünmüyor

**Neden:** Eski deployment'tan kalan dosyalar `public/uploads`'ta

**Çözüm:** Eski dosyaları yeni konuma taşı

```bash
# SSH ile
cd /app/public/uploads
cp -r * /app/uploads/

# Veya manual olarak Coolify file manager'dan
```

## 📊 Storage Kapasitesi

### Mevcut Kullanım Kontrolü

```bash
# Uploads dizini boyutu
du -sh /app/uploads

# Detaylı liste
du -h /app/uploads/* | sort -h

# Toplam dosya sayısı
find /app/uploads -type f | wc -l
```

### Büyük Dosyaları Bulma

```bash
# 5MB'dan büyük dosyalar
find /app/uploads -type f -size +5M -exec ls -lh {} \;

# En büyük 10 dosya
find /app/uploads -type f -exec du -h {} \; | sort -rh | head -n 10
```

## 🔐 Güvenlik

### Doğru Permissions

```bash
# Dizin permissions
/app/uploads: 777 (drwxrwxrwx)
/app/logs: 755 (drwxr-xr-x)

# Dosya permissions (otomatik)
Yüklenen dosyalar: 644 (rw-r--r--)
```

### .htaccess Koruması (Apache)

Eğer Apache kullanıyorsanız:

```apache
# /app/uploads/.htaccess
<Files *.php>
    deny from all
</Files>

# Sadece belirli dosya tiplerini izin ver
<FilesMatch "\.(jpg|jpeg|png|gif|pdf)$">
    Order allow,deny
    Allow from all
</FilesMatch>
```

### nginx Koruması

nginx kullanıyorsanız:

```nginx
location /uploads/ {
    location ~ \.php$ {
        deny all;
    }
}
```

## 📋 Backup Stratejisi

### Manuel Backup

```bash
# Tüm uploads'ı tar.gz'e sıkıştır
cd /app
tar -czf uploads-backup-$(date +%Y%m%d).tar.gz uploads/

# Download et (Coolify file manager)
```

### Otomatik Backup (Coolify)

Coolify → Application → Backups:
- Enable scheduled backups
- Storage'ı backup'a dahil et

## 📈 Monitoring

### Disk Kullanımı İzleme

```bash
# Coolify dashboard'da görüntülenecek
# Veya SSH ile:
df -h /app/uploads
```

### Log Monitoring

```bash
# Upload errors
tail -f /app/logs/upload.log

# PHP errors
tail -f /app/logs/php_errors.log
```

## ✅ Checklist

Deployment öncesi kontrol listesi:

- [ ] `/app/uploads` storage Coolify'a eklendi
- [ ] Destination path: `/app/uploads` (doğru)
- [ ] Environment variable: `UPLOAD_PATH=/app/uploads`
- [ ] Permissions: 777
- [ ] Redeploy yapıldı
- [ ] Test dosyası yüklendi
- [ ] Container restart test edildi
- [ ] Dosyalar hala mevcut

## 🎯 Sonuç

Bu setup'tan sonra:

✅ Yüklenen dosyalar **asla kaybolmaz**  
✅ Container restart → Dosyalar korunur  
✅ Redeploy → Dosyalar korunur  
✅ Backup'lar daha kolay  
✅ Multiple replicas (gelecekte) → Shared storage  

---

**Not:** Bu yapılandırma production için **mutlaka** gereklidir!

