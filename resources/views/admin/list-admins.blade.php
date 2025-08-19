@extends('layouts.admin')

@section('title', 'Kelola Admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Kelola Admin</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kelola Admin</li>
    </ol>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('admin.add-admin') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Admin Baru
            </a>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge bg-info fs-6">
                Total Admin: {{ $admins->total() ?? $admins->count() }}
            </span>
        </div>
    </div>

    <!-- Admin List Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users-cog me-1"></i>
            Daftar Administrator
        </div>
        <div class="card-body">
            @if($admins->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Nama</th>
                                <th style="width: 25%">Email</th>
                                <th style="width: 15%">Status</th>
                                <th style="width: 20%">Bergabung</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $index => $admin)
                            <tr>
                                <td>{{ ($admins->currentPage() - 1) * $admins->perPage() + $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px;">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $admin->name }}</strong>
                                            @if($admin->id == auth()->id())
                                                <small class="badge bg-success ms-1">You</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $admin->email }}</span>
                                    @if($admin->email_verified_at)
                                        <i class="fas fa-check-circle text-success ms-1" title="Email Terverifikasi"></i>
                                    @else
                                        <i class="fas fa-exclamation-circle text-warning ms-1" title="Email Belum Terverifikasi"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($admin->created_at->diffInDays() <= 7)
                                        <span class="badge bg-info">Admin Baru</span>
                                    @else
                                        <span class="badge bg-success">Admin Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $admin->created_at->format('d M Y') }}<br>
                                        <span class="text-xs">{{ $admin->created_at->diffForHumans() }}</span>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($admin->id != auth()->id())
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete('{{ $admin->id }}', '{{ $admin->name }}')"
                                                    title="Hapus Admin">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <span class="btn btn-sm btn-outline-secondary" disabled title="Tidak bisa menghapus diri sendiri">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($admins, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $admins->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Admin</h5>
                    <p class="text-muted">Belum ada administrator lain yang terdaftar.</p>
                    <a href="{{ route('admin.add-admin') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Admin Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Admin
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $admins->total() ?? $admins->count() }}
                            </div>
                        </div>
                        <div class="fas fa-users-cog fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Admin Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $admins->where('created_at', '<', now()->subDays(7))->count() }}
                            </div>
                        </div>
                        <div class="fas fa-user-check fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Admin Baru
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $admins->where('created_at', '>=', now()->subDays(7))->count() }}
                            </div>
                        </div>
                        <div class="fas fa-user-plus fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Email Terverifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $admins->whereNotNull('email_verified_at')->count() }}
                            </div>
                        </div>
                        <div class="fas fa-envelope-check fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus admin:</p>
                <div class="alert alert-warning">
                    <strong id="adminName"></strong><br>
                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan!</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Hapus Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(adminId, adminName) {
        document.getElementById('adminName').textContent = adminName;
        document.getElementById('deleteForm').action = `{{ url('admin/delete-admin') }}/${adminId}`;
        
        // Show modal
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush

@push('styles')
<style>
    .table th {
        background-color: #343a40;
        color: white;
        border-color: #454d55;
        font-weight: 600;
    }
    
    .avatar-initial {
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .text-gray-300 {
        color: rgba(255, 255, 255, 0.3) !important;
    }
    
    .font-weight-bold {
        font-weight: 700;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
    }
</style>
@endpush