import { useQuery } from '@tanstack/react-query';
import PublicLayout from '../../layouts/PublicLayout';
import SEO from '../../components/SEO';
import { publicApi } from '../../lib/api';
import LoadingSpinner from '../../components/LoadingSpinner';

export default function CompaniesPage() {
  const { data, isLoading } = useQuery({
    queryKey: ['companies'],
    queryFn: async () => {
      const response = await publicApi.getCompanies();
      return response.data.data;
    },
  });

  if (isLoading) return <LoadingSpinner />;

  return (
    <PublicLayout>
      <SEO title="Firmalarımız" description="Alanya TEKMER'de yer alan firmalar" />
      
      <div className="py-16">
        <div className="container-custom">
          <h1 className="text-4xl font-bold text-center mb-12">Firmalarımız</h1>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {data && data.length > 0 ? (
              data.map((company: any) => (
                <div key={company.id} className="card">
                  {company.logo_url && (
                    <img src={company.logo_url} alt={company.name} className="w-full h-32 object-contain mb-4" />
                  )}
                  <h3 className="text-xl font-bold mb-2">{company.name}</h3>
                  {company.description && <p className="text-gray-600 mb-4">{company.description}</p>}
                  {company.contact_person && <p className="text-sm text-gray-500">Yetkili: {company.contact_person}</p>}
                  
                  <div className="flex flex-wrap gap-2 mt-4">
                    {company.website && (
                      <a href={company.website} target="_blank" rel="noopener noreferrer" className="text-primary-600 hover:underline text-sm">
                        🌐 Website
                      </a>
                    )}
                    {company.instagram && (
                      <a href={company.instagram} target="_blank" rel="noopener noreferrer" className="text-primary-600 hover:underline text-sm">
                        📷 Instagram
                      </a>
                    )}
                    {company.linkedin && (
                      <a href={company.linkedin} target="_blank" rel="noopener noreferrer" className="text-primary-600 hover:underline text-sm">
                        💼 LinkedIn
                      </a>
                    )}
                  </div>
                </div>
              ))
            ) : (
              <div className="col-span-full text-center text-gray-500">
                Henüz firma bulunmamaktadır.
              </div>
            )}
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}

