@extends('layouts.admin')

@section('title', 'Kelola Donasi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Donasi</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.donations.analytics') }}" class="btn btn-info btn-sm">
                <i class="fas fa-chart-bar"></i> Analytics
            </a>
            <button type="button" class="btn btn-success btn-sm" onclick="exportDonations()">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Donasi Sukses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['total_donations'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-heart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Donasi Sukses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['success_donations']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Donasi Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['pending_donations']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Donasi Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['today_donations'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.donations.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="campaign_id">Campaign</label>
                            <select name="campaign_id" id="campaign_id" class="form-control">
                                <option value="">Semua Campaign</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}" 
                                        {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                        {{ $campaign->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Tanggal Dari</label>
                            <input type="date" name="date_from" id="date_from" 
                                   class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Tanggal Sampai</label>
                            <input type="date" name="date_to" id="date_to" 
                                   class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Cari Donatur</label>
                            <input type="text" name="search" id="search" 
                                   class="form-control" placeholder="Nama atau email donatur..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Donasi</h6>
            <div>
                <button type="button" class="btn btn-sm btn-warning" onclick="showBulkUpdateModal()">
                    <i class="fas fa-edit"></i> Bulk Update
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>ID</th>
                            <th>Campaign</th>
                            <th>Donatur</th>
                            <th>Email</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                            <tr>
                                <td>
                                    <input type="checkbox" class="donation-checkbox" 
                                           value="{{ $donation->id }}">
                                </td>
                                <td>{{ $donation->id }}</td>
                                <td>
                                    <a href="{{ route('campaigns.show', $donation->campaign->id) }}" 
                                       target="_blank" class="text-decoration-none">
                                        {{ Str::limit($donation->campaign->title, 30) }}
                                    </a>
                                </td>
                                <td>{{ $donation->donor_name }}</td>
                                <td>{{ $donation->donor_email }}</td>
                                <td>
                                    <strong>Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($donation->status == 'success')
                                        <span class="badge badge-success">Success</span>
                                    @elseif($donation->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Failed</span>
                                    @endif
                                </td>
                                <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.donations.show', $donation->id) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-warning dropdown-toggle" 
                                                    type="button" data-toggle="dropdown">
                                                Status
                                            </button>
                                            <div class="dropdown-menu">
                                                <form method="POST" 
                                                      action="{{ route('admin.donations.update-status', $donation->id) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-clock text-warning"></i> Pending
                                                    </button>
                                                </form>
                                                <form method="POST" 
                                                      action="{{ route('admin.donations.update-status', $donation->id) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="success">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check text-success"></i> Success
                                                    </button>
                                                </form>
                                                <form method="POST" 
                                                      action="{{ route('admin.donations.update-status', $donation->id) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="failed">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-times text-danger"></i> Failed
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $donation->id }})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data donasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $donations->firstItem() ?? 0 }} sampai {{ $donations->lastItem() ?? 0 }} 
                    dari {{ $donations->total() }} hasil
                </div>
                {{ $donations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Update Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="bulkUpdateForm" method="POST" action="{{ route('admin.donations.bulk-update') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bulk_status">Status Baru</label>
                        <select name="bulk_status" id="bulk_status" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <option value="pending">Pending</option>
                            <option value="success">Success</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div id="selected-donations"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus donasi ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Select all checkbox functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.donation-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Show bulk update modal
function showBulkUpdateModal() {
    const selectedCheckboxes = document.querySelectorAll('.donation-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        alert('Pilih minimal satu donasi untuk di-update');
        return;
    }
    
    const selectedDonations = document.getElementById('selected-donations');
    selectedDonations.innerHTML = '';
    
    selectedCheckboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'donation_ids[]';
        input.value = checkbox.value;
        selectedDonations.appendChild(input);
    });
    
    $('#bulkUpdateModal').modal('show');
}

// Export donations
function exportDonations() {
    const params = new URLSearchParams(window.location.search);
    const exportUrl = '{{ route("admin.donations.export") }}?' + params.toString();
    window.location.href = exportUrl;
}

// Confirm delete
function confirmDelete(donationId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = '/admin/donations/' + donationId;
    $('#deleteModal').modal('show');
}

// Auto-hide alerts
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@endpush