@extends('layouts.app')

@section('title', 'Cara Berdonasi - Kindify.id')

@section('content')
    <style>
        /* Import Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .donation-step {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border-radius: 16px;
            padding: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #bbdefb;
            box-shadow: 0 4px 15px rgba(158, 196, 235, 0.2);
        }
        .donation-step:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(158, 196, 235, 0.3);
        }
        .step-icon {
            background: #2196f3;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .step-number {
            background: #2196f3;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .step-title {
            color: #1565c0;
            font-weight: 600;
            font-size: 1.25rem;
        }
        .btn-primary-custom {
            background: #2196f3;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        }
        .btn-primary-custom:hover {
            background: #1976d2;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.4);
        }
        .guide-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .section-title {
            font-weight: 700;
            color: #0d47a1;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .section-subtitle {
            color: #5472a7;
            font-size: 1.1rem;
            margin-bottom: 3rem;
            text-align: center;
        }
        .main-icon {
            color: #2196f3;
            font-size: 5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
    </style>

    <div class="container py-5">
        <div class="text-center mb-5">
            <i class="fas fa-hand-holding-heart main-icon"></i>
            <h1 class="section-title">Cara Berdonasi di Kindify.id</h1>
            <p class="section-subtitle">
                Ikuti 4 langkah mudah untuk mulai berbagi kebaikan bersama kami.
            </p>
        </div>

        <!-- Step 1 -->
        <div class="row justify-content-center guide-container mb-5">
            <div class="col-lg-10">
                <div class="row donation-step align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="step-number">1</div>
                    </div>
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="step-icon">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="step-title">Cari Campaign</h5>
                        <p class="text-muted mb-0">
                            Jelajahi campaign yang ingin Anda dukung melalui halaman utama atau gunakan fitur pencarian dan filter kategori.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gambar 1: Halaman Utama -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <img src="https://i.imgur.com/5ZzXJ6x.png" alt="Halaman Utama Campaign" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
            </div>
        </div>

        <!-- Step 2 -->
        <div class="row justify-content-center guide-container mb-5">
            <div class="col-lg-10">
                <div class="row donation-step align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="step-number">2</div>
                    </div>
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="step-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="step-title">Lihat Detail Campaign</h5>
                        <p class="text-muted mb-0">
                            Klik "Lihat Detail" untuk membaca informasi lengkap: tujuan, penggunaan dana, dan dokumentasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gambar 2: Form Donasi -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <img src="https://i.imgur.com/8vW9o3H.png" alt="Form Donasi" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
            </div>
        </div>

        <!-- Step 3 -->
        <div class="row justify-content-center guide-container mb-5">
            <div class="col-lg-10">
                <div class="row donation-step align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="step-number">3</div>
                    </div>
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="step-icon">
                            <i class="fas fa-donate"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="step-title">Pilih Jumlah Donasi</h5>
                        <p class="text-muted mb-0">
                            Masukkan nominal yang ingin Anda donasikan. Anda bisa menyumbang sesuai kemampuan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gambar 3: Metode Pembayaran -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <img src="https://i.imgur.com/7fYpNqR.png" alt="Metode Pembayaran" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
            </div>
        </div>

        <!-- Step 4 -->
        <div class="row justify-content-center guide-container mb-5">
            <div class="col-lg-10">
                <div class="row donation-step align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="step-number">4</div>
                    </div>
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="step-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="step-title">Bayar Secara Aman</h5>
                        <p class="text-muted mb-0">
                            Lanjutkan pembayaran melalui transfer bank, e-wallet, atau kartu kredit. Semua transaksi aman dan terenkripsi.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gambar 4: Grid Campaign -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <img src="https://i.imgur.com/2eLmK4F.png" alt="Grid Campaign" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
            </div>
        </div>

        <!-- CTA Button -->
        <div class="text-center mt-5">
            <a href="{{ route('home') }}#campaigns" class="btn btn-primary-custom">
                <i class="fas fa-hand-holding-heart me-2"></i> Mulai Berdonasi Sekarang
            </a>
        </div>
    </div>
@endsection