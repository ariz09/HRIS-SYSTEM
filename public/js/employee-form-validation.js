/**
 * Employee Form Validation Module
 * Handles all form validation logic
 */
const EmployeeFormValidation = (() => {
    // Validation rules configuration
    const rules = {
        first_name: { required: true, maxLength: 50 },
        last_name: { required: true, maxLength: 50 },
        email: { required: true, type: 'email', maxLength: 100 },
        phone_number: { required: true, type: 'phone' },
        birthday: { required: true, type: 'date', maxDate: 'today' },
        hiring_date: { required: true, type: 'date', minDateField: 'birthday' },
        basic_pay: { required: true, type: 'number', min: 0 },
        atm_account_number: { required: true, type: 'numeric', minLength: 10, maxLength: 20 },
        bank_name: { required: true, maxLength: 50 }
    };

    // Initialize validation
    const init = (formId) => {
        const form = document.getElementById(formId);
        if (!form) return;

        // Set up event listeners
        form.addEventListener('submit', handleSubmit);
        form.querySelectorAll('[required]').forEach(field => {
            field.addEventListener('blur', () => validateField(field));
        });

        // Special validation for phone number
        const phoneInput = form.querySelector('#phone_number');
        if (phoneInput) {
            phoneInput.addEventListener('input', formatPhoneNumber);
        }
    };

    // Handle form submission
    const handleSubmit = (e) => {
        e.preventDefault();
        const form = e.target;
        let isValid = true;

        // Validate all required fields
        form.querySelectorAll('[required]').forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        if (isValid) {
            // Show loading state
            const submitBtn = form.querySelector('#submitBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
            }

            // Submit the form
            form.submit();
        } else {
            showAlert('danger', 'Please correct the errors in the form.');
            scrollToFirstError();
        }
    };

    // Validate individual field
    const validateField = (field) => {
        const fieldId = field.id;
        const value = field.value.trim();
        const rule = rules[fieldId];

        // Clear previous errors
        field.classList.remove('is-invalid');

        // Skip validation if no rule exists
        if (!rule) return true;

        // Required validation
        if (rule.required && !value) {
            setFieldError(field, 'This field is required.');
            return false;
        }

        // Type-specific validation
        switch (rule.type) {
            case 'email':
                if (!validateEmail(value)) {
                    setFieldError(field, 'Please enter a valid email address.');
                    return false;
                }
                break;
                
            case 'phone':
                if (!validatePhoneNumber(value)) {
                    setFieldError(field, 'Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)');
                    return false;
                }
                break;
                
            case 'date':
                if (!validateDate(field, rule)) {
                    return false;
                }
                break;
                
            case 'number':
                if (isNaN(value) || (rule.min !== undefined && parseFloat(value) < rule.min)) {
                    setFieldError(field, `Please enter a valid number${rule.min !== undefined ? ` greater than or equal to ${rule.min}` : ''}.`);
                    return false;
                }
                break;
                
            case 'numeric':
                if (!/^\d+$/.test(value)) {
                    setFieldError(field, 'This field should contain only numbers.');
                    return false;
                }
                break;
        }

        // Length validation
        if (rule.maxLength && value.length > rule.maxLength) {
            setFieldError(field, `This field should not exceed ${rule.maxLength} characters.`);
            return false;
        }

        if (rule.minLength && value.length < rule.minLength) {
            setFieldError(field, `This field should be at least ${rule.minLength} characters.`);
            return false;
        }

        return true;
    };

    // Helper functions
    const setFieldError = (field, message) => {
        field.classList.add('is-invalid');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }
    };

    const validateEmail = (email) => {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    };

    const validatePhoneNumber = (phone) => {
        return /^(09|\+639)\d{9}$/.test(phone);
    };

    const validateDate = (field, rule) => {
        const date = new Date(field.value);
        if (isNaN(date.getTime())) {
            setFieldError(field, 'Please enter a valid date.');
            return false;
        }

        if (rule.maxDate === 'today' && date > new Date()) {
            setFieldError(field, 'Date cannot be in the future.');
            return false;
        }

        if (rule.minDateField) {
            const minDateField = document.getElementById(rule.minDateField);
            if (minDateField && minDateField.value) {
                const minDate = new Date(minDateField.value);
                if (date < minDate) {
                    setFieldError(field, `Date cannot be before ${minDateField.labels[0].textContent}.`);
                    return false;
                }
            }
        }

        return true;
    };

    const formatPhoneNumber = (e) => {
        const input = e.target;
        // Remove all non-digit and non-plus characters
        input.value = input.value.replace(/[^0-9+]/g, '');
    };

    const scrollToFirstError = () => {
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    };

    const showAlert = (type, message) => {
        // Remove existing alerts first
        const existingAlerts = document.querySelectorAll('.global-alert');
        existingAlerts.forEach(alert => alert.remove());

        const alert = document.createElement('div');
        alert.className = `global-alert alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 5000);
    };

    return {
        init
    };
})();

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    EmployeeFormValidation.init('employeeForm');
});