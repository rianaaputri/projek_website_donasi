<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font: Inter for modern look -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary-blue: #E3F2FD;
      --secondary-blue: #BBDEFB;
      --accent-blue: #64B5F6;
      --dark-blue: #2196F3;
      --text-primary: #1E3A8A;
      --text-secondary: #64748B;
      --border-color: #E1E7EF;
      --shadow-light: rgba(33, 150, 243, 0.08);
      --shadow-medium: rgba(33, 150, 243, 0.15);
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #E3F2FD 0%, #F8FAFC 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      position: relative;
      overflow-x: hidden;
    }

    /* Subtle background pattern */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: radial-gradient(circle at 25% 25%, rgba(33, 150, 243, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 75% 75%, rgba(33, 150, 243, 0.03) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }

    .login-wrapper {
      width: 100%;
      max-width: 420px;
      position: relative;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border-color);
      border-radius: 24px;
      padding: 3rem 2.5rem;
      box-shadow: 0 8px 32px var(--shadow-light),
                  0 2px 16px rgba(0, 0, 0, 0.02);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--accent-blue), var(--dark-blue));
      border-radius: 24px 24px 0 0;
    }

    .login-container:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 48px var(--shadow-medium),
                  0 4px 24px rgba(0, 0, 0, 0.04);
    }

    .login-header {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .login-header h3 {
      color: var(--text-primary);
      font-weight: 600;
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
      transition: all 0.3s ease;
    }

    .login-header p {
      color: var(--text-secondary);
      font-size: 0.95rem;
      margin: 0;
      font-weight: 400;
      transition: all 0.3s ease;
    }

    /* Enhanced Form Controls */
    .form-floating {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-floating > .form-control {
      border: 2px solid var(--border-color);
      border-radius: 16px;
      padding: 1rem 1.25rem;
      font-size: 0.95rem;
      background-color: rgba(248, 250, 252, 0.5);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      height: auto;
      min-height: 3.5rem;
    }

    .form-floating > .form-control:focus {
      border-color: var(--accent-blue);
      box-shadow: 0 0 0 4px rgba(100, 181, 246, 0.1);
      background-color: white;
      outline: none;
    }

    .form-floating > label {
      color: var(--text-secondary);
      font-weight: 500;
      font-size: 0.9rem;
      padding: 1rem 1.25rem;
      transition: all 0.2s ease;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
      color: var(--dark-blue);
      font-weight: 600;
    }

    /* Password field with icon */
    .password-wrapper {
      position: relative;
    }

    .password-wrapper .form-control {
      padding-right: 3.5rem;
    }

    .toggle-password {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      z-index: 10;
      color: var(--text-secondary);
      font-size: 1.25rem;
      transition: color 0.2s ease;
      padding: 0.5rem;
      border-radius: 8px;
    }

    .toggle-password:hover {
      color: var(--dark-blue);
      background-color: rgba(33, 150, 243, 0.1);
    }

    /* Custom Button */
    .btn-login {
      background: linear-gradient(135deg, var(--accent-blue) 0%, var(--dark-blue) 100%);
      border: none;
      border-radius: 16px;
      padding: 1rem 1.5rem;
      font-weight: 600;
      font-size: 1rem;
      color: white;
      width: 100%;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 16px rgba(33, 150, 243, 0.3);
      margin-bottom: 1.5rem;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(33, 150, 243, 0.4);
      background: linear-gradient(135deg, var(--dark-blue) 0%, #1976D2 100%);
    }

    .btn-login:active {
      transform: translateY(0);
      box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
    }

    /* Forgot Password Button */
    .btn-forgot-password {
      background: transparent;
      border: 2px solid var(--accent-blue);
      border-radius: 16px;
      padding: 1rem 1.5rem;
      font-weight: 600;
      font-size: 1rem;
      color: var(--dark-blue);
      width: 100%;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      margin-bottom: 1.5rem;
      position: relative;
      overflow: hidden;
    }

    .btn-forgot-password::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--accent-blue) 0%, var(--dark-blue) 100%);
      transition: left 0.3s ease;
      z-index: -1;
    }

    .btn-forgot-password:hover {
      color: white;
      border-color: var(--dark-blue);
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(33, 150, 243, 0.3);
    }

    .btn-forgot-password:hover::before {
      left: 0;
    }

    .btn-forgot-password:active {
      transform: translateY(0);
    }

    /* Custom Alert Styles */
    .custom-alert {
      position: fixed;
      top: 20px;
      right: 20px;
      background: white;
      border: 2px solid var(--accent-blue);
      border-radius: 12px;
      padding: 1rem 1.5rem;
      box-shadow: 0 8px 32px var(--shadow-medium);
      z-index: 1050;
      max-width: 350px;
      opacity: 0;
      transform: translateX(100%);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-alert.show {
      opacity: 1;
      transform: translateX(0);
    }

    .custom-alert.success {
      border-color: #10B981;
      background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF4 100%);
    }

    .custom-alert.warning {
      border-color: #F59E0B;
      background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
    }

    .custom-alert.info {
      border-color: var(--accent-blue);
      background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    }

    .alert {
      border: none;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
    }

    .alert-danger {
      background-color: #FEF2F2;
      color: #DC2626;
      border-left: 4px solid #EF4444;
    }

    /* Error Messages */
    .error-message {
      color: #DC2626;
      font-size: 0.85rem;
      margin-top: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* Forgot Password Link */
    .forgot-password-link {
      text-align: center;
      margin-bottom: 1rem;
    }

    .forgot-password-link a {
      color: var(--dark-blue);
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.2s ease;
      position: relative;
    }

    .forgot-password-link a::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 50%;
      background: var(--accent-blue);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .forgot-password-link a:hover {
      color: var(--accent-blue);
    }

    .forgot-password-link a:hover::after {
      width: 100%;
    }

    /* Register Link */
    .register-link {
      text-align: center;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--border-color);
    }

    .register-link p {
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin: 0;
    }

    .register-link a {
      color: var(--dark-blue);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s ease;
    }

    .register-link a:hover {
      color: var(--accent-blue);
      text-decoration: underline;
    }

    /* Form Transition Effects */
    .form-section {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-section.hidden {
      opacity: 0;
      transform: translateY(20px);
      pointer-events: none;
      height: 0;
      overflow: hidden;
      margin: 0;
      padding: 0;
    }

    /* Back Button */
    .btn-back {
      background: transparent;
      border: 2px solid var(--text-secondary);
      border-radius: 16px;
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      font-size: 0.9rem;
      color: var(--text-secondary);
      width: 100%;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      margin-top: 1rem;
    }

    .btn-back:hover {
      border-color: var(--dark-blue);
      color: var(--dark-blue);
      background-color: rgba(33, 150, 243, 0.05);
    }

    /* Responsive Design */
    @media (max-width: 480px) {
      .login-container {
        padding: 2rem 1.5rem;
        border-radius: 20px;
      }
      
      .login-header h3 {
        font-size: 1.5rem;
      }

      .custom-alert {
        max-width: calc(100vw - 40px);
        right: 20px;
        left: 20px;
        transform: translateY(-100%);
      }

      .custom-alert.show {
        transform: translateY(0);
      }
    }

    /* Loading Animation */
    .btn-login.loading,
    .btn-forgot-password.loading {
      position: relative;
      color: transparent;
    }

    .btn-login.loading::after,
    .btn-forgot-password.loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -10px;
      border: 2px solid transparent;
      border-top-color: currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    .btn-login.loading::after {
      border-top-color: white;
    }

    .btn-forgot-password.loading::after {
      border-top-color: var(--dark-blue);
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Success message styling */
    .success-message {
      background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF4 100%);
      border: 2px solid #10B981;
      border-radius: 16px;
      padding: 1.5rem;
      text-align: center;
      color: #065F46;
      margin-bottom: 1.5rem;
    }

    .success-message i {
      font-size: 2rem;
      color: #10B981;
      margin-bottom: 0.5rem;
    }

    .success-message h5 {
      color: #065F46;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
  </style>
</head>
<body>

<div class="login-wrapper">
  <div class="login-container">
    <!-- Login Form -->
    <div id="loginSection" class="form-section">
      <div class="login-header">
        <h3>Selamat Datang</h3>
        <p>Silakan login ke akun Anda</p>
      </div>

      @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          {{ session('error') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" onsubmit="return validateForm()" id="loginForm">
        @csrf

        <!-- Email Field -->
        <div class="form-floating">
          <input type="email" class="form-control" id="email" name="email" 
                 placeholder="Email" required autocomplete="email">
          <label for="email">
            <i class="bi bi-envelope me-2"></i>Alamat Email
          </label>
        </div>

        <!-- Password Field -->
        <div class="form-floating password-wrapper">
          <input type="password" class="form-control @error('password') is-invalid @enderror"
                 id="password" name="password" placeholder="Password" required 
                 autocomplete="current-password" minlength="6">
          <label for="password">
            <i class="bi bi-lock me-2"></i>Kata Sandi
          </label>
          
          <span class="toggle-password" onclick="togglePassword()" tabindex="0" role="button" 
                aria-label="Toggle password visibility">
            <i class="bi bi-eye-slash" id="eyeIcon"></i>
          </span>
        </div>

        <!-- Password Error -->
        <div id="passwordError" class="error-message" style="display: none;">
          <i class="bi bi-exclamation-circle"></i>
          Password minimal 6 karakter
        </div>

        @error('password')
          <div class="error-message">
            <i class="bi bi-exclamation-circle"></i>
            {{ $message }}
          </div>
        @enderror

        <!-- Forgot Password Link -->
        <div class="forgot-password-link">
          <a href="#" onclick="showForgotPassword()" id="forgotPasswordLink">
            <i class="bi bi-key me-1"></i>Lupa kata sandi?
          </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-login" id="submitBtn">
          <i class="bi bi-box-arrow-in-right me-2"></i>
          Masuk ke Dashboard
        </button>

        <!-- Register Link -->
        <div class="register-link">
          <p>Belum memiliki akun? <a href="/register" onclick="return validateRegistration()">Daftar sekarang</a></p>
        </div>
      </form>
    </div>

    <!-- Forgot Password Form -->
    <div id="forgotPasswordSection" class="form-section hidden">
      <div class="login-header">
        <h3>Lupa Kata Sandi</h3>
        <p>Masukkan email Anda untuk reset kata sandi</p>
      </div>

      <div id="forgotPasswordSuccess" class="success-message" style="display: none;">
        <i class="bi bi-check-circle-fill d-block"></i>
        <h5>Email Terkirim!</h5>
        <p class="mb-0">Link reset kata sandi telah dikirim ke email Anda. Silakan cek kotak masuk atau folder spam.</p>
      </div>

      <form id="forgotPasswordForm" onsubmit="return handleForgotPassword(event)">
        <!-- Email Field for Reset -->
        <div class="form-floating">
          <input type="email" class="form-control" id="forgotEmail" name="email" 
                 placeholder="Email" required autocomplete="email">
          <label for="forgotEmail">
            <i class="bi bi-envelope me-2"></i>Alamat Email
          </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-forgot-password" id="forgotSubmitBtn">
          <i class="bi bi-send me-2"></i>
          Kirim Link Reset
        </button>

        <!-- Back Button -->
        <button type="button" class="btn btn-back" onclick="showLogin()">
          <i class="bi bi-arrow-left me-2"></i>
          Kembali ke Login
        </button>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function togglePassword() {
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (passwordField.type === 'password') {
      passwordField.type = 'text';
      eyeIcon.className = 'bi bi-eye';
    } else {
      passwordField.type = 'password';
      eyeIcon.className = 'bi bi-eye-slash';
    }
  }

  function showForgotPassword() {
    const loginSection = document.getElementById('loginSection');
    const forgotSection = document.getElementById('forgotPasswordSection');
    
    loginSection.classList.add('hidden');
    
    setTimeout(() => {
      forgotSection.classList.remove('hidden');
    }, 200);
  }

  function showLogin() {
    const loginSection = document.getElementById('loginSection');
    const forgotSection = document.getElementById('forgotPasswordSection');
    const successDiv = document.getElementById('forgotPasswordSuccess');
    
    forgotSection.classList.add('hidden');
    successDiv.style.display = 'none';
    document.getElementById('forgotPasswordForm').style.display = 'block';
    
    setTimeout(() => {
      loginSection.classList.remove('hidden');
    }, 200);
  }

  function handleForgotPassword(event) {
    event.preventDefault();
    
    const email = document.getElementById('forgotEmail').value;
    const submitBtn = document.getElementById('forgotSubmitBtn');
    const form = document.getElementById('forgotPasswordForm');
    const successDiv = document.getElementById('forgotPasswordSuccess');
    
    // Validasi email
    if (!email || !validateEmailFormat(email)) {
      showCustomAlert('Silakan masukkan alamat email yang valid.', 'warning');
      return false;
    }
    
    // Loading state
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
      // Reset button state
      submitBtn.classList.remove('loading');
      submitBtn.disabled = false;
      
      // Show success message
      form.style.display = 'none';
      successDiv.style.display = 'block';
      
      // In real implementation, you would make an actual API call:
      /*
      fetch('/forgot-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ email: email })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          form.style.display = 'none';
          successDiv.style.display = 'block';
        } else {
          showCustomAlert(data.message || 'Terjadi kesalahan. Silakan coba lagi.', 'warning');
        }
      })
      .catch(error => {
        showCustomAlert('Terjadi kesalahan jaringan. Silakan coba lagi.', 'warning');
      })
      .finally(() => {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
      });
      */
      
    }, 2000); // Simulate network delay
    
    return false;
  }

  function validateEmailFormat(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function validateRegistration() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    
    if (!email && !password) {
      return true;
    }
    
    if (email && !password) {
      showCustomAlert('Email sudah diisi. Silakan masukkan password untuk login, atau kosongkan email untuk daftar akun baru.', 'warning');
      return false;
    }
    
    if (!email && password) {
      showCustomAlert('Password sudah diisi. Silakan masukkan email untuk login, atau kosongkan password untuk daftar akun baru.', 'warning');
      return false;
    }
    
    if (email && password) {
      const confirmLeave = confirm('Data login sudah diisi. Yakin ingin meninggalkan halaman ini untuk mendaftar akun baru?');
      return confirmLeave;
    }
    
    return true;
  }

  function showCustomAlert(message, type = 'info') {
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `custom-alert ${type}`;
    
    const iconClass = {
      'info': 'bi-info-circle',
      'warning': 'bi-exclamation-triangle',
      'success': 'bi-check-circle'
    };
    
    alertDiv.innerHTML = `
      <div class="d-flex align-items-center">
        <i class="bi ${iconClass[type]} me-2" style="font-size: 1.2rem;"></i>
        <span style="font-size: 0.9rem; font-weight: 500;">${message}</span>
      </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => alertDiv.classList.add('show'), 100);
    
    setTimeout(() => {
      alertDiv.classList.remove('show');
      setTimeout(() => alertDiv.remove(), 400);
    }, 5000);
  }

  function validateForm() {
    const password = document.getElementById('password').value;
    const passwordError = document.getElementById('passwordError');
    const submitBtn = document.getElementById('submitBtn');
    
    passwordError.style.display = 'none';
    
    if (password.length < 6) {
      passwordError.style.display = 'block';
      return false;
    }
    
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    return true;
  }

  // Enhanced form interactions
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const inputs = form.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
      input.addEventListener('input', function() {
        if (this.id === 'password') {
          const passwordError = document.getElementById('passwordError');
          if (this.value.length > 0 && this.value.length < 6) {
            passwordError.style.display = 'block';
          } else {
            passwordError.style.display = 'none';
          }
        }
      });
      
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
      });
    });
    
    // Keyboard support for password toggle
    document.querySelector('.toggle-password').addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        togglePassword();
      }
    });

    // Auto-fill email in forgot password form
    document.getElementById('forgotPasswordLink').addEventListener('click', function() {
      const loginEmail = document.getElementById('email').value;
      if (loginEmail) {
        setTimeout(() => {
          document.getElementById('forgotEmail').value = loginEmail;
        }, 300);
      }
    });
  });
</script>

</body>
</html>