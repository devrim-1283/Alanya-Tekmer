<?php
$pageTitle = 'Firma Yönetimi';
$currentAdminPage = 'companies';
$db = Database::getInstance();
$success = $error = '';

// Generate CSRF token
$csrfToken = Security::generateCsrfToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    if ($_POST['action'] === 'add') {
        $logo = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = FileUpload::uploadImage($_FILES['logo'], 'company');
            if ($upload['success']) {
                $logo = $upload['filename'];
            } else {
                $error = $upload['error'];
            }
        }
        
        if (!$error) {
            $db->execute('INSERT INTO companies (name, logo, description, contact_person, phone, instagram, linkedin, website, whatsapp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [Security::cleanInput($_POST['name']), $logo, Security::cleanInput($_POST['description']), Security::cleanInput($_POST['contact_person']),
                Security::normalizePhone($_POST['phone']), Security::cleanInput($_POST['instagram']), Security::cleanInput($_POST['linkedin']),
                Security::cleanInput($_POST['website']), Security::normalizePhone($_POST['whatsapp'])]);
            
            // Clear cache
            try {
                $redis = RedisCache::getInstance();
                $redis->delete('tekmer:data:companies');
            } catch (Exception $e) {}
            
            $success = 'Firma başarıyla eklendi.';
        }
    } elseif ($_POST['action'] === 'delete') {
        $company = $db->fetchOne('SELECT logo FROM companies WHERE id = ?', [(int)$_POST['id']]);
        if ($company && $company['logo']) FileUpload::deleteFile($company['logo']);
        $db->execute('DELETE FROM companies WHERE id = ?', [(int)$_POST['id']]);
        
        // Clear cache
        try {
            $redis = RedisCache::getInstance();
            $redis->delete('tekmer:data:companies');
        } catch (Exception $e) {}
        
        $success = 'Firma silindi.';
    }
}

