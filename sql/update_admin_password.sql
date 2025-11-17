-- ========================================
-- Admin Şifre Güncelleme SQL Komutları
-- ========================================

-- SEÇENEK 1: Admin123!@# şifresi ile güncelle
UPDATE admin_users 
SET password_hash = '$2y$12$IGUcixUIpIDmra.zBynin.NBI/93WI/vcBBdWAvPqI1djTQQGuaIa',
    email = 'admin@alanyatekmer.com',
    is_active = true
WHERE username = 'admin';

-- Eğer admin kullanıcısı yoksa, oluştur:
INSERT INTO admin_users (username, password_hash, email, is_active) 
VALUES ('admin', '$2y$12$IGUcixUIpIDmra.zBynin.NBI/93WI/vcBBdWAvPqI1djTQQGuaIa', 'admin@alanyatekmer.com', true)
ON CONFLICT (username) DO UPDATE 
SET password_hash = '$2y$12$IGUcixUIpIDmra.zBynin.NBI/93WI/vcBBdWAvPqI1djTQQGuaIa',
    is_active = true;

-- ========================================
-- SEÇENEK 2: Tekmer2024! şifresi (daha güvenli)
-- ========================================

-- Eğer Tekmer2024! şifresini kullanmak isterseniz:
/*
UPDATE admin_users 
SET password_hash = '$2y$12$Ca/YYzl1glqh9Maa26GBruJGT7Q2Cet3g6eR2kyiHqYxEqA88j1.W',
    email = 'admin@alanyatekmer.com',
    is_active = true
WHERE username = 'admin';

INSERT INTO admin_users (username, password_hash, email, is_active) 
VALUES ('admin', '$2y$12$Ca/YYzl1glqh9Maa26GBruJGT7Q2Cet3g6eR2kyiHqYxEqA88j1.W', 'admin@alanyatekmer.com', true)
ON CONFLICT (username) DO UPDATE 
SET password_hash = '$2y$12$Ca/YYzl1glqh9Maa26GBruJGT7Q2Cet3g6eR2kyiHqYxEqA88j1.W',
    is_active = true;
*/

-- ========================================
-- Kontrol / Test Sorguları
-- ========================================

-- Kullanıcının olup olmadığını kontrol et
SELECT id, username, email, is_active, created_at, last_login 
FROM admin_users 
WHERE username = 'admin';

-- Tüm admin kullanıcıları listele
SELECT id, username, email, is_active, created_at, last_login 
FROM admin_users;

-- ========================================
-- Kullanım Talimatları
-- ========================================

-- Coolify Terminal'de:
-- psql $DATABASE_URL -f sql/update_admin_password.sql

-- Veya doğrudan:
-- psql $DATABASE_URL -c "UPDATE admin_users SET password_hash = '\$2y\$12\$IGUcixUIpIDmra.zBynin.NBI/93WI/vcBBdWAvPqI1djTQQGuaIa' WHERE username = 'admin';"

-- ========================================
-- Şifre Bilgileri
-- ========================================
-- Şifre 1: Admin123!@#
-- Hash 1: $2y$12$IGUcixUIpIDmra.zBynin.NBI/93WI/vcBBdWAvPqI1djTQQGuaIa
--
-- Şifre 2: Tekmer2024!
-- Hash 2: $2y$12$Ca/YYzl1glqh9Maa26GBruJGT7Q2Cet3g6eR2kyiHqYxEqA88j1.W

