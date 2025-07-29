@extends('layouts.admin')

@section('title', $campaign->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Campaign Details</h1>
    <div>
        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                @if($campaign->image)
                    <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->title }}" 
                         class="img-fluid rounded mb-4" style="max-height: 400px; width: 100%; object-fit: cover;">
                @endif

                <h2>{{ $campaign->title }}</h2>
                <div class="d-flex gap-3 text-muted mb-4">
                    <div><i class="fas fa-calendar me-1"></i>{{ $campaign->created_at->format('d M Y') }}</div>
                    <div><i class="fas fa-tag me-1"></i>{{ $campaign->category }}</div>
                </div>

                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="text-muted">{{ $campaign->description }}</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Target Amount</h5>
                        <p class="text-muted">Rp {{ number_format($campaign->target_amount) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>End Date</h5>
                        <p class="text-muted">{{ $campaign->end_date?->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donations List -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Donations</h5>
            </div>
            <div class="card-body">
                @forelse($campaign->donations()->latest()->take(5)->get() as $donation)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">{{ $donation->donor_name }}</h6>
                            <small class="text-muted">{{ $donation->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">Rp {{ number_format($donation->amount) }}</div>
                            <span class="badge bg-{{ $donation->status == 'success' ? 'success' : 'warning' }}">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3">No donations yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Campaign Statistics</h5>
                @php
                    $collected = $campaign->donations()->where('status', 'success')->sum('amount');
                    $percentage = $campaign->target_amount > 0 ? ($collected/$campaign->target_amount)*100 : 0;
                @endphp
                <div class="progress mb-2" style="height: 10px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Collected</div>
                    <div>Rp {{ number_format($collected) }}</div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>Progress</div>
                    <div>{{ number_format($percentage, 1) }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
