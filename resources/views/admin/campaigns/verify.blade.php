@extends('layouts.admin')
@section('title', 'Verifikasi Campaign')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-check-double me-2"></i>Verifikasi Campaign</h2>
    <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($pendingCampaigns->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-check-double fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Tidak ada campaign menunggu verifikasi</h5>
            <p class="text-muted">Semua campaign sudah diverifikasi.</p>
        </div>
    </div>
@else
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
                                        <div class="bg-secondary rounded" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
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
                                        <a href="{{ route('campaign.show', $campaign->id) }}"
                                           class="btn btn-sm btn-outline-info"
                                           target="_blank"
                                           title="Lihat Campaign">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.campaigns.verify.approve', $campaign->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin verifikasi campaign ini?')">
                                                <i class="fas fa-check"></i> Verifikasi
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.campaigns.verify.reject', $campaign->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menolak campaign ini?')">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </form>
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
@endsection