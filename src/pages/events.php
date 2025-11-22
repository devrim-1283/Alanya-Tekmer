<?php
$pageTitle = 'Etkinlik ve Duyurular - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER etkinlikleri ve duyuruları.';
$currentPage = 'events';

logPageView('events');

// Get filter
$filter = $_GET['type'] ?? 'all';

// Get events from database with cache
$cacheKey = "tekmer:data:events_{$filter}";
$events = CacheHelper::getOrSet($cacheKey, function() use ($filter) {
    $db = Database::getInstance();
    
    if ($filter === 'all') {
        $sql = 'SELECT * FROM events ORDER BY event_date DESC, created_at DESC LIMIT 50';
        return $db->fetchAll($sql);
    } else {
        $sql = 'SELECT * FROM events WHERE type = ? ORDER BY event_date DESC, created_at DESC LIMIT 50';
        return $db->fetchAll($sql, [$filter]);
    }
}, (int)(getenv('CACHE_TTL_DYNAMIC') ?: 900));

include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Etkinlik ve Duyurular</h1>
        <p>Alanya TEKMER güncel etkinlikleri ve duyuruları</p>
    </div>
</section>

<section class="events-section">
    <div class="container">
        <!-- Filter Tabs -->
        <div class="events-filter-tabs">
            <a href="<?php echo url('etkinlikler?type=all'); ?>" 
               class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
                <i class="fas fa-th"></i>
                <span>Tümü</span>
            </a>
            <a href="<?php echo url('etkinlikler?type=etkinlik'); ?>" 
               class="filter-tab <?php echo $filter === 'etkinlik' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Etkinlikler</span>
            </a>
            <a href="<?php echo url('etkinlikler?type=duyuru'); ?>" 
               class="filter-tab <?php echo $filter === 'duyuru' ? 'active' : ''; ?>">
                <i class="fas fa-bullhorn"></i>
                <span>Duyurular</span>
            </a>
        </div>
        
        <?php if (!empty($events)): ?>
            <div class="events-grid">
                <?php foreach ($events as $event): ?>
                    <?php
                    $photos = json_decode($event['photos'], true) ?? [];
                    $mainPhoto = !empty($photos) ? $photos[0] : null;
                    $photoCount = count($photos);
                    $eventTypeLabel = $event['type'] === 'etkinlik' ? 'Etkinlik' : 'Duyuru';
                    $eventTypeIcon = $event['type'] === 'etkinlik' ? 'fa-calendar-check' : 'fa-bullhorn';
                    ?>
                    <article class="event-card">
                        <div class="event-image-wrapper">
                            <?php if ($mainPhoto): ?>
                                <img src="<?php echo getUploadUrl($mainPhoto); ?>" 
                                     alt="<?php echo Security::escape($event['title']); ?>"
                                     class="event-image"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="event-image-placeholder">
                                    <i class="fas <?php echo $eventTypeIcon; ?>"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-badge <?php echo $event['type']; ?>">
                                <i class="fas <?php echo $eventTypeIcon; ?>"></i>
                                <span><?php echo $eventTypeLabel; ?></span>
                            </div>
                            
                            <?php if ($photoCount > 1): ?>
                                <div class="event-photo-count">
                                    <i class="fas fa-images"></i>
                                    <span><?php echo $photoCount; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-content">
                            <div class="event-date-badge">
                                <i class="fas fa-calendar-alt"></i>
                                <time datetime="<?php echo date('Y-m-d', strtotime($event['event_date'])); ?>">
                                    <?php echo formatDate($event['event_date']); ?>
                                </time>
                            </div>
                            
                            <h3 class="event-title">
                                <a href="<?php echo url('etkinlik/' . $event['id']); ?>">
                                    <?php echo Security::escape($event['title']); ?>
                                </a>
                            </h3>
                            
                            <div class="event-description">
                                <?php 
                                $description = Security::escape($event['description']);
                                $truncated = mb_strlen($description) > 150 ? mb_substr($description, 0, 150) . '...' : $description;
                                echo nl2br($truncated);
                                ?>
                            </div>
                            
                            <a href="<?php echo url('etkinlik/' . $event['id']); ?>" class="event-read-more">
                                Devamını Oku
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-events">
                <div class="no-events-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3>Henüz İçerik Yok</h3>
                <p>
                    <?php 
                    if ($filter === 'etkinlik') {
                        echo 'Henüz etkinlik eklenmemiştir.';
                    } elseif ($filter === 'duyuru') {
                        echo 'Henüz duyuru eklenmemiştir.';
                    } else {
                        echo 'Henüz etkinlik veya duyuru eklenmemiştir.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.events-section {
    padding: 60px 0 100px;
    background: linear-gradient(to bottom, #ffffff 0%, #f8fafc 100%);
}

.events-filter-tabs {
    display: flex;
    gap: 15px;
    margin-bottom: 50px;
    justify-content: center;
    flex-wrap: wrap;
}

.filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    color: #6b7280;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.filter-tab i {
    font-size: 1rem;
}

