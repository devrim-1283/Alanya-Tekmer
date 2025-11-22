<?php
$pageTitle = 'Firma Detayı - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER firma detayları.';
$currentPage = 'companies';

logPageView('company-detail');

// Get company ID from URL
$companyId = (int)($_GET['id'] ?? 0);

if (!$companyId) {
    header('Location: ' . url('firmalar'));
    exit;
}

// Get company from database
$db = Database::getInstance();
$company = $db->fetchOne('SELECT * FROM companies WHERE id = ? AND is_active = true', [$companyId]);

if (!$company) {
    header('Location: ' . url('firmalar'));
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo Security::escape($company['name']); ?></h1>
        <p>Firma Detayları</p>
    </div>
</section>

<section class="company-detail-section">
    <div class="container">
        <div class="company-detail-wrapper">
            <!-- Company Logo -->
            <div class="detail-logo-card">
                <?php if ($company['logo']): ?>
                    <img src="<?php echo url('uploads/' . basename($company['logo'])); ?>" 
                         alt="<?php echo Security::escape($company['name']); ?>"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-logo-placeholder\'><i class=\'fas fa-building\'></i></div>';">
                <?php else: ?>
                    <div class="no-logo-placeholder">
                        <i class="fas fa-building"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Company Info -->
            <div class="detail-content">
                <h2 class="company-title">
                    <i class="fas fa-building"></i>
                    <?php echo Security::escape($company['name']); ?>
                </h2>

                <?php if ($company['description']): ?>
                    <div class="info-section">
                        <h3><i class="fas fa-info-circle"></i> Hakkında</h3>
                        <p><?php echo nl2br(Security::escape($company['description'])); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Contact Information -->
                <?php 
                $hasContact = !empty($company['contact_person']) || 
                             !empty($company['phone']) || 
                             (isset($company['email']) && !empty($company['email']));
                if ($hasContact): ?>
                    <div class="info-section">
                        <h3><i class="fas fa-address-card"></i> İletişim Bilgileri</h3>
                        <div class="contact-grid">
                            <?php if (!empty($company['contact_person'])): ?>
                                <div class="contact-item">
                                    <i class="fas fa-user"></i>
                                    <div>
                                        <strong>Yetkili Kişi</strong>
                                        <span><?php echo Security::escape($company['contact_person']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($company['phone'])): ?>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <strong>Telefon</strong>
                                        <a href="tel:<?php echo Security::normalizePhone($company['phone']); ?>">
                                            <?php echo Security::escape($company['phone']); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($company['email']) && !empty($company['email'])): ?>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <strong>E-posta</strong>
                                        <a href="mailto:<?php echo Security::escape($company['email']); ?>">
                                            <?php echo Security::escape($company['email']); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($company['website']): ?>
                                <div class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <div>
                                        <strong>Website</strong>
                                        <a href="<?php echo Security::escape($company['website']); ?>" 
                                           target="_blank" rel="noopener">
                                            Website'yi Ziyaret Et <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Social Media -->
                <?php if ($company['instagram'] || $company['linkedin'] || $company['whatsapp']): ?>
                    <div class="info-section">
                        <h3><i class="fas fa-share-alt"></i> Sosyal Medya</h3>
                        <div class="social-links">
                            <?php if ($company['whatsapp']): ?>
                                <a href="https://wa.me/<?php echo Security::normalizePhone($company['whatsapp']); ?>" 
                                   class="social-link whatsapp" target="_blank" rel="noopener">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>WhatsApp</span>
                                </a>
                            <?php endif; ?>

                            <?php if ($company['instagram']): ?>
                                <a href="<?php echo Security::escape($company['instagram']); ?>" 
                                   class="social-link instagram" target="_blank" rel="noopener">
                                    <i class="fab fa-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                            <?php endif; ?>

                            <?php if ($company['linkedin']): ?>
                                <a href="<?php echo Security::escape($company['linkedin']); ?>" 
                                   class="social-link linkedin" target="_blank" rel="noopener">
                                    <i class="fab fa-linkedin"></i>
                                    <span>LinkedIn</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Back Button -->
                <div class="action-buttons">
                    <a href="<?php echo url('firmalar'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Firmalara Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.company-detail-section {
    padding: 60px 0;
    min-height: 60vh;
}

.company-detail-wrapper {
    max-width: 1000px;
    margin: 0 auto;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.detail-logo-card {
    background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
    padding: 60px 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    border-bottom: 3px solid #f0f0f0;
}

.detail-logo-card img {
    max-width: 400px;
    max-height: 250px;
    width: auto;
    height: auto;
    object-fit: contain;
}

.no-logo-placeholder {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-logo-placeholder i {
    font-size: 6rem;
    color: white;
    opacity: 0.8;
}

.detail-content {
    padding: 50px 60px;
}

.company-title {
    font-size: 2rem;
    color: #2c3e50;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 3px solid #667eea;
    display: flex;
    align-items: center;
    gap: 15px;
}

.company-title i {
    color: #667eea;
}

.info-section {
    margin-bottom: 40px;
}

.info-section h3 {
    font-size: 1.3rem;
    color: #34495e;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-section h3 i {
    color: #667eea;
    font-size: 1.2rem;
}

.info-section p {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #555;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}

.company-detail-section .contact-item {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.company-detail-section .contact-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.company-detail-section .contact-item > i {
    font-size: 1.5rem;
    color: #667eea;
    width: 30px;
    flex-shrink: 0;
}

.company-detail-section .contact-item > div {
    flex: 1;
}

.company-detail-section .contact-item strong {
    display: block;
    color: #6c757d;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.company-detail-section .contact-item span,
.company-detail-section .contact-item a {
    font-size: 1.05rem;
    color: #2c3e50;
    text-decoration: none;
}

.company-detail-section .contact-item a:hover {
    color: #667eea;
}

.company-detail-section .social-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.company-detail-section .social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 24px;
    border-radius: 12px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.company-detail-section .social-link:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
}

.company-detail-section .social-link i {
    font-size: 1.4rem;
}

.company-detail-section .social-link span {
    font-weight: 600;
}

.company-detail-section .social-link.whatsapp {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
}

.company-detail-section .social-link.instagram {
    background: linear-gradient(135deg, #E1306C 0%, #C13584 50%, #833AB4 100%);
}

.company-detail-section .social-link.linkedin {
    background: linear-gradient(135deg, #0077B5 0%, #00669C 100%);
}

.action-buttons {
    margin-top: 50px;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
    display: flex;
    justify-content: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 32px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(108, 117, 125, 0.4);
}

.btn-secondary i {
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .detail-logo-card {
        padding: 40px 20px;
        min-height: 200px;
    }
    
    .detail-logo-card img {
        max-width: 250px;
        max-height: 150px;
    }
    
    .no-logo-placeholder {
        width: 150px;
        height: 150px;
    }
    
    .no-logo-placeholder i {
        font-size: 4rem;
    }
    
    .detail-content {
        padding: 30px 25px;
    }
    
    .company-title {
        font-size: 1.5rem;
        margin-bottom: 30px;
    }
    
    .company-detail-section .contact-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .company-detail-section .social-links {
        grid-template-columns: 1fr;
    }
    
    .company-detail-section .social-link {
        width: 100%;
    }
    
    .action-buttons {
        margin-top: 40px;
        padding-top: 25px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>

