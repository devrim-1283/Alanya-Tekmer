# 🎯 SSS (Sıkça Sorulan Sorular) Modülü - Değişiklik Özeti

## 📅 Tarih
18 Kasım 2025

## 🎨 Yapılan İşlem
Hakkımızda sayfasındaki SSS (Sıkça Sorulan Sorular) bölümü, ana sayfaya etkinlik duyurusunun altına eklendi. Her iki sayfa da aynı veritabanı tablosundan SSS'leri dinamik olarak çekiyor.

---

## 📦 Değişen Dosyalar

### 1. Veritabanı (SQL)
- ✅ **`sql/schema.sql`** - Ana şemaya `faq` tablosu eklendi
- ✅ **`sql/add_faq_table.sql`** - Migration dosyası (mevcut veritabanına eklemek için)
- ✅ **`sql/README_FAQ.md`** - Detaylı kurulum rehberi

### 2. Sayfa Dosyaları
- ✅ **`src/pages/home.php`** - SSS bölümü veritabanından dinamik olarak çekiliyor
- ✅ **`src/pages/about.php`** - SSS bölümü veritabanından dinamik olarak çekiliyor

### 3. Admin Panel
- ✅ **`src/admin/faq.php`** - Yeni SSS yönetim sayfası
- ✅ **`src/admin/header.php`** - Menüye "SSS Yönetimi" linki eklendi
- ✅ **`src/admin/index.php`** - Routing güncellendi

---

## 🗄️ Yeni Veritabanı Tablosu: `faq`

```sql
CREATE TABLE faq (
    id SERIAL PRIMARY KEY,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Varsayılan Veriler:** 4 adet SSS otomatik olarak ekleniyor:
1. TEKMER Nedir?
2. TEKMER'e Nasıl Başvurabilirim?
3. Başvuru Sonrası Süreç Nasıl İşlemektedir?
4. Başvurular Sadece Online Mı Yapılmaktadır?

---

## 🎯 Özellikler

### Ana Sayfa (home.php)
- ✅ SSS bölümü etkinlik duyurusunun altında görünüyor
- ✅ Veritabanından dinamik olarak çekiliyor
- ✅ Sadece aktif (`is_active = true`) SSS'ler gösteriliyor
- ✅ `sort_order` değerine göre sıralanıyor

### Hakkımızda Sayfası (about.php)
- ✅ SSS bölümü misyon bölümünün altında görünüyor
- ✅ Veritabanından dinamik olarak çekiliyor
- ✅ Ana sayfa ile aynı verileri kullanıyor

### Admin Panel (admin/faq.php)
- ➕ **Yeni SSS Ekle** - Modal ile hızlı ekleme
- ✏️ **SSS Düzenle** - Tüm alanları düzenleyebilme
- 🗑️ **SSS Sil** - Onay ile silme
- 🔄 **Durum Değiştir** - Aktif/Pasif yapma (toggle)
- 📊 **Liste Görünümü** - Tüm SSS'leri tablo halinde görüntüleme
- 🎨 **HTML Desteği** - Zengin içerik için HTML kullanabilme

---

## 🚀 Kurulum Talimatları

### Adım 1: Veritabanı Migration
```bash
psql -U your_username -d your_database -f sql/add_faq_table.sql
```

### Adım 2: Sayfaları Test Et
1. Ana Sayfa: `https://yoursite.com/`
2. Hakkımızda: `https://yoursite.com/hakkimizda`

### Adım 3: Admin Paneli
1. Admin paneline giriş yap
2. Sol menüden "SSS Yönetimi" seç
3. SSS'leri yönet

---

## 💡 Kullanım Örnekleri

### Admin Panelinden SSS Ekleme
1. Admin > SSS Yönetimi
2. "Yeni SSS Ekle" butonuna tıkla
3. Soru ve cevabı gir (HTML kullanabilirsin)
4. Sıra numarasını belirle
5. Kaydet

### HTML Kullanımı
```html
<p>Normal paragraf</p>
<strong>Kalın yazı</strong>
<ul>
  <li>Liste öğesi</li>
</ul>
<a href="/basvuru">Link</a>
```

---

## 📊 Veritabanı Bağlantısı

Her iki sayfa da şu sorguyu kullanıyor:

```php
$db = Database::getInstance();
$faqs = $db->fetchAll(
    'SELECT * FROM faq WHERE is_active = ? ORDER BY sort_order ASC',
    [true]
);
```

**Güvenlik:**
- Sorular için: `Security::escape()` kullanılıyor (XSS koruması)
- Cevaplar için: HTML olduğu için direkt yazdırılıyor (güvenilir admin verisi)

---

## 🎨 Görünüm

### Ana Sayfa Konumu
```
Hero Section
↓
İstatistikler
↓
Hizmetler
↓
Etkinlikler & Duyurular
↓
📌 SSS (YENİ!) ← Buraya eklendi
↓
CTA (Başvuru)
```

### Hakkımızda Konumu
```
Sayfa Header
↓
Biz Kimiz?
↓
Misyonumuz
↓
📌 SSS (Dinamik) ← Veritabanından çekiliyor
↓
CTA (Başvuru)
```

---

## ✅ Test Listesi

- [ ] Veritabanı migration'ı çalıştırıldı
- [ ] Ana sayfada SSS görünüyor
- [ ] Hakkımızda sayfasında SSS görünüyor
- [ ] Admin panelinde SSS yönetimi sayfası açılıyor
- [ ] Yeni SSS eklenebiliyor
- [ ] Mevcut SSS düzenlenebiliyor
- [ ] SSS silinebiliyor
- [ ] SSS aktif/pasif yapılabiliyor
- [ ] Sıralama düzgün çalışıyor
- [ ] HTML içerik düzgün görünüyor

---

## 🔒 Güvenlik

- ✅ Admin authentication kontrolü var
- ✅ SQL injection korumalı (prepared statements)
- ✅ XSS korumalı (Security::escape)
- ✅ CSRF koruması (admin session)

---

## 📝 Notlar

1. **Aynı Veri Kaynağı:** Ana sayfa ve Hakkımızda sayfası aynı veritabanı tablosunu kullanıyor. Bir değişiklik her iki sayfada da görünür.

2. **HTML Desteği:** SSS cevaplarında HTML kullanabilirsiniz. Bu sayede zengin içerik oluşturabilirsiniz.

3. **Sıralama:** `sort_order` değerini kullanarak SSS'lerin görünüm sırasını kontrol edebilirsiniz.

4. **Aktif/Pasif:** Pasif yapılan SSS'ler sitelerde görünmez ama admin panelinde kalır.

---

## 🎉 Sonuç

✅ SSS modülü başarıyla eklendi!
✅ Her iki sayfa da aynı veritabanından çekiyor!
✅ Admin panelinden kolayca yönetilebiliyor!
✅ HTML desteği var!
✅ Güvenlik önlemleri alındı!

**İyi Çalışmalar! 🚀**

