<?php
$pageTitle = 'Kullanıcı Sözleşmesi - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER web sitesi kullanım koşulları ve kullanıcı sözleşmesi';
$currentPage = '';
logPageView('terms');
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content" data-aos="fade-up">
            <div class="legal-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <h1 class="page-title">Kullanıcı Sözleşmesi</h1>
            <p class="page-subtitle">Web sitemizi kullanım koşulları ve kuralları</p>
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Ana Sayfa</a>
                <span>/</span>
                <span>Kullanıcı Sözleşmesi</span>
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
                <i class="fas fa-handshake"></i>
            </div>
            <h2>Kullanım Koşullarımız</h2>
            <p>Bu web sitesini kullanarak, aşağıdaki kullanım koşullarını okuduğunuzu, anladığınızı ve kabul ettiğinizi beyan etmiş olursunuz. Lütfen sözleşmemizi dikkatlice okuyunuz.</p>
        </div>

        <!-- Content Cards -->
        <div class="legal-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h2>1. Genel Hükümler</h2>
            </div>
            <div class="card-body">
                <p>Bu web sitesini kullanarak, aşağıdaki kullanım koşullarını kabul etmiş sayılırsınız. Bu koşullar, web sitemizin tüm bölümleri ve sunulan tüm hizmetler için geçerlidir.</p>
                <div class="terms-box">
                    <i class="fas fa-balance-scale"></i>
                    <div>
                        <p><strong>Yasal Uyarı:</strong> Alanya TEKMER, kullanım koşullarını herhangi bir zamanda değiştirme hakkını saklı tutar. Değişiklikler bu sayfada yayımlandığı andan itibaren geçerli olacaktır.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="legal-card" data-aos="fade-up" data-aos-delay="150">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-server"></i>
                </div>
                <h2>2. Hizmet Kapsamı</h2>
            </div>
            <div class="card-body">
                <p>Alanya TEKMER web sitesi, aşağıdaki hizmetleri sunmak amacıyla oluşturulmuştur:</p>
                <div class="service-grid">
                    <div class="service-item">
                        <i class="fas fa-info"></i>
                        <strong>Bilgilendirme</strong>
                        <p>TEKMER hizmetleri ve fırsatları hakkında detaylı bilgi</p>
                    </div>
                    <div class="service-item">
                        <i class="fas fa-file-alt"></i>
                        <strong>Başvuru Yönetimi</strong>
                        <p>Online başvuru formları ve süreç takibi</p>
                    </div>
                    <div class="service-item">
                        <i class="fas fa-calendar-check"></i>
                        <strong>Etkinlik Duyuruları</strong>
                        <p>Güncel etkinlik ve duyuru paylaşımı</p>
                    </div>
                    <div class="service-item">
                        <i class="fas fa-envelope"></i>
                        <strong>İletişim</strong>
                        <p>İletişim formları ve destek hizmetleri</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="legal-card highlight-card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <h2>3. Kullanıcı Sorumlulukları</h2>
            </div>
            <div class="card-body">
                <p class="lead">Web sitemizi kullanan kullanıcılar aşağıdaki kurallara uymayı kabul eder:</p>
                <div class="responsibility-grid">
                    <div class="responsibility-item">
                        <div class="resp-icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <strong>Doğru Bilgi</strong>
                        <p>Başvuru ve iletişim formlarında doğru ve güncel bilgi sağlamak</p>
                    </div>
                    <div class="responsibility-item">
                        <div class="resp-icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <strong>Yasal Kullanım</strong>
                        <p>Web sitesini sadece yasal amaçlar için kullanmak</p>
                    </div>
                    <div class="responsibility-item">
                        <div class="resp-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <strong>Hakları Koruma</strong>
                        <p>Başkalarının haklarını ve gizliliğini ihlal etmemek</p>
                    </div>
                    <div class="responsibility-item">
                        <div class="resp-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <strong>Güvenlik</strong>
                        <p>Sisteme zarar verecek faaliyetlerden kaçınmak</p>
                    </div>
                    <div class="responsibility-item">
                        <div class="resp-icon">
                            <i class="fas fa-ban"></i>
                        </div>
                        <strong>Kötüye Kullanım Yasağı</strong>
                        <p>Spam, virüs veya zararlı içerik paylaşmamak</p>
                    </div>
                    <div class="responsibility-item">
                        <div class="resp-icon">
                            <i class="fas fa-copyright"></i>
                        </div>
                        <strong>Telif Hakları</strong>
                        <p>Telif haklarına saygı göstermek ve izinsiz içerik kullanmamak</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="legal-card" data-aos="fade-up" data-aos-delay="250">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-copyright"></i>
                </div>
                <h2>4. Fikri Mülkiyet Hakları</h2>
            </div>
            <div class="card-body">
                <p>Web sitesinde yer alan tüm içerik, tasarım ve yazılım <strong>Alanya TEKMER A.Ş.</strong>'ye aittir ve telif hakları ile korunmaktadır.</p>
                <div class="ip-grid">
                    <div class="ip-item">
                        <i class="fas fa-palette"></i>
                        <strong>Tasarım</strong>
                        <p>Tüm görsel ve grafik tasarımlar</p>
                    </div>
                    <div class="ip-item">
                        <i class="fas fa-code"></i>
                        <strong>Yazılım</strong>
                        <p>Kaynak kod ve uygulama</p>
                    </div>
                    <div class="ip-item">
                        <i class="fas fa-file-alt"></i>
                        <strong>İçerik</strong>
                        <p>Tüm metin ve dokümantasyon</p>
                    </div>
                    <div class="ip-item">
                        <i class="fas fa-trademark"></i>
                        <strong>Marka</strong>
                        <p>Logo ve marka tescilleri</p>
                    </div>
                </div>
                <div class="warning-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p><strong>Uyarı:</strong> İzinsiz kullanım, kopyalama veya dağıtım yasaktır ve yasal işlem gerektirebilir.</p>
                </div>
            </div>
        </div>

        <div class="legal-card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h2>5. Sorumluluk Sınırlaması</h2>
            </div>
            <div class="card-body">
                <p>Alanya TEKMER, web sitesinin kesintisiz ve hatasız çalışacağını garanti etmez. Aşağıdaki durumlardan sorumlu değildir:</p>
                <ul class="styled-list">
                    <li><i class="fas fa-times-circle"></i> Teknik arızalar veya bakım çalışmaları nedeniyle oluşan kesintiler</li>
                    <li><i class="fas fa-times-circle"></i> Kullanıcı hatalarından kaynaklanan veri kayıpları</li>
                    <li><i class="fas fa-times-circle"></i> Üçüncü taraf hizmetlerinden kaynaklanan sorunlar</li>
                    <li><i class="fas fa-times-circle"></i> İnternet bağlantısı veya cihaz problemlerinden doğan aksaklıklar</li>
                    <li><i class="fas fa-times-circle"></i> Web sitesinin kullanımından kaynaklanan dolaylı zararlar</li>
                </ul>
            </div>
        </div>

        <div class="legal-card" data-aos="fade-up" data-aos-delay="350">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <h2>6. Değişiklikler</h2>
            </div>
            <div class="card-body">
                <p>Alanya TEKMER, kullanım koşullarını önceden haber vermeksizin değiştirme hakkını saklı tutar. Değişiklikler bu sayfada yayımlandığı andan itibaren geçerli olacaktır.</p>
                <div class="update-box">
                    <i class="fas fa-bell"></i>
                    <div>
                        <strong>Önemli Güncelleme:</strong>
                        <p>Web sitemizi düzenli olarak ziyaret ederek kullanım koşullarındaki değişiklikleri takip etmenizi öneririz. Siteyi kullanmaya devam etmeniz, güncel koşulları kabul ettiğiniz anlamına gelir.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="legal-card contact-card" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h2>7. İletişim</h2>
            </div>
            <div class="card-body">
                <p>Kullanım koşulları hakkında sorularınız veya önerileriniz için bizimle iletişime geçebilirsiniz:</p>
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
                        <span>Bize Ulaşın</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Agreement Box -->
        <div class="agreement-box" data-aos="fade-up" data-aos-delay="450">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Sözleşme Kabulü</strong>
                <p>Bu web sitesini kullanarak, yukarıdaki tüm kullanım koşullarını okuduğunuzu, anladığınızı ve kabul ettiğinizi onaylamış olursunuz.</p>
            </div>
        </div>

        <!-- Last Updated -->
        <div class="last-updated" data-aos="fade-up" data-aos-delay="500">
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
/* Additional Styles for Terms Page */
.terms-box {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: #e7f3ff;
    border-left: 4px solid #0066cc;
    border-radius: 8px;
    margin-top: 20px;
}

