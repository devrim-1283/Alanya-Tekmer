import { Link } from 'react-router-dom';
import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';

export default function HomePage() {
  return (
    <PublicLayout>
      <SEO />
      
      {/* Hero Section */}
      <section className="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-20">
        <div className="container-custom">
          <div className="max-w-4xl mx-auto text-center">
            <h1 className="text-5xl md:text-6xl font-bold mb-6">
              ALANYA TEKMER
            </h1>
            <p className="text-2xl md:text-3xl mb-4 font-semibold">
              TEKNOLOJİ VE GİRİŞİMCİLİĞİN MERKEZİ
            </p>
            <p className="text-lg md:text-xl mb-8 text-primary-100">
              Alanya TEKMER olarak ALANYA TEKMER ALKÜ Kestel Yerleşkesinde 1085 m2 alan üzerine inşa edilmiş olup firmalar için konforlu odalar sunmaktadır.
            </p>
            <div className="flex flex-wrap gap-4 justify-center">
              <Link to="/basvuru" className="btn btn-primary bg-white text-primary-700 hover:bg-gray-100 text-lg px-8 py-4">
                Hemen Başvur
              </Link>
              <Link to="/hakkimizda" className="btn btn-outline border-white text-white hover:bg-white/10 text-lg px-8 py-4">
                Hakkımızda
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-16 bg-gray-50">
        <div className="container-custom">
          <h2 className="text-3xl md:text-4xl font-bold text-center mb-12">Özelliklerimiz</h2>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div className="card text-center">
              <div className="text-5xl mb-4">📏</div>
              <h3 className="text-xl font-bold mb-2">1085 m2 Alan</h3>
              <p className="text-gray-600">
                Alanya TEKMER olarak ALANYA TEKMER ALKÜ Kestel Yerleşkesinde 1085 m2 alan üzerine inşa edilmiş olup işletmeler için konforlu odalar sunmaktadır.
              </p>
            </div>

            <div className="card text-center">
              <div className="text-5xl mb-4">🏢</div>
              <h3 className="text-xl font-bold mb-2">13 Ofis</h3>
              <p className="text-gray-600">
                Alanya TEKMER, işletmelere modern ve konforlu 13 kapalı ofis sunarak verimli bir çalışma ortamı sağlamaktadır.
              </p>
            </div>

            <div className="card text-center">
              <div className="text-5xl mb-4">👥</div>
              <h3 className="text-xl font-bold mb-2">106 Firma için Ortak Çalışma Alanı</h3>
              <p className="text-gray-600">
                Girişimcilerin işbirliği yapabileceği ve verimli çalışabileceği 3 ortak alan sunulmaktadır.
              </p>
            </div>

            <div className="card text-center">
              <div className="text-5xl mb-4">🎓</div>
              <h3 className="text-xl font-bold mb-2">Uzman Ekip</h3>
              <p className="text-gray-600">
                Girişimciler ve işletmeler, deneyimli mentörlerden destek alarak projelerini geliştirme ve hedeflerine ulaşma fırsatı bulmaktadır.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section className="py-16">
        <div className="container-custom">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-3xl md:text-4xl font-bold text-center mb-8">BİZ KİMİZ?</h2>
            <div className="prose prose-lg max-w-none">
              <p className="text-lg text-gray-700 mb-4">
                ALANYA TEKMER A.Ş., Alanya Alaaddin Keykubat Üniversitesi ve Küçük ve Orta Ölçekli İşletmeleri Geliştirme ve Destekleme İdaresi Başkanlığı (KOSGEB) proje desteği ile 15 Ekim 2024 tarihinde kurulmuştur.
              </p>
              <p className="text-lg text-gray-700 mb-4">
                ALANYA TEKMER ALKÜ Kestel Yerleşkesinde 1085 m2 alan üzerine inşa edilmiş olup, bünyesinde; 13 kapalı ofis, 3 ortak çalışma alanı, 1 toplantı salonu, 1 eğitim salonu, 1 sosyal alan içermektedir.
              </p>
              <p className="text-lg text-gray-700">
                Alanya Alaaddin Keykubat Üniversitesi olarak hayata geçirdiğimiz TEKMER ile TR 61 Bölgesi'nin teknoloji ve inovasyon üssü olmayı hedefliyoruz. Girişimcilere ve teknoloji odaklı işletmelere sürdürülebilir büyüme için güçlü bir destek sunmakta kararlıyız.
              </p>
            </div>
            <div className="text-center mt-8">
              <Link to="/hakkimizda" className="btn btn-primary">
                Daha Fazla Bilgi
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 bg-primary-600 text-white">
        <div className="container-custom text-center">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">Projenizi Hayata Geçirin</h2>
          <p className="text-xl mb-8 text-primary-100">
            Alanya TEKMER'de yerinizi alın, projelerinizi geliştirin ve başarıya ulaşın.
          </p>
          <Link to="/basvuru" className="btn bg-white text-primary-700 hover:bg-gray-100 text-lg px-8 py-4">
            Başvuru Yap
          </Link>
        </div>
      </section>
    </PublicLayout>
  );
}

