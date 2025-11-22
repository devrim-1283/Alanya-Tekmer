<?php
$pageTitle = 'Gizlilik Sözleşmesi - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER web sitesi gizlilik politikası ve kişisel verilerin korunması hakkında bilgilendirme';
$currentPage = '';
logPageView('privacy');
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content" data-aos="fade-up">
            <div class="legal-icon">
                <i class="fas fa-user-lock"></i>
            </div>
            <h1 class="page-title">Gizlilik Sözleşmesi</h1>
            <p class="page-subtitle">Kişisel verilerinizin güvenliği ve gizliliği önceliğimizdir</p>
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Ana Sayfa</a>
                <span>/</span>
                <span>Gizlilik Sözleşmesi</span>
            </nav>
        </div>
    </div>
</section>

<!-- Legal Content -->
<section class="legal-content-section">
    <div class="container">
        <!-- Introduction Card -->
        <div class="legal-intro-card" data-aos="fade-up">
            <div class="intro-icon">
                <i class="fas fa-shield-check"></i>
            </div>
            <h2>Gizliliğiniz Önceliğimiz</h2>
            <p>Alanya TEKMER olarak, ziyaretçilerimizin gizliliğine saygı duyuyor ve kişisel verilerinizi korumayı taahhüt ediyoruz. Bu gizlilik politikası, web sitemizi ziyaret ettiğinizde toplanan bilgilerin nasıl kullanıldığını açıklar.</p>
        </div>

        <!-- Content Cards -->
        <div class="legal-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <h2>1. Giriş</h2>
            </div>
            <div class="card-body">
                <p>Alanya TEKMER olarak, ziyaretçilerimizin gizliliğine saygı duyuyor ve kişisel verilerinizi korumayı taahhüt ediyoruz. Bu gizlilik politikası, web sitemizi ziyaret ettiğinizde toplanan bilgilerin nasıl kullanıldığını ve korunduğunu detaylı bir şekilde açıklar.</p>
            </div>
        </div>

        <div class="legal-card" data-aos="fade-up" data-aos-delay="150">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <h2>2. Toplanan Bilgiler</h2>
            </div>
            <div class="card-body">
                <p>Web sitemizi kullanırken aşağıdaki bilgiler toplanabilir:</p>
                <div class="feature-list">
                    <div class="feature-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <strong>Kişisel Bilgiler</strong>
                            <p>İsim, e-posta adresi, telefon numarası</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-laptop"></i>
                        <div>
                            <strong>Teknik Bilgiler</strong>
                            <p>IP adresi, tarayıcı türü, ziyaret edilen sayfalar</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-file-invoice"></i>
                        <div>
                            <strong>İş ve Proje Bilgileri</strong>
                            <p>Başvuru formları aracılığıyla sağlanan bilgiler</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="legal-card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h2>3. Bilgilerin Kullanımı</h2>
            </div>
            <div class="card-body">
                <p>Toplanan bilgiler şu amaçlarla kullanılır:</p>
                <ul class="styled-list">
                    <li><i class="fas fa-check-circle"></i> Başvuruların değerlendirilmesi ve işleme alınması</li>
                    <li><i class="fas fa-check-circle"></i> İletişim taleplerinin yanıtlanması ve destek sağlanması</li>
                    <li><i class="fas fa-check-circle"></i> Web sitesi performansının iyileştirilmesi ve kullanıcı deneyiminin artırılması</li>
                    <li><i class="fas fa-check-circle"></i> Yasal yükümlülüklerin yerine getirilmesi</li>
                </ul>
            </div>
        </div>

        <div class="legal-card highlight-card" data-aos="fade-up" data-aos-delay="250">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-cookie-bite"></i>
                </div>
                <h2>4. Çerezler (Cookies)</h2>
            </div>
            <div class="card-body">
                <p class="lead">Web sitemiz, kullanıcı deneyimini geliştirmek için çerezler kullanır.</p>
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <p><strong>Çerezler Nedir?</strong></p>
                        <p>Çerezler, web sitesi ziyaretiniz sırasında tarayıcınıza kaydedilen küçük metin dosyalarıdır. Bu dosyalar, siteyi tekrar ziyaret ettiğinizde size daha iyi bir deneyim sunmamıza yardımcı olur.</p>
                        <p><strong>Nasıl Devre Dışı Bırakılır?</strong></p>
                        <p>Tarayıcı ayarlarınızdan çerezleri devre dışı bırakabilirsiniz. Ancak bu durumda, web sitesinin bazı özellikleri düzgün çalışmayabilir.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="legal-card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>5. Güvenlik</h2>
            </div>
            <div class="card-body">
                <p>Kişisel verilerinizi korumak için uygun teknik ve organizasyonel güvenlik önlemleri alıyoruz.</p>
                <div class="security-grid">
                    <div class="security-item">
                        <i class="fas fa-server"></i>
                        <strong>Güvenli Sunucular</strong>
                        <p>Verileriniz şifreli sunucularda saklanır</p>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-certificate"></i>
                        <strong>SSL Sertifikası</strong>
                        <p>HTTPS protokolü ile güvenli bağlantı</p>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-eye-slash"></i>
                        <strong>Erişim Kontrolü</strong>
                        <p>Sınırlı ve kontrollü veri erişimi</p>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-shield-virus"></i>
                        <strong>Güvenlik Duvarı</strong>
                        <p>Sürekli güvenlik izleme ve koruma</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="legal-card contact-card" data-aos="fade-up" data-aos-delay="350">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h2>6. İletişim</h2>
            </div>
            <div class="card-body">
                <p>Gizlilik politikamız hakkında sorularınız veya endişeleriniz için bizimle iletişime geçebilirsiniz:</p>
                <div class="contact-info-grid">
                    <a href="mailto:<?php echo getSetting('contact_email'); ?>" class="contact-link">
                        <i class="fas fa-envelope"></i>
                        <span><?php echo getSetting('contact_email'); ?></span>
                    </a>
                    <a href="tel:<?php echo getSetting('contact_phone'); ?>" class="contact-link">
                        <i class="fas fa-phone-alt"></i>
                        <span><?php echo getSetting('contact_phone'); ?></span>
                    </a>
                </div>
                <div class="cta-button">
                    <a href="<?php echo url('iletisim'); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i>
                        <span>İletişim Formuna Git</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="info-banner" data-aos="fade-up" data-aos-delay="400">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Önemli Not:</strong>
                <p>Bu gizlilik politikası, Alanya TEKMER web sitesi için geçerlidir. Sitemizden link verilen dış web siteleri için geçerli değildir. Dış sitelerin gizlilik politikalarını incelemenizi öneririz.</p>
            </div>
        </div>

        <!-- Last Updated -->
        <div class="last-updated" data-aos="fade-up" data-aos-delay="450">
            <i class="fas fa-clock"></i>
            <span>Son Güncelleme: <?php echo date('d.m.Y'); ?></span>
        </div>
    </div>
