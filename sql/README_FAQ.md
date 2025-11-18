# SSS (Sıkça Sorulan Sorular) Modülü - Kurulum Rehberi

## 📋 Özet

Bu güncelleme ile **Sıkça Sorulan Sorular (SSS/FAQ)** bölümü veritabanına taşındı. Artık hem **Ana Sayfa** hem de **Hakkımızda** sayfasındaki SSS'ler aynı veritabanı tablosundan dinamik olarak çekiliyor.

## 🎯 Yapılan Değişiklikler

### 1. Veritabanı Değişiklikleri
- Yeni `faq` tablosu oluşturuldu
- Varsayılan SSS verileri eklendi
- Trigger'lar kuruldu (otomatik updated_at güncelleme)

### 2. Sayfa Güncellemeleri
- ✅ `src/pages/home.php` - SSS bölümü dinamik hale getirildi
- ✅ `src/pages/about.php` - SSS bölümü dinamik hale getirildi
- ✅ Her iki sayfa da aynı veritabanı tablosundan SSS çekiyor

### 3. Admin Panel
- ✅ Yeni admin sayfası: `src/admin/faq.php`
- ✅ Admin menüsüne "SSS Yönetimi" eklendi
- ✅ SSS ekleme, düzenleme, silme ve durum değiştirme özellikleri

## 🚀 Kurulum Adımları

### Adım 1: Veritabanı Migration'ı Çalıştırın

PostgreSQL veritabanınıza bağlanın ve şu dosyayı çalıştırın:

```bash
psql -U your_username -d your_database -f sql/add_faq_table.sql
```

**VEYA** manuel olarak SQL komutlarını çalıştırın:

```sql
-- FAQ Tablosunu Oluştur
CREATE TABLE IF NOT EXISTS faq (
    id SERIAL PRIMARY KEY,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- İndeksler
CREATE INDEX idx_faq_sort ON faq(sort_order);
CREATE INDEX idx_faq_active ON faq(is_active);

-- Trigger
CREATE TRIGGER update_faq_updated_at 
BEFORE UPDATE ON faq 
FOR EACH ROW 
EXECUTE FUNCTION update_updated_at_column();

-- Varsayılan veriler (4 adet SSS)
INSERT INTO faq (question, answer, sort_order, is_active) VALUES
('TEKMER Nedir?', '<p>TEKMER; girişimcilere ve işletmelere...</p>', 1, true),
('TEKMER''e Nasıl Başvurabilirim?', '<p>Web sitemiz üzerindeki...</p>', 2, true),
('Başvuru Sonrası Süreç Nasıl İşlemektedir?', '<p>Başvuru yaptıktan sonra...</p>', 3, true),
('Başvurular Sadece Online Mı Yapılmaktadır?', '<p>Evet, başvurular sadece online...</p>', 4, true);
```

### Adım 2: Sayfaları Test Edin

1. **Ana Sayfayı** ziyaret edin: `https://yoursite.com/`
   - Etkinlikler bölümünün altında SSS görmelisiniz
   
2. **Hakkımızda sayfasını** ziyaret edin: `https://yoursite.com/hakkimizda`
   - Misyon bölümünün altında SSS görmelisiniz

### Adım 3: Admin Panelinden SSS Yönetimi

1. Admin paneline giriş yapın: `https://yoursite.com/[ADMIN_PATH]/dashboard`
2. Sol menüden **"SSS Yönetimi"** seçeneğine tıklayın
3. Burada yapabilecekleriniz:
   - ➕ Yeni SSS ekle
   - ✏️ Mevcut SSS'leri düzenle
   - 🔄 SSS sırasını değiştir
   - 👁️ SSS'leri aktif/pasif yap
   - 🗑️ SSS'leri sil

## 📊 Veritabanı Yapısı

### `faq` Tablosu

| Sütun | Tip | Açıklama |
|-------|-----|----------|
| `id` | SERIAL | Birincil anahtar |
| `question` | VARCHAR(500) | Soru metni |
| `answer` | TEXT | Cevap metni (HTML destekli) |
| `sort_order` | INTEGER | Sıralama numarası (küçükten büyüğe) |
| `is_active` | BOOLEAN | Aktif/Pasif durumu |
| `created_at` | TIMESTAMP | Oluşturulma tarihi |
| `updated_at` | TIMESTAMP | Güncellenme tarihi (otomatik) |

## 💡 Kullanım İpuçları

### HTML Desteği

SSS cevaplarında HTML kullanabilirsiniz:

```html
<p>Paragraf metni</p>
<strong>Kalın yazı</strong>
<ul>
  <li>Liste öğesi 1</li>
  <li>Liste öğesi 2</li>
</ul>
<a href="/basvuru">Link metni</a>
```

### Sıralama

- `sort_order` değerini kullanarak SSS'lerin görünüm sırasını kontrol edebilirsiniz
- Küçük sayılar önce görünür (0, 1, 2, 3...)
- Aynı `sort_order` değerine sahip SSS'ler `id`'ye göre sıralanır

### Aktif/Pasif Durum

- Pasif yapılan SSS'ler sitelerde görünmez
- Admin panelinde görünür kalır
- İstediğiniz zaman tekrar aktif yapabilirsiniz

## 🔍 Sorun Giderme

### SSS'ler Görünmüyorsa

1. **Veritabanı bağlantısını kontrol edin**
   ```php
   // src/config/db.php dosyası doğru yapılandırılmış mı?
   ```

2. **Tablo oluşturuldu mu kontrol edin**
   ```sql
   SELECT * FROM faq;
   ```

3. **Aktif SSS var mı kontrol edin**
   ```sql
   SELECT * FROM faq WHERE is_active = true;
   ```

4. **PHP hata loglarını kontrol edin**
   ```bash
   tail -f /var/log/php-error.log
   ```

### Admin Sayfası Açılmıyorsa

1. Dosya yolunu kontrol edin: `src/admin/faq.php`
2. Routing ayarlarını kontrol edin: `src/admin/index.php`
3. Admin giriş yaptığınızdan emin olun

## 📝 Örnek SQL Sorguları

### Yeni SSS Ekle
```sql
INSERT INTO faq (question, answer, sort_order, is_active) 
VALUES ('Yeni Soru?', '<p>Cevap metni</p>', 5, true);
```

### SSS Güncelle
```sql
UPDATE faq 
SET question = 'Güncellenmiş Soru?', 
    answer = '<p>Güncellenmiş cevap</p>' 
WHERE id = 1;
```

### SSS Sil
```sql
DELETE FROM faq WHERE id = 1;
```

### Tüm Aktif SSS'leri Listele
```sql
SELECT * FROM faq WHERE is_active = true ORDER BY sort_order ASC;
```

## ✨ Özellikler

- ✅ Veritabanı tabanlı SSS yönetimi
- ✅ Ana sayfa ve Hakkımızda sayfasında aynı veri
- ✅ Admin panelinden kolay yönetim
- ✅ HTML desteği (zengin içerik)
- ✅ Sıralama özelliği
- ✅ Aktif/Pasif durumu
- ✅ Otomatik tarih güncelleme
- ✅ Güvenli HTML kaçırma (XSS koruması)

## 🎉 Başarılı Kurulum Sonrası

Kurulum başarılı olduysa:

1. Ana sayfada etkinliklerin altında SSS bölümünü göreceksiniz
2. Hakkımızda sayfasında SSS bölümünü göreceksiniz
3. Admin panelinde SSS'leri yönetebileceksiniz
4. Değişiklikler anında her iki sayfada da görünecek

**Teşekkürler! 🚀**

