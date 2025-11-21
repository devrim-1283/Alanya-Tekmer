<?php
$pageTitle = 'Başvuru Detayı';
$currentAdminPage = 'applications';

$db = Database::getInstance();
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header('Location: ' . url(getenv('ADMIN_PATH') . '/applications'));
    exit;
}

$application = $db->fetchOne('SELECT * FROM applications WHERE id = ?', [$id]);

if (!$application) {
    header('Location: ' . url(getenv('ADMIN_PATH') . '/applications'));
    exit;
}

$success = '';
$error = '';

// Generate CSRF token
$csrfToken = Security::generateCsrfToken();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        
        switch ($_POST['action']) {
            case 'update_status':
                $status = Security::cleanInput($_POST['status']);
                try {
                    $db->execute('UPDATE applications SET status = ? WHERE id = ?', [$status, $id]);
                    $success = 'Durum güncellendi.';
                    // Refresh data
                    $application = $db->fetchOne('SELECT * FROM applications WHERE id = ?', [$id]);
                } catch (Exception $e) {
                    $error = 'Güncelleme başarısız.';
                }
                break;
                
            case 'delete':
                try {
                    error_log("Deleting application ID: $id");
                    
                    // Get file info before deleting
                    $app = $db->fetchOne('SELECT project_file FROM applications WHERE id = ?', [$id]);
                    
                    if (!$app) {
                        throw new Exception("Application not found");
                    }
                    
                    // Delete from database
                    $result = $db->execute('DELETE FROM applications WHERE id = ?', [$id]);
                    error_log("Delete result: " . ($result ? 'success' : 'failed'));
                    
                    // Delete file if exists
                    if ($app['project_file']) {
                        error_log("Attempting to delete file: " . $app['project_file']);
                        
                        // Try UPLOAD_PATH first
                        $uploadPath = getenv('UPLOAD_PATH');
                        if ($uploadPath) {
                            $filePath = $uploadPath . '/' . $app['project_file'];
                            if (file_exists($filePath)) {
                                $deleted = @unlink($filePath);
                                error_log("Deleted from UPLOAD_PATH: " . ($deleted ? 'yes' : 'no'));
                            }
                        }
                        
                        // Try public/uploads
                        $filePath = __DIR__ . '/../../public/uploads/' . $app['project_file'];
                        if (file_exists($filePath)) {
                            $deleted = @unlink($filePath);
                            error_log("Deleted from public/uploads: " . ($deleted ? 'yes' : 'no'));
                        }
                    }
                    
                    error_log("Redirecting to applications list");
                    
                    // Redirect to applications list
                    header('Location: ' . url(getenv('ADMIN_PATH') . '/applications?deleted=1'));
                    exit;
                } catch (Exception $e) {
                    error_log("Delete error: " . $e->getMessage());
                    $error = 'Silme işlemi başarısız: ' . $e->getMessage();
                }
                break;
        }
    }
}

$csrfToken = Security::generateCsrfToken();
include __DIR__ . '/header.php';
?>

<style>
.application-detail {
    max-width: 1200px;
    margin: 0 auto;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    color: var(--gray-600);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.back-button:hover {
    color: var(--primary);
    transform: translateX(-5px);
}

.detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
}

.detail-header h1 {
    margin: 0 0 10px 0;
    font-size: 2rem;
}

.detail-header .meta {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    margin-top: 20px;
    opacity: 0.95;
}

