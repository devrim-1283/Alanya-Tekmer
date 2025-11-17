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
        <!-- Filter -->
        <div class="events-filter">
            <a href="<?php echo url('etkinlikler?type=all'); ?>" 
               class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">
                Tümü
            </a>
            <a href="<?php echo url('etkinlikler?type=etkinlik'); ?>" 
               class="filter-btn <?php echo $filter === 'etkinlik' ? 'active' : ''; ?>">
                Etkinlikler
            </a>
            <a href="<?php echo url('etkinlikler?type=duyuru'); ?>" 
               class="filter-btn <?php echo $filter === 'duyuru' ? 'active' : ''; ?>">
                Duyurular
            </a>
        </div>
        
        <?php if (!empty($events)): ?>
            <div class="events-grid">
                <?php foreach ($events as $event): ?>
                    <?php
                    $photos = json_decode($event['photos'], true) ?? [];
                    $mainPhoto = !empty($photos) ? $photos[0] : 'logo.png';
                    ?>
                    <div class="event-card">
                        <div class="event-image">
                            <img src="<?php echo url('uploads/' . Security::escape($mainPhoto)); ?>" 
                                 alt="<?php echo Security::escape($event['title']); ?>"
                                 loading="lazy">
                            <span class="event-badge <?php echo $event['type']; ?>">
                                <?php echo $event['type'] === 'etkinlik' ? 'Etkinlik' : 'Duyuru'; ?>
                            </span>
                        </div>
                        <div class="event-content">
                            <div class="event-date">
                                <i class="fas fa-calendar"></i>
                                <?php echo formatDate($event['event_date']); ?>
                            </div>
                            <h3><?php echo Security::escape($event['title']); ?></h3>
                            <div class="event-description">
                                <?php echo nl2br(Security::escape(truncate($event['description'], 200))); ?>
                            </div>
                            <?php if (count($photos) > 1): ?>
                                <p class="event-photos-count">
                                    <i class="fas fa-images"></i> <?php echo count($photos); ?> fotoğraf
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-calendar-times"></i>
                <p>Henüz <?php echo $filter === 'etkinlik' ? 'etkinlik' : ($filter === 'duyuru' ? 'duyuru' : 'içerik'); ?> eklenmemiştir.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

