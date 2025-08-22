@extends('layouts.admin')

@section('title', 'Manage Campaigns')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-bullhorn me-2"></i>Manage Campaigns</h2>
    {{-- Tombol Add New Campaign dihapus --}}
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Campaign</th>
                        <th>Category</th>
                        <th>Target</th>
                        <th>Collected</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($campaign->image)
                                    <img src="{{ asset('storage/' . $campaign->image) }}" class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $campaign->title }}</h6>
                                    <small class="text-muted">{{ Str::limit($campaign->description, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-info">{{ $campaign->category }}</span></td>
                        <td>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $progress = $campaign->target_amount > 0 ? ($campaign->collected_amount / $campaign->target_amount) * 100 : 0;
                            @endphp
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar" style="width: {{ min($progress, 100) }}%"></div>
                            </div>
                            <small>{{ number_format($progress, 1) }}%</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $campaign->status == 'active' ? 'success' : ($campaign->status == 'completed' ? 'primary' : 'secondary') }}">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td>
                            {{-- Hanya tombol Show yang tersisa --}}
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.campaigns.show', $campaign->id) }}" class="btn btn-outline-info" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            No campaigns found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $campaigns->links() }}
    </div>
</div>

{{-- Modal Delete dihapus karena sudah tidak digunakan --}}
@endsection

{{-- Script untuk confirmDelete dihapus karena sudah tidak digunakan --}}