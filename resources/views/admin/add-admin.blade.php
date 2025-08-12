<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Admin - Kindify.id</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
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
      box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-header {
      padding: 30px 20px;
      text-align: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h3 {
      color: white;
      margin: 0;
      font-weight: 600;
    }

    .sidebar-header p {
      color: rgba(255, 255, 255, 0.8);
      margin: 5px 0 0 0;
      font-size: 14px;
    }

    .sidebar-menu {
      padding: 20px 0;
    }

    .sidebar-menu .menu-item {
      display: block;
      padding: 15px 25px;
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      transition: all 0.3s ease;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
    }

    .sidebar-menu .menu-item:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      padding-left: 35px;
    }

    .sidebar-menu .menu-item.active {
      background: rgba(255, 255, 255, 0.2);
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
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: white;
      border-radius: 8px;
      transition: all 0.3s ease;
      display: block;
      text-align: center;
      text-decoration: none;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
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
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
      @auth
        <small class="text-white-50">Welcome, {{ auth()->user()->name }}</small>
      @else
        <small class="text-white-50">Welcome, Admin</small>
      @endauth
    </div>

    <nav class="sidebar-menu">
      <a href="{{ route('admin.dashboard') }}" class="menu-item">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
      <a href="{{ route('admin.campaigns.index') }}" class="menu-item">
        <i class="fas fa-bullhorn"></i> Campaign Management
      </a>
      <!--<a href="#" class="menu-item">
        <i class="fas fa-users"></i> Registered Users
      </a>-->
      <a href="{{ route('admin.donations.index') }}" class="menu-item">
        <i class="fas fa-hand-holding-heart"></i> Donations
      </a>
      <a href="{{ route('admin.add-admin') }}" class="menu-item active">
        <i class="fas fa-user-shield"></i> Add Admin
      </a>
      <a href="#" class="menu-item">
        <i class="fas fa-chart-bar"></i> Reports
      </a>
      <a href="#" class="menu-item">
        <i class="fas fa-cog"></i> Settings
      </a>
    </nav>

    <div class="sidebar-logout">
      <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Yakin ingin logout?')">
        @csrf
        <button type="submit" class="logout-btn">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
      </form>
    </div>
  </div>

  <!-- Mobile Sidebar Toggle -->
  <div class="d-md-none position-fixed" style="top: 20px; left: 20px; z-index: 1001;">
    <button class="btn btn-primary" type="button" id="sidebarToggle">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2><i class="fas fa-user-shield me-2"></i>Add New Admin</h2>
        <p class="text-muted mb-0">Create a new administrator account</p>
      </div>
      <div class="text-muted">{{ date('d F Y') }}</div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer">
      <!-- Success Alert from Laravel -->
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- Error Alert from Laravel -->
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          <strong>Please fix the following errors:</strong>
          <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
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
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="password-requirements">
                  <h6><i class="fas fa-info-circle me-2"></i>Password Requirements:</h6>
                  <ul>
                    <li>At least 8 characters long</li>
                    <li>Contains at least one uppercase letter</li>
                    <li>Contains at least one lowercase letter</li>
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
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    // Mobile sidebar toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('active');
    });

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
      document.getElementById('alertContainer').innerHTML = alertHTML;
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

      // Password validation (Laravel default: min 8 characters)
      const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
      if (!passwordRegex.test(password.value)) {
        password.classList.add('is-invalid');
        errors.push('Password must be at least 8 characters with uppercase, lowercase, and number');
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

    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>
</body>
</html>