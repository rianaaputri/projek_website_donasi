@extends('layouts.app')

@section('title', 'Edit Donasi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4 fade-in">
                <ol class="breadcrumb bg-white rounded-pill px-4 py-3 shadow-sm border border-blue-light">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"
                            class="text-decoration-none fw-medium btn-animate text-blue-light">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('campaign.show', $donation->campaign->id) }}"
                            class="text-decoration-none fw-medium btn-animate text-blue-light">
                            {{ Str::limit($donation->campaign->title, 30) }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-muted">Edit Donasi</li>
                </ol>
            </nav>

            <!-- Card Form -->
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden card-hover fade-in">
                <div class="card-header bg-blue-gradient text-white py-4">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-heart me-2 icon-bounce"></i>Edit Donasi Anda
                    </h4>
                </div>
                <div class="card-body p-4">

                    <!-- Campaign Info -->
                    <div class="row mb-4 p-3 bg-blue-light rounded-3 border border-blue-light">
                        <div class="col-md-4 mb-3 mb-md-0">
                            @if($donation->campaign->image)
                            <div class="position-relative overflow-hidden rounded-3 btn-animate">
                                <img src="{{ asset('storage/' . $donation->campaign->image) }}"
                                    class="img-fluid rounded-3 w-100" alt="{{ $donation->campaign->title }}"
                                    style="height: 150px; object-fit: cover;">
                            </div>
                            @else
                            <div class="bg-white rounded-3 d-flex align-items-center justify-content-center btn-animate"
                                style="height: 150px;">
                                <i class="fas fa-image text-blue-light icon-bounce" style="font-size: 2rem;"></i>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5 class="text-blue-light fw-bold mb-2">{{ $donation->campaign->title }}</h5>
                            <p class="text-muted mb-3">{{ Str::limit($donation->campaign->description, 150) }}</p>
                            <div class="progress mb-2 rounded-pill shadow-sm" style="height: 10px;">
                                <div class="progress-bar progress-bar-animated-custom rounded-pill"
                                    style="width: {{ $donation->campaign->progress_percentage }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted fw-medium">{{ $donation->campaign->formatted_collected }} dari
                                    {{ $donation->campaign->formatted_target }}</small>
                                <small class="text-blue-light fw-bold">{{
                                    number_format($donation->campaign->progress_percentage, 1) }}%</small>
                            </div>
                        </div>
                    </div>

                    <hr class="border-blue-light">

                    <!-- Edit Donation Form -->
                    <form action="{{ route('donation.update', $donation->id) }}" method="POST" id="donationForm">
                        @csrf
                        @method('PUT')


                        <!-- Nominal Donasi -->
                        <div class="mb-4">
                            <label for="amount" class="form-label fw-bold text-dark">
                                <i class="fas fa-coins text-blue-light me-2"></i>Nominal Donasi <span
                                    class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg mb-3">
                                <span class="input-group-text bg-blue-gradient text-white fw-bold border-0">Rp</span>
                                <input type="number"
                                    class="form-control input-focus-effect @error('amount') is-invalid @enderror"
                                    id="amount" name="amount" value="{{ old('amount', $donation->amount) }}" min="10000"
                                    required>
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Quick Amount Buttons -->
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

                        @push('scripts')
                        <script>
                            document.querySelectorAll('.quick-amount').forEach(button => {
                                button.addEventListener('click', function () {
                                    let amount = this.getAttribute('data-amount');
                                    document.getElementById('amount').value = amount;

                                    // Tambah efek active di tombol
                                    document.querySelectorAll('.quick-amount').forEach(btn => btn.classList.remove('active'));
                                    this.classList.add('active');
                                });
                            });
                        </script>

                        <style>
                            .quick-amount.active {
                                background-color: #3b82f6;
                                color: #fff;
                                border-color: #3b82f6;
                            }
                        </style>
                        @endpush


                        <!-- Pesan -->
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold text-dark">
                                <i class="fas fa-comment-alt text-blue-light me-2"></i>Pesan/Doa (Opsional)
                            </label>
                            <textarea class="form-control input-focus-effect rounded-3" id="comment" name="comment"
                                rows="4">{{ old('comment', $donation->comment) }}</textarea>
                        </div>

                        <!-- Tombol -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('donation.pending') }}"
                                class="btn btn-outline-secondary btn-lg rounded-pill me-md-2 btn-animate">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit"
                                class="btn btn-success btn-lg rounded-pill px-5 py-3 fw-bold btn-animate pulse-animation">
                                <i class="fas fa-save me-2"></i>Lanjutkan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection