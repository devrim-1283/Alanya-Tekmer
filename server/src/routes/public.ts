import { Router } from 'express';
import {
  getTeam,
  getEvents,
  getCompanies,
  getContact,
  getComboboxOptions,
  submitApplication,
  trackAnalytics,
  saveCookieConsent,
} from '../controllers/publicController';
import { applicationLimiter } from '../middleware/rateLimiter';

const router = Router();

// Public routes
router.get('/team', getTeam);
router.get('/events', getEvents);
router.get('/companies', getCompanies);
router.get('/contact', getContact);
router.get('/combobox/:field', getComboboxOptions);

// Application submission (with rate limiting)
router.post('/application', applicationLimiter, submitApplication);

// Analytics tracking
router.post('/analytics/track', trackAnalytics);

// Cookie consent
router.post('/cookie-consent', saveCookieConsent);

export default router;

