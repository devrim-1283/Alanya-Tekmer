import nodemailer, { Transporter } from 'nodemailer';
import { config } from '../config/env';
import { logger } from './logger';

// Create email transporter
const transporter: Transporter = nodemailer.createTransport({
  host: config.smtp.host,
  port: config.smtp.port,
  secure: config.smtp.secure,
  auth: {
    user: config.smtp.user,
    pass: config.smtp.pass,
  },
  tls: {
    rejectUnauthorized: false, // Development only - remove in production
  },
});

// Verify transporter configuration (optional - don't block startup)
if (config.smtp.user && config.smtp.pass && config.smtp.user !== 'your-smtp-password-here') {
  transporter.verify((error) => {
    if (error) {
      logger.warn('Email transporter verification failed - emails will not be sent', { 
        error: error.message 
      });
    } else {
      logger.info('Email transporter is ready');
    }
  });
} else {
  logger.warn('SMTP credentials not configured - emails will not be sent');
}

// Email templates
export const emailTemplates = {
  // New application received (to applicant)
  applicationReceived: (name: string, projectName: string) => ({
    subject: 'Başvurunuz Alındı - Alanya TEKMER',
    html: `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <style>
          body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
          .container { max-width: 600px; margin: 0 auto; padding: 20px; }
          .header { background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
          .content { background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; }
          .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
          .button { display: inline-block; padding: 12px 24px; background: #0ea5e9; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h1>Alanya TEKMER</h1>
            <p>Teknoloji ve Girişimciliğin Merkezi</p>
          </div>
          <div class="content">
            <h2>Sayın ${name},</h2>
            <p>Başvurunuz başarıyla alınmıştır.</p>
            <p><strong>Proje Adı:</strong> ${projectName}</p>
            <p>Başvurunuz değerlendirme sürecine alınmıştır. İcra Kurulumuz projenizi detaylı inceleyecektir. Değerlendirme sonucu en kısa sürede tarafınıza bildirilecektir.</p>
            <p>Başvuru süreciyle ilgili sorularınız için bizimle iletişime geçebilirsiniz.</p>
            <p>Saygılarımızla,<br><strong>Alanya TEKMER Ekibi</strong></p>
          </div>
          <div class="footer">
            <p>Alanya TEKMER - KESTEL MAH. ÜNİVERSİTE CAD. NO: 86/3 ALANYA / ANTALYA</p>
            <p>Tel: +90 242 505 6272 | E-posta: destek@alanyatekmer.com</p>
          </div>
        </div>
      </body>
      </html>
    `,
  }),

  // New application notification (to admin)
  newApplicationAdmin: (name: string, projectName: string, email: string, phone: string) => ({
    subject: 'Yeni Başvuru - Alanya TEKMER',
    html: `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <style>
          body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
          .container { max-width: 600px; margin: 0 auto; padding: 20px; }
          .header { background: #0ea5e9; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
          .content { background: #fff; padding: 20px; border: 1px solid #e5e7eb; border-radius: 0 0 5px 5px; }
          .info { background: #f3f4f6; padding: 15px; border-radius: 5px; margin: 15px 0; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h2>Yeni Proje Başvurusu</h2>
          </div>
          <div class="content">
            <p>Yeni bir proje başvurusu alındı.</p>
            <div class="info">
              <p><strong>Başvuran:</strong> ${name}</p>
              <p><strong>Proje Adı:</strong> ${projectName}</p>
              <p><strong>E-posta:</strong> ${email}</p>
              <p><strong>Telefon:</strong> ${phone}</p>
            </div>
            <p>Admin panelinden başvuruyu inceleyebilirsiniz.</p>
          </div>
        </div>
      </body>
      </html>
    `,
  }),

  // Application approved
  applicationApproved: (name: string, projectName: string) => ({
    subject: 'Başvurunuz Onaylandı - Alanya TEKMER',
    html: `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <style>
          body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
          .container { max-width: 600px; margin: 0 auto; padding: 20px; }
          .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
          .content { background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; }
          .success { background: #d1fae5; border-left: 4px solid #10b981; padding: 15px; margin: 15px 0; border-radius: 5px; }
          .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h1>🎉 Tebrikler!</h1>
          </div>
          <div class="content">
            <h2>Sayın ${name},</h2>
            <div class="success">
              <p><strong>Başvurunuz onaylanmıştır!</strong></p>
            </div>
            <p><strong>Proje Adı:</strong> ${projectName}</p>
            <p>Başvurunuz İcra Kurulumuz tarafından değerlendirilmiş ve uygun bulunmuştur. Alanya TEKMER'e hoş geldiniz!</p>
            <p>Sonraki adımlar için en kısa sürede sizinle iletişime geçilecektir.</p>
            <p>Saygılarımızla,<br><strong>Alanya TEKMER Ekibi</strong></p>
          </div>
          <div class="footer">
            <p>Alanya TEKMER - KESTEL MAH. ÜNİVERSİTE CAD. NO: 86/3 ALANYA / ANTALYA</p>
            <p>Tel: +90 242 505 6272 | E-posta: destek@alanyatekmer.com</p>
          </div>
        </div>
      </body>
      </html>
    `,
  }),

  // Application rejected
  applicationRejected: (name: string, projectName: string, reason: string) => ({
    subject: 'Başvuru Değerlendirme Sonucu - Alanya TEKMER',
    html: `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <style>
          body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
          .container { max-width: 600px; margin: 0 auto; padding: 20px; }
          .header { background: #ef4444; color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
          .content { background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; }
          .info { background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin: 15px 0; border-radius: 5px; }
          .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h1>Başvuru Değerlendirme Sonucu</h1>
          </div>
          <div class="content">
            <h2>Sayın ${name},</h2>
            <p><strong>Proje Adı:</strong> ${projectName}</p>
            <p>Başvurunuz İcra Kurulumuz tarafından değerlendirilmiştir. Maalesef bu aşamada başvurunuz uygun bulunmamıştır.</p>
            <div class="info">
              <p><strong>Değerlendirme Notu:</strong></p>
              <p>${reason}</p>
            </div>
            <p>İlerleyen dönemlerde yeni başvurular yapabilirsiniz. Sorularınız için bizimle iletişime geçebilirsiniz.</p>
            <p>Saygılarımızla,<br><strong>Alanya TEKMER Ekibi</strong></p>
          </div>
          <div class="footer">
            <p>Alanya TEKMER - KESTEL MAH. ÜNİVERSİTE CAD. NO: 86/3 ALANYA / ANTALYA</p>
            <p>Tel: +90 242 505 6272 | E-posta: destek@alanyatekmer.com</p>
          </div>
        </div>
      </body>
      </html>
    `,
  }),

  // Application needs revision
  applicationRevision: (name: string, projectName: string, reason: string) => ({
    subject: 'Başvurunuzda Revize Gerekiyor - Alanya TEKMER',
    html: `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <style>
          body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
          .container { max-width: 600px; margin: 0 auto; padding: 20px; }
          .header { background: #f59e0b; color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
          .content { background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; }
          .info { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 15px 0; border-radius: 5px; }
          .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h1>Revize Gerekiyor</h1>
          </div>
          <div class="content">
            <h2>Sayın ${name},</h2>
            <p><strong>Proje Adı:</strong> ${projectName}</p>
            <p>Başvurunuz İcra Kurulumuz tarafından değerlendirilmiştir. Başvurunuzun değerlendirme sürecine devam edebilmesi için bazı revizyonlar yapılması gerekmektedir.</p>
            <div class="info">
              <p><strong>Revize Talepleri:</strong></p>
              <p>${reason}</p>
            </div>
            <p>Lütfen belirtilen revizyonları yaparak bizimle iletişime geçiniz.</p>
            <p>Saygılarımızla,<br><strong>Alanya TEKMER Ekibi</strong></p>
          </div>
          <div class="footer">
            <p>Alanya TEKMER - KESTEL MAH. ÜNİVERSİTE CAD. NO: 86/3 ALANYA / ANTALYA</p>
            <p>Tel: +90 242 505 6272 | E-posta: destek@alanyatekmer.com</p>
          </div>
        </div>
      </body>
      </html>
    `,
  }),
};

// Send email function
export async function sendEmail(to: string, subject: string, html: string): Promise<boolean> {
  try {
    await transporter.sendMail({
      from: `"Alanya TEKMER" <${config.email.from}>`,
      to,
      subject,
      html,
    });

    logger.info('Email sent successfully', { to, subject });
    return true;
  } catch (error) {
    logger.error('Email send failed', { to, subject, error });
    return false;
  }
}