.detail-header .meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-header .meta-item i {
    font-size: 1.2rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.detail-section {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.detail-section h2 {
    margin: 0 0 25px 0;
    font-size: 1.4rem;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 15px;
    border-bottom: 3px solid var(--primary);
}

.detail-section h2 i {
    color: var(--primary);
    font-size: 1.5rem;
}

.info-grid {
    display: grid;
    gap: 20px;
}

.info-item {
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: 15px;
    padding: 15px;
    background: var(--gray-50);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: var(--gray-100);
    transform: translateX(5px);
}

.info-label {
    font-weight: 600;
    color: var(--gray-600);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-label i {
    color: var(--primary);
    width: 20px;
}

.info-value {
    color: var(--gray-800);
    font-weight: 500;
    word-break: break-word;
}

.info-item.full-width {
    grid-column: 1 / -1;
    grid-template-columns: 140px 1fr;
}

.info-item.full-width .info-value {
    line-height: 1.8;
    white-space: pre-wrap;
}

.sidebar-section {
    position: sticky;
    top: 20px;
}

.status-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

.status-card h3 {
    margin: 0 0 20px 0;
    font-size: 1.1rem;
    color: var(--gray-700);
}

.status-badge-large {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 20px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 20px;
}

.status-badge-large.pending {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: white;
}

.status-badge-large.reviewed {
    background: linear-gradient(135deg, #60a5fa, #3b82f6);
    color: white;
}

.status-badge-large.approved {
    background: linear-gradient(135deg, #34d399, #10b981);
    color: white;
}

.status-badge-large.rejected {
    background: linear-gradient(135deg, #f87171, #ef4444);
    color: white;
}

.status-form select {
    width: 100%;
    padding: 12px;
    border: 2px solid var(--gray-200);
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 15px;
    background: white;
}

.status-form select:focus {
    outline: none;
    border-color: var(--primary);
}

.action-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.action-card h3 {
    margin: 0 0 20px 0;
    font-size: 1.1rem;
    color: var(--gray-700);
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-buttons .btn {
    width: 100%;
    padding: 14px 20px;
    font-weight: 600;
    justify-content: center;
}

.timeline {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid var(--gray-100);
}

.timeline-item {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.timeline-content {
    flex: 1;
}

.timeline-content strong {
    display: block;
    color: var(--gray-800);
    margin-bottom: 3px;
}

.timeline-content small {
    color: var(--gray-500);
}

/* Delete Modal Styles */
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

.warning-icon i {
    font-size: 2.5rem;
    color: #dc2626;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 15px rgba(220, 38, 38, 0);
    }
}

.warning-text {
    font-size: 1.3rem;
    font-weight: 700;
    color: #dc2626;
    margin-bottom: 20px;
}

.delete-info {
    font-size: 1.1rem;
    color: var(--gray-700);
    margin-bottom: 15px;
    line-height: 1.6;
}

.delete-info strong {
    color: var(--gray-900);
    display: block;
    margin-top: 10px;
}

.delete-sub-info {
    color: var(--gray-600);
    margin-bottom: 20px;
}

.delete-sub-info strong {
    color: var(--gray-800);
}

.delete-note {
    background: #fef3c7;
    border: 2px solid #fbbf24;
    border-radius: 10px;
    padding: 15px;
    color: #92400e;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}

.delete-note i {
    font-size: 1.2rem;
    color: #f59e0b;
}

.modal-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    justify-content: center;
}

.modal-actions .btn {
    min-width: 140px;
}

@media (max-width: 968px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .sidebar-section {
        position: relative;
        top: 0;
    }
    
    .info-item {
        grid-template-columns: 1fr;
    }
    
    .info-item.full-width {
        grid-template-columns: 1fr;
    }
    
    .modal-actions {
        flex-direction: column;
    }
    
    .modal-actions .btn {
        width: 100%;
    }
}
</style>

<?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $success; ?>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="application-detail">
    <a href="<?php echo url(getenv('ADMIN_PATH') . '/applications'); ?>" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Başvurulara Dön
    </a>
    
    <div class="detail-header">
        <h1><?php echo Security::escape($application['project_name']); ?></h1>
        <div class="meta">
            <div class="meta-item">
                <i class="fas fa-user"></i>
                <span><?php echo Security::escape($application['full_name']); ?></span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span><?php echo formatDate($application['created_at'], 'd.m.Y H:i'); ?></span>
            </div>
            <div class="meta-item">
                <i class="fas fa-envelope"></i>
                <span><?php echo Security::escape($application['email']); ?></span>
            </div>
            <div class="meta-item">
                <i class="fas fa-phone"></i>
                <span><?php echo formatPhone($application['phone']); ?></span>
            </div>
        </div>
    </div>
    
    <div class="detail-grid">
        <!-- Main Content -->
        <div>
            <!-- Proje Bilgileri -->
            <div class="detail-section">
                <h2><i class="fas fa-project-diagram"></i> Proje Bilgileri</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-tag"></i>
                            Proje Türü
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['project_type']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-industry"></i>
                            Faaliyet Alanı
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['activity_area']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-map-marked-alt"></i>
                            Talep Edilen Alan
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['requested_space']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-users"></i>
                            Ekip Büyüklüğü
                        </div>
                        <div class="info-value"><?php echo $application['team_size']; ?> kişi</div>
                    </div>
                    
                    <div class="info-item full-width">
                        <div class="info-label">
                            <i class="fas fa-align-left"></i>
                            Proje Özeti
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['project_summary']); ?></div>
                    </div>
                    
                    <?php if ($application['expectations']): ?>
                    <div class="info-item full-width">
                        <div class="info-label">
                            <i class="fas fa-bullseye"></i>
                            Beklentiler
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['expectations']); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Kişisel Bilgiler -->
            <div class="detail-section" style="margin-top: 30px;">
                <h2><i class="fas fa-user-circle"></i> Başvuran Bilgileri</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-id-card"></i>
                            TC Kimlik No
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['tc_number']); ?></div>
                    </div>
                    
                    <?php if ($application['university']): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-graduation-cap"></i>
                            Üniversite
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['university']); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($application['department']): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-book"></i>
                            Bölüm
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['department']); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($application['company_name']): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-building"></i>
                            Firma Adı
                        </div>
                        <div class="info-value"><?php echo Security::escape($application['company_name']); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar-section">
            <!-- Status Card -->
            <div class="status-card">
                <h3><i class="fas fa-info-circle"></i> Başvuru Durumu</h3>
                
                <?php
                $statusLabels = [
                    'pending' => 'Bekleyen',
                    'reviewed' => 'İncelendi',
                    'approved' => 'Onaylandı',
                    'rejected' => 'Reddedildi'
                ];
                $statusIcons = [
                    'pending' => 'fa-clock',
                    'reviewed' => 'fa-eye',
                    'approved' => 'fa-check-circle',
                    'rejected' => 'fa-times-circle'
                ];
                ?>
                
                <div class="status-badge-large <?php echo $application['status']; ?>">
                    <i class="fas <?php echo $statusIcons[$application['status']]; ?>"></i>
                    <?php echo $statusLabels[$application['status']]; ?>
                </div>
                
                <form method="POST" class="status-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <input type="hidden" name="action" value="update_status">
                    <label style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px; display: block;">
                        Durumu Değiştir:
                    </label>
                    <select name="status" onchange="this.form.submit()">
                        <option value="pending" <?php echo $application['status'] === 'pending' ? 'selected' : ''; ?>>Bekleyen</option>
                        <option value="reviewed" <?php echo $application['status'] === 'reviewed' ? 'selected' : ''; ?>>İncelendi</option>
                        <option value="approved" <?php echo $application['status'] === 'approved' ? 'selected' : ''; ?>>Onaylandı</option>
                        <option value="rejected" <?php echo $application['status'] === 'rejected' ? 'selected' : ''; ?>>Reddedildi</option>
                    </select>
                </form>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="timeline-content">
                            <strong>Başvuru Tarihi</strong>
                            <small><?php echo formatDate($application['created_at'], 'd.m.Y H:i'); ?></small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="timeline-content">
                            <strong>Son Güncelleme</strong>
                            <small><?php echo formatDate($application['updated_at'], 'd.m.Y H:i'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions Card -->
            <div class="action-card">
                <h3><i class="fas fa-cogs"></i> İşlemler</h3>
                <div class="action-buttons">
                    <?php if ($application['project_file']): ?>
                        <a href="<?php echo url('uploads/' . $application['project_file']); ?>" class="btn btn-success" download>
                            <i class="fas fa-download"></i>
                            Proje Dosyasını İndir
                        </a>
                    <?php endif; ?>
                    
                    <a href="mailto:<?php echo Security::escape($application['email']); ?>" class="btn btn-info">
                        <i class="fas fa-envelope"></i>
                        E-posta Gönder
                    </a>
                    
                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $application['phone']); ?>" class="btn btn-secondary">
                        <i class="fas fa-phone"></i>
                        Telefon Et
                    </a>
                    
                    <button onclick="confirmDelete(<?php echo $application['id']; ?>, '<?php echo addslashes(Security::escape($application['project_name'])); ?>', '<?php echo addslashes(Security::escape($application['full_name'])); ?>')" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Başvuruyu Sil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-content modal-sm">
        <div class="modal-header danger">
            <h2><i class="fas fa-exclamation-triangle"></i> Başvuruyu Sil</h2>
            <button class="modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="delete-warning">
                <div class="warning-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <p class="warning-text">Bu işlem geri alınamaz!</p>
                <p class="delete-info">
                    <strong id="deleteProjectName"></strong> projesine ait başvuruyu silmek üzeresiniz.
                </p>
                <p class="delete-sub-info">
                    Başvuru sahibi: <strong id="deleteFullName"></strong>
                </p>
                <p class="delete-note">
                    <i class="fas fa-info-circle"></i>
                    Başvuruya ait tüm veriler ve dosyalar kalıcı olarak silinecektir.
                </p>
            </div>
            
            <div class="modal-actions">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> İptal
                </button>
                <button type="button" onclick="submitDelete()" class="btn btn-danger btn-lg">
                    <i class="fas fa-trash"></i> Evet, Sil
                </button>
            </div>
            
            <form method="POST" id="deleteForm" style="display: none;">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="action" value="delete">
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, projectName, fullName) {
    console.log('Delete ID:', id, 'Project:', projectName, 'Name:', fullName);
    document.getElementById('deleteProjectName').textContent = projectName;
    document.getElementById('deleteFullName').textContent = fullName;
    document.getElementById('deleteModal').classList.add('active');
}

function submitDelete() {
    // Submit the form
    document.getElementById('deleteForm').submit();
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

<?php include __DIR__ . '/footer.php'; ?>

