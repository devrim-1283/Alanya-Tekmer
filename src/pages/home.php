<?php
// Home page
$pageTitle = 'Ana Sayfa - Alanya TEKMER';
$metaDescription = 'Alanya Teknoloji Geliştirme Merkezi - Girişimcilere yönelik danışmanlık, mentorluk ve destekler';
$currentPage = 'home';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section with Background Image -->
<section class="hero-section">
    <div class="hero-background">
        <img src="<?php echo url('uploads/tekmer.jpg'); ?>" alt="Alanya TEKMER" class="hero-bg-image">
        <div class="hero-overlay"></div>
    </div>
    
    <div class="hero-content">
        <div class="container">
            <div class="hero-text" data-aos="fade-up">
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

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?php echo getTotalCompanies(); ?>">0</h3>
                    <p class="stat-label">Şirket</p>
                </div>
            </div>
            
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="150">0</h3>
                    <p class="stat-label">Girişimci</p>
                </div>
            </div>
            
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?php echo getTotalEvents(); ?>">0</h3>
                    <p class="stat-label">Etkinlik</p>
                </div>
            </div>
            
            <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="50">0</h3>
                    <p class="stat-label">Başarı Hikayesi</p>
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
</script>

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
