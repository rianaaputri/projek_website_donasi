@extends('layouts.admin')

@section('title', 'Manage Campaigns')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-bullhorn me-2"></i>Manage Campaigns</h2>
    <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Campaign
    </a>
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
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($campaign->image)
                                    <img src="{{ Storage::url($campaign->image) }}" class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded me-3" style="width:60px;height:60px;"></div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $campaign->title }}</h6>
                                    <small>{{ Str::limit($campaign->description, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $campaign->category }}</td>
                        <td>Rp {{ number_format($campaign->target_amount) }}</td>
                        <td>
                            @php
                                $collected = $campaign->donations->sum('amount');
                                $percentage = $campaign->target_amount > 0 ? ($collected/$campaign->target_amount)*100 : 0;
                            @endphp
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                            </div>
                            <small>{{ number_format($percentage, 1) }}%</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete('{{ $campaign->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No campaigns found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $campaigns->links() }}
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this campaign? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(campaignId) {
    document.getElementById('deleteForm').action = '/admin/campaigns/' + campaignId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(campaignId) {
    document.getElementById('deleteForm').action = '/admin/campaigns/' + campaignId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
