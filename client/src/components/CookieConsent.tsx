import { useState, useEffect } from 'react';
import { publicApi } from '../lib/api';

export default function CookieConsent() {
  const [show, setShow] = useState(false);

  useEffect(() => {
    // Check if user has already given consent
    const consent = localStorage.getItem('cookie_consent');
    if (!consent) {
      setShow(true);
    }
  }, []);

  const handleAccept = async () => {
    localStorage.setItem('cookie_consent', 'true');
    setShow(false);
    
    // Send consent to backend
    try {
      await publicApi.saveCookieConsent(true);
    } catch (error) {
      console.error('Failed to save cookie consent', error);
    }
  };

  const handleReject = async () => {
    localStorage.setItem('cookie_consent', 'false');
    setShow(false);
    
    // Send rejection to backend
    try {
      await publicApi.saveCookieConsent(false);
    } catch (error) {
      console.error('Failed to save cookie consent', error);
    }
  };

  if (!show) return null;

  return (
    <div className="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 shadow-2xl z-50 p-4 md:p-6">
      <div className="container-custom max-w-6xl mx-auto">
        <div className="flex flex-col md:flex-row items-center justify-between gap-4">
          <div className="flex-1">
            <h3 className="font-bold text-lg mb-2">🍪 Çerez Kullanımı</h3>
            <p className="text-sm text-gray-600">
              Web sitemizde deneyiminizi geliştirmek ve site trafiğini analiz etmek için çerezler kullanıyoruz. 
              Detaylı bilgi için{' '}
              <a href="/gizlilik-sozlesmesi" className="text-primary-600 hover:underline">
                Gizlilik Sözleşmesi
              </a>
              'ni inceleyebilirsiniz.
            </p>
          </div>
          <div className="flex gap-3">
            <button
              onClick={handleReject}
              className="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors"
            >
              Reddet
            </button>
            <button
              onClick={handleAccept}
              className="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
            >
              Kabul Et
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}

