@extends('layouts.app')

@section('title', 'Donasi Campaign - ' . $campaign->title)

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Daftar Donasi untuk Campaign: <strong>{{ $campaign->title }}</strong></h2>

    {{-- Info Campaign --}}
    <div class="card mb-4">
        <div class="row no-gutters">
            <div class="col-md-4">
                @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" class="card-img" alt="Campaign Image">
                @else
                    <img src="https://via.placeholder.com/400x250?text=No+Image" class="card-img" alt="No Image">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5>{{ $campaign->title }}</h5>
                    <p class="text-muted mb-1">Kategori: {{ $campaign->category }}</p>
                    <p class="mb-1">Target Donasi: <strong>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</strong></p>
                    <p class="mb-1">Terkumpul: <strong>Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</strong></p>
                    <p>Status: 
                        @if($campaign->verification_status === 'pending')
                            <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                        @elseif($campaign->verification_status === 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Donasi --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Donasi Terbaru</h5>
        </div>
        <div class="card-body">
            @if($donations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Donatur</th>
                                <th>Jumlah Donasi</th>
                                <th>Waktu</th>
                                <th>Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donations as $index => $donation)
                                <tr>
                                    <td>{{ $donations->firstItem() + $index }}</td>
                                    <td>{{ $donation->user->name ?? 'Anonim' }}</td>
                                    <td>Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                                    <td>{{ $donation->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $donation->message ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $donations->links() }}
                </div>
            @else
                <p class="text-muted">Belum ada donasi untuk campaign ini.</p>
            @endif
        </div>
    </div>

    {{-- Back Button --}}
    <div class="mt-4">
        <a href="{{ route('user.campaigns.history') }}" class="btn btn-secondary">← Kembali ke Riwayat Campaign</a>
    </div>
</div>
@endsection
