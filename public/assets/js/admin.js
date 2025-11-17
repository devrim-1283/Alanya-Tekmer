// Admin Panel JavaScript

function toggleSidebar() {
    document.querySelector('.admin-sidebar').classList.toggle('active');
}

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

