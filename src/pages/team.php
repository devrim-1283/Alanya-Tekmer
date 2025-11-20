<?php
$pageTitle = 'Ekibimiz - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER ekip üyelerimiz ile tanışın.';
$currentPage = 'about';

logPageView('team');

// Get team members from database with cache
$team = CacheHelper::getOrSet('tekmer:data:team', function() {
    $db = Database::getInstance();
    return $db->fetchAll('SELECT * FROM team ORDER BY sort_order ASC, id ASC');
}, (int)(getenv('CACHE_TTL_STATIC') ?: 86400));

include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Ekibimiz</h1>
        <p>Alanya TEKMER ekip üyeleri</p>
    </div>
</section>

<section class="team-section">
    <div class="container">
        <?php if (!empty($team)): ?>
            <div class="team-grid">
                <?php foreach ($team as $member): ?>
                    <div class="team-card">
                        <div class="team-photo">
                            <img src="<?php echo url('uploads/' . Security::escape($member['photo'])); ?>" 
                                 alt="<?php echo Security::escape($member['name']); ?>"
                                 loading="lazy">
                        </div>
                        <div class="team-info">
                            <h3><?php echo Security::escape($member['name']); ?></h3>
                            <p class="team-position"><?php echo Security::escape($member['position']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                <p>Henüz ekip üyesi eklenmemiştir.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Mobile Optimizations for Team Page -->
<style>
@media (max-width: 768px) {
    .team-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .team-card {
        padding: 25px 20px;
    }
    
    .team-avatar {
        width: 100px;
        height: 100px;
    }
    
    .team-name {
        font-size: 1.1rem;
    }
    
    .team-title {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .team-card {
        padding: 20px 15px;
    }
    
    .team-avatar {
        width: 80px;
        height: 80px;
    }
    
    .team-name {
        font-size: 1rem;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>

