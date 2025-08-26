@extends('layouts.app')

@section('title', 'Donasi Campaign - ' . $campaign->title)

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
        <!-- Page Header -->
        <div class="page-header-card mb-4">
            <div class="header-content">
                <h1 class="header-title">Donasi Campaign</h1>
                <p class="header-subtitle">Lihat semua donasi yang telah terkumpul untuk campaign ini</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Campaign Info Card -->
                <div class="main-campaign-card mb-4">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <!-- Campaign Image -->
                            <div class="campaign-image-wrapper">
                                @if($campaign->image)
                                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                                         class="campaign-image" 
                                         alt="{{ $campaign->title }}">
                                @else
                                    <img src="https://via.placeholder.com/400x300/e8f4fd/3b82f6?text=Campaign+Image" 
                                         class="campaign-image" 
                                         alt="Campaign Image">
                                @endif
                                
                                <!-- Image Overlay -->
                                <div class="image-overlay"></div>
                                
                                <!-- Category Badge -->
                                <div class="category-badge">
                                    <span class="badge-content">
                                        <i class="fas fa-tag me-2"></i>{{ $campaign->category }}
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
                        </div>
                        <div class="col-md-7">
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
                                        <i class="fas fa-flag info-icon"></i>
                                        <span>Status: </span>
                                        <span class="verification-badge status-{{ $campaign->verification_status }}">
                                            <i class="fas 
                                                @if($campaign->verification_status === 'approved') fa-check-circle
                                                @elseif($campaign->verification_status === 'rejected') fa-times-circle
                                                @else fa-clock @endif me-1"></i>
                                            @if($campaign->verification_status === 'approved')
                                                Disetujui
                                            @elseif($campaign->verification_status === 'rejected')
                                                Ditolak
                                            @else
                                                Menunggu Verifikasi
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donations Card -->
                <div class="donations-card">
                    <div class="donations-header">
                        <h5 class="donations-title">
                            <i class="fas fa-heart me-2"></i>Donasi Terbaru
                            @if($donations->total() > 0)
                                <span class="donation-count">{{ $donations->total() }} donasi</span>
                            @endif
                        </h5>
                    </div>
                    <div class="donations-body">
                        @if($donations->count() > 0)
                            <div class="donations-table-wrapper">
                                <table class="donations-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><i class="fas fa-user me-2"></i>Nama Donatur</th>
                                            <th><i class="fas fa-money-bill-wave me-2"></i>Jumlah</th>
                                            <th><i class="fas fa-clock me-2"></i>Waktu</th>
                                            <th><i class="fas fa-comment me-2"></i>Pesan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($donations as $index => $donation)
                                            <tr class="donation-row">
                                                <td>
                                                    <span class="row-number">{{ $donations->firstItem() + $index }}</span>
                                                </td>
                                                <td>
                                                    <div class="donor-info">
                                                        <div class="donor-avatar">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div class="donor-details">
                                                            <div class="donor-name">{{ $donation->user->name ?? 'Donatur Anonim' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="donation-amount">
                                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                                </td>
                                                <td class="donation-time">
                                                    {{ $donation->created_at->format('d M Y') }}<br>
                                                    <small>{{ $donation->created_at->format('H:i') }}</small>
                                                </td>
                                                <td class="donation-message" title="{{ $donation->message ?? 'Tanpa pesan' }}">
                                                    {{ $donation->message ?? 'Tanpa pesan' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($donations->hasPages())
                                <div class="pagination-wrapper">
                                    {{ $donations->links() }}
                                </div>
                            @endif
                        @else
                            <div class="no-donations">
                                <i class="fas fa-heart-broken"></i>
                                <h5>Belum Ada Donasi</h5>
                                <p>Campaign ini belum menerima donasi. Jadilah yang pertama untuk berdonasi!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
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

    /* Page Header Card */
    .page-header-card {
        background: var(--glass-white);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 2;
    }

    .header-content {
        padding: 2.5rem;
        text-align: center;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--blue-800);
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .header-subtitle {
        color: var(--blue-600);
        font-size: 1.1rem;
        margin: 0;
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

    /* Campaign Image */
    .campaign-image-wrapper {
        position: relative;
        width: 100%;
        height: 400px;
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
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--glass-border);
    }

    .donations-title {
        color: white;
        font-weight: 600;
        margin: 0;
        font-size: 1.2rem;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .donation-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .donations-body {
        padding: 0;
    }

    /* Donations Table */
    .donations-table-wrapper {
        overflow-x: auto;
    }

    .donations-table {
        width: 100%;
        border-collapse: collapse;
    }

    .donations-table thead th {
        background: var(--blue-50);
        color: var(--blue-700);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 1.25rem 1.5rem;
        border: none;
        text-align: left;
    }

    .donations-table tbody tr.donation-row {
        background: rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    .donations-table tbody tr.donation-row:hover {
        background: rgba(255, 255, 255, 0.7);
        transform: translateX(5px);
    }

    .donations-table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border: none;
    }

    .row-number {
        background: var(--primary-blue);
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .donor-info {
        display: flex;
        align-items: center;
    }

    .donor-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--light-blue), var(--primary-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 0.75rem;
        font-size: 0.9rem;
    }

    .donor-name {
        font-weight: 600;
        color: var(--blue-800);
        font-size: 0.95rem;
    }

    .donation-amount {
        font-weight: 700;
        color: var(--success-color);
        font-size: 1rem;
    }

    .donation-time {
        color: var(--blue-600);
        font-size: 0.85rem;
        line-height: 1.3;
    }

    .donation-message {
        color: var(--blue-600);
        font-style: italic;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.9rem;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination .page-link {
        color: var(--primary-blue);
        background: var(--glass-white);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        padding: 0.75rem 1rem;
        margin: 0 0.125rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: rgba(255, 255, 255, 0.4);
        border-color: var(--soft-blue);
        color: var(--blue-700);
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .no-donations {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--blue-600);
    }

    .no-donations i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--soft-blue);
    }

    .no-donations h5 {
        color: var(--blue-800);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .no-donations p {
        color: var(--blue-600);
        margin: 0;
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
        transition: all 0.4s ease;
    }

    .sidebar-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 70px rgba(0, 0, 0, 0.15);
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
    @media (max-width: 1200px) {
        .header-title {
            font-size: 2rem;
        }
        
        .campaign-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 992px) {
        .content-wrapper {
            padding: 1.5rem;
        }
        
        .campaign-image-wrapper {
            height: 300px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .donations-table thead th,
        .donations-table tbody td {
            padding: 1rem;
        }
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .header-content {
            padding: 2rem 1.5rem;
        }
        
        .header-title {
            font-size: 1.75rem;
        }
        
        .content-wrapper {
            padding: 1rem;
        }
        
        .donations-header {
            padding: 1rem 1.5rem;
        }
        
        .sidebar-body {
            padding: 1.5rem;
        }
        
        .campaign-title {
            font-size: 1.3rem;
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
            height: 250px;
        }
        
        .donations-table thead th,
        .donations-table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
        
        .donation-message {
            max-width: 120px;
        }
        
        .donations-title {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
    }

    @media (max-width: 576px) {
        .header-content {
            padding: 1.5rem 1rem;
        }
        
        .header-title {
            font-size: 1.5rem;
        }
        
        .campaign-image-wrapper {
            height: 200px;
        }
        
        .donations-table thead th,
        .donations-table tbody td {
            padding: 0.5rem 0.25rem;
            font-size: 0.8rem;
        }
        
        .donation-message {
            max-width: 80px;
        }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
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