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
            // Turnstile verification
            if (!Security::verifyTurnstile($_POST['cf-turnstile-response'] ?? '')) {
                $error = 'Captcha doğrulaması başarısız.';
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
                            
                            // Log activity
                            $db->execute(
                                'INSERT INTO activity_log (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)',
                                [$user['id'], 'admin_login', Security::getClientIp(), $_SERVER['HTTP_USER_AGENT'] ?? '']
                            );
                            
                            redirect(url(getenv('ADMIN_PATH') . '/dashboard'));
                        } else {
                            $error = 'Kullanıcı adı veya şifre hatalı.';
                        }
                    } catch (Exception $e) {
                        error_log('Admin login error: ' . $e->getMessage());
                        $error = 'Giriş işlemi sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
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
    
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</body>
</html>

