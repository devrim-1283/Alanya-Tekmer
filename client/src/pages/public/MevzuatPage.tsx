import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';

export default function MevzuatPage() {
  const mevzuatlar = [
    { name: '4691 Sayılı Teknoloji Geliştirme Bölgeleri Kanunu', file: '/mevzuat/4691-SAYILI-TEKNOLOJI-GELISTIRME-BOLGELERI-KANUNU.pdf' },
    { name: '4691 Sayılı Teknoloji Geliştirme Bölgeleri Yönetmeliği', file: '/mevzuat/4691-SAYILI-TEKNOLOJI-GELISTIRME-BOLGELERI-YONETMELIGI.pdf' },
    { name: '5746 Ar-Ge Faaliyetlerinin Desteklenmesi Kanunu Yönetmeliği', file: '/mevzuat/5746_ARGE-FAALIYETLERININ-DESTEKLENMESI-KANUNU-YONETMELIGI.pdf' },
    { name: '7263 Sayılı Kanun', file: '/mevzuat/7263-SAYILI-KANUN.pdf' },
    { name: 'Cumhurbaşkanlığı Kararnamesi', file: '/mevzuat/CUMHURBASKANLIGI-KARARNAMESI.pdf' },
    { name: 'İŞGEM TEKMER Destek Programı', file: '/mevzuat/ISGEM_TEKMER-Destek-Programi.pdf' },
    { name: 'İŞGEM TEKMER Destek Programı Uygulama Esasları', file: '/mevzuat/ISGEM_TEKMER_Destek-Programi-Uygulama-Esaslari.pdf' },
    { name: 'KOSGEB Destek Programları Yönetmeliği', file: '/mevzuat/KOSGEB_Destek_Programlari_Yonetmeligi.pdf' },
  ];

  return (
    <PublicLayout>
      <SEO title="Mevzuat" description="Alanya TEKMER mevzuatları ve resmi belgeler" />
      
      <div className="py-16">
        <div className="container-custom">
          <h1 className="text-4xl font-bold text-center mb-12">Mevzuat</h1>
          
          <div className="max-w-4xl mx-auto">
            <p className="text-lg text-gray-700 mb-8 text-center">
              Alanya TEKMER'in mevzuatlarının resmi kaynaklarıdır. Aşağıdaki belgeler resmi belgelerdir.
            </p>

            <div className="grid gap-4">
              {mevzuatlar.map((mevzuat, index) => (
                <a
                  key={index}
                  href={mevzuat.file}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="card hover:shadow-xl transition-shadow flex items-center justify-between"
                >
                  <div className="flex items-center gap-4">
                    <div className="text-4xl">📄</div>
                    <span className="font-medium">{mevzuat.name}</span>
                  </div>
                  <div className="text-primary-600">İndir →</div>
                </a>
              ))}
            </div>
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}

