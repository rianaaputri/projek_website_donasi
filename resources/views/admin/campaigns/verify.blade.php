@extends('layouts.admin')

@section('title', 'Verifikasi Campaign')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-check-double me-2"></i>Verifikasi Campaign</h2>
    <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

{{-- Menampilkan pesan sukses atau error --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Kondisi jika tidak ada campaign yang menunggu verifikasi --}}
@if($pendingCampaigns->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-check-double fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Tidak ada campaign menunggu verifikasi</h5>
            <p class="text-muted">Semua campaign sudah diverifikasi.</p>
        </div>
    </div>
@else
    {{-- Tampilan tabel jika ada campaign --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Gambar</th>
                            <th>Judul & Pengguna</th>
                            <th>Kategori</th>
                            <th>Target</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingCampaigns as $campaign)
                            <tr>
                                <td>
                                    @if($campaign->image)
                                        <img src="{{ asset('storage/' . $campaign->image) }}"
                                             alt="{{ $campaign->title }}"
                                             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ Str::limit($campaign->title, 30) }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Oleh: <strong>{{ $campaign->user->name ?? 'User Terhapus' }}</strong>
                                        <br>
                                        Email: {{ $campaign->user->email ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ ucfirst($campaign->category) }}</span>
                                </td>
                                <td><strong>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</strong></td>
                                <td>{{ $campaign->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- PERBAIKAN: Menggunakan rute 'campaign.detail' --}}
                                        <a href="{{ route('campaign.detail', $campaign->id) }}"
                                           class="btn btn-sm btn-outline-info"
                                           target="_blank"
                                           title="Lihat Campaign">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.campaigns.verify.approve', $campaign) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin verifikasi campaign ini?')">
                                                <i class="fas fa-check"></i> Verifikasi
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="openRejectModal({{ $campaign->id }}, '{{ addslashes($campaign->title) }}')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Tolak Campaign
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Anda akan menolak campaign: <strong id="campaignTitle"></strong></p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" rows="4" 
                                 placeholder="Masukkan alasan mengapa campaign ini ditolak..." required></textarea>
                        {{-- Menampilkan pesan error dari validasi Laravel --}}
                        @error('rejection_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Alasan ini akan dikirimkan kepada pembuat campaign.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i>Tolak Campaign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openRejectModal(campaignId, campaignTitle) {
        // PERBAIKAN: Gunakan rute named untuk menghindari hardcode URL
        const rejectRoute = '{{ route("admin.campaigns.verify.reject", ":id") }}';
        document.getElementById('rejectForm').action = rejectRoute.replace(':id', campaignId);
        
        // Set judul campaign di modal
        document.getElementById('campaignTitle').textContent = campaignTitle;
        
        // Bersihkan input sebelumnya
        document.getElementById('rejection_reason').value = '';
        
        // Tampilkan modal
        var rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        rejectModal.show();
    }
</script>

{{-- Pastikan ini ada jika Anda memicu modal dari request back-end --}}
@if($errors->has('rejection_reason'))
<script>
    // Membuka modal kembali jika ada error validasi
    document.addEventListener('DOMContentLoaded', function() {
        var rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        rejectModal.show();
    });
</script>
@endif
@endsection