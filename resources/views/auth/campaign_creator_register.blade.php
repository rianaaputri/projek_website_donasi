@extends('layouts.app')

@section('title', 'Daftar Campaign Creator')

@section('content')
<!-- Import Google Fonts Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

<div class="register-wrapper d-flex align-items-center justify-content-center min-vh-100 py-4">
    <div class="register-container">
        <div class="register-header text-center mb-4">
            <h3>Daftar Campaign Creator</h3>
            <p>Lengkapi data di bawah untuk menjadi pembuat campaign donasi!</p>
        </div>

        <form method="POST" action="{{ route('campaign.creator.register') }}" id="registerForm">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <div class="floating-label">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder=" ">
                    <label for="name">
                        <i class="bi bi-person"></i>
                        Nama Lengkap
                    </label>
                </div>
                @error('name')
                    <div class="error-message">
                        <ul><li>{{ $message }}</li></ul>
                    </div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group mt-4">
                <div class="floating-label">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder=" ">
                    <label for="email">
                        <i class="bi bi-envelope"></i>
                        Email (Gmail)
                    </label>
                </div>
                @error('email')
                    <div class="error-message">
                        <ul><li>{{ $message }}</li></ul>
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group mt-4">
                <div class="floating-label password-wrapper">
                    <input id="password" type="password" name="password" required placeholder=" ">
                    <label for="password">
                        <i class="bi bi-lock"></i>
                        Password
                    </label>
                    <span class="toggle-password" onclick="togglePassword('password')">
                        <i class="bi bi-eye-slash" id="passwordEyeIcon"></i>
                    </span>
                </div>
                @error('password')
                    <div class="error-message">
                        <ul><li>{{ $message }}</li></ul>
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group mt-4">
                <div class="floating-label password-wrapper">
                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder=" ">
                    <label for="password_confirmation">
                        <i class="bi bi-shield-lock"></i>
                        Konfirmasi Password
                    </label>
                    <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                        <i class="bi bi-eye-slash" id="confirmPasswordEyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="form-actions mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun?</a>
                    <button type="submit" class="btn-register">
                        <i class="bi bi-person-plus me-2"></i> Daftar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Salin style dari register.blade.php kamu, atau gunakan layout yang sudah ada */
    .register-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid #E1E7EF;
        border-radius: 24px;
        padding: 3rem 2.5rem;
        box-shadow: 0 8px 32px rgba(33, 150, 243, 0.08);
        max-width: 450px;
        width: 100%;
    }

    .register-header h3 {
        color: #1E3A8A;
        font-weight: 600;
        font-size: 1.75rem;
    }

    .register-header p {
        color: #64748B;
    }

    .floating-label {
        position: relative;
    }

    .floating-label input {
        border: 2px solid #E1E7EF;
        border-radius: 16px;
        padding: 1rem 1.25rem;
        width: 100%;
        font-size: 0.95rem;
        background-color: #F8FAFC;
        transition: all 0.3s;
    }

    .floating-label input:focus {
        border-color: #64B5F6;
        box-shadow: 0 0 0 4px rgba(100, 181, 246, 0.1);
        background-color: white;
    }

    .floating-label label {
        position: absolute;
        left: 1.25rem;
        top: 1rem;
        color: #64748B;
        font-weight: 500;
        font-size: 0.95rem;
        pointer-events: none;
        transition: all 0.3s;
        background: transparent;
        padding: 0 0.25rem;
    }

    .floating-label input:not(:placeholder-shown) ~ label,
    .floating-label input:focus ~ label {
        top: -0.5rem;
        left: 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #2196F3;
        background: white;
        padding: 0 0.5rem;
    }

    .error-message {
        color: #EF4444;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: #FEE2E2;
        border: 1px solid #FECACA;
        border-radius: 8px;
    }

    .btn-register {
        background: linear-gradient(135deg, #64B5F6, #2196F3);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        width: auto;
        min-width: 150px;
        box-shadow: 0 4px 16px rgba(33, 150, 243, 0.3);
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(33, 150, 243, 0.4);
    }

    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 1rem;
        cursor: pointer;
        color: #64748B;
    }
</style>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + 'EyeIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye-slash';
    }
}
</script>
@endsection