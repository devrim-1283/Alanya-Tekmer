<?php
$pageTitle = 'Galeri - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER fotoğraf ve video galerisi. Tesisimizden görüntüler ve etkinliklerden anlar.';
$currentPage = 'gallery';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content" data-aos="fade-up">
            <h1 class="page-title">Galeri</h1>
            <p class="page-subtitle">TEKMER'den görüntüler ve videolar</p>
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Ana Sayfa</a>
                <span>/</span>
                <span>Galeri</span>
            </nav>
        </div>
    </div>
</section>

<!-- Gallery Content -->
<section class="gallery-page-section">
    <div class="container">
        <!-- Gallery Grid -->
        <div class="gallery-grid" data-aos="fade-up">
            <?php
            try {
                $db = Database::getInstance();
                $galleries = $db->fetchAll(
                    'SELECT * FROM gallery WHERE is_active = ? ORDER BY sort_order ASC, id DESC',
                    [true]
                );
                
                if (empty($galleries)) {
                    echo '<div class="empty-state">
                            <i class="fas fa-images"></i>
                            <h3>Henüz galeri öğesi eklenmemiş</h3>
                            <p>Yakında fotoğraflar ve videolar eklenecektir.</p>
                          </div>';
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
                echo '<div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        Galeri yüklenirken bir hata oluştu.
                      </div>';
            }
            ?>
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
    
    // Lightbox functionality for gallery images
    document.querySelectorAll('.gallery-item:not(.gallery-video) .gallery-image').forEach(item => {
        item.addEventListener('click', function() {
            const img = this.querySelector('img');
            if (img) {
                // Create lightbox
                const lightbox = document.createElement('div');
                lightbox.className = 'lightbox';
                lightbox.innerHTML = `
                    <div class="lightbox-content">
                        <span class="lightbox-close">&times;</span>
                        <img src="${img.src}" alt="${img.alt}">
                    </div>
                `;
                document.body.appendChild(lightbox);
                document.body.style.overflow = 'hidden';
                
                // Close lightbox
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox || e.target.className === 'lightbox-close') {
                        document.body.removeChild(lightbox);
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    });
</script>

<style>
/* Gallery Page Styles */
.gallery-page-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
}

.gallery-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.gallery-image {
    position: relative;
    padding-bottom: 75%; /* 4:3 aspect ratio */
    overflow: hidden;
    background: #f0f0f0;
    cursor: pointer;
}

.gallery-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-image iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay i {
    font-size: 48px;
    color: white;
}

.gallery-content {
    padding: 20px;
}

.gallery-content h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: #333;
}

.gallery-content p {
    margin: 0;
    font-size: 14px;
    color: #666;
    line-height: 1.6;
}

.video-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255,0,0,0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
    pointer-events: none;
}

.video-badge i {
    font-size: 20px;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    grid-column: 1 / -1;
}

.empty-state i {
    font-size: 80px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    font-size: 24px;
    color: #666;
}

.empty-state p {
    margin: 0;
    font-size: 16px;
    color: #999;
}

/* Lightbox Styles */
.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    cursor: pointer;
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
}

.lightbox-content img {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
}

.lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    color: white;
    font-size: 36px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.lightbox-close:hover {
    color: #ff0000;
}

/* Responsive Design */
@media (max-width: 768px) {
    .gallery-page-section {
        padding: 40px 0;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;
    }
    
    .gallery-content {
        padding: 15px;
    }
    
    .gallery-content h3 {
        font-size: 16px;
    }
    
    .gallery-content p {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

