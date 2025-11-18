-- Migration: Gallery (Galeri) Tablosunu Ekle
-- Bu dosyayı sadece bir kez çalıştırın!

-- Gallery (Galeri - Fotoğraf ve Videolar)
CREATE TABLE IF NOT EXISTS gallery (
    id SERIAL PRIMARY KEY,
    type VARCHAR(20) NOT NULL CHECK (type IN ('image', 'video')),
    media_path VARCHAR(255) NOT NULL,
    thumbnail_path VARCHAR(255),
    title VARCHAR(255),
    description TEXT,
    video_url VARCHAR(500),
    sort_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- İndeksler
CREATE INDEX idx_gallery_type ON gallery(type);
CREATE INDEX idx_gallery_sort ON gallery(sort_order);
CREATE INDEX idx_gallery_active ON gallery(is_active);

-- Trigger for updated_at
CREATE TRIGGER update_gallery_updated_at 
BEFORE UPDATE ON gallery 
FOR EACH ROW 
EXECUTE FUNCTION update_updated_at_column();

-- Açıklama:
-- type: 'image' (fotoğraf) veya 'video' (video)
-- media_path: Yüklenen dosyanın yolu
-- thumbnail_path: Video için küçük resim (thumbnail) yolu
-- title: Medya başlığı
-- description: Medya açıklaması
-- video_url: YouTube/Vimeo gibi harici video URL'si (opsiyonel)
-- sort_order: Görüntüleme sırası
-- is_active: Aktif/Pasif durumu

