import { useQuery } from '@tanstack/react-query';
import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';
import { publicApi } from '../../lib/api';
import LoadingSpinner from '../../components/LoadingSpinner';

export default function ContactPage() {
  const { data, isLoading } = useQuery({
    queryKey: ['contact'],
    queryFn: async () => {
      const response = await publicApi.getContact();
      return response.data.data;
    },
  });

  if (isLoading) return <LoadingSpinner />;

  return (
    <PublicLayout>
      <SEO title="İletişim" description="Alanya TEKMER ile iletişime geçin" />
      
      <div className="py-16">
        <div className="container-custom">
          <h1 className="text-4xl font-bold text-center mb-12">İletişim</h1>
          
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
            <div className="card">
              <h2 className="text-2xl font-bold mb-6">İletişim Bilgileri</h2>
              <div className="space-y-4">
                {data?.phone && (
                  <div className="flex items-start gap-3">
                    <div className="text-2xl">📞</div>
                    <div>
                      <p className="font-semibold">Telefon</p>
                      <a href={`tel:${data.phone}`} className="text-primary-600 hover:underline">
                        {data.phone}
                      </a>
                    </div>
                  </div>
                )}
                {data?.email && (
                  <div className="flex items-start gap-3">
                    <div className="text-2xl">✉️</div>
                    <div>
                      <p className="font-semibold">E-posta</p>
                      <a href={`mailto:${data.email}`} className="text-primary-600 hover:underline">
                        {data.email}
                      </a>
                    </div>
                  </div>
                )}
                {data?.address && (
                  <div className="flex items-start gap-3">
                    <div className="text-2xl">📍</div>
                    <div>
                      <p className="font-semibold">Adres</p>
                      <p className="text-gray-600">{data.address}</p>
                      {data.google_maps_url && (
                        <a href={data.google_maps_url} target="_blank" rel="noopener noreferrer" className="text-primary-600 hover:underline text-sm mt-1 inline-block">
                          Haritada Göster →
                        </a>
                      )}
                    </div>
                  </div>
                )}
              </div>

              <div className="mt-8 pt-8 border-t">
                <h3 className="font-semibold mb-4">Sosyal Medya</h3>
                <div className="flex flex-wrap gap-3">
                  {data?.facebook && (
                    <a href={data.facebook} target="_blank" rel="noopener noreferrer" className="btn btn-outline">
                      Facebook
                    </a>
                  )}
                  {data?.instagram && (
                    <a href={data.instagram} target="_blank" rel="noopener noreferrer" className="btn btn-outline">
                      Instagram
                    </a>
                  )}
                  {data?.linkedin && (
                    <a href={data.linkedin} target="_blank" rel="noopener noreferrer" className="btn btn-outline">
                      LinkedIn
                    </a>
                  )}
                  {data?.youtube && (
                    <a href={data.youtube} target="_blank" rel="noopener noreferrer" className="btn btn-outline">
                      YouTube
                    </a>
                  )}
                </div>
              </div>
            </div>

            {data?.google_maps_url && (
              <div className="card p-0 overflow-hidden">
                <iframe
                  src={data.google_maps_url.replace('maps.app.goo.gl', 'www.google.com/maps/embed?pb=')}
                  width="100%"
                  height="450"
                  style={{ border: 0 }}
                  allowFullScreen
                  loading="lazy"
                  referrerPolicy="no-referrer-when-downgrade"
                  title="Alanya TEKMER Konum"
                ></iframe>
              </div>
            )}
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}

