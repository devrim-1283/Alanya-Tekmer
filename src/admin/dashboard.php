<?php
$pageTitle = 'Dashboard';
$currentAdminPage = 'dashboard';

$db = Database::getInstance();

// Get statistics
$stats = [
    'applications' => $db->fetchOne('SELECT COUNT(*) as count FROM applications')['count'],
    'pending_applications' => $db->fetchOne('SELECT COUNT(*) as count FROM applications WHERE status = ?', ['pending'])['count'],
    'companies' => $db->fetchOne('SELECT COUNT(*) as count FROM companies WHERE is_active = true')['count'],
    'events' => $db->fetchOne('SELECT COUNT(*) as count FROM events')['count'],
    'team_members' => $db->fetchOne('SELECT COUNT(*) as count FROM team')['count'],
    'page_views_today' => $db->fetchOne('SELECT COUNT(*) as count FROM page_views WHERE DATE(created_at) = CURRENT_DATE')['count'],
    'unique_visitors_today' => $db->fetchOne('SELECT COUNT(DISTINCT unique_ip_hash) as count FROM page_views WHERE DATE(created_at) = CURRENT_DATE')['count'],
];

// Recent applications
$recentApplications = $db->fetchAll('SELECT * FROM applications ORDER BY created_at DESC LIMIT 5');

// Recent page views
$topPages = $db->fetchAll('
    SELECT page, COUNT(*) as views 
    FROM page_views 
    WHERE created_at >= NOW() - INTERVAL \'7 days\'
    GROUP BY page 
    ORDER BY views DESC 
    LIMIT 5
');

include __DIR__ . '/header.php';
?>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['applications']; ?></h3>
            <p>Toplam Başvuru</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['pending_applications']; ?></h3>
            <p>Bekleyen Başvuru</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success"><i class="fas fa-building"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['companies']; ?></h3>
            <p>Aktif Firma</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info"><i class="fas fa-calendar"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['events']; ?></h3>
            <p>Etkinlik & Duyuru</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['team_members']; ?></h3>
            <p>Ekip Üyesi</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success"><i class="fas fa-eye"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['page_views_today']; ?></h3>
            <p>Bugünkü Görüntülenme</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info"><i class="fas fa-user-friends"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['unique_visitors_today']; ?></h3>
            <p>Bugünkü Tekil Ziyaretçi</p>
        </div>
    </div>
</div>

<div class="dashboard-row">
    <div class="dashboard-col">
        <div class="card">
            <div class="card-header">
                <h3>Son Başvurular</h3>
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/applications'); ?>" class="btn btn-sm btn-primary">
                    Tümünü Gör
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($recentApplications)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Proje Adı</th>
                                <th>Ad Soyad</th>
                                <th>Tarih</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentApplications as $app): ?>
                                <tr>
                                    <td><?php echo Security::escape($app['project_name']); ?></td>
                                    <td><?php echo Security::escape($app['full_name']); ?></td>
                                    <td><?php echo formatDate($app['created_at'], 'd.m.Y H:i'); ?></td>
                                    <td><?php echo getStatusBadge($app['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">Henüz başvuru bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="dashboard-col">
        <div class="card">
            <div class="card-header">
                <h3>En Çok Ziyaret Edilen Sayfalar (Son 7 Gün)</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($topPages)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sayfa</th>
                                <th>Görüntülenme</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topPages as $page): ?>
                                <tr>
                                    <td><?php echo Security::escape($page['page']); ?></td>
                                    <td><strong><?php echo $page['views']; ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">Henüz veri bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>

