import { Request, Response } from 'express';
import bcrypt from 'bcrypt';
import { query } from '../db/pool';
import { AppError, asyncHandler } from '../middleware/errorHandler';
import { adminLoginSchema, AdminLoginInput } from '@alanya-tekmer/shared';
import { validateTurnstile } from '../middleware/security';
import { getClientIp } from '../utils/ip';
import { logger } from '../utils/logger';

// Admin login
export const login = asyncHandler(async (req: Request, res: Response) => {
  // Validate input
  const input: AdminLoginInput = adminLoginSchema.parse(req.body);

  // Validate Turnstile token
  const ip = getClientIp(req);
  const isTurnstileValid = await validateTurnstile(input.turnstileToken, ip);
  
  if (!isTurnstileValid) {
    throw new AppError(400, 'Güvenlik doğrulaması başarısız');
  }

  // Find admin user
  const result = await query(
    'SELECT id, username, password_hash, role FROM admins WHERE username = $1',
    [input.username]
  );

  if (result.rows.length === 0) {
    throw new AppError(401, 'Kullanıcı adı veya şifre hatalı');
  }

  const admin = result.rows[0];

  // Verify password
  const isPasswordValid = await bcrypt.compare(input.password, admin.password_hash);

  if (!isPasswordValid) {
    throw new AppError(401, 'Kullanıcı adı veya şifre hatalı');
  }

  // Create session
  req.session.adminId = admin.id;
  req.session.username = admin.username;
  req.session.role = admin.role;

  logger.info('Admin logged in', { username: admin.username, ip });

  res.json({
    success: true,
    message: 'Giriş başarılı',
    data: {
      id: admin.id,
      username: admin.username,
      role: admin.role,
    },
  });
});

// Admin logout
export const logout = asyncHandler(async (req: Request, res: Response) => {
  const username = req.session.username;

  req.session.destroy((err) => {
    if (err) {
      logger.error('Session destroy error', err);
      throw new AppError(500, 'Çıkış yapılırken bir hata oluştu');
    }

    logger.info('Admin logged out', { username });

    res.json({
      success: true,
      message: 'Çıkış başarılı',
    });
  });
});

// Check auth status
export const checkAuth = asyncHandler(async (req: Request, res: Response) => {
  if (!req.session.adminId) {
    throw new AppError(401, 'Giriş yapılmamış');
  }

  res.json({
    success: true,
    data: {
      id: req.session.adminId,
      username: req.session.username,
      role: req.session.role,
    },
  });
});

