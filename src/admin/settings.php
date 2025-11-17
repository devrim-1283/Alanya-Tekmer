<?php
$pageTitle = 'Ayarlar';
$currentAdminPage = 'settings';

$db = Database::getInstance();
$success = '';
$error = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Geçersiz istek.';
    } else {
        if ($_POST['action'] === 'update_settings') {
            try {
                foreach ($_POST['settings'] as $key => $value) {
                    $db->execute(
                        'UPDATE settings SET value = ? WHERE key = ?',
                        [Security::cleanInput($value), $key]
                    );
                }
                
                // Clear settings cache
                CacheHelper::clearDataCache('settings');
                $redis = RedisCache::getInstance();
                $redis->delete('tekmer:settings');
                
                $success = 'Ayarlar güncellendi.';
            } catch (Exception $e) {
                $error = 'Güncelleme başarısız.';
            }
        } elseif ($_POST['action'] === 'add_option') {
            try {
                $type = Security::cleanInput($_POST['option_type']);
                $value = Security::cleanInput($_POST['option_value']);
                $sortOrder = (int)$_POST['sort_order'];
                
                $db->execute(
                    'INSERT INTO combobox_options (type, value, sort_order) VALUES (?, ?, ?)',
                    [$type, $value, $sortOrder]
                );
                
                $success = 'Seçenek eklendi.';
            } catch (Exception $e) {
                $error = 'Ekleme başarısız.';
            }
        } elseif ($_POST['action'] === 'delete_option') {
            try {
                $id = (int)$_POST['id'];
                $db->execute('DELETE FROM combobox_options WHERE id = ?', [$id]);
                $success = 'Seçenek silindi.';
            } catch (Exception $e) {
                $error = 'Silme başarısız.';
            }
        }
    }
}

// Get settings
$settings = $db->fetchAll('SELECT * FROM settings ORDER BY key');
$settingsArray = [];
foreach ($settings as $setting) {
    $settingsArray[$setting['key']] = $setting['value'];
}

// Get combobox options
$comboboxOptions = [
    'proje_turu' => $db->fetchAll('SELECT * FROM combobox_options WHERE type = ? ORDER BY sort_order', ['proje_turu']),
    'faaliyet_alani' => $db->fetchAll('SELECT * FROM combobox_options WHERE type = ? ORDER BY sort_order', ['faaliyet_alani']),
    'talep_edilen_alan' => $db->fetchAll('SELECT * FROM combobox_options WHERE type = ? ORDER BY sort_order', ['talep_edilen_alan']),
];

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
    <div class="card-header"><h3>Site Ayarları</h3></div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="update_settings">
            
            <?php foreach ($settingsArray as $key => $value): ?>
                <div class="form-group">
                    <label><?php echo Security::escape($key); ?></label>
                    <?php if (strlen($value) > 100): ?>
                        <textarea name="settings[<?php echo $key; ?>]" rows="3"><?php echo Security::escape($value); ?></textarea>
                    <?php else: ?>
                        <input type="text" name="settings[<?php echo $key; ?>]" value="<?php echo Security::escape($value); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header"><h3>Form Seçenekleri Yönetimi</h3></div>
    <div class="card-body">
        <h4>Proje Türleri</h4>
        <table class="table table-sm">
            <tbody>
                <?php foreach ($comboboxOptions['proje_turu'] as $opt): ?>
                    <tr>
                        <td><?php echo Security::escape($opt['value']); ?></td>
                        <td><?php echo $opt['sort_order']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <input type="hidden" name="action" value="delete_option">
                                <input type="hidden" name="id" value="<?php echo $opt['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <form method="POST" class="inline-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add_option">
            <input type="hidden" name="option_type" value="proje_turu">
            <input type="text" name="option_value" placeholder="Yeni seçenek" required>
            <input type="number" name="sort_order" placeholder="Sıra" value="99" style="width:80px;">
            <button type="submit" class="btn btn-sm btn-primary">Ekle</button>
        </form>
        
        <hr>
        
        <h4>Faaliyet Alanları</h4>
        <table class="table table-sm">
            <tbody>
                <?php foreach ($comboboxOptions['faaliyet_alani'] as $opt): ?>
                    <tr>
                        <td><?php echo Security::escape($opt['value']); ?></td>
                        <td><?php echo $opt['sort_order']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <input type="hidden" name="action" value="delete_option">
                                <input type="hidden" name="id" value="<?php echo $opt['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <form method="POST" class="inline-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add_option">
            <input type="hidden" name="option_type" value="faaliyet_alani">
            <input type="text" name="option_value" placeholder="Yeni alan" required>
            <input type="number" name="sort_order" placeholder="Sıra" value="99" style="width:80px;">
            <button type="submit" class="btn btn-sm btn-primary">Ekle</button>
        </form>
        
        <hr>
        
        <h4>Talep Edilen Alan</h4>
        <table class="table table-sm">
            <tbody>
                <?php foreach ($comboboxOptions['talep_edilen_alan'] as $opt): ?>
                    <tr>
                        <td><?php echo Security::escape($opt['value']); ?></td>
                        <td><?php echo $opt['sort_order']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <input type="hidden" name="action" value="delete_option">
                                <input type="hidden" name="id" value="<?php echo $opt['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <form method="POST" class="inline-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="action" value="add_option">
            <input type="hidden" name="option_type" value="talep_edilen_alan">
            <input type="text" name="option_value" placeholder="Yeni alan" required>
            <input type="number" name="sort_order" placeholder="Sıra" value="99" style="width:80px;">
            <button type="submit" class="btn btn-sm btn-primary">Ekle</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>

