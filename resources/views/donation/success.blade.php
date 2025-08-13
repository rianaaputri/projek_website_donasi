@extends('layouts.app')

@section('title', 'Donasi Berhasil - Terima Kasih!')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <!-- Success Message -->
                    <h1 class="h2 text-success mb-3">Terima Kasih!</h1>
                    <p class="lead mb-4">Donasi Anda berhasil dibuat dan sedang diproses.</p>
                    
                    <!-- Donation Details -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Detail Donasi</h5>
                            
                            <div class="row text-start">
                                <div class="col-sm-4 fw-bold">Campaign:</div>
                                <div class="col-sm-8">{{ $donation->campaign->title }}</div>
                            </div>
                            <hr>
                            
                            <div class="row text-start">
                                <div class="col-sm-4 fw-bold">Nama Donatur:</div>
                                <div class="col-sm-8">{{ $donation->donor_name }}</div>
                            </div>
                            <hr>
                            
                            <div class="row text-start">
                                <div class="col-sm-4 fw-bold">Email:</div>
                                <div class="col-sm-8">{{ $donation->donor_email }}</div>
                            </div>
                            <hr>
                            
                            <div class="row text-start">
                                <div class="col-sm-4 fw-bold">Nominal:</div>
                                <div class="col-sm-8 text-success fw-bold fs-5">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </div>
                            </div>
                            <hr>
                            
                            @if($donation->comment)
                            <div class="row text-start">
                                <div class="col-sm-4 fw-bold">Pesan:</div>
                                <div class="col-sm-8">"{{ $donation->comment }}"</div>
                            </div>
                            <hr>
                            @endif
                            
                          <div class="row text-start" id="status-text">
    <div class="col-sm-4 fw-bold">Status:</div>
    <div class="col-sm-8">
        @if($donation->payment_status === 'success')
            <span class="badge bg-success">Pembayaran Berhasil</span>
        @elseif($donation->payment_status === 'pending')
            <span class="badge bg-warning">Menunggu Pembayaran</span>
        @else
            <span class="badge bg-danger">Pembayaran Gagal</span>
        @endif
    </div>
</div>

                            <hr>
                            
                            <div class="row text-start">
                                <div class="col-sm-4 fw-bold">Tanggal:</div>
                                <div class="col-sm-8">{{ $donation->created_at->format('d F Y, H:i') }} WIB</div>
                            </div>
                        </div>
                    </div>
                    
                   @if($donation->payment_status === 'pending')
    <div class="alert alert-warning" id="status-alert">
        <i class="fas fa-clock me-2"></i>
        <strong>Menunggu Pembayaran</strong><br>
        Silakan selesaikan pembayaran melalui metode yang telah dipilih.
    </div>
@endif

                    
                    <!-- Next Steps -->
                    <div class="mb-4">
                        <h5>Apa Selanjutnya?</h5>
                        <p class="text-muted">
                            Kami akan mengirimkan konfirmasi donasi ke email Anda. 
                            Terima kasih telah berpartisipasi dalam kegiatan sosial ini!
                        </p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('campaign.show', $donation->campaign->id) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>Lihat Campaign
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>Kembali ke Beranda
                        </a>
                    </div>
                    
                    <!-- Share Section -->
                    <div class="mt-5">
                        <h6>Bagikan kebaikan ini:</h6>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('campaign.show', $donation->campaign->id)) }}" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('campaign.show', $donation->campaign->id)) }}&text={{ urlencode('Saya telah berdonasi untuk: ' . $donation->campaign->title) }}" 
                               target="_blank" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={{ urlencode('Saya telah berdonasi untuk: ' . $donation->campaign->title . ' - ' . route('campaign.show', $donation->campaign->id)) }}" 
                               target="_blank" 
                               class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Campaign Progress Update -->
            <div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title">Progress Campaign</h5>
        <div class="progress mb-3" style="height: 12px;">
            <div class="progress-bar bg-success" 
                 role="progressbar"
                 style="width: {{ $donation->campaign->progress_percentage }}%"
                 aria-valuenow="{{ $donation->campaign->progress_percentage }}"
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $donation->campaign->progress_percentage }}%
            </div>
        </div>
        <div class="row text-center">
            <div class="col-4">
                <h6 class="text-success" id="collected-amount">{{ $donation->campaign->formatted_collected }}</h6>
                <small class="text-muted">Terkumpul</small>
            </div>
            <div class="col-4">
                <h6 class="text-primary">{{ $donation->campaign->formatted_target }}</h6>
                <small class="text-muted">Target</small>
            </div>
            <div class="col-4">
                <h6 class="text-warning" id="donor-count">{{ $donation->campaign->donations()->where('payment_status', 'success')->count() }}</h6>
                <small class="text-muted">Donatur</small>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusText = document.getElementById('status-text');
    const statusAlert = document.getElementById('status-alert');
    const collectedText = document.getElementById('collected-amount');
    const donorCountText = document.getElementById('donor-count');
    const progressBar = document.querySelector('.progress-bar');

    function updateStatus() {
        fetch("{{ route('donation.status', $donation->id) }}")
            .then(res => res.json())
            .then(data => {
                if (!data || !data.status) return;

                // Update status badge
                let badgeHTML = '';
                if (data.status === 'success') {
                    badgeHTML = '<span class="badge bg-success">Berhasil</span>';
                    if (statusAlert) statusAlert.style.display = 'none';
                } else if (data.status === 'pending') {
                    badgeHTML = '<span class="badge bg-warning">Menunggu Pembayaran</span>';
                } else if (data.status === 'failed') {
                    badgeHTML = '<span class="badge bg-danger">Gagal</span>';
                    if (statusAlert) statusAlert.style.display = 'none';
                }

                statusText.innerHTML = `
                    <div class="col-sm-4 fw-bold">Status:</div>
                    <div class="col-sm-8">${badgeHTML}</div>
                `;

                // Update progress bar and text if available
                if (data.progress && progressBar) {
                    progressBar.style.width = data.progress + '%';
                }

                if (data.collected && collectedText) {
                    collectedText.innerText = data.collected;
                }

                if (data.donors && donorCountText) {
                    donorCountText.innerText = data.donors;
                }
            })
            .catch(error => console.error('Status update error:', error));
    }

    // Poll only if status not final (handled on backend if needed)
    setInterval(updateStatus, 5000); // update setiap 5 detik
});
</script>
@endpush
