-- Alanya TEKMER Database Initialization Script
-- This script creates all necessary tables for the application

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    username VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Team members table
CREATE TABLE IF NOT EXISTS team (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    photo_url VARCHAR(500) NOT NULL,
    name VARCHAR(200) NOT NULL,
    position VARCHAR(200) NOT NULL,
    order_index INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Events and announcements table
CREATE TABLE IF NOT EXISTS events (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    type VARCHAR(50) NOT NULL CHECK (type IN ('event', 'announcement')),
    title VARCHAR(500) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE,
    photos TEXT[] DEFAULT '{}',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Companies table
CREATE TABLE IF NOT EXISTS companies (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name VARCHAR(300) NOT NULL,
    logo_url VARCHAR(500),
    description TEXT,
    contact_person VARCHAR(200),
    phone VARCHAR(20),
    instagram VARCHAR(200),
    linkedin VARCHAR(200),
    website VARCHAR(300),
    whatsapp VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Applications table
CREATE TABLE IF NOT EXISTS applications (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    project_type VARCHAR(200) NOT NULL,
    business_idea VARCHAR(500) NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    tc_no VARCHAR(11) NOT NULL,
    email VARCHAR(200) NOT NULL,
    university VARCHAR(300),
    company_name VARCHAR(300),
    requested_area VARCHAR(200) NOT NULL,
    expectations TEXT NOT NULL,
    project_name VARCHAR(300) NOT NULL,
    team_size INTEGER NOT NULL,
    project_summary TEXT NOT NULL,
    project_file_url VARCHAR(500) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected', 'revision')),
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact information table
CREATE TABLE IF NOT EXISTS contact_info (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    email VARCHAR(200) NOT NULL,
    google_maps_url VARCHAR(500),
    facebook VARCHAR(300),
    youtube VARCHAR(300),
    linkedin VARCHAR(300),
    instagram VARCHAR(300),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Combobox options table
CREATE TABLE IF NOT EXISTS combobox_options (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    field_name VARCHAR(100) NOT NULL,
    option_value VARCHAR(300) NOT NULL,
    order_index INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(field_name, option_value)
);

-- Page analytics table
CREATE TABLE IF NOT EXISTS page_analytics (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    page_path VARCHAR(500) NOT NULL,
    ip_address VARCHAR(100) NOT NULL,
    unique_ip_hash VARCHAR(64) NOT NULL,
    user_agent TEXT,
    referer VARCHAR(500),
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cookie consents table
CREATE TABLE IF NOT EXISTS cookie_consents (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    ip_address VARCHAR(100) NOT NULL,
    consent_given BOOLEAN NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_events_type ON events(type);
CREATE INDEX IF NOT EXISTS idx_events_date ON events(event_date);
CREATE INDEX IF NOT EXISTS idx_applications_status ON applications(status);
CREATE INDEX IF NOT EXISTS idx_applications_created ON applications(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_analytics_page ON page_analytics(page_path);
CREATE INDEX IF NOT EXISTS idx_analytics_date ON page_analytics(visited_at DESC);
CREATE INDEX IF NOT EXISTS idx_analytics_ip_hash ON page_analytics(unique_ip_hash);
CREATE INDEX IF NOT EXISTS idx_combobox_field ON combobox_options(field_name);
CREATE INDEX IF NOT EXISTS idx_team_order ON team(order_index);

-- Create updated_at trigger function
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Add triggers for updated_at
CREATE TRIGGER update_admins_updated_at BEFORE UPDATE ON admins
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_team_updated_at BEFORE UPDATE ON team
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_events_updated_at BEFORE UPDATE ON events
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_companies_updated_at BEFORE UPDATE ON companies
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_applications_updated_at BEFORE UPDATE ON applications
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_contact_info_updated_at BEFORE UPDATE ON contact_info
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

