<?php
$pageTitle = 'Kullanıcı Sözleşmesi - Alanya TEKMER';
$currentPage = '';
logPageView('terms');
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Kullanıcı Sözleşmesi</h1>
    </div>
</section>

<section class="legal-content">
    <div class="container">
        <h2>1. Genel Hükümler</h2>
        <p>Bu web sitesini kullanarak, aşağıdaki kullanım koşullarını kabul etmiş sayılırsınız.</p>
        
        <h2>2. Hizmet Kapsamı</h2>
        <p>Alanya TEKMER web sitesi, girişimcilere ve işletmelere bilgi sağlamak ve başvuru süreçlerini yönetmek amacıyla hizmet vermektedir.</p>
        
        <h2>3. Kullanıcı Sorumlulukları</h2>
        <p>Kullanıcılar:</p>
        <ul>
            <li>Doğru ve güncel bilgi sağlamakla yükümlüdür</li>
            <li>Web sitesini yasal amaçlar için kullanmalıdır</li>
            <li>Başkalarının haklarını ihlal etmemelidir</li>
            <li>Sisteme zarar verecek faaliyetlerden kaçınmalıdır</li>
        </ul>
        
        <h2>4. Fikri Mülkiyet Hakları</h2>
        <p>Web sitesinde yer alan tüm içerik, tasarım ve yazılım Alanya TEKMER'e aittir ve telif hakları ile korunmaktadır.</p>
        
        <h2>5. Sorumluluk Sınırlaması</h2>
        <p>Alanya TEKMER, web sitesinin kesintisiz ve hatasız çalışacağını garanti etmez. Hizmet kullanımından doğabilecek zararlardan sorumlu değildir.</p>
        
        <h2>6. Değişiklikler</h2>
        <p>Alanya TEKMER, kullanım koşullarını önceden haber vermeksizin değiştirme hakkını saklı tutar.</p>
        
        <h2>7. İletişim</h2>
        <p>Kullanım koşulları hakkında sorularınız için:</p>
        <p>E-posta: <?php echo getSetting('contact_email'); ?></p>
        <p>Telefon: <?php echo getSetting('contact_phone'); ?></p>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

