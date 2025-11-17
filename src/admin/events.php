<?php
$pageTitle = 'Etkinlik & Duyuru Yönetimi';
$currentAdminPage = 'events';
$db = Database::getInstance();
$success = $error = '';

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
            $success = 'Eklendi.';
        }
        CacheHelper::deletePattern('tekmer:data:events_*');
    } elseif ($_POST['action'] === 'delete') {
        $event = $db->fetchOne('SELECT photos FROM events WHERE id = ?', [(int)$_POST['id']]);
        if ($event) {
            $photos = json_decode($event['photos'], true);
            if (is_array($photos)) {
                foreach ($photos as $photo) FileUpload::deleteFile($photo);
            }
            $db->execute('DELETE FROM events WHERE id = ?', [(int)$_POST['id']]);
            CacheHelper::deletePattern('tekmer:data:events_*');
            $success = 'Silindi.';
        }
    }
}

$events = $db->fetchAll('SELECT * FROM events ORDER BY event_date DESC, created_at DESC');
$csrfToken = Security::generateCsrfToken();
include __DIR__ . '/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Etkinlik / Duyuru Ekle</h3></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add">
            <div class="form-group"><label>Tür *</label><select name="type" required><option value="etkinlik">Etkinlik</option><option value="duyuru">Duyuru</option></select></div>
            <div class="form-group"><label>Başlık *</label><input type="text" name="title" required></div>
            <div class="form-group"><label>Açıklama *</label><textarea name="description" rows="4" required></textarea></div>
            <div class="form-group"><label>Tarih</label><input type="date" name="event_date"></div>
            <div class="form-group"><label>Fotoğraflar (1-10 adet)</label><input type="file" name="photos[]" accept="image/*" multiple></div>
            <button type="submit" class="btn btn-primary">Ekle</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Etkinlik & Duyurular</h3></div>
    <div class="card-body">
        <table class="table">
            <thead><tr><th>Tür</th><th>Başlık</th><th>Tarih</th><th>Fotoğraf</th><th>İşlem</th></tr></thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo $event['type'] === 'etkinlik' ? 'Etkinlik' : 'Duyuru'; ?></td>
                        <td><?php echo Security::escape($event['title']); ?></td>
                        <td><?php echo formatDate($event['event_date']); ?></td>
                        <td><?php echo count(json_decode($event['photos'], true) ?? []); ?> adet</td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
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

