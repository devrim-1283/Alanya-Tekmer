import { Link } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { publicApi } from '../lib/api';

export default function Footer() {
  const { data: contactData } = useQuery({
    queryKey: ['contact'],
    queryFn: async () => {
      const response = await publicApi.getContact();
      return response.data.data;
    },
  });

  return (
    <footer className="bg-gray-900 text-white">
      <div className="container-custom py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* About */}
          <div>
            <h3 className="text-lg font-bold mb-4">Alanya TEKMER</h3>
            <p className="text-gray-400 text-sm mb-4">
              Teknoloji ve Girişimciliğin Merkezi. TR 61 Bölgesi'nde girişimciler ve teknoloji odaklı işletmeler için destek merkezi.
            </p>
            <div className="flex gap-2">
              <img src="/footerlogo.png" alt="ALKÜ" className="h-12 w-auto" />
              <img src="/kosgeb.png" alt="KOSGEB" className="h-12 w-auto" />
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-lg font-bold mb-4">Hızlı Linkler</h3>
            <ul className="space-y-2 text-sm">
              <li><Link to="/hakkimizda" className="text-gray-400 hover:text-white transition-colors">Hakkımızda</Link></li>
              <li><Link to="/hizmetlerimiz" className="text-gray-400 hover:text-white transition-colors">Hizmetlerimiz</Link></li>
              <li><Link to="/mevzuat" className="text-gray-400 hover:text-white transition-colors">Mevzuat</Link></li>
              <li><Link to="/firmalar" className="text-gray-400 hover:text-white transition-colors">Firmalar</Link></li>
              <li><Link to="/basvuru" className="text-gray-400 hover:text-white transition-colors">Başvuru</Link></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h3 className="text-lg font-bold mb-4">İletişim</h3>
            <ul className="space-y-2 text-sm text-gray-400">
              {contactData?.phone && (
                <li>
                  <a href={`tel:${contactData.phone}`} className="hover:text-white transition-colors">
                    📞 {contactData.phone}
                  </a>
                </li>
              )}
              {contactData?.email && (
                <li>
                  <a href={`mailto:${contactData.email}`} className="hover:text-white transition-colors">
                    ✉️ {contactData.email}
                  </a>
                </li>
              )}
              {contactData?.address && (
                <li>📍 {contactData.address}</li>
              )}
            </ul>
          </div>

          {/* Social Media */}
          <div>
            <h3 className="text-lg font-bold mb-4">Sosyal Medya</h3>
            <div className="flex flex-col gap-2 text-sm">
              {contactData?.facebook && (
                <a href={contactData.facebook} target="_blank" rel="noopener noreferrer" className="text-gray-400 hover:text-white transition-colors">
                  Facebook
                </a>
              )}
              {contactData?.instagram && (
                <a href={contactData.instagram} target="_blank" rel="noopener noreferrer" className="text-gray-400 hover:text-white transition-colors">
                  Instagram
                </a>
              )}
              {contactData?.linkedin && (
                <a href={contactData.linkedin} target="_blank" rel="noopener noreferrer" className="text-gray-400 hover:text-white transition-colors">
                  LinkedIn
                </a>
              )}
              {contactData?.youtube && (
                <a href={contactData.youtube} target="_blank" rel="noopener noreferrer" className="text-gray-400 hover:text-white transition-colors">
                  YouTube
                </a>
              )}
            </div>
          </div>
        </div>

        {/* Bottom bar */}
        <div className="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-400">
          <p>© 2024 Alanya TEKMER. Tüm hakları saklıdır.</p>
          <div className="flex gap-4">
            <Link to="/gizlilik-sozlesmesi" className="hover:text-white transition-colors">
              Gizlilik Sözleşmesi
            </Link>
            <Link to="/kullanici-sozlesmesi" className="hover:text-white transition-colors">
              Kullanıcı Sözleşmesi
            </Link>
          </div>
          <p>
            Bu site{' '}
            <a href="https://www.devrimtuncer.com" target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors">
              www.devrimtuncer.com
            </a>
            {' '}tarafından geliştirilmiştir.
          </p>
        </div>
      </div>
    </footer>
  );
}

