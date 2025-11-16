import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';

export default function PrivacyPage() {
  return (
    <PublicLayout>
      <SEO title="Gizlilik Sözleşmesi" description="Alanya TEKMER gizlilik sözleşmesi ve KVKK bilgilendirmesi" />
      
      <div className="py-16">
        <div className="container-custom">
          <div className="max-w-4xl mx-auto prose prose-lg">
            <h1>Gizlilik Sözleşmesi ve KVKK Bilgilendirmesi</h1>
            
            <p>
              Alanya TEKMER olarak kişisel verilerinizin güvenliği bizim için önemlidir. 
              Bu sayfada 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında 
              kişisel verilerinizin nasıl işlendiği hakkında sizi bilgilendiriyoruz.
            </p>

            <h2>Veri Sorumlusu</h2>
            <p>
              ALANYA TEKMER A.Ş.<br />
              Adres: KESTEL MAH. ÜNİVERSİTE CAD. NO: 86/3 ALANYA / ANTALYA<br />
              E-posta: destek@alanyatekmer.com
            </p>

            <h2>İşlenen Kişisel Veriler</h2>
            <p>Başvuru sürecinde aşağıdaki kişisel verileriniz işlenmektedir:</p>
            <ul>
              <li>Kimlik bilgileri (Ad, Soyad, TC Kimlik No)</li>
              <li>İletişim bilgileri (Telefon, E-posta, Adres)</li>
              <li>Eğitim bilgileri</li>
              <li>Proje bilgileri</li>
            </ul>

            <h2>Kişisel Verilerin İşlenme Amacı</h2>
            <p>Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:</p>
            <ul>
              <li>Başvurunuzun değerlendirilmesi</li>
              <li>Sizinle iletişime geçilmesi</li>
              <li>Yasal yükümlülüklerin yerine getirilmesi</li>
            </ul>

            <h2>Haklarınız</h2>
            <p>KVKK kapsamında aşağıdaki haklara sahipsiniz:</p>
            <ul>
              <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
              <li>İşlenmişse buna ilişkin bilgi talep etme</li>
              <li>İşlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme</li>
              <li>Yurt içinde veya yurt dışında aktarıldığı üçüncü kişileri bilme</li>
              <li>Eksik veya yanlış işlenmişse düzeltilmesini isteme</li>
              <li>Silme veya yok edilmesini isteme</li>
            </ul>
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}

