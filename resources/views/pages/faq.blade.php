@extends('layouts.app')

@section('title', 'FAQ - Kindify.id')

@push('styles')
    <!-- Import Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #4a90e2;
            --light-blue: #e3f2fd;
            --text-dark: #2c3e50;
            --border-color: rgba(173, 216, 230, 0.3);
            --shadow-light: 0 10px 30px rgba(173, 216, 230, 0.3);
            --shadow-hover: 0 8px 25px rgba(173, 216, 230, 0.3);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
            min-height: 100vh;
            margin: 0;
            color: #5a6c7d;
        }

        .breadcrumb-container {
            margin-bottom: 2rem;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            box-shadow: 0 2px 10px var(--border-color);
            display: inline-block;
        }

        .breadcrumb-item a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #1a73e8;
        }

        .breadcrumb-item.active {
            color: var(--text-dark);
            font-weight: 500;
        }

        .faq-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: var(--shadow-light);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .faq-container:hover {
            transform: translateY(-5px);
        }

        .faq-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--light-blue), var(--primary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.4);
        }

        .faq-title {
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .faq-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--light-blue), var(--primary-blue));
            border-radius: 2px;
        }

        .accordion-item {
            border: none;
            margin-bottom: 1rem;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px var(--border-color);
            transition: all 0.3s ease;
        }

        .accordion-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .accordion-button {
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
            color: var(--primary-blue);
            font-weight: 500;
            font-size: 1.1rem;
            border: none;
            padding: 1.5rem 2rem;
            border-radius: 15px !important;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #5a9ee5 100%);
            color: white;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25);
            border-color: transparent;
        }

        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%234a90e2'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transition: transform 0.3s ease;
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transform: rotate(-180deg);
        }

        .accordion-collapse {
            border-top: 1px solid var(--border-color);
        }

        .accordion-body {
            background: white;
            color: #5a6c7d;
            font-size: 1rem;
            line-height: 1.7;
            padding: 2rem;
            font-weight: 400;
        }

        /* Fade-in Animation */
        .faq-container {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s ease-out forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .faq-title {
                font-size: 2rem;
            }

            .accordion-button {
                padding: 1.25rem 1.5rem;
                font-size: 1rem;
            }

            .accordion-body {
                padding: 1.5rem;
            }

            .breadcrumb {
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <div class="breadcrumb-container text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center d-inline-flex">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </nav>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="faq-container p-4 p-md-5">
                    <!-- FAQ Icon -->
                    <div class="faq-icon">
                        <svg width="30" height="30" fill="white" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                        </svg>
                    </div>

                    <h2 class="faq-title text-center">Pertanyaan Umum (FAQ)</h2>

                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Bagaimana cara berdonasi di Kindify.id?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Anda bisa memilih campaign yang ingin didukung, klik "Lihat Detail", lalu klik "Donasi Sekarang" dan ikuti langkah-langkahnya. Prosesnya sangat mudah dan aman, hanya membutuhkan beberapa menit untuk menyelesaikan donasi Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Apakah donasi saya aman?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, semua donasi diproses melalui gateway pembayaran terpercaya dan kami tidak menyimpan data kartu Anda. Kami menggunakan enkripsi SSL untuk memastikan keamanan data pribadi dan transaksi Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Kapan donasi saya akan disalurkan?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Dana akan disalurkan secara berkala setelah campaign mencapai target atau sesuai jadwal penyaluran yang tercantum. Anda akan mendapatkan update melalui email tentang perkembangan penyaluran donasi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Bagaimana cara membuat campaign sendiri?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Untuk membuat campaign, silakan daftar terlebih dahulu, lalu klik "Buat Campaign" di dashboard Anda. Isi formulir dengan lengkap, upload dokumentasi yang diperlukan, dan tunggu verifikasi dari tim kami.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Apakah ada biaya administrasi?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Kami mengenakan biaya administrasi sebesar 2.5% dari total donasi untuk operasional platform dan pemeliharaan sistem. Biaya ini sudah termasuk biaya payment gateway.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection