@extends('layouts.app')

@section('title', 'Beranda - Kindify')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Berbagi Kebaikan, Menyebarkan Harapan</h1>
                <p class="lead mb-4">Bergabunglah dengan ribuan donatur dalam membantu sesama yang membutuhkan. Setiap donasi Anda membawa perubahan nyata.</p>
                <a href="#kampanye" class="btn btn-light btn-lg">Mulai Berdonasi</a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-hands-helping" style="font-size: 200px; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="donation-stats">
                    <h3 class="text-primary">{{ $totalCampaigns }}</h3>
                    <p class="mb-0">Kampanye Aktif</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="donation-stats">
                    <h3 class="text-success">Rp {{ number_format($totalDonations, 0, ',', '.') }}</h3>
                    <p class="mb-0">Total Donasi</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="donation-stats">
                    <h3 class="text-info">{{ $totalDonors }}</h3>
                    <p class="mb-0">Donatur</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="donation-stats">
                    <h3 class="text-warning">{{ $completedCampaigns }}</h3>
                    <p class="mb-0">Kampanye Selesai</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Campaigns -->
<section id="kampanye" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Kampanye Donasi Terkini</h2>
            <p class="text-muted">Pilih kampanye yang ingin Anda dukung</p>
        </div>
        
        <div class="row">
            @foreach($campaigns as $campaign)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card campaign-card h-100">
                    <img src="{{ asset('uploads/campaigns/' . ($campaign->gambar ?: 'default.jpg')) }}" 
                         class="card-img-top" alt="{{ $campaign->judul }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $campaign->judul }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($campaign->deskripsi, 100) }}</p>
                        
                        <!-- Progress -->
                        @php
                            $percentage = $campaign->target > 0 ? ($campaign->terkumpul / $campaign->target) * 100 : 0;
                        @endphp
                        
                        <div class="mb-3">
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar" style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                            <div class="row small text-muted">
                                <div class="col">
                                    <strong>Rp {{ number_format($campaign->terkumpul, 0, ',', '.') }}</strong>
                                    terkumpul
                                </div>
                                <div class="col text-end">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <small class="text-muted">Target: Rp {{ number_format($campaign->target, 0, ',', '.') }}</small>
                            <div class="d-grid mt-2">
                                <a href="{{ route('campaigns.show', $campaign->id) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-heart"></i> Donasi Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection