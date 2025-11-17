<?php
$pageTitle = 'Firma Yönetimi';
$currentAdminPage = 'companies';
$db = Database::getInstance();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    if ($_POST['action'] === 'add') {
        $logo = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = FileUpload::uploadImage($_FILES['logo'], 'company');
            if ($upload['success']) {
                $logo = $upload['filename'];
            }
        }
        $db->execute('INSERT INTO companies (name, logo, description, contact_person, phone, instagram, linkedin, website, whatsapp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [Security::cleanInput($_POST['name']), $logo, Security::cleanInput($_POST['description']), Security::cleanInput($_POST['contact_person']),
            Security::normalizePhone($_POST['phone']), Security::cleanInput($_POST['instagram']), Security::cleanInput($_POST['linkedin']),
            Security::cleanInput($_POST['website']), Security::normalizePhone($_POST['whatsapp'])]);
        CacheHelper::clearDataCache('companies');
        $success = 'Eklendi.';
    } elseif ($_POST['action'] === 'delete') {
        $company = $db->fetchOne('SELECT logo FROM companies WHERE id = ?', [(int)$_POST['id']]);
        if ($company && $company['logo']) FileUpload::deleteFile($company['logo']);
        $db->execute('DELETE FROM companies WHERE id = ?', [(int)$_POST['id']]);
        CacheHelper::clearDataCache('companies');
        $success = 'Silindi.';
    }
}

$companies = $db->fetchAll('SELECT * FROM companies ORDER BY name');
$csrfToken = Security::generateCsrfToken();
include __DIR__ . '/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Firma Ekle</h3></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add">
            <div class="form-row">
                <div class="form-group"><label>Firma Adı *</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Logo</label><input type="file" name="logo" accept="image/*"></div>
                <div class="form-group"><label>Yetkili</label><input type="text" name="contact_person"></div>
                <div class="form-group"><label>Telefon</label><input type="tel" name="phone"></div>
            </div>
            <div class="form-group"><label>Açıklama</label><textarea name="description" rows="3"></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Instagram</label><input type="url" name="instagram"></div>
                <div class="form-group"><label>LinkedIn</label><input type="url" name="linkedin"></div>
                <div class="form-group"><label>Website</label><input type="url" name="website"></div>
                <div class="form-group"><label>WhatsApp</label><input type="tel" name="whatsapp"></div>
            </div>
            <button type="submit" class="btn btn-primary">Ekle</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Firmalar</h3></div>
    <div class="card-body">
        <table class="table">
            <thead><tr><th>Logo</th><th>Firma Adı</th><th>Yetkili</th><th>Telefon</th><th>İşlem</th></tr></thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td><?php if ($company['logo']): ?><img src="<?php echo url('uploads/' . $company['logo']); ?>" width="50" height="50" style="object-fit:contain;"><?php endif; ?></td>
                        <td><?php echo Security::escape($company['name']); ?></td>
                        <td><?php echo Security::escape($company['contact_person']); ?></td>
                        <td><?php echo formatPhone($company['phone']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $company['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>

