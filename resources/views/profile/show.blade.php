<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary: #007AFF;
            --primary-light: #E3F2FD;
            --primary-dark: #0056CC;
            --secondary: #8E8E93;
            --success: #34C759;
            --danger: #FF3B30;
            --warning: #FF9500;
            --info: #5AC8FA;
            --light: #F2F2F7;
            --dark: #1C1C1E;
            --white: #ffffff;
            
            --gradient-primary: linear-gradient(135deg, #007AFF 0%, #5AC8FA 50%, #AF52DE 100%);
            --gradient-bg: linear-gradient(135deg, #F2F2F7 0%, #ffffff 100%);
            
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 16px 40px rgba(0, 0, 0, 0.20);
            
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --border-radius-xl: 20px;
            
            --transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --transition-fast: all 0.15s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* ===== GLOBAL STYLES WITH POPPINS FONT ===== */
        * {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Helvetica Neue", Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            background: var(--gradient-bg);
            color: var(--dark);
            line-height: 1.47059;
            font-weight: 400;
            letter-spacing: -0.022em;
            overflow-x: hidden;
        }

        .container-fluid {
            max-width: 1400px;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -30px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translate3d(30px, 0, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        .animate-fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        /* ===== STAGGER ANIMATIONS ===== */
        .stagger > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger > *:nth-child(2) { animation-delay: 0.2s; }
        .stagger > *:nth-child(3) { animation-delay: 0.3s; }
        .stagger > *:nth-child(4) { animation-delay: 0.4s; }
        .stagger > *:nth-child(5) { animation-delay: 0.5s; }

        /* ===== HEADER STYLES ===== */
        .page-header {
            text-align: center;
            padding: 3rem 0 2rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.04em;
        }

        .page-subtitle {
            font-size: 1.125rem;
            color: var(--secondary);
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ===== PROFILE CARD ===== */
        .profile-card {
            background: var(--white);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: var(--transition);
        }

        .profile-header {
            background: var(--gradient-primary);
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="25" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="25" r="1" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .avatar {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--white);
            margin: 0 auto 1.5rem;
            position: relative;
            z-index: 1;
            transition: var(--transition);
        }

        .avatar:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .profile-name {
            color: var(--white);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            letter-spacing: -0.025em;
        }

        .profile-role {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.125rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        /* ===== BADGES ===== */
        .badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
            transition: var(--transition);
        }

        .badge-verified {
            background: rgba(52, 199, 89, 0.2);
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .badge-unverified {
            background: rgba(255, 149, 0, 0.2);
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            min-width: 120px;
            letter-spacing: -0.01em;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transition: var(--transition);
            transform: translate(-50%, -50%);
            z-index: 0;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn > * {
            position: relative;
            z-index: 1;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-light {
            background: rgba(255, 255, 255, 0.9);
            color: var(--dark);
            border-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
        }

        .btn-light:hover {
            background: var(--white);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            border-color: var(--success);
            color: var(--white);
        }

        .btn-success:hover {
            background: #28a745;
            border-color: #28a745;
            color: var(--white);
            transform: translateY(-2px);
        }

        /* ===== LOADING STATE ===== */
        .btn-loading {
            color: transparent;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid currentColor;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        /* ===== CONTENT SECTIONS ===== */
        .content-section {
            padding: 2.5rem;
        }

        .info-card {
            background: var(--white);
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-primary);
            transition: var(--transition);
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .info-card:hover::before {
            width: 6px;
        }

        .card-title {
            color: var(--dark);
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: -0.025em;
        }

        .info-item {
            margin-bottom: 1.5rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 500;
            color: var(--dark);
            padding: 1rem;
            background: var(--light);
            border-radius: var(--border-radius);
            border: 2px solid transparent;
            min-height: 3rem;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        /* ===== FORM STYLES ===== */
        .form-control {
            border: 2px solid rgba(0, 0, 0, 0.08);
            border-radius: var(--border-radius);
            padding: 1rem;
            font-size: 1rem;
            transition: var(--transition);
            background: var(--white);
            font-weight: 500;
            letter-spacing: -0.01em;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
            background: var(--white);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--secondary);
            font-weight: 400;
        }

        /* ===== PASSWORD POPUP MODAL ===== */
        .password-modal .modal-content {
            border: none;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-xl);
            backdrop-filter: blur(20px);
        }

        .password-modal .modal-header {
            background: var(--gradient-primary);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;
            padding: 2rem;
        }

        .password-modal .modal-title {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.025em;
        }

        .password-modal .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }

        .password-modal .modal-body {
            padding: 2rem;
        }

        .password-modal .modal-footer {
            border: none;
            padding: 1rem 2rem 2rem;
            justify-content: center;
            gap: 1rem;
        }

        .password-modal .form-group {
            margin-bottom: 1.5rem;
        }

        .password-modal .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .password-modal .input-group {
            position: relative;
        }

        .password-modal .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--secondary);
            font-size: 1.1rem;
            cursor: pointer;
            z-index: 3;
            padding: 0.5rem;
            border-radius: 50%;
            transition: var(--transition);
        }

        .password-modal .toggle-password:hover {
            color: var(--primary);
            background: rgba(0, 122, 255, 0.1);
        }

        .password-modal .form-control {
            padding-right: 3.5rem;
        }

        /* ===== ALERTS ===== */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            border-left: 4px solid;
            backdrop-filter: blur(20px);
        }

        .alert-success {
            background: rgba(52, 199, 89, 0.1);
            color: var(--success);
            border-left-color: var(--success);
        }

        .alert-danger {
            background: rgba(255, 59, 48, 0.1);
            color: var(--danger);
            border-left-color: var(--danger);
        }

        .alert-info {
            background: rgba(90, 200, 250, 0.1);
            color: var(--info);
            border-left-color: var(--info);
        }

        /* ===== DROPDOWN ===== */
        .dropdown-menu {
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            background: var(--white);
            padding: 0.5rem;
            backdrop-filter: blur(20px);
        }

        .dropdown-item {
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            transition: var(--transition);
            color: var(--dark);
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
            transform: translateX(4px);
        }

        .dropdown-item.text-danger {
            color: var(--danger) !important;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(255, 59, 48, 0.1);
            color: var(--danger) !important;
        }

        /* ===== MODE TRANSITIONS ===== */
        .mode-transition {
            transition: var(--transition);
        }

        .edit-mode-enter {
            opacity: 0;
            transform: translateY(-10px);
        }

        .edit-mode-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: var(--transition);
        }

        .view-mode-enter {
            opacity: 0;
            transform: translateY(10px);
        }

        .view-mode-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: var(--transition);
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 991.98px) {
            .page-title {
                font-size: 2rem;
            }

            .profile-header {
                padding: 2rem 1.5rem;
            }

            .content-section {
                padding: 2rem 1.5rem;
            }

            .info-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .page-title {
                font-size: 1.75rem;
            }

            .avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .profile-name {
                font-size: 1.75rem;
            }

            .profile-role {
                font-size: 1rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .info-card {
                padding: 1.25rem;
            }

            .password-modal .modal-body {
                padding: 1.5rem;
            }
        }

        /* ===== UTILITY CLASSES ===== */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 4px;
            transition: var(--transition);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
    </style>
</head>
<body>
    <!-- Main Container -->
    <div class="container-fluid px-3 px-md-4">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <header class="page-header animate-fade-in-down">
                    <h1 class="page-title">
                        <i class="fas fa-user-circle text-gradient me-3"></i>
                        Profil Saya
                    </h1>
                    <p class="page-subtitle">
                        Kelola informasi akun dan pengaturan profil Anda dengan mudah dan aman
                    </p>
                </header>
            </div>
        </div>

        <!-- Alerts Container -->
        <div class="row" id="alerts-container">
            <div class="col-12">
                <!-- Success Alert Example -->
                <div class="alert alert-success animate-fade-in d-none" id="success-alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Profil berhasil diperbarui!</span>
                    <button type="button" class="btn-close ms-auto" onclick="closeAlert('success-alert')"></button>
                </div>
                
                <!-- Danger Alert Example -->
                <div class="alert alert-danger animate-fade-in d-none" id="error-alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span>Terjadi kesalahan saat memperbarui profil.</span>
                    <button type="button" class="btn-close ms-auto" onclick="closeAlert('error-alert')"></button>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="profile-card animate-fade-in-up">
                    
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="container-fluid">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="avatar hover-scale">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <h2 class="profile-name" id="displayName">{{ Auth::user()->name }}</h2>
                                    <p class="profile-role">
                                        <i class="fas fa-user-tag me-2"></i>
                                        {{ ucfirst(Auth::user()->role ?? 'User') }}
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        @if(Auth::user()->hasVerifiedEmail())
                                            <span class="badge-custom badge-verified">
                                                <i class="fas fa-shield-check"></i>
                                                Email Terverifikasi
                                            </span>
                                        @else
                                            <span class="badge-custom badge-unverified">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Email Belum Terverifikasi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex flex-column gap-3 align-items-center mt-4 mt-lg-0">
                                        <button class="btn btn-light hover-lift" onclick="toggleEditMode()" id="editBtn">
                                            <i class="fas fa-edit me-2"></i>Edit Profil
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle hover-lift" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v me-2"></i>Menu
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="/">
                                                    <i class="fas fa-home me-2 text-primary"></i>Beranda
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="showPasswordModal()">
                                                    <i class="fas fa-key me-2 text-warning"></i>Ubah Password
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="logout()">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Content -->
                    <div class="content-section">
                        <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="row g-4">
                                <!-- Personal Information -->
                                <div class="col-lg-6">
                                    <div class="info-card hover-lift animate-fade-in-up stagger">
                                        <h4 class="card-title">
                                            <i class="fas fa-user text-primary"></i>
                                            Informasi Personal
                                        </h4>
                                        
                                        <!-- Name Field -->
                                        <div class="info-item mode-transition">
                                            <div class="info-label">Nama Lengkap</div>
                                            <div class="view-mode">
                                                <div class="info-value" id="displayNameValue">{{ Auth::user()->name }}</div>
                                            </div>
                                            <div class="edit-mode d-none">
                                                <input type="text" class="form-control" name="name" id="nameInput" value="{{ Auth::user()->name }}" required>
                                            </div>
                                        </div>

                                        <!-- Email Field -->
                                        <div class="info-item mode-transition">
                                            <div class="info-label">Alamat Email</div>
                                            <div class="view-mode">
                                                <div class="info-value" id="displayEmailValue">{{ Auth::user()->email }}</div>
                                            </div>
                                            <div class="edit-mode d-none">
                                                <input type="email" class="form-control" name="email" id="emailInput" value="{{ Auth::user()->email }}" required>
                                            </div>
                                        </div>
                                    <!-- Phone Field -->
                                    <div class="info-item mode-transition">
                                        <div class="info-label">Nomor Telepon</div>
                                        <div class="view-mode">
                                            <div class="info-value" id="phoneDisplayValue">{{ Auth::user()->phone ?? 'Belum diisi' }}</div>
                                        </div>
                                        <div class="edit-mode d-none">
                                            <input 
                                                type="tel" 
                                                class="form-control" 
                                                name="phone" 
                                                id="phoneInput" 
                                                value="{{ Auth::user()->phone }}" 
                                                placeholder="Contoh: 081234567890"
                                                inputmode="numeric"
                                                pattern="[0-9]*"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Address Field -->
                                    <div class="info-item mode-transition">
                                        <div class="info-label">Alamat</div>
                                        <div class="view-mode">
                                            <div class="info-value" id="addressDisplayValue">{{ Auth::user()->address ?? 'Belum diisi' }}</div>
                                        </div>
                                        <div class="edit-mode d-none">
                                            <textarea 
                                                class="form-control" 
                                                name="address" 
                                                id="addressInput" 
                                                rows="3" 
                                                placeholder="Masukkan alamat lengkap" 
                                                required>{{ Auth::user()->address }}</textarea>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <!-- Account Information -->
                                <div class="col-lg-6">
                                    <div class="info-card hover-lift animate-fade-in-up stagger">
                                        <h4 class="card-title">
                                            <i class="fas fa-cog text-primary"></i>
                                            Informasi Akun
                                        </h4>

                                        <div class="info-item">
                                            <div class="info-label">Bergabung Sejak</div>
                                            <div class="info-value">
                                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                {{ Auth::user()->created_at->translatedFormat('d F Y') }}
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-label">Update Terakhir</div>
                                            <div class="info-value">
                                                <i class="fas fa-clock me-2 text-primary"></i>
                                                {{ Auth::user()->updated_at->translatedFormat('d F Y H:i') }}
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-label">Status Akun</div>
                                            <div class="info-value">
                                                @if(Auth::user()->is_active ?? true)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                                        <i class="fas fa-times-circle me-1"></i>
                                                        Tidak Aktif
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="text-center">
                                        <!-- View Mode Buttons -->
                                        <div class="view-mode">
                                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                                <a href="/" class="btn btn-outline-primary hover-lift">
                                                    <i class="fas fa-home me-2"></i>Kembali ke Beranda
                                                </a>
                                                @if(!Auth::user()->hasVerifiedEmail())
                                                <button type="button" class="btn btn-outline-success hover-lift" onclick="sendVerificationEmail()">
                                                    <i class="fas fa-envelope me-2"></i>Kirim Verifikasi Email
                                                </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Edit Mode Buttons -->
                                        <div class="edit-mode d-none">
                                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                                <button type="submit" class="btn btn-success hover-lift" id="saveBtn">
                                                    <i class="fas fa-save me-2"></i>
                                                    <span class="btn-text">Simpan Perubahan</span>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary hover-lift" onclick="cancelEdit()">
                                                    <i class="fas fa-times me-2"></i>Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Spacer -->
        <div class="row">
            <div class="col-12">
                <div style="height: 3rem;"></div>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div class="modal fade password-modal" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">
                        <i class="fas fa-key me-2"></i>
                        Ubah Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="passwordForm" action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="currentPassword" class="form-label">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('currentPassword')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="newPassword" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" name="password" required minlength="6">
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('newPassword')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted mt-1">Minimal 6 karakter</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirmPassword')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                            <i class="fas fa-save me-2"></i>
                            <span class="btn-text">Ubah Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 11;">
        <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="successToastMessage">Operasi berhasil!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorToastMessage">Terjadi kesalahan!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        
        <div id="infoToast" class="toast align-items-center text-bg-info border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-info-circle me-2"></i>
                    <span id="infoToastMessage">Informasi!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // ===== APPLICATION STATE =====
    let isEditMode = false;
    let originalFormData = {};
    let passwordModal;
    
    // ===== CSRF TOKEN SETUP =====
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // ===== INITIALIZATION =====
    document.addEventListener('DOMContentLoaded', function() {
        initializeApp();
        setupEventListeners();
        addAnimations();
        initializeModal();
    });

    function initializeApp() {
        // Store original form data
        storeOriginalData();
        
        // Setup smooth scrolling
        document.documentElement.style.scrollBehavior = 'smooth';
        
        // Initialize Bootstrap tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        
        // Show loading complete animation
        document.body.classList.add('loaded');
    }

    function initializeModal() {
        passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
    }

    function setupEventListeners() {
        // Form submission with network check
        document.getElementById('profileForm').addEventListener('submit', (e) => {
            if (!checkNetworkStatus()) {
                e.preventDefault();
                return;
            }
            handleFormSubmit(e);
        });

        // Password form submission
        document.getElementById('passwordForm').addEventListener('submit', (e) => {
            if (!checkNetworkStatus()) {
                e.preventDefault();
                return;
            }
            handlePasswordFormSubmit(e);
        });
        
        // Input validation
        document.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('input', debounce(handleInputValidation, 300));
            input.addEventListener('blur', handleInputValidation);
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', handleKeyboardShortcuts);
        
        // Form change detection
        document.getElementById('profileForm').addEventListener('input', () => {
            const saveBtn = document.getElementById('saveBtn');
            if (saveBtn && isEditMode) {
                saveBtn.classList.remove('btn-secondary');
                saveBtn.classList.add('btn-success');
            }
        });

        // Password confirmation validation
        document.getElementById('confirmPassword').addEventListener('input', validatePasswordConfirmation);
    }

    // ===== PASSWORD MODAL FUNCTIONS =====
    function showPasswordModal() {
        passwordModal.show();
        setTimeout(() => {
            document.getElementById('currentPassword').focus();
        }, 300);
    }

    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.parentNode.querySelector('.toggle-password i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function validatePasswordConfirmation() {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const confirmInput = document.getElementById('confirmPassword');
        
        // Clear previous validation
        confirmInput.classList.remove('is-invalid', 'is-valid');
        const errorDiv = confirmInput.parentNode.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) errorDiv.remove();
        
        if (confirmPassword && newPassword !== confirmPassword) {
            confirmInput.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = 'Konfirmasi password tidak cocok';
            confirmInput.parentNode.parentNode.appendChild(errorDiv);
        } else if (confirmPassword) {
            confirmInput.classList.add('is-valid');
        }
    }

    async function handlePasswordFormSubmit(e) {
        e.preventDefault();
        
        const changeBtn = document.getElementById('changePasswordBtn');
        const btnText = changeBtn.querySelector('.btn-text');
        const form = e.target;
        
        // Show loading state
        changeBtn.disabled = true;
        changeBtn.classList.add('btn-loading');
        btnText.textContent = 'Mengubah...';
        
        try {
            const formData = new FormData(form);
            
            const response = await safeFetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData,
                credentials: 'same-origin'
            });
            
            let result;
            try {
                result = await response.json();
            } catch (e) {
                result = { message: 'Password berhasil diubah!' };
            }
            
            if (response.ok) {
                // Success
                passwordModal.hide();
                form.reset();
                showToast('Password berhasil diubah!', 'success');
            } else {
                if (result.errors) {
                    handlePasswordValidationErrors(result.errors);
                    showToast('Terdapat kesalahan pada form. Silakan periksa kembali.', 'error');
                } else {
                    showToast(result.message || 'Terjadi kesalahan saat mengubah password.', 'error');
                }
            }
            
        } catch (error) {
            console.error('Password change error:', error);
            showToast('Terjadi kesalahan koneksi. Silakan coba lagi.', 'error');
        } finally {
            // Reset button state
            changeBtn.disabled = false;
            changeBtn.classList.remove('btn-loading');
            btnText.textContent = 'Ubah Password';
        }
    }

    function handlePasswordValidationErrors(errors) {
        // Clear previous error states
        document.querySelectorAll('#passwordForm .form-control').forEach(input => {
            input.classList.remove('is-invalid');
            const errorDiv = input.parentNode.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) errorDiv.remove();
        });
        
        // Display new errors
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`#passwordForm [name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errors[field][0];
                input.parentNode.parentNode.appendChild(errorDiv);
            }
        });
    }

    // ===== EDIT MODE FUNCTIONS =====
    function toggleEditMode() {
        isEditMode = !isEditMode;
        
        const viewModes = document.querySelectorAll('.view-mode');
        const editModes = document.querySelectorAll('.edit-mode');
        const editBtn = document.getElementById('editBtn');
        
        if (isEditMode) {
            // Enter edit mode with smooth transition
            viewModes.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        element.classList.add('d-none');
                    }, 150);
                }, index * 50);
            });
            
            setTimeout(() => {
                editModes.forEach((element, index) => {
                    element.classList.remove('d-none');
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }, index * 50 + 50);
                });
            }, 200);
            
            // Update button
            editBtn.innerHTML = '<i class="fas fa-eye me-2"></i>Lihat Profil';
            editBtn.className = 'btn btn-primary hover-lift';
            
            // Focus first input
            setTimeout(() => {
                const firstInput = document.querySelector('.edit-mode input:not([type="hidden"])');
                if (firstInput) firstInput.focus();
            }, 400);
            
            showToast('Mode edit diaktifkan', 'info');
            
        } else {
            exitEditMode();
        }
    }

    function exitEditMode() {
        const viewModes = document.querySelectorAll('.view-mode');
        const editModes = document.querySelectorAll('.edit-mode');
        const editBtn = document.getElementById('editBtn');
        
        // Smooth transition back to view mode
        editModes.forEach((element, index) => {
            setTimeout(() => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    element.classList.add('d-none');
                }, 150);
            }, index * 50);
        });
        
        setTimeout(() => {
            viewModes.forEach((element, index) => {
                element.classList.remove('d-none');
                element.style.opacity = '0';
                element.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 50 + 50);
            });
        }, 200);
        
        // Update button
        editBtn.innerHTML = '<i class="fas fa-edit me-2"></i>Edit Profil';
        editBtn.className = 'btn btn-light hover-lift';
        
        isEditMode = false;
    }

    function cancelEdit() {
        restoreOriginalData();
        exitEditMode();
        showToast('Perubahan dibatalkan', 'info');
    }

    // ===== FORM HANDLING WITH MULTIPLE FALLBACK METHODS =====
    async function handleFormSubmit(e) {
    e.preventDefault();
    const saveBtn = document.getElementById('saveBtn');
    const btnText = saveBtn.querySelector('.btn-text');
    const form = e.target;
    // Show loading state
    saveBtn.disabled = true;
    saveBtn.classList.add('btn-loading');
    btnText.textContent = 'Menyimpan...';
    let success = false;
    try {
        success = await tryFormDataSubmission(form);
        if (success) return;
    } catch (error) {
        console.warn('FormData submission failed:', error);
    }
    try {
        success = await tryJsonSubmission(form);
        if (success) return;
    } catch (error) {
        console.warn('JSON submission failed:', error);
    }
    try {
        await tryTraditionalSubmission(form);
    } catch (error) {
        console.error('All submission methods failed:', error);
        showToast('Gagal menyimpan perubahan. Silakan refresh halaman dan coba lagi.', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.classList.remove('btn-loading');
        btnText.textContent = 'Simpan Perubahan';
    }
}

        async function tryFormDataSubmission(form) {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData,
                credentials: 'same-origin'
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            let result;
            try {
                result = await response.json();
            } catch (e) {
                result = { message: 'Profil berhasil diperbarui!', email_changed: false };
            }
            // Handle validation errors
            if (result.errors) {
                handleValidationErrors(result.errors);
                showToast('Terdapat kesalahan pada form. Silakan periksa kembali.', 'error');
                return false;
            }
            // Success - Update UI
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                address: formData.get('address')
            };
            updateViewModeWithNewData(data, result.email_changed);
            exitEditMode();
            showToast(result.message || 'Profil berhasil diperbarui!', 'success');
            storeOriginalData();
            return true;
        }

    // Method 1: FormData with fetch
    async function tryFormDataSubmission(form) {
        const formData = new FormData(form);
        
        // Debug: Cek data yang dikirim
        console.log('FormData:', {
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            address: formData.get('address')
        });

        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData,
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        let result;
        try {
            result = await response.json();
        } catch (e) {
            result = { message: 'Profil berhasil diperbarui!' };
        }
        
        // Handle validation errors
        if (result.errors) {
            handleValidationErrors(result.errors);
            showToast('Terdapat kesalahan pada form. Silakan periksa kembali.', 'error');
            return false;
        }
        
        // Success - Update UI
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            address: formData.get('address')
        };
        updateViewModeWithNewData(data);
        exitEditMode();
        showToast(result.message || 'Profil berhasil diperbarui!', 'success');
        storeOriginalData();
        
        return true;
    }

    // Method 2: JSON submission
    async function tryJsonSubmission(form) {
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key === '_method' || key === '_token') continue;
            data[key] = value;
        }
        
        const response = await fetch(form.action, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data),
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        let result;
        try {
            result = await response.json();
        } catch (e) {
            result = { message: 'Profil berhasil diperbarui!' };
        }
        
        if (result.errors) {
            handleValidationErrors(result.errors);
            showToast('Terdapat kesalahan pada form. Silakan periksa kembali.', 'error');
            return false;
        }
        
        updateViewModeWithNewData(data);
        exitEditMode();
        showToast(result.message || 'Profil berhasil diperbarui!', 'success');
        storeOriginalData();
        
        return true;
    }

    // Method 3: Traditional form submission (fallback)
    async function tryTraditionalSubmission(form) {
        showToast('Menggunakan metode tradisional...', 'info');
        
        const ajaxField = document.createElement('input');
        ajaxField.type = 'hidden';
        ajaxField.name = 'ajax_fallback';
        ajaxField.value = '1';
        form.appendChild(ajaxField);
        
        form.submit();
    }

    // Enhanced error handling for fetch requests
    async function safeFetch(url, options = {}) {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
        
        try {
            const response = await fetch(url, {
                ...options,
                signal: controller.signal
            });
            clearTimeout(timeoutId);
            return response;
        } catch (error) {
            clearTimeout(timeoutId);
            if (error.name === 'AbortError') {
                throw new Error('Request timeout - koneksi terlalu lama');
            }
            throw error;
        }
    }

    // Update UI with new data
    function updateViewModeWithNewData(data, emailChanged = false) {
        if (data.name) {
            document.getElementById('displayNameValue').textContent = data.name;
            document.getElementById('displayName').textContent = data.name;
        }
        if (data.email) {
            document.getElementById('displayEmailValue').textContent = data.email;
            
            // Update badge status
            const badgeContainer = document.querySelector('.profile-header .d-flex.justify-content-center');
            let badge = badgeContainer.querySelector('.badge-custom');
            if (badge) badge.remove();

            const newBadge = document.createElement('span');
            newBadge.className = `badge-custom ${emailChanged ? 'badge-unverified' : 'badge-verified'}`;
            newBadge.innerHTML = emailChanged
                ? '<i class="fas fa-exclamation-triangle"></i> Email Belum Terverifikasi'
                : '<i class="fas fa-shield-check"></i> Email Terverifikasi';
            badgeContainer.appendChild(newBadge);
        }
        if (data.phone !== undefined) {
            document.getElementById('phoneDisplayValue').textContent = data.phone || 'Belum diisi';
        }
        if (data.address !== undefined) {
            document.getElementById('addressDisplayValue').textContent = data.address || 'Belum diisi';
        }
    }

    function handleValidationErrors(errors) {
        document.querySelectorAll('.form-control').forEach(input => {
            input.classList.remove('is-invalid');
            const errorDiv = input.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) errorDiv.remove();
        });
        
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errors[field][0];
                input.parentNode.appendChild(errorDiv);
            }
        });
    }

    // ===== VALIDATION =====
    function handleInputValidation(e) {
        const input = e.target;
        const isValid = validateField(input);
        
        input.classList.remove('is-invalid', 'is-valid');
        
        const errorDiv = input.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) errorDiv.remove();
        
        if (input.value.trim() && !isValid) {
            input.classList.add('is-invalid');
            
            const errorMsg = getValidationMessage(input);
            if (errorMsg) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errorMsg;
                input.parentNode.appendChild(errorDiv);
            }
        } else if (input.value.trim() && isValid) {
            input.classList.add('is-valid');
        }
    }

    function validateField(input) {
        const value = input.value.trim();
        const type = input.type;
        const name = input.name;
        const minLength = input.getAttribute('minlength');

        if (input.hasAttribute('required') && !value) {
            return false;
        }
        
        if (minLength && value.length < parseInt(minLength)) {
            return false;
        }
        
        // Email hanya @gmail.com
        if (name === 'email') {
            return /^[^\s@]+@gmail\.com$/i.test(value);
        }
        
        if (type === 'tel') {
            return /^[\+]?[0-9\s\-\(\)]+$/.test(value);
        }
        
        if (name === 'password_confirmation') {
            const password = document.querySelector('input[name="password"]')?.value;
            return value === password;
        }
        
        return true;
    }

    function getValidationMessage(input) {
        const value = input.value.trim();
        const name = input.name;
        const minLength = input.getAttribute('minlength');

        if (input.hasAttribute('required') && !value) {
            if (name === 'address') {
                return 'Alamat wajib diisi';
            }
            return 'Field ini wajib diisi';
        }

        if (minLength && value.length < parseInt(minLength)) {
            return `Minimal ${minLength} karakter`;
        }

        if (name === 'email') {
            if (!value.includes('@gmail.com')) {
                return 'Email harus menggunakan domain @gmail.com';
            }
            if (!/^[^\s@]+@gmail\.com$/i.test(value)) {
                return 'Format email tidak valid. Gunakan: contoh@gmail.com';
            }
        }

        if (type === 'tel') {
            return 'Format nomor telepon tidak valid';
        }

        if (name === 'password_confirmation') {
            return 'Konfirmasi password tidak cocok';
        }

        return 'Format tidak valid';
    }

    // ===== DATA MANAGEMENT =====
    function storeOriginalData() {
        const nameInput = document.getElementById('nameInput');
        const emailInput = document.getElementById('emailInput');
        const phoneInput = document.getElementById('phoneInput');
        const addressInput = document.getElementById('addressInput');

        originalFormData = {
            name: nameInput ? nameInput.value : '',
            email: emailInput ? emailInput.value : '',
            phone: phoneInput ? phoneInput.value : '',
            address: addressInput ? addressInput.value : ''
        };
    }

    function restoreOriginalData() {
        const nameInput = document.getElementById('nameInput');
        const emailInput = document.getElementById('emailInput');
        const phoneInput = document.getElementById('phoneInput');
        const addressInput = document.getElementById('addressInput');

        if (nameInput) nameInput.value = originalFormData.name || '';
        if (emailInput) emailInput.value = originalFormData.email || '';
        if (phoneInput) phoneInput.value = originalFormData.phone || '';
        if (addressInput) addressInput.value = originalFormData.address || '';

        document.querySelectorAll('.form-control').forEach(input => {
            input.classList.remove('is-invalid', 'is-valid');
            const errorDiv = input.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) errorDiv.remove();
        });
    }

    // ===== UTILITY FUNCTIONS =====
    function changePassword() {
        showPasswordModal();
    }

    async function sendVerificationEmail() {
        const button = event.target;
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
        
        try {
            const response = await safeFetch('/email/verification-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            let result;
            try {
                result = await response.json();
            } catch (e) {
                result = { message: 'Email verifikasi telah dikirim!' };
            }
            
            if (response.ok) {
                showToast('Email verifikasi telah dikirim!', 'success');
            } else {
                showToast(result.message || 'Gagal mengirim email verifikasi', 'error');
            }
        } catch (error) {
            console.error('Verification email error:', error);
            showToast('Terjadi kesalahan saat mengirim email verifikasi', 'error');
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    }

    function checkNetworkStatus() {
        if (!navigator.onLine) {
            showToast('Tidak ada koneksi internet. Periksa koneksi Anda.', 'error');
            return false;
        }
        return true;
    }

    window.addEventListener('online', () => {
        showToast('Koneksi internet tersambung kembali', 'success');
    });

    window.addEventListener('offline', () => {
        showToast('Koneksi internet terputus', 'error');
    });

    function logout() {
        if (confirm('Apakah Anda yakin ingin keluar?')) {
            showToast('Sedang logout...', 'info');
            setTimeout(() => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }, 1000);
        }
    }

    function closeAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }
    }

    function showToast(message, type = 'success') {
        const toastId = type + 'Toast';
        const toastElement = document.getElementById(toastId);
        const messageElement = document.getElementById(type + 'ToastMessage');
        
        if (toastElement && messageElement) {
            messageElement.textContent = message;
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 5000
            });
            toast.show();
        }
    }

    function handleKeyboardShortcuts(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
            e.preventDefault();
            toggleEditMode();
        }
        
        if ((e.ctrlKey || e.metaKey) && e.key === 's' && isEditMode) {
            e.preventDefault();
            document.getElementById('profileForm').dispatchEvent(new Event('submit'));
        }
        
        if (e.key === 'Escape' && isEditMode) {
            cancelEdit();
        }

        if (e.key === 'Escape' && passwordModal._isShown) {
            passwordModal.hide();
        }
    }

    // ===== ANIMATIONS =====
    function addAnimations() {
        const cards = document.querySelectorAll('.info-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.info-card, .alert').forEach(el => {
            observer.observe(el);
        });
        
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', createRippleEffect);
        });
    }

    function createRippleEffect(e) {
        const button = e.currentTarget;
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
            z-index: 0;
        `;
        
        if (!document.querySelector('#ripple-keyframes')) {
            const style = document.createElement('style');
            style.id = 'ripple-keyframes';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        button.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function announceChange(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;
        
        document.body.appendChild(announcement);
        setTimeout(() => announcement.remove(), 1000);
    }
</script>
</body>
</html>