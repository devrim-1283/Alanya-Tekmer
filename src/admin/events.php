<?php
$pageTitle = 'Etkinlik & Duyuru Yönetimi';
$currentAdminPage = 'events';
$db = Database::getInstance();
$success = $error = '';

// Generate CSRF token
$csrfToken = Security::generateCsrfToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $photos = [];
        if (isset($_FILES['photos']) && is_array($_FILES['photos']['name'])) {
            $upload = FileUpload::uploadMultipleImages($_FILES['photos'], 'event', 10);
            if ($upload['success']) {
                $photos = $upload['files'];
            }
        }
        
        if ($_POST['action'] === 'add') {
            $db->execute('INSERT INTO events (type, title, description, photos, event_date) VALUES (?, ?, ?, ?, ?)',
                [Security::cleanInput($_POST['type']), Security::cleanInput($_POST['title']), Security::cleanInput($_POST['description']), 
                json_encode($photos), Security::cleanInput($_POST['event_date'])]);
            $success = 'Etkinlik eklendi.';
        }
        
        // Clear cache
        try {
            $redis = RedisCache::getInstance();
            $redis->delete('tekmer:data:events_etkinlik');
            $redis->delete('tekmer:data:events_duyuru');
        } catch (Exception $e) {
            // Cache clear failed, continue
        }
    } elseif ($_POST['action'] === 'delete') {
        $event = $db->fetchOne('SELECT photos FROM events WHERE id = ?', [(int)$_POST['id']]);
        if ($event) {
            $photos = json_decode($event['photos'], true);
            if (is_array($photos)) {
                foreach ($photos as $photo) FileUpload::deleteFile($photo);
            }
            $db->execute('DELETE FROM events WHERE id = ?', [(int)$_POST['id']]);
            
            // Clear cache
            try {
                $redis = RedisCache::getInstance();
                $redis->delete('tekmer:data:events_etkinlik');
                $redis->delete('tekmer:data:events_duyuru');
            } catch (Exception $e) {
                // Cache clear failed, continue
            }
            
            $success = 'Etkinlik silindi.';
        }
    }
}

$events = $db->fetchAll('SELECT * FROM events ORDER BY event_date DESC, created_at DESC');
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
        <h3><i class="fas fa-plus-circle"></i> Yeni Etkinlik / Duyuru Ekle</h3>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="eventForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="type">
                        <i class="fas fa-tag"></i>
                        Tür *
                    </label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="etkinlik">📅 Etkinlik</option>
                        <option value="duyuru">📢 Duyuru</option>
                    </select>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="event_date">
                        <i class="fas fa-calendar"></i>
                        Tarih
                    </label>
                    <input type="date" name="event_date" id="event_date" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label for="title">
                    <i class="fas fa-heading"></i>
                    Başlık *
                </label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Etkinlik veya duyuru başlığı" required>
            </div>
            
            <div class="form-group">
                <label for="description">
                    <i class="fas fa-align-left"></i>
                    Açıklama *
                </label>
                <textarea name="description" id="description" class="form-control" rows="6" placeholder="Detaylı açıklama yazın..." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="photos">
                    <i class="fas fa-images"></i>
                    Fotoğraflar (Maksimum 10 adet)
                </label>
                <div class="file-upload-area" onclick="document.getElementById('photos').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Fotoğrafları sürükleyip bırakın veya tıklayın</p>
                    <small>JPG, PNG - Maksimum 10 fotoğraf</small>
                </div>
                <input type="file" name="photos[]" id="photos" accept="image/*" multiple style="display: none;" onchange="previewImages(this)">
                <div id="imagePreview" class="image-preview"></div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i>
                    Kaydet
                </button>
                <button type="reset" class="btn btn-secondary btn-lg" onclick="clearPreviews()">
                    <i class="fas fa-times"></i>
                    Temizle
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card modern-card" style="margin-top: 30px;">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Tüm Etkinlik & Duyurular</h3>
        <div class="header-stats">
            <span class="stat-badge">
                <i class="fas fa-calendar"></i>
                <?php echo count(array_filter($events, fn($e) => $e['type'] === 'etkinlik')); ?> Etkinlik
            </span>
            <span class="stat-badge">
                <i class="fas fa-bullhorn"></i>
                <?php echo count(array_filter($events, fn($e) => $e['type'] === 'duyuru')); ?> Duyuru
            </span>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($events)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Henüz Etkinlik veya Duyuru Yok</h3>
                <p>Yukarıdaki formu kullanarak yeni etkinlik veya duyuru ekleyebilirsiniz.</p>
            </div>
        <?php else: ?>
        <div class="events-grid">
            <?php foreach ($events as $event): 
                $photos = json_decode($event['photos'], true) ?? [];
                $photoCount = count($photos);
                $firstPhoto = !empty($photos) ? $photos[0] : null;
            ?>
                <div class="event-card">
                    <div class="event-card-image">
                        <?php if ($firstPhoto): ?>
                            <img src="<?php echo url('uploads/' . $firstPhoto); ?>" alt="<?php echo Security::escape($event['title']); ?>">
                            <?php if ($photoCount > 1): ?>
                                <div class="photo-count">
                                    <i class="fas fa-images"></i> +<?php echo $photoCount - 1; ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-<?php echo $event['type'] === 'etkinlik' ? 'calendar' : 'bullhorn'; ?>"></i>
                            </div>
                        <?php endif; ?>
                        <div class="event-type-badge <?php echo $event['type']; ?>">
                            <?php echo $event['type'] === 'etkinlik' ? '📅 Etkinlik' : '📢 Duyuru'; ?>
                        </div>
                    </div>
                    <div class="event-card-body">
                        <h4><?php echo Security::escape($event['title']); ?></h4>
                        <p><?php echo Security::escape(truncate($event['description'], 120)); ?></p>
                        <div class="event-meta">
                            <span>
                                <i class="fas fa-calendar"></i>
                                <?php echo $event['event_date'] ? formatDate($event['event_date'], 'd.m.Y') : 'Tarih yok'; ?>
                            </span>
                            <span>
                                <i class="fas fa-images"></i>
                                <?php echo $photoCount; ?> Fotoğraf
                            </span>
                        </div>
                    </div>
                    <div class="event-card-actions">
                        <button onclick="viewEvent(<?php echo htmlspecialchars(json_encode($event)); ?>)" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detay
                        </button>
                        <button onclick="confirmDeleteEvent(<?php echo $event['id']; ?>, '<?php echo Security::escape($event['title']); ?>')" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="modal" id="eventModal">
    <div class="modal-overlay" onclick="closeEventModal()"></div>
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2><i class="fas fa-calendar-alt"></i> Etkinlik Detayları</h2>
            <button class="modal-close" onclick="closeEventModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="eventModalBody">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-content modal-sm">
        <div class="modal-header danger">
            <h2><i class="fas fa-exclamation-triangle"></i> Etkinliği Sil</h2>
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
                    <strong id="deleteEventTitle"></strong> etkinliğini silmek üzeresiniz.
                </p>
                <p class="delete-note">
                    <i class="fas fa-info-circle"></i>
                    Etkinliğe ait tüm veriler ve fotoğraflar kalıcı olarak silinecektir.
                </p>
            </div>
            
            <form method="POST" id="deleteForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteEventId">
                
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
.modern-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: none;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.header-stats {
    display: flex;
    gap: 15px;
}

.stat-badge {
    background: var(--gray-100);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-700);
}

.stat-badge i {
    color: var(--primary);
    margin-right: 5px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group label i {
    color: var(--primary);
    margin-right: 8px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--gray-200);
    border-radius: 10px;
    font-size: 15px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.file-upload-area {
    border: 3px dashed var(--gray-300);
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: var(--gray-50);
}

.file-upload-area:hover {
    border-color: var(--primary);
    background: var(--gray-100);
}

.file-upload-area i {
    font-size: 3rem;
    color: var(--gray-400);
    margin-bottom: 15px;
}

.file-upload-area p {
    margin: 10px 0;
    font-weight: 600;
    color: var(--gray-700);
}

.file-upload-area small {
    color: var(--gray-500);
}

.image-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.image-preview-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.image-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-preview-item .remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    background: var(--danger);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.form-actions {
    display: flex;
    gap: 15px;
    padding-top: 20px;
    border-top: 2px solid var(--gray-100);
}

.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
}

.event-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.event-card-image {
    position: relative;
    width: 100%;
    height: 200px;
    background: var(--gray-100);
}

.event-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.event-card-image .no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: var(--gray-300);
}

.event-type-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    backdrop-filter: blur(10px);
}

