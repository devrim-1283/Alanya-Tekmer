import { Routes, Route } from 'react-router-dom';
import { lazy, Suspense, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { trackPageView } from './lib/analytics';
import CookieConsent from './components/CookieConsent';
import LoadingSpinner from './components/LoadingSpinner';

// Public pages (lazy loaded)
const HomePage = lazy(() => import('./pages/public/HomePage'));
const AboutPage = lazy(() => import('./pages/public/AboutPage'));
const MevzuatPage = lazy(() => import('./pages/public/MevzuatPage'));
const ServicesPage = lazy(() => import('./pages/public/ServicesPage'));
const EventsPage = lazy(() => import('./pages/public/EventsPage'));
const AnnouncementsPage = lazy(() => import('./pages/public/AnnouncementsPage'));
const CompaniesPage = lazy(() => import('./pages/public/CompaniesPage'));
const ApplicationPage = lazy(() => import('./pages/public/ApplicationPage'));
const ContactPage = lazy(() => import('./pages/public/ContactPage'));
const PrivacyPage = lazy(() => import('./pages/public/PrivacyPage'));
const TermsPage = lazy(() => import('./pages/public/TermsPage'));

// Admin pages (lazy loaded)
const AdminLogin = lazy(() => import('./pages/admin/AdminLogin'));
const AdminLayout = lazy(() => import('./layouts/AdminLayout'));
const AdminDashboard = lazy(() => import('./pages/admin/AdminDashboard'));
const AdminTeam = lazy(() => import('./pages/admin/AdminTeam'));
const AdminEvents = lazy(() => import('./pages/admin/AdminEvents'));
const AdminCompanies = lazy(() => import('./pages/admin/AdminCompanies'));
const AdminApplications = lazy(() => import('./pages/admin/AdminApplications'));
const AdminSettings = lazy(() => import('./pages/admin/AdminSettings'));
const AdminAnalytics = lazy(() => import('./pages/admin/AdminAnalytics'));

// Admin route prefix
const ADMIN_PREFIX = '/ee9Y0hc8rx7yTACaaoXhSh9cOOhrVB7aXCfEzhaC3XAIrsgoi1';

function App() {
  const location = useLocation();

  // Track page views
  useEffect(() => {
    trackPageView(location.pathname);
  }, [location]);

  return (
    <>
      <Suspense fallback={<LoadingSpinner />}>
        <Routes>
          {/* Public routes */}
          <Route path="/" element={<HomePage />} />
          <Route path="/hakkimizda" element={<AboutPage />} />
          <Route path="/mevzuat" element={<MevzuatPage />} />
          <Route path="/hizmetlerimiz" element={<ServicesPage />} />
          <Route path="/etkinlikler" element={<EventsPage />} />
          <Route path="/duyurular" element={<AnnouncementsPage />} />
          <Route path="/firmalar" element={<CompaniesPage />} />
          <Route path="/basvuru" element={<ApplicationPage />} />
          <Route path="/iletisim" element={<ContactPage />} />
          <Route path="/gizlilik-sozlesmesi" element={<PrivacyPage />} />
          <Route path="/kullanici-sozlesmesi" element={<TermsPage />} />

          {/* Admin login */}
          <Route path={`${ADMIN_PREFIX}/login`} element={<AdminLogin />} />

          {/* Admin routes */}
          <Route path={ADMIN_PREFIX} element={<AdminLayout />}>
            <Route index element={<AdminDashboard />} />
            <Route path="dashboard" element={<AdminDashboard />} />
            <Route path="team" element={<AdminTeam />} />
            <Route path="events" element={<AdminEvents />} />
            <Route path="companies" element={<AdminCompanies />} />
            <Route path="applications" element={<AdminApplications />} />
            <Route path="settings" element={<AdminSettings />} />
            <Route path="analytics" element={<AdminAnalytics />} />
          </Route>

          {/* 404 */}
          <Route path="*" element={<HomePage />} />
        </Routes>
      </Suspense>

      <CookieConsent />
    </>
  );
}

export default App;

