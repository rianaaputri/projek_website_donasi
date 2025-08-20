@extends('layouts.app')

@section('title', 'Detail Campaign')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <img src="{{ asset('storage/' . $campaign->image) }}" class="card-img-top" alt="{{ $campaign->title }}" style="max-height: 350px; object-fit: cover;">
                <div class="card-body">
                    <h3 class="card-title fw-bold">{{ $campaign->title }}</h3>
                    <p class="text-muted">
                        <i class="bi bi-person-circle"></i> Dibuat oleh: {{ $campaign->user->name }}
                    </p>
                    <p>{{ $campaign->description }}</p>

                    <div class="progress my-3">
                        @php
                            $progress = $campaign->target_amount > 0 ? round(($campaign->collected_amount / $campaign->target_amount) * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>

                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <h5 class="fw-bold">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</h5>
                            <small class="text-muted">Terkumpul</small>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</h5>
                            <small class="text-muted">Target</small>
                        </div>
                    </div>

                    <p class="mb-1"><i class="bi bi-calendar-event"></i> Berakhir pada: {{ \Carbon\Carbon::parse($campaign->end_date)->translatedFormat('d F Y') }}</p>
                    <p class="mb-1"><i class="bi bi-flag"></i> Status Verifikasi: 
                        <span class="badge bg-{{ $campaign->verification_status == 'approved' ? 'success' : ($campaign->verification_status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($campaign->verification_status) }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Daftar Donasi Terbaru --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">Donasi Terbaru</div>
                <div class="card-body">
                    @if($campaign->recent_donations->count() > 0)
                        <ul class="list-group">
                            @foreach($campaign->recent_donations as $donation)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $donation->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                    </div>
                                    <span class="fw-bold text-success">Rp {{ number_format($donation->amount, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Belum ada donasi untuk campaign ini.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="{{ route('user.campaigns.history') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
