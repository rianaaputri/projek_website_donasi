@extends('layouts.app')

@section('title', 'Donasi untuk ' . $campaign['title'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h3 class="text-primary mb-2">💝 Form Donasi</h3>
                    <p class="text-muted">{{ $campaign['title'] }}</p>
                </div>
            </div>

            <!-- Form Donasi -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('campaign.donation.process', $campaign['id']) }}" method="POST" id="donationForm">
                        @csrf
                        
                        <!-- Pilih Nominal -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Nominal Donasi</label>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="50000">Rp 50.000</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="100000">Rp 100.000</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="200000">Rp 200.000</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="500000">Rp 500.000</button>
                                </div>
                            </div>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                   name="amount" id="customAmount" placeholder="Atau masukkan nominal lain (min. 10.000)" 
                                   value="{{ old('amount') }}" min="10000">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Data Donatur -->
                        <div class="mb-3">
                            <label for="donor_name" class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control @error('donor_name') is-invalid @enderror" 
                                   id="donor_name" name="donor_name" value="{{ old('donor_name') }}" required>
                            @error('donor_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="donor_email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control @error('donor_email') is-invalid @enderror" 
                                   id="donor_email" name="donor_email" value="{{ old('donor_email') }}" required>
                            @error('donor_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Komentar/Doa -->
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Pesan Dukungan (Opsional)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" 
                                      placeholder="Tulis doa atau pesan dukungan Anda...">{{ old('comment') }}</textarea>
                            <div class="form-text">Pesan Anda akan ditampilkan di halaman campaign</div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <div class="alert alert-info">
                                <small>
                                    <i class="bi bi-info-circle"></i>
                                    Setelah klik "Lanjut Pembayaran", Anda akan diarahkan ke halaman pembayaran Midtrans yang aman.
                                </small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            🔒 Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kembali -->
            <div class="text-center mt-3">
                <a href="{{ route('campaign.detail', $campaign['id']) }}" class="text-muted">
                    ← Kembali ke detail campaign
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountButtons = document.querySelectorAll('.amount-btn');
    const customAmountInput = document.getElementById('customAmount');

    amountButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Reset all buttons
            amountButtons.forEach(btn => btn.classList.remove('btn-primary'));
            amountButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
            
            // Activate clicked button
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
            
            // Set amount
            const amount = this.getAttribute('data-amount');
            customAmountInput.value = amount;
        });
    });

    // Reset buttons when custom amount is typed
    customAmountInput.addEventListener('input', function() {
        amountButtons.forEach(btn => btn.classList.remove('btn-primary'));
        amountButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
    });
});
</script>
@endsection