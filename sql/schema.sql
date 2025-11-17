-- Alanya TEKMER Database Schema
-- PostgreSQL 14+

-- Settings table for site configuration
CREATE TABLE IF NOT EXISTS settings (
    id SERIAL PRIMARY KEY,
    key VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Team members
CREATE TABLE IF NOT EXISTS team (
    id SERIAL PRIMARY KEY,
    photo VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_team_sort ON team(sort_order);

-- Events and announcements
CREATE TABLE IF NOT EXISTS events (
    id SERIAL PRIMARY KEY,
    type VARCHAR(20) NOT NULL CHECK (type IN ('etkinlik', 'duyuru')),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    photos JSONB,
    event_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_events_type ON events(type);
CREATE INDEX idx_events_date ON events(event_date DESC);

-- Companies
CREATE TABLE IF NOT EXISTS companies (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    logo VARCHAR(255),
    description TEXT,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    instagram VARCHAR(255),
    linkedin VARCHAR(255),
    website VARCHAR(255),
    whatsapp VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_companies_active ON companies(is_active);

-- Combobox options for application form
CREATE TABLE IF NOT EXISTS combobox_options (
    id SERIAL PRIMARY KEY,
    type VARCHAR(50) NOT NULL CHECK (type IN ('proje_turu', 'faaliyet_alani', 'talep_edilen_alan')),
    value VARCHAR(255) NOT NULL,
    sort_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_combobox_type ON combobox_options(type, sort_order);

-- Applications
CREATE TABLE IF NOT EXISTS applications (
    id SERIAL PRIMARY KEY,
    project_name VARCHAR(255) NOT NULL,
    project_type VARCHAR(100) NOT NULL,
    activity_area VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    tc_number VARCHAR(11) NOT NULL,
    email VARCHAR(255) NOT NULL,
    university VARCHAR(255),
    department VARCHAR(255),
    company_name VARCHAR(255),
    requested_space VARCHAR(100) NOT NULL,
    expectations TEXT,
    team_size INTEGER,
    project_summary TEXT,
    project_file VARCHAR(255),
    data_consent BOOLEAN NOT NULL DEFAULT FALSE,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'reviewed', 'approved', 'rejected')),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_applications_status ON applications(status);
CREATE INDEX idx_applications_created ON applications(created_at DESC);
CREATE INDEX idx_applications_email ON applications(email);

-- Admin users
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    last_login TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Page views for analytics
CREATE TABLE IF NOT EXISTS page_views (
    id SERIAL PRIMARY KEY,
    page VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    unique_ip_hash VARCHAR(64),
    user_agent TEXT,
    referer TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_page_views_page ON page_views(page);
CREATE INDEX idx_page_views_created ON page_views(created_at DESC);
CREATE INDEX idx_page_views_unique_ip ON page_views(unique_ip_hash);

-- Contact form submissions
CREATE TABLE IF NOT EXISTS contact_submissions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status VARCHAR(20) DEFAULT 'new' CHECK (status IN ('new', 'read', 'replied')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_contact_status ON contact_submissions(status);
CREATE INDEX idx_contact_created ON contact_submissions(created_at DESC);

-- Insert default settings
INSERT INTO settings (key, value) VALUES
('site_name', 'Alanya TEKMER'),
('site_description', 'Teknoloji ve Girişimciliğin Merkezi'),
('contact_phone', '+90 242 505 6272'),
('contact_address', 'KESTEL MAH. ÜNİVERSİTE CAD. NO: 86/3 ALANYA / ANTALYA'),
('stat_companies', '25'),
('stat_entrepreneurs', '150'),
('stat_events', '50'),
('contact_email', 'destek@alanyatekmer.com'),
('google_maps_url', 'https://maps.app.goo.gl/ouvvfMy2ewS5Yz4f9'),
('facebook_url', 'https://www.facebook.com/AlanyaAlku/?locale=tr_TR'),
('youtube_url', 'https://www.youtube.com/channel/UCs40jTsKc_BgFcEg0krhMVw/videos'),
('linkedin_url', 'https://www.linkedin.com/404/'),
('instagram_url', 'https://www.instagram.com/alanyatekmer/')
ON CONFLICT (key) DO NOTHING;

-- Insert default combobox options
INSERT INTO combobox_options (type, value, sort_order) VALUES
('proje_turu', 'Ön İnkübasyon', 1),
('proje_turu', 'İnkübasyon', 2),
('proje_turu', 'İnkübasyon Sonrası', 3),
('faaliyet_alani', 'Yazılım ve Bilişim Teknolojileri', 1),
('faaliyet_alani', 'Ar-Ge ve İnovasyon', 2),
('faaliyet_alani', 'Biyoteknoloji', 3),
('faaliyet_alani', 'Enerji ve Çevre', 4),
('faaliyet_alani', 'Makine ve İmalat', 5),
('faaliyet_alani', 'Diğer', 6),
('talep_edilen_alan', 'Kapalı Ofis', 1),
('talep_edilen_alan', 'Ortak Çalışma Alanı', 2),
('talep_edilen_alan', 'Danışmanlık Desteği', 3)
ON CONFLICT DO NOTHING;

-- Insert default admin user (username: admin, password: Admin123!@# - MUST BE CHANGED!)
-- Password hash for 'Admin123!@#' with bcrypt cost 12
INSERT INTO admin_users (username, password_hash, email) VALUES
('admin', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5GyB3dyYVeDQy', 'admin@alanyatekmer.com')
ON CONFLICT (username) DO NOTHING;

-- Function to update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Triggers for updated_at
CREATE TRIGGER update_settings_updated_at BEFORE UPDATE ON settings FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_team_updated_at BEFORE UPDATE ON team FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_events_updated_at BEFORE UPDATE ON events FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_companies_updated_at BEFORE UPDATE ON companies FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_applications_updated_at BEFORE UPDATE ON applications FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

