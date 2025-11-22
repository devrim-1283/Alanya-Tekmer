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
                    <a href="<?php echo url('firma/' . $company['id']); ?>" class="company-logo-card">
                        <div class="logo-image-wrapper">
                            <?php if ($company['logo']): ?>
                                <img src="<?php echo url('uploads/' . basename($company['logo'])); ?>" 
                                     alt="<?php echo Security::escape($company['name']); ?>"
                                     class="logo-image"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'logo-image-placeholder\'><i class=\'fas fa-building\'></i></div>';">
                            <?php else: ?>
                                <div class="logo-image-placeholder">
                                    <i class="fas fa-building"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="company-name-overlay">
                            <span><?php echo Security::escape($company['name']); ?></span>
                        </div>
                    </a>
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

<style>
.companies-section {
    padding: 60px 0;
}

.companies-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    margin-top: 40px;
}

.company-logo-card {
    position: relative;
    background: white;
    border-radius: 16px;
    padding: 30px;
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    text-decoration: none;
    overflow: hidden;
}

.company-logo-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.logo-image-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    overflow: hidden;
}

.logo-image {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    filter: grayscale(20%);
    transition: all 0.3s ease;
}

.company-logo-card:hover .logo-image {
    filter: grayscale(0%);
    transform: scale(1.05);
}

.logo-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
}

.logo-image-placeholder i {
    font-size: 4rem;
    color: white;
    opacity: 0.8;
}

.company-name-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.85), transparent);
    padding: 25px 15px 15px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.company-logo-card:hover .company-name-overlay {
    opacity: 1;
}

.company-name-overlay span {
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
    text-align: center;
    display: block;
    line-height: 1.3;
}

.no-data {
    text-align: center;
    padding: 80px 20px;
    color: #999;
}

.no-data i {
    font-size: 5rem;
    color: #ddd;
    margin-bottom: 20px;
}

.no-data p {
    font-size: 1.1rem;
    color: #666;
}

/* Tablet */
@media (max-width: 992px) {
    .companies-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
}

/* Mobile */
@media (max-width: 768px) {
    .companies-section {
        padding: 40px 0;
    }
    
    .companies-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 30px;
    }
    
    .company-logo-card {
        padding: 20px;
        border-radius: 12px;
    }
    
    .company-name-overlay span {
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .companies-grid {
        gap: 15px;
    }
    
    .company-logo-card {
        padding: 15px;
    }
    
    .logo-image-placeholder i {
        font-size: 3rem;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>


