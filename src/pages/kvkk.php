<?php
$pageTitle = 'KVKK Bilgilendirme - Alanya TEKMER';
$currentPage = '';
logPageView('kvkk');
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>KVKK Bilgilendirme</h1>
        <p>Kişisel Verilerin Korunması Kanunu Aydınlatma Metni</p>
    </div>
</section>

<section class="legal-content">
    <div class="container">
        <h2>1. Veri Sorumlusu</h2>
        <p><strong>Alanya TEKMER A.Ş.</strong> olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca veri sorumlusu sıfatıyla, kişisel verilerinizin işlenmesine ilişkin sizleri bilgilendirmek isteriz.</p>
        
        <h2>2. İşlenen Kişisel Veriler</h2>
        <p>Başvuru sürecinde aşağıdaki kişisel verileriniz işlenmektedir:</p>
        <ul>
            <li>Kimlik Bilgileri: Ad, soyad, TC kimlik numarası</li>
            <li>İletişim Bilgileri: Telefon, e-posta adresi</li>
            <li>Eğitim Bilgileri: Üniversite, bölüm</li>
            <li>Mesleki Bilgiler: Firma bilgileri, proje detayları</li>
            <li>İşlem Güvenliği Bilgileri: IP adresi, kullanıcı aracı bilgileri</li>
        </ul>
        
        <h2>3. Kişisel Verilerin İşlenme Amacı</h2>
        <p>Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:</p>
        <ul>
            <li>Başvuruların değerlendirilmesi ve yönetilmesi</li>
            <li>İletişim faaliyetlerinin yürütülmesi</li>
            <li>Yasal yükümlülüklerin yerine getirilmesi</li>
            <li>İstatistiksel analiz ve raporlama</li>
            <li>Bilgi güvenliği süreçlerinin yürütülmesi</li>
        </ul>
        
        <h2>4. Kişisel Verilerin Aktarılması</h2>
        <p>Kişisel verileriniz, yasal yükümlülükler çerçevesinde KOSGEB, Alanya Alaaddin Keykubat Üniversitesi ve ilgili kamu kurum ve kuruluşları ile paylaşılabilir.</p>
        
        <h2>5. Kişisel Verilerin Toplanma Yöntemi</h2>
        <p>Kişisel verileriniz, web sitemizdeki başvuru formları, iletişim formları ve otomatik loglar aracılığıyla elektronik ortamda toplanmaktadır.</p>
        
        <h2>6. KVKK Kapsamındaki Haklarınız</h2>
        <p>KVKK'nın 11. maddesi uyarınca aşağıdaki haklara sahipsiniz:</p>
        <ul>
            <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
            <li>İşlenmişse bilgi talep etme</li>
            <li>İşlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme</li>
            <li>Yurt içinde veya yurt dışında aktarıldığı üçüncü kişileri bilme</li>
            <li>Eksik veya yanlış işlenmişse düzeltilmesini isteme</li>
            <li>KVKK'nın 7. maddesinde öngörülen şartlar çerçevesinde silinmesini veya yok edilmesini isteme</li>
            <li>Aktarıldığı üçüncü kişilere yukarıdaki işlemlerin bildirilmesini isteme</li>
            <li>Münhasıran otomatik sistemler ile analiz edilmesi nedeniyle aleyhinize bir sonuç doğmasına itiraz etme</li>
            <li>Kanuna aykırı olarak işlenmesi sebebiyle zarara uğramanız hâlinde zararın giderilmesini talep etme</li>
        </ul>
        
        <h2>7. Başvuru Yöntemi</h2>
        <p>Yukarıda belirtilen haklarınızı kullanmak için aşağıdaki yöntemlerle başvuruda bulunabilirsiniz:</p>
        <p><strong>E-posta:</strong> <?php echo getSetting('contact_email'); ?></p>
        <p><strong>Adres:</strong> <?php echo Security::escape(getSetting('contact_address')); ?></p>
        
        <h2>8. İletişim</h2>
        <p>KVKK ile ilgili detaylı bilgi ve sorularınız için bizimle iletişime geçebilirsiniz:</p>
        <p>Telefon: <?php echo getSetting('contact_phone'); ?></p>
        <p>E-posta: <?php echo getSetting('contact_email'); ?></p>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

