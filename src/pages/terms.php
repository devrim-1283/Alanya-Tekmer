<?php
$pageTitle = 'Kullanıcı Sözleşmesi - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER web sitesi kullanım koşulları ve kullanıcı sözleşmesi';
$currentPage = 'terms';
logPageView('terms');
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content" data-aos="fade-up">
            <h1 class="page-title">Kullanıcı Sözleşmesi</h1>
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Ana Sayfa</a>
                <span>/</span>
                <span>Kullanıcı Sözleşmesi</span>
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
                <p class="lead">Bu web sitesini kullanarak, aşağıdaki kullanım koşullarını okuduğunuzu, anladığınızı ve kabul ettiğinizi beyan etmiş olursunuz. Lütfen sözleşmemizi dikkatlice okuyunuz.</p>

                <div class="legal-item">
                    <h2>1. Genel Hükümler</h2>
                    <p>Bu web sitesini kullanarak, aşağıdaki kullanım koşullarını kabul etmiş sayılırsınız. Bu koşullar, web sitemizin tüm bölümleri ve sunulan tüm hizmetler için geçerlidir.</p>
                    <div class="info-note">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Alanya TEKMER, kullanım koşullarını herhangi bir zamanda değiştirme hakkını saklı tutar. Değişiklikler bu sayfada yayımlandığı andan itibaren geçerli olacaktır.</p>
                    </div>
                </div>

                <div class="legal-item">
                    <h2>2. Hizmet Kapsamı</h2>
                    <p>Alanya TEKMER web sitesi, aşağıdaki hizmetleri sunmak amacıyla oluşturulmuştur:</p>
                    <ul class="legal-list check-list">
                        <li>TEKMER hizmetleri ve fırsatları hakkında detaylı bilgilendirme</li>
                        <li>Online başvuru formları ve süreç takibi</li>
                        <li>Güncel etkinlik ve duyuru paylaşımı</li>
                        <li>İletişim formları ve destek hizmetleri</li>
                    </ul>
                </div>

                <div class="legal-item">
                    <h2>3. Kullanıcı Sorumlulukları</h2>
                    <p>Web sitemizi kullanan kullanıcılar aşağıdaki kurallara uymayı kabul eder:</p>
                    <ul class="legal-list">
                        <li><strong>Doğru Bilgi:</strong> Başvuru ve iletişim formlarında doğru ve güncel bilgi sağlamak.</li>
                        <li><strong>Yasal Kullanım:</strong> Web sitesini sadece yasal amaçlar için kullanmak.</li>
                        <li><strong>Güvenlik:</strong> Sisteme zarar verecek faaliyetlerden kaçınmak.</li>
                        <li><strong>Telif Hakları:</strong> Telif haklarına saygı göstermek ve izinsiz içerik kullanmamak.</li>
                    </ul>
                </div>

                <div class="legal-item">
                    <h2>4. Fikri Mülkiyet Hakları</h2>
                    <p>Web sitesinde yer alan tüm içerik, tasarım ve yazılım <strong>Alanya TEKMER A.Ş.</strong>'ye aittir ve telif hakları ile korunmaktadır. İzinsiz kullanım, kopyalama veya dağıtım yasaktır.</p>
                </div>

                <div class="legal-item">
                    <h2>5. Sorumluluk Sınırlaması</h2>
                    <p>Alanya TEKMER, web sitesinin kesintisiz ve hatasız çalışacağını garanti etmez. Teknik arızalar, bakım çalışmaları veya üçüncü taraf hizmetlerinden kaynaklanan sorunlardan sorumlu değildir.</p>
                </div>

                <div class="legal-item">
                    <h2>6. İletişim</h2>
                    <p>Kullanım koşulları hakkında sorularınız veya önerileriniz için bizimle iletişime geçebilirsiniz:</p>
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
