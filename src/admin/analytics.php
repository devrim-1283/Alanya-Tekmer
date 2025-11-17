<?php
$pageTitle = 'Analitikler';
$currentAdminPage = 'analytics';

$db = Database::getInstance();

// Date range filter
$startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Page views by date
$pageViewsByDate = $db->fetchAll('
    SELECT DATE(created_at) as date, COUNT(*) as views, COUNT(DISTINCT unique_ip_hash) as unique_visitors
    FROM page_views
    WHERE DATE(created_at) BETWEEN ? AND ?
    GROUP BY DATE(created_at)
    ORDER BY date DESC
', [$startDate, $endDate]);

// Page views by page
$pageViewsByPage = $db->fetchAll('
    SELECT page, COUNT(*) as views
    FROM page_views
    WHERE DATE(created_at) BETWEEN ? AND ?
    GROUP BY page
    ORDER BY views DESC
    LIMIT 10
', [$startDate, $endDate]);

// Top IPs
$topIps = $db->fetchAll('
    SELECT ip_address, COUNT(*) as visits
    FROM page_views
    WHERE DATE(created_at) BETWEEN ? AND ?
    GROUP BY ip_address
    ORDER BY visits DESC
    LIMIT 10
', [$startDate, $endDate]);

include __DIR__ . '/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Analitikler</h3>
        <form method="GET" class="filter-form">
            <input type="date" name="start_date" value="<?php echo $startDate; ?>">
            <input type="date" name="end_date" value="<?php echo $endDate; ?>">
            <button type="submit" class="btn btn-primary btn-sm">Filtrele</button>
        </form>
    </div>
</div>

<div class="dashboard-row">
    <div class="dashboard-col">
        <div class="card">
            <div class="card-header"><h3>Günlük Görüntülenmeler</h3></div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr><th>Tarih</th><th>Görüntülenme</th><th>Tekil Ziyaretçi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pageViewsByDate as $row): ?>
                            <tr>
                                <td><?php echo formatDate($row['date']); ?></td>
                                <td><?php echo $row['views']; ?></td>
                                <td><?php echo $row['unique_visitors']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="dashboard-col">
        <div class="card">
            <div class="card-header"><h3>Sayfa Bazlı Görüntülenmeler</h3></div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr><th>Sayfa</th><th>Görüntülenme</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pageViewsByPage as $row): ?>
                            <tr>
                                <td><?php echo Security::escape($row['page']); ?></td>
                                <td><strong><?php echo $row['views']; ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header"><h3>En Aktif IP Adresleri</h3></div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr><th>IP Adresi</th><th>Ziyaret</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topIps as $row): ?>
                            <tr>
                                <td><?php echo Security::escape($row['ip_address']); ?></td>
                                <td><strong><?php echo $row['visits']; ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>

