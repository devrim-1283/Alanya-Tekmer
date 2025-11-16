import { useQuery } from '@tanstack/react-query';
import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';
import { publicApi } from '../../lib/api';
import LoadingSpinner from '../../components/LoadingSpinner';

export default function AnnouncementsPage() {
  const { data, isLoading } = useQuery({
    queryKey: ['events', 'announcement'],
    queryFn: async () => {
      const response = await publicApi.getEvents('announcement');
      return response.data.data;
    },
  });

  if (isLoading) return <LoadingSpinner />;

  return (
    <PublicLayout>
      <SEO title="Duyurular" description="Alanya TEKMER duyuruları" />
      
      <div className="py-16">
        <div className="container-custom">
          <h1 className="text-4xl font-bold text-center mb-12">Duyurular</h1>
          
          <div className="max-w-4xl mx-auto space-y-6">
            {data && data.length > 0 ? (
              data.map((announcement: any) => (
                <div key={announcement.id} className="card">
                  <h3 className="text-2xl font-bold mb-2">{announcement.title}</h3>
                  <p className="text-sm text-gray-500 mb-4">
                    {new Date(announcement.created_at).toLocaleDateString('tr-TR')}
                  </p>
                  <div className="prose max-w-none" dangerouslySetInnerHTML={{ __html: announcement.description }} />
                  {announcement.photos && announcement.photos.length > 0 && (
                    <div className="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                      {announcement.photos.map((photo: string, index: number) => (
                        <img key={index} src={photo} alt="" className="w-full h-32 object-cover rounded-lg" />
                      ))}
                    </div>
                  )}
                </div>
              ))
            ) : (
              <div className="text-center text-gray-500">
                Henüz duyuru bulunmamaktadır.
              </div>
            )}
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}