.terms-box i {
    font-size: 28px;
    color: #0066cc;
    flex-shrink: 0;
}

.terms-box strong {
    display: block;
    color: #0066cc;
    margin-bottom: 5px;
}

.terms-box p {
    margin: 0;
    color: #004085;
    font-size: 14px;
    line-height: 1.6;
}

.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 25px;
}

.service-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 25px 15px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.service-item:hover {
    background: #667eea;
    transform: translateY(-3px);
}

.service-item:hover i,
.service-item:hover strong,
.service-item:hover p {
    color: white;
}

.service-item i {
    font-size: 36px;
    color: #667eea;
    margin-bottom: 15px;
    transition: color 0.3s ease;
}

.service-item strong {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-size: 16px;
    transition: color 0.3s ease;
}

.service-item p {
    margin: 0;
    font-size: 13px;
    color: #666;
    line-height: 1.5;
    transition: color 0.3s ease;
}

.responsibility-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 25px;
}

.responsibility-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 25px 20px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    border: 2px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
}

.responsibility-item:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-3px);
}

.resp-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.resp-icon i {
    font-size: 28px;
    color: white;
}

.responsibility-item strong {
    display: block;
    margin-bottom: 8px;
    color: white;
    font-size: 16px;
}

.responsibility-item p {
    margin: 0;
    font-size: 14px;
    color: rgba(255,255,255,0.9);
    line-height: 1.5;
}

