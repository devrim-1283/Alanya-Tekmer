-- Notifications table for admin notifications
-- Run this migration to add notifications support

CREATE TABLE IF NOT EXISTS notifications (
    id SERIAL PRIMARY KEY,
    type VARCHAR(50) NOT NULL CHECK (type IN ('new_application', 'contact_form', 'system', 'other')),
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    reference_type VARCHAR(50), -- 'application', 'contact', etc.
    reference_id INTEGER, -- ID of the related item
    is_read BOOLEAN DEFAULT FALSE,
    is_acknowledged BOOLEAN DEFAULT FALSE, -- Onaylandı mı?
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_notifications_type ON notifications(type);
CREATE INDEX idx_notifications_read ON notifications(is_read);
CREATE INDEX idx_notifications_acknowledged ON notifications(is_acknowledged);
CREATE INDEX idx_notifications_created ON notifications(created_at DESC);

-- Trigger for updated_at
CREATE TRIGGER update_notifications_updated_at 
    BEFORE UPDATE ON notifications 
    FOR EACH ROW 
    EXECUTE FUNCTION update_updated_at_column();

-- Comments
COMMENT ON TABLE notifications IS 'Admin panel notifications';
COMMENT ON COLUMN notifications.type IS 'Type of notification: new_application, contact_form, system, other';
COMMENT ON COLUMN notifications.is_read IS 'Whether notification has been viewed';
COMMENT ON COLUMN notifications.is_acknowledged IS 'Whether notification has been acknowledged/approved';

