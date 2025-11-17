<?php
$pageTitle = 'Alanya TEKMER - Teknoloji ve Girişimciliğin Merkezi';
$metaDescription = 'Alanya TEKMER olarak girişimcilere ve işletmelere inkübasyon, danışmanlık, mentörlük hizmetleri sunuyoruz.';
$currentPage = 'home';

logPageView('home');

include __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>ALANYA TEKMER</h1>
        <h2>TEKNOLOJİ VE GİRİŞİMCİLİĞİN MERKEZİ</h2>
        <p>Alanya TEKMER olarak ALKÜ Kestel Yerleşkesinde 1085 m² alan üzerine inşa edilmiş olup firmalar için konforlu odalar sunmaktadır.</p>
        <div class="hero-buttons">
            <a href="<?php echo url('basvuru'); ?>" class="btn btn-primary btn-lg">Başvuru Yap</a>
            <a href="<?php echo url('hakkimizda'); ?>" class="btn btn-secondary btn-lg">Hakkımızda</a>
        </div>
    </div>
    <div class="hero-image">
        <img src="<?php echo url('logo.png'); ?>" alt="Alanya TEKMER">
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <div class="section-header">
            <h2>Özelliklerimiz</h2>
            <p>Girişimcilere ve işletmelere sunduğumuz imkanlar</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3>1085 m² Alan</h3>
                <p>Alanya TEKMER olarak ALKÜ Kestel Yerleşkesinde 1085 m² alan üzerine inşa edilmiş olup işletmeler için konforlu odalar sunmaktadır.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <h3>13 Ofis</h3>
                <p>İşletmelere modern ve konforlu 13 kapalı ofis sunarak verimli bir çalışma ortamı sağlamaktadır.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Ortak Çalışma Alanı</h3>
                <p>Girişimcilerin işbirliği yapabileceği ve verimli çalışabileceği 3 ortak alan sunulmaktadır.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Uzman Ekip</h3>
                <p>Girişimciler ve işletmeler, deneyimli mentörlerden destek alarak projelerini geliştirme fırsatı bulmaktadır.</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-us">
    <div class="container">
        <div class="section-header">
            <h2>Neden Alanya TEKMER?</h2>
        </div>
        
        <div class="why-us-grid">
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>5746 Sayılı Kanun'dan Yararlanma İmkanı</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>Danışmanlık & Mentörlük</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>Sınai Mülkiyet Danışmanlık Hizmeti</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>Eğitimler</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>Sosyal Alanlar</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>Temizlik ve Güvenlik Hizmetleri</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>Profesyonel Toplantı Salonu</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>Kampüs Olanakları</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>Ücretsiz Wi-Fi</span>
            </div>
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <span>7/24 Çalışma İmkanı</span>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <h2>Projeleriniz için başvurmaya hazır mısınız?</h2>
        <p>Alanya TEKMER ailesine katılın ve girişiminizi bir üst seviyeye taşıyın.</p>
        <a href="<?php echo url('basvuru'); ?>" class="btn btn-primary btn-lg">Hemen Başvur</a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

