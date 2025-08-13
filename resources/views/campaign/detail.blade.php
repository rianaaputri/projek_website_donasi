@extends('layouts.app')

@section('title', $campaign->title . ' - Donasi Online')

@section('content')
<style>
    :root {
        --blue-50: #f0f9ff;
        --blue-100: #e0f2fe;
        --blue-200: #bae6fd;
        --blue-300: #7dd3fc;
        --blue-400: #38bdf8;
        --blue-500: #0ea5e9;
        --blue-600: #0284c7;
        --blue-700: #0369a1;
        --blue-800: #075985;
    }

    .btn-animate {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-animate::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-animate:hover::before {
        left: 100%;
    }

    .btn-animate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.3) !important;
    }

    .btn-animate:active {
        transform: translateY(0);
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(14, 165, 233, 0.1) !important;
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .progress-bar-animated-custom {
        background: linear-gradient(45deg, var(--blue-400), var(--blue-600));
        animation: progress-shine 2s linear infinite;
    }

    @keyframes progress-shine {
        0% { background-position: -200px 0; }
        100% { background-position: 200px 0; }
    }

    .icon-bounce {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .bg-blue-light {
        background: linear-gradient(135deg, var(--blue-50) 0%, var(--blue-100) 100%);
    }

    .text-blue-light {
        color: var(--blue-500);
    }

    .border-blue-light {
        border-color: var(--blue-200) !important;
    }

    .bg-blue-gradient {
        background: linear-gradient(135deg, var(--blue-400) 0%, var(--blue-600) 100%);
    }
</style>

<div class="bg-blue-light min-vh-100">
    <div class="container py-5">
        <!-- Enhanced Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4 fade-in">
            <ol class="breadcrumb bg-white rounded-pill px-4 py-3 shadow-sm border border-blue-light">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none fw-medium btn-animate text-blue-light">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </li>
                <li class="breadcrumb-item active text-muted">{{ $campaign->title }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Campaign Main Content -->
            <div class="col-lg-8">
                <!-- Main Campaign Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 card-hover fade-in">
                    @if($campaign->image)
                        <div class="position-relative overflow-hidden">
                            <img src="{{ asset('storage/' . $campaign->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $campaign->title }}"
                                 style="height: 400px; object-fit: cover; transition: transform 0.3s ease;">
                        </div>
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-blue-gradient" 
                             style="height: 400px;">
                            <i class="fas fa-image text-white opacity-75 icon-bounce" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body p-4">
                        <!-- Enhanced Status Badges -->
                        <div class="mb-4">
                            <span class="badge fs-6 px-3 py-2 rounded-pill btn-animate bg-blue-gradient text-white">
                                <i class="fas fa-tag me-1"></i>{{ $campaign->category }}
                            </span>
                            @if($campaign->status === 'completed')
                                <span class="badge bg-success bg-gradient fs-6 ms-2 px-3 py-2 rounded-pill btn-animate">
                                    <i class="fas fa-check me-1"></i>Selesai
                                </span>
                            @elseif($campaign->status === 'active')
                                <span class="badge fs-6 ms-2 px-3 py-2 rounded-pill btn-animate bg-blue-gradient text-white pulse-animation">
                                    <i class="fas fa-clock me-1"></i>Aktif
                                </span>
                            @endif
                        </div>
                        
                        <h1 class="card-title h2 mb-4 text-dark fw-bold">{{ $campaign->title }}</h1>
                        
                        <!-- Enhanced Campaign Description -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-blue-gradient rounded-circle p-3 me-3 btn-animate d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-info text-white"></i>
                                </div>
                                <h4 class="mb-0 text-blue-light fw-bold">Deskripsi Campaign</h4>
                            </div>
                            <div class="bg-white rounded-3 p-4 border-start border-4 shadow-sm" style="border-color: var(--blue-400) !important;">
                                <div class="text-muted" style="line-height: 1.8; font-size: 1.05rem;">
                                    {!! nl2br(e($campaign->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced Recent Donors Section -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden card-hover fade-in">
                    <div class="card-header bg-blue-gradient text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-users me-2"></i>
                            Donatur Terbaru ({{ $recentDonors->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($recentDonors->isEmpty())
                            <div class="text-center py-5">
                                <div class="bg-blue-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 pulse-animation" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-heart text-blue-light" style="font-size: 2rem;"></i>
                                </div>
                                <h6 class="text-muted mb-2">Belum ada donatur</h6>
                                <p class="text-muted small">Jadilah yang pertama berkontribusi!</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($recentDonors as $donation)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-blue-light rounded-3 border border-blue-light h-100 btn-animate">
                                            <div class="bg-blue-gradient text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
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
                                                    <div class="mt-2 p-2 bg-white rounded-2 border-start border-3" style="border-color: var(--blue-400) !important;">
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

            <!-- Enhanced Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 sticky-top card-hover fade-in" style="top: 20px;">
                    <div class="card-body p-4">
                        <!-- Enhanced Progress Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-bold text-dark">
                                    <i class="fas fa-chart-line text-blue-light me-2"></i>Progress Donasi
                                </h5>
                                <span class="badge fs-6 px-3 py-2 rounded-pill btn-animate bg-blue-gradient text-white">
                                    {{ number_format($campaign->progress_percentage, 1) }}%
                                </span>
                            </div>
                            
                            <div class="progress mb-4 rounded-pill shadow-sm" style="height: 15px;">
                                <div class="progress-bar progress-bar-animated-custom rounded-pill" 
                                     role="progressbar" 
                                     style="width: {{ $campaign->progress_percentage }}%;">
                                </div>
                            </div>
                            
                            <div class="row text-center g-3">
                                <div class="col-6">
                                    <div class="bg-blue-light rounded-3 p-3 border border-blue-light btn-animate">
                                        <h4 class="text-success mb-1 fw-bold">{{ $campaign->formatted_collected }}</h4>
                                        <small class="text-muted fw-medium">
                                            <i class="fas fa-coins me-1"></i>Terkumpul
                                        </small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-blue-light rounded-3 p-3 border border-blue-light btn-animate">
                                        <h4 class="text-blue-light mb-1 fw-bold">{{ $campaign->formatted_target }}</h4>
                                        <small class="text-muted fw-medium">
                                            <i class="fas fa-bullseye me-1"></i>Target
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced Donation Stats -->
                        <div class="row text-center mb-4 g-3">
                            <div class="col-6">
                                <div class="p-3 rounded-3 border btn-animate" style="background: rgba(14, 165, 233, 0.1); border-color: rgba(14, 165, 233, 0.25) !important;">
                                    <div class="text-blue-light mb-2">
                                        <i class="fas fa-users icon-bounce" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <h5 class="mb-1 fw-bold text-blue-light">{{ $campaign->donations->count() }}</h5>
                                    <small class="text-muted fw-medium">Donatur</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 border btn-animate" style="background: rgba(14, 165, 233, 0.1); border-color: rgba(14, 165, 233, 0.25) !important;">
                                    <div class="text-blue-light mb-2">
                                        <i class="fas fa-calendar-alt icon-bounce" style="font-size: 1.5rem;"></i>
                                    </div>
                                    {{ optional($campaign->created_at)->diffInDays(now()) ?? 0 }}
                                    <small class="text-muted fw-medium">Hari berjalan</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced Donation Button -->
                        @if($campaign->status === 'active' && $campaign->is_active)
                            <div class="d-grid mb-4">
                                <a href="{{ route('donation.create', $campaign->id) }}" 
                                   class="btn btn-success btn-lg rounded-pill py-3 fw-bold shadow-sm btn-animate pulse-animation">
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
                        
                        <!-- Enhanced Share Buttons -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add intersection observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all fade-in elements
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Add click effect for animated buttons
    document.querySelectorAll('.btn-animate').forEach(button => {
        button.addEventListener('click', function(e) {
            let ripple = document.createElement('span');
            let rect = this.getBoundingClientRect();
            let size = Math.max(rect.width, rect.height);
            let x = e.clientX - rect.left - size / 2;
            let y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
</script>

<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-effect 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-effect {
    to {
        transform: scale(2);
        opacity: 0;
    }
}
</style>

@endsection