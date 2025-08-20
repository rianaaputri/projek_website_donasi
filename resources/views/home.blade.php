@extends('layouts.app')

@section('title', 'kindify.id - Berbagi Kebaikan untuk Indonesia')

@section('content')

<!-- Import Google Fonts Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Mau Berbuat Baik Apa Hari Ini?</h1>
                <p class="lead mb-4">Yuk bergabung dengan platform terpercaya untuk berbagi kebaikan dan membantu sesama yang membutuhkan.</p>
                <a href="#campaigns" class="btn btn-light btn-lg">
                    <i class="fas fa-heart me-2"></i> Mulai Berdonasi
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <div style="position: relative;">
                    <i class="fas fa-hands-helping" style="font-size: 8rem; opacity: 0.9; color: white;"></i>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <i class="fas fa-heart" style="font-size: 2rem; color: #ff6b6b; animation: heartbeat 1.5s ease-in-out infinite;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number">{{ number_format($campaigns->sum(fn($c) => $c->donations->count())) }}</div>
                    <div class="stat-label">Donatur Bergabung</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-hand-holding-heart"></i></div>
                    <div class="stat-number">{{ number_format($campaigns->count()) }}</div>
                    <div class="stat-label">Campaign Aktif</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-coins"></i></div>
                    <div class="stat-number">{{ number_format($campaigns->sum('collected_amount'), 0, ',', '.') }}</div>
                    <div class="stat-label">Dana Terkumpul (Rp)</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="campaigns" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Program Donasi Terbaru</h2>
                <p class="section-subtitle">Pilih program donasi yang ingin Anda dukung dan mulai berbagi kebaikan.</p>
            </div>
        </div>

        <!-- Search Section -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="search-container">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input 
                            type="text" 
                            id="campaignSearch" 
                            class="form-control search-input" 
                            placeholder="Cari campaign berdasarkan judul atau kategori..."
                            value="{{ request('search') }}"
                        >
                        <button type="button" id="clearSearch" class="clear-search" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="search-filters mt-3">
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary filter-btn active" data-category="all">
                                <i class="fas fa-th-large me-1"></i> Semua
                            </button>
                            @php
                                $categories = $campaigns->pluck('category')->unique()->filter();
                            @endphp
                            @foreach($categories as $category)
                                <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-category="{{ $category }}">
                                    <i class="fas fa-tag me-1"></i> {{ $category }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results Info -->
        <div class="row mb-3">
            <div class="col-12">
                <div id="searchResultsInfo" class="text-center text-muted" style="display: none;">
                    <small id="searchResultsText"></small>
                </div>
                <div id="noResultsMessage" class="text-center" style="display: none;">
                    <div class="py-4">
                        <i class="fas fa-search" style="font-size: 3rem; color: #e9ecef;"></i>
                        <h5 class="text-muted mt-3">Tidak Ada Campaign Yang Ditemukan</h5>
                        <p class="text-muted">Coba gunakan kata kunci yang berbeda atau pilih kategori lain.</p>
                    </div>
                </div>
            </div>
        </div>

        @if($campaigns->isEmpty())
            <div class="row">
                <div class="col text-center py-5">
                    <i class="fas fa-heart-broken" style="font-size: 4rem; color: #e9ecef;"></i>
                    <h4 class="text-muted mt-3">Belum Ada Campaign Aktif</h4>
                    <p class="text-muted">Silakan cek kembali nanti ya.</p>
                </div>
            </div>
        @else
            <!-- Campaign Grid (replacing carousel for better search experience) -->
            <div id="campaignGrid" class="row justify-content-center">
                @foreach($campaigns as $campaign)
                    <div class="col-lg-4 col-md-6 mb-4 campaign-item" 
                         data-title="{{ strtolower($campaign->title) }}" 
                         data-category="{{ strtolower($campaign->category) }}"
                         data-description="{{ strtolower($campaign->description) }}">
                        <div class="card campaign-card">
                            @if($campaign->image)
                                <img src="{{ asset('storage/'.$campaign->image) }}" class="card-img-top" alt="{{ $campaign->title }}">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(45deg, #e8f4fd, #b3e5fc);">
                                    <i class="fas fa-image" style="font-size: 3rem; color: #6c757d; opacity: 0.5;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <span class="badge bg-primary mb-2">{{ $campaign->category }}</span>
                                <h5 class="card-title">{{ $campaign->title }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($campaign->description, 100) }}</p>

                                <div class="progress progress-custom mb-2">
                                    <div class="progress-bar" style="width: {{ $campaign->progress_percentage }}%"></div>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}% tercapai</small>
                                    <small class="text-muted"><i class="fas fa-users me-1"></i>{{ $campaign->donations->count() }} donatur</small>
                                </div>

                                <div class="mb-3">
                                    <div class="fw-bold text-success fs-5">{{ $campaign->formatted_collected }}</div>
                                    <small class="text-muted">dari target {{ $campaign->formatted_target }}</small>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </a>
                                    @if($campaign->progress_percentage >= 100)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i> Tercapai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="py-5" style="background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));">
    <div class="container text-center text-white">
        <div class="col-lg-8 mx-auto">
            <h2 class="mb-4">Ingin Membuat Campaign Donasi?</h2>
            <p class="lead mb-4">Platform donasi terpercaya untuk Anda yang ingin berkontribusi lebih.</p>
            @guest
                <div class="button-group">
                    <a href="{{ route('register') }}" class="btn-custom btn-primary-custom">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn-custom btn-outline-custom">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </a>
                </div>
            @else
                @auth('admin')
                    <a href="{{ route('admin.campaigns.create') }}" class="btn-custom btn-primary-custom">
                        <i class="fas fa-plus me-2"></i> Buat Campaign (Admin)
                    </a>
            @else
                <a href="{{ route('user.campaigns.create') }}" class="btn-custom btn-primary-custom">
                    <i class="fas fa-plus me-2"></i> Buat Campaign
                </a>
            @endauth
            @endguest
        </div>
    </div>
</section>

<!-- Footer Section -->
<footer class="footer-section">
    <div class="container">
        <!-- Main Footer Content -->
        <div class="row py-5 justify-content-center">
            <div class="col-lg-5 col-md-6 mb-4 text-center">
                <div class="footer-brand">
                    <h4 class="footer-logo">
                        <i class="fas fa-heart me-2"></i>
                        Kindify.id
                    </h4>
                    <p class="footer-description">
                        Platform donasi terpercaya untuk berbagi kebaikan dan membantu sesama yang membutuhkan di Indonesia.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <h5 class="footer-title">Bantuan & Dukungan</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('donation.guide') }}">Cara Berdonasi</a></li>
                    <li><a href="{{ route('support.center') }}">Pusat Bantuan</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <h5 class="footer-title">Kontak Kami</h5>
                <div class="footer-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope me-2"></i>
                        <span>info@kindify.id</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone me-2"></i>
                        <span>+62 821 3088 6804</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <span>Cimahi, Indonesia</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom / Copyright -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <p class="copyright-text mb-2">
                        &copy; {{ date('Y') }} <strong>Kindify.id</strong> - Platform Donasi Terpercaya
                    </p>
                    <p class="powered-by">
                        Made with <i class="fas fa-heart"></i> for Indonesia
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Import Poppins Font */
* {
    font-family: 'Poppins', sans-serif;
}

/* Footer Styles */
.footer-section {
    background: linear-gradient(135deg, #e8f4f8 0%, #d6eaf8 50%, #aed6f1 100%);
    color: #2c3e50;
    position: relative;
    overflow: hidden;
    margin-top: 3rem;
}

.footer-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.3), transparent);
}

.footer-brand {
    margin-bottom: 2rem;
}

.footer-logo {
    color: #2980b9;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.footer-logo i {
    color: #e74c3c;
    animation: heartbeat 2s ease-in-out infinite;
}

.footer-description {
    color: #34495e;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.footer-title {
    color: #2980b9;
    font-weight: 600;
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 3px;
    background: linear-gradient(90deg, #3498db, #5dade2);
    border-radius: 2px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.8rem;
}

.footer-links a {
    color: #5a6c7d;
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    position: relative;
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
}

.footer-links a:hover {
    color: #2980b9;
    background: rgba(52, 152, 219, 0.1);
    transform: translateY(-2px);
}

.social-links {
    display: flex;
    gap: 15px;
    margin-top: 1.5rem;
    justify-content: center;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.8);
    color: #5a6c7d;
    border-radius: 50%;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: 2px solid rgba(52, 152, 219, 0.2);
}

.social-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, #3498db, #5dade2);
    border-radius: 50%;
    transform: scale(0);
    transition: transform 0.3s ease;
}

.social-link i {
    position: relative;
    z-index: 1;
    transition: color 0.3s ease;
}

.social-link:hover {
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
    border-color: #3498db;
}

.social-link:hover::before {
    transform: scale(1);
}

.footer-contact {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5a6c7d;
    font-size: 0.95rem;
    padding: 8px 15px;
    border-radius: 25px;
    background: rgba(255, 255, 255, 0.6);
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: rgba(52, 152, 219, 0.1);
    transform: translateY(-2px);
}

.contact-item i {
    color: #3498db;
    width: 18px;
    flex-shrink: 0;
    margin-right: 8px;
}

.footer-bottom {
    border-top: 1px solid rgba(52, 152, 219, 0.2);
    padding: 2rem 0;
    margin-top: 2rem;
    background: rgba(255, 255, 255, 0.3);
}

.copyright-text {
    margin: 0;
    font-size: 1rem;
    color: #34495e;
    font-weight: 500;
}

.copyright-text strong {
    color: #2980b9;
    font-weight: 700;
}

.powered-by {
    margin: 0;
    font-size: 0.9rem;
    color: #5a6c7d;
    margin-top: 0.5rem;
}

.powered-by i {
    color: #e74c3c;
    animation: heartbeat 2s ease-in-out infinite;
    margin: 0 5px;
}

/* Search Container Styles */
.search-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.search-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 15px;
    color: #6c757d;
    font-size: 16px;
    z-index: 2;
}

.search-input {
    padding: 12px 45px 12px 45px;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    font-size: 16px;
    background: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.search-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25), 0 4px 20px rgba(0, 0, 0, 0.1);
    outline: none;
}

.clear-search {
    position: absolute;
    right: 15px;
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
    z-index: 2;
}

.clear-search:hover {
    color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.search-filters {
    animation: fadeInUp 0.5s ease-out;
}

.filter-btn {
    border-radius: 25px;
    padding: 6px 16px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    color: #6c757d;
    background: #fff;
}

.filter-btn:hover {
    border-color: #0d6efd;
    color: #0d6efd;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
}

.filter-btn.active {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

/* Campaign Item Animation */
.campaign-item {
    transition: all 0.3s ease;
}

.campaign-item.fade-out {
    opacity: 0;
    transform: scale(0.95);
}

.campaign-item.fade-in {
    opacity: 1;
    transform: scale(1);
}

/* Custom Button Styles */
.btn-custom {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 28px;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    border-radius: 50px;
    border: 2px solid transparent;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    margin: 0 8px;
    min-width: 160px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-custom:hover::before {
    left: 100%;
}

/* Primary Button */
.btn-primary-custom {
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    color: #0d6efd;
    border: 2px solid #ffffff;
}

.btn-primary-custom:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #0b5ed7;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.btn-primary-custom:active {
    transform: translateY(0);
}

/* Outline Button */
.btn-outline-custom {
    background: transparent;
    color: #ffffff;
    border: 2px solid #ffffff;
}

.btn-outline-custom:hover {
    background: #ffffff;
    color: #0d6efd;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
}

.btn-outline-custom:active {
    transform: translateY(0);
}

/* Button Group */
.button-group {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 16px;
    margin-top: 20px;
}

/* Navbar Button Styles - untuk tombol di navbar */
.navbar .btn {
    font-family: 'Poppins', sans-serif;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 25px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-width: 100px;
}

.navbar .btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.navbar .btn:hover::before {
    left: 100%;
}

.navbar .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Heartbeat Animation */
@keyframes heartbeat {
    0%   { transform: scale(1); }
    50%  { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Fade In Up Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pulse Animation for Buttons */
@keyframes pulse {
    0% { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }
    50% { box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); }
    100% { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }
}

.btn-custom:hover {
    animation: pulse 1.5s ease-in-out infinite;
}

.campaign-card .card-body {
    display: flex;
    flex-direction: column;
}

.campaign-card .card-body > div:last-child {
    margin-top: auto;
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .footer-section .col-lg-5,
    .footer-section .col-lg-3 {
        margin-bottom: 2.5rem;
    }
    
    .footer-brand {
        margin-bottom: 2.5rem;
    }
}

@media (max-width: 767.98px) {
    .search-container {
        padding: 20px 15px;
    }
    
    .search-input {
        font-size: 14px;
        padding: 10px 40px 10px 40px;
    }
    
    .filter-btn {
        font-size: 12px;
        padding: 5px 12px;
    }
    
    .button-group {
        flex-direction: column;
        gap: 12px;
    }
    
    .btn-custom {
        width: 100%;
        max-width: 280px;
        margin: 0;
    }
    
    .footer-section {
        margin-top: 2rem;
    }
    
    .footer-logo {
        font-size: 1.8rem;
    }
    
    .footer-description {
        font-size: 0.95rem;
    }
    
    .social-links {
        gap: 12px;
    }
    
    .social-link {
        width: 42px;
        height: 42px;
        font-size: 1rem;
    }
    
    .footer-bottom {
        padding: 1.5rem 0;
    }
}

@media (max-width: 767.98px) {
    .search-container {
        padding: 20px 15px;
    }
    
    .search-input {
        font-size: 14px;
        padding: 10px 40px 10px 40px;
    }
    
    .filter-btn {
        font-size: 12px;
        padding: 5px 12px;
    }
    
    .button-group {
        flex-direction: column;
        gap: 12px;
    }
    
    .btn-custom {
        width: 100%;
        max-width: 280px;
        margin: 0;
    }
    
    .footer-bottom {
        text-align: center;
    }
    
    .footer-bottom .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .footer-bottom .col-md-6:last-child {
        margin-bottom: 0;
    }
}

@media (max-width: 575.98px) {
    .btn-custom {
        padding: 10px 24px;
        font-size: 14px;
        min-width: 140px;
    }
    
    .footer-logo {
        font-size: 1.6rem;
    }
    
    .footer-description {
        font-size: 0.9rem;
        padding: 0 15px;
    }
    
    .social-link {
        width: 40px;
        height: 40px;
        font-size: 0.95rem;
    }
    
    .footer-title {
        font-size: 1.1rem;
    }
    
    .contact-item {
        font-size: 0.9rem;
        padding: 6px 12px;
    }
    
    .copyright-text {
        font-size: 0.95rem;
    }
    
    .powered-by {
        font-size: 0.85rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('campaignSearch');
    const clearSearchBtn = document.getElementById('clearSearch');
    const campaignItems = document.querySelectorAll('.campaign-item');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchResultsInfo = document.getElementById('searchResultsInfo');
    const searchResultsText = document.getElementById('searchResultsText');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const campaignGrid = document.getElementById('campaignGrid');
    
    let currentCategory = 'all';
    let searchTimeout;

    // Search functionality
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Show/hide clear button
        clearSearchBtn.style.display = searchTerm ? 'block' : 'none';
        
        // Add small delay for better UX
        searchTimeout = setTimeout(() => {
            campaignItems.forEach(item => {
                const title = item.dataset.title;
                const category = item.dataset.category;
                const description = item.dataset.description;
                
                const matchesSearch = !searchTerm || 
                    title.includes(searchTerm) || 
                    category.includes(searchTerm) || 
                    description.includes(searchTerm);
                
                const matchesCategory = currentCategory === 'all' || category === currentCategory;
                
                if (matchesSearch && matchesCategory) {
                    item.style.display = 'block';
                    item.classList.remove('fade-out');
                    item.classList.add('fade-in');
                    visibleCount++;
                } else {
                    item.classList.add('fade-out');
                    item.classList.remove('fade-in');
                    setTimeout(() => {
                        if (item.classList.contains('fade-out')) {
                            item.style.display = 'none';
                        }
                    }, 300);
                }
            });
            
            // Update search results info
            updateSearchResults(searchTerm, visibleCount);
        }, 200);
    }
    
    // Update search results display
    function updateSearchResults(searchTerm, visibleCount) {
        const totalItems = campaignItems.length;
        
        if (searchTerm || currentCategory !== 'all') {
            if (visibleCount > 0) {
                searchResultsInfo.style.display = 'block';
                noResultsMessage.style.display = 'none';
                
                let resultText = `Menampilkan ${visibleCount} dari ${totalItems} campaign`;
                if (searchTerm) {
                    resultText += ` untuk "${searchTerm}"`;
                }
                if (currentCategory !== 'all') {
                    resultText += ` dalam kategori "${currentCategory}"`;
                }
                
                searchResultsText.textContent = resultText;
            } else {
                searchResultsInfo.style.display = 'none';
                noResultsMessage.style.display = 'block';
            }
        } else {
            searchResultsInfo.style.display = 'none';
            noResultsMessage.style.display = 'none';
        }
    }
    
    // Category filter functionality
    function filterByCategory(category) {
        currentCategory = category.toLowerCase();
        
        // Update active filter button
        filterButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.category.toLowerCase() === currentCategory) {
                btn.classList.add('active');
            }
        });
        
        performSearch();
    }
    
    // Event listeners
    searchInput.addEventListener('input', performSearch);
    
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        performSearch();
        searchInput.focus();
    });
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterByCategory(this.dataset.category);
        });
    });
    
    // Search input focus effects
    searchInput.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
    });
    
    searchInput.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Focus search with Ctrl/Cmd + K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Clear search with Escape
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            performSearch();
            searchInput.blur();
        }
    });
    
    // Initialize search if there's a value (from URL parameter)
    if (searchInput.value) {
        performSearch();
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Footer animation on scroll
    const footer = document.querySelector('.footer-section');
    const footerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                footer.style.animation = 'fadeInUp 0.8s ease-out';
            }
        });
    }, { threshold: 0.1 });
    
    if (footer) {
        footerObserver.observe(footer);
    }
});
</script>

@endsection