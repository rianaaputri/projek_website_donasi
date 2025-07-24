{{-- File: resources/views/campaigns/detail.blade.php --}}
@extends('layouts.app')

@section('title', $campaign->title)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            {{-- Campaign Image --}}
            <div class="card mb-4">
                <img src="{{ $campaign->image ? asset('storage/' . $campaign->image) : asset('images/default-campaign.jpg') }}" 
                     class="card-img-top" alt="{{ $campaign->title }}" style="height: 400px; object-fit: cover;">
            </div>

            {{-- Campaign Description --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Deskripsi Kampanye</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{!! nl2br(e($campaign->description)) !!}</p>
                </div>
            </div>

            {{-- Recent Donations --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Donasi Terbaru</h5>
                </div>
                <div class="card-body">
                    @forelse($campaign->donations as $donation)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; min-width: 40px;">
                                <small><strong>{{ substr($donation->display_name, 0, 1) }}</strong></small>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="fw-bold">{{ $donation->display_name }}</div>
                                <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                @if($donation->message)
                                    <div class="small text-muted mt-1">{{ $donation->message }}</div>
                                @endif
                            </div>
                            <div class="text-success fw-bold">
                                {{ $donation->formatted_amount }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada donasi untuk kampanye ini.</p>
                    @endforelse
                </div>
            </div>

            {{-- Comments --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Komentar & Dukungan</h5>
                </div>
                <div class="card-body">
                    @forelse($campaign->comments as $comment)
                        <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 35px; height: 35px; min-width: 35px;">
                                <small><strong>{{ $comment->avatar_initials }}</strong></small>
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">{{ $comment->display_name }}</div>
                                <div class="small text-muted mb-1">{{ $comment->created_at->diffForHumans() }}</div>
                                <p class="mb-0">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada komentar untuk kampanye ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h4 class="card-title">{{ $campaign->title }}</h4>
                    
                    {{-- Campaign Stats --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Terkumpul:</span>
                            <strong class="text-success">{{ $campaign->formatted_current_amount }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Target:</span>
                            <span>{{ $campaign->formatted_target_amount }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $campaign->progress_percentage }}%"></div>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="fw-bold text-primary">{{ $campaign->donors_count }}</div>
                                <small class="text-muted">Donatur</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold text-info">{{ number_format($campaign->progress_percentage, 1) }}%</div>
                                <small class="text-muted">Tercapai</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold {{ $campaign->days_left > 7 ? 'text-success' : 'text-danger' }}">
                                    {{ $campaign->days_left }}
                                </div>
                                <small class="text-muted">Hari lagi</small>
                            </div>
                        </div>
                    </div>

                    {{-- Category & Urgency Badges --}}
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ ucfirst($campaign->category) }}</span>
                        @if($campaign->is_urgent)
                            <span class="badge bg-danger">Mendesak</span>
                        @endif
                    </div>

                    {{-- Donate Button --}}
                    @if($campaign->is_active && !$campaign->is_expired)
                        <a href="{{ route('donate.form', $campaign->id) }}" class="btn btn-success w-100 btn-lg">
                            <i class="fas fa-heart"></i> Donasi Sekarang
                        </a>
                        <div class="text-center mt-2">
                            <small class="text-muted">Donasi aman & terpercaya</small>
                        </div>
                    @else
                        <button class="btn btn-secondary w-100 btn-lg" disabled>
                            {{ $campaign->is_expired ? 'Kampanye Berakhir' : 'Kampanye Tidak Aktif' }}
                        </button>
                    @endif

                    {{-- Share Buttons --}}
                    <div class="mt-4">
                        <h6>Bagikan Kampanye:</h6>
                        <div class="btn-group w-100" role="group">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" class="btn btn-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($campaign->title) }}" 
                               target="_blank" class="btn btn-info btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . request()->url()) }}" 
                               target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Back Button --}}
<div class="container mt-3">
    <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kampanye
    </a>
</div>
@endsection