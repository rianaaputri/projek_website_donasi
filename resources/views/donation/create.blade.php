@extends('layouts.app')

@section('title', 'Donasi untuk ' . $campaign->title)

@section('content')
<style>
    :root {
        --blue-50: #f0f9ff;
        --blue-100: #e0f2fe;
        --blue-200: #bae6fd;
        --blue-300: #7dd3fc;
        --blue-400: #38bdf8;
        --blue-500: #0ea5e9;
        --blue-600: #0284c7;
        --blue-700: #0369a1;
        --blue-800: #075985;
    }

    .btn-animate {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-animate::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-animate:hover::before {
        left: 100%;
    }

    .btn-animate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.3) !important;
    }

    .btn-animate:active {
        transform: translateY(0);
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(14, 165, 233, 0.1) !important;
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }

    .progress-bar-animated-custom {
        background: linear-gradient(45deg, var(--blue-400), var(--blue-600));
        animation: progress-shine 2s linear infinite;
    }

    @keyframes progress-shine {
        0% { background-position: -200px 0; }
        100% { background-position: 200px 0; }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .bg-blue-light {
        background: linear-gradient(135deg, var(--blue-50) 0%, var(--blue-100) 100%);
    }

    .text-blue-light {
        color: var(--blue-500);
    }

    .border-blue-light {
        border-color: var(--blue-200) !important;
    }

    .bg-blue-gradient {
        background: linear-gradient(135deg, var(--blue-400) 0%, var(--blue-600) 100%);
    }

    .form-control:focus {
        border-color: var(--blue-400);
        box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
    }

    .form-check-input:checked {
        background-color: var(--blue-500);
        border-color: var(--blue-500);
    }

    .form-check-input:focus {
        border-color: var(--blue-400);
        box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
    }

    .quick-amount {
        background-color: white !important;
        color: var(--blue-500) !important;
        border: 2px solid var(--blue-300) !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
    }

    .quick-amount:hover {
        background-color: var(--blue-50) !important;
        border-color: var(--blue-400) !important;
        transform: translateY(-1px);
    }

    .quick-amount.active {
        background: linear-gradient(135deg, var(--blue-500) 0%, var(--blue-600) 100%) !important;
        color: white !important;
        border-color: var(--blue-600) !important;
        transform: scale(1.05) !important;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4) !important;
    }

    .quick-amount.active:hover {
        background: linear-gradient(135deg, var(--blue-600) 0%, var(--blue-700) 100%) !important;
        transform: scale(1.05) translateY(-1px) !important;
    }

    .input-focus-effect {
        transition: all 0.3s ease;
    }

    .input-focus-effect:focus {
        transform: scale(1.02);
    }

    .icon-bounce {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    .shake-animation {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>

<div class="bg-blue-light min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Enhanced Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4 fade-in">
                    <ol class="breadcrumb bg-white rounded-pill px-4 py-3 shadow-sm border border-blue-light">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none fw-medium btn-animate text-blue-light">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('campaign.show', $campaign->id) }}" class="text-decoration-none fw-medium btn-animate text-blue-light">
                                {{ Str::limit($campaign->title, 30) }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted">Donasi</li>
                    </ol>
                </nav>

                <!-- Enhanced Form Card -->
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden card-hover fade-in">
                    <div class="card-header bg-blue-gradient text-white py-4">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-heart me-2 icon-bounce"></i>Form Donasi
                        </h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Enhanced Campaign Summary -->
                        <div class="row mb-4 p-3 bg-blue-light rounded-3 border border-blue-light">
                            <div class="col-md-4 mb-3 mb-md-0">
                                @if($campaign->image)
                                    <div class="position-relative overflow-hidden rounded-3 btn-animate">
                                        <img src="{{ asset('storage/' . $campaign->image) }}" 
                                             class="img-fluid rounded-3 w-100" 
                                             alt="{{ $campaign->title }}"
                                             style="height: 150px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="bg-white rounded-3 d-flex align-items-center justify-content-center btn-animate" style="height: 150px;">
                                        <i class="fas fa-image text-blue-light icon-bounce" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h5 class="text-blue-light fw-bold mb-2">{{ $campaign->title }}</h5>
                                <p class="text-muted mb-3">{{ Str::limit($campaign->description, 150) }}</p>
                                
                                <div class="progress mb-2 rounded-pill shadow-sm" style="height: 10px;">
                                    <div class="progress-bar progress-bar-animated-custom rounded-pill" 
                                         style="width: {{ $campaign->progress_percentage }}%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted fw-medium">{{ $campaign->formatted_collected }} dari {{ $campaign->formatted_target }}</small>
                                    <small class="text-blue-light fw-bold">{{ number_format($campaign->progress_percentage, 1) }}%</small>
                                </div>
                            </div>
                        </div>

                        <hr class="border-blue-light">

                        <!-- Enhanced Donation Form -->
                        <form action="{{ route('donation.store') }}" method="POST" id="donationForm">
                            @csrf
                            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

    <input type="hidden" name="donor_name" value="{{ Auth::user()->name }}">
    <input type="hidden" name="donor_email" value="{{ Auth::user()->email }}">

                            <!-- Enhanced Anonymous Checkbox -->
                            <div class="mb-4 p-3 bg-blue-light rounded-3 border border-blue-light">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input btn-animate" 
                                           id="is_anonymous" 
                                           name="is_anonymous" 
                                           value="1" 
                                           {{ old('is_anonymous') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="is_anonymous">
                                        <i class="fas fa-user-secret text-blue-light me-2"></i>
                                        Donasi sebagai Anonim (Seseorang)
                                    </label>
                                </div>
                            </div>

                            <!-- Enhanced Amount Input -->
                            <div class="mb-4">
                                <label for="amount" class="form-label fw-bold text-dark">
                                    <i class="fas fa-coins text-blue-light me-2"></i>Nominal Donasi 
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-blue-gradient text-white fw-bold border-0">Rp</span>
                                    <input type="number" 
                                           class="form-control input-focus-effect @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount') }}" 
                                           min="10000" 
                                           placeholder="Minimal Rp 10.000" 
                                           style="border-top-right-radius: 25px; border-bottom-right-radius: 25px;"
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Enhanced Quick Amount -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-bolt text-blue-light me-2"></i>Pilih Nominal Cepat:
                                </label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach([25000, 50000, 100000, 250000, 500000] as $value)
                                        <button type="button" 
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 quick-amount btn-animate fw-medium" 
                                                data-amount="{{ $value }}">
                                            Rp {{ number_format($value, 0, ',', '.') }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Enhanced Comment -->
                            <div class="mb-4">
                                <label for="comment" class="form-label fw-bold text-dark">
                                    <i class="fas fa-comment-alt text-blue-light me-2"></i>Pesan/Doa (Opsional)
                                </label>
                                <textarea class="form-control input-focus-effect rounded-3 @error('comment') is-invalid @enderror" 
                                          id="comment" 
                                          name="comment" 
                                          rows="4" 
                                          placeholder="Tulis pesan atau doa untuk campaign ini...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Enhanced Terms -->
                            <div class="mb-4 p-3 bg-blue-light rounded-3 border border-blue-light">
                                <div class="form-check">
                                    <input class="form-check-input btn-animate" type="checkbox" id="terms" required>
                                    <label class="form-check-label fw-medium" for="terms">
                                        <i class="fas fa-shield-alt text-blue-light me-2"></i>
                                        Saya setuju dengan 
                                        <a href="#" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#termsModal" 
                                           class="text-blue-light fw-bold text-decoration-none btn-animate">
                                            syarat dan ketentuan
                                        </a> 
                                        yang berlaku
                                    </label>
                                </div>
                            </div>

                            <!-- Enhanced Action Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('campaign.show', $campaign->id) }}" 
                                   class="btn btn-outline-secondary btn-lg rounded-pill me-md-2 btn-animate">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" 
                                        class="btn btn-success btn-lg rounded-pill px-5 py-3 fw-bold btn-animate pulse-animation">
                                    <i class="fas fa-heart me-2"></i>Lanjut ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-blue-gradient text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-contract me-2"></i>Syarat dan Ketentuan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <h6 class="text-blue-light fw-bold">
                        <i class="fas fa-hand-holding-heart me-2"></i>1. Ketentuan Donasi
                    </h6>
                    <p class="text-muted">Donasi yang telah diberikan bersifat final dan tidak dapat dibatalkan atau diminta kembali.</p>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-blue-light fw-bold">
                        <i class="fas fa-coins me-2"></i>2. Penggunaan Dana
                    </h6>
                    <p class="text-muted">Dana yang terkumpul akan digunakan sesuai dengan tujuan campaign yang telah dijelaskan.</p>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-blue-light fw-bold">
                        <i class="fas fa-eye me-2"></i>3. Transparansi
                    </h6>
                    <p class="text-muted">Kami berkomitmen untuk memberikan laporan penggunaan dana secara transparan.</p>
                </div>
                
                <div class="mb-0">
                    <h6 class="text-blue-light fw-bold">
                        <i class="fas fa-lock me-2"></i>4. Kerahasiaan Data
                    </h6>
                    <p class="text-muted mb-0">Data pribadi donatur akan dijaga kerahasiaannya sesuai dengan kebijakan privasi kami.</p>
                </div>
            </div>
            <div class="modal-footer border-0 bg-blue-light rounded-bottom-4">
                <button type="button" 
                        class="btn bg-blue-gradient text-white btn-lg rounded-pill px-4 btn-animate fw-bold" 
                        data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const quickAmountButtons = document.querySelectorAll('.quick-amount');
    const amountInput = document.getElementById('amount');
    const anonymousCheckbox = document.getElementById('is_anonymous');
    const nameInput = document.getElementById('donor_name');

    // Quick amount selection with animation
    quickAmountButtons.forEach(button => {
        button.addEventListener('click', function () {
            const amount = this.dataset.amount;
            amountInput.value = amount;
            quickAmountButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Add pulse effect to amount input
            amountInput.classList.add('pulse-animation');
            setTimeout(() => {
                amountInput.classList.remove('pulse-animation');
            }, 1000);
        });
    });

    // Remove active class when typing manually
    amountInput.addEventListener('input', () => {
        quickAmountButtons.forEach(btn => btn.classList.remove('active'));
    });

    // Toggle anonymous name with animation
    function toggleAnonymousName() {
        if (anonymousCheckbox.checked) {
            nameInput.value = 'Seseorang';
            nameInput.readOnly = true;
            nameInput.classList.add('bg-light');
        } else {
            nameInput.value = '';
            nameInput.readOnly = false;
            nameInput.classList.remove('bg-light');
        }
        
        // Add animation effect
        nameInput.classList.add('pulse-animation');
        setTimeout(() => {
            nameInput.classList.remove('pulse-animation');
        }, 600);
    }

    // Initial check (for old input after validation error)
    toggleAnonymousName();
    anonymousCheckbox.addEventListener('change', toggleAnonymousName);

    // Enhanced form validation
    const form = document.getElementById('donationForm');
    form.addEventListener('submit', function(e) {
        const amount = parseInt(amountInput.value);
        if (amount < 10000) {
            e.preventDefault();
            
            // Add shake animation to amount input
            amountInput.classList.add('shake-animation');
            setTimeout(() => {
                amountInput.classList.remove('shake-animation');
            }, 500);
            
            // Custom alert with better styling
            Swal.fire({
                icon: 'warning',
                title: 'Nominal Minimal',
                text: 'Minimal donasi adalah Rp 10.000',
                confirmButtonColor: '#0ea5e9',
                confirmButtonText: 'OK'
            });
            
            amountInput.focus();
        }
    });

    // Add fade-in animation for form elements
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe fade-in elements
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Add ripple effect to animated buttons
    document.querySelectorAll('.btn-animate').forEach(button => {
        button.addEventListener('click', function(e) {
            let ripple = document.createElement('span');
            let rect = this.getBoundingClientRect();
            let size = Math.max(rect.width, rect.height);
            let x = e.clientX - rect.left - size / 2;
            let y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
</script>

<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-effect 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-effect {
    to {
        transform: scale(2);
        opacity: 0;
    }
}
</style>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush