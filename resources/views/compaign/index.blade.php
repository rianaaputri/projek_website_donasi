@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center text-primary mb-4">Daftar Campaign Donasi</h2>
    <div class="row">
        @foreach ($campaigns as $campaign)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <img src="{{ asset('storage/' . $campaign->gambar) }}" class="card-img-top" alt="{{ $campaign->judul }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $campaign->judul }}</h5>
                    <p class="card-text text-secondary">{{ \Illuminate\Support\Str::limit($campaign->deskripsi, 100) }}</p>
                    <p class="text-dark fw-bold">Target: Rp{{ number_format($campaign->target_donasi, 0, ',', '.') }}</p>
                    <a href="{{ route('donasi.create', ['id' => $campaign->id]) }}" class="btn btn-primary w-100">Donasi Sekarang</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
