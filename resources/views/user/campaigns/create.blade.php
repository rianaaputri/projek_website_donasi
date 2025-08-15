@extends('layouts.app')
@section('title', 'Buat Campaign Donasi')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-plus-circle me-2"></i> Buat Campaign Donasi Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.campaigns.store') }}" method="POST" enctype="multipart/form-data" id="campaignForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Judul Campaign <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" required maxlength="255">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="health" {{ old('category') == 'health' ? 'selected' : '' }}>Kesehatan</option>
                                        <option value="education" {{ old('category') == 'education' ? 'selected' : '' }}>Pendidikan</option>
                                        <option value="disaster" {{ old('category') == 'disaster' ? 'selected' : '' }}>Bencana Alam</option>
                                        <option value="social" {{ old('category') == 'social' ? 'selected' : '' }}>Sosial</option>
                                        <option value="environment" {{ old('category') == 'environment' ? 'selected' : '' }}>Lingkungan</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Target Dana (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" name="target_amount" class="form-control @error('target_amount') is-invalid @enderror"
                                           value="{{ old('target_amount') }}" min="1000" step="1000" required>
                                    @error('target_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="6" required maxlength="5000">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                <span id="charCount">0</span>/5000 karakter
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar Campaign <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/jpg,image/gif" id="imageInput" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Max 2MB. Format: JPG, PNG, GIF</small>
                        </div>

                        <div class="mb-3" id="imagePreview" style="display: none;">
                            <img id="preview" src="" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i><span id="submitText">Submit Campaign</span>
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    .card {
        border-radius: 12px;
    }
    .card-header {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter untuk description
    const descriptionInput = document.querySelector('[name="description"]');
    const charCounter = document.getElementById('charCount');

    if (descriptionInput && charCounter) {
        function updateCharCount() {
            const currentLength = descriptionInput.value.length;
            charCounter.textContent = currentLength;
            
            if (currentLength > 5000) {
                charCounter.style.color = '#dc3545';
                descriptionInput.classList.add('is-invalid');
            } else {
                charCounter.style.color = currentLength > 4500 ? '#ffc107' : '#6c757d';
                descriptionInput.classList.remove('is-invalid');
            }
        }
        
        // Event listener untuk input
        descriptionInput.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial count
    }

    // Image preview functionality
    const imageInput = document.getElementById('imageInput');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            previewImage(this);
        });
    }

    // Form submission handler
    const campaignForm = document.getElementById('campaignForm');
    if (campaignForm) {
        campaignForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            
            // Prevent double submission
            if (submitBtn.disabled) {
                e.preventDefault();
                return false;
            }
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
        });
    }
});

// Function untuk preview image
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

        // Validate file size
        if (file.size > maxSize) {
            alert('Ukuran file maksimal 2MB.');
            input.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        // Validate file type
        if (!allowedTypes.includes(file.type)) {
            alert('Format tidak didukung. Gunakan JPG, PNG, atau GIF.');
            input.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}
</script>

@endsection