import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';

export default function ServicesPage() {
  return (
    <PublicLayout>
      <SEO 
        title="Hizmetlerimiz" 
        description="Alanya TEKMER'in sunduğu hizmetler ve avantajlar hakkında bilgi edinin."
      />
      
      <div className="py-16">
        <div className="container-custom">
          <h1 className="text-4xl font-bold text-center mb-12">Hizmetlerimiz</h1>
          
          <div className="max-w-4xl mx-auto prose prose-lg">
            <h2>ALANYA TEKMER'in Sizlere Sağlayacağı Avantajlar</h2>
            
            <ul className="space-y-2">
              <li>KOSGEB Tekmer Muafiyetleri</li>
              <li>Danışmanlık & Mentorluk</li>
              <li>Sınai Mülkiyet Danışmanlık Hizmeti</li>
              <li>Eğitimler</li>
              <li>Sosyal Alanlar</li>
              <li>Temizlik ve güvenlik hizmetleri</li>
              <li>Toplantı Salonu</li>
              <li>Kampüs Olanakları</li>
              <li>Ücretsiz Wi-fi</li>
              <li>7/24 Çalışma İmkanı</li>
            </ul>

            <p>
              ALANYA TEKMER girişimcilere ve işletmelere ön inkübasyon, inkübasyon ve inkübasyon sonrası süreçlerde; iş geliştirme, mali kaynaklara erişim, yönetim, danışmanlık, mentörlük, ofis ve ağlara katılım gibi hizmetler ile destek olmak amaçlı kurulmuş bir teknoloji merkezidir.
            </p>

            <h3>Ar-Ge ve Tasarım İndirimi</h3>
            <p>Ar-Ge ve yenilik veya tasarım harcamalarının tamamı (%100'ü), kurum kazancının tespitinde indirim konusu yapılmaktadır.</p>

            <h3>Gelir Vergi Stopajı Teşviki</h3>
            <p>Teknoloji merkezlerinde çalışan Ar-Ge ve destek personelinin elde ettikleri ücretler üzerinden hesaplanan gelir vergisinin belirli oranları vergiden indirilebilir.</p>

            <h3>Sigorta Primi Desteği</h3>
            <p>Teknoloji merkezlerinde çalışan Ar-Ge ve destek personelinin elde ettikleri ücretler üzerinden hesaplanan sigorta primi işveren hissesinin %50'si karşılanmaktadır.</p>

            <h3>Damga Vergisi İstisnası</h3>
            <p>Ar-Ge ve yenilik faaliyetleri ile ilgili olarak düzenlenen kağıtlar damga vergisinden istisnadır.</p>

            <h3>Gümrük Vergisi İstisnası</h3>
            <p>Ar-Ge, yenilik ve tasarım projeleri ile ilgili araştırmalarda kullanılmak üzere ithal edilen eşya gümrük vergisinden ve diğer harçlardan istisnadır.</p>

            <h3>Temel Bilimler Desteği</h3>
            <p>En az lisans derecesine sahip Ar-Ge personeli istihdam eden teknoloji merkezlerine, bu personelin aylık ücretinin o yıl için uygulanan asgari ücretin brüt tutarı kadarlık kısmı, Sanayi ve Teknoloji Bakanlığı bütçesinden karşılanır.</p>
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}

