@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-blue: #3b82f6;
        --light-blue: #dbeafe;
        --soft-blue: #eff6ff;
        --blue-50: #f8fafc;
        --blue-100: #e2e8f0;
        --blue-200: #cbd5e1;
        --blue-600: #2563eb;
        --blue-700: #1d4ed8;
        --success-green: #22c55e;
        --danger-red: #ef4444;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --shadow-soft: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-medium: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-large: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    body {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--blue-50) 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--text-primary);
    }

    .campaign-wrapper {
        min-height: 100vh;
        padding: 2rem 0;
    }

    .main-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-large);
        border: 1px solid var(--blue-100);
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--light-blue) 100%);
        padding: 2rem;
        border-bottom: 1px solid var(--blue-100);
    }

    .page-title {
        color: var(--primary-blue);
        font-weight: 700;
        font-size: 1.875rem;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0;
        font-weight: 400;
    }

    .card-body-custom {
        padding: 2rem;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        letter-spacing: 0.025em;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--blue-200);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--text-primary);
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-control:hover {
        border-color: var(--blue-600);
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--blue-200);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--text-primary);
        cursor: pointer;
    }

    .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--blue-200);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--text-primary);
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }

    .form-textarea:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    /* Checkbox Styles */
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-checkbox {
        width: 18px;
        height: 18px;
        border: 2px solid var(--blue-200);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-checkbox:checked {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .checkbox-label {
        color: var(--text-primary);
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        margin: 0;
    }

    /* Buttons */
    .btn-primary-soft {
        background: var(--primary-blue);
        border: 1px solid var(--primary-blue);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-soft);
        cursor: pointer;
    }

    .btn-primary-soft:hover {
        background: var(--blue-700);
        border-color: var(--blue-700);
        color: white;
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium);
    }

    .btn-secondary-soft {
        background: transparent;
        border: 1px solid var(--blue-200);
        color: var(--text-secondary);
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary-soft:hover {
        background: var(--blue-50);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        text-decoration: none;
    }

    /* Error Alert */
    .alert-danger-soft {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-danger-soft ul {
        margin: 0;
        padding-left: 1.25rem;
    }

    .alert-danger-soft li {
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: flex-end;
        padding-top: 1.5rem;
        border-top: 1px solid var(--blue-100);
        margin-top: 2rem;
    }

    /* Form Grid */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    /* Required indicator */
    .required::after {
        content: ' *';
        color: var(--danger-red);
        font-weight: bold;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .campaign-wrapper {
            padding: 1rem 0;
        }

        .card-header-custom,
        .card-body-custom {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .btn-primary-soft,
        .btn-secondary-soft {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .card-header-custom,
        .card-body-custom {
            padding: 1.25rem;
        }
    }
</style>

<div class="campaign-wrapper">
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="card-header-custom">
                <div class="text-center">
                    <h1 class="page-title">Tambah Campaign Baru</h1>
                    <p class="page-subtitle">Buat campaign fundraising untuk mencapai tujuan yang bermakna</p>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body-custom">
                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert-danger-soft">
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                            <strong>Terdapat kesalahan pada form:</strong>
                        </div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('campaign.store') }}">
                    @csrf
                    
                    <!-- Campaign Title -->
                    <div class="form-group">
                        <label class="form-label required">Judul Campaign</label>
                        <input type="text" 
                               name="title" 
                               class="form-control" 
                               value="{{ old('title') }}" 
                               placeholder="Masukkan judul campaign yang menarik"
                               required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label required">Deskripsi Campaign</label>
                        <textarea name="description" 
                                  class="form-textarea" 
                                  placeholder="Jelaskan tujuan dan detail campaign Anda"
                                  required>{{ old('description') }}</textarea>
                    </div>

                    <!-- Form Row for Category and Target -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Target Donasi</label>
                            <input type="number" 
                                   name="target_amount" 
                                   class="form-control" 
                                   value="{{ old('target_amount') }}" 
                                   placeholder="500000"
                                   min="1"
                                   required>
                        </div>
                    </div>

                    <!-- Image URL -->
                    <div class="form-group">
                        <label class="form-label">Gambar Campaign (URL)</label>
                        <input type="url" 
                               name="image" 
                               class="form-control" 
                               value="{{ old('image') }}" 
                               placeholder="https://example.com/image.jpg">
                    </div>

                    <!-- Form Row for Status and Active -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Status Campaign</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pengaturan</label>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       class="form-checkbox"
                                       id="is_active"
                                       {{ old('is_active') ? 'checked' : '' }}>
                                <label for="is_active" class="checkbox-label">Campaign Aktif</label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('campaign.index') }}" class="btn-secondary-soft">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn-primary-soft">
                            <i class="fas fa-save me-2"></i>
                            Simpan Campaign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and enhancements
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    // Add floating label effect
    inputs.forEach(input => {
        // Focus effects
        input.addEventListener('focus', function() {
            this.style.transform = 'translateY(-1px)';
            this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1), 0 4px 6px -1px rgb(0 0 0 / 0.1)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
            if (!this.matches(':focus')) {
                this.style.boxShadow = '';
            }
        });
    });

    // Button click effects
    document.querySelectorAll('.btn-primary-soft, .btn-secondary-soft').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
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

    // Form submission loading state
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.disabled = true;
    });

    // Target amount formatting
    const targetAmountInput = document.querySelector('input[name="target_amount"]');
    if (targetAmountInput) {
        targetAmountInput.addEventListener('input', function() {
            // Remove non-numeric characters except for the decimal point
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});
</script>

<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Custom scrollbar for textarea */
.form-textarea::-webkit-scrollbar {
    width: 6px;
}

.form-textarea::-webkit-scrollbar-track {
    background: var(--blue-100);
    border-radius: 3px;
}

.form-textarea::-webkit-scrollbar-thumb {
    background: var(--blue-200);
    border-radius: 3px;
}

.form-textarea::-webkit-scrollbar-thumb:hover {
    background: var(--primary-blue);
}
</style>

@endsection