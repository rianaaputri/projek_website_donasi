<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #E3F2FD 0%, #F8FAFC 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .login-container {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
      max-width: 400px;
      width: 100%;
    }
    .form-floating {
      margin-bottom: 1rem;
    }
    .toggle-password {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
    .error-message {
      color: #DC2626;
      font-size: 0.85rem;
      margin-top: 0.25rem;
    }
  </style>
</head>
<body>
<div class="login-container">
  <h4 class="text-center mb-4">Login ke Akun Anda</h4>

  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}" id="loginForm" onsubmit="return validateForm()">
    @csrf
    <div class="form-floating">
      <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" required>
      <label for="email">Email</label>
      @error('email')
        <div class="error-message">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-floating position-relative">
      <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required minlength="6">
      <label for="password">Password</label>
      <span class="toggle-password" onclick="togglePassword()">
        <i class="bi bi-eye-slash" id="eyeIcon"></i>
      </span>
      <div id="passwordError" class="error-message" style="display: none;">Password minimal 6 karakter</div>
      @error('password')
        <div class="error-message">{{ $message }}</div>
      @enderror
    </div>

    <div class="text-end mb-3">
      <a href="{{ route('password.request') }}">Lupa password?</a>
    </div>

    <button type="submit" class="btn btn-primary w-100">Login</button>

    <div class="text-center mt-3">
      <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
    </div>
  </form>
</div>

<script>
  function togglePassword() {
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordField.type === 'password') {
      passwordField.type = 'text';
      eyeIcon.classList.remove('bi-eye-slash');
      eyeIcon.classList.add('bi-eye');
    } else {
      passwordField.type = 'password';
      eyeIcon.classList.remove('bi-eye');
      eyeIcon.classList.add('bi-eye-slash');
    }
  }

  function validateForm() {
    const password = document.getElementById('password').value;
    const passwordError = document.getElementById('passwordError');

    passwordError.style.display = 'none';

    if (password.length < 6) {
      passwordError.style.display = 'block';
      return false;
    }
    return true;
  }
</script>
</body>
</html>
