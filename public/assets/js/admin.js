// Admin Panel JavaScript

// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.querySelector('.modern-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar) {
        sidebar.classList.toggle('mobile-open');
    }
    if (overlay) {
        overlay.classList.toggle('active');
    }
}

// Desktop sidebar collapse/expand
function toggleSidebarCollapse() {
    const sidebar = document.querySelector('.modern-sidebar');
    
    if (sidebar) {
        sidebar.classList.toggle('collapsed');
        
        // Save state to localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }
}

// Load sidebar state on page load
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.modern-sidebar');
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    if (sidebar && isCollapsed) {
        sidebar.classList.add('collapsed');
    }
});

function confirmDelete(message = 'Bu kaydı silmek istediğinizden emin misiniz?') {
    return confirm(message);
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

