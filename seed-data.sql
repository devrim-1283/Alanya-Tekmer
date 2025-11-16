-- Seed Initial Data for Alanya TEKMER

-- Insert contact information
INSERT INTO contact_info (phone, address, email, google_maps_url, facebook, youtube, linkedin, instagram)
VALUES (
    '+90 242 505 6272',
    'KESTEL MAH. ÜNİVERSİTE CAD. NO: 86/3 ALANYA / ANTALYA',
    'destek@alanyatekmer.com',
    'https://maps.app.goo.gl/ouvvfMy2ewS5Yz4f9',
    'https://www.facebook.com/AlanyaAlku/?locale=tr_TR',
    'https://www.youtube.com/channel/UCs40jTsKc_BgFcEg0krhMVw/videos',
    'https://www.linkedin.com/404/',
    'https://www.instagram.com/alanyatekmer/'
)
ON CONFLICT DO NOTHING;

-- Insert combobox options for "başvurulan proje"
INSERT INTO combobox_options (field_name, option_value, order_index)
VALUES 
    ('project_type', 'Kulukça', 1),
    ('project_type', 'Yazılım Projesi', 2),
    ('project_type', 'Donanım Projesi', 3),
    ('project_type', 'Ar-Ge Projesi', 4),
    ('project_type', 'İnovasyon Projesi', 5)
ON CONFLICT (field_name, option_value) DO NOTHING;

-- Insert combobox options for "iş fikri faaliyet alanı"
INSERT INTO combobox_options (field_name, option_value, order_index)
VALUES 
    ('business_idea', 'Yazılım', 1),
    ('business_idea', 'Donanım', 2),
    ('business_idea', 'Biyoteknoloji', 3),
    ('business_idea', 'Enerji', 4),
    ('business_idea', 'Sağlık', 5),
    ('business_idea', 'Eğitim', 6),
    ('business_idea', 'Fintech', 7),
    ('business_idea', 'E-Ticaret', 8),
    ('business_idea', 'Tarım', 9),
    ('business_idea', 'Diğer', 10)
ON CONFLICT (field_name, option_value) DO NOTHING;

-- Insert combobox options for "talep edilen alan"
INSERT INTO combobox_options (field_name, option_value, order_index)
VALUES 
    ('requested_area', 'Ortak Alanda Masa', 1),
    ('requested_area', 'Kapalı Ofis (1-2 Kişilik)', 2),
    ('requested_area', 'Kapalı Ofis (3-4 Kişilik)', 3),
    ('requested_area', 'Kapalı Ofis (5+ Kişilik)', 4)
ON CONFLICT (field_name, option_value) DO NOTHING;

