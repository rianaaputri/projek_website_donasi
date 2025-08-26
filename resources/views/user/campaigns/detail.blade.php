@extends('layouts.app')

@section('title', 'Detail Campaign')

@section('content')

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<div class="min-vh-100" style="background: linear-gradient(135deg, #e8f4fd 0%, #f0f8ff 25%, #f8fafc 50%, #e3f2fd 75%, #dbeafe 100%);">
    
    <!-- Floating Background Elements -->
    <div class="floating-bg">
        <div class="float-element float-1"></div>
        <div class="float-element float-2"></div>
        <div class="float-element float-3"></div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <!-- Main Campaign Card -->
                <div class="main-campaign-card mb-4">
                    <!-- Campaign Image - Landscape -->
                    <div class="campaign-image-wrapper">
                        <img src="{{ asset('storage/' . $campaign->image) }}" 
                             class="campaign-image" 
                             alt="{{ $campaign->title }}">
                        
                        <!-- Image Overlay -->
                        <div class="image-overlay"></div>
                        
                        <!-- Category Badge on Image -->
                        <div class="category-badge">
                            <span class="badge-content">
                                <i class="fas fa-image me-2"></i>Campaign
                            </span>
                        </div>

                        <!-- Progress Ring -->
                        <div class="progress-ring-overlay">
                            <div class="progress-circle">
                                @php
                                    $progress = $campaign->target_amount > 0 ? round(($campaign->collected_amount / $campaign->target_amount) * 100) : 0;
                                @endphp
                                <svg width="70" height="70">
                                    <circle cx="35" cy="35" r="30" class="progress-bg"></circle>
                                    <circle cx="35" cy="35" r="30" class="progress-fill" 
                                            style="stroke-dasharray: {{ 188.4 * $progress / 100 }} 188.4;"></circle>
                                </svg>
                                <div class="progress-text">{{ $progress }}%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Content -->
                    <div class="content-wrapper">
                        <!-- Title and Creator -->
                        <div class="title-section">
                            <h3 class="campaign-title">{{ $campaign->title }}</h3>
                            <p class="creator-info">
                                <i class="fas fa-user-circle me-2"></i>
                                <span>Dibuat oleh: <strong>{{ $campaign->user->name }}</strong></span>
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="description-section">
                            <p class="campaign-description">{{ $campaign->description }}</p>
                        </div>

                        <!-- Enhanced Progress Bar -->
                        <div class="progress-section">
                            <div class="custom-progress">
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: {{ $progress }}%">
                                        <div class="progress-shine"></div>
                                    </div>
                                </div>
                                <div class="progress-label">{{ $progress }}%</div>
                            </div>
                        </div>

                        <!-- Statistics Grid -->
                        <div class="stats-grid">
                            <div class="stat-card stat-collected">
                                <div class="stat-icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</div>
                                    <div class="stat-label">Terkumpul</div>
                                </div>
                            </div>
                            
                            <div class="stat-card stat-target">
                                <div class="stat-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</div>
                                    <div class="stat-label">Target</div>
                                </div>
                            </div>
                        </div>

                        <!-- Campaign Info -->
                        <div class="campaign-info">
                            <div class="info-item">
                                <i class="fas fa-calendar-alt info-icon"></i>
                                <span>Berakhir pada: <strong>{{ \Carbon\Carbon::parse($campaign->end_date)->translatedFormat('d F Y') }}</strong></span>
                            </div>
                            
                            <div class="info-item">
                                <i class="fas fa-flag info-icon"></i>
                                <span>Status Verifikasi: </span>
                                <span class="verification-badge status-{{ $campaign->verification_status }}">
                                    <i class="fas 
                                        @if($campaign->verification_status === 'approved') fa-check-circle
                                        @elseif($campaign->verification_status === 'rejected') fa-times-circle
                                        @else fa-clock @endif me-1"></i>
                                    {{ ucfirst($campaign->verification_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Donations Card -->
                <div class="donations-card">
                    <div class="donations-header">
                        <h5 class="donations-title">
                            <i class="fas fa-heart me-2"></i>Donasi Terbaru
                        </h5>
                    </div>
                    <div class="donations-body">
                        @if($campaign->recent_donations->count() > 0)
                            <div class="donations-list">
                                @foreach($campaign->recent_donations as $donation)
                                    <div class="donation-item">
                                        <div class="donor-info">
                                            <div class="donor-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="donor-details">
                                                <div class="donor-name">{{ $donation->user->name }}</div>
                                                <div class="donation-time">{{ $donation->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <div class="donation-amount">
                                            Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-donations">
                                <i class="fas fa-heart-broken"></i>
                                <p>Belum ada donasi untuk campaign ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="sidebar-card">
                    <div class="sidebar-body">
                        <a href="{{ route('user.campaigns.history') }}" class="back-button">
                            <div class="button-content">
                                <i class="fas fa-arrow-left me-2"></i>
                                <span>Kembali ke Riwayat</span>
                            </div>
                            <div class="button-glow"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Base Font */
    body {
        font-family: 'Poppins', sans-serif !important;
        font-weight: 400;
        line-height: 1.6;
    }

    /* Color Variables */
    :root {
        --primary-blue: #3b82f6;
        --light-blue: #60a5fa;
        --soft-blue: #93c5fd;
        --pale-blue: #dbeafe;
        --blue-50: #eff6ff;
        --blue-100: #dbeafe;
        --blue-200: #bfdbfe;
        --blue-600: #2563eb;
        --blue-700: #1d4ed8;
        --blue-800: #1e40af;
        --glass-white: rgba(255, 255, 255, 0.25);
        --glass-border: rgba(255, 255, 255, 0.2);
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
    }

    /* Floating Background */
    .floating-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .float-element {
        position: absolute;
        border-radius: 50%;
        opacity: 0.6;
        animation: floating 8s ease-in-out infinite;
    }

    .float-1 {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--soft-blue), var(--light-blue));
        top: 10%;
        left: 5%;
        animation-delay: 0s;
    }

    .float-2 {
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, var(--pale-blue), var(--soft-blue));
        top: 70%;
        right: 10%;
        animation-delay: 2s;
    }

    .float-3 {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--light-blue), var(--primary-blue));
        bottom: 30%;
        left: 20%;
        animation-delay: 4s;
    }

    @keyframes floating {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        33% { transform: translateY(-20px) rotate(120deg); }
        66% { transform: translateY(20px) rotate(240deg); }
    }

    /* Main Campaign Card */
    .main-campaign-card {
        background: var(--glass-white);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
        z-index: 2;
        transition: all 0.4s ease;
    }

    .main-campaign-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 70px rgba(0, 0, 0, 0.15);
    }

    /* Campaign Image - Landscape */
    .campaign-image-wrapper {
        position: relative;
        width: 100%;
        height: 350px;
        overflow: hidden;
    }

    .campaign-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }

    .main-campaign-card:hover .campaign-image {
        transform: scale(1.05);
    }

    /* Image Overlay */
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 197, 253, 0.05) 100%);
        z-index: 1;
    }

    .category-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 3;
        animation: bounceIn 1s ease;
    }

    .badge-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        color: var(--blue-700);
        padding: 10px 18px;
        border-radius: 25px;
        font-weight: 500;
        font-size: 0.9rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        display: inline-block;
        transition: all 0.3s ease;
    }

    .badge-content:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    /* Progress Ring Overlay */
    .progress-ring-overlay {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 3;
    }

    .progress-circle {
        position: relative;
        width: 70px;
        height: 70px;
    }

    .progress-circle svg {
        transform: rotate(-90deg);
        width: 100%;
        height: 100%;
    }

    .progress-bg {
        fill: none;
        stroke: rgba(255, 255, 255, 0.3);
        stroke-width: 4;
    }

    .progress-fill {
        fill: none;
        stroke: white;
        stroke-width: 4;
        stroke-linecap: round;
        transition: stroke-dasharray 1s ease;
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.6));
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    /* Content Wrapper */
    .content-wrapper {
        padding: 2rem;
        position: relative;
    }

    /* Title Section */
    .title-section {
        margin-bottom: 1.5rem;
    }

    .campaign-title {
        font-weight: 700;
        color: var(--blue-800);
        line-height: 1.3;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .creator-info {
        color: var(--blue-600);
        margin: 0;
        font-size: 0.95rem;
    }

    .creator-info i {
        color: var(--light-blue);
    }

    /* Description Section */
    .description-section {
        margin-bottom: 1.5rem;
    }

    .campaign-description {
        color: var(--blue-700);
        line-height: 1.6;
        font-size: 1rem;
        margin: 0;
    }

    /* Progress Section */
    .progress-section {
        margin: 2rem 0;
        position: relative;
    }

    .custom-progress {
        position: relative;
        margin-bottom: 1rem;
    }

    .progress-track {
        height: 14px;
        background: var(--blue-100);
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--success-color) 0%, #059669 100%);
        border-radius: 10px;
        position: relative;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(16, 185, 129, 0.4);
    }

    .progress-shine {
        position: absolute;
        top: 0;
        left: -50px;
        width: 50px;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shine 2s infinite;
    }

    .progress-label {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    @keyframes shine {
        0% { left: -50px; }
        100% { left: 100%; }
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin: 2rem 0;
    }

    .stat-card {
        background: var(--glass-white);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .stat-collected {
        border-left: 4px solid var(--success-color);
    }

    .stat-target {
        border-left: 4px solid var(--primary-blue);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .stat-collected .stat-icon {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
    }

    .stat-target .stat-icon {
        background: rgba(59, 130, 246, 0.15);
        color: var(--primary-blue);
    }

    .stat-content {
        flex: 1;
        text-align: left;
    }

    .stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--blue-800);
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--blue-600);
    }

    /* Campaign Info */
    .campaign-info {
        margin: 1.5rem 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: translateX(5px);
    }

    .info-icon {
        color: var(--primary-blue);
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }

    .info-item span {
        color: var(--blue-700);
        font-size: 0.95rem;
    }

    /* Verification Badge */
    .verification-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
        margin-left: 0.5rem;
    }

    .status-approved {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-rejected {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Donations Card */
    .donations-card {
        background: var(--glass-white);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
        z-index: 2;
        transition: all 0.4s ease;
    }

    .donations-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 70px rgba(0, 0, 0, 0.15);
    }

    .donations-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--light-blue) 100%);
        padding: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
    }

    .donations-title {
        color: white;
        font-weight: 600;
        margin: 0;
        font-size: 1.2rem;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .donations-body {
        padding: 1.5rem;
    }

    .donations-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .donation-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        transition: all 0.3s ease;
        border-left: 4px solid var(--success-color);
    }

    .donation-item:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: translateX(5px);
    }

    .donor-info {
        display: flex;
        align-items: center;
    }

    .donor-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--light-blue), var(--primary-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
        font-size: 1.1rem;
    }

    .donor-details {
        display: flex;
        flex-direction: column;
    }

    .donor-name {
        font-weight: 600;
        color: var(--blue-800);
        font-size: 0.95rem;
    }

    .donation-time {
        font-size: 0.8rem;
        color: var(--blue-600);
    }

    .donation-amount {
        font-weight: 700;
        color: var(--success-color);
        font-size: 1rem;
    }

    .no-donations {
        text-align: center;
        padding: 2rem;
        color: var(--blue-600);
    }

    .no-donations i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--soft-blue);
    }

    /* Sidebar Card */
    .sidebar-card {
        background: var(--glass-white);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 2;
    }

    .sidebar-body {
        padding: 2rem;
    }

    /* Back Button */
    .back-button {
        display: block;
        width: 100%;
        position: relative;
        padding: 16px 24px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--light-blue) 0%, var(--primary-blue) 100%);
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        border: none;
        text-align: center;
    }

    .back-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }

    .button-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .button-glow {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .back-button:hover .button-glow {
        left: 100%;
    }

    /* Animations */
    @keyframes bounceIn {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .content-wrapper {
            padding: 1.5rem;
        }
        
        .campaign-title {
            font-size: 1.5rem;
        }
        
        .campaign-image-wrapper {
            height: 250px;
        }
    }
    
    @media (max-width: 768px) {
        .content-wrapper {
            padding: 1rem;
        }
        
        .donations-body,
        .sidebar-body {
            padding: 1rem;
        }
        
        .campaign-title {
            font-size: 1.3rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-card {
            padding: 1rem;
        }
        
        .category-badge,
        .progress-ring-overlay {
            position: static;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .floating-bg {
            display: none;
        }
        
        .campaign-image-wrapper {
            height: 200px;
        }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: var(--blue-50);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--light-blue), var(--primary-blue));
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--primary-blue), var(--blue-700));
    }
</style>

@endsection