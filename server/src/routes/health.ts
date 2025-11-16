import { Router, Request, Response } from 'express';
import { query } from '../db/pool';
import { redis } from '../redis/client';
import { asyncHandler } from '../middleware/errorHandler';

const router = Router();

// Health check endpoint
router.get('/', asyncHandler(async (req: Request, res: Response) => {
  const health = {
    status: 'ok',
    timestamp: new Date().toISOString(),
    services: {
      database: false,
      redis: false,
    },
  };

  try {
    // Check database
    await query('SELECT 1');
    health.services.database = true;
  } catch (error) {
    health.status = 'error';
  }

  try {
    // Check Redis
    await redis.ping();
    health.services.redis = true;
  } catch (error) {
    health.status = 'error';
  }

  const statusCode = health.status === 'ok' ? 200 : 503;
  res.status(statusCode).json(health);
}));

export default router;

