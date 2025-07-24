<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Pembayaran - DonasiKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 text-center">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-warning mb-4">
                            <i class="fas fa-clock fa-5x"></i>
                        </div>
                        <h2 class="text-warning fw-bold mb-3">Menunggu Pembayaran</h2>
                        <p class="text-muted mb-4">
                            Donasi Anda telah terdaftar dan menunggu pembayaran. 
                            Silakan selesaikan pembayaran untuk melanjutkan donasi.
                        </p>
                        
                        @if(session('donation'))
                        <div class="alert alert-info">
                            <strong>Order ID:</strong> {{ session('donation')->order_id }}<br>
                            <strong>Nominal:</strong> Rp {{ number_format(session('donation')->nominal, 0, ',', '.') }}
                        </div>
                        @endif

                        <div class="d-grid gap-2 d-md-block">
                            <a href="/" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>Kembali ke Beranda
                            </a>
                            <button class="btn btn-outline-warning" onclick="checkPaymentStatus()">
                                <i class="fas fa-sync me-2"></i>Cek Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkPaymentStatus() {
            // TODO: Implementasi cek status pembayaran via AJAX
            alert('Fitur cek status akan diimplementasikan pada Day 4');
        }
    </script>
</body>
</html>