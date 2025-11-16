import { Router } from 'express';
import { requireAuth, requireAdmin } from '../middleware/auth';
import { adminLoginLimiter } from '../middleware/rateLimiter';
import { login, logout, checkAuth } from '../controllers/authController';
import {
  getDashboardStats,
  getTeamMembers,
  createTeamMember,
  updateTeamMember,
  deleteTeamMember,
  getEventsAdmin,
  createEvent,
  updateEvent,
  deleteEvent,
  getCompaniesAdmin,
  createCompany,
  updateCompany,
  deleteCompany,
  getApplications,
  updateApplicationStatus,
  updateContactInfo,
  getComboboxOptionsAdmin,
  createComboboxOption,
  deleteComboboxOption,
  getAnalytics,
} from '../controllers/adminController';

const router = Router();

// Auth routes
router.post('/login', adminLoginLimiter, login);
router.post('/logout', requireAuth, logout);
router.get('/check-auth', checkAuth);

// All routes below require authentication
router.use(requireAuth);
router.use(requireAdmin);

// Dashboard
router.get('/dashboard', getDashboardStats);

// Team management
router.get('/team', getTeamMembers);
router.post('/team', createTeamMember);
router.put('/team/:id', updateTeamMember);
router.delete('/team/:id', deleteTeamMember);

// Event/Announcement management
router.get('/events', getEventsAdmin);
router.post('/events', createEvent);
router.put('/events/:id', updateEvent);
router.delete('/events/:id', deleteEvent);

// Company management
router.get('/companies', getCompaniesAdmin);
router.post('/companies', createCompany);
router.put('/companies/:id', updateCompany);
router.delete('/companies/:id', deleteCompany);

// Application management
router.get('/applications', getApplications);
router.patch('/applications/:id/status', updateApplicationStatus);

// Contact info management
router.put('/contact', updateContactInfo);

// Combobox options management
router.get('/combobox-options', getComboboxOptionsAdmin);
router.post('/combobox-options', createComboboxOption);
router.delete('/combobox-options/:id', deleteComboboxOption);

// Analytics
router.get('/analytics', getAnalytics);

export default router;

