@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Riwayat Donasi Saya</h2>

    @if($donations->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Campaign</th>
                    <th>Nominal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donations as $donation)
                    <tr>
                        <td>{{ $donation->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $donation->campaign->title }}</td>
                        <td>Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
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

        {{ $donations->links() }}
    @else
        <div class="alert alert-info">
            Belum ada donasi yang kamu lakukan.
        </div>
    @endif
</div>
@endsection
