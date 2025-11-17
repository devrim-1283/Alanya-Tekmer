<?php
$pageTitle = 'Gizlilik Sözleşmesi - Alanya TEKMER';
$currentPage = '';
logPageView('privacy');
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Gizlilik Sözleşmesi</h1>
    </div>
</section>

<section class="legal-content">
    <div class="container">
        <h2>1. Giriş</h2>
        <p>Alanya TEKMER olarak, ziyaretçilerimizin gizliliğine saygı duyuyor ve kişisel verilerinizi korumayı taahhüt ediyoruz. Bu gizlilik politikası, web sitemizi ziyaret ettiğinizde toplanan bilgilerin nasıl kullanıldığını açıklar.</p>
        
        <h2>2. Toplanan Bilgiler</h2>
        <p>Web sitemizi kullanırken aşağıdaki bilgiler toplanabilir:</p>
        <ul>
            <li>İsim, e-posta adresi, telefon numarası gibi kişisel bilgiler</li>
            <li>IP adresi, tarayıcı türü, ziyaret edilen sayfalar gibi teknik bilgiler</li>
            <li>Başvuru formları aracılığıyla sağlanan iş ve proje bilgileri</li>
        </ul>
        
        <h2>3. Bilgilerin Kullanımı</h2>
        <p>Toplanan bilgiler şu amaçlarla kullanılır:</p>
        <ul>
            <li>Başvuruların değerlendirilmesi</li>
            <li>İletişim taleplerinin yanıtlanması</li>
            <li>Web sitesi performansının iyileştirilmesi</li>
            <li>Yasal yükümlülüklerin yerine getirilmesi</li>
        </ul>
        
        <h2>4. Çerezler</h2>
        <p>Web sitemiz, kullanıcı deneyimini geliştirmek için çerezler kullanır. Tarayıcı ayarlarınızdan çerezleri devre dışı bırakabilirsiniz.</p>
        
        <h2>5. Güvenlik</h2>
        <p>Kişisel verilerinizi korumak için uygun teknik ve organizasyonel güvenlik önlemleri alıyoruz.</p>
        
        <h2>6. İletişim</h2>
        <p>Gizlilik politikamız hakkında sorularınız için bizimle iletişime geçebilirsiniz:</p>
        <p>E-posta: <?php echo getSetting('contact_email'); ?></p>
        <p>Telefon: <?php echo getSetting('contact_phone'); ?></p>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

