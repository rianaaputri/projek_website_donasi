@extends('layouts.app')

@section('title', 'Donasi Pending')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Donasi Pending</h2>

    @if($pendingDonations->isEmpty())
        <div class="alert alert-info">Tidak ada donasi yang pending.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Campaign</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingDonations as $donation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $donation->campaign->title }}</td>
                    <td>Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>
                        <a href="{{ route('donation.edit', $donation->id) }}" class="btn btn-primary btn-sm">
                            Lanjutkan Pembayaran
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
