@extends('layouts.admin')

@section('title', 'Add New Campaign')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>Add New Campaign</h2>
    <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Campaigns
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data" id="campaignForm">
                    @csrf

                    {{-- TITLE --}}
                    <div class="mb-3">
                        <label class="form-label">Campaign Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" required maxlength="255">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CATEGORY & TARGET --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @if(isset($categories) && is_array($categories))
                                        @foreach($categories as $key => $value)
                                            <option value="{{ $key }}" 
                                                    {{ old('category') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="health">Kesehatan</option>
                                        <option value="education">Pendidikan</option>
                                        <option value="disaster">Bencana Alam</option>
                                        <option value="social">Sosial</option>
                                        <option value="environment">Lingkungan</option>
                                    @endif
                                </select>
                                @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Target Amount (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="target_amount" class="form-control @error('target_amount') is-invalid @enderror"
                                    value="{{ old('target_amount') }}" min="1000" step="1000" required>
                                @error('target_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- END DATE --}}
                    <div class="mb-3">
                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                            value="{{ old('end_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- HIDDEN USER ID --}}
                    <input type="hidden" name="user_id" value="{{ auth()->id() ?? 1 }}">

                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="6" required maxlength="5000" placeholder="Jelaskan detail kampanye fundraising Anda...">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">
                            <span id="charCount">0</span>/5000 karakter
                        </div>
                    </div>

                    {{-- IMAGE --}}
                    <div class="mb-3">
                        <label class="form-label">Campaign Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewImage(this)" required>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                    </div>

                    <div class="mb-3" id="imagePreview" style="display: none;">
                        <img id="preview" src="" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>

                    {{-- ERROR DISPLAY --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6>Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i><span id="submitText">Create Campaign</span>
                        </button>
                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TIPS --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Tips Kampanye Sukses</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tulis judul yang menarik dan jelas</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tetapkan target yang realistis</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Pilih kategori yang tepat</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tentukan tanggal berakhir yang wajar</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Buat deskripsi yang detail dan meyakinkan</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Upload gambar berkualitas tinggi</li>
                    <li><i class="fas fa-check text-success me-2"></i>Verifikasi semua informasi sebelum submit</li>
                </ul>
            </div>
        </div>

        {{-- QUICK STATS --}}
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar me-2"></i>Info Sistem</h5>
            </div>
            <div class="card-body">
                <div class="small text-muted">
                    <div class="mb-2">
                        <strong>Kategori Tersedia:</strong> 
                        @if(isset($categories))
                            {{ count($categories) }}
                        @else
                            5
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>Target Minimum:</strong> Rp 1,000
                    </div>
                    <div class="mb-2">
                        <strong>Ukuran File Maks:</strong> 2MB
                    </div>
                    <div>
                        <strong>Format Gambar:</strong> JPG, PNG, GIF
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Prevent multiple form submissions
let isSubmitting = false;

document.getElementById('campaignForm').addEventListener('submit', function(e) {
    if (isSubmitting) {
        e.preventDefault();
        return false;
    }
    
    // Validate form before submit
    if (!validateForm()) {
        e.preventDefault();
        return false;
    }
    
    isSubmitting = true;
    
    // Update submit button
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
    
    // Re-enable after 10 seconds as fallback
    setTimeout(() => {
        isSubmitting = false;
        submitBtn.disabled = false;
        submitText.innerHTML = 'Create Campaign';
    }, 10000);
});

// Form validation
function validateForm() {
    let isValid = true;
    const form = document.getElementById('campaignForm');
    
    // Clear previous error messages
    form.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    
    // Validate title
    const title = form.querySelector('[name="title"]');
    if (!title.value.trim()) {
        showFieldError(title, 'Title is required');
        isValid = false;
    }
    
    // Validate category
    const category = form.querySelector('[name="category"]');
    if (!category.value) {
        showFieldError(category, 'Category is required');
        isValid = false;
    }
    
    // Validate target amount
    const targetAmount = form.querySelector('[name="target_amount"]');
    if (!targetAmount.value || parseInt(targetAmount.value) < 1000) {
        showFieldError(targetAmount, 'Target amount must be at least Rp 1,000');
        isValid = false;
    }
    
    // Validate end date
    const endDate = form.querySelector('[name="end_date"]');
    const today = new Date();
    const selectedDate = new Date(endDate.value);
    
    if (!endDate.value || selectedDate <= today) {
        showFieldError(endDate, 'End date must be in the future');
        isValid = false;
    }
    
    // Validate description
    const description = form.querySelector('[name="description"]');
    if (!description.value.trim() || description.value.trim().length < 30) {
        showFieldError(description, 'Description must be at least 30 characters');
        isValid = false;
    }
    
    // Validate image
    const image = form.querySelector('[name="image"]');
    if (!image.files.length) {
        showFieldError(image, 'Campaign image is required');
        isValid = false;
    } else {
        const file = image.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        
        if (file.size > maxSize) {
            showFieldError(image, 'Image size must be less than 2MB');
            isValid = false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            showFieldError(image, 'Invalid image format. Use JPG, PNG, or GIF');
            isValid = false;
        }
    }
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    let feedback = field.parentNode.querySelector('.invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        field.parentNode.appendChild(feedback);
    }
    feedback.textContent = message;
}

// Image preview function
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size and type
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        
        if (file.size > maxSize) {
            alert('File size too large! Maximum 2MB allowed.');
            input.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        if (!allowedTypes.includes(file.type)) {
            alert('Invalid file type! Please use JPG, PNG, or GIF.');
            input.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}

// Auto-format number input (thousand separator)
const targetAmountInput = document.querySelector('input[name="target_amount"]');
if (targetAmountInput) {
    targetAmountInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        if (value) {
            // Remove leading zeros
            value = parseInt(value).toString();
            e.target.value = value;
        }
    });
    
    // Format display on blur
    targetAmountInput.addEventListener('blur', function(e) {
        let value = parseInt(e.target.value);
        if (value && !isNaN(value)) {
            // You can add thousand separator here if needed
            // e.target.value = value.toLocaleString('id-ID');
        }
    });
}

// Character counter for description
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
    
    descriptionInput.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
}

// Auto-save draft (optional - you can implement this if needed)
function autoSaveDraft() {
    const formData = new FormData(document.getElementById('campaignForm'));
    const draftData = {};
    
    for (let [key, value] of formData.entries()) {
        if (key !== 'image' && key !== '_token') {
            draftData[key] = value;
        }
    }
    
    // Save to localStorage (you can change this to send to server)
    localStorage.setItem('campaign_draft', JSON.stringify(draftData));
}

// Load draft on page load
function loadDraft() {
    const savedDraft = localStorage.getItem('campaign_draft');
    if (savedDraft) {
        const draftData = JSON.parse(savedDraft);
        const form = document.getElementById('campaignForm');
        
        Object.keys(draftData).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field && !field.value) {
                field.value = draftData[key];
            }
        });
    }
}

// Clear draft after successful submission
function clearDraft() {
    localStorage.removeItem('campaign_draft');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadDraft();
    
    // Auto-save every 30 seconds
    setInterval(autoSaveDraft, 30000);
});
</script>
@endsection