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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background Circles */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
        }
        
        body::before {
            width: 500px;
            height: 500px;
            top: -250px;
            left: -250px;
            animation-delay: 0s;
        }
        
        body::after {
            width: 400px;
            height: 400px;
            bottom: -200px;
            right: -200px;
            animation-delay: 5s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-box {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .login-header {
            text-align: center;
            padding: 48px 40px 32px;
            background: white;
        }
        
        .logo-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .logo-wrapper i {
            font-size: 36px;
            color: white;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .login-header p {
            color: #718096;
            font-size: 15px;
            font-weight: 500;
        }
        
        .alert {
            margin: 0 40px 24px;
            padding: 14px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .alert i {
            font-size: 18px;
        }
        
        .login-form {
            padding: 0 40px 40px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 16px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .form-group input:focus + i {
            color: #667eea;
        }
        
        .turnstile-container {
            margin-bottom: 24px;
            display: flex;
            justify-content: center;
            min-height: 65px;
        }
        
        .btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: inherit;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .login-footer {
            padding: 24px 40px;
            background: #f7fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .login-footer p {
            margin: 0;
            color: #718096;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .login-footer i {
            color: #48bb78;
            font-size: 14px;
        }
        
        /* Loading state */
        .btn-primary.loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-primary.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
            }
            
            .login-box {
                border-radius: 20px;
            }
            
            .login-header {
                padding: 40px 30px 24px;
            }
            
            .login-header h1 {
                font-size: 24px;
            }
            
            .login-form {
                padding: 0 30px 30px;
            }
            
            .alert {
                margin: 0 30px 20px;
            }
            
            .login-footer {
                padding: 20px 30px;
            }
            
            .logo-wrapper {
                width: 70px;
                height: 70px;
            }
            
            .logo-wrapper i {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo-wrapper">
                    <i class="fas fa-rocket"></i>
                </div>
                <h1>Alanya TEKMER</h1>
                <p>Admin Paneli</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo Security::escape($error); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <div class="form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" placeholder="admin" required autofocus>
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                
                <div class="turnstile-container">
                    <div class="cf-turnstile" data-sitekey="<?php echo getenv('TURNSTILE_SITE_KEY'); ?>"></div>
                </div>
                
                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Giriş Yap</span>
                </button>
            </form>
            
            <div class="login-footer">
                <p>
                    <i class="fas fa-shield-alt"></i>
                    <small>Güvenli bağlantı - Tüm işlemler şifrelenir</small>
                </p>
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
                container.innerHTML = '<div style="color: #f59e0b; font-size: 13px; text-align: center;"><i class="fas fa-exclamation-triangle"></i> CAPTCHA yüklenemedi</div>';
            }
        }
        
        // Check if Turnstile loaded successfully after 5 seconds
        setTimeout(function() {
            const turnstileWidget = document.querySelector('.cf-turnstile');
            if (turnstileWidget && !turnstileWidget.querySelector('iframe')) {
                console.warn('Turnstile widget yüklenmedi');
            }
        }, 5000);
        
        // Form submission handling
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const username = document.getElementById('username').value.trim();
                const password = document.getElementById('password').value;
                
                if (!username || !password) {
                    e.preventDefault();
                    
                    // Show error
                    let errorDiv = document.querySelector('.alert-danger');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger';
                        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>Lütfen tüm alanları doldurun</span>';
                        loginForm.parentNode.insertBefore(errorDiv, loginForm);
                    } else {
                        errorDiv.querySelector('span').textContent = 'Lütfen tüm alanları doldurun';
                    }
                    
                    return false;
                }
                
                // Add loading state
                loginBtn.classList.add('loading');
                loginBtn.querySelector('span').textContent = 'Giriş yapılıyor...';
            });
        }
        
        // Auto-hide error messages after 8 seconds
        const errorAlert = document.querySelector('.alert-danger');
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.opacity = '0';
                errorAlert.style.transition = 'opacity 0.5s ease';
                setTimeout(function() {
                    errorAlert.remove();
                }, 500);
            }, 8000);
        }
        
        // Input animations
        const inputs = document.querySelectorAll('.form-group input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
        
        // Keyboard shortcut (Ctrl/Cmd + Enter to submit)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                loginForm?.submit();
            }
        });
        
        // Add subtle parallax effect to background
        if (window.innerWidth > 768) {
            document.addEventListener('mousemove', function(e) {
                const x = (e.clientX / window.innerWidth - 0.5) * 20;
                const y = (e.clientY / window.innerHeight - 0.5) * 20;
                document.body.style.backgroundPosition = `${x}px ${y}px`;
            });
        }
    </script>
</body>
</html>

