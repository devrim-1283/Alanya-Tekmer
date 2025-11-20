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

<!-- Hero Header -->
<section style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); padding: 100px 0 80px; color: white; text-align: center;">
    <div class="container">
        <div data-aos="fade-up">
            <span style="display: inline-block; background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 50px; font-size: 0.9em; margin-bottom: 20px;">
                <i class="fas fa-envelope"></i> Bize Ulaşın
            </span>
            <h1 style="font-size: 3em; font-weight: 800; margin-bottom: 20px; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">İletişim</h1>
            <p style="font-size: 1.3em; max-width: 700px; margin: 0 auto; opacity: 0.95;">Sorularınız, önerileriniz veya işbirliği teklifleriniz için bizimle iletişime geçin</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section style="padding: 80px 0; background: #f9fafb;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 50px; align-items: start;">
            
            <!-- Contact Info -->
            <div data-aos="fade-right">
                <div style="background: linear-gradient(135deg, #1e40af, #3b82f6); border-radius: 24px; padding: 40px; color: white; box-shadow: 0 20px 60px rgba(30, 64, 175, 0.3); position: sticky; top: 100px;">
                    <h2 style="font-size: 2em; font-weight: 800; margin-bottom: 30px; color: white;">İletişim Bilgileri</h2>
                    <p style="opacity: 0.95; margin-bottom: 35px; line-height: 1.7;">Herhangi bir sorunuz varsa bizimle iletişime geçmekten çekinmeyin. Size en kısa sürede dönüş yapacağız.</p>
                    
                    <div style="margin-bottom: 25px; display: flex; gap: 20px; align-items: flex-start;">
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-map-marker-alt" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.1em; font-weight: 700; margin-bottom: 8px; color: white;">Adres</h3>
                            <p style="opacity: 0.9; line-height: 1.6;"><?php echo Security::escape(getSetting('contact_address', 'Alanya Alaaddin Keykubat Üniversitesi, Alanya TEKMER, Alanya/Antalya')); ?></p>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 25px; display: flex; gap: 20px; align-items: flex-start;">
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-phone" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.1em; font-weight: 700; margin-bottom: 8px; color: white;">Telefon</h3>
                            <a href="tel:<?php echo getSetting('contact_phone', '+90 242 000 00 00'); ?>" style="color: white; text-decoration: none; opacity: 0.9; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                                <?php echo getSetting('contact_phone', '+90 242 000 00 00'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 35px; display: flex; gap: 20px; align-items: flex-start;">
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-envelope" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.1em; font-weight: 700; margin-bottom: 8px; color: white;">E-posta</h3>
                            <a href="mailto:<?php echo getSetting('contact_email', 'info@alanyatekmer.com'); ?>" style="color: white; text-decoration: none; opacity: 0.9; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                                <?php echo getSetting('contact_email', 'info@alanyatekmer.com'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 25px;">
                        <h3 style="font-size: 1.1em; font-weight: 700; margin-bottom: 15px; color: white;">Sosyal Medya</h3>
                        <div style="display: flex; gap: 12px;">
                            <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div data-aos="fade-left">
                <div style="background: white; border-radius: 24px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <h2 style="font-size: 2em; font-weight: 800; margin-bottom: 15px; color: #111827;">Mesaj Gönder</h2>
                    <p style="color: #6b7280; margin-bottom: 35px; font-size: 1.05em;">Formu doldurarak bize mesaj gönderebilirsiniz. En kısa sürede size dönüş yapacağız.</p>
                
                    <?php if ($success): ?>
                        <div style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-radius: 16px; padding: 25px; border-left: 5px solid #10b981; margin-bottom: 30px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 45px; height: 45px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-check" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h4 style="color: #065f46; font-weight: 700; margin-bottom: 5px;">Başarılı!</h4>
                                    <p style="color: #047857; margin: 0;">Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div style="background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 16px; padding: 25px; border-left: 5px solid #ef4444; margin-bottom: 30px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 45px; height: 45px; background: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-exclamation-triangle" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h4 style="color: #991b1b; font-weight: 700; margin-bottom: 5px;">Hata!</h4>
                                    <p style="color: #dc2626; margin: 0;"><?php echo Security::escape($error); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!$success): ?>
                        <form method="POST" style="display: grid; gap: 25px;">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div>
                                    <label for="name" style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 0.95em;">
                                        Ad Soyad <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" required 
                                           value="<?php echo Security::escape($_POST['name'] ?? ''); ?>"
                                           style="width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 1em; transition: all 0.3s; outline: none;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                           placeholder="Adınız ve soyadınız">
                                </div>
                                
                                <div>
                                    <label for="email" style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 0.95em;">
                                        E-posta <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" required 
                                           value="<?php echo Security::escape($_POST['email'] ?? ''); ?>"
                                           style="width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 1em; transition: all 0.3s; outline: none;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                           placeholder="ornek@email.com">
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div>
                                    <label for="phone" style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 0.95em;">
                                        Telefon
                                    </label>
                                    <input type="tel" id="phone" name="phone" 
                                           value="<?php echo Security::escape($_POST['phone'] ?? ''); ?>"
                                           style="width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 1em; transition: all 0.3s; outline: none;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                           placeholder="0555 123 45 67">
                                </div>
                                
                                <div>
                                    <label for="subject" style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 0.95em;">
                                        Konu
                                    </label>
                                    <input type="text" id="subject" name="subject" 
                                           value="<?php echo Security::escape($_POST['subject'] ?? ''); ?>"
                                           style="width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 1em; transition: all 0.3s; outline: none;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                           placeholder="Mesaj konusu">
                                </div>
                            </div>
                            
                            <div>
                                <label for="message" style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 0.95em;">
                                    Mesajınız <span style="color: #ef4444;">*</span>
                                </label>
                                <textarea id="message" name="message" rows="6" required
                                          style="width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 1em; transition: all 0.3s; outline: none; resize: vertical; font-family: inherit;"
                                          onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
                                          onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                          placeholder="Mesajınızı buraya yazın..."><?php echo Security::escape($_POST['message'] ?? ''); ?></textarea>
                            </div>
                            
                            <div style="margin: 10px 0;">
                                <div class="cf-turnstile" data-sitekey="<?php echo getenv('TURNSTILE_SITE_KEY'); ?>"></div>
                            </div>
                            
                            <button type="submit" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; padding: 16px 40px; border: none; border-radius: 12px; font-size: 1.1em; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(59, 130, 246, 0.3)'">
                                <i class="fas fa-paper-plane"></i> Mesajı Gönder
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section style="padding: 80px 0; background: #f9fafb;">
    <div class="container">
        <div data-aos="fade-up" style="text-align: center; margin-bottom: 50px;">
            <span style="display: inline-block; background: linear-gradient(135deg, #1e40af, #3b82f6); color: white; padding: 8px 20px; border-radius: 50px; font-size: 0.9em; margin-bottom: 20px;">
                <i class="fas fa-map-marker-alt"></i> Konumumuz
            </span>
            <h2 style="font-size: 2.5em; font-weight: 800; color: #111827; margin-bottom: 15px;">Bizi Ziyaret Edin</h2>
            <p style="color: #6b7280; font-size: 1.1em; max-width: 700px; margin: 0 auto;">Alanya Alaaddin Keykubat Üniversitesi Kestel Yerleşkesi'ndeyiz</p>
        </div>
        
        <div data-aos="fade-up" data-aos-delay="100" style="border-radius: 24px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15); position: relative;">
            <div style="height: 500px; position: relative;">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3206.13060868513!2d32.0827820112754!3d36.52684327221093!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14dc9b9a0703d157%3A0x64083ab78c21c2c!2sAlanya%20Teknoloji%20Geli%C5%9Ftirme%20Merkezi%20(TEKMER)!5e0!3m2!1str!2str!4v1763630327641!5m2!1str!2str"
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            
            <!-- Map Overlay Info Card -->
            <div style="position: absolute; bottom: 30px; left: 30px; background: white; border-radius: 16px; padding: 25px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 400px;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #1e40af, #3b82f6); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-building" style="color: white; font-size: 22px;"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.2em; font-weight: 700; color: #111827;">Alanya TEKMER</h3>
                        <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 0.9em;">Teknoloji Geliştirme Merkezi</p>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 10px; color: #374151; font-size: 0.95em; line-height: 1.6;">
                    <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-top: 3px; flex-shrink: 0;"></i>
                    <span><?php echo Security::escape(getSetting('contact_address', 'Alanya Alaaddin Keykubat Üniversitesi, Kestel Yerleşkesi, Alanya/Antalya')); ?></span>
                </div>
                <a href="https://www.google.com/maps/dir/?api=1&destination=36.52684327221093,32.0827820112754" 
                   target="_blank" 
                   style="display: inline-flex; align-items: center; gap: 8px; margin-top: 15px; background: linear-gradient(135deg, #1e40af, #3b82f6); color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9em; transition: all 0.3s;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(30,64,175,0.3)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-directions"></i>
                    <span>Yol Tarifi Al</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Responsive Styles -->
<style>
    @media (max-width: 768px) {
        .container > div[style*="grid-template-columns: 1fr 1.5fr"] {
            grid-template-columns: 1fr !important;
        }
        
        .container > div > div[data-aos="fade-right"] > div[style*="sticky"] {
            position: relative !important;
            top: auto !important;
        }
        
        form > div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
        
        /* Map overlay card responsive */
        section div[style*="position: absolute; bottom: 30px; left: 30px"] {
            position: relative !important;
            bottom: auto !important;
            left: auto !important;
            max-width: 100% !important;
            margin-top: -50px !important;
            margin-left: 20px !important;
            margin-right: 20px !important;
        }
    }
    
    @media (max-width: 480px) {
        section h1[style*="font-size: 3em"] {
            font-size: 2em !important;
        }
        
        section h2[style*="font-size: 2.5em"] {
            font-size: 1.8em !important;
        }
        
        section h2[style*="font-size: 2em"] {
            font-size: 1.5em !important;
        }
    }
</style>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
</script>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

<?php 
$additionalJs = ['turnstile'];
include __DIR__ . '/../includes/footer.php'; 
?>

