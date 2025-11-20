<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Security::escape($pageTitle ?? 'Admin Panel'); ?> - Alanya TEKMER</title>
    <link rel="stylesheet" href="<?php echo asset('css/admin.css'); ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="modern-admin-body">
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <div class="admin-container">
        <!-- Modern Sidebar -->
        <aside class="modern-sidebar" id="modernSidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <div class="brand-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">TEKMER</span>
                        <span class="brand-subtitle">Admin Panel</span>
                    </div>
                </div>
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Search Box -->
            <div class="sidebar-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Ara..." id="sidebarSearch">
            </div>
            
            <!-- Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">Ana Menü</span>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/dashboard'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                        <div class="nav-badge">
                            <span class="badge badge-primary">●</span>
                        </div>
                    </a>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/applications'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'applications' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="nav-text">Başvurular</span>
                        <?php
                        try {
                            $pendingCount = $db->fetchOne('SELECT COUNT(*) as count FROM applications WHERE status = ?', ['pending'])['count'];
                            if ($pendingCount > 0):
                        ?>
                        <div class="nav-badge">
                            <span class="badge badge-warning"><?php echo $pendingCount; ?></span>
                        </div>
                        <?php endif; } catch(Exception $e) {} ?>
                    </a>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/analytics'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'analytics' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="nav-text">Analitikler</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">İçerik Yönetimi</span>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/events'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'events' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="nav-text">Etkinlikler</span>
                    </a>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/companies'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'companies' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <span class="nav-text">Firmalar</span>
                    </a>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/team'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'team' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">Ekip</span>
                    </a>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/gallery'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'gallery' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <span class="nav-text">Galeri</span>
                    </a>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/faq'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'faq' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <span class="nav-text">SSS</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Sistem</span>
                    
                    <a href="<?php echo url(getenv('ADMIN_PATH') . '/settings'); ?>" 
                       class="nav-item <?php echo ($currentAdminPage ?? '') === 'settings' ? 'active' : ''; ?>">
                        <div class="nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span class="nav-text">Ayarlar</span>
                    </a>
                </div>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?php echo Security::escape($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                        <span class="user-role">Yönetici</span>
                    </div>
                    <div class="user-actions">
                        <button class="user-action-btn" onclick="toggleUserMenu()">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="user-menu" id="userMenu">
                            <a href="<?php echo url(getenv('ADMIN_PATH') . '/settings'); ?>">
                                <i class="fas fa-user-cog"></i> Profil
                            </a>
                            <a href="<?php echo url(getenv('ADMIN_PATH') . '/logout'); ?>">
                                <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <div class="admin-main-wrapper">
            <!-- Top Header -->
            <header class="modern-header">
                <div class="header-left">
                    <button class="mobile-sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="page-title-wrapper">
                        <h1 class="page-title"><?php echo Security::escape($pageTitle ?? 'Dashboard'); ?></h1>
                        <nav class="breadcrumb">
                            <span><i class="fas fa-home"></i></span>
                            <span class="breadcrumb-separator">/</span>
                            <span><?php echo Security::escape($pageTitle ?? 'Dashboard'); ?></span>
                        </nav>
                    </div>
                </div>
                
                <div class="header-right">
                    <!-- Quick Actions -->
                    <div class="header-actions">
                        <a href="<?php echo url(); ?>" class="header-btn" target="_blank" title="Siteyi Görüntüle">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        
                        <button class="header-btn" title="Bildirimler" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                        
                        <button class="header-btn" title="Tam Ekran" onclick="toggleFullscreen()">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                    
                    <!-- Notifications Panel -->
                    <div class="notifications-panel" id="notificationsPanel">
                        <div class="notifications-header">
                            <h3>Bildirimler</h3>
                            <button onclick="toggleNotifications()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="notifications-body">
                            <div class="notification-item unread">
                                <div class="notification-icon success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <p><strong>Yeni Başvuru</strong></p>
                                    <span>Ahmet Yılmaz başvuru yaptı</span>
                                    <small>5 dakika önce</small>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon info">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <p><strong>Sistem Güncellemesi</strong></p>
                                    <span>Yeni özellikler eklendi</span>
                                    <small>2 saat önce</small>
                                </div>
                            </div>
                        </div>
                        <div class="notifications-footer">
                            <a href="#">Tümünü Gör</a>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="modern-main-content">

