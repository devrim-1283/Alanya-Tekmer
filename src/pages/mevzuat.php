<?php
$pageTitle = 'Mevzuat - Alanya TEKMER';
$metaDescription = 'Alanya TEKMER mevzuat belgeleri ve resmi dökümanlar.';
$currentPage = 'about';

logPageView('mevzuat');

// Get PDF files from mevzuat directory
$mevzuatDir = __DIR__ . '/../../mevzuat';
$pdfFiles = [];

if (is_dir($mevzuatDir)) {
    $files = scandir($mevzuatDir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
            $pdfFiles[] = [
                'filename' => $file,
                'display_name' => str_replace(['-', '_', '.pdf'], [' ', ' ', ''], $file),
                'size' => filesize($mevzuatDir . '/' . $file)
            ];
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Mevzuat</h1>
        <p>Alanya TEKMER'in resmi mevzuat belgeleri</p>
    </div>
</section>

<section class="mevzuat-section">
    <div class="container">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <p>Bu sayfada yer alan belgeler Alanya TEKMER'in resmi mevzuat kaynaklarıdır. Tüm belgeler resmi kaynaklardan alınmıştır.</p>
        </div>
        
        <?php if (!empty($pdfFiles)): ?>
            <div class="mevzuat-list">
                <?php foreach ($pdfFiles as $pdf): ?>
                    <div class="mevzuat-item">
                        <div class="mevzuat-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="mevzuat-info">
                            <h3><?php echo Security::escape($pdf['display_name']); ?></h3>
                            <p class="mevzuat-size">Dosya Boyutu: <?php echo round($pdf['size'] / 1048576, 2); ?> MB</p>
                        </div>
                        <div class="mevzuat-actions">
                            <a href="<?php echo url('mevzuat/' . urlencode($pdf['filename'])); ?>" 
                               class="btn btn-primary" 
                               target="_blank">
                                <i class="fas fa-eye"></i> Görüntüle
                            </a>
                            <a href="<?php echo url('mevzuat/' . urlencode($pdf['filename'])); ?>" 
                               class="btn btn-secondary" 
                               download>
                                <i class="fas fa-download"></i> İndir
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                <p>Henüz mevzuat belgesi eklenmemiştir.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

