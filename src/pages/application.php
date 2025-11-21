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
                                
                                // Create notification for new application
                                $applicationId = $db->lastInsertId();
                                $db->execute(
                                    'INSERT INTO notifications (type, title, message, reference_type, reference_id) 
                                     VALUES (?, ?, ?, ?, ?)',
                                    [
                                        'new_application',
                                        'Yeni Başvuru',
                                        Security::cleanInput($_POST['full_name']) . ' adlı kişiden yeni bir başvuru geldi. Proje: ' . Security::cleanInput($_POST['project_name']),
                                        'application',
                                        $applicationId
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
include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header with Gradient -->
<section class="application-hero">
    <div class="container">
        <div class="hero-content" data-aos="fade-up">
            <div class="hero-icon">
                <i class="fas fa-rocket"></i>
            </div>
            <h1>Başvuru Formu</h1>
            <p>Alanya TEKMER'e başvurun ve girişim ekosisteminin bir parçası olun</p>
        </div>
    </div>
</section>

<section class="application-wizard-section">
    <div class="container">
        <?php if ($success): ?>
            <div class="success-card">
                <div class="success-animation">
                    <div class="success-checkmark">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                        </div>
                    </div>
                </div>
                <h2>Başvurunuz Başarıyla Alındı!</h2>
                <p>Başvurunuz başarıyla kaydedildi. İcra Kurulumuz projenizi detaylı inceleyecektir.</p>
                <p class="success-note">Komite kararı sonucu size e-posta veya telefon ile ulaşılacaktır.</p>
                <a href="<?php echo url(); ?>" class="btn btn-success btn-lg">
                    <i class="fas fa-home"></i>
                    Ana Sayfaya Dön
                </a>
            </div>
        <?php else: ?>
            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="wizard-alert alert-danger" data-aos="fade-down">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Hata!</strong>
                        <p><?php echo Security::escape($error); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Wizard Container -->
            <div class="wizard-container" data-aos="fade-up">
                <!-- Progress Bar -->
                <div class="wizard-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <div class="progress-text">
                        <span id="progressText">Adım 1 / 3</span>
                    </div>
                </div>
                
                <!-- Steps Indicator -->
                <div class="wizard-steps">
                    <div class="wizard-step active" data-step="1">
                        <div class="step-circle">
                            <div class="step-number">1</div>
                            <div class="step-check"><i class="fas fa-check"></i></div>
                        </div>
                        <div class="step-label">
                            <div class="step-title">Kişisel Bilgiler</div>
                            <div class="step-desc">Kimlik bilgileriniz</div>
                        </div>
                        <div class="step-connector"></div>
                    </div>
                    
                    <div class="wizard-step" data-step="2">
                        <div class="step-circle">
                            <div class="step-number">2</div>
                            <div class="step-check"><i class="fas fa-check"></i></div>
                        </div>
                        <div class="step-label">
                            <div class="step-title">Proje Bilgileri</div>
                            <div class="step-desc">Proje detayları</div>
                        </div>
                        <div class="step-connector"></div>
                    </div>
                    
                    <div class="wizard-step" data-step="3">
                        <div class="step-circle">
                            <div class="step-number">3</div>
                            <div class="step-check"><i class="fas fa-check"></i></div>
                        </div>
                        <div class="step-label">
                            <div class="step-title">Proje Detayları</div>
                            <div class="step-desc">Son adım</div>
                        </div>
                    </div>
                </div>
                
                <!-- Form -->
                <form method="POST" enctype="multipart/form-data" class="wizard-form" id="applicationForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    
                    <!-- Step 1: Personal Info -->
                    <div class="form-step show" data-step="1">
                        <div class="step-header">
                            <div class="step-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h2>Kişisel Bilgileriniz</h2>
                            <p>Sizinle iletişim kurmak için bilgilerinize ihtiyacımız var</p>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="full_name">
                                    <i class="fas fa-user"></i>
                                    Ad Soyad *
                                </label>
                                <input type="text" id="full_name" name="full_name" class="form-control" required 
                                       placeholder="Adınız ve soyadınız">
                                <span class="error-message"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i>
                                    E-posta Adresi *
                                </label>
                                <input type="email" id="email" name="email" class="form-control" required 
                                       placeholder="ornek@email.com">
                                <span class="error-message"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">
                                    <i class="fas fa-phone"></i>
                                    Telefon *
                                </label>
                                <input type="tel" id="phone" name="phone" class="form-control" required 
                                       placeholder="+90 5XX XXX XX XX">
                                <span class="error-message"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="tc_number">
                                    <i class="fas fa-id-card"></i>
                                    TC Kimlik No *
                                </label>
                                <input type="text" id="tc_number" name="tc_number" class="form-control" required 
                                       maxlength="11" placeholder="11 haneli TC kimlik numarası">
                                <span class="error-message"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="university">
                                    <i class="fas fa-graduation-cap"></i>
                                    Üniversite
                                </label>
                                <input type="text" id="university" name="university" class="form-control" 
                                       placeholder="Okuduğunuz üniversite (varsa)">
                            </div>
                            
                            <div class="form-group">
                                <label for="department">
                                    <i class="fas fa-book"></i>
                                    Bölüm
                                </label>
                                <input type="text" id="department" name="department" class="form-control" 
                                       placeholder="Bölümünüz (varsa)">
                            </div>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="company_name">
                                <i class="fas fa-building"></i>
                                Firma Adı
                            </label>
                            <input type="text" id="company_name" name="company_name" class="form-control" 
                                   placeholder="Firma adınız (varsa)">
                        </div>
                    </div>
                    
                    <!-- Step 2: Project Info -->
                    <div class="form-step" data-step="2">
                        <div class="step-header">
                            <div class="step-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h2>Proje Bilgileriniz</h2>
                            <p>Projeniz hakkında temel bilgileri paylaşın</p>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="project_name">
                                <i class="fas fa-project-diagram"></i>
                                Proje Adı *
                            </label>
                            <input type="text" id="project_name" name="project_name" class="form-control" required 
                                   placeholder="Projenizin adı">
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="project_type">
                                    <i class="fas fa-tag"></i>
                                    Proje Türü *
                                </label>
                                <select id="project_type" name="project_type" class="form-control" required>
                                    <option value="">Seçiniz</option>
                                    <?php foreach ($projectTypes as $type): ?>
                                        <option value="<?php echo Security::escape($type['value']); ?>">
                                            <?php echo Security::escape($type['value']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="error-message"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="activity_area">
                                    <i class="fas fa-industry"></i>
                                    Faaliyet Alanı *
                                </label>
                                <select id="activity_area" name="activity_area" class="form-control" required>
                                    <option value="">Seçiniz</option>
                                    <?php foreach ($activityAreas as $area): ?>
                                        <option value="<?php echo Security::escape($area['value']); ?>">
                                            <?php echo Security::escape($area['value']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="error-message"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="requested_space">
                                    <i class="fas fa-map-marked-alt"></i>
                                    Talep Edilen Alan *
                                </label>
                                <select id="requested_space" name="requested_space" class="form-control" required>
                                    <option value="">Seçiniz</option>
                                    <?php foreach ($requestedSpaces as $space): ?>
                                        <option value="<?php echo Security::escape($space['value']); ?>">
                                            <?php echo Security::escape($space['value']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="error-message"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="team_size">
                                    <i class="fas fa-users"></i>
                                    Ekip Büyüklüğü *
                                </label>
                                <input type="number" id="team_size" name="team_size" class="form-control" required 
                                       min="1" placeholder="Kaç kişi?">
                                <span class="error-message"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Project Details -->
                    <div class="form-step" data-step="3">
                        <div class="step-header">
                            <div class="step-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h2>Proje Detayları</h2>
                            <p>Projenizi daha detaylı anlatın ve belgelerinizi yükleyin</p>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="project_summary">
                                <i class="fas fa-align-left"></i>
                                Proje Özeti * (En az 50 karakter)
                            </label>
                            <textarea id="project_summary" name="project_summary" class="form-control" required 
                                      rows="6" placeholder="Projenizi kısaca tanıtın..."></textarea>
                            <div class="char-counter-wrapper">
                                <span class="char-counter" id="charCounter">0 / 50 karakter</span>
                            </div>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="expectations">
                                <i class="fas fa-bullseye"></i>
                                TEKMER'den Beklentileriniz
                            </label>
                            <textarea id="expectations" name="expectations" class="form-control" 
                                      rows="4" placeholder="Bizden beklentilerinizi paylaşın..."></textarea>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="project_file">
                                <i class="fas fa-file-pdf"></i>
                                Proje Dosyası * (PDF, Maksimum 5MB)
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="project_file" name="project_file" class="form-control" accept=".pdf" required>
                                <div class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>PDF dosyası seçin veya sürükleyin</span>
                                    <small>Maksimum dosya boyutu: 5MB</small>
                                </div>
                                <div class="file-upload-name"></div>
                            </div>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group full-width">
                            <div class="consent-box">
                                <input type="checkbox" id="data_consent" name="data_consent" required>
                                <label for="data_consent">
                                    <span class="checkbox-icon"></span>
                                    <span class="checkbox-text">
                                        Verdiğim tüm bilgiler doğru ve bana aittir. Bu bilgiler başvurumun değerlendirilmesi ve bana ulaşılmasında kullanılabilir. 
                                        <a href="<?php echo url('kvkk'); ?>" target="_blank">KVKK Bilgilendirme</a> metnini okudum, anladım ve kabul ediyorum. *
                                    </span>
                                </label>
                                <span class="error-message"></span>
                            </div>
                        </div>
                        
                        <div class="turnstile-wrapper">
                            <div class="cf-turnstile" data-sitekey="<?php echo getenv('TURNSTILE_SITE_KEY'); ?>"></div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="wizard-navigation">
                        <button type="button" class="btn btn-secondary btn-prev" onclick="prevStep()" style="display: none;">
                            <i class="fas fa-arrow-left"></i>
                            Geri
                        </button>
                        <button type="button" class="btn btn-primary btn-next" onclick="nextStep()">
                            İleri
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="submit" class="btn btn-success btn-submit" style="display: none;">
                            <i class="fas fa-paper-plane"></i>
                            Başvuruyu Gönder
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Application Wizard Styles -->
<style>
/* Hero Section */
.application-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
}

.application-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.hero-content {
    text-align: center;
    position: relative;
    z-index: 1;
    color: white;
}

.hero-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 36px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.hero-content h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 15px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.hero-content p {
    font-size: 1.1rem;
    opacity: 0.95;
}

/* Wizard Section */
.application-wizard-section {
    padding: 60px 0 80px;
    background: #f8f9fa;
}

/* Success Card */
.success-card {
    background: white;
    border-radius: 20px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
    animation: slideUpFade 0.5s ease-out;
}

@keyframes slideUpFade {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-animation {
    margin-bottom: 30px;
}

.success-checkmark {
    width: 100px;
    height: 100px;
    margin: 0 auto;
    position: relative;
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.check-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #4BB543, #3a9337);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(75, 181, 67, 0.3);
    position: relative;
}

.check-icon::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(75, 181, 67, 0.2);
    border-radius: 50%;
    animation: ripple 2s infinite;
}

@keyframes ripple {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.icon-line {
    position: absolute;
}

.icon-line.line-tip {
    width: 30px;
    height: 5px;
    background: white;
    left: 24px;
    top: 52px;
    transform: rotate(45deg);
    border-radius: 3px;
    animation: drawTip 0.4s ease-out 0.2s forwards;
    transform-origin: left center;
    opacity: 0;
}

.icon-line.line-long {
    width: 50px;
    height: 5px;
    background: white;
    right: 16px;
    top: 45px;
    transform: rotate(-45deg);
    border-radius: 3px;
    animation: drawLong 0.4s ease-out 0.4s forwards;
    transform-origin: right center;
    opacity: 0;
}

@keyframes drawTip {
    0% {
        width: 0;
        opacity: 0;
    }
    100% {
        width: 30px;
        opacity: 1;
    }
}

@keyframes drawLong {
    0% {
        width: 0;
        opacity: 0;
    }
    100% {
        width: 50px;
        opacity: 1;
    }
}

/* Remove unused elements */
.icon-circle,
.icon-fix {
    display: none;
}

.success-card h2 {
    color: #28a745;
    font-size: 2rem;
    margin-bottom: 15px;
}

.success-card p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 10px;
}

.success-note {
    background: #f0f9ff;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 0;
}

/* Wizard Container */
.wizard-container {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: 0 auto;
}

/* Progress Bar */
.wizard-progress {
    margin-bottom: 40px;
}

.progress-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 10px;
    width: 33.33%;
    transition: width 0.4s ease;
}

.progress-text {
    text-align: center;
    font-weight: 600;
    color: #667eea;
}

/* Steps Indicator */
.wizard-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 50px;
    position: relative;
}

.wizard-step {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.step-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e9ecef;
    border: 3px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
    margin-bottom: 10px;
}

.step-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #999;
    transition: all 0.3s ease;
}

.step-check {
    display: none;
    color: white;
    font-size: 1.5rem;
}

.wizard-step.active .step-circle {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    transform: scale(1.1);
}

.wizard-step.active .step-number {
    color: white;
}

.wizard-step.completed .step-circle {
    background: #28a745;
    border-color: #28a745;
}

.wizard-step.completed .step-number {
    display: none;
}

.wizard-step.completed .step-check {
    display: block;
}

.step-label {
    text-align: center;
}

.step-title {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
    margin-bottom: 3px;
}

.step-desc {
    font-size: 0.75rem;
    color: #999;
}

.wizard-step.active .step-title {
    color: #667eea;
}

.step-connector {
    position: absolute;
    top: 30px;
    left: 60%;
    width: 80%;
    height: 3px;
    background: #e9ecef;
    z-index: 1;
    transition: background 0.3s ease;
}

.wizard-step:last-child .step-connector {
    display: none;
}

.wizard-step.completed .step-connector {
    background: #28a745;
}

/* Form Steps */
.wizard-form {
    min-height: 400px;
    position: relative;
}

.form-step {
    display: none;
    animation: fadeInSlide 0.5s ease;
}

.form-step.show {
    display: block;
}

@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.step-header {
    text-align: center;
    margin-bottom: 40px;
}

.step-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 32px;
}

