<?php
$pageTitle = 'İletişim - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER ile iletişime geçin.';
$currentPage = 'contact';

logPageView('contact');

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rate limiting
    $ip = Security::getClientIp();
    if (!Security::checkRateLimit($ip, 5, 3600, 'contact')) {
        $error = 'Çok fazla istek gönderdiniz. Lütfen daha sonra tekrar deneyin.';
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
                $validator->required('name', 'Ad Soyad alanı zorunludur')
                         ->required('email', 'E-posta alanı zorunludur')
                         ->email('email', 'Geçerli bir e-posta adresi giriniz')
                         ->required('message', 'Mesaj alanı zorunludur')
                         ->minLength('message', 10, 'Mesaj en az 10 karakter olmalıdır');
                
                if ($_POST['phone'] ?? '') {
                    $validator->phone('phone');
                }
                
                if ($validator->fails()) {
                    $error = $validator->getFirstError();
                } else {
                    // Save to database
                    try {
                        $db = Database::getInstance();
                        $db->execute(
                            'INSERT INTO contact_submissions (name, email, phone, subject, message, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)',
                            [
                                Security::cleanInput($_POST['name']),
                                Security::cleanInput($_POST['email']),
                                Security::normalizePhone($_POST['phone'] ?? ''),
                                Security::cleanInput($_POST['subject'] ?? ''),
                                Security::cleanInput($_POST['message']),
                                $ip,
                                Security::getUserAgent()
                            ]
                        );
                        
                        $success = true;
                    } catch (Exception $e) {
                        $error = 'Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
                        if (getenv('DEBUG_MODE') === 'true') {
                            $error .= ' - ' . $e->getMessage();
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

<section class="page-header">
    <div class="container">
        <h1>İletişim</h1>
        <p>Bizimle iletişime geçin</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info">
                <h2>İletişim Bilgileri</h2>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3>Adres</h3>
                        <p><?php echo Security::escape(getSetting('contact_address')); ?></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h3>Telefon</h3>
                        <p><a href="tel:<?php echo getSetting('contact_phone'); ?>"><?php echo getSetting('contact_phone'); ?></a></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h3>E-posta</h3>
                        <p><a href="mailto:<?php echo getSetting('contact_email'); ?>"><?php echo getSetting('contact_email'); ?></a></p>
                    </div>
                </div>
                
                <?php if (getSetting('google_maps_url')): ?>
                    <div class="map-container">
                        <iframe 
                            src="<?php echo str_replace('app.goo.gl', 'maps.google.com/maps?q=', getSetting('google_maps_url')) . '&output=embed'; ?>"
                            width="100%" 
                            height="300" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-container">
                <h2>Mesaj Gönder</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo Security::escape($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!$success): ?>
                    <form method="POST" class="contact-form">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        
                        <div class="form-group">
                            <label for="name">Ad Soyad *</label>
                            <input type="text" id="name" name="name" required 
                                   value="<?php echo Security::escape($_POST['name'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">E-posta *</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo Security::escape($_POST['email'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo Security::escape($_POST['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Konu</label>
                            <input type="text" id="subject" name="subject" 
                                   value="<?php echo Security::escape($_POST['subject'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Mesajınız *</label>
                            <textarea id="message" name="message" rows="5" required><?php echo Security::escape($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="turnstile-container">
                            <div class="cf-turnstile" data-sitekey="<?php echo getenv('TURNSTILE_SITE_KEY'); ?>"></div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Gönder
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php 
$additionalJs = ['turnstile'];
include __DIR__ . '/../includes/footer.php'; 
?>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

