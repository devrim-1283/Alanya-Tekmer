import { Request, Response } from 'express';
import { query } from '../db/pool';
import { AppError, asyncHandler } from '../middleware/errorHandler';
import { cacheService } from '../services/cacheService';
import { 
  applicationSchema, 
  ApplicationInput,
  analyticsTrackSchema,
  cookieConsentSchema 
} from '@alanya-tekmer/shared';
import { validateTurnstile } from '../middleware/security';
import { getClientIp, hashIp } from '../utils/ip';
import { uploadPDF, validateFileMagicNumbers, getFileUrl, deleteFile } from '../utils/fileUpload';
import { sendEmail, emailTemplates } from '../utils/email';
import { config } from '../config/env';
import path from 'path';

// Get team members
export const getTeam = asyncHandler(async (req: Request, res: Response) => {
  // Check cache first
  const cached = await cacheService.getTeam();
  if (cached) {
    return res.json({
      success: true,
      data: cached,
      cached: true,
    });
  }

  // Fetch from database
  const result = await query(
    'SELECT id, photo_url, name, position, order_index FROM team ORDER BY order_index ASC, created_at ASC'
  );

  // Cache the result
  await cacheService.setTeam(result.rows);

  res.json({
    success: true,
    data: result.rows,
  });
});

// Get events/announcements
export const getEvents = asyncHandler(async (req: Request, res: Response) => {
  const type = req.query.type as string | undefined;

  // Validate type if provided
  if (type && !['event', 'announcement'].includes(type)) {
    throw new AppError(400, 'Geçersiz tip parametresi');
  }

  // Check cache first
  const cached = await cacheService.getEvents(type);
  if (cached) {
    return res.json({
      success: true,
      data: cached,
      cached: true,
    });
  }

  // Build query
  let queryText = 'SELECT id, type, title, description, event_date, photos, created_at FROM events';
  const queryParams: any[] = [];

  if (type) {
    queryText += ' WHERE type = $1';
    queryParams.push(type);
  }

  queryText += ' ORDER BY created_at DESC';

  // Fetch from database
  const result = await query(queryText, queryParams);

  // Cache the result
  await cacheService.setEvents(result.rows, type);

  res.json({
    success: true,
    data: result.rows,
  });
});

// Get companies
export const getCompanies = asyncHandler(async (req: Request, res: Response) => {
  // Check cache first
  const cached = await cacheService.getCompanies();
  if (cached) {
    return res.json({
      success: true,
      data: cached,
      cached: true,
    });
  }

  // Fetch from database
  const result = await query(
    'SELECT id, name, logo_url, description, contact_person, phone, instagram, linkedin, website, whatsapp FROM companies ORDER BY created_at ASC'
  );

  // Cache the result (never expires automatically)
  await cacheService.setCompanies(result.rows);

  res.json({
    success: true,
    data: result.rows,
  });
});

// Get contact info
export const getContact = asyncHandler(async (req: Request, res: Response) => {
  // Check cache first
  const cached = await cacheService.getContact();
  if (cached) {
    return res.json({
      success: true,
      data: cached,
      cached: true,
    });
  }

  // Fetch from database
  const result = await query(
    'SELECT phone, address, email, google_maps_url, facebook, youtube, linkedin, instagram FROM contact_info ORDER BY updated_at DESC LIMIT 1'
  );

  if (result.rows.length === 0) {
    throw new AppError(404, 'İletişim bilgileri bulunamadı');
  }

  // Cache the result
  await cacheService.setContact(result.rows[0]);

  res.json({
    success: true,
    data: result.rows[0],
  });
});

// Get combobox options
export const getComboboxOptions = asyncHandler(async (req: Request, res: Response) => {
  const field = req.params.field;

  if (!field) {
    throw new AppError(400, 'Alan adı gerekli');
  }

  // Check cache first
  const cached = await cacheService.getCombobox(field);
  if (cached) {
    return res.json({
      success: true,
      data: cached,
      cached: true,
    });
  }

  // Fetch from database
  const result = await query(
    'SELECT id, option_value FROM combobox_options WHERE field_name = $1 ORDER BY order_index ASC',
    [field]
  );

  // Cache the result
  await cacheService.setCombobox(field, result.rows);

  res.json({
    success: true,
    data: result.rows,
  });
});

