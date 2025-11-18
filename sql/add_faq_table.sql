-- Migration: FAQ (Sıkça Sorulan Sorular) Tablosunu Ekle
-- Bu dosyayı sadece bir kez çalıştırın!

-- FAQ (Sıkça Sorulan Sorular)
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

-- Trigger for updated_at
CREATE TRIGGER update_faq_updated_at 
BEFORE UPDATE ON faq 
FOR EACH ROW 
EXECUTE FUNCTION update_updated_at_column();

-- Varsayılan SSS verilerini ekle
INSERT INTO faq (question, answer, sort_order, is_active) VALUES
('TEKMER Nedir?', '<p>TEKMER; girişimcilere ve işletmelere ön inkübasyon, inkübasyon, inkübasyon sonrası süreçlerde; iş geliştirme, mali kaynaklara erişim, yönetim, danışmanlık, mentörlük, ofis ve ağlara katılım gibi hizmetlerin sağlandığı yapılardır.</p><p><strong>Bu süreçler;</strong></p><ul><li><strong>Ön inkübasyon:</strong> İşletmesini kurmamış girişimcilere yönelik tek başına veya bir grup halinde eğitim, danışmanlık, mentörlük, proje fikri doğrulama ve iş geliştirme amacıyla verilen hizmetleri,</li><li><strong>İnkübasyon:</strong> Girişimci veya işletmelerin geliştirilmesi ve proje/iş fikirlerinin ticarileşmesini sağlamak amacıyla sunulan eğitim, teknik danışmanlık, mentörlük, koçluk, ağlara erişim, yatırımcı bulma, işlik/çalışma alanı ve ortak kullanıma yönelik alan/makine/yazılım hizmetleri ile tanıtıma yönelik hizmetleri ve hızlandırıcı programını,</li><li><strong>İnkübasyon sonrası:</strong> İnkübasyon sürecini tamamlamış/tamamlamak üzere olan işletmelere sunulan pazar stratejisi geliştirme, yönetim, büyüme stratejisi, fon bulma, ağlara erişim hizmetleri ile bu işletmeler tarafından ön inkübasyon/inkübasyon sürecindekilere sunulan tecrübe paylaşımı, mentörlük, koçluk, fon sağlama benzeri hizmetleri ve hızlandırıcı programlarını kapsar.</li></ul>', 1, true),
('TEKMER''e Nasıl Başvurabilirim?', '<p>Web sitemiz üzerindeki <a href="/basvuru">online başvuru</a> butonuna tıkladıktan sonra karşınıza çıkan ilgili formu doldurarak başvurunuzu gerçekleştirebilirsiniz.</p>', 2, true),
('Başvuru Sonrası Süreç Nasıl İşlemektedir?', '<p>Başvuru yaptıktan sonra İcra Kurulumuz projeleri detaylı incelemektedir. Komite kararı sonucu başarılı bulunan girişimci adayları ve firmalar mülakata davet edilerek seçilenler ilgili programa katılmaya ve sunulacak tüm imkânlardan faydalanmaya hak kazanır.</p>', 3, true),
('Başvurular Sadece Online Mı Yapılmaktadır?', '<p>Evet, başvurular sadece online yapılmaktadır.</p>', 4, true);

