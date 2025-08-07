<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin - Kindify.id</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h3 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-header p {
            color: rgba(255,255,255,0.8);
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu .menu-item {
            display: block;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        
        .sidebar-menu .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 35px;
        }
        
        .sidebar-menu .menu-item.active {
            background: rgba(255,255,255,0.2);
            border-right: 4px solid white;
            color: white;
        }
        
        .sidebar-menu .menu-item i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }
        
        .sidebar-logout {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            padding: 0 20px;
        }
        
        .logout-btn {
            width: 100%;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: block;
            text-align: center;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }
        
        .form-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e3e6f0;
            padding: 15px 20px;
            transition: all 0.3s ease;
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
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
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
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-heart me-2"></i>Kindify.id</h3>
            <p>Admin Panel</p>
            <small class="text-white-50">Welcome, Admin</small>
        </div>
        
        <nav class="sidebar-menu">
            <a href="#" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-bullhorn"></i>
                Campaign Management
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-users"></i>
                Registered Users
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-hand-holding-heart"></i>
                Donations
            </a>
            <a href="#" class="menu-item active">
                <i class="fas fa-user-shield"></i>
                Add Admin
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                Reports
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                Settings
            </a>
        </nav>
        
        <div class="sidebar-logout">
            <a href="#" class="logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="fas fa-user-shield me-2"></i>Add New Admin</h2>
                <p class="text-muted mb-0">Create a new administrator account</p>
            </div>
            <div class="text-muted">{{ new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</div>
        </div>

        <!-- Success/Error Messages -->
        <div id="alertContainer"></div>

        <!-- Add Admin Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card form-card">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Administrator Information</h5>
                        <small class="text-muted">Fill in the details to create a new admin account</small>
                    </div>
                    <div class="card-body p-4">
                        <form id="addAdminForm" method="POST" action="#">
                            
                            <!-- Name Field -->
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name" 
                                       placeholder="Enter full name"
                                       required>
                                <div class="invalid-feedback">
                                    Please provide a valid name.
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Enter email address"
                                       required>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter secure password"
                                           required>
                                    <span class="input-group-text" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback">
                                    Password must be at least 8 characters long.
                                </div>
                                
                                <div class="password-requirements">
                                    <h6><i class="fas fa-info-circle me-2"></i>Password Requirements:</h6>
                                    <ul>
                                        <li>At least 8 characters long</li>
                                        <li>Contains at least one uppercase letter</li>
                                        <li>Contains at least one lowercase letter</li>
                                        <li>Contains at least one number</li>
                                        <li>Contains at least one special character (!@#$%^&*)</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
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
                                <div class="invalid-feedback">
                                    Passwords do not match.
                                </div>
                            </div>

                            <!-- CSRF Token (Laravel) -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="role" value="admin">

                            <!-- Submit Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="button" class="btn btn-secondary me-md-2" onclick="window.history.back()">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Create Admin Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="card form-card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-shield-alt fa-3x text-primary mb-2"></i>
                                <h6>Secure Access</h6>
                                <small class="text-muted">Admin accounts have full system access</small>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-envelope-open fa-3x text-success mb-2"></i>
                                <h6>Email Verification</h6>
                                <small class="text-muted">Verification email will be sent automatically</small>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-key fa-3x text-warning mb-2"></i>
                                <h6>Strong Password</h6>
                                <small class="text-muted">Ensure password meets security requirements</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId === 'password' ? 'togglePasswordIcon' : 'togglePasswordConfirmIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form validation
        document.getElementById('addAdminForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            // Reset validation states
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
            
            let isValid = true;
            
            // Name validation
            if (name.length < 2) {
                document.getElementById('name').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('name').classList.add('is-valid');
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('email').classList.add('is-valid');
            }
            
            // Password validation
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                document.getElementById('password').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('password').classList.add('is-valid');
            }
            
            // Password confirmation validation
            if (password !== passwordConfirm) {
                document.getElementById('password_confirmation').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('password_confirmation').classList.add('is-valid');
            }
            
            if (isValid) {
                showAlert('success', 'Admin account created successfully!');
                // Here you would normally submit the form to your Laravel backend
                // this.submit();
            } else {
                showAlert('danger', 'Please fix the errors above and try again.');
            }
        });
        
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            alertContainer.innerHTML = alertHTML;
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
        
        // Real-time validation feedback
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            
            if (passwordRegex.test(password)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });
        
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password === confirmPassword && password.length > 0) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });
    </script>
</body>
</html>