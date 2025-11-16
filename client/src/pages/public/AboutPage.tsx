import { useQuery } from '@tanstack/react-query';
import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';
import { publicApi } from '../../lib/api';
import LoadingSpinner from '../../components/LoadingSpinner';

export default function AboutPage() {
  const { data, isLoading } = useQuery({
    queryKey: ['team'],
    queryFn: async () => {
      const response = await publicApi.getTeam();
      return response.data.data;
    },
  });

  if (isLoading) return <LoadingSpinner />;

  return (
    <PublicLayout>
      <SEO 
        title="Hakkımızda" 
        description="Alanya TEKMER hakkında bilgi edinin. Ekibimiz, misyonumuz ve vizyonumuz."
      />
      
      <div className="py-16">
        <div className="container-custom">
          <h1 className="text-4xl font-bold text-center mb-12">Hakkımızda</h1>
          
          <div className="max-w-4xl mx-auto mb-16">
            <div className="prose prose-lg max-w-none">
              <h2>BİZ KİMİZ?</h2>
              <p>
                ALANYA TEKMER A.Ş., Alanya Alaaddin Keykubat Üniversitesi ve Küçük ve Orta Ölçekli İşletmeleri Geliştirme ve Destekleme İdaresi Başkanlığı (KOSGEB) proje desteği ile 15 Ekim 2024 tarihinde kurulmuştur.
              </p>
              <p>
                ALANYA TEKMER ALKÜ Kestel Yerleşkesinde 1085 m2 alan üzerine inşa edilmiş olup, bünyesinde; 13 kapalı ofis, 3 ortak çalışma alanı, 1 toplantı salonu, 1 eğitim salonu, 1 sosyal alan içermektedir.
              </p>
              <p>
                Alanya Alaaddin Keykubat Üniversitesi olarak hayata geçirdiğimiz TEKMER ile TR 61 Bölgesi'nin teknoloji ve inovasyon üssü olmayı hedefliyoruz. Girişimcilere ve teknoloji odaklı işletmelere sürdürülebilir büyüme için güçlü bir destek sunmakta kararlıyız.
              </p>

              <h2>MİSYON</h2>
              <p>
                Teknoloji Geliştirme Merkezi (TEKMER), Alanya Alaaddin Keykubat Üniversitesi tarafından kurulan ve bölge ekonomisine katkı sağlamak amacıyla hayata geçirilen bir inisiyatiftir. TEKMER'in temel misyonu, TR 61 Bölgesi'nde (Antalya, Isparta, Burdur) yer alan girişimciler, start-uplar ve teknoloji odaklı işletmeler için bir merkez oluşturarak, bölgenin bilim ve teknoloji tabanlı inovasyon potansiyelini harekete geçirmek ve sürdürülebilir ekonomik büyümeyi desteklemektir.
              </p>
            </div>
          </div>

          {data && data.length > 0 && (
            <>
              <h2 className="text-3xl font-bold text-center mb-8">Ekibimiz</h2>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {data.map((member: any) => (
                  <div key={member.id} className="card text-center">
                    <img 
                      src={member.photo_url} 
                      alt={member.name}
                      className="w-32 h-32 rounded-full mx-auto mb-4 object-cover"
                    />
                    <h3 className="text-xl font-bold mb-2">{member.name}</h3>
                    <p className="text-gray-600">{member.position}</p>
                  </div>
                ))}
              </div>
            </>
          )}
        </div>
      </div>
    </PublicLayout>
  );
}

