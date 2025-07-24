@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Pembayaran Donasi</h4>
                </div>
                
                <div class="card-body">
                    <!-- Order Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Untuk Campaign:</h6>
                            <h5>{{ $donation->campaign->title }}</h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Total Donasi:</h6>
                            <h4 class="text-success">{{ $donation->formatted_amount }}</h4>
                        </div>
                    </div>
                    
                    <!-- Payment Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>ID Pembayaran:</strong> {{ $donation->payment_id }}
                    </div>
                    
                    <!-- Payment Method Selection -->
                    <div class="mb-4">
                        <h6 class="mb-3">Pilih Metode Pembayaran:</h6>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <button class="btn btn-outline-primary w-100 payment-method" 
                                        data-method="bank_transfer">
                                    <i class="fas fa-university"></i><br>
                                    <small>Transfer Bank</small>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-primary w-100 payment-method" 
                                        data-method="e_wallet">
                                    <i class="fas fa-mobile-alt"></i><br>
                                    <small>E-Wallet</small>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-primary w-100 payment-method" 
                                        data-method="qris">
                                    <i class="fas fa-qrcode"></i><br>
                                    <small>QRIS</small>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Simulasi Payment (akan diganti dengan Midtrans) -->
                    <div class="d-grid">
                        <button id="payButton" 
                                class="btn btn-success btn-lg" 
                                onclick="simulatePayment({{ $donation->id }})">
                            <i class="fas fa-credit-card"></i> 
                            Bayar Sekarang
                        </button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-lock"></i> 
                            Pembayaran aman dan terenkripsi
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Payment method selection
document.querySelectorAll('.payment-method').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.payment-method').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-primary');
        });
        this.classList.remove('btn-outline-primary');
        this.classList.add('btn-primary');
    });
});

// Simulate payment (akan diganti dengan Midtrans Integration)
function simulatePayment(donationId) {
    const payButton = document.getElementById('payButton');
    payButton.innerHTML = '<span class="loading"></span> Memproses Pembayaran...';
    payButton.disabled = true;
    
    // Simulasi delay pembayaran
    setTimeout(() => {
        window.location.href = `/donasi/success/${donationId}`;
    }, 2000);
}

// Auto-select first payment method
document.addEventListener('DOMContentLoaded', function() {
    const firstMethod = document.querySelector('.payment-method');
    if (firstMethod) {
        firstMethod.click();
    }
});
</script>
@endsection