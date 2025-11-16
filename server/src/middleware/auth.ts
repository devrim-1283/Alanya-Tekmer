import { Request, Response, NextFunction } from 'express';
import { AppError } from './errorHandler';

// Check if user is authenticated
export function requireAuth(req: Request, res: Response, next: NextFunction): void {
  if (!req.session.adminId) {
    throw new AppError(401, 'Giriş yapmanız gerekiyor');
  }
  next();
}

// Check if user is admin
export function requireAdmin(req: Request, res: Response, next: NextFunction): void {
  if (!req.session.adminId) {
    throw new AppError(401, 'Giriş yapmanız gerekiyor');
  }

  if (req.session.role !== 'admin') {
    throw new AppError(403, 'Bu işlem için yetkiniz yok');
  }

  next();
}

