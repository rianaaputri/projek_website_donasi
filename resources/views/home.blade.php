@extends('layouts.app')

@section('title', 'kindify.id - Berbagi Kebaikan untuk Indonesia')

@section('content')

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Mau Berbuat Baik Apa Hari Ini?</h1>
                <p class="lead mb-4">Yuk bergabung dengan platform terpercaya untuk berbagi kebaikan dan membantu sesama yang membutuhkan. Berbagi Kebaikan untuk Indonesia.</p>
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

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">{{ number_format($campaigns->sum(fn($c) => $c->donations->count())) }}</div>
                    <div class="stat-label">Donatur Bergabung</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div class="stat-number">{{ number_format($campaigns->count()) }}</div>
                    <div class="stat-label">Campaign Aktif</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-number">{{ number_format($campaigns->sum('collected_amount'), 0, ',', '.') }}</div>
                    <div class="stat-label">Dana Terkumpul (Rp)</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Campaigns Section -->
<section id="campaigns" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Program Donasi Terbaru</h2>
                <p class="section-subtitle">Pilih program donasi yang ingin Anda dukung dan mulai berbagi kebaikan untuk sesama yang membutuhkan.</p>
            </div>
        </div>

        @if($campaigns->isEmpty())
            <div class="row">
                <div class="col text-center">
                    <div class="py-5">
                        <i class="fas fa-heart-broken" style="font-size: 4rem; color: #e9ecef; margin-bottom: 2rem;"></i>
                        <h4 class="text-muted">Belum Ada Campaign Aktif</h4>
                        <p class="text-muted">Saat ini belum ada campaign donasi yang tersedia. Silakan cek kembali nanti.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($campaigns as $campaign)
                    <div class="col-lg-4 col-md-6 mb-4">
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
                                
                                <div class="progress progress-custom">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $campaign->progress_percentage }}%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}% tercapai</small>
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>{{ $campaign->donations->count() }} donatur
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-success fs-5">{{ $campaign->formatted_collected }}</div>
                                    <small class="text-muted">dari target {{ $campaign->formatted_target }}</small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </a>
                                    @if($campaign->progress_percentage < 100)
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            @if($campaign->end_date)
                                                {{ \Carbon\Carbon::parse($campaign->end_date)->diffForHumans() }}
                                            @else
                                                Aktif
                                            @endif
                                        </small>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Tercapai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Load More Button -->
            @if($campaigns->count() >= 6)
                <div class="row">
                    <div class="col text-center">
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-plus me-2"></i> Lihat Lebih Banyak
                        </a>
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--light-blue) 100%);">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">Ingin Membuat Campaign Donasi?</h2>
                <p class="lead mb-4">Bergabunglah dengan ribuan orang yang telah mempercayakan campaign donasi mereka kepada kami. Platform yang aman, terpercaya, dan mudah digunakan.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </a>
                @else
                    <a href="{{ route('campaign.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i> Buat Campaign
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>

<style>
@keyframes heartbeat {
    0% {
        transform: translate(-50%, -50%) scale(1);
    }
    50% {
        transform: translate(-50%, -50%) scale(1.1);
    }
    100% {
        transform: translate(-50%, -50%) scale(1);
    }
}

.campaign-card .card-body {
    display: flex;
    flex-direction: column;
}

.campaign-card .card-body > div:last-child {
    margin-top: auto;
}
</style>

@endsection