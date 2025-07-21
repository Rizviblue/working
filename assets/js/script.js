// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Demo login functionality
function loginAsDemo(role) {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    const credentials = {
        admin: { email: 'admin@courier.com', password: 'password' },
        agent: { email: 'agent@courier.com', password: 'password' },
        user: { email: 'user@courier.com', password: 'password' }
    };
    
    if (credentials[role]) {
        emailInput.value = credentials[role].email;
        passwordInput.value = credentials[role].password;
        
        // Auto-submit form
        setTimeout(() => {
            document.getElementById('loginForm').submit();
        }, 500);
    }
}

// Sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.stat-card, .recent-couriers, .quick-actions');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Confirm deletions
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Format tracking number display
function formatTrackingNumber(trackingNumber) {
    return trackingNumber.replace(/(.{3})/g, '$1 ').trim();
}

// Real-time search functionality
function initializeSearch() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.searchable-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }
}

// Initialize search on page load
document.addEventListener('DOMContentLoaded', initializeSearch);