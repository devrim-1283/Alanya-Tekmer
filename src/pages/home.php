<?php
// Home page
$pageTitle = 'Ana Sayfa - Alanya TEKMER';
$metaDescription = 'Alanya Teknoloji Geliştirme Merkezi - Girişimcilere yönelik danışmanlık, mentorluk ve destekler';
$currentPage = 'home';

logPageView('home');

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section - Split Layout -->
<section class="hero-section hero-split">
    <div class="container">
        <div class="hero-wrapper">
            <!-- Left Side - Text Content -->
            <div class="hero-text" data-aos="fade-right">
                <span class="hero-subtitle">Hoş Geldiniz</span>
                <h1 class="hero-title">Alanya Teknoloji Geliştirme Merkezi</h1>
                <p class="hero-description">
                    Girişimcilere yönelik danışmanlık, mentorluk ve destekler ile 
                    inovasyon ekosisteminin kalbi
                </p>
                <div class="hero-buttons">
                    <a href="<?php echo url('basvuru'); ?>" class="btn btn-hero-primary">
                        <i class="fas fa-file-alt"></i>
                        <span>Başvuru Yap</span>
                    </a>
                    <a href="<?php echo url('hakkimizda'); ?>" class="btn btn-hero-secondary">
                        <i class="fas fa-info-circle"></i>
                        <span>Biz Kimiz?</span>
                    </a>
                </div>
            </div>
            
            <!-- Right Side - Image -->
            <div class="hero-image" data-aos="fade-left">
                <div class="hero-image-wrapper">
                    <img src="<?php echo asset('images/tekmer.jpg'); ?>" alt="Alanya TEKMER" class="hero-img" loading="eager" onerror="this.src='/uploads/tekmer.jpg'">
                    <div class="hero-image-overlay"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Down Indicator -->
    <div class="scroll-indicator">
        <div class="mouse">
            <div class="wheel"></div>
        </div>
        <span>Aşağı Kaydır</span>
    </div>
</section>

<!-- Stats Section - Modern Design -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?php echo getSetting('stat_companies', '0'); ?>"><?php echo getSetting('stat_companies', '0'); ?></h3>
                    <p class="stat-label">Şirket</p>
                </div>
            </div>
            
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?php echo getSetting('stat_entrepreneurs', '0'); ?>"><?php echo getSetting('stat_entrepreneurs', '0'); ?></h3>
                    <p class="stat-label">Girişimci</p>
                </div>
            </div>
            
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?php echo getSetting('stat_events', '0'); ?>"><?php echo getSetting('stat_events', '0'); ?></h3>
                    <p class="stat-label">Etkinlik</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Neler Sunuyoruz?</span>
            <h2 class="section-title">Hizmetlerimiz</h2>
            <p class="section-description">Girişimcilere ve şirketlere yönelik kapsamlı destek hizmetleri</p>
        </div>
        
        <div class="services-grid">
            <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                <div class="service-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3 class="service-title">Danışmanlık</h3>
                <p class="service-description">İş geliştirme, pazarlama ve stratejik yönetim danışmanlığı</p>
                <a href="<?php echo url('hizmetlerimiz'); ?>" class="service-link">
                    Detaylı Bilgi <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                <div class="service-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 class="service-title">Eğitim</h3>
                <p class="service-description">Girişimcilik, inovasyon ve teknoloji eğitimleri</p>
                <a href="<?php echo url('hizmetlerimiz'); ?>" class="service-link">
                    Detaylı Bilgi <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="300">
                <div class="service-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 class="service-title">Mentorluk</h3>
                <p class="service-description">Alanında uzman mentorlardan birebir destek</p>
                <a href="<?php echo url('hizmetlerimiz'); ?>" class="service-link">
                    Detaylı Bilgi <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="400">
                <div class="service-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <h3 class="service-title">Ağ Oluşturma</h3>
                <p class="service-description">Yatırımcı ve iş ortağı buluşmaları</p>
                <a href="<?php echo url('hizmetlerimiz'); ?>" class="service-link">
                    Detaylı Bilgi <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Recent Events Section -->
