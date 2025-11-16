import { z } from 'zod';

// Turkish phone number validation (supports multiple formats)
const phoneRegex = /^(\+90|0)?5\d{9}$/;

// TC Kimlik No validation (11 digits)
const tcNoRegex = /^\d{11}$/;

// Email validation
const emailSchema = z.string().email('Geçerli bir e-posta adresi giriniz');

// Phone validation
const phoneSchema = z.string().regex(phoneRegex, 'Geçerli bir telefon numarası giriniz (örn: 5386912283)');

// TC No validation
const tcNoSchema = z.string().regex(tcNoRegex, 'TC Kimlik No 11 haneli olmalıdır').refine((val) => {
  // TC Kimlik No algorithm validation
  if (val.length !== 11) return false;
  const digits = val.split('').map(Number);
  
  // First digit cannot be 0
  if (digits[0] === 0) return false;
  
  // 10th digit validation
  const sum1 = (digits[0] + digits[2] + digits[4] + digits[6] + digits[8]) * 7;
  const sum2 = digits[1] + digits[3] + digits[5] + digits[7];
  const digit10 = (sum1 - sum2) % 10;
  if (digits[9] !== digit10) return false;
  
  // 11th digit validation
  const sum3 = digits.slice(0, 10).reduce((a, b) => a + b, 0);
  const digit11 = sum3 % 10;
  if (digits[10] !== digit11) return false;
  
  return true;
}, 'Geçersiz TC Kimlik No');

// URL validation (optional)
const urlSchema = z.string().url('Geçerli bir URL giriniz').optional().or(z.literal(''));

// Admin login validation
export const adminLoginSchema = z.object({
  username: z.string().min(3, 'Kullanıcı adı en az 3 karakter olmalıdır'),
  password: z.string().min(5, 'Şifre en az 5 karakter olmalıdır'),
  turnstileToken: z.string().min(1, 'Güvenlik doğrulaması gerekli'),
});

export type AdminLoginInput = z.infer<typeof adminLoginSchema>;

// Team member validation
export const teamMemberSchema = z.object({
  name: z.string().min(2, 'İsim en az 2 karakter olmalıdır').max(200),
  position: z.string().min(2, 'Görev en az 2 karakter olmalıdır').max(200),
  order_index: z.number().int().min(0).default(0),
});

export type TeamMemberInput = z.infer<typeof teamMemberSchema>;

// Event validation
export const eventSchema = z.object({
  type: z.enum(['event', 'announcement'], {
    errorMap: () => ({ message: 'Tip etkinlik veya duyuru olmalıdır' }),
  }),
  title: z.string().min(5, 'Başlık en az 5 karakter olmalıdır').max(500),
  description: z.string().min(10, 'Açıklama en az 10 karakter olmalıdır'),
  event_date: z.string().optional().or(z.literal('')),
});

export type EventInput = z.infer<typeof eventSchema>;

// Company validation
export const companySchema = z.object({
  name: z.string().min(2, 'Firma adı en az 2 karakter olmalıdır').max(300),
  description: z.string().optional(),
  contact_person: z.string().max(200).optional(),
  phone: phoneSchema.optional().or(z.literal('')),
  instagram: urlSchema,
  linkedin: urlSchema,
  website: urlSchema,
  whatsapp: phoneSchema.optional().or(z.literal('')),
});

export type CompanyInput = z.infer<typeof companySchema>;

// Application validation
export const applicationSchema = z.object({
  project_type: z.string().min(1, 'Proje tipi seçiniz'),
  business_idea: z.string().min(1, 'Faaliyet alanı seçiniz'),
  full_name: z.string().min(3, 'Ad soyad en az 3 karakter olmalıdır').max(200),
  phone: phoneSchema,
  tc_no: tcNoSchema,
  email: emailSchema,
  university: z.string().max(300).optional().or(z.literal('')),
  company_name: z.string().max(300).optional().or(z.literal('')),
  requested_area: z.string().min(1, 'Talep edilen alan seçiniz'),
  expectations: z.string().min(20, 'Beklentilerinizi en az 20 karakter ile açıklayınız'),
  project_name: z.string().min(3, 'Proje adı en az 3 karakter olmalıdır').max(300),
  team_size: z.number().int().min(1, 'Ekip en az 1 kişiden oluşmalıdır').max(100),
  project_summary: z.string().min(50, 'Proje özeti en az 50 karakter olmalıdır'),
  data_consent: z.boolean().refine((val) => val === true, {
    message: 'Veri kullanım onayı vermelisiniz',
  }),
  turnstileToken: z.string().min(1, 'Güvenlik doğrulaması gerekli'),
});

export type ApplicationInput = z.infer<typeof applicationSchema>;

// Application status update validation
export const applicationStatusSchema = z.object({
  status: z.enum(['pending', 'approved', 'rejected', 'revision'], {
    errorMap: () => ({ message: 'Geçersiz durum' }),
  }),
  rejection_reason: z.string().optional(),
}).refine((data) => {
  // If status is rejected or revision, rejection_reason is required
  if ((data.status === 'rejected' || data.status === 'revision') && !data.rejection_reason) {
    return false;
  }
  return true;
}, {
  message: 'Red veya revize durumu için açıklama gereklidir',
  path: ['rejection_reason'],
});

export type ApplicationStatusInput = z.infer<typeof applicationStatusSchema>;

// Contact info validation
export const contactInfoSchema = z.object({
  phone: phoneSchema,
  address: z.string().min(10, 'Adres en az 10 karakter olmalıdır'),
  email: emailSchema,
  google_maps_url: urlSchema,
  facebook: urlSchema,
  youtube: urlSchema,
  linkedin: urlSchema,
  instagram: urlSchema,
});

export type ContactInfoInput = z.infer<typeof contactInfoSchema>;

// Combobox option validation
export const comboboxOptionSchema = z.object({
  field_name: z.string().min(1, 'Alan adı gerekli'),
  option_value: z.string().min(1, 'Değer gerekli').max(300),
  order_index: z.number().int().min(0).default(0),
});

export type ComboboxOptionInput = z.infer<typeof comboboxOptionSchema>;

// Analytics tracking validation
export const analyticsTrackSchema = z.object({
  page_path: z.string().min(1, 'Sayfa yolu gerekli'),
  referer: z.string().optional(),
});

export type AnalyticsTrackInput = z.infer<typeof analyticsTrackSchema>;

// Cookie consent validation
export const cookieConsentSchema = z.object({
  consent_given: z.boolean(),
});

export type CookieConsentInput = z.infer<typeof cookieConsentSchema>;

// File upload validation helpers
export const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
export const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
export const ALLOWED_PDF_TYPE = 'application/pdf';

// PDF magic numbers for validation
export const PDF_MAGIC_NUMBERS = [0x25, 0x50, 0x44, 0x46]; // %PDF

