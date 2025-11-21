<?php
$pageTitle = '404 - Sayfa Bulunamadı';
$currentPage = '';

logPageView('404');

include __DIR__ . '/../includes/header.php';
?>

<section class="error-page">
    <div class="container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2>Sayfa Bulunamadı</h2>
            <p>Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
            <div class="error-actions">
                <a href="<?php echo url(); ?>" class="btn btn-primary">Ana Sayfaya Dön</a>
                <a href="<?php echo url('iletisim'); ?>" class="btn btn-secondary">İletişime Geç</a>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Optimizations for 404 Page -->
<style>
@media (max-width: 768px) {
    .error-section {
        padding: 60px 0;
    }
    
    .error-code {
        font-size: 6rem;
    }
    
    .error-message {
        font-size: 1.5rem;
    }
    
    .error-description {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .error-code {
        font-size: 4rem;
    }
    
    .error-message {
        font-size: 1.25rem;
    }
    
    .error-description {
        font-size: 0.9rem;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>

