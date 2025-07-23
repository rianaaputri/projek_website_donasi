@extends('layouts.app')

@section('title', 'kindify')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Berbagi Kebaikan untuk Indonesia</h1>
                <p class="lead mb-4">Memebuat Kebaikan Jadi Nyata.</p>
                <a href="#campaigns" class="btn btn-light btn-lg">
                    <i class="fas fa-heart me-2"></i>Mulai Berdonasi
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-hands-helping" style="font-size: 8rem; opacity: 0.8;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="mb-3">
                    <i class="fas fa-users text-primary" style="font-size: 3rem;"></i>
                </div>
                <h4>{{ number_format($campaigns->sum(function($c) { return $c->donations->count(); })) }}</h4>
                <p class="text-muted">Donatur</p>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <i class="fas fa-hand-holding-heart text-success" style="font-size: 3rem;"></i>
                </div>
                <h4>{{ $campaigns->count() }}</h4>
                <p class="text-muted">Campaign Aktif</p>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <i class="fas fa-coins text-warning" style="font-size: 3rem;"></i>
                </div>
                <h4>Rp {{ number_format($campaigns->sum('collected_amount'), 0, ',', '.') }}</h4>
                <p class="text-muted">Dana Terkumpul</p>
            </div>
        </div>
    </div>
</section>

<!-- Campaigns Section -->
<section id="campaigns" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold">Campaign Donasi</h2>
                <p class="lead text-muted">Pilih campaign yang ingin Anda dukung dan mulai berbagi kebaikan</p>
            </div>
        </div>

        @if($campaigns->isEmpty())
            <div class="row">
                <div class="col-12 text-center">
                    <div class="py-5">
                        <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">Belum ada campaign aktif</h4>
                        <p class="text-muted">Campaign akan segera hadir. Stay tuned!</p>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($campaigns as $campaign)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card campaign-card h-100">
                            @if($campaign->image)
                                <img src="{{ asset('storage/' . $campaign->image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $campaign->title }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                     style="height: 200px;">
                                    <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge bg-primary">{{ $campaign->category }}</span>
                                </div>
                                
                                <h5 class="card-title">{{ $campaign->title }}</h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($campaign->description, 100) }}
                                </p>
                                
                                <div class="mt-auto">
                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="progress progress-custom">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: {{ $campaign->progress_percentage }}%">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small class="text-muted">
                                                {{ number_format($campaign->progress_percentage, 1) }}%
                                            </small>
                                            <small class="text-muted">
                                                {{ $campaign->donations->count() }} donatur
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Amount Info -->
                                    <div class="mb-3">
                                        <div class="fw-bold text-success">
                                            {{ $campaign->formatted_collected }}
                                        </div>
                                        <small class="text-muted">
                                            dari {{ $campaign->formatted_target }}
                                        </small>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('campaign.show', $campaign->id) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </a>
                                        <a href="{{ route('donation.create', $campaign->id) }}" 
                                           class="btn btn-donate">
                                            <i class="fas fa-heart me-1"></i>Donasi Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3>Ingin membuat campaign donasi?</h3>
                <p class="mb-0">Hubungi admin untuk mengajukan campaign donasi Anda</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="#" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>Hubungi Admin
                </a>
            </div>
        </div>
    </div>
</section>
@endsection