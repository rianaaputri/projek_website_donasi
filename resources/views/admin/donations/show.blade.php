@extends('layouts.admin')

@section('title', 'Detail Donasi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Donasi #{{ $donation->id }}</h1>
        <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

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

    <div class="row">
        <!-- Donation Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Donasi</h6>
                    <div>
                        @if($donation->status == 'success')
                            <span class="badge badge-success badge-lg">Success</span>
                        @elseif($donation->status == 'pending')
                            <span class="badge badge-warning badge-lg">Pending</span>
                        @else
                            <span class="badge badge-danger badge-lg">Failed</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID Donasi:</strong></td>
                                    <td>{{ $donation->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Donatur:</strong></td>
                                    <td>{{ $donation->donor_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Donatur:</strong></td>
                                    <td>{{ $donation->donor_email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Donasi:</strong></td>
                                    <td class="text-success">
                                        <strong>Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($donation->status == 'success')
                                            <span class="badge badge-success">Success</span>
                                        @elseif($donation->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Payment ID:</strong></td>
                                    <td>{{ $donation->payment_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Donasi:</strong></td>
                                    <td>{{ $donation->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Terakhir Update:</strong></td>
                                    <td>{{ $donation->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Selisih Waktu:</strong></td>
                                    <td>{{ $donation->created_at->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($donation->comment)
                        <div class="mt-4">
                            <h6 class="font-weight-bold">Komentar Donatur:</h6>
                            <div class="bg-light p-3 rounded">
                                <em>"{{ $donation->comment }}"</em>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Campaign Info & Actions -->
        <div class="col-lg-4">
            <!-- Campaign Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Campaign Terkait</h6>
                </div>
                <div class="card-body">
                    @if($donation->campaign->image)
                        <img src="{{ asset('storage/campaigns/' . $donation->campaign->image) }}" 
                             class="img-fluid rounded mb-3" alt="Campaign Image">
                    @endif
                    
                    <h6 class="font-weight-bold">{{ $donation->campaign->title }}</h6>
                    <p class="text-muted small">{{ $donation->campaign->category }}</p>
                    
                    <div class="mb-2">
                        <strong>Target:</strong> 
                        Rp {{ number_format($donation->campaign->target_amount, 0, ',', '.') }}
                    </div>
                    <div class="mb-2">
                        <strong>Terkumpul:</strong> 
                        Rp {{ number_format($donation->campaign->collected_amount, 0, ',', '.') }}
                    </div>
                    
                    @php
                        $percentage = $donation->campaign->target_amount > 0 
                            ? ($donation->campaign->collected_amount / $donation->campaign->target_amount) * 100 
                            : 0;
                    @endphp
                    
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ min($percentage, 100) }}%">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                    
                    <a href="{{ route('campaigns.show', $donation->campaign->id) }}" 
                       class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Lihat Campaign
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="font-weight-bold">Ubah Status:</label>
                        <div class="btn-group-vertical w-100" role="group">
                            <form method="POST" 
                                  action="{{ route('admin.donations.update-status', $donation->id) }}"
                                  style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn btn-warning btn-sm mb-1 w-100"
                                        {{ $donation->status == 'pending' ? 'disabled' : '' }}>
                                    <i class="fas fa-clock"></i> Set Pending
                                </button>
                            </form>
                            
                            <form method="POST" 
                                  action="{{ route('admin.donations.update-status', $donation->id) }}"
                                  style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="success">
                                <button type="submit" class="btn btn-success btn-sm mb-1 w-100"
                                        {{ $donation->status == 'success' ? 'disabled' : '' }}>
                                    <i class="fas fa-check"></i> Set Success
                                </button>
                            </form>
                            
                            <form method="POST" 
                                  action="{{ route('admin.donations.update-status', $donation->id) }}"
                                  style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="failed">
                                <button type="submit" class="btn btn-danger btn-sm mb-1 w-100"
                                        {{ $donation->status == 'failed' ? 'disabled' : '' }}>
                                    <i class="fas fa-times"></i> Set Failed
                                </button>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <button type="button" class="btn btn-danger btn-sm w-100" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Hapus Donasi
                        </button>
                    </div>

                    <div>
                        <a href="mailto:{{ $donation->donor_email }}" 
                           class="btn btn-info btn-sm w-100">
                            <i class="fas fa-envelope"></i> Email Donatur
                        </a>
                    </div>
                </div>
            </div>

            <!-- Donation Statistics -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Donatur</h6>
                </div>
                <div class="card-body">
                    @php
                        $donorStats = \App\Models\Donation::where('donor_email', $donation->donor_email)
                            ->selectRaw('
                                COUNT(*) as total_donations,
                                SUM(CASE WHEN status = "success" THEN amount ELSE 0 END) as total_amount,
                                COUNT(CASE WHEN status = "success" THEN 1 END) as success_count
                            ')
                            ->first();
                    @endphp
                    
                    <div class="text-center">
                        <div class="mb-2">
                            <span class="h4 text-primary">{{ $donorStats->total_donations }}</span>
                            <br>
                            <small class="text-muted">Total Donasi</small>
                        </div>
                        
                        <div class="mb-2">
                            <span class="h4 text-success">{{ $donorStats->success_count }}</span>
                            <br>
                            <small class="text-muted">Donasi Sukses</small>
                        </div>
                        
                        <div>
                            <span class="h5 text-info">
                                Rp {{ number_format($donorStats->total_amount, 0, ',', '.') }}
                            </span>
                            <br>
                            <small class="text-muted">Total Kontribusi</small>
                        </div>
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
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus donasi ini?</p>
                <div class="bg-light p-3 rounded">
                    <strong>Detail Donasi:</strong><br>
                    ID: {{ $donation->id }}<br>
                    Donatur: {{ $donation->donor_name }}<br>
                    Jumlah: Rp {{ number_format($donation->amount, 0, ',', '.') }}<br>
                    Status: {{ ucfirst($donation->status) }}
                </div>
                <p class="text-danger mt-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. 
                    @if($donation->status == 'success')
                        Jumlah donasi akan dikurangi dari total terkumpul campaign.
                    @endif
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('admin.donations.destroy', $donation->id) }}" 
                      style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete() {
    $('#deleteModal').modal('show');
}

// Auto-hide alerts
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@endpush