@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('styles')
<style>
    .stats-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    
    .content-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }
    
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        background: linear-gradient(45deg, #667eea, #764ba2);
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 6px 12px;
        border-radius: 20px;
    }
    
    .recent-donation-item {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .recent-donation-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
    <div class="text-muted">{{ date('d F Y') }}</div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card text-white" style="background: linear-gradient(45deg, #667eea, #764ba2);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Campaigns</h5>
                        <h2 id="total-campaigns">{{ $stats['total_campaigns'] ?? 0 }}</h2>
                        <small class="text-white-50">
                            <a href="{{ route('admin.campaigns.index') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-arrow-right me-1"></i>View All
                            </a>
                        </small>
                    </div>
                    <i class="fas fa-bullhorn fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card text-white" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Donations</h5>
                        <h2 id="total-donations">{{ $stats['total_donations'] ?? 0 }}</h2>
                        <small class="text-white-50">
                            <a href="{{ route('admin.donations.index') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-arrow-right me-1"></i>View All
                            </a>
                        </small>
                    </div>
                    <i class="fas fa-hand-holding-heart fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card text-white" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Collected</h5>
                        <h2 id="total-collected">Rp {{ number_format($stats['total_collected'] ?? 0, 0, ',', '.') }}</h2>
                        <small class="text-white-50">This month</small>
                    </div>
                    <i class="fas fa-money-bill fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card text-white" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Users</h5>
                        <h2 id="total-users">{{ $stats['total_users'] ?? 0 }}</h2>
                        <small class="text-white-50">Regular users only</small>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-lg-8">
        <div class="card content-card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Recent Campaigns</h5>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_campaigns ?? [] as $campaign)
                                @php
                                    $progress = $campaign->target_amount > 0 ? ($campaign->current_amount / $campaign->target_amount) * 100 : 0;
                                    $progress = min($progress, 100);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($campaign->image)
                                                <img src="{{ asset('storage/' . $campaign->image) }}" 
                                                     class="rounded me-2" 
                                                     style="width:40px;height:40px;object-fit:cover;" 
                                                     alt="{{ $campaign->title }}">
                                            @else
                                                <div class="bg-primary rounded me-2" style="width:40px;height:40px;"></div>
                                            @endif
                                            <div>
                                                <strong>{{ Str::limit($campaign->title, 20) }}</strong>
                                                <br><small class="text-muted">{{ $campaign->category ?? 'General' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <small>{{ number_format($progress, 1) }}%</small>
                                    </td>
                                    <td>
                                        @if($campaign->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($campaign->status === 'completed')
                                            <span class="badge bg-primary">Completed</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-sm btn-outline-primary" title="View Campaign">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Campaign">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <br>Belum ada campaign
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card content-card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Donations</h5>
            </div>
            <div class="card-body">
                @forelse($recent_donations ?? [] as $donation)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 recent-donation-item">
                        <div>
                            <strong>{{ $donation->donor_name ?? 'Anonymous' }}</strong>
                            <br><small class="text-muted">{{ Str::limit($donation->campaign->title ?? 'Unknown Campaign', 25) }}</small>
                        </div>
                        <div class="text-end">
                            <strong class="text-success">Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong>
                            <br><small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-heart fa-2x mb-2"></i>
                        <br>Belum ada donasi
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm deletion actions
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        // Optional: Add click animations to stats cards
        document.querySelectorAll('.stats-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    });
</script>
@endsection