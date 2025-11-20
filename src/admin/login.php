<?php
// Admin login

if (isAdmin()) {
    redirect(url(getenv('ADMIN_PATH') . '/dashboard'));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = Security::getClientIp();
    
    // Rate limiting - 10 attempts per 5 minutes
    if (!Security::checkRateLimit($ip, 10, 300, 'admin_login')) {
        $error = 'Çok fazla başarısız giriş denemesi. 5 dakika sonra tekrar deneyin.';
    } else {
        // CSRF check
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $error = 'Geçersiz istek.';
        } else {
            // Turnstile verification - allow bypass in development
            $turnstileResponse = $_POST['cf-turnstile-response'] ?? '';
            $bypassTurnstile = getenv('BYPASS_TURNSTILE') === 'true' || getenv('DEBUG_MODE') === 'true';
            
            if (!$bypassTurnstile && !Security::verifyTurnstile($turnstileResponse)) {
                $error = 'Captcha doğrulaması başarısız. Lütfen sayfayı yenileyin.';
                
                // Log for debugging
                error_log('Turnstile verification failed for IP: ' . $ip);
            } else {
                $username = Security::cleanInput($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                
                if (empty($username) || empty($password)) {
                    $error = 'Kullanıcı adı ve şifre gereklidir.';
                } else {
                    try {
                        $db = Database::getInstance();
                        $user = $db->fetchOne(
                            'SELECT * FROM admin_users WHERE username = ? AND is_active = true',
                            [$username]
                        );
                        
                        if ($user && Security::verifyPassword($password, $user['password_hash'])) {
                            $_SESSION['admin_logged_in'] = true;
                            $_SESSION['admin_user_id'] = $user['id'];
                            $_SESSION['admin_username'] = $user['username'];
                            
                            // Regenerate session ID
                            session_regenerate_id(true);
                            
                            // Update last login
                            $db->execute(
                                'UPDATE admin_users SET last_login = CURRENT_TIMESTAMP WHERE id = ?',
                                [$user['id']]
                            );
                            
                            // Log activity - check if activity_log table exists
                            try {
                                $db->execute(
                                    'INSERT INTO activity_log (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)',
                                    [$user['id'], 'admin_login', Security::getClientIp(), $_SERVER['HTTP_USER_AGENT'] ?? '']
                                );
                            } catch (Exception $logError) {
                                // Log the error but don't fail login
                                error_log('Activity log error: ' . $logError->getMessage());
                            }
                            
                            redirect(url(getenv('ADMIN_PATH') . '/dashboard'));
                        } else {
                            $error = 'Kullanıcı adı veya şifre hatalı.';
                            
                            // Debug mode - show more info
                            if (getenv('DEBUG_MODE') === 'true') {
                                if (!$user) {
                                    $error .= ' (Kullanıcı bulunamadı)';
                                } else if (!Security::verifyPassword($password, $user['password_hash'])) {
                                    $error .= ' (Şifre eşleşmiyor)';
                                }
                            }
                        }
                    } catch (Exception $e) {
                        error_log('Admin login error: ' . $e->getMessage());
                        error_log('Stack trace: ' . $e->getTraceAsString());
                        
                        $error = 'Giriş işlemi sırasında bir hata oluştu.';
                        
                        // Show detailed error in debug mode
                        if (getenv('DEBUG_MODE') === 'true') {
                            $error .= ' Detay: ' . $e->getMessage();
                        } else {
                            $error .= ' Lütfen daha sonra tekrar deneyin.';
                        }
                    }
                }
            }
        }
    }
}

$csrfToken = Security::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - Alanya TEKMER</title>
    <link rel="stylesheet" href="<?php echo asset('css/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="<?php echo asset('images/logo.png'); ?>" alt="Alanya TEKMER">
                <h1>Admin Paneli</h1>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo Security::escape($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <div class="form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="turnstile-container">
                    <div class="cf-turnstile" data-sitekey="<?php echo getenv('TURNSTILE_SITE_KEY'); ?>"></div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </button>
            </form>
            
            <div class="login-footer">
                <p><small>Güvenli admin paneli - Tüm işlemler loglanmaktadır</small></p>
            </div>
        </div>
    </div>
    
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer onerror="handleTurnstileError()"></script>
    <script>
        // Handle Turnstile loading errors
        function handleTurnstileError() {
            console.error('Turnstile yüklenemedi');
            const container = document.querySelector('.turnstile-container');
            if (container) {
                container.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> CAPTCHA yüklenemedi. Yine de giriş yapabilirsiniz.</div>';
            }
        }
        
        // Check if Turnstile loaded successfully after 5 seconds
        setTimeout(function() {
            const turnstileWidget = document.querySelector('.cf-turnstile');
            if (turnstileWidget && !turnstileWidget.querySelector('iframe')) {
                console.warn('Turnstile widget yüklenmedi');
            }
        }, 5000);
        
        // Better form validation
        document.querySelector('.login-form')?.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                alert('Lütfen kullanıcı adı ve şifre girin.');
                return false;
            }
        });
        
        // Auto-hide error messages after 10 seconds
        const errorAlert = document.querySelector('.alert-danger');
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.opacity = '0';
                errorAlert.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    errorAlert.style.display = 'none';
                }, 500);
            }, 10000);
        }
    </script>
</body>
</html>