.filter-tab:hover {
    border-color: #2563eb;
    color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
}

.filter-tab.active {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    border-color: #2563eb;
    color: white;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
}

.filter-tab.active:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
}

.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
}

.event-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    position: relative;
}

.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.event-image-wrapper {
    position: relative;
    width: 100%;
    height: 240px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.event-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.event-card:hover .event-image {
    transform: scale(1.1);
}

.event-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.event-image-placeholder i {
    font-size: 4rem;
    color: white;
    opacity: 0.6;
}

.event-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    color: white;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.event-badge.etkinlik {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.9) 0%, rgba(5, 150, 105, 0.9) 100%);
}

.event-badge.duyuru {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.9) 0%, rgba(217, 119, 6, 0.9) 100%);
}

.event-badge i {
    font-size: 0.8rem;
}

.event-photo-count {
    position: absolute;
    bottom: 15px;
    right: 15px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 20px;
    color: white;
    font-size: 0.85rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.event-photo-count i {
    font-size: 0.8rem;
}

.event-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.event-date-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: #f0f9ff;
    color: #0369a1;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 15px;
    width: fit-content;
}

.event-date-badge i {
    font-size: 0.8rem;
}

.event-title {
    margin: 0 0 12px 0;
    font-size: 1.35rem;
    font-weight: 700;
    line-height: 1.4;
    color: #1e293b;
}

.event-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.event-title a:hover {
    color: #2563eb;
}

.event-description {
    color: #64748b;
    font-size: 0.95rem;
    line-height: 1.7;
    margin-bottom: 20px;
    flex: 1;
}

.event-read-more {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #2563eb;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid #e5e7eb;
}

.event-read-more:hover {
    color: #1e40af;
    gap: 12px;
}

.event-read-more i {
    font-size: 0.85rem;
    transition: transform 0.3s ease;
}

.event-read-more:hover i {
    transform: translateX(4px);
}

.no-events {
    text-align: center;
    padding: 100px 20px;
}

.no-events-icon {
    margin-bottom: 25px;
}

.no-events-icon i {
    font-size: 5rem;
    color: #cbd5e1;
}

.no-events h3 {
    font-size: 1.5rem;
    color: #475569;
    margin-bottom: 10px;
}

.no-events p {
    color: #94a3b8;
    font-size: 1rem;
}

/* Tablet */
@media (max-width: 992px) {
    .events-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }
    
    .event-image-wrapper {
        height: 220px;
    }
}

/* Mobile */
@media (max-width: 768px) {
    .events-section {
        padding: 40px 0 80px;
    }
    
    .events-filter-tabs {
        margin-bottom: 35px;
        gap: 10px;
    }
    
    .filter-tab {
        padding: 12px 20px;
        font-size: 0.9rem;
        flex: 1;
        min-width: 100px;
        justify-content: center;
    }
    
    .filter-tab span {
        display: none;
    }
    
    .filter-tab i {
        font-size: 1.1rem;
    }
    
    .events-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .event-image-wrapper {
        height: 200px;
    }
    
    .event-content {
        padding: 20px;
    }
    
    .event-title {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .events-filter-tabs {
        gap: 8px;
    }
    
    .filter-tab {
        padding: 10px 16px;
    }
    
    .event-card {
        border-radius: 16px;
    }
    
    .event-image-wrapper {
        height: 180px;
    }
    
    .event-content {
        padding: 18px;
    }
    
    .event-title {
        font-size: 1.1rem;
    }
    
    .event-description {
        font-size: 0.9rem;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
