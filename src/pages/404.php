<?php
$pageTitle = '404 - Sayfa Bulunamadı';
$currentPage = '';
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

<?php include __DIR__ . '/../includes/footer.php'; ?>