.event-type-badge.etkinlik {
    background: rgba(99, 102, 241, 0.9);
    color: white;
}

.event-type-badge.duyuru {
    background: rgba(245, 158, 11, 0.9);
    color: white;
}

.photo-count {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.event-card-body {
    padding: 20px;
    flex: 1;
}

.event-card-body h4 {
    margin: 0 0 12px 0;
    font-size: 1.2rem;
    color: var(--gray-900);
}

.event-card-body p {
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 15px;
}

.event-meta {
    display: flex;
    gap: 20px;
    font-size: 0.9rem;
    color: var(--gray-500);
}

.event-meta span i {
    color: var(--primary);
    margin-right: 5px;
}

.event-card-actions {
    padding: 15px 20px;
    border-top: 2px solid var(--gray-100);
    display: flex;
    gap: 10px;
}

.event-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.event-gallery img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.event-gallery img:hover {
    transform: scale(1.05);
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

.modal-actions-delete {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.modal-actions-delete .btn {
    flex: 1;
    padding: 14px 24px;
    font-weight: 600;
    font-size: 1rem;
}

/* Event Gallery */
.event-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.gallery-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.gallery-item:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay i {
    color: white;
    font-size: 2rem;
}

@media (max-width: 768px) {
    .events-grid {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .header-stats {
        width: 100%;
        justify-content: flex-start;
    }
}
</style>

<script>
function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const item = document.createElement('div');
                    item.className = 'image-preview-item';
                    item.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn" onclick="removeImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(item);
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

function removeImage(index) {
    const input = document.getElementById('photos');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });
    
    input.files = dt.files;
    previewImages(input);
}

function clearPreviews() {
    document.getElementById('imagePreview').innerHTML = '';
}

function viewEvent(eventData) {
    const event = typeof eventData === 'string' ? JSON.parse(eventData) : eventData;
    
    // Parse photos
    let photos = [];
    try {
        photos = JSON.parse(event.photos || '[]');
    } catch (e) {
        console.error('Error parsing photos:', e);
        photos = [];
    }
    
    console.log('Event photos:', photos);
    
    let photosHtml = '';
    if (photos && photos.length > 0) {
        photosHtml = `
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-images"></i>
                    Fotoğraflar (${photos.length})
                </div>
                <div class="event-gallery">`;
        
        photos.forEach(photo => {
            const photoUrl = `<?php echo url('uploads/'); ?>${photo}`;
            photosHtml += `
                <div class="gallery-item">
                    <img src="${photoUrl}" alt="Event photo" onclick="window.open('${photoUrl}', '_blank')">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>`;
        });
        
        photosHtml += '</div></div>';
    }
    
    const modalBody = document.getElementById('eventModalBody');
    modalBody.innerHTML = `
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-tag"></i>
                    Tür
                </div>
                <div class="detail-value">
                    <span class="status-badge-large ${event.type}">
                        ${event.type === 'etkinlik' ? '📅 Etkinlik' : '📢 Duyuru'}
                    </span>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-calendar"></i>
                    Etkinlik Tarihi
                </div>
                <div class="detail-value">${event.event_date ? formatDate(event.event_date) : 'Tarih yok'}</div>
            </div>
            
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-heading"></i>
                    Başlık
                </div>
                <div class="detail-value"><strong>${escapeHtml(event.title)}</strong></div>
            </div>
            
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-align-left"></i>
                    Açıklama
                </div>
                <div class="detail-value multiline">${escapeHtml(event.description)}</div>
            </div>
            
            ${photosHtml}
            
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-clock"></i>
                    Oluşturulma Tarihi
                </div>
                <div class="detail-value">${formatDate(event.created_at)}</div>
            </div>
        </div>
        
        ${photosHtml}
        
        <div class="modal-actions">
            <button onclick="closeEventModal()" class="btn btn-secondary">
                <i class="fas fa-times"></i> Kapat
            </button>
        </div>
    `;
    
    document.getElementById('eventModal').classList.add('active');
}

function closeEventModal() {
    document.getElementById('eventModal').classList.remove('active');
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

// Delete confirmation modal
function confirmDeleteEvent(id, title) {
    document.getElementById('deleteEventId').value = id;
    document.getElementById('deleteEventTitle').textContent = title;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEventModal();
        closeDeleteModal();
    }
});
</script>

<?php include __DIR__ . '/footer.php'; ?>

