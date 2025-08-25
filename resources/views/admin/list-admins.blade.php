@extends('layouts.admin')

@section('title', 'Kelola Pengguna & Administrator')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Kelola Pengguna & Administrator</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kelola Pengguna & Administrator</li>
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

    <!-- Filter and Search Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama, email, atau telepon...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Role</label>
                    <select class="form-select" name="role">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="campaign_creator" {{ request('role') == 'campaign_creator' ? 'selected' : '' }}>Campaign Creator</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Verifikasi</label>
                    <select class="form-select" name="verification">
                        <option value="">Semua</option>
                        <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="unverified" {{ request('verification') == 'unverified' ? 'selected' : '' }}>Belum Terverifikasi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Pengguna
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $users->total() ?? $users->count() }}
                            </div>
                        </div>
                        <div class="fas fa-users fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Administrator
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $users->where('role', 'admin')->count() }}
                            </div>
                        </div>
                        <div class="fas fa-user-shield fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                User Biasa
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $users->where('role', 'user')->count() }}
                            </div>
                        </div>
                        <div class="fas fa-user fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Email Terverifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $users->whereNotNull('email_verified_at')->count() }}
                            </div>
                        </div>
                        <div class="fas fa-envelope-check fa-2x text-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users List Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Daftar Pengguna & Administrator
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Nama</th>
                                <th style="width: 20%">Email</th>
                                <th style="width: 10%">Role</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%">Bergabung</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }} text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->id == auth()->id())
                                                <small class="badge bg-success ms-1">You</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                        <i class="fas fa-check-circle text-success ms-1" title="Email Terverifikasi"></i>
                                    @else
                                        <i class="fas fa-exclamation-circle text-warning ms-1" title="Email Belum Terverifikasi"></i>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}<br>
                                        <span class="text-xs">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</span>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Role Change Button -->
                                        @if(auth()->id() != $user->id)
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info" 
                                                    onclick="changeRole('{{ $user->id }}', '{{ $user->role }}', '{{ $user->name }}')"
                                                    title="Ubah Hak Akses">
                                                <i class="fas fa-user-cog"></i>
                                            </button>
                                        @endif

                                        <!-- Status Change Button -->
                                        @if(auth()->id() != $user->id)
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" 
                                                    onclick="changeStatus('{{ $user->id }}', {{ $user->is_active ? 'false' : 'true' }}, '{{ $user->name }}')"
                                                    title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} User">
                                                <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }}"></i>
                                            </button>
                                        @endif

                                        <!-- Email Verification Button -->
                                        @if(!$user->email_verified_at)
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    onclick="verifyEmail('{{ $user->id }}', '{{ $user->name }}')"
                                                    title="Verifikasi Email">
                                                <i class="fas fa-envelope-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($users, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Data</h5>
                    <p class="text-muted">Belum ada pengguna yang sesuai dengan filter.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Role Change Confirmation Modal -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-cog me-2"></i>Ubah Hak Akses
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengubah hak akses untuk:</p>
                <div class="alert alert-info">
                    <strong id="roleUserName"></strong><br>
                    <small class="text-muted">
                        Role saat ini: <span id="currentRole" class="badge bg-primary"></span><br>
                        Role baru: <span id="newRole" class="badge bg-danger"></span>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-info" id="confirmRoleChange">
                    <i class="fas fa-user-cog me-1"></i>Ubah Hak Akses
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Confirmation Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-slash me-2"></i>Ubah Status User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="statusMessage">Apakah Anda yakin ingin mengubah status untuk:</p>
                <div class="alert alert-warning">
                    <strong id="statusUserName"></strong><br>
                    <small class="text-muted" id="statusAction">Tindakan ini akan mengubah status user.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" id="confirmStatusChange">
                    <i class="fas fa-user-slash me-1"></i>Ubah Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Email Verification Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-envelope-check me-2"></i>Verifikasi Email
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin memverifikasi email untuk:</p>
                <div class="alert alert-primary">
                    <strong id="verifyUserName"></strong><br>
                    <small class="text-muted">Email akan ditandai sebagai terverifikasi.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmVerify">
                    <i class="fas fa-envelope-check me-1"></i>Verifikasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- User Detail Modal -->
<div class="modal fade" id="userDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>Detail Pengguna
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentUserId = null;
    let currentAction = null;

    // Change Role Function
    function changeRole(userId, currentRole, userName) {
        currentUserId = userId;
        currentAction = 'role';
        
        const newRole = currentRole === 'admin' ? 'user' : 'admin' : 'campaign_creator';
        
        document.getElementById('roleUserName').textContent = userName;
        document.getElementById('currentRole').textContent = currentRole.charAt(0).toUpperCase() + currentRole.slice(1);
        document.getElementById('currentRole').className = `badge bg-${currentRole === 'admin' ? 'danger' : 'primary'}`;
        document.getElementById('currentRole').className = `badge bg-${currentRole === 'campaign_creator' ? 'danger' : 'primary'}`;
        document.getElementById('newRole').textContent = newRole.charAt(0).toUpperCase() + newRole.slice(1);
        document.getElementById('newRole').className = `badge bg-${newRole === 'admin' ? 'danger' : 'primary'}`;
        document.getElementById('newRole').className = `badge bg-${newRole === 'campaign_creator' ? 'danger' : 'primary'}`;
        
        new bootstrap.Modal(document.getElementById('roleModal')).show();
    }

    // Change Status Function
    function changeStatus(userId, newStatus, userName) {
        currentUserId = userId;
        currentAction = 'status';
        
        const statusText = newStatus === 'true' ? 'mengaktifkan' : 'menonaktifkan';
        
        document.getElementById('statusUserName').textContent = userName;
        document.getElementById('statusAction').textContent = `User akan ${statusText === 'mengaktifkan' ? 'diaktifkan' : 'dinonaktifkan'}.`;
        
        new bootstrap.Modal(document.getElementById('statusModal')).show();
        
        // Store the new status
        document.getElementById('confirmStatusChange').setAttribute('data-status', newStatus);
    }

    // Verify Email Function
    function verifyEmail(userId, userName) {
        currentUserId = userId;
        currentAction = 'verify';
        
        document.getElementById('verifyUserName').textContent = userName;
        new bootstrap.Modal(document.getElementById('verifyModal')).show();
    }

    // View User Function
    function viewUser(userId) {
        currentUserId = userId;
        
        // Show modal first
        const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
        modal.show();
        
        // Reset content
        document.getElementById('userDetailContent').innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        // Fetch user details
        fetch(`{{ route('admin.show-user') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                const createdDate = new Date(user.created_at).toLocaleDateString('id-ID');
                const verifiedStatus = user.email_verified_at ? 
                    '<span class="badge bg-success">Terverifikasi</span>' : 
                    '<span class="badge bg-warning">Belum Terverifikasi</span>';
                
                document.getElementById('userDetailContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Dasar</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Nama:</strong></td><td>${user.name}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${user.email} ${verifiedStatus}</td></tr>
                                <tr><td><strong>Telepon:</strong></td><td>${user.phone || '-'}</td></tr>
                                <tr><td><strong>Alamat:</strong></td><td>${user.address || '-'}</td></tr>
                                <tr><td><strong>Role:</strong></td><td><span class="badge bg-${user.role === 'admin' ? 'danger' : 'primary'}">${user.role === 'admin' ? 'danger' : 'primary'} ${user.role.charAt(0).toUpperCase() + user.role.slice(1)} </span></td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge bg-${user.is_active ? 'success' : 'secondary'}">${user.is_active ? 'Aktif' : 'Tidak Aktif'}</span></td></tr>
                                <tr><td><strong>Bergabung:</strong></td><td>${createdDate}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Statistik Aktivitas</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Total Kampanye:</strong></td><td>${user.campaigns_count}</td></tr>
                                <tr><td><strong>Total Donasi:</strong></td><td>${user.donations_count}</td></tr>
                                <tr><td><strong>Total Donasi (Rp):</strong></td><td>Rp ${new Intl.NumberFormat('id-ID').format(user.donations_total)}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('userDetailContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('userDetailContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan saat memuat data.
                </div>
            `;
        });
    }

    // Confirm Role Change
    document.getElementById('confirmRoleChange').addEventListener('click', function() {
        if (!currentUserId) return;
        
        const currentRoleText = document.getElementById('currentRole').textContent.toLowerCase();
        const newRoleText = document.getElementById('newRole').textContent.toLowerCase();
        
        fetch(`{{ route('admin.update-role') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: currentUserId,
                current_role: currentRoleText,
                new_role: newRoleText
            })
        })
        .then(response => response.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('roleModal')).hide();
            
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            bootstrap.Modal.getInstance(document.getElementById('roleModal')).hide();
            showAlert('danger', 'Terjadi kesalahan saat mengubah role.');
        });
    });

    // Confirm Status Change
    document.getElementById('confirmStatusChange').addEventListener('click', function() {
        if (!currentUserId) return;
        
        const newStatus = this.getAttribute('data-status') === 'true';
        
        fetch(`{{ route('admin.update-status') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: currentUserId,
                is_active: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
            
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
            showAlert('danger', 'Terjadi kesalahan saat mengubah status.');
        });
    });

    // Confirm Email Verification
    document.getElementById('confirmVerify').addEventListener('click', function() {
        if (!currentUserId) return;
        
        fetch(`{{ route('admin.verify-email') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: currentUserId
            })
        })
        .then(response => response.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('verifyModal')).hide();
            
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            bootstrap.Modal.getInstance(document.getElementById('verifyModal')).hide();
            showAlert('danger', 'Terjadi kesalahan saat memverifikasi email.');
        });
    });

    // Show Alert Function
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        const breadcrumb = container.querySelector('.breadcrumb').parentElement;
        breadcrumb.insertAdjacentElement('afterend', alertDiv);
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
    
    .modal-header.bg-info .btn-close-white,
    .modal-header.bg-warning .btn-close-white,
    .modal-header.bg-primary .btn-close-white,
    .modal-header.bg-secondary .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>
@endpush