import multer, { FileFilterCallback } from 'multer';
import path from 'path';
import fs from 'fs';
import crypto from 'crypto';
import { Request } from 'express';
import { config } from '../config/env';
import { AppError } from '../middleware/errorHandler';
import { ALLOWED_IMAGE_TYPES, ALLOWED_PDF_TYPE, PDF_MAGIC_NUMBERS } from '@alanya-tekmer/shared';

// Ensure upload directory exists
if (!fs.existsSync(config.uploadPath)) {
  fs.mkdirSync(config.uploadPath, { recursive: true });
}

// Configure storage
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, config.uploadPath);
  },
  filename: (req, file, cb) => {
    // Generate unique filename
    const uniqueSuffix = crypto.randomBytes(16).toString('hex');
    const ext = path.extname(file.originalname);
    const sanitizedName = file.originalname
      .replace(ext, '')
      .replace(/[^a-zA-Z0-9]/g, '_')
      .substring(0, 50);
    cb(null, `${Date.now()}-${uniqueSuffix}-${sanitizedName}${ext}`);
  },
});

// File filter for images
const imageFilter = (req: Request, file: Express.Multer.File, cb: FileFilterCallback) => {
  if (ALLOWED_IMAGE_TYPES.includes(file.mimetype)) {
    cb(null, true);
  } else {
    cb(new AppError(400, 'Sadece resim dosyaları yüklenebilir (JPEG, PNG, WebP, GIF)'));
  }
};

// File filter for PDFs
const pdfFilter = (req: Request, file: Express.Multer.File, cb: FileFilterCallback) => {
  if (file.mimetype === ALLOWED_PDF_TYPE) {
    cb(null, true);
  } else {
    cb(new AppError(400, 'Sadece PDF dosyaları yüklenebilir'));
  }
};

// Multer configuration for images
export const uploadImage = multer({
  storage,
  fileFilter: imageFilter,
  limits: {
    fileSize: config.maxFileSize,
  },
});

// Multer configuration for PDFs
export const uploadPDF = multer({
  storage,
  fileFilter: pdfFilter,
  limits: {
    fileSize: config.maxFileSize,
  },
});

// Multer configuration for multiple images
export const uploadMultipleImages = multer({
  storage,
  fileFilter: imageFilter,
  limits: {
    fileSize: config.maxFileSize,
    files: 10, // Maximum 10 images
  },
});

// Validate file magic numbers (for extra security)
export async function validateFileMagicNumbers(filePath: string, expectedType: 'pdf' | 'image'): Promise<boolean> {
  return new Promise((resolve, reject) => {
    const stream = fs.createReadStream(filePath, { start: 0, end: 3 });
    const chunks: Buffer[] = [];

    stream.on('data', (chunk: any) => {
      chunks.push(Buffer.from(chunk));
    });

    stream.on('end', () => {
      const buffer = Buffer.concat(chunks);
      
      if (expectedType === 'pdf') {
        // Check PDF magic numbers: %PDF
        const isPDF = PDF_MAGIC_NUMBERS.every((byte, index) => buffer[index] === byte);
        resolve(isPDF);
      } else if (expectedType === 'image') {
        // Check common image magic numbers
        const bytes = Array.from(buffer);
        
        // JPEG: FF D8 FF
        const isJPEG = bytes[0] === 0xFF && bytes[1] === 0xD8 && bytes[2] === 0xFF;
        
        // PNG: 89 50 4E 47
        const isPNG = bytes[0] === 0x89 && bytes[1] === 0x50 && bytes[2] === 0x4E && bytes[3] === 0x47;
        
        // GIF: 47 49 46
        const isGIF = bytes[0] === 0x47 && bytes[1] === 0x49 && bytes[2] === 0x46;
        
        // WebP: 52 49 46 46 (RIFF)
        const isWebP = bytes[0] === 0x52 && bytes[1] === 0x49 && bytes[2] === 0x46 && bytes[3] === 0x46;
        
        resolve(isJPEG || isPNG || isGIF || isWebP);
      } else {
        resolve(false);
      }
    });

    stream.on('error', (error) => {
      reject(error);
    });
  });
}

// Delete file
export function deleteFile(filePath: string): void {
  try {
    if (fs.existsSync(filePath)) {
      fs.unlinkSync(filePath);
    }
  } catch (error) {
    // Log but don't throw - file deletion is not critical
    console.error('Error deleting file:', error);
  }
}

// Get file URL
export function getFileUrl(filename: string): string {
  return `/uploads/${filename}`;
}

