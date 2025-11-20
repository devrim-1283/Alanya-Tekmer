<?php
// Admin Gallery Management
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../utils/upload.php';
require_once __DIR__ . '/../utils/helpers.php';

// Check authentication
requireAdmin();

$pageTitle = 'Galeri Yönetimi';
$currentAdminPage = 'gallery';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $db = Database::getInstance();
        
        switch ($_POST['action']) {
            case 'add':
                $type = trim($_POST['type'] ?? '');
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $video_url = trim($_POST['video_url'] ?? '');
                $sort_order = intval($_POST['sort_order'] ?? 0);
                $is_active = isset($_POST['is_active']) && $_POST['is_active'] === 'true';
                
                if (!in_array($type, ['image', 'video'])) {
                    throw new Exception('Geçersiz medya tipi.');
                }
                
                $media_path = '';
                
                // Handle image upload
                if ($type === 'image') {
                    if (!isset($_FILES['media_file']) || $_FILES['media_file']['error'] === UPLOAD_ERR_NO_FILE) {
                        throw new Exception('Lütfen bir resim seçin.');
                    }
                    
                    $uploadResult = FileUpload::uploadImage($_FILES['media_file'], 'gallery', 1920, true);
                    if (!$uploadResult['success']) {
                        throw new Exception($uploadResult['error'] ?? 'Resim yüklenirken hata oluştu.');
                    }
                    
                    $media_path = 'uploads/' . $uploadResult['filename'];
                }
                
                // Handle video
                if ($type === 'video') {
                    if (empty($video_url)) {
                        throw new Exception('Lütfen video URL\'si girin (YouTube veya Vimeo).');
                    }
                    
                    // Video için medya yolu URL olacak
                    $media_path = $video_url;
                }
                
                $db->execute(
                    'INSERT INTO gallery (type, media_path, title, description, video_url, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)',
                    [$type, $media_path, $title, $description, $video_url, $sort_order, $is_active]
                );
                
                echo json_encode(['success' => true, 'message' => 'Galeri öğesi başarıyla eklendi.']);
                exit;
                
            case 'update':
                $id = intval($_POST['id'] ?? 0);
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $video_url = trim($_POST['video_url'] ?? '');
                $sort_order = intval($_POST['sort_order'] ?? 0);
                $is_active = isset($_POST['is_active']) && $_POST['is_active'] === 'true';
                
                if ($id <= 0) {
                    throw new Exception('Geçersiz ID.');
                }
                
                // Get existing item
                $existing = $db->fetchOne('SELECT * FROM gallery WHERE id = ?', [$id]);
                if (!$existing) {
                    throw new Exception('Galeri öğesi bulunamadı.');
                }
                
                $media_path = $existing['media_path'];
                
                // Handle new image upload if provided
                if ($existing['type'] === 'image' && isset($_FILES['media_file']) && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $uploadResult = FileUpload::uploadImage($_FILES['media_file'], 'gallery', 1920, true);
                    if ($uploadResult['success']) {
                        // Delete old image
                        $oldFile = basename($existing['media_path']);
                        FileUpload::deleteFile($oldFile);
                        
                        $media_path = 'uploads/' . $uploadResult['filename'];
                    }
                }
                
                // Update video URL if it's a video
                if ($existing['type'] === 'video' && !empty($video_url)) {
                    $media_path = $video_url;
                }
                
                $db->execute(
                    'UPDATE gallery SET media_path = ?, title = ?, description = ?, video_url = ?, sort_order = ?, is_active = ? WHERE id = ?',
                    [$media_path, $title, $description, $video_url, $sort_order, $is_active, $id]
                );
                
                echo json_encode(['success' => true, 'message' => 'Galeri öğesi başarıyla güncellendi.']);
                exit;
                
            case 'delete':
                $id = intval($_POST['id'] ?? 0);
                
                if ($id <= 0) {
                    throw new Exception('Geçersiz ID.');
                }
                
                // Get item to delete file
                $item = $db->fetchOne('SELECT * FROM gallery WHERE id = ?', [$id]);
                if ($item && $item['type'] === 'image') {
                    $filename = basename($item['media_path']);
                    FileUpload::deleteFile($filename);
                }
                
                $db->execute('DELETE FROM gallery WHERE id = ?', [$id]);
                
                echo json_encode(['success' => true, 'message' => 'Galeri öğesi başarıyla silindi.']);
                exit;
                
            case 'toggle_status':
                $id = intval($_POST['id'] ?? 0);
                
                if ($id <= 0) {
                    throw new Exception('Geçersiz ID.');
                }
                
                $db->execute('UPDATE gallery SET is_active = NOT is_active WHERE id = ?', [$id]);
                
                echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']);
                exit;
                
            default:
                throw new Exception('Geçersiz işlem.');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Get all gallery items
try {
    $db = Database::getInstance();
    $galleries = $db->fetchAll('SELECT * FROM gallery ORDER BY sort_order ASC, id DESC');
} catch (Exception $e) {
    $galleries = [];
    $error = $e->getMessage();
}

require_once __DIR__ . '/header.php';
?>

<div class="admin-header">
    <h1><i class="fas fa-images"></i> Galeri Yönetimi</h1>
    <button class="btn btn-primary" onclick="showAddModal()">
        <i class="fas fa-plus"></i> Yeni Öğe Ekle
    </button>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    <?php echo Security::escape($error); ?>
</div>
<?php endif; ?>

<div class="admin-content">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th style="width: 100px">Önizleme</th>
                            <th style="width: 80px">Tip</th>
                            <th>Başlık</th>
                            <th style="width: 100px">Sıra</th>
                            <th style="width: 100px">Durum</th>
                            <th style="width: 200px">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($galleries)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Henüz galeri öğesi eklenmemiş.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($galleries as $item): ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td>
                                    <?php if ($item['type'] === 'image'): ?>
                                        <img src="<?php echo url($item['media_path']); ?>" 
                                             alt="Preview" 
                                             style="max-width: 80px; max-height: 60px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div style="width: 80px; height: 60px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                            <i class="fas fa-video" style="font-size: 24px; color: #666;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $item['type'] === 'image' ? 'info' : 'warning'; ?>">
                                        <?php echo $item['type'] === 'image' ? 'Resim' : 'Video'; ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo Security::escape($item['title'] ?: 'Başlıksız'); ?></strong>
                                    <?php if ($item['description']): ?>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo mb_substr(Security::escape($item['description']), 0, 50); ?>...
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $item['sort_order']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $item['is_active'] ? 'success' : 'secondary'; ?>">
                                        <?php echo $item['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='editGallery(<?php echo json_encode($item); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="toggleStatus(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteGallery(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal" id="galleryModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2 id="modalTitle">Galeri Öğesi Ekle</h2>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="galleryForm" enctype="multipart/form-data">
            <input type="hidden" id="galleryId" name="id">
            <input type="hidden" id="action" name="action" value="add">
            
            <div class="form-group">
                <label for="type">Medya Tipi *</label>
                <select id="type" name="type" class="form-control" required onchange="toggleMediaFields()">
                    <option value="">Seçin</option>
                    <option value="image">Fotoğraf</option>
                    <option value="video">Video (YouTube/Vimeo)</option>
                </select>
            </div>
            
            <div id="imageField" class="form-group" style="display: none;">
                <label for="media_file">Fotoğraf *</label>
                <input type="file" id="media_file" name="media_file" class="form-control" accept="image/jpeg,image/png,image/jpg">
                <small class="form-text text-muted">JPG, PNG formatları desteklenir. Maksimum 5MB.</small>
            </div>
            
            <div id="videoField" class="form-group" style="display: none;">
                <label for="video_url">Video URL *</label>
                <input type="url" id="video_url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                <small class="form-text text-muted">YouTube veya Vimeo video linki girin.</small>
            </div>
            
            <div class="form-group">
                <label for="title">Başlık</label>
                <input type="text" id="title" name="title" class="form-control" maxlength="255">
            </div>
            
            <div class="form-group">
                <label for="description">Açıklama</label>
                <textarea id="description" name="description" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="sort_order">Sıra Numarası</label>
                    <input type="number" id="sort_order" name="sort_order" class="form-control" value="0" min="0">
                </div>
                
                <div class="form-group col-md-6">
                    <label>&nbsp;</label>
                    <div class="custom-control custom-checkbox" style="margin-top: 10px;">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                        <label class="custom-control-label" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">İptal</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleMediaFields() {
    const type = document.getElementById('type').value;
    const imageField = document.getElementById('imageField');
    const videoField = document.getElementById('videoField');
    const mediaFile = document.getElementById('media_file');
    const videoUrl = document.getElementById('video_url');
    
    if (type === 'image') {
        imageField.style.display = 'block';
        videoField.style.display = 'none';
        mediaFile.required = true;
        videoUrl.required = false;
    } else if (type === 'video') {
        imageField.style.display = 'none';
        videoField.style.display = 'block';
        mediaFile.required = false;
        videoUrl.required = true;
    } else {
        imageField.style.display = 'none';
        videoField.style.display = 'none';
        mediaFile.required = false;
        videoUrl.required = false;
    }
}

function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Yeni Galeri Öğesi Ekle';
    document.getElementById('galleryForm').reset();
    document.getElementById('action').value = 'add';
    document.getElementById('galleryId').value = '';
    document.getElementById('is_active').checked = true;
    document.getElementById('type').disabled = false;
    toggleMediaFields();
    document.getElementById('galleryModal').classList.add('active');
}

function editGallery(item) {
    document.getElementById('modalTitle').textContent = 'Galeri Öğesi Düzenle';
    document.getElementById('action').value = 'update';
    document.getElementById('galleryId').value = item.id;
    document.getElementById('type').value = item.type;
    document.getElementById('type').disabled = true; // Can't change type when editing
    document.getElementById('title').value = item.title || '';
    document.getElementById('description').value = item.description || '';
    document.getElementById('video_url').value = item.video_url || '';
    document.getElementById('sort_order').value = item.sort_order;
    document.getElementById('is_active').checked = item.is_active;
    
    toggleMediaFields();
    
    // Make file upload optional for editing
    document.getElementById('media_file').required = false;
    
    document.getElementById('galleryModal').classList.add('active');
}

function closeModal() {
    document.getElementById('galleryModal').classList.remove('active');
}

// Handle form submission
document.getElementById('galleryForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    formData.set('is_active', document.getElementById('is_active').checked ? 'true' : 'false');
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            window.location.reload();
        } else {
            alert('Hata: ' + result.message);
        }
    } catch (error) {
        alert('Bir hata oluştu: ' + error.message);
    }
});

async function toggleStatus(id) {
    if (!confirm('Durumu değiştirmek istediğinizden emin misiniz?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'toggle_status');
    formData.append('id', id);
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.reload();
        } else {
            alert('Hata: ' + result.message);
        }
    } catch (error) {
        alert('Bir hata oluştu: ' + error.message);
    }
}

async function deleteGallery(id) {
    if (!confirm('Bu galeri öğesini silmek istediğinizden emin misiniz?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            window.location.reload();
        } else {
            alert('Hata: ' + result.message);
        }
    } catch (error) {
        alert('Bir hata oluştu: ' + error.message);
    }
}

// Close modal on outside click
window.onclick = function(event) {
    const modal = document.getElementById('galleryModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>