$companies = $db->fetchAll('SELECT * FROM companies ORDER BY name');
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
        <h3><i class="fas fa-plus-circle"></i> Yeni Firma Ekle</h3>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="companyForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="name">
                        <i class="fas fa-building"></i>
                        Firma Adı *
                    </label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Firma adını girin" required>
                </div>
                
                <div class="form-group col-md-4">
                    <label for="contact_person">
                        <i class="fas fa-user-tie"></i>
                        Yetkili Kişi
                    </label>
                    <input type="text" name="contact_person" id="contact_person" class="form-control" placeholder="Yetkili adı">
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">
                    <i class="fas fa-align-left"></i>
                    Açıklama
                </label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Firma hakkında kısa açıklama..."></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="phone">
                        <i class="fas fa-phone"></i>
                        Telefon
                    </label>
                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="+90 5XX XXX XX XX">
                </div>
                
                <div class="form-group col-md-4">
                    <label for="whatsapp">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </label>
                    <input type="tel" name="whatsapp" id="whatsapp" class="form-control" placeholder="+90 5XX XXX XX XX">
                </div>
                
                <div class="form-group col-md-4">
                    <label for="website">
                        <i class="fas fa-globe"></i>
                        Website
                    </label>
                    <input type="url" name="website" id="website" class="form-control" placeholder="https://">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="instagram">
                        <i class="fab fa-instagram"></i>
                        Instagram
                    </label>
                    <input type="url" name="instagram" id="instagram" class="form-control" placeholder="https://instagram.com/">
                </div>
                
                <div class="form-group col-md-6">
                    <label for="linkedin">
                        <i class="fab fa-linkedin"></i>
                        LinkedIn
                    </label>
                    <input type="url" name="linkedin" id="linkedin" class="form-control" placeholder="https://linkedin.com/">
                </div>
            </div>
            
            <div class="form-group">
                <label for="logo">
                    <i class="fas fa-image"></i>
                    Logo
                </label>
                <div class="file-upload-area" onclick="document.getElementById('logo').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Logo yüklemek için tıklayın</p>
                    <small>PNG, JPG - Maksimum 5MB</small>
                </div>
                <input type="file" name="logo" id="logo" accept="image/*" style="display: none;" onchange="previewLogo(this)">
                <div id="logoPreview" class="logo-preview"></div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i>
                    Firmayı Kaydet
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
        <h3><i class="fas fa-list"></i> Tüm Firmalar</h3>
        <div class="header-stats">
            <span class="stat-badge">
                <i class="fas fa-building"></i>
                <?php echo count($companies); ?> Firma
            </span>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($companies)): ?>
            <div class="empty-state">
                <i class="fas fa-building"></i>
                <h3>Henüz Firma Yok</h3>
                <p>Yukarıdaki formu kullanarak yeni firma ekleyebilirsiniz.</p>
            </div>
        <?php else: ?>
        <div class="companies-grid">
            <?php foreach ($companies as $company): ?>
                <div class="company-card">
                    <div class="company-logo">
                        <?php if ($company['logo']): ?>
                            <img src="<?php echo url('uploads/' . $company['logo']); ?>" alt="<?php echo Security::escape($company['name']); ?>">
                        <?php else: ?>
                            <div class="no-logo">
                                <i class="fas fa-building"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="company-body">
                        <h4><?php echo Security::escape($company['name']); ?></h4>
                        <?php if ($company['description']): ?>
                            <p><?php echo Security::escape(truncate($company['description'], 100)); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($company['contact_person']): ?>
                            <div class="company-meta">
                                <i class="fas fa-user-tie"></i>
                                <?php echo Security::escape($company['contact_person']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($company['phone']): ?>
                            <div class="company-meta">
                                <i class="fas fa-phone"></i>
                                <?php echo formatPhone($company['phone']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="company-social">
                            <?php if ($company['website']): ?>
                                <a href="<?php echo Security::escape($company['website']); ?>" target="_blank" title="Website">
                                    <i class="fas fa-globe"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($company['instagram']): ?>
                                <a href="<?php echo Security::escape($company['instagram']); ?>" target="_blank" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($company['linkedin']): ?>
                                <a href="<?php echo Security::escape($company['linkedin']); ?>" target="_blank" title="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($company['whatsapp']): ?>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $company['whatsapp']); ?>" target="_blank" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="company-actions">
                        <button onclick="viewCompany(<?php echo htmlspecialchars(json_encode($company)); ?>)" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detay
                        </button>
                        <button onclick="confirmDeleteCompany(<?php echo $company['id']; ?>, '<?php echo Security::escape($company['name']); ?>')" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Company Detail Modal -->
<div class="modal" id="companyModal">
    <div class="modal-overlay" onclick="closeCompanyModal()"></div>
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2><i class="fas fa-building"></i> Firma Detayları</h2>
            <button class="modal-close" onclick="closeCompanyModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="companyModalBody">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-content modal-sm">
        <div class="modal-header danger">
            <h2><i class="fas fa-exclamation-triangle"></i> Firmayı Sil</h2>
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
                    <strong id="deleteCompanyName"></strong> firmasını silmek üzeresiniz.
                </p>
                <p class="delete-note">
                    <i class="fas fa-info-circle"></i>
                    Firmaya ait tüm veriler ve logo kalıcı olarak silinecektir.
                </p>
            </div>
            
            <form method="POST" id="deleteForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteCompanyId">
                
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

.companies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
}

.company-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.company-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.company-logo {
    width: 100%;
    height: 180px;
    background: var(--gray-50);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.company-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.company-logo .no-logo {
    font-size: 4rem;
    color: var(--gray-300);
}

.company-body {
    padding: 20px;
    flex: 1;
}

.company-body h4 {
    margin: 0 0 10px 0;
    font-size: 1.3rem;
    color: var(--gray-900);
}

.company-body p {
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 15px;
}

.company-meta {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.company-meta i {
    color: var(--primary);
    margin-right: 8px;
    width: 20px;
}

.company-social {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid var(--gray-100);
}

.company-social a {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-700);
    transition: all 0.3s ease;
    text-decoration: none;
}

.company-social a:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
}

.company-actions {
    padding: 15px 20px;
    border-top: 2px solid var(--gray-100);
    display: flex;
    gap: 10px;
}

.logo-preview {
    margin-top: 15px;
    text-align: center;
}

.logo-preview img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .companies-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearPreview() {
    document.getElementById('logoPreview').innerHTML = '';
}

function viewCompany(companyData) {
    const company = typeof companyData === 'string' ? JSON.parse(companyData) : companyData;
    
    let socialLinks = '';
    if (company.website) socialLinks += `<a href="${company.website}" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-globe"></i> Website</a>`;
    if (company.instagram) socialLinks += `<a href="${company.instagram}" target="_blank" class="btn btn-sm btn-secondary"><i class="fab fa-instagram"></i> Instagram</a>`;
    if (company.linkedin) socialLinks += `<a href="${company.linkedin}" target="_blank" class="btn btn-sm btn-secondary"><i class="fab fa-linkedin"></i> LinkedIn</a>`;
    if (company.whatsapp) socialLinks += `<a href="https://wa.me/${company.whatsapp.replace(/[^0-9]/g, '')}" target="_blank" class="btn btn-sm btn-success"><i class="fab fa-whatsapp"></i> WhatsApp</a>`;
    
    const modalBody = document.getElementById('companyModalBody');
    modalBody.innerHTML = `
        <div class="detail-grid">
            ${company.logo ? `
            <div class="detail-item full-width" style="text-align: center;">
                <img src="<?php echo url('uploads/'); ?>${company.logo}" alt="${escapeHtml(company.name)}" style="max-width: 300px; max-height: 200px; object-fit: contain;">
            </div>
            ` : ''}
            
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-building"></i>
                    Firma Adı
                </div>
                <div class="detail-value"><strong>${escapeHtml(company.name)}</strong></div>
            </div>
            
            ${company.description ? `
            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-align-left"></i>
                    Açıklama
                </div>
                <div class="detail-value">${escapeHtml(company.description)}</div>
            </div>
            ` : ''}
            
            ${company.contact_person ? `
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-user-tie"></i>
                    Yetkili Kişi
                </div>
                <div class="detail-value">${escapeHtml(company.contact_person)}</div>
            </div>
            ` : ''}
            
            ${company.phone ? `
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-phone"></i>
                    Telefon
                </div>
                <div class="detail-value">${escapeHtml(company.phone)}</div>
            </div>
            ` : ''}
        </div>
        
        ${socialLinks ? `
        <div style="margin-top: 20px;">
            <h3><i class="fas fa-share-alt"></i> Sosyal Medya</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                ${socialLinks}
            </div>
        </div>
        ` : ''}
        
        <div class="modal-actions">
            <button onclick="closeCompanyModal()" class="btn btn-secondary">
                <i class="fas fa-times"></i> Kapat
            </button>
        </div>
    `;
    
    document.getElementById('companyModal').classList.add('active');
}

function closeCompanyModal() {
    document.getElementById('companyModal').classList.remove('active');
}

function confirmDeleteCompany(id, name) {
    document.getElementById('deleteCompanyId').value = id;
    document.getElementById('deleteCompanyName').textContent = name;
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

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCompanyModal();
        closeDeleteModal();
    }
});
</script>

<?php include __DIR__ . '/footer.php'; ?>
