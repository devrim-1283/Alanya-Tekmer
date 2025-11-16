import express, { Application } from 'express';
import compression from 'compression';
import path from 'path';
import { config, isDevelopment } from './config/env';
import { logger } from './utils/logger';
import { testConnection, closePool } from './db/pool';
import { testRedisConnection, closeRedis } from './redis/client';
import { helmetMiddleware, corsMiddleware, sanitizeInput } from './middleware/security';
import { sessionMiddleware } from './middleware/session';
import { errorHandler, notFoundHandler } from './middleware/errorHandler';
import { generalLimiter } from './middleware/rateLimiter';

// Import routes
import publicRoutes from './routes/public';
import adminRoutes from './routes/admin';
import healthRoutes from './routes/health';

const app: Application = express();

// Trust proxy (for Cloudflare)
app.set('trust proxy', 1);

// Security middleware
app.use(helmetMiddleware);
app.use(corsMiddleware);

// Body parsing middleware
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// Compression
app.use(compression());

// Session middleware
app.use(sessionMiddleware);

// Input sanitization
app.use(sanitizeInput);

// Rate limiting (general)
app.use('/api', generalLimiter);

// Routes
app.use('/health', healthRoutes);
app.use('/api/v1', publicRoutes);
app.use('/api/v1/admin', adminRoutes);

// Serve static files in production
if (!isDevelopment) {
  const clientBuildPath = path.join(__dirname, '../../client/dist');
  app.use(express.static(clientBuildPath));

  // Serve index.html for all other routes (SPA)
  app.get('*', (req, res) => {
    res.sendFile(path.join(clientBuildPath, 'index.html'));
  });
}

// Error handlers (must be last)
app.use(notFoundHandler);
app.use(errorHandler);

// Start server
async function startServer() {
  try {
    // Test database connection
    const dbConnected = await testConnection();
    if (!dbConnected) {
      throw new Error('Database connection failed');
    }

    // Test Redis connection
    const redisConnected = await testRedisConnection();
    if (!redisConnected) {
      throw new Error('Redis connection failed');
    }

    // Start listening
    app.listen(config.port, () => {
      logger.info(`Server started on port ${config.port}`, {
        environment: config.nodeEnv,
        debugMode: config.debugMode,
      });
    });
  } catch (error) {
    logger.error('Failed to start server', error);
    process.exit(1);
  }
}

// Graceful shutdown
process.on('SIGTERM', async () => {
  logger.info('SIGTERM received, shutting down gracefully');
  await closePool();
  await closeRedis();
  process.exit(0);
});

process.on('SIGINT', async () => {
  logger.info('SIGINT received, shutting down gracefully');
  await closePool();
  await closeRedis();
  process.exit(0);
});

// Handle uncaught exceptions
process.on('uncaughtException', (error) => {
  console.error('Uncaught exception:', error);
  logger.error('Uncaught exception', error);
  process.exit(1);
});

process.on('unhandledRejection', (reason: any, promise) => {
  console.error('Unhandled rejection:', reason);
  console.error('Promise:', promise);
  logger.error('Unhandled rejection', { reason, promise });
  process.exit(1);
});

// Start the server
startServer();

export default app;

