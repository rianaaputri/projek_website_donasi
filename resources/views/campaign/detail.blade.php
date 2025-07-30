@extends('layouts.app')

@section('title', $campaign->title . ' - Donasi Online')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-5">
        <!-- Breadcrumb dengan styling yang lebih baik -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded-pill px-4 py-3 shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-primary text-decoration-none fw-medium">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted">{{ $campaign->title }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Campaign Main Content -->
            <div class="col-lg-8">
                <!-- Main Campaign Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    @if($campaign->image)
                        <img src="{{ asset('storage/' . $campaign->image) }}" 
                             class="card-img-top" 
                             alt="{{ $campaign->title }}"
                             style="height: 400px; object-fit: cover;">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center" 
                             style="height: 400px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                            <i class="fas fa-image text-primary opacity-50" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body p-4">
                        <!-- Status Badges -->
                        <div class="mb-4">
                            <span class="badge bg-primary bg-gradient fs-6 px-3 py-2 rounded-pill">
                                <i class="fas fa-tag me-1"></i>{{ $campaign->category }}
                            </span>
                            @if($campaign->status === 'completed')
                                <span class="badge bg-success bg-gradient fs-6 ms-2 px-3 py-2 rounded-pill">
                                    <i class="fas fa-check me-1"></i>Selesai
                                </span>
                            @elseif($campaign->status === 'active')
                                <span class="badge bg-info bg-gradient fs-6 ms-2 px-3 py-2 rounded-pill">
                                    <i class="fas fa-clock me-1"></i>Aktif
                                </span>
                            @endif
                        </div>
                        
                        <h1 class="card-title h2 mb-4 text-dark fw-bold">{{ $campaign->title }}</h1>
                        
                        <!-- Campaign Description -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-gradient rounded-circle p-2 me-3">
                                    <i class="fas fa-info text-white"></i>
                                </div>
                                <h4 class="mb-0 text-primary fw-bold">Deskripsi Campaign</h4>
                            </div>
                            <div class="bg-light rounded-3 p-4 border-start border-primary border-4">
                                <div class="text-muted" style="line-height: 1.8; font-size: 1.05rem;">
                                    {!! nl2br(e($campaign->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Donors Section -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-primary bg-gradient text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-users me-2"></i>
                            Donatur Terbaru ({{ $recentDonors->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($recentDonors->isEmpty())
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-heart text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <h6 class="text-muted mb-2">Belum ada donatur</h6>
                                <p class="text-muted small">Jadilah yang pertama berkontribusi!</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($recentDonors as $donation)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-light rounded-3 border border-light-subtle h-100">
                                            <div class="bg-primary bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold text-dark">{{ $donation->donor_name }}</h6>
                                                <div class="text-success fw-bold fs-6">{{ $donation->formatted_amount }}</div>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $donation->created_at->diffForHumans() }}
                                                </small>
                                                @if($donation->comment)
                                                    <div class="mt-2 p-2 bg-white rounded-2 border-start border-primary border-3">
                                                        <p class="mb-0 text-muted small fst-italic">
                                                            "{{ Str::limit($donation->comment, 50) }}"
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <!-- Progress Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-bold text-dark">
                                    <i class="fas fa-chart-line text-primary me-2"></i>Progress Donasi
                                </h5>
                                <span class="badge bg-primary bg-gradient fs-6 px-3 py-2 rounded-pill">
                                    {{ number_format($campaign->progress_percentage, 1) }}%
                                </span>
                            </div>
                            
                            <div class="progress mb-4 rounded-pill" style="height: 15px;">
                                <div class="progress-bar bg-primary bg-gradient progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: {{ $campaign->progress_percentage }}%">
                                </div>
                            </div>
                            
                            <div class="row text-center g-3">
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 border border-light-subtle">
                                        <h4 class="text-success mb-1 fw-bold">{{ $campaign->formatted_collected }}</h4>
                                        <small class="text-muted fw-medium">
                                            <i class="fas fa-coins me-1"></i>Terkumpul
                                        </small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 border border-light-subtle">
                                        <h4 class="text-primary mb-1 fw-bold">{{ $campaign->formatted_target }}</h4>
                                        <small class="text-muted fw-medium">
                                            <i class="fas fa-bullseye me-1"></i>Target
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Donation Stats -->
                        <div class="row text-center mb-4 g-3">
                            <div class="col-6">
                                <div class="bg-primary bg-gradient bg-opacity-10 p-3 rounded-3 border border-primary border-opacity-25">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <h5 class="mb-1 fw-bold text-primary">{{ $campaign->donations->count() }}</h5>
                                    <small class="text-muted fw-medium">Donatur</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-info bg-gradient bg-opacity-10 p-3 rounded-3 border border-info border-opacity-25">
                                    <div class="text-info mb-2">
                                        <i class="fas fa-calendar-alt" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <h5 class="mb-1 fw-bold text-info">{{ (int) $campaign->created_at->diffInDays(now()) }}</h5>
                                    <small class="text-muted fw-medium">Hari berjalan</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Donation Button -->
                        @if($campaign->status === 'active' && $campaign->is_active)
                            <div class="d-grid mb-4">
                                <a href="{{ route('donation.create', $campaign->id) }}" 
                                   class="btn btn-success btn-lg rounded-pill py-3 fw-bold shadow-sm">
                                    <i class="fas fa-heart me-2"></i>Donasi Sekarang
                                </a>
                            </div>
                        @else
                            <div class="d-grid mb-4">
                                <button class="btn btn-secondary btn-lg rounded-pill py-3 fw-bold" disabled>
                                    <i class="fas fa-times me-2"></i>Campaign Tidak Aktif
                                </button>
                            </div>
                        @endif
                        
                        <!-- Share Buttons -->
                        <div class="border-top pt-4">
                            <h6 class="mb-3 fw-bold text-dark">
                                <i class="fas fa-share-alt text-primary me-2"></i>Bagikan Campaign:
                            </h6>
                            <div class="d-grid gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-sm rounded-pill py-2 fw-medium">
                                    <i class="fab fa-facebook-f me-2"></i>Bagikan di Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($campaign->title) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-info btn-sm rounded-pill py-2 fw-medium">
                                    <i class="fab fa-twitter me-2"></i>Bagikan di Twitter
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . request()->url()) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-success btn-sm rounded-pill py-2 fw-medium">
                                    <i class="fab fa-whatsapp me-2"></i>Bagikan via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection