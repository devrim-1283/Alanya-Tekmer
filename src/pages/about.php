<?php
$pageTitle = 'Hakkımızda - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER A.Ş., Alanya Alaaddin Keykubat Üniversitesi ve KOSGEB proje desteği ile kurulmuştur.';
$currentPage = 'about';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content" data-aos="fade-up">
            <h1 class="page-title">Hakkımızda</h1>
            <p class="page-subtitle">Alanya TEKMER olarak biz kimiz, misyonumuz nedir?</p>
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Ana Sayfa</a>
                <span>/</span>
                <span>Hakkımızda</span>
            </nav>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="about-section">
    <div class="container">
        <!-- Who We Are -->
        <div class="content-card" data-aos="fade-up">
            <div class="card-icon">
                <i class="fas fa-building"></i>
            </div>
            <h2 class="card-title">Biz Kimiz?</h2>
            <div class="card-content">
                <p><strong>ALANYA TEKMER A.Ş.</strong>, Alanya Alaaddin Keykubat Üniversitesi ve Küçük ve Orta Ölçekli İşletmeleri Geliştirme ve Destekleme İdaresi Başkanlığı (KOSGEB) proje desteği ile <strong>15 Ekim 2024</strong> tarihinde kurulmuştur.</p>
                
                <p>ALANYA TEKMER ALKÜ Kestel Yerleşkesinde <strong>1085 m²</strong> alan üzerine inşa edilmiş olup, bünyesinde;</p>
                
                <div class="features-grid">
                    <div class="feature-item">
                        <i class="fas fa-door-open"></i>
                        <span>13 Kapalı Ofis</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <span>3 Ortak Çalışma Alanı</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-handshake"></i>
                        <span>1 Toplantı Salonu</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>1 Eğitim Salonu</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-coffee"></i>
                        <span>1 Sosyal Alan</span>
                    </div>
                </div>
                
                <p class="highlight-text">Alanya Alaaddin Keykubat Üniversitesi olarak hayata geçirdiğimiz TEKMER ile <strong>TR 61 Bölgesi'nin teknoloji ve inovasyon üssü</strong> olmayı hedefliyoruz. Girişimcilere ve teknoloji odaklı işletmelere sürdürülebilir büyüme için güçlü bir destek sunmakta kararlıyız.</p>
            </div>
        </div>
        
        <!-- Mission -->
        <div class="content-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-icon">
                <i class="fas fa-bullseye"></i>
            </div>
            <h2 class="card-title">Misyonumuz</h2>
            <div class="card-content">
                <p>Teknoloji Geliştirme Merkezi (TEKMER), Alanya Alaaddin Keykubat Üniversitesi tarafından kurulan ve bölge ekonomisine katkı sağlamak amacıyla hayata geçirilen bir inisiyatiftir.</p>
                
                <p>TEKMER'in temel misyonu, <strong>TR 61 Bölgesi'nde</strong> (Antalya, Isparta, Burdur) yer alan girişimciler, start-uplar ve teknoloji odaklı işletmeler için bir merkez oluşturarak, bölgenin bilim ve teknoloji tabanlı inovasyon potansiyelini harekete geçirmek ve sürdürülebilir ekonomik büyümeyi desteklemektir.</p>
            </div>
        </div>
        
        <!-- Gallery -->
        <div class="gallery-section" data-aos="fade-up" data-aos-delay="150">
            <div class="section-header-center">
                <h2 class="section-title">Galeri</h2>
                <p class="section-description">TEKMER'den görüntüler ve videolar</p>
            </div>
            
            <div class="gallery-grid">
                <?php
                try {
                    $db = Database::getInstance();
                    $galleries = $db->fetchAll(
                        'SELECT * FROM gallery WHERE is_active = ? ORDER BY sort_order ASC, id DESC',
                        [true]
                    );
                    
                    if (empty($galleries)) {
                        echo '<p class="text-center">Henüz galeri öğesi eklenmemiş.</p>';
                    } else {
                        foreach ($galleries as $item):
                            if ($item['type'] === 'image'):
                ?>
                    <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100">
                        <div class="gallery-image">
                            <img src="<?php echo url($item['media_path']); ?>" 
                                 alt="<?php echo Security::escape($item['title'] ?? 'Galeri resmi'); ?>"
                                 loading="lazy">
                            <div class="gallery-overlay">
                                <i class="fas fa-search-plus"></i>
                            </div>
                        </div>
                        <?php if ($item['title'] || $item['description']): ?>
                        <div class="gallery-content">
                            <?php if ($item['title']): ?>
                                <h3><?php echo Security::escape($item['title']); ?></h3>
                            <?php endif; ?>
                            <?php if ($item['description']): ?>
                                <p><?php echo Security::escape($item['description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php
                            elseif ($item['type'] === 'video'):
                                // YouTube veya Vimeo URL'si varsa
                                if ($item['video_url']):
                                    $videoId = '';
                                    $platform = '';
                                    
                                    // YouTube video ID'sini çıkar
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $item['video_url'], $match)) {
                                        $videoId = $match[1];
                                        $platform = 'youtube';
                                    }
                                    // Vimeo video ID'sini çıkar
                                    elseif (preg_match('/vimeo\.com\/(\d+)/i', $item['video_url'], $match)) {
                                        $videoId = $match[1];
                                        $platform = 'vimeo';
                                    }
                                    
                                    if ($videoId):
                ?>
                    <div class="gallery-item gallery-video" data-aos="zoom-in" data-aos-delay="100">
                        <div class="gallery-image">
                            <?php if ($platform === 'youtube'): ?>
                                <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            <?php elseif ($platform === 'vimeo'): ?>
                                <iframe src="https://player.vimeo.com/video/<?php echo $videoId; ?>" 
                                        frameborder="0" 
                                        allow="autoplay; fullscreen; picture-in-picture" 
                                        allowfullscreen></iframe>
                            <?php endif; ?>
                            <div class="video-badge">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <?php if ($item['title'] || $item['description']): ?>
                        <div class="gallery-content">
                            <?php if ($item['title']): ?>
                                <h3><?php echo Security::escape($item['title']); ?></h3>
                            <?php endif; ?>
                            <?php if ($item['description']): ?>
                                <p><?php echo Security::escape($item['description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php
                                    endif;
                                endif;
                            endif;
                        endforeach;
                    }
                } catch (Exception $e) {
                    error_log('Error fetching gallery: ' . $e->getMessage());
                    echo '<p class="text-center">Galeri yüklenirken bir hata oluştu.</p>';
                }
                ?>
            </div>
        </div>
        
        <!-- FAQ -->
        <div class="faq-section" data-aos="fade-up" data-aos-delay="200">
            <div class="section-header-center">
                <h2 class="section-title">Sıkça Sorulan Sorular</h2>
                <p class="section-description">TEKMER hakkında merak ettiğiniz her şey</p>
            </div>
            
            <div class="faq-accordion">
                <?php
                try {
                    $db = Database::getInstance();
                    $faqs = $db->fetchAll(
                        'SELECT * FROM faq WHERE is_active = ? ORDER BY sort_order ASC',
                        [true]
                    );
                    
                    if (empty($faqs)) {
                        echo '<p class="text-center">Yakında SSS eklenecektir.</p>';
                    } else {
                        foreach ($faqs as $faq):
                ?>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php echo Security::escape($faq['question']); ?></h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <?php echo $faq['answer']; ?>
                        </div>
                    </div>
                <?php
                        endforeach;
                    }
                } catch (Exception $e) {
                    error_log('Error fetching FAQs: ' . $e->getMessage());
                    echo '<p class="text-center">SSS yüklenirken bir hata oluştu.</p>';
                }
                ?>
            </div>
        </div>
        
        <!-- CTA -->
        <div class="cta-card" data-aos="fade-up" data-aos-delay="300">
            <h2>Projeleriniz için başvurmaya hazır mısınız?</h2>
            <p>Alanya TEKMER ailesine katılın ve girişiminizi bir üst seviyeye taşıyın.</p>
            <a href="<?php echo url('basvuru'); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket"></i>
                <span>Hemen Başvur</span>
            </a>
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
    
    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const faqItem = question.closest('.faq-item');
            const isActive = faqItem.classList.contains('active');
            
            // Close all
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
