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
    }

    .login-header p {
      color: var(--text-secondary);
      font-size: 0.95rem;
      margin: 0;
      font-weight: 400;
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
      max-width: 300px;
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

    /* Responsive Design */
    @media (max-width: 480px) {
      .login-container {
        padding: 2rem 1.5rem;
        border-radius: 20px;
      }
      
      .login-header h3 {
        font-size: 1.5rem;
      }
    }

    /* Loading Animation */
    .btn-login.loading {
      position: relative;
      color: transparent;
    }

    .btn-login.loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -10px;
      border: 2px solid transparent;
      border-top-color: white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>

<div class="login-wrapper">
  <div class="login-container">
    <div class="login-header">
      <h3>Selamat Datang</h3>
      <p>Silakan login ke akun admin Anda</p>
    </div>

    @if(session('error'))
      <div class="alert alert-danger d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="/admin/login" onsubmit="return validateForm()" id="loginForm">
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

      <!-- Submit Button -->
      <button type="submit" class="btn btn-login" id="submitBtn">
        <i class="bi bi-box-arrow-in-right me-2"></i>
        Masuk ke Dashboard
      </button>

      <!-- Register Link -->
      <div class="register-link">
        <p>Belum memiliki akun? <a href="/auth/user-register" onclick="return validateRegistration()">Daftar sekarang</a></p>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function togglePassword() {
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (passwordField.type === 'password') {
      // Mata tertutup -> buka mata (bisa lihat password)
      passwordField.type = 'text';
      eyeIcon.className = 'bi bi-eye'; // mata terbuka
    } else {
      // Mata terbuka -> tutup mata (tidak bisa lihat password)
      passwordField.type = 'password';
      eyeIcon.className = 'bi bi-eye-slash'; // mata tertutup
    }
  }

  function validateRegistration() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    
    // Jika form kosong, langsung izinkan ke register tanpa notifikasi
    if (!email && !password) {
      return true; // Langsung lanjut ke halaman register
    }
    
    // Jika hanya email diisi
    if (email && !password) {
      showCustomAlert('Email sudah diisi. Silakan masukkan password untuk login, atau kosongkan email untuk daftar akun baru.', 'warning');
      return false; // Tidak jadi ke register
    }
    
    // Jika hanya password diisi
    if (!email && password) {
      showCustomAlert('Password sudah diisi. Silakan masukkan email untuk login, atau kosongkan password untuk daftar akun baru.', 'warning');
      return false; // Tidak jadi ke register
    }
    
    if (email && password) {
      const confirmLeave = confirm('Data login sudah diisi. Yakin ingin meninggalkan halaman ini untuk mendaftar akun baru?');
      return confirmLeave;
    }
    
    return true;
  }

  function showCustomAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
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
    
    // Show alert
    setTimeout(() => alertDiv.classList.add('show'), 100);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
      alertDiv.classList.remove('show');
      setTimeout(() => alertDiv.remove(), 400);
    }, 5000);
  }

  function validateForm() {
    const password = document.getElementById('password').value;
    const passwordError = document.getElementById('passwordError');
    const submitBtn = document.getElementById('submitBtn');
    
    // Reset error state
    passwordError.style.display = 'none';
    
    // Validate password length
    if (password.length < 6) {
      passwordError.style.display = 'block';
      return false;
    }
    
    // Add loading state
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    return true;
  }

  // Enhanced form interactions
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const inputs = form.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
      // Real-time validation feedback
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
      
      // Focus effects
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
  });
</script>

</body>
</html>
