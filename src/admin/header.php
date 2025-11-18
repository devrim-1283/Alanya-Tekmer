<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Security::escape($pageTitle ?? 'Admin Panel'); ?> - Alanya TEKMER</title>
    <link rel="stylesheet" href="<?php echo asset('css/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="<?php echo url('logo.png'); ?>" alt="Alanya TEKMER">
                <h3>Admin Panel</h3>
            </div>
            
            <nav class="sidebar-menu">
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/dashboard'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/team'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'team' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Ekip Yönetimi
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/events'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'events' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar"></i> Etkinlik & Duyuru
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/companies'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'companies' ? 'active' : ''; ?>">
                    <i class="fas fa-building"></i> Firma Yönetimi
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/faq'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'faq' ? 'active' : ''; ?>">
                    <i class="fas fa-question-circle"></i> SSS Yönetimi
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/gallery'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'gallery' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> Galeri Yönetimi
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/applications'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'applications' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i> Başvurular
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/analytics'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'analytics' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i> Analitikler
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/settings'); ?>" 
                   class="menu-item <?php echo ($currentAdminPage ?? '') === 'settings' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i> Ayarlar
                </a>
                
                <a href="<?php echo url(getenv('ADMIN_PATH') . '/logout'); ?>" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <p class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <?php echo Security::escape($_SESSION['admin_username'] ?? 'Admin'); ?>
                </p>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="admin-content">
            <header class="admin-header">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1><?php echo Security::escape($pageTitle ?? 'Admin Panel'); ?></h1>
                <div class="header-actions">
                    <a href="<?php echo url(); ?>" class="btn btn-secondary btn-sm" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Siteyi Görüntüle
                    </a>
                </div>
            </header>
            
            <main class="admin-main">