.step-header h2 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 10px;
}

.step-header p {
    color: #666;
    font-size: 1rem;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group label i {
    margin-right: 8px;
    color: #667eea;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-control.error {
    border-color: #dc3545;
}

.error-message {
    display: none;
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 5px;
}

.form-control.error + .error-message {
    display: block;
}

/* Character Counter */
.char-counter-wrapper {
    text-align: right;
    margin-top: 5px;
}

.char-counter {
    font-size: 0.85rem;
    color: #999;
}

.char-counter.valid {
    color: #28a745;
    font-weight: 600;
}

/* File Upload */
.file-upload-wrapper {
    position: relative;
}

.file-upload-wrapper input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-upload-label {
    border: 2px dashed #667eea;
    background: #f8f9ff;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-label:hover {
    border-color: #764ba2;
    background: #f0f2ff;
}

.file-upload-label i {
    font-size: 48px;
    color: #667eea;
    display: block;
    margin-bottom: 10px;
}

.file-upload-label span {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.file-upload-label small {
    color: #999;
    font-size: 0.85rem;
}

.file-upload-name {
    margin-top: 10px;
    padding: 10px;
    background: #e7f3ff;
    border-radius: 8px;
    color: #667eea;
    font-weight: 600;
    display: none;
}

.file-upload-name.active {
    display: block;
}

/* Consent Box */
.consent-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
}

.consent-box input[type="checkbox"] {
    display: none;
}

.consent-box label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    margin: 0;
}

.checkbox-icon {
    width: 24px;
    height: 24px;
    border: 2px solid #667eea;
    border-radius: 6px;
    flex-shrink: 0;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-icon::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    color: white;
    font-weight: bold;
    transition: transform 0.3s ease;
}

.consent-box input[type="checkbox"]:checked + label .checkbox-icon {
    background: #667eea;
}

.consent-box input[type="checkbox"]:checked + label .checkbox-icon::after {
    transform: translate(-50%, -50%) scale(1);
}

.checkbox-text {
    font-size: 0.9rem;
    line-height: 1.6;
    color: #555;
}

.checkbox-text a {
    color: #667eea;
    text-decoration: underline;
}

/* Turnstile Wrapper */
.turnstile-wrapper {
    margin: 30px 0;
    display: flex;
    justify-content: center;
}

/* Wizard Navigation */
.wizard-navigation {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e9ecef;
}

.wizard-navigation .btn {
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateX(-3px);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    margin-left: auto;
}

.btn-primary:hover {
    transform: translateX(3px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    margin-left: auto;
}

.btn-success:hover {
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

/* Alert */
.wizard-alert {
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.wizard-alert.alert-danger {
    background: #fee;
    border-left: 4px solid #dc3545;
}

.alert-icon {
    font-size: 24px;
    color: #dc3545;
}

.alert-content strong {
    display: block;
    margin-bottom: 5px;
    color: #dc3545;
}

.alert-content p {
    margin: 0;
    color: #721c24;
}

/* Responsive Design */
@media (max-width: 768px) {
    .application-hero {
        padding: 60px 0 40px;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .hero-icon {
        width: 60px;
        height: 60px;
        font-size: 28px;
    }
    
    .wizard-container {
        padding: 25px 20px;
    }
    
    .wizard-steps {
        flex-direction: column;
        gap: 20px;
    }
    
    .wizard-step {
        flex-direction: row;
        justify-content: flex-start;
        text-align: left;
    }
    
    .step-circle {
        margin-right: 15px;
        margin-bottom: 0;
        width: 50px;
        height: 50px;
    }
    
    .step-number {
        font-size: 1.2rem;
    }
    
    .step-connector {
        display: none;
    }
    
    .step-label {
        text-align: left;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .step-icon {
        width: 60px;
        height: 60px;
        font-size: 28px;
    }
    
    .step-header h2 {
        font-size: 1.5rem;
    }
    
    .wizard-navigation {
        flex-direction: column;
    }
    
    .wizard-navigation .btn {
        width: 100%;
        justify-content: center;
    }
    
    .btn-primary,
    .btn-success {
        margin-left: 0;
    }
    
    .success-card {
        padding: 40px 20px;
    }
}

@media (max-width: 576px) {
    .wizard-container {
        padding: 20px 15px;
        border-radius: 12px;
    }
    
    .form-control {
        padding: 12px 14px;
    }
    
    .step-desc {
        display: none;
    }
    
    .file-upload-label {
        padding: 30px 15px;
    }
    
    .file-upload-label i {
        font-size: 36px;
    }
}
</style>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
// Initialize AOS
AOS.init({
    duration: 600,
    easing: 'ease-in-out',
    once: true
});

// Wizard State
let currentStep = 1;
const totalSteps = 3;

// Update Progress
function updateProgress() {
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const percentage = (currentStep / totalSteps) * 100;
    
    progressFill.style.width = percentage + '%';
    progressText.textContent = `Adım ${currentStep} / ${totalSteps}`;
}

// Validate Step
function validateStep(step) {
    const formStep = document.querySelector(`.form-step[data-step="${step}"]`);
    const requiredInputs = formStep.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    requiredInputs.forEach(input => {
        const errorMsg = input.closest('.form-group')?.querySelector('.error-message');
        
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            if (errorMsg) {
                errorMsg.textContent = 'Bu alan zorunludur';
                errorMsg.style.display = 'block';
            }
        } else if (input.type === 'email' && !isValidEmail(input.value)) {
            isValid = false;
            input.classList.add('error');
            if (errorMsg) {
                errorMsg.textContent = 'Geçerli bir e-posta adresi girin';
                errorMsg.style.display = 'block';
            }
        } else if (input.id === 'tc_number' && input.value.length !== 11) {
            isValid = false;
            input.classList.add('error');
            if (errorMsg) {
                errorMsg.textContent = 'TC Kimlik No 11 haneli olmalıdır';
                errorMsg.style.display = 'block';
            }
        } else if (input.id === 'project_summary' && input.value.length < 50) {
            isValid = false;
            input.classList.add('error');
            if (errorMsg) {
                errorMsg.textContent = 'Proje özeti en az 50 karakter olmalıdır';
                errorMsg.style.display = 'block';
            }
        } else {
            input.classList.remove('error');
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
        }
    });
    
    // Check checkbox for step 3
    if (step === 3) {
        const checkbox = document.getElementById('data_consent');
        const errorMsg = checkbox.closest('.form-group')?.querySelector('.error-message');
        if (!checkbox.checked) {
            isValid = false;
            if (errorMsg) {
                errorMsg.textContent = 'KVKK onayını kabul etmelisiniz';
                errorMsg.style.display = 'block';
            }
        } else {
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
        }
    }
    
    return isValid;
}

// Email Validation
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Next Step
function nextStep() {
    if (!validateStep(currentStep)) {
        // Scroll to first error
        const firstError = document.querySelector('.form-control.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
        return;
    }
    
    if (currentStep < totalSteps) {
        // Hide current step
        document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('show');
        
        // Mark current step as completed
        document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.add('completed');
        document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.remove('active');
        
        // Move to next step
        currentStep++;
        
        // Show next step
        setTimeout(() => {
            document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('show');
            document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.add('active');
            updateProgress();
            updateButtons();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 300);
    }
}

// Previous Step
function prevStep() {
    if (currentStep > 1) {
        // Hide current step
        document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('show');
        document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.remove('active');
        
        // Move to previous step
        currentStep--;
        
        // Show previous step
        setTimeout(() => {
            document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('show');
            document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.add('active');
            document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.remove('completed');
            updateProgress();
            updateButtons();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 300);
    }
}

// Update Navigation Buttons
function updateButtons() {
    const btnPrev = document.querySelector('.btn-prev');
    const btnNext = document.querySelector('.btn-next');
    const btnSubmit = document.querySelector('.btn-submit');
    
    // Show/hide prev button
    if (currentStep === 1) {
        btnPrev.style.display = 'none';
    } else {
        btnPrev.style.display = 'flex';
    }
    
    // Show/hide next vs submit button
    if (currentStep === totalSteps) {
        btnNext.style.display = 'none';
        btnSubmit.style.display = 'flex';
    } else {
        btnNext.style.display = 'flex';
        btnSubmit.style.display = 'none';
    }
}

// Character Counter
const projectSummary = document.getElementById('project_summary');
if (projectSummary) {
    projectSummary.addEventListener('input', function() {
        const counter = document.getElementById('charCounter');
        const length = this.value.length;
        counter.textContent = `${length} / 50 karakter`;
        
        if (length >= 50) {
            counter.classList.add('valid');
        } else {
            counter.classList.remove('valid');
        }
    });
}

// TC Number Validation
const tcNumber = document.getElementById('tc_number');
if (tcNumber) {
    tcNumber.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11);
    });
}

// Phone Formatting
const phone = document.getElementById('phone');
if (phone) {
    phone.addEventListener('input', function() {
        let value = this.value.replace(/[^0-9+]/g, '');
        this.value = value;
    });
}

// File Upload
const fileInput = document.getElementById('project_file');
if (fileInput) {
    fileInput.addEventListener('change', function() {
        const fileName = this.files[0]?.name;
        const fileNameDisplay = document.querySelector('.file-upload-name');
        
        if (fileName) {
            fileNameDisplay.textContent = fileName;
            fileNameDisplay.classList.add('active');
        } else {
            fileNameDisplay.classList.remove('active');
        }
    });
}

// Remove error on input
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('error');
        const errorMsg = this.closest('.form-group')?.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.style.display = 'none';
        }
    });
});

// Initialize
updateProgress();
updateButtons();

// Form submission validation
document.getElementById('applicationForm')?.addEventListener('submit', function(e) {
    if (!validateStep(currentStep)) {
        e.preventDefault();
        const firstError = document.querySelector('.form-control.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
});
</script>

