<!-- resources/views/campaign-histori.blade.php -->

@extends('layouts.app')

@section('title', 'Histori Campaign Saya')

@section('content')
<div class="container py-5">
    <h2 class="section-title text-center">Histori Campaign Saya</h2>
    <p class="text-center section-subtitle">Berikut adalah daftar campaign yang pernah Anda ajukan.</p>

    @if($campaigns->isEmpty())
        <div class="text-center py-4">
            <i class="fas fa-folder-open text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Anda belum pernah mengajukan campaign.</p>
            <a href="{{ route('campaign.create') }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus me-1"></i> Buat Campaign Baru
            </a>
        </div>
    @else
        <div class="row">
            @foreach($campaigns as $campaign)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="campaign-card card h-100 shadow-sm">
                        <img src="{{ $campaign->thumbnail ? asset('storage/' . $campaign->thumbnail) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                             class="card-img-top" alt="{{ $campaign->title }}">
                        <div class="card-body">
                            {{-- Enhanced Status Badge with colors and icons --}}
                            @php
                                $statusConfig = [
                                    'pending' => ['class' => 'bg-warning text-dark', 'icon' => 'fas fa-clock', 'text' => 'Pending'],
                                    'approved' => ['class' => 'bg-success', 'icon' => 'fas fa-check-circle', 'text' => 'Disetujui'],
                                    'rejected' => ['class' => 'bg-danger', 'icon' => 'fas fa-times-circle', 'text' => 'Ditolak'],
                                    'active' => ['class' => 'bg-info', 'icon' => 'fas fa-play-circle', 'text' => 'Aktif'],
                                    'completed' => ['class' => 'bg-primary', 'icon' => 'fas fa-flag-checkered', 'text' => 'Selesai'],
                                    'cancelled' => ['class' => 'bg-secondary', 'icon' => 'fas fa-ban', 'text' => 'Dibatalkan'],
                                    'expired' => ['class' => 'bg-dark', 'icon' => 'fas fa-hourglass-end', 'text' => 'Kadaluarsa'],
                                ];
                                $currentStatus = $statusConfig[$campaign->status] ?? ['class' => 'bg-secondary', 'icon' => 'fas fa-question', 'text' => ucfirst($campaign->status)];
                            @endphp
                            
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge {{ $currentStatus['class'] }} d-flex align-items-center">
                                    <i class="{{ $currentStatus['icon'] }} me-1"></i>
                                    {{ $currentStatus['text'] }}
                                </span>
                                
                                {{-- Additional status info --}}
                                @if($campaign->status === 'rejected' && isset($campaign->rejection_reason))
                                    <i class="fas fa-info-circle text-danger" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top" 
                                       title="{{ $campaign->rejection_reason }}"></i>
                                @elseif($campaign->status === 'expired')
                                    <i class="fas fa-calendar-times text-muted" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top" 
                                       title="Berakhir: {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}"></i>
                                @endif
                            </div>

                            <h5 class="card-title">{{ $campaign->title }}</h5>
                            
                            <div class="d-flex justify-content-between text-muted small mb-2">
                                <span>
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Dibuat: {{ $campaign->created_at->format('d M Y') }}
                                </span>
                                @if($campaign->updated_at && $campaign->updated_at != $campaign->created_at)
                                    <span>
                                        <i class="fas fa-edit me-1"></i>
                                        Update: {{ $campaign->updated_at->format('d M Y') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Progress bar (only show for active/approved campaigns) --}}
                            @if(in_array($campaign->status, ['active', 'approved', 'completed']))
                                <div class="progress-custom mb-2">
                                    @php
                                        $progressPercentage = $campaign->current_amount / max($campaign->target_amount, 1) * 100;
                                        $progressClass = $progressPercentage >= 100 ? 'bg-success' : 'bg-primary';
                                    @endphp
                                    <div class="progress">
                                        <div class="progress-bar {{ $progressClass }}" 
                                             role="progressbar" 
                                             style="width: {{ min($progressPercentage, 100) }}%"
                                             aria-valuenow="{{ $progressPercentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ number_format($progressPercentage, 1) }}%
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="mb-2 small">
                                    <strong class="text-success">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</strong> 
                                    <span class="text-muted">dari</span>
                                    <strong>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</strong>
                                </p>
                            @else
                                <p class="mb-2 small text-muted">
                                    Target: <strong>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</strong>
                                </p>
                            @endif

                            {{-- Campaign stats (for active/completed campaigns) --}}
                            @if(in_array($campaign->status, ['active', 'completed']) && isset($campaign->donors_count))
                                <div class="row text-center small text-muted mb-2">
                                    <div class="col-6">
                                        <i class="fas fa-users"></i>
                                        {{ $campaign->donors_count ?? 0 }} Donatur
                                    </div>
                                    <div class="col-6">
                                        @if(isset($campaign->days_remaining))
                                            <i class="fas fa-clock"></i>
                                            {{ $campaign->days_remaining > 0 ? $campaign->days_remaining . ' hari lagi' : 'Berakhir' }}
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Action buttons --}}
                            <div class="d-grid gap-2">
                                <a href="{{ route('campaign.detail', $campaign->id) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat Detail
                                </a>
                                
                                {{-- Additional action buttons based on status --}}
                                @if($campaign->status === 'pending')
                                    <a href="{{ route('campaign.edit', $campaign->id) }}" 
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit Campaign
                                    </a>
                                @elseif($campaign->status === 'active')
                                    <button type="button" 
                                            class="btn btn-outline-success btn-sm"
                                            onclick="shareUrl('{{ route('campaign.detail', $campaign->id) }}')">
                                        <i class="fas fa-share me-1"></i>
                                        Share Campaign
                                    </button>
                                @elseif($campaign->status === 'rejected')
                                    @if(Route::has('campaign.edit'))
                                        <a href="{{ route('campaign.edit', $campaign->id) }}" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-redo me-1"></i>
                                            Edit & Ajukan Ulang
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination if needed --}}
        @if(method_exists($campaigns, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $campaigns->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Initialize tooltips and share functionality --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Share URL function
    function shareUrl(url) {
        if (navigator.share) {
            navigator.share({
                title: 'Campaign Donasi',
                url: url
            }).catch(console.error);
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function() {
                alert('Link campaign telah disalin ke clipboard!');
            }).catch(function() {
                fallbackCopyTextToClipboard(url);
            });
        } else {
            fallbackCopyTextToClipboard(url);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            var successful = document.execCommand('copy');
            if (successful) {
                alert('Link campaign telah disalin ke clipboard!');
            } else {
                alert('Gagal menyalin link. Silakan salin manual: ' + text);
            }
        } catch (err) {
            alert('Gagal menyalin link. Silakan salin manual: ' + text);
        }
        document.body.removeChild(textArea);
    }
</script>
@endpush

{{-- Additional CSS for better styling --}}
@push('styles')
<style>
    .campaign-card {
        transition: transform 0.2s ease-in-out;
        border: 1px solid rgba(0,0,0,0.125);
    }
    
    .campaign-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
    }
    
    .campaign-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .progress-custom .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
    }
    
    .progress-custom .progress-bar {
        border-radius: 4px;
        font-size: 0.7rem;
        line-height: 8px;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .text-success {
        color: #28a745 !important;
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection