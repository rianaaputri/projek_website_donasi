@extends('layouts.app')
@section('title', 'Buat Campaign Donasi')
@section('content')
<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white text-center py-4 rounded-top-4">
                    <h5 class="mb-0">
                        <i class="fas fa-hand-holding-heart me-2"></i> Buat Campaign Donasi Baru
                    </h5>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('user.campaigns.store') }}" method="POST" enctype="multipart/form-data" id="campaignForm">
                        @csrf

                        <!-- Judul Campaign -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-primary">
                                <i class="fas fa-heading text-primary me-1"></i> Judul Campaign <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" placeholder="Masukkan judul campaign..." required maxlength="255">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori & Target Dana -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary">
                                    <i class="fas fa-tags text-primary me-1"></i> Kategori <span class="text-danger">*</span>
                                </label>
                                <select name="category" class="form-select form-select-lg @error('category') is-invalid @enderror" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="health" {{ old('category') == 'health' ? 'selected' : '' }}>🏥 Kesehatan</option>
                                    <option value="education" {{ old('category') == 'education' ? 'selected' : '' }}>🎓 Pendidikan</option>
                                    <option value="disaster" {{ old('category') == 'disaster' ? 'selected' : '' }}>🌪️ Bencana Alam</option>
                                    <option value="social" {{ old('category') == 'social' ? 'selected' : '' }}>🤝 Sosial</option>
                                    <option value="environment" {{ old('category') == 'environment' ? 'selected' : '' }}>🌱 Lingkungan</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary">
                                    <i class="fas fa-money-bill-wave text-primary me-1"></i> Target Dana (Rp) <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="target_amount" 
                                    class="form-control form-control-lg @error('target_amount') is-invalid @enderror"
                                    value="{{ old('target_amount') }}" 
                                    placeholder="Contoh: 5000000" 
                                    min="10000" 
                                    max="1000000000" 
                                    step="1000" 
                                    required>
                                @error('target_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-primary">
                                <i class="fas fa-calendar-alt text-primary me-1"></i> Tanggal Berakhir <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="end_date" class="form-control form-control-lg @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-primary">
                                <i class="fas fa-align-left text-primary me-1"></i> Deskripsi <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="5" placeholder="Ceritakan detail campaign Anda..." required maxlength="5000">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-end mt-1">
                                <small><span id="charCount">0</span>/5000 karakter</small>
                            </div>
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-primary">
                                <i class="fas fa-image text-primary me-1"></i> Gambar Campaign <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/jpg,image/gif" id="imageInput" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-1">
                                Maksimal 2MB. Format: JPG, PNG, GIF.
                            </small>
                            <!-- 👇 Peringatan penting -->
                            <small class="text-warning d-block mt-1">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Perhatian:</strong> Jika terjadi kesalahan validasi, Anda harus upload ulang gambar.
                            </small>
                        </div>

                        <!-- Hidden input untuk simpan nama file sementara (siap untuk dikembangkan) -->
                        <input type="hidden" name="temp_image_name" id="temp_image_name" value="{{ old('temp_image_name') }}">

                        <!-- Preview Gambar -->
                        <div class="mb-4" id="imagePreview" style="display: none;">
                            <label class="form-label text-primary fw-semibold">Pratinjau Gambar:</label>
                            <div class="d-flex justify-content-center">
                                <img id="preview" src="" class="img-fluid rounded-3 shadow-sm" style="max-width: 300px; max-height: 300px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-end gap-3 mt-5">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm" id="submitBtn">
                                <span id="submitText"><i class="fas fa-paper-plane me-2"></i>Submit Campaign</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Font Global */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fc;
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 123, 255, 0.15) !important;
    }

    /* Soft Blue Gradient Header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4a90e2, #5dade2);
    }

    /* Input & Select Styling */
    .form-control, .form-select {
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        border: 1.5px solid #d0ebff;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        background-color: #ffffff;
    }

    /* Button Styling */
    .btn-primary {
        background-color: #4a90e2;
        border-color: #4a90e2;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #3a7bc8;
        border-color: #3a7bc8;
        transform: scale(1.05);
    }
    .btn-primary:disabled {
        background-color: #87ceeb;
        border-color: #87ceeb;
    }

    .btn-outline-secondary {
        border-radius: 50rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }
    .btn-outline-secondary:hover {
        background-color: #f1f1f1;
        transform: scale(1.02);
    }

    /* Character Counter */
    #charCount {
        font-weight: 600;
        color: #6c757d;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem;
        }
        .btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const descriptionInput = document.querySelector('[name="description"]');
    const charCounter = document.getElementById('charCount');
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const campaignForm = document.getElementById('campaignForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');

    // Update karakter
    if (descriptionInput && charCounter) {
        const updateCharCount = () => {
            const length = descriptionInput.value.length;
            charCounter.textContent = length;

            if (length > 5000) {
                charCounter.style.color = '#dc3545';
                descriptionInput.classList.add('is-invalid');
            } else if (length > 4500) {
                charCounter.style.color = '#ffc107';
                descriptionInput.classList.remove('is-invalid');
            } else {
                charCounter.style.color = '#6c757d';
                descriptionInput.classList.remove('is-invalid');
            }
        };

        descriptionInput.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    // Preview gambar
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const maxSize = 2 * 1024 * 1024;
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

                if (file.size > maxSize) {
                    alert('Ukuran file maksimal 2MB.');
                    this.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }

                if (!allowedTypes.includes(file.type)) {
                    alert('Format tidak didukung. Gunakan JPG, PNG, atau GIF.');
                    this.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }

    // Validasi frontend: Cegah submit jika deskripsi kurang dari 50 karakter
    campaignForm.addEventListener('submit', function(e) {
        const descValue = descriptionInput.value.trim();

        if (descValue.length < 50) {
            e.preventDefault();
            alert('Deskripsi minimal 50 karakter. Mohon periksa kembali.');
            descriptionInput.focus();
            return false;
        }

        if (submitBtn.disabled) {
            e.preventDefault();
            return;
        }

        submitBtn.disabled = true;
        submitText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
    });
});
</script>

@endsection