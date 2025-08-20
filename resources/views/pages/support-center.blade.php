@extends('layouts.app')

@section('title', 'Pusat Bantuan - Kindify.id')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
            min-height: 100vh;
            margin: 0;
            color: #5a6c7d;
        }

        :root {
            --primary-blue: #4a90e2;
            --light-blue: #e3f2fd;
            --text-dark: #2c3e50;
            --border-color: rgba(173, 216, 230, 0.3);
            --shadow-light: 0 10px 30px rgba(173, 216, 230, 0.3);
            --shadow-hover: 0 8px 25px rgba(173, 216, 230, 0.3);
        }

        .support-title {
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
        }

        .support-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--light-blue), var(--primary-blue));
            border-radius: 2px;
        }

        .alert-info {
            background: linear-gradient(135deg, var(--light-blue), #d0e7ff);
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            border-radius: 16px;
            padding: 1.2rem 1.5rem;
            font-weight: 500;
        }

        .alert-info i {
            color: var(--primary-blue);
        }

        .alert-info a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .alert-info a:hover {
            text-decoration: underline;
        }

        .list-group-item {
            border: none;
            border-radius: 14px !important;
            margin-bottom: 1rem;
            background: white;
            box-shadow: 0 4px 12px rgba(173, 216, 230, 0.15);
            transition: all 0.3s ease;
            padding: 1.2rem 1.5rem;
            border: 1px solid var(--border-color);
        }

        .list-group-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(173, 216, 230, 0.25);
            background: #f8fbff;
            border-color: var(--primary-blue);
        }

        .list-group-item h6 {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .list-group-item small {
            color: #6c757d;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue), #5a9ee5);
            border: none;
            padding: 10px 28px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3a7bc8, #4a8ed9);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(74, 144, 226, 0.4);
        }

        .btn-outline-primary {
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            padding: 10px 28px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.3);
        }

        .support-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--light-blue), var(--primary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            margin: 0 auto 2rem;
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
        }

        @media (max-width: 768px) {
            .support-title {
                font-size: 2rem;
            }

            .list-group-item {
                padding: 1rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <div class="support-icon">
                <i class="fas fa-hands-helping"></i>
            </div>
            <h1 class="support-title">Pusat Bantuan Kindify.id</h1>
            <p class="text-muted">
                Temukan jawaban cepat untuk pertanyaan umum seputar donasi, campaign, dan penggunaan platform.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Info Alert -->
                <div class="alert alert-info text-center mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Butuh bantuan lebih lanjut?
                    <a href="https://wa.me/6282130886804" target="_blank">Chat kami via WhatsApp</a>
                    atau kirim pesan melalui email.
                </div>

                <!-- FAQ List -->
                <div class="list-group">
                    <a href="#" class="list-group-item">
                        <h6>Bagaimana cara mengonfirmasi donasi saya?</h6>
                        <small class="text-muted">
                            Cek email Anda untuk bukti donasi digital. Anda juga bisa melihat riwayat donasi di dashboard akun Anda.
                        </small>
                    </a>

                    <a href="#" class="list-group-item">
                        <h6>Apa yang terjadi jika target campaign tidak tercapai?</h6>
                        <small class="text-muted">
                            Dana akan tetap disalurkan secara proporsional sesuai kebutuhan, dengan transparansi penuh melalui laporan campaign.
                        </small>
                    </a>

                    <a href="#" class="list-group-item">
                        <h6>Bisakah saya mengajukan campaign?</h6>
                        <small class="text-muted">
                            Ya! Daftar akun, verifikasi identitas, lalu klik "Buat Campaign" di dashboard Anda. Tim kami akan membantu verifikasi.
                        </small>
                    </a>

                    <a href="#" class="list-group-item">
                        <h6>Apakah identitas donatur dirahasiakan?</h6>
                        <small class="text-muted">
                            Anda bisa memilih untuk menyembunyikan nama saat berdonasi. Hanya tim internal yang bisa melihat data lengkap.
                        </small>
                    </a>

                    <a href="#" class="list-group-item">
                        <h6>Apakah ada biaya administrasi?</h6>
                        <small class="text-muted">
                            Kami mengenakan biaya administrasi 2.5% untuk operasional platform dan biaya payment gateway.
                        </small>
                    </a>
                </div>

                <!-- CTA Buttons -->
                <div class="text-center mt-5">
                    <a href="{{ route('faq') }}" class="btn btn-outline-primary me-md-3 mb-3 mb-md-0">
                        <i class="fas fa-question-circle me-2"></i> Kunjungi FAQ
                    </a>
                    <a href="https://wa.me/6282130886804" target="_blank" class="btn btn-primary">
                        <i class="fab fa-whatsapp me-2"></i> Chat via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection