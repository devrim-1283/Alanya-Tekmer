<?php
$pageTitle = 'Firmalarımız - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER bünyesinde yer alan firmalar.';
$currentPage = 'companies';

logPageView('companies');

// Get companies from database with cache
$companies = CacheHelper::getOrSet('tekmer:data:companies', function() {
    $db = Database::getInstance();
    return $db->fetchAll('SELECT * FROM companies WHERE is_active = true ORDER BY name ASC');
}, (int)(getenv('CACHE_TTL_STATIC') ?: 86400));

include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Firmalarımız</h1>
        <p>Alanya TEKMER bünyesinde yer alan firmalar</p>
    </div>
</section>

<section class="companies-section">
    <div class="container">
        <?php if (!empty($companies)): ?>
            <div class="companies-grid">
                <?php foreach ($companies as $company): ?>
                    <div class="company-card">
                        <?php if ($company['logo']): ?>
                            <div class="company-logo">
                                <img src="<?php echo url('uploads/' . Security::escape($company['logo'])); ?>" 
                                     alt="<?php echo Security::escape($company['name']); ?>"
                                     loading="lazy">
                            </div>
                        <?php endif; ?>
                        
                        <div class="company-content">
                            <h3><?php echo Security::escape($company['name']); ?></h3>
                            
                            <?php if ($company['description']): ?>
                                <p class="company-description">
                                    <?php echo nl2br(Security::escape($company['description'])); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($company['contact_person']): ?>
                                <p class="company-contact">
                                    <i class="fas fa-user"></i> 
                                    <strong>Yetkili:</strong> <?php echo Security::escape($company['contact_person']); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="company-links">
                                <?php if ($company['phone']): ?>
                                    <a href="tel:<?php echo Security::normalizePhone($company['phone']); ?>" 
                                       class="company-link" title="Telefon">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($company['whatsapp']): ?>
                                    <a href="https://wa.me/<?php echo Security::normalizePhone($company['whatsapp']); ?>" 
                                       class="company-link" title="WhatsApp" target="_blank" rel="noopener">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($company['instagram']): ?>
                                    <a href="<?php echo Security::escape($company['instagram']); ?>" 
                                       class="company-link" title="Instagram" target="_blank" rel="noopener">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($company['linkedin']): ?>
                                    <a href="<?php echo Security::escape($company['linkedin']); ?>" 
                                       class="company-link" title="LinkedIn" target="_blank" rel="noopener">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($company['website']): ?>
                                    <a href="<?php echo Security::escape($company['website']); ?>" 
                                       class="company-link" title="Website" target="_blank" rel="noopener">
                                        <i class="fas fa-globe"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-building"></i>
                <p>Henüz firma eklenmemiştir.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