</section>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
</script>

<style>
/* Additional Styles for Privacy Page */
.info-box {
    display: flex;
    gap: 20px;
    padding: 25px;
    background: rgba(255,255,255,0.3);
    border-radius: 12px;
    margin-top: 20px;
    border-left: 4px solid rgba(255,255,255,0.5);
}

.info-box i {
    font-size: 32px;
    color: rgba(255,255,255,0.9);
    flex-shrink: 0;
}

.info-box p {
    margin: 0 0 10px 0;
    color: white;
}

.info-box p:last-child {
    margin-bottom: 0;
}

.info-box strong {
    color: white;
    font-size: 16px;
}

.security-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 25px;
}

.security-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 25px 15px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.security-item:hover {
    background: #28a745;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.security-item:hover i {
    color: white;
}

.security-item:hover strong,
.security-item:hover p {
    color: white;
}

.security-item i {
    font-size: 36px;
    color: #28a745;
    margin-bottom: 15px;
    transition: color 0.3s ease;
}

.security-item strong {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-size: 16px;
    transition: color 0.3s ease;
}

.security-item p {
    margin: 0;
    font-size: 13px;
    color: #666;
    line-height: 1.5;
    transition: color 0.3s ease;
}

.cta-button {
    margin-top: 30px;
    text-align: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: white;
    color: #f5576c;
    border: 2px solid white;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn:hover {
    background: transparent;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.info-banner {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 25px 30px;
    background: #fff3cd;
    border-left: 5px solid #ffc107;
    border-radius: 12px;
    margin-top: 30px;
}

.info-banner i {
    font-size: 28px;
    color: #856404;
    flex-shrink: 0;
    margin-top: 2px;
}

.info-banner strong {
    display: block;
    margin-bottom: 8px;
    color: #856404;
    font-size: 16px;
}

.info-banner p {
    margin: 0;
    color: #856404;
    font-size: 14px;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 992px) {
    .legal-content-section {
        padding: 60px 0;
    }
    
    .legal-intro-card {
        padding: 35px 25px;
    }
    
    .card-header {
        padding: 20px 25px;
    }
    
    .card-body {
        padding: 25px;
    }
}

@media (max-width: 768px) {
    .legal-content-section {
        padding: 40px 0;
    }
    
    .legal-intro-card {
        padding: 25px 20px;
        margin-bottom: 30px;
    }
    
    .legal-intro-card h2 {
        font-size: 22px;
    }
    
    .legal-intro-card p {
        font-size: 14px;
    }
    
    .intro-icon {
        font-size: 36px;
        margin-bottom: 15px;
    }
    
    .legal-card {
        margin-bottom: 20px;
    }
    
    .card-header {
        padding: 18px 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .card-header h2 {
        font-size: 18px;
    }
    
    .card-icon {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .card-body p {
        font-size: 14px;
        line-height: 1.7;
    }
    
    .card-body .lead {
        font-size: 15px;
    }
    
    .security-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .security-item {
        padding: 20px 15px;
    }
    
    .security-item i {
        font-size: 28px;
    }
    
    .security-item strong {
        font-size: 15px;
    }
    
    .security-item p {
        font-size: 12px;
    }
    
    .feature-list {
        gap: 15px;
    }
    
    .feature-item {
        padding: 15px;
        gap: 12px;
    }
    
    .feature-item i {
        font-size: 20px;
    }
    
    .feature-item strong {
        font-size: 15px;
    }
    
    .feature-item p {
        font-size: 13px;
    }
    
    .info-box {
        flex-direction: column;
        text-align: center;
        padding: 20px;
        gap: 15px;
    }
    
    .info-box i {
        font-size: 28px;
    }
    
    .info-banner {
        flex-direction: column;
        text-align: center;
        padding: 20px;
        gap: 15px;
    }
    
    .info-banner i {
        font-size: 24px;
    }
    
    .info-banner strong {
        font-size: 15px;
    }
    
    .info-banner p {
        font-size: 13px;
    }
    
    .contact-info-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .contact-link {
        padding: 14px 18px;
        font-size: 14px;
    }
    
    .cta-button {
        margin-top: 25px;
    }
    
    .btn {
        padding: 12px 24px;
        font-size: 14px;
        width: 100%;
        justify-content: center;
    }
    
    .last-updated {
        padding: 15px;
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .legal-content-section {
        padding: 30px 0;
    }
    
    .legal-intro-card {
        padding: 20px 15px;
    }
    
    .legal-intro-card h2 {
        font-size: 20px;
    }
    
    .card-header {
        padding: 15px;
    }
    
    .card-header h2 {
        font-size: 16px;
    }
    
    .card-body {
        padding: 18px;
    }
    
    .card-body p {
        font-size: 13px;
    }
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

