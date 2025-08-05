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
                    <h4 class="mb-0"><i class="fas fa-heart me-2"></i>Form Donasi</h4>
                </div>
                
                <div class="card-body">
                    <!-- Campaign Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($campaign->image)
                                <img src="{{ asset('storage/' . $campaign->image) }}" class="img-fluid rounded" alt="{{ $campaign->title }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5>{{ $campaign->title }}</h5>
                            <p class="text-muted">{{ Str::limit($campaign->description, 150) }}</p>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $campaign->progress_percentage }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">{{ $campaign->formatted_collected }} dari {{ $campaign->formatted_target }}</small>
                                <small class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}%</small>
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
                                <input type="text" class="form-control @error('donor_name') is-invalid @enderror" id="donor_name" name="donor_name" value="{{ old('donor_name') }}" required>
                                @error('donor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="donor_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('donor_email') is-invalid @enderror" id="donor_email" name="donor_email" value="{{ old('donor_email') }}" required>
                                @error('donor_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Donasi Anonim -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_anonymous" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_anonymous">
                                    Anonim
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Nominal Donasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" min="1000" placeholder="Minimal Rp 10.000" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Quick Amount -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Nominal Cepat:</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach([25000, 50000, 100000, 250000, 500000] as $value)
                                    <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="{{ $value }}">Rp {{ number_format($value, 0, ',', '.') }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label">Pesan/Doa (Opsional)</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="4" placeholder="Tulis pesan atau doa untuk campaign ini...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Saya setuju dengan <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat dan ketentuan</a> yang berlaku
                                </label>
                            </div>
                        </div>

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

<!-- Modal Syarat dan Ketentuan -->
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
document.addEventListener('DOMContentLoaded', function () {
    const quickAmountButtons = document.querySelectorAll('.quick-amount');
    const amountInput = document.getElementById('amount');
    const anonymousCheckbox = document.getElementById('is_anonymous');
    const nameInput = document.getElementById('donor_name');

    quickAmountButtons.forEach(button => {
        button.addEventListener('click', function () {
            const amount = this.dataset.amount;
            amountInput.value = amount;
            quickAmountButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });

    amountInput.addEventListener('input', () => {
        quickAmountButtons.forEach(btn => btn.classList.remove('active'));
    });

    // Toggle anonymous name
    function toggleAnonymousName() {
        if (anonymousCheckbox.checked) {
            nameInput.value = 'Hamba Allah';
            nameInput.readOnly = true;
        } else {
            nameInput.value = '';
            nameInput.readOnly = false;
        }
    }

    // Initial check (for old input after validation error)
    toggleAnonymousName();

    anonymousCheckbox.addEventListener('change', toggleAnonymousName);

    // Minimal validation
    const form = document.getElementById('donationForm');
    form.addEventListener('submit', function(e) {
        const amount = parseInt(amountInput.value);
        if (amount < 10000) {
            e.preventDefault();
            alert('Minimal donasi adalah Rp 10.000');
            amountInput.focus();
        }
    });
});
</script>
@endpush
