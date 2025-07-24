@extends('layouts.app')

@section('title', 'Kindify')

@section('content')

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Berbagi Kebaikan untuk Indonesia</h1>
                <p class="lead mb-4">Membuat Kebaikan Jadi Nyata.</p>
                <a href="#campaigns" class="btn btn-light btn-lg">
                    <i class="fas fa-heart me-2"></i> Mulai Berdonasi
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
                <i class="fas fa-users text-primary" style="font-size: 3rem;"></i>
                <h4>{{ $campaigns->sum(fn($c) => $c->donations->count()) }}</h4>
                <p class="text-muted">Donatur</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-hand-holding-heart text-success" style="font-size: 3rem;"></i>
                <h4>{{ $campaigns->count() }}</h4>
                <p class="text-muted">Campaign Aktif</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-coins text-warning" style="font-size: 3rem;"></i>
                <h4>Rp {{ number_format($campaigns->sum('collected_amount'), 0, ',', '.') }}</h4>
                <p class="text-muted">Dana Terkumpul</p>
            </div>
        </div>
    </div>
</section>

<!-- Campaigns -->
<section id="campaigns" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold">Campaign Donasi</h2>
                <p class="lead text-muted">Pilih campaign yang ingin Anda dukung dan mulai berbagi kebaikan.</p>
            </div>
        </div>

        @if($campaigns->isEmpty())
            <div class="row">
                <div class="col text-center">
                    <p>Belum ada campaign aktif.</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($campaigns as $campaign)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card campaign-card h-100">
                            @if($campaign->image)
                                <img src="{{ asset('storage/'.$campaign->image) }}" class="card-img-top" alt="{{ $campaign->title }}">
                            @endif
                            <div class="card-body">
                                <span class="badge bg-primary">{{ $campaign->category }}</span>
                                <h5>{{ $campaign->title }}</h5>
                                <p>{{ Str::limit($campaign->description, 100) }}</p>
                                <div class="progress progress-custom">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $campaign->progress_percentage }}%"></div>
                                </div>
                                <small>{{ number_format($campaign->progress_percentage) }}%</small>
                                <div class="fw-bold text-success">{{ $campaign->formatted_collected }}</div>
                                <small class="text-muted">dari {{ $campaign->formatted_target }}</small>
                                <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-outline-primary mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection
