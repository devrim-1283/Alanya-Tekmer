<?php
$pageTitle = 'Ekip Yönetimi';
$currentAdminPage = 'team';
$db = Database::getInstance();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    if ($_POST['action'] === 'add') {
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = FileUpload::uploadImage($_FILES['photo'], 'team');
            if ($upload['success']) {
                $db->execute('INSERT INTO team (photo, name, position, sort_order) VALUES (?, ?, ?, ?)',
                    [$upload['filename'], Security::cleanInput($_POST['name']), Security::cleanInput($_POST['position']), (int)$_POST['sort_order']]);
                
                // Clear cache
                try {
                    $redis = RedisCache::getInstance();
                    $redis->delete('tekmer:data:team');
                } catch (Exception $e) {}
                
                $success = 'Ekip üyesi başarıyla eklendi.';
            } else {
                $error = $upload['error'];
            }
        } else {
            $error = 'Lütfen fotoğraf yükleyin.';
        }
    } elseif ($_POST['action'] === 'delete') {
        $member = $db->fetchOne('SELECT photo FROM team WHERE id = ?', [(int)$_POST['id']]);
        if ($member) {
            FileUpload::deleteFile($member['photo']);
            $db->execute('DELETE FROM team WHERE id = ?', [(int)$_POST['id']]);
            
            // Clear cache
            try {
                $redis = RedisCache::getInstance();
                $redis->delete('tekmer:data:team');
            } catch (Exception $e) {}
            
            $success = 'Ekip üyesi silindi.';
        }
    }
}

$team = $db->fetchAll('SELECT * FROM team ORDER BY sort_order, id');
$csrfToken = Security::generateCsrfToken();
include __DIR__ . '/header.php';
?>

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

<div class="card modern-card">
    <div class="card-header">
        <h3><i class="fas fa-user-plus"></i> Yeni Ekip Üyesi Ekle</h3>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="teamForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">
                        <i class="fas fa-user"></i>
                        Ad Soyad *
                    </label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ad Soyad" required>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="position">
                        <i class="fas fa-briefcase"></i>
                        Görevi *
                    </label>
                    <input type="text" name="position" id="position" class="form-control" placeholder="Örn: Genel Müdür" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="sort_order">
                    <i class="fas fa-sort-numeric-down"></i>
                    Sıralama
                </label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="0" min="0">
                <small class="form-text">Küçük sayılar önce gösterilir</small>
            </div>
            
            <div class="form-group">
                <label for="photo">
                    <i class="fas fa-camera"></i>
                    Fotoğraf *
                </label>
                <div class="file-upload-area" onclick="document.getElementById('photo').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Fotoğraf yüklemek için tıklayın</p>
                    <small>PNG, JPG - Maksimum 5MB - Kare görsel önerilir</small>
                </div>
                <input type="file" name="photo" id="photo" accept="image/*" style="display: none;" onchange="previewPhoto(this)" required>
                <div id="photoPreview" class="photo-preview"></div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i>
                    Ekip Üyesini Kaydet
                </button>
                <button type="reset" class="btn btn-secondary btn-lg" onclick="clearPreview()">
                    <i class="fas fa-times"></i>
                    Temizle
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card modern-card" style="margin-top: 30px;">
    <div class="card-header">
        <h3><i class="fas fa-users"></i> Ekip Üyeleri</h3>
        <div class="header-stats">
            <span class="stat-badge">
                <i class="fas fa-user"></i>
                <?php echo count($team); ?> Üye
            </span>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($team)): ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>Henüz Ekip Üyesi Yok</h3>
                <p>Yukarıdaki formu kullanarak yeni ekip üyesi ekleyebilirsiniz.</p>
            </div>
        <?php else: ?>
        <div class="team-grid">
            <?php foreach ($team as $member): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <img src="<?php echo url('uploads/' . $member['photo']); ?>" alt="<?php echo Security::escape($member['name']); ?>">
                        <div class="team-order">#<?php echo $member['sort_order']; ?></div>
                    </div>
                    <div class="team-info">
                        <h4><?php echo Security::escape($member['name']); ?></h4>
                        <p><?php echo Security::escape($member['position']); ?></p>
                    </div>
                    <div class="team-actions">
                        <button onclick="viewMember(<?php echo htmlspecialchars(json_encode($member)); ?>)" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detay
                        </button>
                        <button onclick="confirmDeleteMember(<?php echo $member['id']; ?>, '<?php echo Security::escape($member['name']); ?>')" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Member Detail Modal -->
<div class="modal" id="memberModal">
    <div class="modal-overlay" onclick="closeMemberModal()"></div>
    <div class="modal-content modal-md">
        <div class="modal-header">
            <h2><i class="fas fa-user"></i> Ekip Üyesi Detayları</h2>
            <button class="modal-close" onclick="closeMemberModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="memberModalBody">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-content modal-sm">
        <div class="modal-header danger">
            <h2><i class="fas fa-exclamation-triangle"></i> Üyeyi Sil</h2>
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
                    <strong id="deleteMemberName"></strong> ekip üyesini silmek üzeresiniz.
                </p>
                <p class="delete-note">
                    <i class="fas fa-info-circle"></i>
                    Üyeye ait tüm veriler ve fotoğraf kalıcı olarak silinecektir.
                </p>
            </div>
            
            <form method="POST" id="deleteForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteMemberId">
                
                <div class="modal-actions-delete">
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
<?php include __DIR__ . '/../admin/styles/modern-page.css'; ?>

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.team-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    text-align: center;
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.team-photo {
    position: relative;
    width: 100%;
    padding-top: 100%;
    background: var(--gray-100);
}

.team-photo img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-order {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(99, 102, 241, 0.9);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.team-info {
    padding: 20px;
}

.team-info h4 {
    margin: 0 0 8px 0;
    font-size: 1.2rem;
    color: var(--gray-900);
}

.team-info p {
    margin: 0;
    color: var(--primary);
    font-weight: 500;
}

.team-actions {
    padding: 15px;
    border-top: 2px solid var(--gray-100);
    display: flex;
    gap: 10px;
}

.photo-preview {
    margin-top: 15px;
    text-align: center;
}

.photo-preview img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.modal-md {
    max-width: 600px;
}

@media (max-width: 768px) {
    .team-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}
</style>

<script>
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Photo Preview">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearPreview() {
    document.getElementById('photoPreview').innerHTML = '';
}

function viewMember(memberData) {
    const member = typeof memberData === 'string' ? JSON.parse(memberData) : memberData;
    
    const modalBody = document.getElementById('memberModalBody');
    modalBody.innerHTML = `
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="<?php echo url('uploads/'); ?>${member.photo}" alt="${escapeHtml(member.name)}" 
                 style="width: 250px; height: 250px; object-fit: cover; border-radius: 50%; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
        </div>
        
        <div class="detail-grid">
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-user"></i>
                    Ad Soyad
                </div>
                <div class="detail-value"><strong>${escapeHtml(member.name)}</strong></div>
            </div>
            
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-briefcase"></i>
                    Görevi
                </div>
                <div class="detail-value">${escapeHtml(member.position)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-sort-numeric-down"></i>
                    Sıralama
                </div>
                <div class="detail-value">#${member.sort_order}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-calendar"></i>
                    Eklenme Tarihi
                </div>
                <div class="detail-value">${formatDate(member.created_at)}</div>
            </div>
        </div>
        
        <div class="modal-actions">
            <button onclick="closeMemberModal()" class="btn btn-secondary">
                <i class="fas fa-times"></i> Kapat
            </button>
        </div>
    `;
    
    document.getElementById('memberModal').classList.add('active');
}

function closeMemberModal() {
    document.getElementById('memberModal').classList.remove('active');
}

function confirmDeleteMember(id, name) {
    document.getElementById('deleteMemberId').value = id;
    document.getElementById('deleteMemberName').textContent = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('tr-TR', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric'
    });
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMemberModal();
        closeDeleteModal();
    }
});
</script>

<?php include __DIR__ . '/footer.php'; ?>
