<?php
$pageTitle = 'Başvuru Yönetimi';
$currentAdminPage = 'applications';

$db = Database::getInstance();
$success = '';
$error = '';

// Generate CSRF token
$csrfToken = Security::generateCsrfToken();

// Check for success message from redirect
if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $success = 'Başvuru başarıyla silindi.';
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $id = (int)$_POST['id'];
        
        switch ($_POST['action']) {
            case 'update_status':
                $status = Security::cleanInput($_POST['status']);
                try {
                    $db->execute('UPDATE applications SET status = ? WHERE id = ?', [$status, $id]);
                    $success = 'Durum güncellendi.';
                } catch (Exception $e) {
                    $error = 'Güncelleme başarısız.';
                }
                break;
        }
    }
}

// Get applications
$filter = $_GET['status'] ?? 'all';
if ($filter === 'all') {
    $applications = $db->fetchAll('SELECT * FROM applications ORDER BY created_at DESC');
} else {
    $applications = $db->fetchAll('SELECT * FROM applications WHERE status = ? ORDER BY created_at DESC', [$filter]);
}

$csrfToken = Security::generateCsrfToken();
include __DIR__ . '/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo Security::escape($success); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo Security::escape($error); ?></div>
<?php endif; ?>

<!-- Debug: Test Modal Button -->
<button onclick="testModal()" style="position: fixed; bottom: 20px; right: 20px; z-index: 99999; background: red; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
    🧪 TEST MODAL
</button>

<div class="card">
    <div class="card-header">
        <h3>Başvurular</h3>
        <div class="filter-buttons">
            <a href="?status=all" class="btn btn-sm <?php echo $filter === 'all' ? 'btn-primary' : 'btn-secondary'; ?>">Tümü</a>
            <a href="?status=pending" class="btn btn-sm <?php echo $filter === 'pending' ? 'btn-primary' : 'btn-secondary'; ?>">Bekleyen</a>
            <a href="?status=reviewed" class="btn btn-sm <?php echo $filter === 'reviewed' ? 'btn-primary' : 'btn-secondary'; ?>">İncelenen</a>
            <a href="?status=approved" class="btn btn-sm <?php echo $filter === 'approved' ? 'btn-primary' : 'btn-secondary'; ?>">Onaylanan</a>
            <a href="?status=rejected" class="btn btn-sm <?php echo $filter === 'rejected' ? 'btn-primary' : 'btn-secondary'; ?>">Reddedilen</a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($applications)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Başvuru Bulunamadı</h3>
                <p>Seçili filtreye göre gösterilecek başvuru bulunmamaktadır.</p>
            </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Proje Adı</th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Telefon</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th style="width: 300px;">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?php echo Security::escape($app['project_name']); ?></td>
                        <td><?php echo Security::escape($app['full_name']); ?></td>
                        <td><?php echo Security::escape($app['email']); ?></td>
                        <td><?php echo formatPhone($app['phone']); ?></td>
                        <td><?php echo formatDate($app['created_at'], 'd.m.Y'); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                <select name="status" onchange="this.form.submit()" class="select-sm">
                                    <option value="pending" <?php echo $app['status'] === 'pending' ? 'selected' : ''; ?>>Bekleyen</option>
                                    <option value="reviewed" <?php echo $app['status'] === 'reviewed' ? 'selected' : ''; ?>>İncelendi</option>
                                    <option value="approved" <?php echo $app['status'] === 'approved' ? 'selected' : ''; ?>>Onaylandı</option>
                                    <option value="rejected" <?php echo $app['status'] === 'rejected' ? 'selected' : ''; ?>>Reddedildi</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="<?php echo url(getenv('ADMIN_PATH') . '/application/' . $app['id']); ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detay
                            </a>
                            <?php if ($app['project_file']): ?>
                                <a href="<?php echo url('uploads/' . $app['project_file']); ?>" class="btn btn-sm btn-success" download>
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<style>
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--gray-400);
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    color: var(--gray-600);
    margin-bottom: 10px;
}

.empty-state p {
    color: var(--gray-500);
}

/* Delete Modal Styles */
.modal-sm {
    max-width: 500px;
}

.modal-header.danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.delete-warning {
    text-align: center;
    padding: 20px;
}

.warning-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }
}

.warning-icon i {
    font-size: 2.5rem;
    color: var(--danger);
}

.warning-text {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--danger);
    margin-bottom: 20px;
}

.delete-info {
    font-size: 1rem;
    color: var(--gray-700);
    margin-bottom: 10px;
    line-height: 1.6;
}

.delete-sub-info {
    font-size: 0.95rem;
    color: var(--gray-600);
    margin-bottom: 20px;
}

.delete-note {
    background: var(--gray-50);
    padding: 15px;
    border-radius: 10px;
    border-left: 4px solid var(--warning);
    font-size: 0.9rem;
    color: var(--gray-600);
    text-align: left;
    margin-top: 20px;
}

.delete-note i {
    color: var(--warning);
    margin-right: 8px;
}

.modal-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.modal-actions .btn {
    flex: 1;
    padding: 14px 24px;
    font-weight: 600;
    font-size: 1rem;
}
</style>


<?php include __DIR__ . '/footer.php'; ?>

