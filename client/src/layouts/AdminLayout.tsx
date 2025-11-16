import { Outlet, Link, useNavigate, useLocation } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { adminApi } from '../lib/api';
import { useEffect } from 'react';

const ADMIN_PREFIX = '/ee9Y0hc8rx7yTACaaoXhSh9cOOhrVB7aXCfEzhaC3XAIrsgoi1';

export default function AdminLayout() {
  const navigate = useNavigate();
  const location = useLocation();

  const { data: authData, isLoading } = useQuery({
    queryKey: ['auth'],
    queryFn: async () => {
      const response = await adminApi.checkAuth();
      return response.data.data;
    },
    retry: false,
  });

  const logoutMutation = useMutation({
    mutationFn: adminApi.logout,
    onSuccess: () => {
      navigate(`${ADMIN_PREFIX}/login`);
    },
  });

  useEffect(() => {
    if (!isLoading && !authData) {
      navigate(`${ADMIN_PREFIX}/login`);
    }
  }, [authData, isLoading, navigate]);

  if (isLoading) {
    return <div className="min-h-screen flex items-center justify-center">Yükleniyor...</div>;
  }

  if (!authData) {
    return null;
  }

  const navLinks = [
    { path: `${ADMIN_PREFIX}/dashboard`, label: 'Dashboard', icon: '📊' },
    { path: `${ADMIN_PREFIX}/team`, label: 'Ekip', icon: '👥' },
    { path: `${ADMIN_PREFIX}/events`, label: 'Etkinlik/Duyuru', icon: '📅' },
    { path: `${ADMIN_PREFIX}/companies`, label: 'Firmalar', icon: '🏢' },
    { path: `${ADMIN_PREFIX}/applications`, label: 'Başvurular', icon: '📝' },
    { path: `${ADMIN_PREFIX}/settings`, label: 'Ayarlar', icon: '⚙️' },
    { path: `${ADMIN_PREFIX}/analytics`, label: 'Analytics', icon: '📈' },
  ];

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Sidebar */}
      <aside className="fixed left-0 top-0 h-full w-64 bg-gray-900 text-white p-6">
        <div className="mb-8">
          <h1 className="text-2xl font-bold">Alanya TEKMER</h1>
          <p className="text-sm text-gray-400">Admin Panel</p>
        </div>

        <nav className="space-y-2">
          {navLinks.map((link) => (
            <Link
              key={link.path}
              to={link.path}
              className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${
                location.pathname === link.path
                  ? 'bg-primary-600'
                  : 'hover:bg-gray-800'
              }`}
            >
              <span>{link.icon}</span>
              <span>{link.label}</span>
            </Link>
          ))}
        </nav>

        <div className="absolute bottom-6 left-6 right-6">
          <div className="border-t border-gray-700 pt-4">
            <p className="text-sm text-gray-400 mb-2">
              Hoş geldin, {authData.username}
            </p>
            <button
              onClick={() => logoutMutation.mutate()}
              className="w-full px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
            >
              Çıkış Yap
            </button>
          </div>
        </div>
      </aside>

      {/* Main content */}
      <main className="ml-64 p-8">
        <Outlet />
      </main>
    </div>
  );
}

