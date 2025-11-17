    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Alanya TEKMER</h3>
                    <p><?php echo Security::escape(getSetting('site_description')); ?></p>
                    <div class="footer-logos">
                        <img src="<?php echo asset('images/footer.png'); ?>" alt="Footer Logo" class="footer-logo">
                        <img src="<?php echo asset('images/kosgebv.png'); ?>" alt="KOSGEB" class="kosgeb-logo">
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Hızlı Linkler</h3>
                    <ul>
                        <li><a href="<?php echo url('hakkimizda'); ?>">Hakkımızda</a></li>
                        <li><a href="<?php echo url('hizmetlerimiz'); ?>">Hizmetlerimiz</a></li>
                        <li><a href="<?php echo url('etkinlikler'); ?>">Etkinlikler</a></li>
                        <li><a href="<?php echo url('firmalar'); ?>">Firmalar</a></li>
                        <li><a href="<?php echo url('basvuru'); ?>">Başvuru</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>İletişim</h3>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo Security::escape(getSetting('contact_address')); ?>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <a href="tel:<?php echo getSetting('contact_phone'); ?>">
                                <?php echo getSetting('contact_phone'); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?php echo getSetting('contact_email'); ?>">
                                <?php echo getSetting('contact_email'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Sosyal Medya</h3>
                    <div class="social-links-footer">
                        <?php if (getSetting('facebook_url')): ?>
                            <a href="<?php echo Security::escape(getSetting('facebook_url')); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-facebook"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSetting('instagram_url')): ?>
                            <a href="<?php echo Security::escape(getSetting('instagram_url')); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSetting('linkedin_url')): ?>
                            <a href="<?php echo Security::escape(getSetting('linkedin_url')); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSetting('youtube_url')): ?>
                            <a href="<?php echo Security::escape(getSetting('youtube_url')); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-youtube"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-links">
                    <a href="<?php echo url('gizlilik-sozlesmesi'); ?>">Gizlilik Sözleşmesi</a>
                    <a href="<?php echo url('kullanici-sozlesmesi'); ?>">Kullanıcı Sözleşmesi</a>
                    <a href="<?php echo url('kvkk'); ?>">KVKK Bilgilendirme</a>
                </div>
                <p>&copy; <?php echo date('Y'); ?> Alanya TEKMER. Tüm hakları saklıdır.</p>
                <p>Bu site <a href="https://www.devrimtuncer.com" target="_blank" rel="noopener">www.devrimtuncer.com</a> tarafından geliştirilmiştir.</p>
            </div>
        </div>
    </footer>

    <script src="<?php echo asset('js/main.js'); ?>"></script>
    
    <?php if (isset($additionalJs)): ?>
        <?php foreach ((array)$additionalJs as $js): ?>
            <script src="<?php echo asset('js/' . $js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
    // Cookie consent
    function acceptCookies() {
        localStorage.setItem('cookieConsent', 'accepted');
        document.getElementById('cookieConsent').style.display = 'none';
    }
    
    if (!localStorage.getItem('cookieConsent')) {
        document.getElementById('cookieConsent').style.display = 'block';
    }
    
    // Mobile menu toggle
    function toggleMobileMenu() {
        document.getElementById('navMenu').classList.toggle('active');
    }
    </script>
</body>
</html>

