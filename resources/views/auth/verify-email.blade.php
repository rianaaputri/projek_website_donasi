<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #6ba6f7;
            --light-blue: #e8f3ff;
            --ultra-light-blue: #f6faff;
            --medium-blue: #7db3f8;
            --soft-blue: #93c1f9;
            --deep-blue: #5a94e6;
            --accent-blue: #4c8bd4;
            --success-green: #4ade80;
            --warning-orange: #fb923c;
            --danger-red: #f87171;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --white-soft: #fefefe;
            --shadow-soft: rgba(107, 166, 247, 0.12);
            --shadow-medium: rgba(107, 166, 247, 0.18);
            --shadow-strong: rgba(107, 166, 247, 0.25);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--ultra-light-blue) 0%, var(--light-blue) 35%, #e1f0ff 70%, var(--ultra-light-blue) 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            position: relative;
            overflow-x: hidden;
            color: var(--text-primary);
            font-weight: 400;
            line-height: 1.6;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(107, 166, 247, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(147, 193, 249, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(232, 243, 255, 0.4) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .main-container {
            max-width: 680px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
            position: relative;
            z-index: 10;
        }

        .verification-card {
            background: rgba(254, 254, 254, 0.95);
            border-radius: 32px;
            box-shadow: 
                0 32px 80px var(--shadow-soft),
                0 16px 40px rgba(107, 166, 247, 0.08),
                0 0 0 1px rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transform: translateY(0);
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .verification-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 
                0 48px 120px var(--shadow-medium),
                0 24px 60px rgba(107, 166, 247, 0.12),
                0 0 0 1px rgba(255, 255, 255, 0.8);
        }

        .card-header {
            background: linear-gradient(135deg, 
                var(--primary-blue) 0%, 
                var(--medium-blue) 25%,
                var(--soft-blue) 75%, 
                var(--deep-blue) 100%);
            color: white;
            padding: 4rem 2.5rem;
            text-align: center;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: headerGlow 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes headerGlow {
            0%, 100% { 
                transform: rotate(0deg) scale(1);
                opacity: 0.3;
            }
            50% { 
                transform: rotate(180deg) scale(1.1);
                opacity: 0.7;
            }
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255,255,255,0.3) 50%, 
                transparent 100%);
        }

        .header-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            backdrop-filter: blur(16px);
            border: 3px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 2;
            box-shadow: 
                0 16px 32px rgba(0,0,0,0.1),
                inset 0 1px 0 rgba(255,255,255,0.3);
        }

        .header-icon i {
            font-size: 2.5rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .card-header h2 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.1);
            letter-spacing: -0.025em;
        }

        .card-header p {
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.9;
            margin-top: 0.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .alert-custom {
            border: none;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 12px 40px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
        }

        .alert-custom::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 5px;
            height: 100%;
            border-radius: 0 20px 20px 0;
        }

        .alert-info {
            background: linear-gradient(135deg, 
                rgba(232, 243, 255, 0.9) 0%, 
                rgba(246, 250, 255, 0.95) 100%);
            color: var(--deep-blue);
            border: 1px solid rgba(107, 166, 247, 0.15);
        }

        .alert-info::before {
            background: linear-gradient(180deg, var(--primary-blue), var(--deep-blue));
        }

        .alert-success {
            background: linear-gradient(135deg, 
                rgba(220, 252, 231, 0.9) 0%, 
                rgba(240, 253, 244, 0.95) 100%);
            color: #15803d;
            border: 1px solid rgba(74, 222, 128, 0.15);
        }

        .alert-success::before {
            background: linear-gradient(180deg, var(--success-green), #22c55e);
        }

        .alert-danger {
            background: linear-gradient(135deg, 
                rgba(254, 226, 226, 0.9) 0%, 
                rgba(255, 242, 242, 0.95) 100%);
            color: #dc2626;
            border: 1px solid rgba(248, 113, 113, 0.15);
        }

        .alert-danger::before {
            background: linear-gradient(180deg, var(--danger-red), #ef4444);
        }

        .alert-warning {
            background: linear-gradient(135deg, 
                rgba(254, 243, 199, 0.9) 0%, 
                rgba(255, 251, 235, 0.95) 100%);
            color: #d97706;
            border: 1px solid rgba(251, 146, 60, 0.15);
        }

        .alert-warning::before {
            background: linear-gradient(180deg, var(--warning-orange), #f59e0b);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--deep-blue) 100%);
            border: none;
            border-radius: 18px;
            padding: 16px 40px;
            font-weight: 600;
            font-size: 1.05rem;
            text-transform: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 
                0 8px 24px var(--shadow-soft),
                0 4px 12px rgba(107, 166, 247, 0.2);
            position: relative;
            overflow: hidden;
            color: white;
            letter-spacing: 0.025em;
            width: 100%;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255,255,255,0.25), 
                transparent);
            transition: left 0.6s ease;
        }

        .btn-primary-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 
                0 16px 40px var(--shadow-medium),
                0 8px 20px rgba(107, 166, 247, 0.3);
            background: linear-gradient(135deg, var(--deep-blue) 0%, var(--accent-blue) 100%);
            color: white;
        }

        .btn-primary-custom:active {
            transform: translateY(-1px) scale(1.01);
        }

        .countdown-timer {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.95) 0%, 
                rgba(248, 250, 252, 0.98) 100%);
            border-radius: 20px;
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.04);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .countdown-timer::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 5px;
            height: 100%;
            border-radius: 20px 0 0 20px;
        }

        .border-warning .countdown-timer::before { 
            background: linear-gradient(180deg, var(--warning-orange), #f59e0b);
        }
        
        .border-info .countdown-timer::before { 
            background: linear-gradient(180deg, var(--primary-blue), var(--deep-blue));
        }
        
        .btn-disabled {
            background: linear-gradient(135deg, #cbd5e1, #94a3b8) !important;
            color: #64748b !important;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }

        .btn-disabled:hover {
            background: linear-gradient(135deg, #cbd5e1, #94a3b8) !important;
            color: #64748b !important;
            transform: none !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }

        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
        }

        .toast-custom {
            background: var(--white-soft);
            border-left: 5px solid var(--danger-red);
            box-shadow: 0 16px 48px rgba(0,0,0,0.12);
            border-radius: 16px;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .pulse-animation {
            animation: pulse 2.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 8px 32px rgba(0,0,0,0.04);
            }
            50% { 
                transform: scale(1.02);
                box-shadow: 0 12px 40px rgba(251, 146, 60, 0.15);
            }
        }

        .timer-display {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--deep-blue);
            text-shadow: 0 1px 2px rgba(0,0,0,0.05);
            letter-spacing: 0.05em;
        }

        .floating-elements {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
            top: 0;
            left: 0;
        }

        .floating-circle {
            position: absolute;
            background: rgba(107, 166, 247, 0.06);
            border-radius: 50%;
            animation: float 12s ease-in-out infinite;
            border: 1px solid rgba(147, 193, 249, 0.1);
            backdrop-filter: blur(2px);
        }

        .circle-1 { 
            width: 140px; 
            height: 140px; 
            top: 12%; 
            left: 6%; 
            animation-delay: 0s; 
        }
        .circle-2 { 
            width: 200px; 
            height: 200px; 
            top: 60%; 
            right: 5%; 
            animation-delay: 3s; 
        }
        .circle-3 { 
            width: 120px; 
            height: 120px; 
            bottom: 20%; 
            left: 12%; 
            animation-delay: 6s; 
        }
        .circle-4 { 
            width: 160px; 
            height: 160px; 
            top: 35%; 
            right: 20%; 
            animation-delay: 9s; 
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg) scale(1); 
                opacity: 0.4;
            }
            25% { 
                transform: translateY(-25px) rotate(90deg) scale(1.05); 
                opacity: 0.6;
            }
            50% { 
                transform: translateY(-40px) rotate(180deg) scale(1.1); 
                opacity: 0.8;
            }
            75% { 
                transform: translateY(-15px) rotate(270deg) scale(0.95); 
                opacity: 0.5;
            }
        }

        .icon-pulse {
            animation: iconPulse 3s infinite ease-in-out;
        }

        @keyframes iconPulse {
            0%, 100% { 
                transform: scale(1); 
                filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            }
            50% { 
                transform: scale(1.08); 
                filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));
            }
        }

        .card-body {
            padding: 3rem;
            position: relative;
            z-index: 2;
        }

        .text-muted-custom {
            color: var(--text-muted) !important;
        }

        .info-box {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.9) 0%, 
                rgba(248, 250, 252, 0.95) 100%);
            border: 1px solid rgba(107, 166, 247, 0.08);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            animation: statusBlink 2.5s infinite;
            box-shadow: 0 0 8px currentColor;
        }

        .status-active { 
            background-color: var(--success-green);
            box-shadow: 0 0 12px rgba(74, 222, 128, 0.4);
        }
        .status-warning { 
            background-color: var(--warning-orange);
            box-shadow: 0 0 12px rgba(251, 146, 60, 0.4);
        }
        .status-danger { 
            background-color: var(--danger-red);
            box-shadow: 0 0 12px rgba(248, 113, 113, 0.4);
        }

        @keyframes statusBlink {
            0%, 100% { 
                opacity: 1; 
                transform: scale(1);
            }
            50% { 
                opacity: 0.6; 
                transform: scale(1.1);
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(30px) scale(0.95);
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1);
            }
        }

        .text-success { color: var(--success-green) !important; }
        .text-warning { color: var(--warning-orange) !important; }
        .text-danger { color: var(--danger-red) !important; }
        .text-info { color: var(--primary-blue) !important; }

        /* Enhanced responsive design */
        @media (max-width: 768px) {
            .main-container { 
                padding: 1.5rem 1rem; 
            }
            
            .card-header { 
                padding: 2.5rem 1.5rem; 
            }
            
            .card-body { 
                padding: 2rem 1.5rem; 
            }
            
            .header-icon { 
                width: 80px; 
                height: 80px; 
            }
            
            .header-icon i {
                font-size: 2rem;
            }
            
            .card-header h2 {
                font-size: 1.85rem;
            }
            
            .timer-display { 
                font-size: 1.25rem; 
            }
            
            .countdown-timer {
                padding: 1.25rem;
            }
            
            .alert-custom {
                padding: 1.5rem;
            }

            .floating-circle {
                display: none; /* Hide floating elements on mobile for better performance */
            }
        }

        @media (max-width: 480px) {
            .verification-card {
                border-radius: 24px;
                margin: 0 0.5rem;
            }
            
            .card-header {
                padding: 2rem 1rem;
            }
            
            .card-body {
                padding: 1.5rem 1rem;
            }
        }

        /* Better focus states for accessibility */
        .btn-primary-custom:focus {
            outline: none;
            box-shadow: 
                0 8px 24px var(--shadow-soft),
                0 4px 12px rgba(107, 166, 247, 0.2),
                0 0 0 3px rgba(107, 166, 247, 0.3);
        }

        .btn:focus-visible {
            outline: 2px solid var(--primary-blue);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-circle circle-1"></div>
        <div class="floating-circle circle-2"></div>
        <div class="floating-circle circle-3"></div>
        <div class="floating-circle circle-4"></div>
    </div>

    <div class="main-container">
        <div class="card verification-card fade-in">
            <div class="card-header">
                <div class="header-icon icon-pulse">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h2 class="mb-0">Verifikasi Email</h2>
                <p class="mb-0 mt-2">Langkah terakhir untuk mengaktifkan akun kamu</p>
            </div>

            <div class="card-body">
                <!-- Laravel Session Alerts -->
                @if (session('error'))
                    @if (str_contains(session('error'), 'kadaluarsa'))
                        <!-- Expired Link Alert -->
                        <div class="alert alert-custom alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-1">
                                        <span class="status-indicator status-warning"></span>
                                        Oops! Link Sudah Kedaluwarsa
                                    </h5>
                                    <p class="mb-2">{{ session('error') }}</p>
                                    <small class="text-muted">Tenang aja, tinggal minta link baru di bawah ini!</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Other errors -->
                        <div class="alert alert-custom alert-danger">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-1">
                                        <span class="status-indicator status-danger"></span>
                                        Ada Masalah Nih
                                    </h5>
                                    <p class="mb-0">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif (session('resent') || session('status'))
                    <div class="alert alert-custom alert-success">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">
                                    <span class="status-indicator status-active"></span>
                                    Sip! Link verifikasi baru udah dikirim ke email kamu.
                                </h6>
                                <small>{{ session('status') ?? 'Cek inbox atau folder spam ya!' }}</small>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Normal message when no session alerts -->
                    <div class="alert alert-custom alert-info">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
                            <div>
                                <h6 class="mb-2">
                                    <span class="status-indicator status-warning"></span>
                                    Verifikasi Email Diperlukan
                                </h6>
                                <p class="mb-3">Makasih udah daftar! Sebelum mulai, bisa verifikasi email address kamu dulu ga dengan klik link yang udah kita kirim ke email kamu?</p>

                                <div class="info-box">
                                    <small class="text-muted-custom">
                                        <i class="fas fa-lightbulb me-2"></i>
                                        <strong>Tips:</strong> Link verifikasi cuma valid selama 5 menit aja ya! Kalo belum dapet emailnya atau udah expired, tinggal klik tombol "Kirim Ulang" di bawah.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($user))
                    <!-- User Info -->
                    <div class="info-box mb-4">
                        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="mb-0"><small class="text-muted-custom">Daftar pada: {{ $user->created_at->format('d M Y H:i') }} WIB</small></p>
                    </div>
                @endif

                <!-- Countdown Timers -->
                <div class="row g-3 mb-4">
                    <!-- Link Expiry Countdown -->
                    <div class="col-md-6">
                        <div class="countdown-timer border border-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hourglass-half me-3 text-warning fa-lg"></i>
                                <div>
                                    <p class="mb-1 fw-semibold">Link akan kedaluwarsa dalam:</p>
                                    <span id="expiryCountdown" class="timer-display text-success">4m 30s</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resend Cooldown -->
                    <div class="col-md-6">
                        <div id="cooldownSection" class="countdown-timer border border-info" style="display: none;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-3 text-info fa-lg"></i>
                                <div>
                                    <p class="mb-1 fw-semibold">Kirim ulang tersedia dalam:</p>
                                    <span id="cooldownTimer" class="timer-display text-info">2m 00s</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <form id="resendForm" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <div class="d-flex justify-content-center mt-4">
                        <button id="resendBtn" type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-paper-plane me-2"></i>
                            <span id="btnText">Kirim Ulang Email Verifikasi</span>
                        </button>
                    </div>
                </form>

                <!-- Hidden logout form (removed as requested) -->

                <!-- Progress indicator -->
                <div class="text-center mt-4">
                    <small class="text-muted-custom">
                        <i class="fas fa-shield-alt me-1"></i>
                        Email kamu akan digunakan untuk keamanan akun dan notifikasi penting
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container for notifications -->
    <div class="toast-container">
        <div id="expiredToast" class="toast toast-custom" role="alert">
            <div class="toast-header">
                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                <strong class="me-auto">Link Kedaluwarsa!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Oops! Link verifikasi sudah kedaluwarsa. Silakan minta link baru ya!
            </div>
        </div>
@if (session('warning'))
    <div class="alert alert-custom alert-warning">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div>
                <h5 class="mb-1">
                    <span class="status-indicator status-warning"></span>
                    Verifikasi Diperlukan
                </h5>
                <p class="mb-0">{{ session('warning') }}</p>
            </div>
        </div>
    </div>
@endif

        <div id="successToast" class="toast" role="alert" style="border-left-color: var(--success-green);">
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Email Terkirim!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Link verifikasi baru sudah dikirim ke email kamu. Cek inbox ya!
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration - these would come from server in real app
            const config = {
                linkExpiresAt: new Date(Date.now() + 5 * 60 * 1000), // 5 minutes from now
                canResendAt: @if(session('last_resent_at'))
                    new Date({{ (strtotime(session('last_resent_at')) + 120) * 1000 }}) // 2 minutes after last resend
                @else
                    new Date() // Can resend immediately if no previous resend
                @endif,
                isLinkExpired: {{ session('error') && str_contains(session('error'), 'kadaluarsa') ? 'true' : 'false' }}
            };

            const elements = {
                expiryCountdown: document.getElementById('expiryCountdown'),
                cooldownTimer: document.getElementById('cooldownTimer'),
                cooldownSection: document.getElementById('cooldownSection'),
                resendBtn: document.getElementById('resendBtn'),
                btnText: document.getElementById('btnText'),
                resendForm: document.getElementById('resendForm'),
                expiredToast: document.getElementById('expiredToast'),
                successToast: document.getElementById('successToast')
            };

            // Show expired toast if link was expired
            if (config.isLinkExpired) {
                const toast = new bootstrap.Toast(elements.expiredToast);
                toast.show();
            }

            // Show success toast if email was resent
            @if(session('resent') || session('status'))
            const successToast = new bootstrap.Toast(elements.successToast);
            successToast.show();
            @endif

            function updateExpiryCountdown() {
                const now = new Date().getTime();
                const distance = config.linkExpiresAt.getTime() - now;

                if (distance <= 0) {
                    elements.expiryCountdown.innerHTML = '<span class="text-danger fw-bold">KEDALUWARSA</span>';
                    elements.expiryCountdown.closest('.countdown-timer').classList.add('pulse-animation');
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                let display = '';
                if (hours > 0) {
                    display = `${hours}j ${minutes}m ${seconds}s`;
                } else {
                    display = `${minutes}m ${seconds}s`;
                }
                
                elements.expiryCountdown.textContent = display;

                // Change color based on remaining time
                if (distance <= 1 * 60 * 1000) { // Less than 1 minute
                    elements.expiryCountdown.className = 'timer-display text-danger';
                } else if (distance <= 2 * 60 * 1000) { // Less than 2 minutes
                    elements.expiryCountdown.className = 'timer-display text-warning';
                } else {
                    elements.expiryCountdown.className = 'timer-display text-success';
                }
            }

            function updateResendCooldown() {
                const now = new Date().getTime();
                const distance = config.canResendAt.getTime() - now;

                if (distance <= 0) {
                    // Can resend now
                    elements.cooldownSection.style.display = 'none';
                    elements.resendBtn.disabled = false;
                    elements.resendBtn.classList.remove('btn-disabled');
                    elements.btnText.textContent = 'Kirim Ulang Email Verifikasi';
                    return;
                }

                // Still in cooldown
                elements.cooldownSection.style.display = 'block';
                elements.resendBtn.disabled = true;
                elements.resendBtn.classList.add('btn-disabled');

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                elements.cooldownTimer.textContent = `${minutes}m ${seconds}s`;
                elements.btnText.textContent = `Tunggu ${minutes}m ${seconds}s`;
            }

            // Update countdowns every second
            updateExpiryCountdown();
            updateResendCooldown();
            
            setInterval(updateExpiryCountdown, 1000);
            setInterval(updateResendCooldown, 1000);

            // Handle form submission
            elements.resendForm.addEventListener('submit', function(e) {
                const now = new Date().getTime();
                
                // Check if still in cooldown
                if (config.canResendAt.getTime() > now) {
                    e.preventDefault();
                    const remainingSeconds = Math.ceil((config.canResendAt.getTime() - now) / 1000);
                    
                    // Show toast instead of alert for better UX
                    const toastElement = document.createElement('div');
                    toastElement.className = 'toast toast-custom';
                    toastElement.innerHTML = `
                        <div class="toast-header">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <strong class="me-auto">Tunggu Sebentar!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            Tunggu ${remainingSeconds} detik lagi untuk mengirim ulang email.
                        </div>
                    `;
                    document.querySelector('.toast-container').appendChild(toastElement);
                    const toast = new bootstrap.Toast(toastElement);
                    toast.show();
                    
                    // Remove toast element after it's hidden
                    toastElement.addEventListener('hidden.bs.toast', function() {
                        toastElement.remove();
                    });
                    
                    return;
                }

                // Show loading state
                elements.resendBtn.disabled = true;
                elements.resendBtn.classList.add('btn-disabled');
                elements.btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
                
                // Update cooldown time for next request (2 minutes)
                config.canResendAt = new Date(Date.now() + 2 * 60 * 1000);
            });

            // Enhanced keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.ctrlKey) {
                    // Ctrl+Enter to resend
                    if (!elements.resendBtn.disabled) {
                        elements.resendForm.dispatchEvent(new Event('submit'));
                    }
                }
            });

            // Simulate checking verification status every 10 seconds
            // In real app, this would be an AJAX call to check verification status
            setInterval(function() {
                // If verified, redirect to dashboard
                // This would be implemented based on your backend logic
            }, 10000);

            // Add smooth scrolling for better UX
            window.addEventListener('load', function() {
                document.body.style.opacity = '1';
            });

            // Enhanced accessibility - announce important changes to screen readers
            function announceToScreenReader(message) {
                const announcement = document.createElement('div');
                announcement.setAttribute('aria-live', 'polite');
                announcement.setAttribute('aria-atomic', 'true');
                announcement.className = 'sr-only position-absolute';
                announcement.textContent = message;
                document.body.appendChild(announcement);
                
                setTimeout(() => {
                    document.body.removeChild(announcement);
                }, 1000);
            }

            // Announce when countdown changes to critical state
            let lastAnnouncedState = null;
            setInterval(function() {
                const now = new Date().getTime();
                const distance = config.linkExpiresAt.getTime() - now;
                
                if (distance <= 1 * 60 * 1000 && distance > 0 && lastAnnouncedState !== 'critical') {
                    announceToScreenReader('Peringatan: Link verifikasi akan kedaluwarsa dalam kurang dari 1 menit');
                    lastAnnouncedState = 'critical';
                } else if (distance <= 0 && lastAnnouncedState !== 'expired') {
                    announceToScreenReader('Link verifikasi telah kedaluwarsa. Silakan kirim ulang email verifikasi.');
                    lastAnnouncedState = 'expired';
                }
            }, 5000);
        });
    </script>
</body>
</html>