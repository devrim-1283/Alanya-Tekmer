import session from 'express-session';
import RedisStore from 'connect-redis';
import { redis } from '../redis/client';
import { config, isProduction } from '../config/env';

// Create Redis store for sessions
const redisStore = new RedisStore({
  client: redis,
  prefix: 'sess:',
  ttl: 86400, // 24 hours
});

// Session middleware configuration
export const sessionMiddleware = session({
  store: redisStore,
  secret: config.sessionSecret,
  resave: false,
  saveUninitialized: false,
  name: 'alanya_tekmer_sid',
  cookie: {
    secure: isProduction, // HTTPS only in production
    httpOnly: true,
    maxAge: 86400000, // 24 hours
    sameSite: 'strict',
  },
});

// Extend session types
declare module 'express-session' {
  interface SessionData {
    adminId?: string;
    username?: string;
    role?: string;
  }
}

