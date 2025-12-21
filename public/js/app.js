/* public/js/app.js */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Puerta de Oro System Loaded');

    // Highlight active link in sidebar
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });

    // Auto-hide alerts after 3 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(el => el.style.display = 'none');
        }, 3000);
    }
});

// Utility to format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(amount);
}

// Utility for confirmation dialogs
function confirmAction(message) {
    return confirm(message);
}
