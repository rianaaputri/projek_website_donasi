<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --dark-blue: #1e40af;
            --gradient-start: #60a5fa;
            --gradient-end: #3b82f6;
            --success-green: #22c55e;
            --warning-orange: #f59e0b;
            --danger-red: #ef4444;
        }

        body {
            background: linear-gradient(135deg, var(--light-blue) 0%, #f0f9ff 50%, #e0f2fe 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        .main-container {
            max-width: 650px;
            margin: 0 auto;
            padding: 2rem 1rem;
            position: relative;
            z-index: 10;
        }

        .verification-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            box-shadow: 
                0 25px 50px rgba(59, 130, 246, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            overflow: hidden;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .verification-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 35px 70px rgba(59, 130, 246, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.3);
        }

        .card-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end), var(--dark-blue));
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .header-icon {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 2;
        }

        .alert-custom {
            border: none;
            border-radius: 16px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .alert-custom::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        .alert-info::before {
            background: var(--primary-blue);
        }

        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }

        .alert-success::before {
            background: var(--success-green);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .alert-danger::before {
            background: var(--danger-red);
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .alert-warning::before {
            background: var(--warning-orange);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border: none;
            border-radius: 14px;
            padding: 14px 32px;
            font-weight: 600;
            text-transform: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, var(--gradient-end), var(--dark-blue));
        }

        .btn-outline-custom {
            color: #64748b;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 28px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.5);
        }

        .btn-outline-custom:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #475569;
            transform: translateY(-2px);
        }

        .countdown-timer {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 16px;
            padding: 1.75rem;
            margin-top: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .countdown-timer::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--warning-orange);
        }

        .timer-display {
            font-family: 'Courier New', monospace;
            font-size: 1.4rem;
            font-weight: bold;
            color: #92400e;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
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
            background: rgba(59, 130, 246, 0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .circle-1 { 
            width: 120px; 
            height: 120px; 
            top: 15%; 
            left: 8%; 
            animation-delay: 0s; 
        }
        .circle-2 { 
            width: 180px; 
            height: 180px; 
            top: 65%; 
            right: 8%; 
            animation-delay: 2.5s; 
        }
        .circle-3 { 
            width: 100px; 
            height: 100px; 
            bottom: 25%; 
            left: 15%; 
            animation-delay: 5s; 
        }
        .circle-4 { 
            width: 140px; 
            height: 140px; 
            top: 40%; 
            right: 25%; 
            animation-delay: 7s; 
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg) scale(1); 
                opacity: 0.6;
            }
            33% { 
                transform: translateY(-30px) rotate(120deg) scale(1.1); 
                opacity: 0.8;
            }
            66% { 
                transform: translateY(-10px) rotate(240deg) scale(0.9); 
                opacity: 0.4;
            }
        }

        .icon-pulse {
            animation: iconPulse 2.5s infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .card-body {
            padding: 2.5rem;
            position: relative;
            z-index: 2;
        }

        .text-muted-custom {
            color: #64748b !important;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 12px;
            padding: 1.25rem;
            backdrop-filter: blur(5px);
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: statusBlink 2s infinite;
        }

        .status-active { background-color: var(--success-green); }
        .status-warning { background-color: var(--warning-orange); }
        .status-danger { background-color: var(--danger-red); }

        @keyframes statusBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .card-header {
                padding: 2rem 1.5rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .header-icon {
                width: 70px;
                height: 70px;
            }
            
            .timer-display {
                font-size: 1.2rem;
            }
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
                    <i class="fas fa-envelope-open-text fa-2x"></i>
                </div>
                <h2 class="mb-0">Verifikasi Email</h2>
                <p class="mb-0 mt-2 opacity-90">Langkah terakhir untuk mengaktifkan akun kamu</p>
            </div>

            <div class="card-body">
                <!-- Alert untuk berbagai kondisi -->
                <div id="alertContainer">
                    <!-- Verifikasi gagal -->
                    <div class="alert alert-custom alert-danger d-none" id="verificationFailed">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">
                                    <span class="status-indicator status-danger"></span>
                                    Verifikasi Gagal
                                </h5>
                                <p class="mb-0">Terjadi kesalahan saat menyimpan tanggal verifikasi. Silakan coba lagi atau hubungi admin.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Link expired -->
                    <div class="alert alert-custom alert-danger d-none" id="linkExpired">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">
                                    <span class="status-indicator status-danger"></span>
                                    Link Verifikasi Kedaluwarsa
                                </h5>
                                <p class="mb-0">Link verifikasi sudah tidak berlaku. Silakan minta link baru dengan klik tombol di bawah.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email terkirim -->
                    <div class="alert alert-custom alert-success d-none" id="emailSent">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">
                                    <span class="status-indicator status-active"></span>
                                    Sip! Link verifikasi baru udah dikirim ke email kamu.
                                </h6>
                                <small>Cek inbox atau folder spam ya!</small>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan normal -->
                    <div class="alert alert-custom alert-info" id="normalMessage">
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
                </div>

                <!-- Countdown Timer -->
                <div id="countdownSection" class="countdown-timer">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 fw-semibold">
                                <i class="fas fa-hourglass-half me-2"></i>
                                Sisa waktu:
                            </p>
                            <span id="countdown" class="timer-display">05m 00s</span>
                        </div>
                        <div class="text-end">
                            <small class="text-muted-custom">
                                Berakhir pada:<br>
                                <strong id="expiryTime">--:--</strong>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                    <button id="resendBtn" type="button" class="btn btn-primary-custom flex-fill">
                        <i class="fas fa-paper-plane me-2"></i>
                        <span>Kirim Ulang Email Verifikasi</span>
                    </button>
                    <button id="logoutBtn" type="button" class="btn btn-outline-custom">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Keluar
                    </button>
                </div>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simulasi data - dalam implementasi nyata, ini akan datang dari server
            const mockData = {
                hasError: false,
                errorType: null, // 'verification_failed', 'expired', null
                isResent: false,
                expiresAt: new Date(Date.now() + 5 * 60 * 1000) // 5 menit dari sekarang
            };

            // Handle different alert states
            function showAlert(type) {
                // Hide all alerts first
                document.querySelectorAll('.alert-custom').forEach(alert => {
                    alert.classList.add('d-none');
                });

                // Show specific alert
                const alertMap = {
                    'verification_failed': 'verificationFailed',
                    'expired': 'linkExpired',
                    'resent': 'emailSent',
                    'normal': 'normalMessage'
                };

                const targetAlert = document.getElementById(alertMap[type]);
                if (targetAlert) {
                    targetAlert.classList.remove('d-none');
                    targetAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }

            // Initialize alerts based on state
            if (mockData.hasError) {
                showAlert(mockData.errorType);
                document.getElementById('countdownSection').style.display = 'none';
            } else if (mockData.isResent) {
                showAlert('resent');
                setTimeout(() => showAlert('normal'), 5000); // Show normal message after 5 seconds
            } else {
                showAlert('normal');
            }

            // Countdown timer
            if (mockData.expiresAt && !mockData.hasError) {
                const countdownEl = document.getElementById('countdown');
                const expiryTimeEl = document.getElementById('expiryTime');
                
                // Set expiry time display
                expiryTimeEl.textContent = mockData.expiresAt.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZone: 'Asia/Jakarta'
                }) + ' WIB';

                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = mockData.expiresAt.getTime() - now;

                    if (distance <= 0) {
                        countdownEl.innerHTML = '<span class="text-danger fw-bold">KEDALUWARSA</span>';
                        showAlert('expired');
                        document.getElementById('countdownSection').style.display = 'none';
                        return;
                    }

                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    countdownEl.textContent = String(minutes).padStart(2, '0') + 'm ' + 
                                            String(seconds).padStart(2, '0') + 's';

                    // Change color when less than 1 minute
                    if (minutes === 0 && seconds <= 30) {
                        countdownEl.classList.add('text-danger');
                    } else if (minutes === 0) {
                        countdownEl.classList.add('text-warning');
                    }
                }

                updateCountdown();
                const countdownInterval = setInterval(updateCountdown, 1000);
            }

            // Button event handlers
            document.getElementById('resendBtn').addEventListener('click', function() {
                const button = this;
                const originalText = button.innerHTML;
                
                // Show loading state
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';

                // Simulate API call
                setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    showAlert('resent');
                    
                    // Reset countdown with new expiry time
                    mockData.expiresAt = new Date(Date.now() + 5 * 60 * 1000);
                    document.getElementById('countdownSection').style.display = 'block';
                    location.reload(); // In real app, you might want to restart countdown instead
                }, 2000);
            });

            document.getElementById('logoutBtn').addEventListener('click', function() {
                if (confirm('Yakin mau keluar? Kamu perlu login lagi nanti untuk verifikasi email.')) {
                    // In real app, this would submit logout form
                    alert('Logout functionality would be implemented here');
                }
            });
        });
    </script>
</body>
</html>