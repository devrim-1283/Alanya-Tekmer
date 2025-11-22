// Main JavaScript

document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle - Event Listener approach (Backup for onclick)
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const navMenu = document.getElementById('navMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');

    function handleMenuToggle(e) {
        if (e) e.preventDefault();
        toggleMobileMenu();
    }

    if (mobileToggle) {
        // Remove old listeners to be safe (though we can't really do that easily without reference)
        // Just add the new one. If onclick works, this might double toggle if not careful.
        // But we will make toggleMobileMenu smart.
        mobileToggle.onclick = handleMenuToggle; // Override inline onclick to be sure
    }

    if (mobileOverlay) {
        mobileOverlay.onclick = handleMenuToggle;
    }

    // Close mobile menu on link click
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                // Don't close if it's a dropdown toggle
                if (!this.classList.contains('dropdown-toggle')) {
                    closeMobileMenu();
                }
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                closeMobileMenu();
            }
        });
    });
});

// Global Toggle Function
window.toggleMobileMenu = function () {
    const navMenu = document.getElementById('navMenu');
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const body = document.body;

    if (navMenu && mobileToggle) {
        navMenu.classList.toggle('active');
        mobileToggle.classList.toggle('active');

        if (mobileOverlay) {
            mobileOverlay.classList.toggle('active');
        }

        // Prevent background scrolling when menu is open
        if (navMenu.classList.contains('active')) {
            body.style.overflow = 'hidden';
        } else {
            body.style.overflow = '';
        }
    }
};

// Global Close Function
window.closeMobileMenu = function () {
    const navMenu = document.getElementById('navMenu');
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const body = document.body;

    if (navMenu && navMenu.classList.contains('active')) {
        navMenu.classList.remove('active');
        if (mobileToggle) mobileToggle.classList.remove('active');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        body.style.overflow = '';
    }
};

// Cookie consent
function acceptCookies() {
    localStorage.setItem('cookieConsent', 'accepted');
    document.getElementById('cookieConsent').style.display = 'none';
}

if (!localStorage.getItem('cookieConsent')) {
    const cookieBanner = document.getElementById('cookieConsent');
    if (cookieBanner) {
        cookieBanner.style.display = 'block';
    }
}