.ip-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.ip-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 20px 15px;
    background: #f8f9fa;
    border-radius: 12px;
}

.ip-item i {
    font-size: 32px;
    color: #667eea;
    margin-bottom: 12px;
}

.ip-item strong {
    display: block;
    margin-bottom: 5px;
    color: #333;
    font-size: 15px;
}

.ip-item p {
    margin: 0;
    font-size: 13px;
    color: #666;
}

.warning-box {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    border-radius: 8px;
    margin-top: 20px;
}

.warning-box i {
    font-size: 24px;
    color: #856404;
    flex-shrink: 0;
}

.warning-box p {
    margin: 0;
    color: #856404;
    font-size: 14px;
    line-height: 1.6;
}

.update-box {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: #d1ecf1;
    border-left: 4px solid #17a2b8;
    border-radius: 8px;
    margin-top: 20px;
}

.update-box i {
    font-size: 28px;
    color: #0c5460;
    flex-shrink: 0;
}

.update-box strong {
    display: block;
    color: #0c5460;
    margin-bottom: 5px;
    font-size: 16px;
}

.update-box p {
    margin: 0;
    color: #0c5460;
    font-size: 14px;
    line-height: 1.6;
}

.agreement-box {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 30px;
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    border-radius: 16px;
    margin-top: 30px;
    box-shadow: 0 5px 20px rgba(17, 153, 142, 0.3);
}

.agreement-box i {
    font-size: 48px;
    color: white;
    flex-shrink: 0;
}

.agreement-box strong {
    display: block;
    margin-bottom: 8px;
    font-size: 18px;
    color: white;
}

.agreement-box p {
    margin: 0;
    color: white;
    font-size: 15px;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .service-grid,
    .responsibility-grid,
    .ip-grid {
        grid-template-columns: 1fr;
    }
    
    .terms-box,
    .warning-box,
    .update-box {
        flex-direction: column;
        text-align: center;
    }
    
    .agreement-box {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

