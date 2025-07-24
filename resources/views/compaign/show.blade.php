@extends('layouts.app')

@section('title', $campaign->judul . ' - DonasiKu')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Campaign Details -->
        <div class="col-lg-8">
            <div class="card">
                <img src="{{ asset('uploads/campaigns/' . ($campaign->gambar ?: 'default.jpg')) }}" 
                     class="card-img-top" alt="{{ $campaign->judul }}" style="height: 400px; object-fit: cover;">
                <div class="card-body">
                    <h1 class="card-title">{{ $campaign->judul }}</h1>
                    <p class="text-muted"><i class="fas fa-tag"></i> {{ $campaign->kategori }}</p>
                    
                    <!-- Progress -->
                    @php
                        $percentage = $campaign->target > 0 ? ($campaign->terkumpul / $campaign->target) * 100 : 0;
                    @endphp
                    
                    <div class="mb-4">
                        <div class="progress mb-2" style="height: 12px;">
                            <div class="progress-bar" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4 class="text-primary">Rp {{ number_format($campaign->terkumpul, 0, ',', '.') }}</h4>
                                <small class="text-muted">terkumpul dari Rp {{ number_format($campaign->target, 0, ',', '.') }}</small>
                            </div>
                            <div class="col text-end">
                                <h4 class="text-success">{{ number_format($percentage, 1) }}%</h4>
                                <small class="text-muted">tercapai</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h3>Deskripsi Kampanye</h3>
                    <div class="campaign-description">
                        {!! nl2br(e($campaign->deskripsi)) !!}
                    </div>
                    
                    <!-- Comments Section -->
                    <hr>
                    <h3>Dukungan & Doa <small class="text-muted">({{ $donations->count() }})</small></h3>
                    
                    @if($donations->count() > 0)
                        <div class="comments-section">
                            @foreach($donations as $donation)
                                @if($donation->komentar)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="card-title mb-1">
                                                <i class="fas fa-user-circle text-primary"></i> 
                                                {{ $donation->nama }}
                                            </h6>
                                            <small class="text-muted">
                                                {{ $donation->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <p class="card-text">{{ $donation->komentar }}</p>
                                        <small class="text-success">
                                            <i class="fas fa-heart"></i> 
                                            Donasi Rp {{ number_format($donation->nominal, 0, ',', '.') }}
                                        </small>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada dukungan. Jadilah yang pertama!</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Donation Form -->
        <div class="col-lg-4">
            <div class="card sticky-top">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">
                        <i class="fas fa-heart text-danger"></i> Berikan Donasi
                    </h4>
                    
                    <form id="donationForm">
                        @csrf
                        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                        
                        <!-- Nama -->
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        
                        <!-- Nominal -->
                        <div class="mb-3">
                            <label class="form-label">Nominal Donasi *</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="nominal" 
                                       min="10000" placeholder="50000" required>
                            </div>
                        </div>
                        
                        <!-- Quick Amount -->
                        <div class="mb-3">
                            <label class="form-label">Nominal Cepat</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 quick-amount" 
                                            data-amount="50000">50K</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 quick-amount" 
                                            data-amount="100000">100K</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 quick-amount" 
                                            data-amount="200000">200K</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 quick-amount" 
                                            data-amount="500000">500K</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Komentar -->
                        <div class="mb-3">
                            <label class="form-label">Pesan & Doa</label>
                            <textarea class="form-control" name="komentar" rows="3" 
                                      placeholder="Tulis pesan dukungan Anda..."></textarea>
                        </div>
                        
                        <!-- Anonim -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="anonim" id="anonim">
                                <label class="form-check-label" for="anonim">
                                    Donasi sebagai Anonim
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="btnDonasi">
                            <i class="fas fa-heart"></i> Donasi Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick amount buttons
    document.querySelectorAll('.quick-amount').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelector('input[name="nominal"]').value = this.getAttribute('data-amount');
        });
    });
    
    // Donation form submission
    document.getElementById('donationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('btnDonasi');
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        
        // Send to backend
        fetch('{{ route("donations.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Open Midtrans Snap
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        alert('Terima kasih! Donasi Anda berhasil.');
                        window.location.reload();
                    },
                    onPending: function(result) {
                        alert('Transaksi pending. Silahkan selesaikan pembayaran.');
                        window.location.reload();
                    },
                    onError: function(result) {
                        alert('Terjadi kesalahan. Silahkan coba lagi.');
                        console.error(result);
                    },
                    onClose: function() {
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-heart"></i> Donasi Sekarang';
                    }
                });
            } else {
                alert('Error: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-heart"></i> Donasi Sekarang';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem. Silahkan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-heart"></i> Donasi Sekarang';
        });
    });
});
</script>
@endsection