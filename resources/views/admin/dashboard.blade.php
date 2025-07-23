@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
    <div class="text-muted">{{ now()->format('d F Y') }}</div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(45deg, #667eea, #764ba2);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Campaigns</h5>
                        <h2>{{ $totalCampaigns }}</h2>
                    </div>
                    <i class="fas fa-bullhorn fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Donations</h5>
                        <h2>{{ $totalDonations }}</h2>
                    </div>
                    <i class="fas fa-hand-holding-heart fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Collected</h5>
                        <h2>Rp {{ number_format($totalCollected, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-money-bill fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Success Rate</h5>
                        <h2>{{ $successRate }}%</h2>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar me-2"></i>Recent Campaigns</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Campaign</th>
                                <th>Target</th>
                                <th>Collected</th>
                                <th>Progress</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCampaigns as $campaign)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($campaign->image)
                                            <img src="{{ asset('storage/campaigns/' . $campaign->image) }}" class="rounded me-2" width="40" height="40">
                                        @else
                                            <div class="bg-secondary rounded me-2" style="width:40px;height:40px;"></div>
                                        @endif
                                        <div>
                                            <strong>{{ $campaign->title }}</strong>
                                            <br><small class="text-muted">{{ $campaign->category }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $progress = $campaign->target_amount > 0 ? ($campaign->collected_amount / $campaign->target_amount) * 100 : 0;
                                    @endphp
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" style="width: {{ min($progress, 100) }}%"></div>
                                    </div>
                                    <small>{{ number_format($progress, 1) }}%</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $campaign->status == 'active' ? 'success' : ($campaign->status == 'completed' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No campaigns found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-clock me-2"></i>Recent Donations</h5>
            </div>
            <div class="card-body">
                @forelse($recentDonations as $donation)
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: #f8f9fa;">
                    <div>
                        <strong>{{ $donation->donor_name }}</strong>
                        <br><small class="text-muted">{{ $donation->campaign->title }}</small>
                    </div>
                    <div class="text-end">
                        <strong class="text-success">Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong>
                        <br><small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">No recent donations</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection