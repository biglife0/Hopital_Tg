/**
 * HÔPITAL SAINT-ANTÉNOR - JAVASCRIPT
 * Université Anténor Firmin (UNAF)
 */

document.addEventListener('DOMContentLoaded', function() {

    // Form validation enhancement
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Date validation - prevent past dates
    const dateInputs = document.querySelectorAll('input[type="date"][min-today]');
    dateInputs.forEach(input => {
        const today = new Date().toISOString().split('T')[0];
        input.setAttribute('min', today);
    });

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) {
                value = value.substring(0, 8);
            }
            e.target.value = value;
        });
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Search functionality enhancement
    const searchInputs = document.querySelectorAll('[data-search-table]');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const tableId = this.getAttribute('data-search-table');
            const table = document.getElementById(tableId);
            if (table) {
                filterTable(table, this.value);
            }
        });
    });

    // Status confirmation dialogs
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Print functionality
    const printButtons = document.querySelectorAll('[data-print]');
    printButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            window.print();
        });
    });

    // Dynamic time slots based on specialty
    const specialtySelect = document.getElementById('specialty');
    const timeSelect = document.getElementById('appointment_time');

    if (specialtySelect && timeSelect) {
        specialtySelect.addEventListener('change', function() {
            updateTimeSlots(this.value, timeSelect);
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Filter table rows based on search term
function filterTable(table, searchTerm) {
    const tbody = table.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(term)) {
            row.style.display = '';
            row.classList.add('fade-in-up');
        } else {
            row.style.display = 'none';
        }
    });
}

// Update time slots based on specialty
function updateTimeSlots(specialtyId, timeSelect) {
    const timeSlots = {
        '1': ['08:00', '09:00', '10:00', '14:00', '15:00'], // Cardiologie
        '2': ['08:30', '09:30', '10:30', '14:30', '15:30'], // Dermatologie
        'default': ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00']
    };

    const slots = timeSlots[specialtyId] || timeSlots['default'];
    timeSelect.innerHTML = '<option value="">Choisir une heure...</option>';

    slots.forEach(slot => {
        const option = document.createElement('option');
        option.value = slot;
        option.textContent = slot;
        timeSelect.appendChild(option);
    });
}

// Confirm appointment status change
function confirmStatusChange(appointmentId, newStatus) {
    const statusLabels = {
        'confirme': 'confirmer',
        'annule': 'annuler',
        'complete': 'marquer comme complété'
    };

    return confirm(`Êtes-vous sûr de vouloir ${statusLabels[newStatus]} ce rendez-vous ?`);
}

// Preview message before sending reply
function previewReply(messageId) {
    const replyText = document.getElementById('reply_' + messageId).value;
    if (replyText.trim() === '') {
        alert('Veuillez écrire une réponse avant de prévisualiser.');
        return false;
    }

    const previewDiv = document.getElementById('preview_' + messageId);
    previewDiv.innerHTML = `
        <div class="message-reply">
            <div class="reply-header">
                <i class="fas fa-reply me-2"></i>Réponse de l'administration
            </div>
            <div class="reply-content">${escapeHtml(replyText)}</div>
            <small class="text-muted">${new Date().toLocaleString()}</small>
        </div>
    `;
    previewDiv.style.display = 'block';
    return false;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Toggle sidebar on mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    sidebar.classList.toggle('show');
}

// Export table to CSV
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    const rows = table.querySelectorAll('tr');
    let csv = [];

    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];

        cols.forEach(col => {
            let data = col.textContent.replace(/(
|
|)/gm, '').trim();
            rowData.push('"' + data + '"');
        });

        csv.push(rowData.join(';'));
    });

    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], {type: 'text/csv'});
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Print specific element
function printElement(elementId) {
    const element = document.getElementById(elementId);
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = element.innerHTML;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
