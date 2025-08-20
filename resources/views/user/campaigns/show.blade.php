@extends('layouts.app')
@section('title', 'Detail Campaign')

@section('content')

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="row g-0">
                    <!-- Campaign Image -->
                    <div class="col-md-5">
                        <img src="{{ $campaign->image_url }}" 
                             alt="{{ $campaign->title }}" 
                             class="img-fluid rounded-start-4 h-100 w-100" 
                             style="object-fit: cover;">
                    </div>

                    <!-- Campaign Content -->
                    <div class="col-md-7">
                        <div class="card-body p-4">
                            <!-- Title & Category -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h3 class="fw-bold text-primary mb-0">{{ $campaign->title }}</h3>
                                <span class="badge bg-dark text-white rounded-pill">
                                    {{ $campaign->category }}
                                </span>
                            </div>

                            <!-- Verification Status -->
                            <div class="mb-3">
                                <span class="badge 
                                    @if($campaign->verification_status === 'approved') bg-success
                                    @elseif($campaign->verification_status === 'rejected') bg-danger
                                    @else bg-warning text-dark @endif
                                    px-3 py-2 rounded-pill">
                                    {{ ucfirst($campaign->verification_status ?? 'pending') }}
                                </span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Terkumpul</span>
                                    <span>{{ $campaign->progress_percentage }}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-primary rounded-pill" 
                                         style="width: {{ $campaign->progress_percentage }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Target & Collected -->
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3 text-center">
                                        <div class="small text-muted">Target</div>
                                        <div class="fw-semibold text-dark">
                                            {{ $campaign->formatted_target_amount }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3 text-center">
                                        <div class="small text-muted">Terkumpul</div>
                                        <div class="fw-semibold text-primary">
                                            {{ $campaign->formatted_current_amount }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="mb-4">
                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                @if($campaign->is_expired)
                                    <span class="text-danger">Campaign telah berakhir</span>
                                @else
                                    <span class="text-muted">
                                        Berakhir dalam {{ $campaign->days_remaining }} hari
                                    </span>
                                @endif
                            </div>

                            <!-- Rejection Reason -->
                            @if($campaign->verification_status === 'rejected' && $campaign->rejection_reason)
                                <div class="alert alert-danger rounded-3">
                                    <strong>Alasan Penolakan:</strong>
                                    <p class="mb-0">{{ $campaign->rejection_reason }}</p>
                                </div>
                            @endif

                            <!-- Description -->
                            <div class="mb-4">
                                <h6 class="fw-bold">Deskripsi Campaign</h6>
                                <p class="text-muted" style="white-space: pre-line;">
                                    {{ $campaign->description }}
                                </p>
                            </div>

                            <!-- Back Button -->
                            <a href="{{ route('user.campaigns.history') }}" 
                               class="btn btn-secondary rounded-pill px-4">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fc;
    }
    .card {
        overflow: hidden;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
</style>

@endsection
