// Initialize form validation when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    setupFormValidation('form');
    setupUppercaseFields();
    setupNumericFields();
    setupPhoneValidation();

    // Auto-dismiss success alert after 3 seconds
    setTimeout(function () {
        let alert = document.getElementById('success-alert');
        if (alert) {
            let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 3000);
});

// Basic validation setup
function setupFormValidation(formSelector) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    // Validate on submit
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Check all required fields
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Special validation for phone number
        const phoneInput = form.querySelector('#phone_number');
        if (phoneInput && !validatePhoneNumber(phoneInput)) {
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showAlert('danger', 'Please fill in all required fields correctly.');
            // Scroll to first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Validate on blur (when leaving field)
    form.querySelectorAll('[required]').forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}

// Phone Number Validation
function setupPhoneValidation() {
    const phoneInput = document.getElementById('phone_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            // Remove all non-digit and non-plus characters
            this.value = this.value.replace(/[^0-9+]/g, '');
            validatePhoneNumber(this);
        });
        
        phoneInput.addEventListener('blur', function() {
            validatePhoneNumber(this);
        });
    }
}

function validatePhoneNumber(input) {
    const regex = /^(09|\+639)\d{9}$/;
    const isValid = regex.test(input.value);
    
    if (input.value && !isValid) {
        input.classList.add('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = 'Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)';
        }
    } else {
        input.classList.remove('is-invalid');
    }
    
    return isValid;
}

// Numeric fields setup
function setupNumericFields() {
    document.querySelectorAll('.numeric-only').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });
}

// Uppercase fields setup
function setupUppercaseFields() {
    document.querySelectorAll('.uppercase').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
}

// Alert System
function showAlert(type, message) {
    // Remove existing alerts first
    const existingAlerts = document.querySelectorAll('.global-alert');
    existingAlerts.forEach(alert => alert.remove());

    const alert = document.createElement('div');
    alert.className = `global-alert alert alert-${type} alert-dismissible fade show`;
    alert.style.position = 'fixed';
    alert.style.top = '70px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.style.maxWidth = '400px';
    alert.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}