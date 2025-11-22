<?php
// Get event ID
$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Get event from database
$db = Database::getInstance();
$sql = 'SELECT * FROM events WHERE id = ?';
$event = $db->fetchOne($sql, [$id]);

if (!$event) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$pageTitle = Security::escape($event['title']) . ' - Alanya TEKMER';
$metaDescription = mb_substr(strip_tags($event['description']), 0, 160) . '...';
$currentPage = 'events';

logPageView('event_detail', $id);

require_once __DIR__ . '/../includes/header.php';

// Parse photos
$photos = json_decode($event['photos'], true) ?? [];
$mainPhoto = !empty($photos) ? $photos[0] : null;
$eventTypeLabel = $event['type'] === 'etkinlik' ? 'Etkinlik' : 'Duyuru';
$eventTypeIcon = $event['type'] === 'etkinlik' ? 'fa-calendar-check' : 'fa-bullhorn';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content" data-aos="fade-up">
            <h1 class="page-title"><?php echo Security::escape($event['title']); ?></h1>
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Ana Sayfa</a>
                <span>/</span>
                <a href="<?php echo url('etkinlikler'); ?>">Etkinlikler</a>
                <span>/</span>
                <span><?php echo Security::escape($event['title']); ?></span>
            </nav>
        </div>
    </div>
</section>

<section class="event-detail-section">
    <div class="container">
        <div class="event-detail-grid">
            <!-- Main Content -->
            <div class="event-main-content" data-aos="fade-up">
                <div class="event-hero-image">
                    <?php if ($mainPhoto): ?>
                        <img src="<?php echo getUploadUrl($mainPhoto); ?>" alt="<?php echo Security::escape($event['title']); ?>">
                    <?php else: ?>
                        <div class="event-placeholder">
                            <i class="fas <?php echo $eventTypeIcon; ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="event-date-badge large">
                        <i class="fas fa-calendar-alt"></i>
                        <time datetime="<?php echo date('Y-m-d', strtotime($event['event_date'])); ?>">
                            <?php echo formatDate($event['event_date']); ?>
                        </time>
                    </div>
                </div>

                <div class="event-body">
                    <div class="event-meta">
                        <span class="meta-item">
                            <i class="fas <?php echo $eventTypeIcon; ?>"></i>
                            <?php echo $eventTypeLabel; ?>
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            <?php echo date('H:i', strtotime($event['event_date'])); ?>
                        </span>
                    </div>

                    <div class="event-description-content">
                        <?php echo nl2br(Security::escape($event['description'])); ?>
                    </div>

                    <?php if (count($photos) > 1): ?>
                        <div class="event-gallery">
                            <h3>Etkinlik Görselleri</h3>
                            <div class="gallery-grid">
                                <?php foreach (array_slice($photos, 1) as $photo): ?>
                                    <a href="<?php echo getUploadUrl($photo); ?>" class="gallery-item" target="_blank">
                                        <img src="<?php echo getUploadUrl($photo); ?>" alt="Etkinlik Görseli">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="event-sidebar" data-aos="fade-left" data-aos-delay="100">
                <div class="sidebar-widget share-widget">
                    <h3>Paylaş</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(getCurrentUrl()); ?>" target="_blank" class="share-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(getCurrentUrl()); ?>&text=<?php echo urlencode($event['title']); ?>" target="_blank" class="share-btn twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(getCurrentUrl()); ?>&title=<?php echo urlencode($event['title']); ?>" target="_blank" class="share-btn linkedin">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="whatsapp://send?text=<?php echo urlencode($event['title'] . ' ' . getCurrentUrl()); ?>" target="_blank" class="share-btn whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <div class="sidebar-widget recent-events-widget">
                    <h3>Diğer Etkinlikler</h3>
                    <?php
                    $otherEvents = $db->fetchAll('SELECT * FROM events WHERE id != ? ORDER BY event_date DESC LIMIT 3', [$id]);
                    if ($otherEvents):
                    ?>
                        <div class="mini-events-list">
                            <?php foreach ($otherEvents as $other): 
                                $otherPhotos = json_decode($other['photos'], true) ?? [];
                                $otherMainPhoto = !empty($otherPhotos) ? $otherPhotos[0] : null;
                            ?>
                                <a href="<?php echo url('etkinlik/' . $other['id']); ?>" class="mini-event-card">
                                    <div class="mini-event-image">
                                        <?php if ($otherMainPhoto): ?>
                                            <img src="<?php echo getUploadUrl($otherMainPhoto); ?>" alt="<?php echo Security::escape($other['title']); ?>">
                                        <?php else: ?>
                                            <div class="mini-placeholder">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mini-event-info">
                                        <h4><?php echo Security::escape($other['title']); ?></h4>
                                        <span class="date"><?php echo formatDate($other['event_date']); ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-content">Başka etkinlik bulunmuyor.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.event-detail-section {
    padding: 60px 0;
    background-color: #f8fafc;
}

.event-detail-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
}

.event-main-content {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.event-hero-image {
    position: relative;
    width: 100%;
    height: 400px;
    background: #f1f5f9;
}

.event-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.event-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.event-placeholder i {
    font-size: 5rem;
    color: white;
    opacity: 0.5;
}

.event-date-badge.large {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: white;
    padding: 10px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    color: #2563eb;
    font-weight: 600;
    font-size: 1.1rem;
}

.event-body {
    padding: 40px;
}

.event-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-weight: 500;
}

.meta-item i {
    color: #2563eb;
}

.event-description-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #334155;
    margin-bottom: 40px;
}

.event-gallery h3 {
    font-size: 1.5rem;
    margin-bottom: 20px;
    color: #1e293b;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}

.gallery-item {
    height: 150px;
    border-radius: 12px;
    overflow: hidden;
    display: block;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.1);
}

/* Sidebar */
.sidebar-widget {
    background: white;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.sidebar-widget h3 {
    font-size: 1.2rem;
    color: #1e293b;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f1f5f9;
}

.share-buttons {
    display: flex;
    gap: 10px;
}

.share-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.share-btn:hover {
    transform: translateY(-3px);
}

.share-btn.facebook { background: #1877f2; }
.share-btn.twitter { background: #1da1f2; }
.share-btn.linkedin { background: #0077b5; }
.share-btn.whatsapp { background: #25d366; }

.mini-events-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.mini-event-card {
    display: flex;
    gap: 15px;
    text-decoration: none;
    padding: 10px;
    border-radius: 12px;
    transition: background 0.3s ease;
}

.mini-event-card:hover {
    background: #f8fafc;
}

.mini-event-image {
    width: 70px;
    height: 70px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}

.mini-event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.mini-placeholder {
    width: 100%;
    height: 100%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
}

.mini-event-info h4 {
    font-size: 0.95rem;
    color: #1e293b;
    margin: 0 0 5px 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.mini-event-info .date {
    font-size: 0.85rem;
    color: #64748b;
}

@media (max-width: 992px) {
    .event-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .event-hero-image {
        height: 300px;
    }
}

@media (max-width: 768px) {
    .event-body {
        padding: 25px;
    }
    
    .event-hero-image {
        height: 250px;
    }
    
    .event-date-badge.large {
        bottom: 15px;
        left: 15px;
        padding: 8px 16px;
        font-size: 1rem;
    }
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
