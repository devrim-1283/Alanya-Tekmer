<?php
$pageTitle = 'Bildirimler';
$currentAdminPage = 'notifications';

$db = Database::getInstance();
$success = '';
$error = '';

// Generate CSRF token
$csrfToken = Security::generateCsrfToken();

// Handle actions (delete, acknowledge, mark as read)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $id = (int)$_POST['id'];
        
        switch ($_POST['action']) {
            case 'delete':
                try {
                    $db->execute('DELETE FROM notifications WHERE id = ?', [$id]);
                    $success = 'Bildirim silindi.';
                } catch (Exception $e) {
                    $error = 'Bildirim silinirken hata oluştu.';
                }
                break;
                
            case 'acknowledge':
                try {
                    $db->execute('UPDATE notifications SET is_acknowledged = TRUE, is_read = TRUE WHERE id = ?', [$id]);
                    $success = 'Bildirim onaylandı.';
                } catch (Exception $e) {
                    $error = 'Bildirim onaylanırken hata oluştu.';
                }
                break;
                
            case 'mark_read':
                try {
                    $db->execute('UPDATE notifications SET is_read = TRUE WHERE id = ?', [$id]);
                } catch (Exception $e) {
                    $error = 'İşlem başarısız.';
                }
                break;
                
            case 'mark_all_read':
                try {
                    $db->execute('UPDATE notifications SET is_read = TRUE WHERE is_read = FALSE');
                    $success = 'Tüm bildirimler okundu olarak işaretlendi.';
                } catch (Exception $e) {
                    $error = 'İşlem başarısız.';
                }
                break;
                
            case 'delete_acknowledged':
                try {
                    $db->execute('DELETE FROM notifications WHERE is_acknowledged = TRUE');
                    $success = 'Onaylanmış bildirimler silindi.';
                } catch (Exception $e) {
                    $error = 'Silme işlemi başarısız.';
                }
                break;
        }
    }
}

// Get filter
$filter = $_GET['filter'] ?? 'all';
$query = 'SELECT * FROM notifications';
$params = [];

switch ($filter) {
    case 'unread':
        $query .= ' WHERE is_read = FALSE';
        break;
    case 'acknowledged':
        $query .= ' WHERE is_acknowledged = TRUE';
        break;
    case 'unacknowledged':
        $query .= ' WHERE is_acknowledged = FALSE';
        break;
    case 'applications':
        $query .= ' WHERE type = ?';
        $params[] = 'new_application';
        break;
}

$query .= ' ORDER BY created_at DESC';
$notifications = $db->fetchAll($query, $params);

// Count statistics
$stats = [
    'total' => $db->fetchOne('SELECT COUNT(*) as count FROM notifications')['count'],
    'unread' => $db->fetchOne('SELECT COUNT(*) as count FROM notifications WHERE is_read = FALSE')['count'],
    'unacknowledged' => $db->fetchOne('SELECT COUNT(*) as count FROM notifications WHERE is_acknowledged = FALSE')['count'],
    'applications' => $db->fetchOne('SELECT COUNT(*) as count FROM notifications WHERE type = ?', ['new_application'])['count']
];

$csrfToken = Security::generateCsrfToken();
include __DIR__ . '/header.php';
?>

