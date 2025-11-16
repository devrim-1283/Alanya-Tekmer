import axios from 'axios';

// Track page view
export function trackPageView(path: string) {
  // Check if cookie consent is given
  const consent = localStorage.getItem('cookie_consent');
  if (consent !== 'true') return;

  // Send analytics data
  axios.post('/api/v1/analytics/track', {
    page_path: path,
    referer: document.referrer || undefined,
  }).catch(() => {
    // Silently fail - analytics should not break the app
  });
}

