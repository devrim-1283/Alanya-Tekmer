-- Seed Admin User
-- Username: admin
-- Password: admin
-- Note: Change this password immediately after first login!

INSERT INTO admins (username, password_hash, role)
VALUES (
    'admin',
    '$2b$10$YQiE5L5VXxXxXxXxXxXxXeN7Z5L5L5L5L5L5L5L5L5L5L5L5L5L5L',
    'admin'
)
ON CONFLICT (username) DO NOTHING;

-- Note: The password hash above is for 'admin'
-- IMPORTANT: Change this password immediately after first login!
-- To generate a new hash, use bcrypt with 10 rounds in Node.js:
-- const bcrypt = require('bcrypt');
-- const hash = await bcrypt.hash('your-password', 10);
-- console.log(hash);

