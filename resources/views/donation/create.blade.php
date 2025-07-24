@extends('layouts.app')

@section('title', 'Donasi untuk ' . $campaign->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('campaign.show', $campaign->id) }}">{{ Str::limit($campaign->title, 30) }}</a></li>
                    <li class="breadcrumb-item active">Donasi</li>
                </ol>
            </nav>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-heart me-2"></i>Form Donasi
                    </h4>
                </div>
                
                <div class="card-body">
                    <!-- Campaign Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($campaign->image)
                                <img src="{{ asset('storage/' . $campaign->image) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $campaign->title }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 150px;">
                                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5>{{ $campaign->title }}</h5>
                            <p class="text-muted">{{ Str::limit($campaign->description, 150) }}</p>
                            
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ $campaign->progress_percentage }}%"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    {{ $campaign->formatted_collected }} dari {{ $campaign->formatted_target }}
                                </small>
                                <small class="text-muted">
                                    {{ number_format($campaign->progress_percentage, 1) }}%
                                </small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Donation Form -->
                    <form action="{{ route('donation.store') }}" method="POST" id="donationForm">
                        @csrf
                        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="donor_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('donor_name') is-invalid @enderror" 
                                       id="donor_name" 
                                       name="donor_name" 
                                       value="{{ old('donor_name') }}" 
                                       required>
                                @error('donor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="donor_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('donor_email') is-invalid @enderror" 
                                       id="donor_email" 
                                       name="donor_email" 
                                       value="{{ old('donor_email') }}" 
                                       required>
                                @error('donor_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Nominal Donasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" 
                                       name="amount" 
                                       value="{{ old('amount') }}" 
                                       min="10000" 
                                       placeholder="Minimal Rp 10.000" 
                                       required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Minimal donasi Rp 10.000</div>
                        </div>
                        
                        <!-- Quick Amount Buttons -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Nominal Cepat:</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="25000">Rp 25.000</button>
                                <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="50000">Rp 50.000</button>
                                <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="100000">Rp 100.000</button>
                                <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="250000">Rp 250.000</button>
                                <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="500000">Rp 500.000</button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label">Pesan/Doa (Opsional)</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" 
                                      name="comment" 
                                      rows="4" 
                                      placeholder="Tulis pesan atau doa untuk campaign ini...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Saya setuju dengan <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat dan ketentuan</a> yang berlaku
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-heart me-2"></i>Lanjut ke Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Syarat dan Ketentuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Ketentuan Donasi</h6>
                <p>Donasi yang telah diberikan bersifat final dan tidak dapat dibatalkan atau diminta kembali.</p>
                
                <h6>2. Penggunaan Dana</h6>
                <p>Dana yang terkumpul akan digunakan sesuai dengan tujuan campaign yang telah dijelaskan.</p>
                
                <h6>3. Transparansi</h6>
                <p>Kami berkomitmen untuk memberikan laporan penggunaan dana secara transparan.</p>
                
                <h6>4. Kerahasiaan Data</h6>
                <p>Data pribadi donatur akan dijaga kerahasiaannya sesuai dengan kebijakan privasi kami.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick amount buttons
    const quickAmountButtons = document.querySelectorAll('.quick-amount');
    const amountInput = document.getElementById('amount');
    
    quickAmountButtons.forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.dataset.amount;
            amountInput.value = amount;
            
            // Remove active class from all buttons
            quickAmountButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
        });
    });
    
    // Format number input
    amountInput.addEventListener('input', function() {
        // Remove active class from quick amount buttons when custom amount is entered
        quickAmountButtons.forEach(btn => btn.classList.remove('active'));
    });
    
    // Form validation
    const form = document.getElementById('donationForm');
    form.addEventListener('submit', function(e) {
        const amount = parseInt(amountInput.value);
        if (amount < 10000) {
            e.preventDefault();
            alert('Minimal donasi adalah Rp 10.000');
            amountInput.focus();
            return false;
        }
    });
});
</script>
@endpush