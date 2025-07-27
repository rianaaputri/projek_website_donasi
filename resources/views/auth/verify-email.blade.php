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
        }

        body {
            background: linear-gradient(135deg, var(--light-blue) 0%, #f0f9ff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .verification-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 2rem;
            text-align: center;
            border: none;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            backdrop-filter: blur(10px);
        }

        .alert-custom {
            border: none;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            border-left: 4px solid var(--primary-blue);
        }

        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, var(--gradient-end), var(--dark-blue));
        }

        .btn-outline-custom {
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-1px);
        }

        .countdown-timer {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border-left: 4px solid #f59e0b;
        }

        .timer-display {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            font-weight: bold;
            color: #92400e;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }

        .floating-circle {
            position: absolute;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .circle-1 { width: 100px; height: 100px; top: 10%; left: 10%; animation-delay: 0s; }
        .circle-2 { width: 150px; height: 150px; top: 70%; right: 10%; animation-delay: 2s; }
        .circle-3 { width: 80px; height: 80px; bottom: 20%; left: 20%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .icon-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .card-body {
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        .text-muted-custom {
            color: #64748b !important;
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-circle circle-1"></div>
        <div class="floating-circle circle-2"></div>
        <div class="floating-circle circle-3"></div>
    </div>

    <div class="main-container">
        <div class="card verification-card">
            <div class="card-header">
                <div class="header-icon icon-pulse">
                    <i class="fas fa-envelope-open-text fa-2x"></i>
                </div>
                <h2 class="mb-0">Verifikasi Email</h2>
                <p class="mb-0 mt-2 opacity-90">Langkah terakhir untuk mengaktifkan akun kamu</p>
            </div>

            <div class="card-body">
                <!-- Expired Link Alert -->
                <div id="expiredAlert" class="alert alert-custom alert-danger d-none">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">Waduh, Link Verifikasi Udah Kadaluarsa!</h5>
                            <p class="mb-0">Link verifikasi email kamu udah expired nih. Tenang aja, kamu bisa minta yang baru di bawah ini.</p>
                        </div>
                    </div>
                </div>

                <!-- Normal Message -->
                <div id="normalMessage">
                    <div class="alert alert-custom alert-info">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
                            <div>
                                <p class="mb-0">Makasih udah daftar! Sebelum mulai, bisa verifikasi email address kamu dulu ga dengan klik link yang udah kita kirim ke email kamu? Kalo belum dapet emailnya, kita bisa kirim lagi kok.</p>
                                
                                <div class="mt-3 p-3 rounded-3" style="background: rgba(255,255,255,0.7);">
                                    <small class="text-muted-custom">
                                        <i class="fas fa-clock me-1"></i>
                                        <strong>Info:</strong> Link verifikasi cuma valid selama 1 jam aja ya! Kalo udah expired, tinggal klik tombol "Kirim Ulang" di bawah.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="successAlert" class="alert alert-custom alert-success d-none">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Sip! Link verifikasi baru udah dikirim ke email kamu.</h6>
                            <small>Cek inbox atau folder spam ya!</small>
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="errorAlert" class="alert alert-custom alert-danger d-none">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle fa-2x me-3"></i>
                        <div>
                            <p class="mb-0">Terjadi kesalahan saat mengirim email verifikasi. Silakan coba lagi.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-center mt-4">
                    <button class="btn btn-primary-custom" onclick="resendVerification()">
                        <i class="fas fa-paper-plane me-2"></i>
                        <span id="resendBtnText">Kirim Ulang Email Verifikasi</span>
                    </button>
                    
                    <button class="btn btn-outline-custom" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Log Out
                    </button>
                </div>

                <!-- Countdown Timer -->
                <div id="countdownSection" class="countdown-timer">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hourglass-half fa-lg me-3" style="color: #f59e0b;"></i>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-semibold" style="color: #92400e;">
                                Link verifikasi akan expired pada: 
                                <span id="expiry-time" class="timer-display">25 Jul 2025, 15:30</span>
                            </p>
                            <p class="mb-0">
                                <small>Sisa waktu: <span id="countdown" class="timer-display">45m 30s</span></small>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="text-center mt-4">
                    <small class="text-muted-custom">
                        <i class="fas fa-shield-alt me-1"></i>
                        Verifikasi email membantu menjaga keamanan akun kamu
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simulasi state management
        let isExpired = false;
        let hasSuccess = false;
        let hasError = false;

        // Demo: Set expiry time (1 hour from now)
        const expiryTime = new Date(Date.now() + 60 * 60 * 1000);
        document.getElementById('expiry-time').textContent = expiryTime.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Countdown timer function
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = expiryTime.getTime() - now;
            
            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById('countdown').innerHTML = '<span class="text-danger">EXPIRED</span>';
                showExpiredState();
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('countdown').innerHTML = 
                (hours > 0 ? hours + "j " : "") + 
                (minutes > 0 ? minutes + "m " : "") + 
                seconds + "s";
        }

        // Start countdown
        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown();

        // State management functions
        function showExpiredState() {
            isExpired = true;
            document.getElementById('expiredAlert').classList.remove('d-none');
            document.getElementById('normalMessage').classList.add('d-none');
            document.getElementById('countdownSection').classList.add('d-none');
            document.getElementById('resendBtnText').textContent = 'Kirim Link Baru';
        }

        function showSuccessState() {
            hasSuccess = true;
            hasError = false;
            document.getElementById('successAlert').classList.remove('d-none');
            document.getElementById('errorAlert').classList.add('d-none');
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                document.getElementById('successAlert').classList.add('d-none');
                hasSuccess = false;
            }, 5000);
        }

        function showErrorState() {
            hasError = true;
            hasSuccess = false;
            document.getElementById('errorAlert').classList.remove('d-none');
            document.getElementById('successAlert').classList.add('d-none');
            
            // Hide error message after 5 seconds
            setTimeout(() => {
                document.getElementById('errorAlert').classList.add('d-none');
                hasError = false;
            }, 5000);
        }

        // Button actions
        function resendVerification() {
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            
            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
            btn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Reset button
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                // Show success (you can change this to showErrorState() to test error state)
                showSuccessState();
                
                // Reset expired state if it was expired
                if (isExpired) {
                    isExpired = false;
                    document.getElementById('expiredAlert').classList.add('d-none');
                    document.getElementById('normalMessage').classList.remove('d-none');
                    document.getElementById('countdownSection').classList.remove('d-none');
                    document.getElementById('resendBtnText').textContent = 'Kirim Ulang Email Verifikasi';
                    
                    // Restart countdown with new expiry time
                    clearInterval(countdownInterval);
                    const newExpiryTime = new Date(Date.now() + 60 * 60 * 1000);
                    expiryTime.setTime(newExpiryTime.getTime());
                    document.getElementById('expiry-time').textContent = newExpiryTime.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    setInterval(updateCountdown, 1000);
                }
            }, 2000);
        }

        function logout() {
            if (confirm('Yakin mau logout?')) {
                // Simulate logout
                alert('Logout berhasil! (Demo)');
            }
        }

        // Demo buttons for testing different states
        console.log('Demo functions available:');
        console.log('- showExpiredState() - to test expired state');
        console.log('- showSuccessState() - to test success message');
        console.log('- showErrorState() - to test error message');

        // Entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.verification-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>