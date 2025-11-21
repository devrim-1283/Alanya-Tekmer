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
    'page_views_today' => $db->fetchOne('SELECT COUNT(*) as count FROM page_views WHERE DATE(created_at) = CURRENT_DATE')['count'],
    'unique_visitors_today' => $db->fetchOne('SELECT COUNT(DISTINCT unique_ip_hash) as count FROM page_views WHERE DATE(created_at) = CURRENT_DATE')['count'],
    'total_page_views' => $db->fetchOne('SELECT COUNT(*) as count FROM page_views')['count'],
    'total_unique_visitors' => $db->fetchOne('SELECT COUNT(DISTINCT unique_ip_hash) as count FROM page_views')['count'],
];

// Recent applications
$recentApplications = $db->fetchAll('SELECT * FROM applications ORDER BY created_at DESC LIMIT 10');

// Get last 7 days stats for trend
$last7Days = $db->fetchAll('
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as views,
        COUNT(DISTINCT unique_ip_hash) as unique_visitors
    FROM page_views 
    WHERE created_at >= NOW() - INTERVAL \'7 days\'
    GROUP BY DATE(created_at)
    ORDER BY date DESC
');

// Recent page views
$topPages = $db->fetchAll('
    SELECT page, COUNT(*) as views 
    FROM page_views 
    WHERE created_at >= NOW() - INTERVAL \'7 days\'
    GROUP BY page 
    ORDER BY views DESC 
    LIMIT 10
');

// Page names mapping
$pageNames = [
    'home' => 'Ana Sayfa',
    'about' => 'Hakkımızda',
    'services' => 'Hizmetlerimiz',
    'application' => 'Başvuru',
    'companies' => 'Firmalar',
    'team' => 'Ekibimiz',
    'events' => 'Etkinlikler & Duyurular',
    'gallery' => 'Galeri',
    'contact' => 'İletişim',
    'terms' => 'Kullanım Koşulları',
    'privacy' => 'Gizlilik Politikası',
    'kvkk' => 'KVKK',
    'mevzuat' => 'Mevzuat',
    '404' => '404 - Sayfa Bulunamadı',
];

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
        <div class="stat-icon success"><i class="fas fa-eye"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['page_views_today']); ?></h3>
            <p>Bugünkü Görüntülenme</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info"><i class="fas fa-user-friends"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['unique_visitors_today']); ?></h3>
            <p>Bugünkü Tekil Ziyaretçi</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['total_page_views']); ?></h3>
            <p>Toplam Görüntülenme</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['total_unique_visitors']); ?></h3>
            <p>Toplam Tekil Ziyaretçi</p>
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
                                <tr style="cursor: pointer;" onclick="window.location='<?php echo url(getenv('ADMIN_PATH') . '/applications'); ?>'">
                                    <td>
                                        <i class="fas fa-folder" style="color: var(--primary); margin-right: 8px;"></i>
                                        <strong><?php echo Security::escape($app['project_name']); ?></strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-user" style="color: var(--gray-400); margin-right: 5px; font-size: 0.85rem;"></i>
                                        <?php echo Security::escape($app['full_name']); ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-clock" style="color: var(--gray-400); margin-right: 5px; font-size: 0.85rem;"></i>
                                        <?php echo formatDate($app['created_at'], 'd.m.Y H:i'); ?>
                                    </td>
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
                            <?php foreach ($topPages as $page): 
                                $pageName = $pageNames[$page['page']] ?? ucfirst($page['page']);
                            ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-file-alt" style="color: var(--primary); margin-right: 8px;"></i>
                                        <?php echo Security::escape($pageName); ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary"><?php echo number_format($page['views']); ?> görüntülenme</span>
                                    </td>
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

<!-- Weekly Trend -->
<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar"></i> Son 7 Gün Trend</h3>
    </div>
    <div class="card-body">
        <?php if (!empty($last7Days)): ?>
            <div class="trend-stats">
                <?php foreach (array_reverse($last7Days) as $day): ?>
                    <div class="trend-item">
                        <div class="trend-date"><?php echo formatDate($day['date'], 'd M'); ?></div>
                        <div class="trend-bar">
                            <div class="trend-bar-fill" style="width: <?php echo min(100, ($day['views'] / max(array_column($last7Days, 'views'))) * 100); ?>%;">
                                <span class="trend-value"><?php echo number_format($day['views']); ?></span>
                            </div>
                        </div>
                        <div class="trend-unique">
                            <i class="fas fa-user"></i> <?php echo number_format($day['unique_visitors']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-data">Henüz veri bulunmamaktadır.</p>
        <?php endif; ?>
    </div>
</div>

<style>
.trend-stats {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.trend-item {
    display: grid;
    grid-template-columns: 80px 1fr 120px;
    gap: 15px;
    align-items: center;
    padding: 10px;
    background: var(--gray-50);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.trend-item:hover {
    background: var(--gray-100);
    transform: translateX(5px);
}

.trend-date {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9rem;
}

.trend-bar {
    background: var(--gray-200);
    height: 32px;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
}

.trend-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 10px;
    transition: width 0.5s ease;
    min-width: 50px;
}

.trend-value {
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
}

.trend-unique {
    text-align: right;
    color: var(--gray-600);
    font-size: 0.9rem;
}

.trend-unique i {
    color: var(--info);
    margin-right: 5px;
}

@media (max-width: 768px) {
    .trend-item {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .trend-unique {
        text-align: left;
    }
}
</style>

<?php include __DIR__ . '/footer.php'; ?>

