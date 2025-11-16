import { Request, Response } from 'express';
import { query } from '../db/pool';
import { AppError, asyncHandler } from '../middleware/errorHandler';
import { cacheService } from '../services/cacheService';
import {
  teamMemberSchema,
  eventSchema,
  companySchema,
  applicationStatusSchema,
  contactInfoSchema,
  comboboxOptionSchema,
} from '@alanya-tekmer/shared';
import { uploadImage, uploadMultipleImages, validateFileMagicNumbers, getFileUrl, deleteFile } from '../utils/fileUpload';
import { sendEmail, emailTemplates } from '../utils/email';
import path from 'path';
import { config } from '../config/env';

// Dashboard stats
export const getDashboardStats = asyncHandler(async (req: Request, res: Response) => {
  const [applications, companies, events, team, todayVisits] = await Promise.all([
    query('SELECT COUNT(*) as count, status FROM applications GROUP BY status'),
    query('SELECT COUNT(*) as count FROM companies'),
    query('SELECT COUNT(*) as count FROM events'),
    query('SELECT COUNT(*) as count FROM team'),
    query(`SELECT COUNT(*) as count FROM page_analytics WHERE visited_at >= CURRENT_DATE`),
  ]);

  const applicationStats = applications.rows.reduce((acc: any, row: any) => {
    acc[row.status] = parseInt(row.count, 10);
    return acc;
  }, {});

  res.json({
    success: true,
    data: {
      applications: applicationStats,
      companies: parseInt(companies.rows[0].count, 10),
      events: parseInt(events.rows[0].count, 10),
      team: parseInt(team.rows[0].count, 10),
      todayVisits: parseInt(todayVisits.rows[0].count, 10),
    },
  });
});

// ===== TEAM MANAGEMENT =====

export const getTeamMembers = asyncHandler(async (req: Request, res: Response) => {
  const result = await query(
    'SELECT * FROM team ORDER BY order_index ASC, created_at ASC'
  );

  res.json({
    success: true,
    data: result.rows,
  });
});

export const createTeamMember = asyncHandler(async (req: Request, res: Response) => {
  // Handle file upload
  await new Promise<void>((resolve, reject) => {
    uploadImage.single('photo')(req, res, (err: any) => {
      if (err) reject(new AppError(400, err.message || 'Dosya yükleme hatası'));
      else resolve();
    });
  });

  if (!req.file) {
    throw new AppError(400, 'Fotoğraf gerekli');
  }

  const filePath = req.file.path;

  try {
    const isValidImage = await validateFileMagicNumbers(filePath, 'image');
    if (!isValidImage) {
      deleteFile(filePath);
      throw new AppError(400, 'Geçersiz resim dosyası');
    }

    const input = teamMemberSchema.parse(req.body);
    const photoUrl = getFileUrl(req.file.filename);

    const result = await query(
      'INSERT INTO team (photo_url, name, position, order_index) VALUES ($1, $2, $3, $4) RETURNING *',
      [photoUrl, input.name, input.position, input.order_index]
    );

    await cacheService.invalidateTeam();

    res.status(201).json({
      success: true,
      message: 'Ekip üyesi eklendi',
      data: result.rows[0],
    });
  } catch (error) {
    deleteFile(filePath);
    throw error;
  }
});

export const updateTeamMember = asyncHandler(async (req: Request, res: Response) => {
  const { id } = req.params;
  let photoUrl: string | undefined;

  // Handle file upload if provided
  if (req.headers['content-type']?.includes('multipart/form-data')) {
    await new Promise<void>((resolve, reject) => {
      uploadImage.single('photo')(req, res, (err: any) => {
        if (err) reject(new AppError(400, err.message));
        else resolve();
      });
    });

    if (req.file) {
      const filePath = req.file.path;
      const isValidImage = await validateFileMagicNumbers(filePath, 'image');
      if (!isValidImage) {
        deleteFile(filePath);
        throw new AppError(400, 'Geçersiz resim dosyası');
      }
      photoUrl = getFileUrl(req.file.filename);
    }
  }

  const input = teamMemberSchema.parse(req.body);

  const updateFields: string[] = [];
  const values: any[] = [];
  let paramIndex = 1;

  if (photoUrl) {
    updateFields.push(`photo_url = $${paramIndex++}`);
    values.push(photoUrl);
  }

  updateFields.push(`name = $${paramIndex++}`);
  values.push(input.name);

  updateFields.push(`position = $${paramIndex++}`);
  values.push(input.position);

  updateFields.push(`order_index = $${paramIndex++}`);
  values.push(input.order_index);

  values.push(id);

  const result = await query(
    `UPDATE team SET ${updateFields.join(', ')} WHERE id = $${paramIndex} RETURNING *`,
    values
  );

  if (result.rows.length === 0) {
    throw new AppError(404, 'Ekip üyesi bulunamadı');
  }

  await cacheService.invalidateTeam();

  res.json({
    success: true,
    message: 'Ekip üyesi güncellendi',
    data: result.rows[0],
  });
});

