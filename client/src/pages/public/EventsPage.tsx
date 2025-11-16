import { useQuery } from '@tanstack/react-query';
import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';
import { publicApi } from '../../lib/api';
import LoadingSpinner from '../../components/LoadingSpinner';

export default function EventsPage() {
  const { data, isLoading } = useQuery({
    queryKey: ['events', 'event'],
    queryFn: async () => {
      const response = await publicApi.getEvents('event');
      return response.data.data;
    },
  });

  if (isLoading) return <LoadingSpinner />;

  return (
    <PublicLayout>
      <SEO title="Etkinlikler" description="Alanya TEKMER etkinlikleri" />
      
      <div className="py-16">
        <div className="container-custom">
          <h1 className="text-4xl font-bold text-center mb-12">Etkinlikler</h1>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {data && data.length > 0 ? (
              data.map((event: any) => (
                <div key={event.id} className="card">
                  {event.photos && event.photos.length > 0 && (
                    <img src={event.photos[0]} alt={event.title} className="w-full h-48 object-cover rounded-lg mb-4" />
                  )}
                  <h3 className="text-xl font-bold mb-2">{event.title}</h3>
                  {event.event_date && (
                    <p className="text-sm text-gray-500 mb-2">
                      {new Date(event.event_date).toLocaleDateString('tr-TR')}
                    </p>
                  )}
                  <p className="text-gray-600">{event.description}</p>
                </div>
              ))
            ) : (
              <div className="col-span-full text-center text-gray-500">
                Henüz etkinlik bulunmamaktadır.
              </div>
            )}
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}

