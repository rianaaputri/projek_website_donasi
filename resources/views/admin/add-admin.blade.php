@extends('layouts.admin')

@section('title', 'Add Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Add Admin</li>
@endsection

@section('styles')
<style>
    .form-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e3e6f0;
        padding: 15px 20px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        transform: translateY(-2px);
    }

    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .btn-primary {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        border-radius: 10px;
        padding: 15px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        border-radius: 10px;
        padding: 15px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .password-requirements {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border-radius: 10px;
        padding: 20px;
        margin-top: 15px;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 20px;
    }

    .password-requirements li {
        margin-bottom: 8px;
        color: #6c757d;
        font-family: 'Poppins', sans-serif;
    }

    .input-group-text {
        background: white;
        border: 2px solid #e3e6f0;
        border-left: none;
        border-radius: 0 10px 10px 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .input-group-text:hover {
        background: #f8f9fa;
    }

    .input-group .form-control {
        border-right: none;
        border-radius: 10px 0 0 10px;
    }

    .input-group .form-control:focus + .input-group-text {
        border-color: #667eea;
    }

    .page-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
    }

    .form-control.is-valid {
        border-color: #28a745;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.8-.77-.76-.77-.8.77zm1.54-4.96L8.17 6.1a1.3 1.3 0 01-.31.6l-3.88-3.88a1.3 1.3 0 01.86-.05z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title mb-2">
                <i class="fas fa-user-shield me-2"></i>Add New Admin
            </h2>
            <p class="text-muted mb-0">Create a new administrator account</p>
        </div>
        <div class="text-muted">{{ date('d F Y') }}</div>
    </div>
</div>

<!-- Add Admin Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-0 py-4">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Administrator Information</h5>
                <small class="text-muted">Fill in the details to create a new admin account</small>
            </div>
            <div class="card-body p-4">
                <form id="addAdminForm" method="POST" action="{{ route('admin.store-admin') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Full Name
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               placeholder="Enter full name" 
                               value="{{ old('name') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email Address
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               placeholder="Enter email address" 
                               value="{{ old('email') }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter secure password" 
                                   required>
                            <span class="input-group-text" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="password-requirements">
                            <h6><i class="fas fa-info-circle me-2"></i>Password Requirements:</h6>
                            <ul>
                                <li>At least 6 characters long</li>
                                <li>Contains at least one number</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>Confirm Password
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirm your password" 
                                   required>
                            <span class="input-group-text" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>

                    <input type="hidden" name="role" value="admin">
                    <input type="hidden" name="email_verified_at" value="{{ now() }}">

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-user-plus me-2"></i>Create Admin Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId === 'password' ? 'togglePasswordIcon' : 'togglePasswordConfirmIcon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Show alert function
    function showAlert(type, message) {
        const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        
        // Insert alert at the top of content
        const alertContainer = document.querySelector('.main-content');
        const pageHeader = document.querySelector('.page-header');
        const alertDiv = document.createElement('div');
        alertDiv.innerHTML = alertHTML;
        alertContainer.insertBefore(alertDiv, pageHeader.nextSibling);
    }

    // Form validation
    document.getElementById('addAdminForm').addEventListener('submit', function (e) {
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');

        // Reset validation classes
        [name, email, password, passwordConfirm].forEach(el => {
            el.classList.remove('is-invalid', 'is-valid');
        });

        let isValid = true;
        let errors = [];

        // Name validation
        if (name.value.trim().length < 2) {
            name.classList.add('is-invalid');
            errors.push('Name must be at least 2 characters long');
            isValid = false;
        } else {
            name.classList.add('is-valid');
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            email.classList.add('is-invalid');
            errors.push('Please enter a valid email address');
            isValid = false;
        } else {
            email.classList.add('is-valid');
        }

        // Password validation (minimum 6 characters with at least one number)
        const passwordRegex = /^(?=.*\d).{6,}$/;
        if (!passwordRegex.test(password.value)) {
            password.classList.add('is-invalid');
            errors.push('Password must be at least 6 characters with at least one number');
            isValid = false;
        } else {
            password.classList.add('is-valid');
        }

        // Password confirmation validation
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.classList.add('is-invalid');
            errors.push('Passwords do not match');
            isValid = false;
        } else if (password.value.length > 0) {
            passwordConfirm.classList.add('is-valid');
        }

        if (!isValid) {
            e.preventDefault();
            showAlert('danger', 'Please fix the following errors:<br>• ' + errors.join('<br>• '));
            return false;
        }

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
        submitBtn.disabled = true;
    });

    // Real-time password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword.length > 0) {
            if (password === confirmPassword) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });

    // Real-time password validation
    document.getElementById('password').addEventListener('input', function() {
        const passwordRegex = /^(?=.*\d).{6,}$/;
        const confirmPassword = document.getElementById('password_confirmation');
        
        if (this.value.length > 0) {
            if (passwordRegex.test(this.value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
        
        // Recheck password confirmation
        if (confirmPassword.value.length > 0) {
            if (this.value === confirmPassword.value) {
                confirmPassword.classList.remove('is-invalid');
                confirmPassword.classList.add('is-valid');
            } else {
                confirmPassword.classList.remove('is-valid');
                confirmPassword.classList.add('is-invalid');
            }
        }
    });
</script>
@endsection