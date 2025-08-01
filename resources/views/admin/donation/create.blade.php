@extends('layouts.app')

@section('title', 'Donasi untuk ' . $campaign->title)

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Breadcrumb dengan styling yang lebih baik -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-white rounded-pill px-4 py-3 shadow-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-primary text-decoration-none fw-medium">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('campaign.show', $campaign->id) }}" class="text-primary text-decoration-none fw-medium">
                                {{ Str::limit($campaign->title, 30) }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted">Donasi</li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-primary bg-gradient text-white py-4">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-heart me-2"></i>Form Donasi
                        </h4>
                        <p class="mb-0 opacity-75 mt-1">Berkontribusi untuk kebaikan bersama</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Campaign Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                @if($campaign->image)
                                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                                         class="img-fluid rounded-3 shadow-sm w-100" 
                                         alt="{{ $campaign->title }}"
                                         style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" 
                                         style="height: 180px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                        <i class="fas fa-image text-primary opacity-50" style="font-size: 2.5rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="bg-light rounded-3 p-3 h-100 border border-light-subtle">
                                    <h5 class="text-dark fw-bold mb-2">{{ $campaign->title }}</h5>
                                    <p class="text-muted mb-3" style="font-size: 0.95rem; line-height: 1.6;">
                                        {{ Str::limit($campaign->description, 150) }}
                                    </p>
                                    
                                    <div class="progress mb-3 rounded-pill" style="height: 10px;">
                                        <div class="progress-bar bg-primary bg-gradient progress-bar-striped" 
                                             style="width: {{ $campaign->progress_percentage }}%"></div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted fw-medium">
                                            <i class="fas fa-coins text-success me-1"></i>
                                            {{ $campaign->formatted_collected }} dari {{ $campaign->formatted_target }}
                                        </small>
                                        <span class="badge bg-primary bg-gradient px-3 py-2 rounded-pill">
                                            {{ number_format($campaign->progress_percentage, 1) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Donation Form -->
                        <form action="{{ route('donation.store') }}" method="POST" id="donationForm">
                            @csrf
                            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                            
                            <!-- Personal Information Section -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-gradient rounded-circle p-2 me-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <h5 class="mb-0 text-primary fw-bold">Informasi Donatur</h5>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="donor_name" class="form-label fw-medium text-dark">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg rounded-3 @error('donor_name') is-invalid @enderror" 
                                               id="donor_name" 
                                               name="donor_name" 
                                               value="{{ old('donor_name') }}" 
                                               placeholder="Masukkan nama lengkap"
                                               required>
                                        @error('donor_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="donor_email" class="form-label fw-medium text-dark">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" 
                                               class="form-control form-control-lg rounded-3 @error('donor_email') is-invalid @enderror" 
                                               id="donor_email" 
                                               name="donor_email" 
                                               value="{{ old('donor_email') }}" 
                                               placeholder="nama@email.com"
                                               required>
                                        @error('donor_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Donation Amount Section -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-gradient rounded-circle p-2 me-3">
                                        <i class="fas fa-coins text-white"></i>
                                    </div>
                                    <h5 class="mb-0 text-success fw-bold">Nominal Donasi</h5>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="amount" class="form-label fw-medium text-dark">
                                        Jumlah Donasi <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary bg-gradient text-white fw-bold border-0">Rp</span>
                                        <input type="number" 
                                               class="form-control rounded-end-3 @error('amount') is-invalid @enderror" 
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
                                    <div class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>Minimal donasi Rp 10.000
                                    </div>
                                </div>
                                
                                <!-- Quick Amount Buttons -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-dark mb-2">Pilih Nominal Cepat:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-primary rounded-pill quick-amount" data-amount="25000">
                                            Rp 25.000
                                        </button>
                                        <button type="button" class="btn btn-outline-primary rounded-pill quick-amount" data-amount="50000">
                                            Rp 50.000
                                        </button>
                                        <button type="button" class="btn btn-outline-primary rounded-pill quick-amount" data-amount="100000">
                                            Rp 100.000
                                        </button>
                                        <button type="button" class="btn btn-outline-primary rounded-pill quick-amount" data-amount="250000">
                                            Rp 250.000
                                        </button>
                                        <button type="button" class="btn btn-outline-primary rounded-pill quick-amount" data-amount="500000">
                                            Rp 500.000
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Message Section -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-gradient rounded-circle p-2 me-3">
                                        <i class="fas fa-comment text-white"></i>
                                    </div>
                                    <h5 class="mb-0 text-info fw-bold">Pesan & Doa</h5>
                                </div>
                                
                                <label for="comment" class="form-label fw-medium text-dark">
                                    Pesan/Doa (Opsional)
                                </label>
                                <textarea class="form-control form-control-lg rounded-3 @error('comment') is-invalid @enderror" 
                                          id="comment" 
                                          name="comment" 
                                          rows="4" 
                                          placeholder="Tulis pesan atau doa untuk campaign ini...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="fas fa-heart me-1"></i>Pesan Anda akan ditampilkan di halaman campaign
                                </div>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="bg-light rounded-3 p-3 border border-light-subtle">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label fw-medium text-dark" for="terms">
                                            <i class="fas fa-shield-alt text-primary me-2"></i>
                                            Saya setuju dengan 
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-primary text-decoration-none fw-bold">
                                                syarat dan ketentuan
                                            </a> yang berlaku
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4 me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 fw-bold shadow-sm">
                                    <i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary bg-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-contract me-2"></i>Syarat dan Ketentuan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-gradient rounded-circle p-1 me-2" style="width: 20px; height: 20px;">
                            <span class="text-white fw-bold small">1</span>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">Ketentuan Donasi</h6>
                    </div>
                    <p class="text-muted ms-4 mb-0">Donasi yang telah diberikan bersifat final dan tidak dapat dibatalkan atau diminta kembali.</p>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success bg-gradient rounded-circle p-1 me-2" style="width: 20px; height: 20px;">
                            <span class="text-white fw-bold small">2</span>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">Penggunaan Dana</h6>
                    </div>
                    <p class="text-muted ms-4 mb-0">Dana yang terkumpul akan digunakan sesuai dengan tujuan campaign yang telah dijelaskan.</p>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info bg-gradient rounded-circle p-1 me-2" style="width: 20px; height: 20px;">
                            <span class="text-white fw-bold small">3</span>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">Transparansi</h6>
                    </div>
                    <p class="text-muted ms-4 mb-0">Kami berkomitmen untuk memberikan laporan penggunaan dana secara transparan.</p>
                </div>
                
                <div class="mb-0">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning bg-gradient rounded-circle p-1 me-2" style="width: 20px; height: 20px;">
                            <span class="text-white fw-bold small">4</span>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">Kerahasiaan Data</h6>
                    </div>
                    <p class="text-muted ms-4 mb-0">Data pribadi donatur akan dijaga kerahasiaannya sesuai dengan kebijakan privasi kami.</p>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>Saya Mengerti
                </button>
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
            quickAmountButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            
            // Add active class to clicked button
            this.classList.remove('btn-outline-primary');
            this.classList.add('active', 'btn-primary');
        });
    });
    
    // Format number input
    amountInput.addEventListener('input', function() {
        // Remove active class from quick amount buttons when custom amount is entered
        quickAmountButtons.forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-primary');
        });
    });
    
    // Form validation with better UX
    const form = document.getElementById('donationForm');
    form.addEventListener('submit', function(e) {
        const amount = parseInt(amountInput.value);
        if (amount < 10000) {
            e.preventDefault();
            
            // Show error with Bootstrap alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Perhatian!</strong> Minimal donasi adalah Rp 10.000
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert alert before form
            form.insertBefore(alertDiv, form.firstChild);
            amountInput.focus();
            amountInput.classList.add('is-invalid');
            
            // Auto remove alert after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
                amountInput.classList.remove('is-invalid');
            }, 5000);
            
            return false;
        }
    });
    
    // Add number formatting to amount input
    amountInput.addEventListener('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value) {
            // Add thousand separators for display (optional)
            // this.value = parseInt(value).toLocaleString('id-ID');
        }
    });
});
</script>
@endpush