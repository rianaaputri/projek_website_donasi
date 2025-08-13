@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-history me-2"></i>Riwayat Donasi Saya</h2>

    @if($donations->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Campaign</th>
                        <th>Nominal</th>
                        <th>Komentar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                        <tr>
                            <td>{{ $donation->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('campaign.show', $donation->campaign->id) }}" class="text-decoration-none">
                                    {{ $donation->campaign->title }}
                                </a>
                            </td>
                            <td>Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                            <td>
                                @if($donation->comment)
                                    <span class="text-muted">{{ $donation->comment }}</span>
                                @else
                                    <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                            <td>
                                @if($donation->payment_status === 'success')
                                    <span class="badge bg-success">Berhasil</span>
                                @elseif($donation->payment_status === 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @else
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $donations->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Belum ada donasi yang kamu lakukan.
        </div>
    @endif
</div>
@endsection