<section class="events-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Güncel</span>
            <h2 class="section-title">Etkinlikler & Duyurular</h2>
            <p class="section-description">Yaklaşan etkinlikler ve önemli duyurularımız</p>
        </div>
        
        <div class="events-grid">
            <?php
            try {
                $db = Database::getInstance();
                $events = $db->fetchAll(
                    'SELECT * FROM events WHERE status = ? AND event_date >= CURRENT_DATE ORDER BY event_date ASC LIMIT 3',
                    ['active']
                );
                
                if (empty($events)) {
                    echo '<p class="text-center">Yakında etkinlikler eklenecektir.</p>';
                } else {
                    $delay = 100;
                    foreach ($events as $event):
            ?>
                <div class="event-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <?php if ($event['image_path']): ?>
                        <div class="event-image">
                            <img src="<?php echo url($event['image_path']); ?>" alt="<?php echo Security::escape($event['title']); ?>">
                            <div class="event-date-badge">
                                <span class="day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                                <span class="month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="event-content">
                        <span class="event-type"><?php echo Security::escape($event['type']); ?></span>
                        <h3 class="event-title"><?php echo Security::escape($event['title']); ?></h3>
                        <p class="event-excerpt"><?php echo mb_substr(strip_tags($event['description']), 0, 120); ?>...</p>
                        <div class="event-meta">
                            <span><i class="fas fa-calendar"></i> <?php echo date('d.m.Y', strtotime($event['event_date'])); ?></span>
                            <span><i class="fas fa-map-marker-alt"></i> <?php echo Security::escape($event['location']); ?></span>
                        </div>
                    </div>
                </div>
            <?php
                    $delay += 100;
                    endforeach;
                }
            } catch (Exception $e) {
                error_log('Error fetching events: ' . $e->getMessage());
                echo '<p class="text-center">Etkinlikler yüklenirken bir hata oluştu.</p>';
            }
            ?>
        </div>
        
        <div class="text-center" data-aos="fade-up">
            <a href="<?php echo url('etkinlikler'); ?>" class="btn btn-primary btn-lg">
                Tüm Etkinlikleri Gör <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section-home">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Merak Edilenler</span>
            <h2 class="section-title">Sıkça Sorulan Sorular</h2>
            <p class="section-description">TEKMER hakkında merak ettiğiniz her şey</p>
        </div>
        
        <div class="faq-accordion" data-aos="fade-up" data-aos-delay="100">
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
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content" data-aos="zoom-in">
            <h2 class="cta-title">Hemen Başvurun!</h2>
            <p class="cta-description">
                Projenizi hayata geçirmek için gereken tüm desteği almaya hazır mısınız?
            </p>
            <a href="<?php echo url('basvuru'); ?>" class="btn btn-cta-white">
                <i class="fas fa-rocket"></i>
                <span>Başvuru Formunu Doldurun</span>
            </a>
        </div>
    </div>
</section>

<!-- AOS Animation Library -->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
    
    // Counter Animation
    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                el.textContent = target;
                clearInterval(timer);
            } else {
                el.textContent = Math.floor(current);
            }
        }, 16);
    }
    
    // Trigger counters when in viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.stat-number');
                counters.forEach(counter => animateCounter(counter));
                observer.unobserve(entry.target);
            }
        });
    });
    
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
    
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

<!-- Mobile Optimizations for Home Page -->
<style>
@media (max-width: 768px) {
    /* Hero section mobile */
    .hero-wrapper {
        flex-direction: column;
        gap: 30px;
    }
    
    .hero-text {
        text-align: center;
    }
    
    .hero-title {
        font-size: 1.75rem;
        line-height: 1.2;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .hero-buttons {
        flex-direction: row;
        gap: 10px;
        justify-content: center;
    }
    
    .btn-hero-primary,
    .btn-hero-secondary {
        flex: 1;
        min-width: 0;
        justify-content: center;
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .btn-hero-primary span,
    .btn-hero-secondary span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .hero-image {
        order: -1; /* Show image first on mobile */
        max-width: 100%;
    }
    
    .hero-image-wrapper {
        height: 250px;
    }
    
    .scroll-indicator {
        display: none; /* Hide scroll indicator on mobile */
    }
    
    /* Stats grid mobile */
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .stat-card {
        padding: 20px 15px;
    }
    
    .stat-value {
        font-size: 1.75rem;
    }
    
    .stat-label {
        font-size: 0.85rem;
    }
    
    /* Features mobile */
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .feature-card {
        padding: 25px 20px;
    }
    
    /* Services mobile */
    .services-grid {
        grid-template-columns: 1fr;
    }
    
    /* Events mobile */
    .events-grid {
        grid-template-columns: 1fr;
    }
    
    .event-card {
        flex-direction: column;
    }
    
    .event-image {
        width: 100%;
        height: 200px;
    }
    
    .event-content {
        padding: 20px;
    }
    
    /* CTA buttons mobile */
    .cta-buttons {
        flex-direction: column;
        gap: 15px;
    }
    
    .cta-buttons .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 0.9rem;
    }
    
    .hero-buttons {
        gap: 8px;
    }
    
    .btn-hero-primary,
    .btn-hero-secondary {
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .btn-hero-primary i,
    .btn-hero-secondary i {
        font-size: 14px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-value {
        font-size: 2rem;
    }
}
</style>

<?php
// Helper functions for stats
function getTotalCompanies() {
    try {
        $db = Database::getInstance();
        $result = $db->fetchOne('SELECT COUNT(*) as count FROM companies WHERE status = ?', ['active']);
        return $result['count'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

function getTotalEvents() {
    try {
        $db = Database::getInstance();
        $result = $db->fetchOne('SELECT COUNT(*) as count FROM events WHERE status = ?', ['active']);
        return $result['count'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

require_once __DIR__ . '/../includes/footer.php';
?>
