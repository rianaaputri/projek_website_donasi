<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #059669;
            --danger: #dc2626;
            --warning: #d97706;
            --info: #0891b2;
            --light: #f8fafc;
            --dark: #0f172a;
            --white: #ffffff;
            
            --gradient-primary: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
            --gradient-bg: linear-gradient(135deg, #f1f5f9 0%, #ffffff 100%);
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            --border-radius: 0.5rem;
            --border-radius-lg: 0.75rem;
            --border-radius-xl: 1rem;
            
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== GLOBAL STYLES ===== */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            background: var(--gradient-bg);
            color: var(--dark);
            line-height: 1.6;
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
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        .animate-fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1) both;
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
            letter-spacing: -0.025em;
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
            border: 1px solid rgb(226 232 240);
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
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 4px solid rgba(255, 255, 255, 0.3);
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
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.875rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
            transition: var(--transition);
        }

        .badge-verified {
            background: rgba(5, 150, 105, 0.2);
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .badge-unverified {
            background: rgba(217, 119, 6, 0.2);
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            min-width: 120px;
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
            backdrop-filter: blur(10px);
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
            background: #047857;
            border-color: #047857;
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
            border: 1px solid rgb(226 232 240);
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
            font-weight: 600;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgb(241 245 249);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .info-item {
            margin-bottom: 1.5rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 500;
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
            background: rgb(248 250 252);
            border-radius: var(--border-radius);
            border: 2px solid transparent;
            min-height: 3rem;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        /* ===== FORM STYLES ===== */
        .form-control {
            border: 2px solid rgb(226 232 240);
            border-radius: var(--border-radius);
            padding: 1rem;
            font-size: 1rem;
            transition: var(--transition);
            background: var(--white);
            font-weight: 500;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: var(--white);
            outline: none;
        }

        .form-control::placeholder {
            color: rgb(148 163 184);
            font-weight: 400;
        }

        /* ===== ALERTS ===== */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            border-left: 4px solid;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: rgba(5, 150, 105, 0.1);
            color: var(--success);
            border-left-color: var(--success);
        }

        .alert-danger {
            background: rgba(220, 38, 38, 0.1);
            color: var(--danger);
            border-left-color: var(--danger);
        }

        .alert-info {
            background: rgba(8, 145, 178, 0.1);
            color: var(--info);
            border-left-color: var(--info);
        }

        /* ===== DROPDOWN ===== */
        .dropdown-menu {
            border: 1px solid rgb(226 232 240);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            background: var(--white);
            padding: 0.5rem;
            backdrop-filter: blur(10px);
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
            background: rgba(220, 38, 38, 0.1);
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
        }

        /* ===== UTILITY CLASSES ===== */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
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
                                    <h2 class="profile-name">{{ Auth::user()->name }}</h2>
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
                                                <li><a class="dropdown-item" href="#" onclick="changePassword()">
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
                        <form id="profileForm">
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
                                                <div class="info-value">{{ Auth::user()->name }}</div>
                                            </div>
                                            <div class="edit-mode d-none">
                                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                                            </div>
                                        </div>

                                        <!-- Email Field -->
                                        <div class="info-item mode-transition">
                                            <div class="info-label">Alamat Email</div>
                                            <div class="view-mode">
                                                <div class="info-value">{{ Auth::user()->email }}</div>
                                            </div>
                                            <div class="edit-mode d-none">
                                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                                            </div>
                                        </div>

                                        <!-- Hapus Nomor Telepon dan Alamat -->
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

                                        <!-- Password Section (Edit Mode Only) -->
                                        <div class="edit-mode d-none" id="passwordSection">
                                            <div class="mt-4 p-3 bg-light rounded-3 border">
                                                <h6 class="fw-semibold text-secondary mb-3">
                                                    <i class="fas fa-lock me-2 text-primary"></i>
                                                    Keamanan Akun
                                                </h6>
                                                <div class="info-item">
                                                    <div class="info-label">Password Baru</div>
                                                    <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-label">Konfirmasi Password</div>
                                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi password baru">
                                                </div>
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
        let formData = {};
        
        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();
            setupEventListeners();
            addAnimations();
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

        function setupEventListeners() {
            // Form submission
            document.getElementById('profileForm').addEventListener('submit', handleFormSubmit);
            
            // Input validation
            document.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('input', handleInputValidation);
                input.addEventListener('blur', handleInputValidation);
            });
            
            // Keyboard shortcuts
            document.addEventListener('keydown', handleKeyboardShortcuts);
            
            // Auto-save draft (every 30 seconds in edit mode)
            setInterval(() => {
                if (isEditMode) {
                    saveDraft();
                }
            }, 30000);
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
                // Exit edit mode
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
            // Restore original data
            restoreOriginalData();
            exitEditMode();
            showToast('Perubahan dibatalkan', 'info');
        }

        // ===== FORM HANDLING =====
        function handleFormSubmit(e) {
            e.preventDefault();
            
            const saveBtn = document.getElementById('saveBtn');
            const btnText = saveBtn.querySelector('.btn-text');
            
            // Show loading state
            saveBtn.disabled = true;
            saveBtn.classList.add('btn-loading');
            btnText.textContent = 'Menyimpan...';
            
            // Simulate API call
            setTimeout(() => {
                // Reset button state
                saveBtn.disabled = false;
                saveBtn.classList.remove('btn-loading');
                btnText.textContent = 'Simpan Perubahan';
                
                // Update view mode with new data
                updateViewMode();
                
                // Exit edit mode
                exitEditMode();
                
                // Show success message
                showToast('Profil berhasil diperbarui!', 'success');
                
                // Update stored data
                storeOriginalData();
                
            }, 2000);
        }

        function updateViewMode() {
            const formData = new FormData(document.getElementById('profileForm'));
            
            // Update name
            const nameValue = document.querySelector('.view-mode .info-value');
            if (formData.get('name')) {
                nameValue.textContent = formData.get('name');
                document.querySelector('.profile-name').textContent = formData.get('name');
            }
            
            // Update other fields similarly...
        }

        // ===== VALIDATION =====
        function handleInputValidation(e) {
            const input = e.target;
            const isValid = validateField(input);
            
            if (isValid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-valid');
            }
        }

        function validateField(input) {
            const value = input.value.trim();
            const type = input.type;
            const name = input.name;
            
            if (input.hasAttribute('required') && !value) {
                return false;
            }
            
            switch (type) {
                case 'email':
                    return !value || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                case 'tel':
                    return !value || /^[\+]?[0-9\s\-\(\)]+$/.test(value);
                default:
                    if (name === 'password_confirmation') {
                        const password = document.querySelector('input[name="password"]').value;
                        return !value || value === password;
                    }
                    return true;
            }
        }

        // ===== DATA MANAGEMENT =====
        function storeOriginalData() {
            const form = document.getElementById('profileForm');
            formData = new FormData(form);
        }

        function restoreOriginalData() {
            // Restore form values from stored data
            for (let [key, value] of formData.entries()) {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = value;
                }
            }
        }

        function saveDraft() {
            // Auto-save functionality (you can implement local storage here)
            console.log('Draft saved automatically');
        }

        // ===== UTILITY FUNCTIONS =====
        function changePassword() {
            if (!isEditMode) {
                toggleEditMode();
            }
            
            setTimeout(() => {
                const passwordSection = document.getElementById('passwordSection');
                if (passwordSection) {
                    passwordSection.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    
                    const passwordInput = passwordSection.querySelector('input[name="password"]');
                    if (passwordInput) {
                        passwordInput.focus();
                        passwordInput.classList.add('animate-pulse');
                        setTimeout(() => passwordInput.classList.remove('animate-pulse'), 1000);
                    }
                }
            }, 500);
        }

        function sendVerificationEmail() {
            showToast('Email verifikasi telah dikirim!', 'success');
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                showToast('Sedang logout...', 'info');
                setTimeout(() => {
                    window.location.href = '/';
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

        // ===== TOAST NOTIFICATIONS =====
        function showToast(message, type = 'success') {
            const toastId = type + 'Toast';
            const toastElement = document.getElementById(toastId);
            const messageElement = document.getElementById(type + 'ToastMessage');
            
            if (toastElement && messageElement) {
                messageElement.textContent = message;
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
            }
        }

        // ===== KEYBOARD SHORTCUTS =====
        function handleKeyboardShortcuts(e) {
            // Ctrl/Cmd + E to toggle edit mode
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                toggleEditMode();
            }
            
            // Ctrl/Cmd + S to save (only in edit mode)
            if ((e.ctrlKey || e.metaKey) && e.key === 's' && isEditMode) {
                e.preventDefault();
                document.getElementById('profileForm').dispatchEvent(new Event('submit'));
            }
            
            // Escape to cancel edit
            if (e.key === 'Escape' && isEditMode) {
                cancelEdit();
            }
        }

        // ===== ANIMATIONS =====
        function addAnimations() {
            // Stagger animations for cards
            const cards = document.querySelectorAll('.info-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Add scroll animations
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
            
            // Add ripple effect to buttons
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
            
            // Add ripple keyframes if not exists
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

        // ===== PERFORMANCE OPTIMIZATIONS =====
        
        // Debounce function for input validation
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

        // Throttle function for scroll events
        function throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            }
        }

        // Apply debounced validation
        document.addEventListener('DOMContentLoaded', function() {
            const debouncedValidation = debounce(handleInputValidation, 300);
            document.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('input', debouncedValidation);
            });
        });

        // ===== ACCESSIBILITY ENHANCEMENTS =====
        
        // Focus management
        function manageFocus() {
            const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
            const modal = document.querySelector('.modal');
            
            if (modal && modal.classList.contains('show')) {
                const focusableContent = modal.querySelectorAll(focusableElements);
                const firstFocusableElement = focusableContent[0];
                const lastFocusableElement = focusableContent[focusableContent.length - 1];

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        if (e.shiftKey) {
                            if (document.activeElement === firstFocusableElement) {
                                lastFocusableElement.focus();
                                e.preventDefault();
                            }
                        } else {
                            if (document.activeElement === lastFocusableElement) {
                                firstFocusableElement.focus();
                                e.preventDefault();
                            }
                        }
                    }
                });
            }
        }

        // Announce changes to screen readers
        function announceChange(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = message;
            
            document.body.appendChild(announcement);
            setTimeout(() => announcement.remove(), 1000);
        }

        // Enhanced form validation with accessibility
        function validateFormAccessibility() {
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input, textarea, select');
            let isValid = true;
            
            inputs.forEach(input => {
                const isFieldValid = validateField(input);
                
                if (!isFieldValid) {
                    input.setAttribute('aria-invalid', 'true');
                    input.setAttribute('aria-describedby', input.name + '-error');
                    isValid = false;
                } else {
                    input.removeAttribute('aria-invalid');
                    input.removeAttribute('aria-describedby');
                }
            });
            
            return isValid;
        }
    </script>
</body>
</html>