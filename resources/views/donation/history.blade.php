@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-primary me-2"></i>
                        Riwayat Donasi Saya
                    </h5>
                </div>
                <div class="card-body">
                    @if($donations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="py-3">Tanggal</th>
                                        <th scope="col" class="py-3">Program</th>
                                        <th scope="col" class="py-3">Nominal</th>
                                        <th scope="col" class="py-3">Komentar</th>
                                        <th scope="col" class="py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donations as $donation)
                                        <tr>
                                            <td class="text-muted small">
                                                {{ $donation->created_at->format('d M Y') }}<br>
                                                <span class="text-primary">{{ $donation->created_at->format('H:i') }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('campaign.show', $donation->campaign->id) }}" 
                                                   class="text-decoration-none text-dark fw-medium">
                                                    {{ $donation->campaign->title }}
                                                </a>
                                            </td>
                                            <td class="fw-medium">
                                                Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                            </td>
                                            <td style="max-width: 200px;">
                                                @if($donation->comment)
                                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 100%;">
                                                        "{{ $donation->comment }}"
                                                    </small>
                                                @else
                                                    <small class="text-muted fst-italic">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($donation->payment_status === 'success')
                                                    <span class="badge rounded-pill bg-success px-3">Berhasil</span>
                                                @elseif($donation->payment_status === 'pending')
                                                    <span class="badge rounded-pill bg-warning px-3">Menunggu</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger px-3">Gagal</span>
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
                        <div class="text-center py-5">
                            <i class="fas fa-donate text-muted mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="text-muted">Belum ada donasi yang kamu lakukan</h5>
                            <p class="text-muted mb-4">Mari mulai berdonasi untuk membantu sesama</p>
                            <a href="{{ route('home') }}" class="btn btn-primary btn-sm px-3">
                                Lihat Program Donasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