<style>
.notification-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 20px;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.stat-card.green {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.stat-card.orange {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card.blue {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card h3 {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 10px;
    font-weight: 500;
}

.stat-card .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-card .stat-icon {
    font-size: 2rem;
    opacity: 0.3;
    float: right;
    margin-top: -50px;
}

.filter-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.filter-actions .btn {
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.filter-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.notification-item {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
    border-left: 5px solid #e9ecef;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    border-left-width: 5px;
}

.notification-item:hover {
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
    transform: translateY(-3px);
}

.notification-item.unread {
    border-left-color: #667eea;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    border-color: #d6dbf5;
}

.notification-item.new_application {
    border-left-color: #28a745;
}

.notification-item.acknowledged {
    opacity: 0.7;
    border-left-color: #28a745;
    background: #fafbfc;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.notification-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.notification-item:hover .notification-icon {
    transform: scale(1.1);
}

.notification-icon.new_application {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #28a745;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
}

.notification-icon.contact_form {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    color: #0c5460;
    box-shadow: 0 2px 8px rgba(12, 84, 96, 0.2);
}

.notification-icon.system {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
    color: #856404;
    box-shadow: 0 2px 8px rgba(133, 100, 4, 0.2);
}

.notification-title h4 {
    margin: 0;
    font-size: 1.15rem;
    color: #2c3e50;
    font-weight: 600;
    line-height: 1.4;
}

.notification-badges {
    display: flex;
    gap: 8px;
}

.badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge.badge-new {
    background: #667eea;
    color: white;
}

.badge.badge-success {
    background: #28a745;
    color: white;
}

.notification-message {
    margin: 10px 0;
    color: #666;
    line-height: 1.6;
}

.notification-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.notification-time {
    font-size: 0.85rem;
    color: #999;
}

.notification-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.notification-actions form {
    display: inline-block;
    margin: 0;
}

.notification-actions .btn {
    white-space: nowrap;
    min-width: auto;
    padding: 8px 16px;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.notification-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.notification-actions .btn i {
    font-size: 0.9rem;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #ddd;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .filter-actions {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    
    .filter-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .notification-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .notification-badges {
        flex-wrap: wrap;
    }
    
    .notification-footer {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .notification-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .notification-actions .btn {
        flex: 1;
        min-width: 80px;
        font-size: 0.8rem;
        padding: 8px 10px;
    }
    
    .notification-actions .btn i {
        font-size: 0.9rem;
    }
    
    .notification-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .notification-stats {
        grid-template-columns: 1fr;
    }
    
    .notification-title h4 {
        font-size: 1rem;
    }
    
    .stat-card {
        padding: 15px;
    }
    
    .stat-card .stat-number {
        font-size: 2rem;
    }
}
</style>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo Security::escape($success); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo Security::escape($error); ?></div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="notification-stats">
    <div class="stat-card">
        <h3>Toplam Bildirim</h3>
        <div class="stat-number"><?php echo $stats['total']; ?></div>
        <div class="stat-icon"><i class="fas fa-bell"></i></div>
    </div>
    
    <div class="stat-card orange">
        <h3>Okunmamış</h3>
        <div class="stat-number"><?php echo $stats['unread']; ?></div>
        <div class="stat-icon"><i class="fas fa-envelope"></i></div>
    </div>
    
    <div class="stat-card green">
        <h3>Yeni Başvurular</h3>
        <div class="stat-number"><?php echo $stats['applications']; ?></div>
        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
    </div>
    
    <div class="stat-card blue">
        <h3>Onay Bekleyen</h3>
        <div class="stat-number"><?php echo $stats['unacknowledged']; ?></div>
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
    </div>
</div>

<!-- Filter and Actions -->
<div class="card">
    <div class="card-header">
        <h3>Bildirimler</h3>
    </div>
    <div class="card-body">
        <div class="filter-actions">
            <div class="filter-buttons">
                <a href="?filter=all" class="btn btn-sm <?php echo $filter === 'all' ? 'btn-primary' : 'btn-secondary'; ?>">
                    <i class="fas fa-list"></i> Tümü
                </a>
                <a href="?filter=unread" class="btn btn-sm <?php echo $filter === 'unread' ? 'btn-primary' : 'btn-secondary'; ?>">
                    <i class="fas fa-envelope"></i> Okunmamış
                </a>
                <a href="?filter=unacknowledged" class="btn btn-sm <?php echo $filter === 'unacknowledged' ? 'btn-primary' : 'btn-secondary'; ?>">
                    <i class="fas fa-clock"></i> Onay Bekleyen
                </a>
                <a href="?filter=applications" class="btn btn-sm <?php echo $filter === 'applications' ? 'btn-primary' : 'btn-secondary'; ?>">
                    <i class="fas fa-file-alt"></i> Başvurular
                </a>
                <a href="?filter=acknowledged" class="btn btn-sm <?php echo $filter === 'acknowledged' ? 'btn-primary' : 'btn-secondary'; ?>">
                    <i class="fas fa-check-circle"></i> Onaylanmış
                </a>
            </div>
            
            <div style="margin-left: auto; display: flex; gap: 10px;">
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <input type="hidden" name="action" value="mark_all_read">
                    <button type="submit" class="btn btn-sm btn-info">
                        <i class="fas fa-check-double"></i> Tümünü Okundu İşaretle
                    </button>
                </form>
                
                <form method="POST" style="display: inline;" onsubmit="return confirm('Onaylanmış tüm bildirimler silinecek. Emin misiniz?');">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <input type="hidden" name="action" value="delete_acknowledged">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fas fa-trash-alt"></i> Onaylanmışları Temizle
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Notifications List -->
        <div class="notifications-list">
            <?php if (empty($notifications)): ?>
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <h3>Bildirim Bulunamadı</h3>
                    <p>Seçili filtreye göre gösterilecek bildirim yok.</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                    <div class="notification-item <?php echo $notif['is_read'] ? '' : 'unread'; ?> <?php echo $notif['type']; ?> <?php echo $notif['is_acknowledged'] ? 'acknowledged' : ''; ?>">
                        <div class="notification-header">
                            <div class="notification-title">
                                <div class="notification-icon <?php echo $notif['type']; ?>">
                                    <?php
                                    $icons = [
                                        'new_application' => 'fa-file-alt',
                                        'contact_form' => 'fa-envelope',
                                        'system' => 'fa-cog',
                                        'other' => 'fa-bell'
                                    ];
                                    echo '<i class="fas ' . ($icons[$notif['type']] ?? 'fa-bell') . '"></i>';
                                    ?>
                                </div>
                                <div>
                                    <h4><?php echo Security::escape($notif['title']); ?></h4>
                                </div>
                            </div>
                            <div class="notification-badges">
                                <?php if (!$notif['is_read']): ?>
                                    <span class="badge badge-new">Yeni</span>
                                <?php endif; ?>
                                <?php if ($notif['is_acknowledged']): ?>
                                    <span class="badge badge-success">Onaylandı</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="notification-message">
                            <?php echo Security::escape($notif['message']); ?>
                        </div>
                        
                        <div class="notification-footer">
                            <div class="notification-time">
                                <i class="fas fa-clock"></i>
                                <?php echo formatDate($notif['created_at'], 'd.m.Y H:i'); ?>
                            </div>
                            
                            <div class="notification-actions">
                                <?php if ($notif['reference_type'] === 'application' && $notif['reference_id']): ?>
                                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/applications'); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Başvuruyu Gör
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!$notif['is_acknowledged']): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                        <input type="hidden" name="action" value="acknowledge">
                                        <input type="hidden" name="id" value="<?php echo $notif['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Onayla
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Bu bildirimi silmek istediğinizden emin misiniz?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $notif['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>

