import { cache } from '../redis/client';
import { logger } from '../utils/logger';

// Cache keys
export const CACHE_KEYS = {
  COMPANIES: 'companies:all',
  TEAM: 'team:all',
  EVENTS: (type?: string) => type ? `events:${type}` : 'events:all',
  CONTACT: 'contact:info',
  COMBOBOX: (field: string) => `combobox:${field}`,
};

// Cache TTL (in seconds)
export const CACHE_TTL = {
  COMPANIES: 0, // Never expire automatically (manual invalidation only)
  TEAM: 3600, // 1 hour
  EVENTS: 1800, // 30 minutes
  CONTACT: 86400, // 24 hours
  COMBOBOX: 86400, // 24 hours
};

// Cache service
export const cacheService = {
  // Get companies from cache
  async getCompanies() {
    return await cache.get(CACHE_KEYS.COMPANIES);
  },

  // Set companies in cache
  async setCompanies(data: any) {
    // No TTL - manual invalidation only
    return await cache.set(CACHE_KEYS.COMPANIES, data, CACHE_TTL.COMPANIES || 86400);
  },

  // Invalidate companies cache
  async invalidateCompanies() {
    logger.info('Invalidating companies cache');
    return await cache.del(CACHE_KEYS.COMPANIES);
  },

  // Get team from cache
  async getTeam() {
    return await cache.get(CACHE_KEYS.TEAM);
  },

  // Set team in cache
  async setTeam(data: any) {
    return await cache.set(CACHE_KEYS.TEAM, data, CACHE_TTL.TEAM);
  },

  // Invalidate team cache
  async invalidateTeam() {
    logger.info('Invalidating team cache');
    return await cache.del(CACHE_KEYS.TEAM);
  },

  // Get events from cache
  async getEvents(type?: string) {
    return await cache.get(CACHE_KEYS.EVENTS(type));
  },

  // Set events in cache
  async setEvents(data: any, type?: string) {
    return await cache.set(CACHE_KEYS.EVENTS(type), data, CACHE_TTL.EVENTS);
  },

  // Invalidate events cache
  async invalidateEvents() {
    logger.info('Invalidating events cache');
    await cache.del(CACHE_KEYS.EVENTS());
    await cache.del(CACHE_KEYS.EVENTS('event'));
    await cache.del(CACHE_KEYS.EVENTS('announcement'));
  },

  // Get contact info from cache
  async getContact() {
    return await cache.get(CACHE_KEYS.CONTACT);
  },

  // Set contact info in cache
  async setContact(data: any) {
    return await cache.set(CACHE_KEYS.CONTACT, data, CACHE_TTL.CONTACT);
  },

  // Invalidate contact cache
  async invalidateContact() {
    logger.info('Invalidating contact cache');
    return await cache.del(CACHE_KEYS.CONTACT);
  },

  // Get combobox options from cache
  async getCombobox(field: string) {
    return await cache.get(CACHE_KEYS.COMBOBOX(field));
  },

  // Set combobox options in cache
  async setCombobox(field: string, data: any) {
    return await cache.set(CACHE_KEYS.COMBOBOX(field), data, CACHE_TTL.COMBOBOX);
  },

  // Invalidate combobox cache
  async invalidateCombobox(field?: string) {
    logger.info('Invalidating combobox cache', { field });
    if (field) {
      return await cache.del(CACHE_KEYS.COMBOBOX(field));
    } else {
      return await cache.delPattern('combobox:*');
    }
  },
};

