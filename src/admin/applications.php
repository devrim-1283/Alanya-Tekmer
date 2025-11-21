<?php
$pageTitle = 'Başvuru Yönetimi';
$currentAdminPage = 'applications';

$db = Database::getInstance();
$success = '';
$error = '';

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
                
            case 'delete':
                try {
                    // Get file info before deleting
                    $app = $db->fetchOne('SELECT project_file FROM applications WHERE id = ?', [$id]);
                    
                    // Delete from database
                    $db->execute('DELETE FROM applications WHERE id = ?', [$id]);
                    
                    // Delete file if exists
                    if ($app && $app['project_file']) {
                        // Try UPLOAD_PATH first
                        $uploadPath = getenv('UPLOAD_PATH');
                        if ($uploadPath) {
                            $filePath = $uploadPath . '/' . $app['project_file'];
                            if (file_exists($filePath)) {
                                @unlink($filePath);
                            }
                        }
                        
                        // Try public/uploads
                        $filePath = __DIR__ . '/../../public/uploads/' . $app['project_file'];
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                    
                    $success = 'Başvuru silindi.';
                } catch (Exception $e) {
                    $error = 'Silme işlemi başarısız.';
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
                            <button 
                                class="btn btn-sm btn-danger delete-btn" 
                                data-id="<?php echo $app['id']; ?>"
                                data-project="<?php echo Security::escape($app['project_name']); ?>"
                                data-name="<?php echo Security::escape($app['full_name']); ?>">
                                <i class="fas fa-trash"></i> Sil
                            </button>
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
            
            <form method="POST" id="deleteForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteId">
                
                <div class="modal-actions">
                    <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> İptal
                    </button>
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fas fa-trash"></i> Evet, Sil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#deleteModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000 !important;
    background: rgba(0, 0, 0, 0.7);
}

#deleteModal.active {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Force modal styles */
.modal#deleteModal {
    display: none;
}

.modal#deleteModal.active {
    display: flex !important;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 16px;
    max-width: 900px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
    display: flex;
    flex-direction: column;
}

.modal-lg {
    max-width: 1100px;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 25px 30px;
    border-bottom: 2px solid var(--gray-100);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-close {
    width: 40px;
    height: 40px;
    border: none;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.modal-body {
    padding: 30px;
    overflow-y: auto;
    flex: 1;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
    margin-bottom: 30px;
}

.detail-item {
    background: var(--gray-50);
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid var(--primary);
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    font-size: 0.85rem;
    color: var(--gray-500);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-label i {
    color: var(--primary);
}

.detail-value {
    font-size: 1rem;
    color: var(--gray-800);
    font-weight: 500;
    line-height: 1.6;
}

.detail-value.multiline {
    white-space: pre-wrap;
}

.status-badge-large {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.status-badge-large.pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge-large.reviewed {
    background: #d1ecf1;
    color: #0c5460;
}

.status-badge-large.approved {
    background: #d4edda;
    color: #155724;
}

.status-badge-large.rejected {
    background: #f8d7da;
    color: #721c24;
}

.modal-actions {
    display: flex;
    gap: 15px;
    padding: 20px 30px;
    border-top: 2px solid var(--gray-100);
    background: var(--gray-50);
}

.modal-actions .btn {
    flex: 1;
    padding: 12px 24px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .modal-content {
        width: 95%;
        max-height: 95vh;
    }
    
    .modal-header {
        padding: 20px;
    }
    
    .modal-header h2 {
        font-size: 1.2rem;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-actions {
        flex-direction: column;
        padding: 15px;
    }
}
</style>

<script>
// Delete confirmation modal
function confirmDelete(id, projectName, fullName) {
    console.log('confirmDelete called');
    console.log('ID:', id);
    console.log('Project:', projectName);
    console.log('Name:', fullName);
    
    const modal = document.getElementById('deleteModal');
    console.log('Modal element:', modal);
    
    const deleteIdInput = document.getElementById('deleteId');
    const projectNameEl = document.getElementById('deleteProjectName');
    const fullNameEl = document.getElementById('deleteFullName');
    
    console.log('deleteId input:', deleteIdInput);
    console.log('projectName element:', projectNameEl);
    console.log('fullName element:', fullNameEl);
    
    if (deleteIdInput) deleteIdInput.value = id;
    if (projectNameEl) projectNameEl.textContent = projectName;
    if (fullNameEl) fullNameEl.textContent = fullName;
    
    if (modal) {
        modal.classList.add('active');
        console.log('Modal opened');
    } else {
        console.error('Modal not found!');
    }
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// Test function - call this from console: testModal()
window.testModal = function() {
    console.log('Testing modal...');
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.add('active');
        console.log('Modal classes after adding active:', modal.className);
        console.log('Modal display after adding active:', window.getComputedStyle(modal).display);
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Test modal element
    const modal = document.getElementById('deleteModal');
    console.log('Page loaded, modal element:', modal);
    if (!modal) {
        console.error('DELETE MODAL NOT FOUND IN DOM!');
    } else {
        console.log('Modal found successfully');
        console.log('Modal classes:', modal.className);
        console.log('Modal display:', window.getComputedStyle(modal).display);
    }
    
    // Add click listeners to delete buttons
    const deleteButtons = document.querySelectorAll('.delete-btn');
    console.log('Found delete buttons:', deleteButtons.length);
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const project = this.getAttribute('data-project');
            const name = this.getAttribute('data-name');
            
            console.log('Delete button clicked:', { id, project, name });
            confirmDelete(id, project, name);
        });
    });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>

