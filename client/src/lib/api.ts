import axios from 'axios';

// Create axios instance
export const api = axios.create({
  baseURL: '/api/v1',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Redirect to login if unauthorized (admin routes)
      if (window.location.pathname.includes('ee9Y0hc8rx7yTACaaoXhSh9cOOhrVB7aXCfEzhaC3XAIrsgoi1')) {
        window.location.href = '/ee9Y0hc8rx7yTACaaoXhSh9cOOhrVB7aXCfEzhaC3XAIrsgoi1/login';
      }
    }
    return Promise.reject(error);
  }
);

// API endpoints
export const publicApi = {
  // Team
  getTeam: () => api.get('/team'),

  // Events
  getEvents: (type?: 'event' | 'announcement') => 
    api.get('/events', { params: { type } }),

  // Companies
  getCompanies: () => api.get('/companies'),

  // Contact
  getContact: () => api.get('/contact'),

  // Combobox options
  getComboboxOptions: (field: string) => api.get(`/combobox/${field}`),

  // Application
  submitApplication: (data: FormData) => 
    api.post('/application', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),

  // Cookie consent
  saveCookieConsent: (consentGiven: boolean) =>
    api.post('/cookie-consent', { consent_given: consentGiven }),
};

export const adminApi = {
  // Auth
  login: (username: string, password: string, turnstileToken: string) =>
    api.post('/admin/login', { username, password, turnstileToken }),

  logout: () => api.post('/admin/logout'),

  checkAuth: () => api.get('/admin/check-auth'),

  // Dashboard
  getDashboard: () => api.get('/admin/dashboard'),

  // Team
  getTeam: () => api.get('/admin/team'),
  createTeamMember: (data: FormData) =>
    api.post('/admin/team', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  updateTeamMember: (id: string, data: FormData) =>
    api.put(`/admin/team/${id}`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  deleteTeamMember: (id: string) => api.delete(`/admin/team/${id}`),

  // Events
  getEvents: (type?: string) => api.get('/admin/events', { params: { type } }),
  createEvent: (data: FormData) =>
    api.post('/admin/events', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  updateEvent: (id: string, data: FormData) =>
    api.put(`/admin/events/${id}`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  deleteEvent: (id: string) => api.delete(`/admin/events/${id}`),

  // Companies
  getCompanies: () => api.get('/admin/companies'),
  createCompany: (data: FormData) =>
    api.post('/admin/companies', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  updateCompany: (id: string, data: FormData) =>
    api.put(`/admin/companies/${id}`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  deleteCompany: (id: string) => api.delete(`/admin/companies/${id}`),

  // Applications
  getApplications: (status?: string, page?: number, limit?: number) =>
    api.get('/admin/applications', { params: { status, page, limit } }),
  updateApplicationStatus: (id: string, status: string, rejectionReason?: string) =>
    api.patch(`/admin/applications/${id}/status`, { status, rejection_reason: rejectionReason }),

  // Contact
  updateContact: (data: any) => api.put('/admin/contact', data),

  // Combobox options
  getComboboxOptions: (field?: string) =>
    api.get('/admin/combobox-options', { params: { field } }),
  createComboboxOption: (data: any) => api.post('/admin/combobox-options', data),
  deleteComboboxOption: (id: string) => api.delete(`/admin/combobox-options/${id}`),

  // Analytics
  getAnalytics: (days?: number) => api.get('/admin/analytics', { params: { days } }),
};

