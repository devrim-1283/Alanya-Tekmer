import { useQuery } from '@tanstack/react-query';
import { adminApi } from '../../lib/api';

export default function AdminDashboard() {
  const { data } = useQuery({
    queryKey: ['dashboard'],
    queryFn: async () => {
      const response = await adminApi.getDashboard();
      return response.data.data;
    },
  });

  return (
    <div>
      <h1 className="text-3xl font-bold mb-8">Dashboard</h1>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="card">
          <h3 className="text-lg font-semibold mb-2">Bekleyen Başvurular</h3>
          <p className="text-4xl font-bold text-primary-600">{data?.applications?.pending || 0}</p>
        </div>

        <div className="card">
          <h3 className="text-lg font-semibold mb-2">Onaylanan Başvurular</h3>
          <p className="text-4xl font-bold text-green-600">{data?.applications?.approved || 0}</p>
        </div>

        <div className="card">
          <h3 className="text-lg font-semibold mb-2">Firmalar</h3>
          <p className="text-4xl font-bold text-blue-600">{data?.companies || 0}</p>
        </div>

        <div className="card">
          <h3 className="text-lg font-semibold mb-2">Bugünkü Ziyaretler</h3>
          <p className="text-4xl font-bold text-purple-600">{data?.todayVisits || 0}</p>
        </div>
      </div>
    </div>
  );
}

