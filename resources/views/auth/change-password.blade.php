@extends('layouts.guest')

@section('styles')
<!-- Font Awesome -->

<style>
    .password-toggle-container {
        position: relative;
    }

    .password-input {
        padding-right: 40px !important;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        cursor: pointer;
        color: #6c757d;
        z-index: 2;
    }

    .password-requirements {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 5px;
    }

    .requirement {
        display: flex;
        align-items: center;
        margin-bottom: 2px;
    }

    .requirement i {
        margin-right: 5px;
        font-size: 0.75rem;
    }

    .valid {
        color: #28a745;
    }

    .invalid {
        color: #dc3545;
    }
    
    .alert {
        position: relative;
        padding: 1rem 1rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }
    
    .alert-success {
        color: #0f5132;
        background-color: #d1e7dd;
        border-color: #badbcc;
    }
    
    .alert-danger {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }
    
    .btn-close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 1.25rem 1rem;
    }
    
    /* Add this to ensure form controls display properly */
    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
</style>
@endsection

@section('content')
<main class="min-h-screen flex items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-sm sm:max-w-md bg-white shadow-xl rounded-2xl min-h-[600px] p-6 sm:p-8 flex flex-col justify-between space-y-6">

        <!-- Alerts -->
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Logo -->
        <div class="flex justify-center mt-4 mb-2">
            <img class="h-[4.5rem] sm:h-[5.25rem] md:h-[6rem] w-auto" src="{{ asset('images/logo2.png') }}" alt="logo">
        </div>

        <!-- Form -->
        <div class="flex-1 flex flex-col justify-center">
            <form method="POST" action="{{ route('password.update') }}" id="passwordChangeForm" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div class="password-toggle-container">
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" id="current_password" name="current_password"
                        class="password-input form-control @error('current_password') is-invalid @enderror" required>
                    <button type="button" class="password-toggle" data-target="current_password">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                    @error('current_password')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <div class="password-toggle-container">
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" id="new_password" name="new_password"
                            class="password-input form-control @error('new_password') is-invalid @enderror" required>
                        <button type="button" class="password-toggle" data-target="new_password">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                        @error('new_password')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="password-requirements mt-2">
                        <div class="requirement"><i id="reqLength" class="fas fa-circle invalid"></i><span>At least 8 characters</span></div>
                        <div class="requirement"><i id="reqUpper" class="fas fa-circle invalid"></i><span>At least 1 uppercase letter</span></div>
                        <div class="requirement"><i id="reqLower" class="fas fa-circle invalid"></i><span>At least 1 lowercase letter</span></div>
                        <div class="requirement"><i id="reqNumber" class="fas fa-circle invalid"></i><span>At least 1 number</span></div>
                        <div class="requirement"><i id="reqSpecial" class="fas fa-circle invalid"></i><span>At least 1 special character</span></div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="password-toggle-container">
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                        class="password-input form-control @error('new_password_confirmation') is-invalid @enderror" required>
                    <button type="button" class="password-toggle" data-target="new_password_confirmation">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                    @error('new_password_confirmation')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                    <div id="passwordMatch" class="text-sm mt-1"></div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between mt-8">
                    <a href="{{ route('profile.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back to Profile
                    </a>
                    <button type="submit" id="submitButton" disabled
                        class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality
        function togglePassword(id, button) {
            const input = document.getElementById(id);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }

        // Add event listeners to all toggle buttons
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                togglePassword(targetId, this);
            });
        });

        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[\W_]/.test(password)
            };

            document.getElementById('reqLength').className = requirements.length ? 'fas fa-check-circle valid' : 'fas fa-times-circle invalid';
            document.getElementById('reqUpper').className = requirements.upper ? 'fas fa-check-circle valid' : 'fas fa-times-circle invalid';
            document.getElementById('reqLower').className = requirements.lower ? 'fas fa-check-circle valid' : 'fas fa-times-circle invalid';
            document.getElementById('reqNumber').className = requirements.number ? 'fas fa-check-circle valid' : 'fas fa-times-circle invalid';
            document.getElementById('reqSpecial').className = requirements.special ? 'fas fa-check-circle valid' : 'fas fa-times-circle invalid';

            return Object.values(requirements).every(Boolean);
        }

        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            const matchDiv = document.getElementById('passwordMatch');

            if (!newPassword || !confirmPassword) {
                matchDiv.textContent = '';
                return false;
            }

            if (newPassword === confirmPassword) {
                matchDiv.textContent = 'Passwords match';
                matchDiv.style.color = 'green';
                return true;
            } else {
                matchDiv.textContent = 'Passwords do not match';
                matchDiv.style.color = 'red';
                return false;
            }
        }

        function updateSubmitButton() {
            const isValid = validatePassword(document.getElementById('new_password').value);
            const isMatch = checkPasswordMatch();
            document.getElementById('submitButton').disabled = !(isValid && isMatch);
        }

        // Add event listeners to new password and confirmation fields
        document.getElementById('new_password').addEventListener('input', updateSubmitButton);
        document.getElementById('new_password_confirmation').addEventListener('input', updateSubmitButton);
    });
</script>

@endsection