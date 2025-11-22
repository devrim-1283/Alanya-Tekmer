<?php
$pageTitle = 'KVKK Bilgilendirme - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER Kişisel Verilerin Korunması Kanunu (KVKK) Aydınlatma Metni';
$currentPage = 'kvkk';
logPageView('kvkk');
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content" data-aos="fade-up">
            <h1 class="page-title">KVKK Bilgilendirme</h1>
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Ana Sayfa</a>
                <span>/</span>
                <span>KVKK</span>
            </nav>
        </div>
    </div>
</section>

<!-- Legal Content -->
<section class="legal-section">
    <div class="container">
        <div class="legal-document" data-aos="fade-up">
            <div class="legal-header">
                <div class="last-updated">
                    <i class="fas fa-clock"></i> Son Güncelleme: <?php echo date('d.m.Y'); ?>
                </div>
            </div>

            <div class="legal-body">
                <p class="lead">6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında kişisel verilerinizin nasıl işlendiği hakkında sizi bilgilendirmek istiyoruz.</p>

                <div class="legal-item">
                    <h2>1. Veri Sorumlusu</h2>
                    <p><strong>Alanya TEKMER A.Ş.</strong> olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca veri sorumlusu sıfatıyla, kişisel verilerinizin işlenmesine ilişkin sizleri bilgilendirmek isteriz.</p>
                </div>

                <div class="legal-item">
                    <h2>2. İşlenen Kişisel Veriler</h2>
                    <p>Başvuru sürecinde aşağıdaki kişisel verileriniz işlenmektedir:</p>
                    <ul class="legal-list">
                        <li><strong>Kimlik Bilgileri:</strong> Ad, soyad, TC kimlik numarası.</li>
                        <li><strong>İletişim Bilgileri:</strong> Telefon, e-posta adresi.</li>
                        <li><strong>Eğitim Bilgileri:</strong> Üniversite, bölüm.</li>
                        <li><strong>Mesleki Bilgiler:</strong> Firma bilgileri, proje detayları.</li>
                        <li><strong>İşlem Güvenliği:</strong> IP adresi, kullanıcı aracı bilgileri.</li>
                    </ul>
                </div>

                <div class="legal-item">
                    <h2>3. Kişisel Verilerin İşlenme Amacı</h2>
                    <p>Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:</p>
                    <ul class="legal-list check-list">
                        <li>Başvuruların değerlendirilmesi ve yönetilmesi</li>
                        <li>İletişim faaliyetlerinin yürütülmesi</li>
                        <li>Yasal yükümlülüklerin yerine getirilmesi</li>
                        <li>İstatistiksel analiz ve raporlama</li>
                        <li>Bilgi güvenliği süreçlerinin yürütülmesi</li>
                    </ul>
                </div>

                <div class="legal-item">
                    <h2>4. Kişisel Verilerin Aktarılması</h2>
                    <p>Kişisel verileriniz, yasal yükümlülükler çerçevesinde KOSGEB, Alanya Alaaddin Keykubat Üniversitesi ve ilgili kamu kurum ve kuruluşları ile paylaşılabilir.</p>
                </div>

                <div class="legal-item">
                    <h2>5. Kişisel Verilerin Toplanma Yöntemi</h2>
                    <p>Kişisel verileriniz, web sitemizdeki başvuru formları, iletişim formları ve otomatik loglar aracılığıyla elektronik ortamda toplanmaktadır.</p>
                </div>

                <div class="legal-item">
                    <h2>6. KVKK Kapsamındaki Haklarınız</h2>
                    <p>KVKK'nın 11. maddesi uyarınca aşağıdaki haklara sahipsiniz:</p>
                    <ul class="legal-list">
                        <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                        <li>İşlenmişse bilgi talep etme</li>
                        <li>İşlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                        <li>Yurt içinde veya yurt dışında aktarıldığı üçüncü kişileri bilme</li>
                        <li>Eksik veya yanlış işlenmişse düzeltilmesini isteme</li>
                        <li>Silinmesini veya yok edilmesini isteme</li>
                        <li>Aktarıldığı üçüncü kişilere işlemlerin bildirilmesini isteme</li>
                        <li>Otomatik sistemler ile analiz edilmesine itiraz etme</li>
                        <li>Kanuna aykırı işlenmesi sebebiyle zararın giderilmesini talep etme</li>
                    </ul>
                </div>

                <div class="legal-item">
                    <h2>7. Başvuru Yöntemi</h2>
                    <p>Yukarıda belirtilen haklarınızı kullanmak için aşağıdaki iletişim kanalları üzerinden başvuruda bulunabilirsiniz:</p>
                    <div class="contact-box">
                        <a href="mailto:<?php echo getSetting('contact_email'); ?>">
                            <i class="fas fa-envelope"></i> <?php echo getSetting('contact_email'); ?>
                        </a>
                        <a href="tel:<?php echo getSetting('contact_phone'); ?>">
                            <i class="fas fa-phone"></i> <?php echo getSetting('contact_phone'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Clean Legal Pages Design */
.legal-section {
    padding: 60px 0;
    background-color: #f8f9fa;
    min-height: 80vh;
}

.legal-document {
    max-width: 900px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.legal-header {
    padding: 30px 50px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
}

.last-updated {
    font-size: 0.9rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 8px;
}

.legal-body {
    padding: 50px;
}

.legal-body .lead {
    font-size: 1.15rem;
    color: #2c3e50;
    line-height: 1.8;
    margin-bottom: 40px;
    font-weight: 500;
}

.legal-item {
    margin-bottom: 40px;
}

.legal-item:last-child {
    margin-bottom: 0;
}

.legal-item h2 {
    font-size: 1.5rem;
    color: #1a202c;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #edf2f7;
    font-weight: 700;
}

.legal-item p {
    font-size: 1rem;
    line-height: 1.8;
    color: #4a5568;
    margin-bottom: 15px;
}

.legal-list {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.legal-list li {
    position: relative;
    padding-left: 25px;
    margin-bottom: 12px;
    color: #4a5568;
    line-height: 1.6;
}

.legal-list li::before {
    content: "•";
    color: #667eea;
    font-weight: bold;
    position: absolute;
    left: 0;
    font-size: 1.2em;
}

.legal-list.check-list li::before {
    content: "\f00c";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    font-size: 0.9em;
    top: 3px;
}

.info-note {
    background: #ebf8ff;
    border-left: 4px solid #4299e1;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    gap: 15px;
    align-items: flex-start;
    margin-top: 20px;
}

.info-note i {
    color: #4299e1;
    font-size: 1.2rem;
    margin-top: 3px;
}

.info-note p {
    margin: 0;
    font-size: 0.95rem;
    color: #2c5282;
}

.contact-box {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.contact-box a {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.contact-box a:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .legal-section {
        padding: 30px 0;
    }

    .legal-header {
        padding: 20px 30px;
    }

    .legal-body {
        padding: 30px 20px;
    }

    .legal-item h2 {
        font-size: 1.3rem;
    }

    .legal-body .lead {
        font-size: 1.05rem;
    }

    .contact-box {
        flex-direction: column;
        gap: 10px;
    }

    .contact-box a {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 50
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