// Submit application
export const submitApplication = asyncHandler(async (req: Request, res: Response) => {
  // Handle file upload first
  await new Promise<void>((resolve, reject) => {
    uploadPDF.single('project_file')(req, res, (err: any) => {
      if (err) {
        reject(new AppError(400, err.message || 'Dosya yükleme hatası'));
      } else {
        resolve();
      }
    });
  });

  if (!req.file) {
    throw new AppError(400, 'Proje dosyası gerekli');
  }

  const filePath = req.file.path;

  try {
    // Validate PDF magic numbers
    const isValidPDF = await validateFileMagicNumbers(filePath, 'pdf');
    if (!isValidPDF) {
      deleteFile(filePath);
      throw new AppError(400, 'Geçersiz PDF dosyası');
    }

    // Parse and validate input
    const input: ApplicationInput = applicationSchema.parse({
      ...req.body,
      team_size: parseInt(req.body.team_size, 10),
    });

    // Validate Turnstile token
    const ip = getClientIp(req);
    const isTurnstileValid = await validateTurnstile(input.turnstileToken, ip);

    if (!isTurnstileValid) {
      deleteFile(filePath);
      throw new AppError(400, 'Güvenlik doğrulaması başarısız');
    }

    // Get file URL
    const fileUrl = getFileUrl(req.file.filename);

    // Insert application
    const result = await query(
      `INSERT INTO applications (
        project_type, business_idea, full_name, phone, tc_no, email,
        university, company_name, requested_area, expectations,
        project_name, team_size, project_summary, project_file_url, status
      ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, 'pending')
      RETURNING id`,
      [
        input.project_type,
        input.business_idea,
        input.full_name,
        input.phone,
        input.tc_no,
        input.email,
        input.university || null,
        input.company_name || null,
        input.requested_area,
        input.expectations,
        input.project_name,
        input.team_size,
        input.project_summary,
        fileUrl,
      ]
    );

    // Send emails (don't wait for them)
    const applicationId = result.rows[0].id;
    
    // Email to applicant
    const applicantEmail = emailTemplates.applicationReceived(input.full_name, input.project_name);
    sendEmail(input.email, applicantEmail.subject, applicantEmail.html);

    // Email to admin
    const adminEmail = emailTemplates.newApplicationAdmin(
      input.full_name,
      input.project_name,
      input.email,
      input.phone
    );
    sendEmail(config.email.admin, adminEmail.subject, adminEmail.html);

    res.status(201).json({
      success: true,
      message: 'Başvurunuz başarıyla alındı',
      data: {
        id: applicationId,
      },
    });
  } catch (error) {
    // Clean up uploaded file on error
    deleteFile(filePath);
    throw error;
  }
});

// Track page analytics
export const trackAnalytics = asyncHandler(async (req: Request, res: Response) => {
  const input = analyticsTrackSchema.parse(req.body);
  const ip = getClientIp(req);
  const ipHash = hashIp(ip);
  const userAgent = req.headers['user-agent'] || null;

  await query(
    `INSERT INTO page_analytics (page_path, ip_address, unique_ip_hash, user_agent, referer)
     VALUES ($1, $2, $3, $4, $5)`,
    [input.page_path, ip, ipHash, userAgent, input.referer || null]
  );

  res.json({
    success: true,
    message: 'Analytics kaydedildi',
  });
});

// Save cookie consent
export const saveCookieConsent = asyncHandler(async (req: Request, res: Response) => {
  const input = cookieConsentSchema.parse(req.body);
  const ip = getClientIp(req);

  await query(
    'INSERT INTO cookie_consents (ip_address, consent_given) VALUES ($1, $2)',
    [ip, input.consent_given]
  );

  res.json({
    success: true,
    message: 'Cookie onayı kaydedildi',
  });
});

