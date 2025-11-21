<?php
$pageTitle = 'Başvuru Yönetimi';
$currentAdminPage = 'applications';

$db = Database::getInstance();
$success = '';
$error = '';

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
                        $filePath = __DIR__ . '/../../public/uploads/' . $app['project_file'];
                        if (file_exists($filePath)) {
                            unlink($filePath);
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
                            <button onclick="viewApplication(<?php echo htmlspecialchars(json_encode($app)); ?>)" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detay
                            </button>
                            <?php if ($app['project_file']): ?>
                                <a href="<?php echo url('uploads/' . $app['project_file']); ?>" class="btn btn-sm btn-success" download>
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            <?php endif; ?>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Bu başvuruyu silmek istediğinizden emin misiniz?');">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </form>
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
</style>

<!-- Application Detail Modal -->
<div class="modal" id="applicationModal">
    <div class="modal-overlay" onclick="closeApplicationModal()"></div>
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2><i class="fas fa-file-alt"></i> Başvuru Detayları</h2>
            <button class="modal-close" onclick="closeApplicationModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    animation: fadeIn 0.3s ease;
}

.modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
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
function viewApplication(appData) {
    const app = typeof appData === 'string' ? JSON.parse(appData) : appData;
    
    const statusTexts = {
        'pending': 'Bekleyen',
        'reviewed': 'İncelendi',
        'approved': 'Onaylandı',
        'rejected': 'Reddedildi'
    };
    
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-project-diagram"></i>
                    Proje Adı
                </div>
                <div class="detail-value">${escapeHtml(app.project_name)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-tag"></i>
                    Proje Türü
                </div>
                <div class="detail-value">${escapeHtml(app.project_type)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-industry"></i>
                    Faaliyet Alanı
                </div>
                <div class="detail-value">${escapeHtml(app.activity_area)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-map-marked-alt"></i>
                    Talep Edilen Alan
                </div>
                <div class="detail-value">${escapeHtml(app.requested_space)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-user"></i>
                    Ad Soyad
                </div>
                <div class="detail-value">${escapeHtml(app.full_name)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-phone"></i>
                    Telefon
                </div>
                <div class="detail-value">${escapeHtml(app.phone)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-envelope"></i>
                    E-posta
                </div>
                <div class="detail-value">${escapeHtml(app.email)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-id-card"></i>
                    TC Kimlik No
                </div>
                <div class="detail-value">${escapeHtml(app.tc_number)}</div>
            </div>
            
            ${app.university ? `
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-graduation-cap"></i>
                    Üniversite
                </div>
                <div class="detail-value">${escapeHtml(app.university)}</div>
            </div>
            ` : ''}
            
            ${app.department ? `
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-book"></i>
                    Bölüm
                </div>
                <div class="detail-value">${escapeHtml(app.department)}</div>
            </div>
            ` : ''}
            
            ${app.company_name ? `
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-building"></i>
                    Firma Adı
                </div>
                <div class="detail-value">${escapeHtml(app.company_name)}</div>
            </div>
            ` : ''}
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-users"></i>
                    Ekip Büyüklüğü
                </div>
                <div class="detail-value">${app.team_size} kişi</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-calendar"></i>
                    Başvuru Tarihi
                </div>
                <div class="detail-value">${formatDate(app.created_at)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-info-circle"></i>
                    Durum
                </div>
                <div class="detail-value">
                    <span class="status-badge-large ${app.status}">
                        <i class="fas fa-${getStatusIcon(app.status)}"></i>
                        ${statusTexts[app.status] || app.status}
                    </span>
                </div>
            </div>
            
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-align-left"></i>
                    Proje Özeti
                </div>
                <div class="detail-value multiline">${escapeHtml(app.project_summary)}</div>
            </div>
            
            ${app.expectations ? `
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-bullseye"></i>
                    TEKMER'den Beklentiler
                </div>
                <div class="detail-value multiline">${escapeHtml(app.expectations)}</div>
            </div>
            ` : ''}
        </div>
        
        <div class="modal-actions">
            ${app.project_file ? `
                <a href="<?php echo url('uploads/'); ?>${app.project_file}" class="btn btn-success" download>
                    <i class="fas fa-download"></i> Proje Dosyasını İndir (PDF)
                </a>
            ` : '<div></div>'}
            <button onclick="closeApplicationModal()" class="btn btn-secondary">
                <i class="fas fa-times"></i> Kapat
            </button>
        </div>
    `;
    
    document.getElementById('applicationModal').classList.add('active');
}

function closeApplicationModal() {
    document.getElementById('applicationModal').classList.remove('active');
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('tr-TR', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getStatusIcon(status) {
    const icons = {
        'pending': 'clock',
        'reviewed': 'eye',
        'approved': 'check-circle',
        'rejected': 'times-circle'
    };
    return icons[status] || 'info-circle';
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApplicationModal();
    }
});
</script>

<?php include __DIR__ . '/footer.php'; ?>