export const deleteTeamMember = asyncHandler(async (req: Request, res: Response) => {
  const { id } = req.params;

  const result = await query('DELETE FROM team WHERE id = $1 RETURNING *', [id]);

  if (result.rows.length === 0) {
    throw new AppError(404, 'Ekip üyesi bulunamadı');
  }

  await cacheService.invalidateTeam();

  res.json({
    success: true,
    message: 'Ekip üyesi silindi',
  });
});

// ===== EVENT/ANNOUNCEMENT MANAGEMENT =====

export const getEventsAdmin = asyncHandler(async (req: Request, res: Response) => {
  const type = req.query.type as string | undefined;
  
  let queryText = 'SELECT * FROM events';
  const queryParams: any[] = [];

  if (type) {
    queryText += ' WHERE type = $1';
    queryParams.push(type);
  }

  queryText += ' ORDER BY created_at DESC';

  const result = await query(queryText, queryParams);

  res.json({
    success: true,
    data: result.rows,
  });
});

export const createEvent = asyncHandler(async (req: Request, res: Response) => {
  // Handle multiple image uploads
  await new Promise<void>((resolve, reject) => {
    uploadMultipleImages.array('photos', 10)(req, res, (err: any) => {
      if (err) reject(new AppError(400, err.message));
      else resolve();
    });
  });

  const files = req.files as Express.Multer.File[];
  const photoUrls: string[] = [];

  try {
    // Validate all images
    if (files && files.length > 0) {
      for (const file of files) {
        const isValidImage = await validateFileMagicNumbers(file.path, 'image');
        if (!isValidImage) {
          files.forEach(f => deleteFile(f.path));
          throw new AppError(400, 'Geçersiz resim dosyası');
        }
        photoUrls.push(getFileUrl(file.filename));
      }
    }

    const input = eventSchema.parse(req.body);

    const result = await query(
      'INSERT INTO events (type, title, description, event_date, photos) VALUES ($1, $2, $3, $4, $5) RETURNING *',
      [input.type, input.title, input.description, input.event_date || null, photoUrls]
    );

    await cacheService.invalidateEvents();

    res.status(201).json({
      success: true,
      message: 'Etkinlik/Duyuru eklendi',
      data: result.rows[0],
    });
  } catch (error) {
    if (files) files.forEach(f => deleteFile(f.path));
    throw error;
  }
});

export const updateEvent = asyncHandler(async (req: Request, res: Response) => {
  const { id } = req.params;
  let newPhotoUrls: string[] = [];

  // Handle multiple image uploads if provided
  if (req.headers['content-type']?.includes('multipart/form-data')) {
    await new Promise<void>((resolve, reject) => {
      uploadMultipleImages.array('photos', 10)(req, res, (err: any) => {
        if (err) reject(new AppError(400, err.message));
        else resolve();
      });
    });

    const files = req.files as Express.Multer.File[];
    if (files && files.length > 0) {
      for (const file of files) {
        const isValidImage = await validateFileMagicNumbers(file.path, 'image');
        if (!isValidImage) {
          files.forEach(f => deleteFile(f.path));
          throw new AppError(400, 'Geçersiz resim dosyası');
        }
        newPhotoUrls.push(getFileUrl(file.filename));
      }
    }
  }

  const input = eventSchema.parse(req.body);

  const updateFields: string[] = [];
  const values: any[] = [];
  let paramIndex = 1;

  updateFields.push(`type = $${paramIndex++}`);
  values.push(input.type);

  updateFields.push(`title = $${paramIndex++}`);
  values.push(input.title);

  updateFields.push(`description = $${paramIndex++}`);
  values.push(input.description);

  updateFields.push(`event_date = $${paramIndex++}`);
  values.push(input.event_date || null);

  if (newPhotoUrls.length > 0) {
    updateFields.push(`photos = $${paramIndex++}`);
    values.push(newPhotoUrls);
  }

  values.push(id);

  const result = await query(
    `UPDATE events SET ${updateFields.join(', ')} WHERE id = $${paramIndex} RETURNING *`,
    values
  );

  if (result.rows.length === 0) {
    throw new AppError(404, 'Etkinlik/Duyuru bulunamadı');
  }

  await cacheService.invalidateEvents();

  res.json({
    success: true,
    message: 'Etkinlik/Duyuru güncellendi',
    data: result.rows[0],
  });
});

export const deleteEvent = asyncHandler(async (req: Request, res: Response) => {
  const { id } = req.params;

  const result = await query('DELETE FROM events WHERE id = $1 RETURNING *', [id]);

  if (result.rows.length === 0) {
    throw new AppError(404, 'Etkinlik/Duyuru bulunamadı');
  }

  await cacheService.invalidateEvents();

  res.json({
    success: true,
    message: 'Etkinlik/Duyuru silindi',
  });
});

// ===== COMPANY MANAGEMENT =====

export const getCompaniesAdmin = asyncHandler(async (req: Request, res: Response) => {
  const result = await query('SELECT * FROM companies ORDER BY created_at ASC');

  res.json({
    success: true,
    data: result.rows,
  });
});

export const createCompany = asyncHandler(async (req: Request, res: Response) => {
  let logoUrl: string | undefined;

  // Handle file upload if provided
  if (req.headers['content-type']?.includes('multipart/form-data')) {
    await new Promise<void>((resolve, reject) => {
      uploadImage.single('logo')(req, res, (err: any) => {
        if (err) reject(new AppError(400, err.message));
        else resolve();
      });
    });

    if (req.file) {
      const filePath = req.file.path;
      const isValidImage = await validateFileMagicNumbers(filePath, 'image');
      if (!isValidImage) {
        deleteFile(filePath);
        throw new AppError(400, 'Geçersiz resim dosyası');
      }
      logoUrl = getFileUrl(req.file.filename);
    }
  }

  const input = companySchema.parse(req.body);

  const result = await query(
    `INSERT INTO companies (name, logo_url, description, contact_person, phone, instagram, linkedin, website, whatsapp)
     VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9) RETURNING *`,
    [
      input.name,
      logoUrl || null,
      input.description || null,
      input.contact_person || null,
      input.phone || null,
      input.instagram || null,
      input.linkedin || null,
      input.website || null,
      input.whatsapp || null,
    ]
  );

  await cacheService.invalidateCompanies();

  res.status(201).json({
    success: true,
    message: 'Firma eklendi',
    data: result.rows[0],
  });
});

export const updateCompany = asyncHandler(async (req: Request, res: Response) => {
  const { id } = req.params;
  let logoUrl: string | undefined;

  // Handle file upload if provided
  if (req.headers['content-type']?.includes('multipart/form-data')) {
    await new Promise<void>((resolve, reject) => {
      uploadImage.single('logo')(req, res, (err: any) => {
        if (err) reject(new AppError(400, err.message));
        else resolve();
      });
    });

    if (req.file) {
      const filePath = req.file.path;
      const isValidImage = await validateFileMagicNumbers(filePath, 'image');
      if (!isValidImage) {
        deleteFile(filePath);
        throw new AppError(400, 'Geçersiz resim dosyası');
      }
      logoUrl = getFileUrl(req.file.filename);
    }
  }

  const input = companySchema.parse(req.body);

  const updateFields: string[] = [];
  const values: any[] = [];
  let paramIndex = 1;

  if (logoUrl) {
    updateFields.push(`logo_url = $${paramIndex++}`);
    values.push(logoUrl);
  }

  updateFields.push(`name = $${paramIndex++}`);
  values.push(input.name);

  updateFields.push(`description = $${paramIndex++}`);
  values.push(input.description || null);

  updateFields.push(`contact_person = $${paramIndex++}`);
  values.push(input.contact_person || null);

  updateFields.push(`phone = $${paramIndex++}`);
  values.push(input.phone || null);

  updateFields.push(`instagram = $${paramIndex++}`);
  values.push(input.instagram || null);

  updateFields.push(`linkedin = $${paramIndex++}`);
  values.push(input.linkedin || null);

  updateFields.push(`website = $${paramIndex++}`);
  values.push(input.website || null);

  updateFields.push(`whatsapp = $${paramIndex++}`);
  values.push(input.whatsapp || null);

  values.push(id);

  const result = await query(
    `UPDATE companies SET ${updateFields.join(', ')} WHERE id = $${paramIndex} RETURNING *`,
    values
  );

  if (result.rows.length === 0) {
    throw new AppError(404, 'Firma bulunamadı');
  }

  await cacheService.invalidateCompanies();

  res.json({
    success: true,
    message: 'Firma güncellendi',
    data: result.rows[0],
  });
});

export const deleteCompany = asyncHandler(async (req: Request, res: Response) => {
  const { id } = req.params;

  const result = await query('DELETE FROM companies WHERE id = $1 RETURNING *', [id]);

  if (result.rows.length === 0) {
    throw new AppError(404, 'Firma bulunamadı');
  }

  await cacheService.invalidateCompanies();

  res.json({
    success: true,
    message: 'Firma silindi',
  });
});

// ===== APPLICATION MANAGEMENT =====

export const getApplications = asyncHandler(async (req: Request, res: Response) => {
  const status = req.query.status as string | undefined;
  const page = parseInt(req.query.page as string, 10) || 1;
  const limit = parseInt(req.query.limit as string, 10) || 20;
  const offset = (page - 1) * limit;

  let queryText = 'SELECT * FROM applications';
  const queryParams: any[] = [];

  if (status) {
    queryText += ' WHERE status = $1';
    queryParams.push(status);
  }

  queryText += ' ORDER BY created_at DESC LIMIT $' + (queryParams.length + 1) + ' OFFSET $' + (queryParams.length + 2);
  queryParams.push(limit, offset);

  const [result, countResult] = await Promise.all([
    query(queryText, queryParams),
    query(`SELECT COUNT(*) as count FROM applications${status ? ' WHERE status = $1' : ''}`, status ? [status] : []),
  ]);

  res.json({
    success: true,
    data: result.rows,
    pagination: {
      page,
      limit,
      total: parseInt(countResult.rows[0].count, 10),
    },
  });
});

export const updateApplicationStatus = asyncHandler(async (req: Request, res: Response) => {
  const { id } = req.params;
  const input = applicationStatusSchema.parse(req.body);

  // Get application details for email
  const appResult = await query(
    'SELECT full_name, email, project_name FROM applications WHERE id = $1',
    [id]
  );

  if (appResult.rows.length === 0) {
    throw new AppError(404, 'Başvuru bulunamadı');
  }

  const application = appResult.rows[0];

  // Update status
  const result = await query(
    'UPDATE applications SET status = $1, rejection_reason = $2 WHERE id = $3 RETURNING *',
    [input.status, input.rejection_reason || null, id]
  );

  // Send email based on status
  let emailTemplate;
  switch (input.status) {
    case 'approved':
      emailTemplate = emailTemplates.applicationApproved(application.full_name, application.project_name);
      break;
    case 'rejected':
      emailTemplate = emailTemplates.applicationRejected(application.full_name, application.project_name, input.rejection_reason || '');
      break;
    case 'revision':
      emailTemplate = emailTemplates.applicationRevision(application.full_name, application.project_name, input.rejection_reason || '');
      break;
  }

  if (emailTemplate) {
    sendEmail(application.email, emailTemplate.subject, emailTemplate.html);
  }

  res.json({
    success: true,
    message: 'Başvuru durumu güncellendi',
    data: result.rows[0],
  });
});

// ===== CONTACT INFO MANAGEMENT =====

export const updateContactInfo = asyncHandler(async (req: Request, res: Response) => {
  const input = contactInfoSchema.parse(req.body);

  // Check if contact info exists
  const existing = await query('SELECT id FROM contact_info LIMIT 1');

  let result;
  if (existing.rows.length > 0) {
    // Update existing
    result = await query(
      `UPDATE contact_info SET phone = $1, address = $2, email = $3, google_maps_url = $4,
       facebook = $5, youtube = $6, linkedin = $7, instagram = $8 WHERE id = $9 RETURNING *`,
      [
        input.phone,
        input.address,
        input.email,
        input.google_maps_url || null,
        input.facebook || null,
        input.youtube || null,
        input.linkedin || null,
        input.instagram || null,
        existing.rows[0].id,
      ]
    );
  } else {
    // Insert new
    result = await query(
      `INSERT INTO contact_info (phone, address, email, google_maps_url, facebook, youtube, linkedin, instagram)
       VALUES ($1, $2, $3, $4, $5, $6, $7, $8) RETURNING *`,
      [
        input.phone,
        input.address,
        input.email,
        input.google_maps_url || null,
        input.facebook || null,
        input.youtube || null,
        input.linkedin || null,
        input.instagram || null,
      ]
    );
  }

  await cacheService.invalidateContact();

  res.json({
    success: true,
    message: 'İletişim bilgileri güncellendi',
    data: result.rows[0],
  });
});

// ===== COMBOBOX OPTIONS MANAGEMENT =====

export const getComboboxOptionsAdmin = asyncHandler(async (req: Request, res: Response) => {
  const field = req.query.field as string | undefined;

  let queryText = 'SELECT * FROM combobox_options';
  const queryParams: any[] = [];

  if (field) {
    queryText += ' WHERE field_name = $1';
    queryParams.push(field);
  }

  queryText += ' ORDER BY field_name, order_index ASC';

  const result = await query(queryText, queryParams);

  res.json({
    success: true,
    data: result.rows,
  });
});

export const createComboboxOption = asyncHandler(async (req: Request, res: Response) => {
  const input = comboboxOptionSchema.parse(req.body);

  const result = await query(
    'INSERT INTO combobox_options (field_name, option_value, order_index) VALUES ($1, $2, $3) RETURNING *',
    [input.field_name, input.option_value, input.order_index]
  );

  await cacheService.invalidateCombobox(input.field_name);

  res.status(201).json({
    success: true,
    message: 'Seçenek eklendi',
    data: result.rows[0],
  });
});

export const deleteComboboxOption = asyncHandler(async (req: Request, res: Response) => {
  const { id } = req.params;

  const result = await query('DELETE FROM combobox_options WHERE id = $1 RETURNING field_name', [id]);

  if (result.rows.length === 0) {
    throw new AppError(404, 'Seçenek bulunamadı');
  }

  await cacheService.invalidateCombobox(result.rows[0].field_name);

  res.json({
    success: true,
    message: 'Seçenek silindi',
  });
});

// ===== ANALYTICS =====

export const getAnalytics = asyncHandler(async (req: Request, res: Response) => {
  const days = parseInt(req.query.days as string, 10) || 30;

  const [pageViews, topPages, uniqueVisitors] = await Promise.all([
    query(
      `SELECT DATE(visited_at) as date, COUNT(*) as count
       FROM page_analytics
       WHERE visited_at >= CURRENT_DATE - INTERVAL '${days} days'
       GROUP BY DATE(visited_at)
       ORDER BY date DESC`
    ),
    query(
      `SELECT page_path, COUNT(*) as count
       FROM page_analytics
       WHERE visited_at >= CURRENT_DATE - INTERVAL '${days} days'
       GROUP BY page_path
       ORDER BY count DESC
       LIMIT 10`
    ),
    query(
      `SELECT COUNT(DISTINCT unique_ip_hash) as count
       FROM page_analytics
       WHERE visited_at >= CURRENT_DATE - INTERVAL '${days} days'`
    ),
  ]);

  res.json({
    success: true,
    data: {
      pageViews: pageViews.rows,
      topPages: topPages.rows,
      uniqueVisitors: parseInt(uniqueVisitors.rows[0].count, 10),
    },
  });
});

