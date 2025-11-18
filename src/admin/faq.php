<?php
// Admin FAQ Management
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';

// Check authentication
Security::requireAuth();

$pageTitle = 'SSS Yönetimi';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $db = Database::getInstance();
        
        switch ($_POST['action']) {
            case 'add':
                $question = trim($_POST['question'] ?? '');
                $answer = trim($_POST['answer'] ?? '');
                $sort_order = intval($_POST['sort_order'] ?? 0);
                $is_active = isset($_POST['is_active']) && $_POST['is_active'] === 'true';
                
                if (empty($question) || empty($answer)) {
                    throw new Exception('Soru ve cevap gereklidir.');
                }
                
                $db->execute(
                    'INSERT INTO faq (question, answer, sort_order, is_active) VALUES (?, ?, ?, ?)',
                    [$question, $answer, $sort_order, $is_active]
                );
                
                echo json_encode(['success' => true, 'message' => 'SSS başarıyla eklendi.']);
                exit;
                
            case 'update':
                $id = intval($_POST['id'] ?? 0);
                $question = trim($_POST['question'] ?? '');
                $answer = trim($_POST['answer'] ?? '');
                $sort_order = intval($_POST['sort_order'] ?? 0);
                $is_active = isset($_POST['is_active']) && $_POST['is_active'] === 'true';
                
                if ($id <= 0 || empty($question) || empty($answer)) {
                    throw new Exception('Geçersiz veri.');
                }
                
                $db->execute(
                    'UPDATE faq SET question = ?, answer = ?, sort_order = ?, is_active = ? WHERE id = ?',
                    [$question, $answer, $sort_order, $is_active, $id]
                );
                
                echo json_encode(['success' => true, 'message' => 'SSS başarıyla güncellendi.']);
                exit;
                
            case 'delete':
                $id = intval($_POST['id'] ?? 0);
                
                if ($id <= 0) {
                    throw new Exception('Geçersiz ID.');
                }
                
                $db->execute('DELETE FROM faq WHERE id = ?', [$id]);
                
                echo json_encode(['success' => true, 'message' => 'SSS başarıyla silindi.']);
                exit;
                
            case 'toggle_status':
                $id = intval($_POST['id'] ?? 0);
                
                if ($id <= 0) {
                    throw new Exception('Geçersiz ID.');
                }
                
                $db->execute('UPDATE faq SET is_active = NOT is_active WHERE id = ?', [$id]);
                
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

// Get all FAQs
try {
    $db = Database::getInstance();
    $faqs = $db->fetchAll('SELECT * FROM faq ORDER BY sort_order ASC, id ASC');
} catch (Exception $e) {
    $faqs = [];
    $error = $e->getMessage();
}

require_once __DIR__ . '/header.php';
?>

<div class="admin-header">
    <h1><i class="fas fa-question-circle"></i> SSS Yönetimi</h1>
    <button class="btn btn-primary" onclick="showAddModal()">
        <i class="fas fa-plus"></i> Yeni SSS Ekle
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
                            <th>Soru</th>
                            <th style="width: 100px">Sıra</th>
                            <th style="width: 100px">Durum</th>
                            <th style="width: 200px">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($faqs)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Henüz SSS eklenmemiş.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($faqs as $faq): ?>
                            <tr>
                                <td><?php echo $faq['id']; ?></td>
                                <td>
                                    <strong><?php echo Security::escape($faq['question']); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo mb_substr(strip_tags($faq['answer']), 0, 100); ?>...
                                    </small>
                                </td>
                                <td><?php echo $faq['sort_order']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $faq['is_active'] ? 'success' : 'secondary'; ?>">
                                        <?php echo $faq['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='editFaq(<?php echo json_encode($faq); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="toggleStatus(<?php echo $faq['id']; ?>)">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteFaq(<?php echo $faq['id']; ?>)">
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
<div class="modal" id="faqModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2 id="modalTitle">SSS Ekle</h2>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="faqForm">
            <input type="hidden" id="faqId" name="id">
            <input type="hidden" id="action" name="action" value="add">
            
            <div class="form-group">
                <label for="question">Soru *</label>
                <input type="text" id="question" name="question" class="form-control" required maxlength="500">
            </div>
            
            <div class="form-group">
                <label for="answer">Cevap * (HTML kullanabilirsiniz)</label>
                <textarea id="answer" name="answer" class="form-control" rows="10" required></textarea>
                <small class="form-text text-muted">
                    HTML etiketleri kullanabilirsiniz: &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;a&gt;
                </small>
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
function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Yeni SSS Ekle';
    document.getElementById('faqForm').reset();
    document.getElementById('action').value = 'add';
    document.getElementById('faqId').value = '';
    document.getElementById('is_active').checked = true;
    document.getElementById('faqModal').classList.add('active');
}

function editFaq(faq) {
    document.getElementById('modalTitle').textContent = 'SSS Düzenle';
    document.getElementById('action').value = 'update';
    document.getElementById('faqId').value = faq.id;
    document.getElementById('question').value = faq.question;
    document.getElementById('answer').value = faq.answer;
    document.getElementById('sort_order').value = faq.sort_order;
    document.getElementById('is_active').checked = faq.is_active;
    document.getElementById('faqModal').classList.add('active');
}

function closeModal() {
    document.getElementById('faqModal').classList.remove('active');
}

// Handle form submission
document.getElementById('faqForm').addEventListener('submit', async (e) => {
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

async function deleteFaq(id) {
    if (!confirm('Bu SSS\'yi silmek istediğinizden emin misiniz?')) {
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
    const modal = document.getElementById('faqModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>

