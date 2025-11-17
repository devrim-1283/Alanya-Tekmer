<?php
$pageTitle = 'Başvuru - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER\'e başvurun ve girişim ekosisteminin bir parçası olun.';
$currentPage = 'application';

logPageView('application');

$success = false;
$error = '';
$step = 1;

// Get combobox options
$db = Database::getInstance();
$projectTypes = $db->fetchAll('SELECT value FROM combobox_options WHERE type = ? AND is_active = true ORDER BY sort_order', ['proje_turu']);
$activityAreas = $db->fetchAll('SELECT value FROM combobox_options WHERE type = ? AND is_active = true ORDER BY sort_order', ['faaliyet_alani']);
$requestedSpaces = $db->fetchAll('SELECT value FROM combobox_options WHERE type = ? AND is_active = true ORDER BY sort_order', ['talep_edilen_alan']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rate limiting
    $ip = Security::getClientIp();
    if (!Security::checkRateLimit($ip, 3, 3600, 'application')) {
        $error = 'Çok fazla başvuru gönderdiniz. Lütfen 1 saat sonra tekrar deneyin.';
    } else {
        // CSRF check
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $error = 'Geçersiz istek. Lütfen sayfayı yenileyip tekrar deneyin.';
        } else {
            // Turnstile verification
            if (!Security::verifyTurnstile($_POST['cf-turnstile-response'] ?? '')) {
                $error = 'Captcha doğrulaması başarısız. Lütfen tekrar deneyin.';
            } else {
                // Validate input
                $validator = new Validator($_POST);
                $validator->required('project_name', 'Proje adı zorunludur')
                         ->required('project_type', 'Proje türü zorunludur')
                         ->required('activity_area', 'Faaliyet alanı zorunludur')
                         ->required('full_name', 'Ad Soyad zorunludur')
                         ->required('phone', 'Telefon zorunludur')
                         ->phone('phone')
                         ->required('tc_number', 'TC Kimlik No zorunludur')
                         ->tcNumber('tc_number')
                         ->required('email', 'E-posta zorunludur')
                         ->email('email')
                         ->required('requested_space', 'Talep edilen alan zorunludur')
                         ->required('team_size', 'Ekip büyüklüğü zorunludur')
                         ->numeric('team_size')
                         ->min('team_size', 1, 'Ekip en az 1 kişiden oluşmalıdır')
                         ->required('project_summary', 'Proje özeti zorunludur')
                         ->minLength('project_summary', 50, 'Proje özeti en az 50 karakter olmalıdır')
                         ->checkbox('data_consent', 'Veri kullanım onayını kabul etmelisiniz');
                
                if ($validator->fails()) {
                    $error = $validator->getFirstError();
                } else {
                    // Validate PDF file
                    if (!isset($_FILES['project_file']) || $_FILES['project_file']['error'] === UPLOAD_ERR_NO_FILE) {
                        $error = 'Proje dosyası zorunludur';
                    } else {
                        $uploadResult = FileUpload::uploadPdf($_FILES['project_file'], 'application');
                        
                        if (!$uploadResult['success']) {
                            $error = $uploadResult['error'];
                        } else {
                            // Save to database
                            try {
                                $db->execute(
                                    'INSERT INTO applications (
                                        project_name, project_type, activity_area, full_name, phone, tc_number, 
                                        email, university, department, company_name, requested_space, expectations,
                                        team_size, project_summary, project_file, data_consent, ip_address, user_agent
                                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                    [
                                        Security::cleanInput($_POST['project_name']),
                                        Security::cleanInput($_POST['project_type']),
                                        Security::cleanInput($_POST['activity_area']),
                                        Security::cleanInput($_POST['full_name']),
                                        Security::normalizePhone($_POST['phone']),
                                        Security::cleanInput($_POST['tc_number']),
                                        Security::cleanInput($_POST['email']),
                                        Security::cleanInput($_POST['university'] ?? ''),
                                        Security::cleanInput($_POST['department'] ?? ''),
                                        Security::cleanInput($_POST['company_name'] ?? ''),
                                        Security::cleanInput($_POST['requested_space']),
                                        Security::cleanInput($_POST['expectations'] ?? ''),
                                        (int)$_POST['team_size'],
                                        Security::cleanInput($_POST['project_summary']),
                                        $uploadResult['filename'],
                                        true,
                                        $ip,
                                        Security::getUserAgent()
                                    ]
                                );
                                
                                $success = true;
                            } catch (Exception $e) {
                                // Delete uploaded file if database insert fails
                                FileUpload::deleteFile($uploadResult['filename']);
                                $error = 'Başvurunuz kaydedilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
                                if (getenv('DEBUG_MODE') === 'true') {
                                    $error .= ' - ' . $e->getMessage();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

$csrfToken = Security::generateCsrfToken();
$additionalCss = ['application'];
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Başvuru Formu</h1>
        <p>Alanya TEKMER'e başvurun ve girişim ekosisteminin bir parçası olun</p>
    </div>
</section>

<section class="application-section">
    <div class="container">
        <?php if ($success): ?>
            <div class="success-message">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Başvurunuz Alındı!</h2>
                <p>Başvurunuz başarıyla kaydedildi. İcra Kurulumuz projenizi detaylı inceleyecektir.</p>
                <p>Komite kararı sonucu size e-posta veya telefon ile ulaşılacaktır.</p>
                <a href="<?php echo url(); ?>" class="btn btn-primary">Ana Sayfaya Dön</a>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo Security::escape($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Progress Steps -->
            <div class="application-steps">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-title">Proje Bilgileri</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-title">Kişisel Bilgiler</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-title">Proje Detayları</div>
                </div>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="application-form" id="applicationForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <!-- Step 1: Project Info -->
                <div class="form-step active" data-step="1">
                    <h2>Proje Bilgileri</h2>
                    
                    <div class="form-group">
                        <label for="project_name">Proje Adı *</label>
                        <input type="text" id="project_name" name="project_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="project_type">Başvurulan Proje Türü *</label>
                        <select id="project_type" name="project_type" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($projectTypes as $type): ?>
                                <option value="<?php echo Security::escape($type['value']); ?>">
                                    <?php echo Security::escape($type['value']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="activity_area">İş Fikri / Faaliyet Alanı *</label>
                        <select id="activity_area" name="activity_area" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($activityAreas as $area): ?>
                                <option value="<?php echo Security::escape($area['value']); ?>">
                                    <?php echo Security::escape($area['value']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="requested_space">Talep Edilen Alan *</label>
                        <select id="requested_space" name="requested_space" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($requestedSpaces as $space): ?>
                                <option value="<?php echo Security::escape($space['value']); ?>">
                                    <?php echo Security::escape($space['value']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                            İleri <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Personal Info -->
                <div class="form-step" data-step="2">
                    <h2>Kişisel Bilgiler</h2>
                    
                    <div class="form-group">
                        <label for="full_name">Ad Soyad *</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Telefon *</label>
                            <input type="tel" id="phone" name="phone" required 
                                   placeholder="+90 5XX XXX XX XX">
                        </div>
                        
                        <div class="form-group">
                            <label for="tc_number">TC Kimlik No *</label>
                            <input type="text" id="tc_number" name="tc_number" required 
                                   maxlength="11" placeholder="11 haneli TC kimlik numarası">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-posta *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="university">Üniversite</label>
                            <input type="text" id="university" name="university" 
                                   placeholder="Varsa okuduğunuz üniversite">
                        </div>
                        
                        <div class="form-group">
                            <label for="department">Bölüm</label>
                            <input type="text" id="department" name="department" 
                                   placeholder="Varsa bölümünüz">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="company_name">Firma Adı</label>
                        <input type="text" id="company_name" name="company_name" 
                               placeholder="Varsa firma adınız">
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(1)">
                            <i class="fas fa-arrow-left"></i> Geri
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                            İleri <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Project Details -->
                <div class="form-step" data-step="3">
                    <h2>Proje Detayları</h2>
                    
                    <div class="form-group">
                        <label for="team_size">Proje Ekibi Kaç Kişiden Oluşuyor? *</label>
                        <input type="number" id="team_size" name="team_size" required min="1">
                    </div>
                    
                    <div class="form-group">
                        <label for="project_summary">Proje Özeti * (En az 50 karakter)</label>
                        <textarea id="project_summary" name="project_summary" rows="6" required 
                                  placeholder="Projenizi kısaca tanıtın..."></textarea>
                        <small class="char-counter">0 / 50 karakter</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="expectations">Alanya TEKMER'den Beklentileriniz</label>
                        <textarea id="expectations" name="expectations" rows="4" 
                                  placeholder="Bizden beklentilerinizi paylaşın..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="project_file">Proje Dosyası * (PDF, Max 5MB)</label>
                        <input type="file" id="project_file" name="project_file" accept=".pdf" required>
                        <small>Sadece PDF formatında dosya yükleyebilirsiniz.</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="data_consent" name="data_consent" required>
                            <label for="data_consent">
                                Verdiğim tüm bilgiler doğru ve bana aittir. Bu bilgiler başvurumun değerlendirilmesi ve bana ulaşılmasında kullanılabilir. 
                                <a href="<?php echo url('kvkk'); ?>" target="_blank">KVKK Bilgilendirme</a> metnini okudum, anladım ve kabul ediyorum. *
                            </label>
                        </div>
                    </div>
                    
                    <div class="turnstile-container">
                        <div class="cf-turnstile" data-sitekey="<?php echo getenv('TURNSTILE_SITE_KEY'); ?>"></div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left"></i> Geri
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Başvuruyu Gönder
                        </button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php 
$additionalJs = ['application'];
include __DIR__ . '/../includes/footer.php'; 
?>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script>
// Multi-step form handling
function nextStep(step) {
    // Validate current step
    const currentStep = document.querySelector('.form-step.active');
    const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
        } else {
            input.classList.remove('error');
        }
    });
    
    if (!isValid) {
        alert('Lütfen tüm zorunlu alanları doldurun.');
        return;
    }
    
    // Move to next step
    document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    
    document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
    document.querySelector(`.step[data-step="${step}"]`).classList.add('active');
    
    // Mark previous steps as completed
    for (let i = 1; i < step; i++) {
        document.querySelector(`.step[data-step="${i}"]`).classList.add('completed');
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    
    document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
    document.querySelector(`.step[data-step="${step}"]`).classList.add('active');
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Character counter
document.getElementById('project_summary')?.addEventListener('input', function() {
    const counter = document.querySelector('.char-counter');
    counter.textContent = `${this.value.length} / 50 karakter`;
    
    if (this.value.length >= 50) {
        counter.style.color = 'green';
    } else {
        counter.style.color = '#666';
    }
});

// TC number validation
document.getElementById('tc_number')?.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11);
});

// Phone formatting
document.getElementById('phone')?.addEventListener('input', function() {
    let value = this.value.replace(/[^0-9+]/g, '');
    this.value = value;
});
</script>

