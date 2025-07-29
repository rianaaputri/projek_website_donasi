@extends('layouts.app')

@section('title', $campaign->title . ' - Donasi Online')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">{{ $campaign->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Campaign Main Content -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                         class="card-img-top" 
                         alt="{{ $campaign->title }}"
                         style="height: 400px; object-fit: cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                         style="height: 400px;">
                        <i class="fas fa-image text-muted" style="font-size: 5rem;"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-primary fs-6">{{ $campaign->category }}</span>
                        @if($campaign->status === 'completed')
                            <span class="badge bg-success fs-6 ms-2">
                                <i class="fas fa-check me-1"></i>Selesai
                            </span>
                        @elseif($campaign->status === 'active')
                            <span class="badge bg-warning text-dark fs-6 ms-2">
                                <i class="fas fa-clock me-1"></i>Aktif
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="card-title h2 mb-4">{{ $campaign->title }}</h1>
                    
                    <!-- Campaign Description -->
                    <div class="mb-4">
                        <h4>Deskripsi Campaign</h4>
                        <div class="text-muted" style="line-height: 1.8;">
                            {!! nl2br(e($campaign->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Donors Section -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Donatur Terbaru ({{ $recentDonors->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentDonors->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-heart text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Belum ada donatur. Jadilah yang pertama!</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($recentDonors as $donation)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $donation->donor_name }}</h6>
                                            <div class="text-success fw-bold">{{ $donation->formatted_amount }}</div>
                                            <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                            @if($donation->comment)
                                                <p class="mb-0 mt-2 text-muted small">
                                                    "{{ Str::limit($donation->comment, 50) }}"
                                                </p>
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
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <!-- Progress Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Progress Donasi</h5>
                            <span class="badge bg-primary">{{ number_format($campaign->progress_percentage, 1) }}%</span>
                        </div>
                        
                        <div class="progress mb-3" style="height: 12px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $campaign->progress_percentage }}%">
                            </div>
                        </div>
                        
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-success mb-1">{{ $campaign->formatted_collected }}</h4>
                                <small class="text-muted">Terkumpul</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-primary mb-1">{{ $campaign->formatted_target }}</h4>
                                <small class="text-muted">Target</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Donation Stats -->
                    <div class="row text-center mb-4">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <h5 class="mb-1">{{ $campaign->donations->count() }}</h5>
                                <small class="text-muted">Donatur</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <h5 class="mb-1">{{  (int) $campaign->created_at->diffInDays(now())}}</h5>
                                <small class="text-muted">Hari berjalan</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Donation Button -->
                    @if($campaign->status === 'active' && $campaign->is_active)
                        <div class="d-grid">
                            <a href="{{ route('donation.create', $campaign->id) }}" 
                               class="btn btn-success btn-lg">
                                <i class="fas fa-heart me-2"></i>Donasi Sekarang
                            </a>
                        </div>
                    @else
                        <div class="d-grid">
                            <button class="btn btn-secondary btn-lg" disabled>
                                <i class="fas fa-times me-2"></i>Campaign Tidak Aktif
                            </button>
                        </div>
                    @endif
                    
                    <!-- Share Buttons -->
                    <div class="mt-4">
                        <h6 class="mb-3">Bagikan Campaign:</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($campaign->title) }}" 
                               target="_blank" 
                               class="btn btn-outline-info btn-sm flex-fill">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . request()->url()) }}" 
                               target="_blank" 
                               class="btn btn-outline-success btn-sm flex-fill">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection