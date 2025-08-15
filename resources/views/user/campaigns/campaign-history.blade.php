@extends('layouts.app')
@section('title', 'Riwayat Campaign')
@section('content')

<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-primary mb-1">
                        <i class="fas fa-history me-2"></i>Riwayat Campaign Saya
                    </h4>
                    <p class="text-muted mb-0">Kelola dan pantau semua campaign yang telah Anda buat</p>
                </div>
                <a href="{{ route('user.campaigns.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm">
                    <i class="fas fa-plus me-2"></i>Buat Campaign Baru
                </a>
            </div>

            <!-- Success/Error Alert -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                        <div class="flex-grow-1">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            @if (session('campaign_id'))
                                <br><small class="text-muted">ID Campaign: #{{ session('campaign_id') }}</small>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle text-danger me-3 fs-5"></i>
                        <div class="flex-grow-1">
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Campaign Cards -->
            @if($campaigns->count() > 0)
                <div class="row g-4">
                    @foreach($campaigns as $campaign)
                        <div class="col-lg-6 col-xl-4">
                            <div class="card campaign-card h-100 border-0 rounded-4 shadow-sm">
                                <!-- Campaign Image -->
                                <div class="position-relative">
                                    <img src="{{ $campaign->image_url }}" 
                                         class="card-img-top rounded-top-4" 
                                         alt="{{ $campaign->title }}"
                                         style="height: 200px; object-fit: cover;">
                                    
                                    <!-- Status Badge -->
                                    <div class="position-absolute top-0 end-0 m-3">
                                        {!! $campaign->status_badge !!}
                                    </div>

                                    <!-- Category Badge -->
                                    <div class="position-absolute bottom-0 start-0 m-3">
                                        <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-3">
                                            {{ $campaign->category_icon }} {{ $campaign->category_name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    <!-- Campaign Title -->
                                    <h5 class="card-title fw-bold mb-2 text-truncate" title="{{ $campaign->title }}">
                                        {{ $campaign->title }}
                                    </h5>

                                    <!-- Campaign Stats -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">Progress</span>
                                            <span class="fw-semibold text-primary">{{ $campaign->progress_percentage }}%</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height: 6px;">
                                            <div class="progress-bar bg-primary rounded-pill" 
                                                 style="width: {{ $campaign->progress_percentage }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Amount Info -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded-3">
                                                <div class="small text-muted">Terkumpul</div>
                                                <div class="fw-semibold text-primary small">{{ $campaign->formatted_current_amount }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded-3">
                                                <div class="small text-muted">Target</div>
                                                <div class="fw-semibold text-dark small">{{ $campaign->formatted_target_amount }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Time Info -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted small">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            @if($campaign->is_expired)
                                                Berakhir
                                            @else
                                                {{ $campaign->days_remaining }} hari lagi
                                            @endif
                                        </span>
                                        <span class="text-muted small">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $campaign->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <!-- Rejection Reason (if rejected) -->
                                    @if($campaign->verification_status === 'rejected' && $campaign->rejection_reason)
                                        <div class="alert alert-danger py-2 px-3 mb-3 rounded-3">
                                            <small class="mb-0">
                                                <strong>Alasan Penolakan:</strong><br>
                                                {{ $campaign->rejection_reason }}
                                            </small>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Footer -->
                                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                    <div class="d-flex gap-2">
                                        @if($campaign->verification_status === 'approved')
                                            <a href="{{ route('campaigns.show', $campaign->slug) }}" 
                                               class="btn btn-outline-primary btn-sm rounded-pill flex-fill">
                                                <i class="fas fa-eye me-1"></i>Lihat
                                            </a>
                                            <a href="{{ route('user.campaigns.donations', $campaign->id) }}" 
                                               class="btn btn-primary btn-sm rounded-pill flex-fill">
                                                <i class="fas fa-chart-bar me-1"></i>Donatur
                                            </a>
                                        @elseif($campaign->verification_status === 'rejected')
                                            <button class="btn btn-outline-secondary btn-sm rounded-pill flex-fill" disabled>
                                                <i class="fas fa-times me-1"></i>Ditolak
                                            </button>
                                            <a href="{{ route('user.campaigns.edit', $campaign->id) }}" 
                                               class="btn btn-warning btn-sm rounded-pill flex-fill">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                        @else
                                            <button class="btn btn-outline-warning btn-sm rounded-pill w-100" disabled>
                                                <i class="fas fa-clock me-1"></i>Menunggu Verifikasi
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($campaigns->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $campaigns->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted mb-3">Belum Ada Campaign</h5>
                    <p class="text-muted mb-4">Anda belum membuat campaign apapun. Mulai buat campaign pertama Anda untuk membantu sesama!</p>
                    <a href="{{ route('user.campaigns.create') }}" class="btn btn-primary px-4 rounded-pill">
                        <i class="fas fa-plus me-2"></i>Buat Campaign Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Font Global */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fc;
    }

    /* Campaign Card Styling */
    .campaign-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }
    .campaign-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 123, 255, 0.15) !important;
    }

    /* Progress Bar */
    .progress {
        background-color: #e9ecef;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }

    /* Button Styling */
    .btn-primary {
        background-color: #4a90e2;
        border-color: #4a90e2;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #3a7bc8;
        border-color: #3a7bc8;
        transform: scale(1.05);
    }

    .btn-outline-primary {
        color: #4a90e2;
        border-color: #4a90e2;
        font-weight: 500;
    }
    .btn-outline-primary:hover {
        background-color: #4a90e2;
        border-color: #4a90e2;
        transform: scale(1.05);
    }

    /* Alert Styling */
    .alert {
        border: none;
    }

    /* Badge Styling */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Card Image Overlay */
    .position-absolute .badge {
        backdrop-filter: blur(10px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn {
            width: 100%;
        }
        
        .col-6 {
            flex: 0 0 50%;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .campaign-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .campaign-card:nth-child(2) {
        animation-delay: 0.1s;
    }
    .campaign-card:nth-child(3) {
        animation-delay: 0.2s;
    }
    .campaign-card:nth-child(4) {
        animation-delay: 0.3s;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Add loading state to buttons
    const actionButtons = document.querySelectorAll('.btn[href]');
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.hasAttribute('disabled')) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
                this.disabled = true;

                // Re-enable after 3 seconds if still on page
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            }
        });
    });
});
</script>

@endsection