import { Request, Response, NextFunction } from 'express';
import { ZodError } from 'zod';
import { logger, logErrorToSentry } from '../utils/logger';
import { config } from '../config/env';

// Custom error class
export class AppError extends Error {
  constructor(
    public statusCode: number,
    public message: string,
    public isOperational = true
  ) {
    super(message);
    Object.setPrototypeOf(this, AppError.prototype);
    Error.captureStackTrace(this, this.constructor);
  }
}

// Error handler middleware
export function errorHandler(
  err: Error | AppError | ZodError,
  req: Request,
  res: Response,
  next: NextFunction
): void {
  // Log error
  logger.error('Error occurred', {
    error: err.message,
    stack: err.stack,
    path: req.path,
    method: req.method,
  });

  // Handle Zod validation errors
  if (err instanceof ZodError) {
    const errors = err.errors.map((e) => ({
      field: e.path.join('.'),
      message: e.message,
    }));

    res.status(400).json({
      success: false,
      message: 'Doğrulama hatası',
      errors,
    });
    return;
  }

  // Handle custom AppError
  if (err instanceof AppError) {
    res.status(err.statusCode).json({
      success: false,
      message: err.message,
    });
    return;
  }

  // Handle PostgreSQL errors
  if ('code' in err) {
    const pgError = err as any;
    
    if (pgError.code === '23505') {
      res.status(409).json({
        success: false,
        message: 'Bu kayıt zaten mevcut',
      });
      return;
    }

    if (pgError.code === '23503') {
      res.status(400).json({
        success: false,
        message: 'İlişkili kayıt bulunamadı',
      });
      return;
    }
  }

  // Log to Sentry for unexpected errors
  if (!(err instanceof AppError)) {
    logErrorToSentry(err, {
      path: req.path,
      method: req.method,
      body: req.body,
    });
  }

  // Send generic error response
  res.status(500).json({
    success: false,
    message: config.debugMode
      ? err.message
      : 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
    ...(config.debugMode && { stack: err.stack }),
  });
}

// Not found handler
export function notFoundHandler(req: Request, res: Response): void {
  res.status(404).json({
    success: false,
    message: 'İstenen kaynak bulunamadı',
  });
}

// Async handler wrapper
export function asyncHandler(
  fn: (req: Request, res: Response, next: NextFunction) => Promise<any>
) {
  return (req: Request, res: Response, next: NextFunction) => {
    Promise.resolve(fn(req, res, next)).catch(next);
  };
}

