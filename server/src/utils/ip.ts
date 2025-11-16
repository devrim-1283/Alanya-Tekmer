import { Request } from 'express';
import requestIp from 'request-ip';
import crypto from 'crypto';

// Get client IP address
export function getClientIp(req: Request): string {
  return requestIp.getClientIp(req) || 'unknown';
}

// Create unique hash for IP (for privacy)
export function hashIp(ip: string): string {
  return crypto.createHash('sha256').update(ip).digest('hex');
}

