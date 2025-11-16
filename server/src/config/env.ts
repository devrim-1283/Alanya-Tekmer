import * as dotenv from 'dotenv-safe';
import path from 'path';

// Load environment variables
dotenv.config({
  path: path.resolve(__dirname, '../../../.env'),
  example: path.resolve(__dirname, '../../../.env-example'),
  allowEmptyValues: false,
});

export const config = {
  // Server
  nodeEnv: process.env.NODE_ENV || 'development',
  port: parseInt(process.env.PORT || '3000', 10),
  debugMode: process.env.DEBUG_MODE === 'true',

  // Database
  databaseUrl: process.env.DATABASE_URL!,

  // Redis
  redisUrl: process.env.REDIS_URL!,

  // Session
  sessionSecret: process.env.SESSION_SECRET!,

  // Cloudflare Turnstile
  turnstileSecretKey: process.env.TURNSTILE_SECRET_KEY!,

  // SMTP
  smtp: {
    host: process.env.SMTP_HOST!,
    port: parseInt(process.env.SMTP_PORT || '587', 10),
    secure: process.env.SMTP_SECURE === 'true',
    user: process.env.SMTP_USER!,
    pass: process.env.SMTP_PASS!,
  },

  // Email
  email: {
    from: process.env.EMAIL_FROM!,
    admin: process.env.EMAIL_ADMIN!,
  },

  // File Upload
  maxFileSize: parseInt(process.env.MAX_FILE_SIZE || '10485760', 10),
  uploadPath: process.env.UPLOAD_PATH || '/app/uploads',

  // Domain
  frontendUrl: process.env.FRONTEND_URL!,

  // Sentry
  sentryDsn: process.env.SENTRY_DSN,

  // Rate Limiting
  rateLimit: {
    windowMs: parseInt(process.env.RATE_LIMIT_WINDOW_MS || '900000', 10),
    maxRequests: parseInt(process.env.RATE_LIMIT_MAX_REQUESTS || '100', 10),
  },
};

export const isDevelopment = config.nodeEnv === 'development';
export const isProduction = config.nodeEnv === 'production';

