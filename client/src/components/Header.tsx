import { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { publicApi } from '../lib/api';

export default function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const location = useLocation();

  // Fetch contact info for header
  const { data: contactData } = useQuery({
    queryKey: ['contact'],
    queryFn: async () => {
      const response = await publicApi.getContact();
      return response.data.data;
    },
  });

  const isActive = (path: string) => location.pathname === path;

  const navLinks = [
    { path: '/', label: 'Ana Sayfa' },
    { path: '/hakkimizda', label: 'Hakkımızda' },
    { path: '/mevzuat', label: 'Mevzuat' },
    { path: '/hizmetlerimiz', label: 'Hizmetlerimiz' },
    { path: '/etkinlikler', label: 'Etkinlikler' },
    { path: '/duyurular', label: 'Duyurular' },
    { path: '/firmalar', label: 'Firmalar' },
    { path: '/basvuru', label: 'Başvuru' },
    { path: '/iletisim', label: 'İletişim' },
  ];

  return (
    <header className="sticky top-0 z-40 bg-white shadow-md">
      {/* Top bar */}
      <div className="bg-primary-700 text-white py-2">
        <div className="container-custom flex flex-wrap items-center justify-between text-sm">
          <div className="flex items-center gap-4">
            {contactData?.phone && (
              <a href={`tel:${contactData.phone}`} className="hover:text-primary-200 transition-colors">
                📞 {contactData.phone}
              </a>
            )}
            {contactData?.address && (
              <span className="hidden md:inline">📍 {contactData.address}</span>
            )}
          </div>
          <div className="flex items-center gap-3">
            {contactData?.facebook && (
              <a href={contactData.facebook} target="_blank" rel="noopener noreferrer" className="hover:text-primary-200 transition-colors">
                Facebook
              </a>
            )}
            {contactData?.instagram && (
              <a href={contactData.instagram} target="_blank" rel="noopener noreferrer" className="hover:text-primary-200 transition-colors">
                Instagram
              </a>
            )}
            {contactData?.linkedin && (
              <a href={contactData.linkedin} target="_blank" rel="noopener noreferrer" className="hover:text-primary-200 transition-colors">
                LinkedIn
              </a>
            )}
            {contactData?.youtube && (
              <a href={contactData.youtube} target="_blank" rel="noopener noreferrer" className="hover:text-primary-200 transition-colors">
                YouTube
              </a>
            )}
          </div>
        </div>
      </div>

      {/* Main navigation */}
      <nav className="container-custom py-4">
        <div className="flex items-center justify-between">
          {/* Logo */}
          <Link to="/" className="flex items-center gap-3">
            <img src="/logo.png" alt="Alanya TEKMER" className="h-12 w-auto" />
            <div>
              <h1 className="text-xl font-bold text-primary-700">Alanya TEKMER</h1>
              <p className="text-xs text-gray-600">Teknoloji ve Girişimciliğin Merkezi</p>
            </div>
          </Link>

          {/* Desktop navigation */}
          <div className="hidden lg:flex items-center gap-1">
            {navLinks.map((link) => (
              <Link
                key={link.path}
                to={link.path}
                className={`px-4 py-2 rounded-lg font-medium transition-colors ${
                  isActive(link.path)
                    ? 'bg-primary-600 text-white'
                    : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700'
                }`}
              >
                {link.label}
              </Link>
            ))}
          </div>

          {/* Mobile menu button */}
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="lg:hidden p-2 rounded-lg hover:bg-gray-100"
          >
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              {mobileMenuOpen ? (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              ) : (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
              )}
            </svg>
          </button>
        </div>

        {/* Mobile navigation */}
        {mobileMenuOpen && (
          <div className="lg:hidden mt-4 pb-4 border-t pt-4">
            <div className="flex flex-col gap-2">
              {navLinks.map((link) => (
                <Link
                  key={link.path}
                  to={link.path}
                  onClick={() => setMobileMenuOpen(false)}
                  className={`px-4 py-3 rounded-lg font-medium transition-colors ${
                    isActive(link.path)
                      ? 'bg-primary-600 text-white'
                      : 'text-gray-700 hover:bg-primary-50'
                  }`}
                >
                  {link.label}
                </Link>
              ))}
            </div>
          </div>
        )}
      </nav>
    </header>
  );
}

