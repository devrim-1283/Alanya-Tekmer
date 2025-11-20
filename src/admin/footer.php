            </main>
            
            <!-- Footer -->
            <footer class="modern-footer">
                <div class="footer-content">
                    <div class="footer-left">
                        <span>© <?php echo date('Y'); ?> Alanya TEKMER. Tüm hakları saklıdır.</span>
                    </div>
                    <div class="footer-right">
                        <span>Versiyon 2.0.0</span>
                        <span class="separator">•</span>
                        <a href="<?php echo url(); ?>" target="_blank">Siteye Git</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="<?php echo asset('js/admin.js'); ?>"></script>
    <script>
    // Toggle Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('modernSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const body = document.body;
        
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        body.classList.toggle('sidebar-open');
    }
    
    // Toggle User Menu
    function toggleUserMenu() {
        const userMenu = document.getElementById('userMenu');
        userMenu.classList.toggle('active');
    }
    
    // Close user menu when clicking outside
    document.addEventListener('click', function(e) {
        const userMenu = document.getElementById('userMenu');
        const userActionBtn = document.querySelector('.user-action-btn');
        
        if (userMenu && userActionBtn) {
            if (!userMenu.contains(e.target) && !userActionBtn.contains(e.target)) {
                userMenu.classList.remove('active');
            }
        }
    });
    
    // Toggle Notifications
    function toggleNotifications() {
        const panel = document.getElementById('notificationsPanel');
        panel.classList.toggle('active');
    }
    
    // Close notifications when clicking outside
    document.addEventListener('click', function(e) {
        const panel = document.getElementById('notificationsPanel');
        const notifBtn = document.querySelector('.header-btn[onclick="toggleNotifications()"]');
        
        if (panel && notifBtn) {
            if (!panel.contains(e.target) && !notifBtn.contains(e.target)) {
                panel.classList.remove('active');
            }
        }
    });
    
    // Toggle Fullscreen
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
    
    // Sidebar Search
    const sidebarSearch = document.getElementById('sidebarSearch');
    if (sidebarSearch) {
        sidebarSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Confirm Delete
    function confirmDelete(message = 'Bu kaydı silmek istediğinizden emin misiniz?') {
        return confirm(message);
    }
    
    // Auto-hide notifications
    setTimeout(function() {
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        unreadNotifications.forEach(notification => {
            notification.classList.remove('unread');
        });
    }, 5000);
    
    // Smooth scroll for main content
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    </script>
</body>
</html>

