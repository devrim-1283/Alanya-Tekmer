<?php
$pageTitle = 'Ekip Yönetimi';
$currentAdminPage = 'team';
$db = Database::getInstance();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    if ($_POST['action'] === 'add') {
        $upload = FileUpload::uploadImage($_FILES['photo'], 'team');
        if ($upload['success']) {
            $db->execute('INSERT INTO team (photo, name, position, sort_order) VALUES (?, ?, ?, ?)',
                [Security::escape($upload['filename']), Security::cleanInput($_POST['name']), Security::cleanInput($_POST['position']), (int)$_POST['sort_order']]);
            CacheHelper::clearDataCache('team');
            $success = 'Eklendi.';
        } else {
            $error = $upload['error'];
        }
    } elseif ($_POST['action'] === 'delete') {
        $member = $db->fetchOne('SELECT photo FROM team WHERE id = ?', [(int)$_POST['id']]);
        if ($member) {
            FileUpload::deleteFile($member['photo']);
            $db->execute('DELETE FROM team WHERE id = ?', [(int)$_POST['id']]);
            CacheHelper::clearDataCache('team');
            $success = 'Silindi.';
        }
    }
}

$team = $db->fetchAll('SELECT * FROM team ORDER BY sort_order, id');
$csrfToken = Security::generateCsrfToken();
include __DIR__ . '/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Ekip Üyesi Ekle</h3></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add">
            <div class="form-row">
                <div class="form-group"><label>Fotoğraf *</label><input type="file" name="photo" accept="image/*" required></div>
                <div class="form-group"><label>Ad Soyad *</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Görevi *</label><input type="text" name="position" required></div>
                <div class="form-group"><label>Sıra</label><input type="number" name="sort_order" value="0"></div>
                <div class="form-group"><button type="submit" class="btn btn-primary">Ekle</button></div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Ekip Üyeleri</h3></div>
    <div class="card-body">
        <table class="table">
            <thead><tr><th>Fotoğraf</th><th>Ad Soyad</th><th>Görevi</th><th>Sıra</th><th>İşlem</th></tr></thead>
            <tbody>
                <?php foreach ($team as $member): ?>
                    <tr>
                        <td><img src="<?php echo url('uploads/' . $member['photo']); ?>" width="50" height="50" style="object-fit:cover;border-radius:50%;"></td>
                        <td><?php echo Security::escape($member['name']); ?></td>
                        <td><?php echo Security::escape($member['position']); ?></td>
                        <td><?php echo $member['sort_order']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
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

