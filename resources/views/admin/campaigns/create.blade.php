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
                <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- TITLE --}}
                    <div class="mb-3">
                        <label class="form-label">Campaign Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" required>
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
                                    <option value="">Select Category</option>
                                    <option value="Kesehatan" {{ old('category') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                    <option value="Pendidikan" {{ old('category') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                    <option value="Lingkungan" {{ old('category') == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                                    <option value="Kemanusiaan" {{ old('category') == 'Kemanusiaan' ? 'selected' : '' }}>Kemanusiaan</option>
                                    <option value="Bencana" {{ old('category') == 'Bencana' ? 'selected' : '' }}>Bencana Alam</option>
                                    <option value="Sosial" {{ old('category') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
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
                                    value="{{ old('target_amount') }}" min="1000" required>
                                @error('target_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                  

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="6" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- IMAGE --}}
                    <div class="mb-3">
                        <label class="form-label">Campaign Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                            accept="image/*" onchange="previewImage(this)" required>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG</small>
                    </div>

                    <div class="mb-3" id="imagePreview" style="display: none;">
                        <img id="preview" src="" class="img-thumbnail" style="max-width: 200px;">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Campaign
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
                <h5><i class="fas fa-info-circle me-2"></i>Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i>Write a compelling title</li>
                    <li><i class="fas fa-check text-success me-2"></i>Set realistic target amount</li>
                    <li><i class="fas fa-check text-success me-2"></i>Add detailed description</li>
                    <li><i class="fas fa-check text-success me-2"></i>Upload high-quality image</li>
                    <li><i class="fas fa-check text-success me-2"></i>Choose appropriate category</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
