<?php
$pageTitle = 'Başvuru Yönetimi';
$currentAdminPage = 'applications';

$db = Database::getInstance();
$success = '';
$error = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $id = (int)$_POST['id'];
        $status = Security::cleanInput($_POST['status']);
        
        try {
            $db->execute('UPDATE applications SET status = ? WHERE id = ?', [$status, $id]);
            $success = 'Durum güncellendi.';
        } catch (Exception $e) {
            $error = 'Güncelleme başarısız.';
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
        <table class="table">
            <thead>
                <tr>
                    <th>Proje Adı</th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Telefon</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th>İşlem</th>
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
                            <button onclick="viewApplication(<?php echo $app['id']; ?>)" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detay
                            </button>
                            <?php if ($app['project_file']): ?>
                                <a href="<?php echo url('uploads/' . $app['project_file']); ?>" class="btn btn-sm btn-secondary" download>
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function viewApplication(id) {
    // Simple implementation - could be modal
    alert('Detay görüntüleme için modal eklenebilir. ID: ' + id);
}
</script>

<?php include __DIR__ . '/footer.php'; ?>

