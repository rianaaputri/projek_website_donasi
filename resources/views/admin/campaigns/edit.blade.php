{{-- resources/views/admin/campaigns/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Campaign')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Campaign</h1>
    <div>
        <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title Field -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Campaign Title *</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $campaign->title) }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  required>{{ old('description', $campaign->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category Field - Fixed Version -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" 
                                name="category" 
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            @if(isset($categories) && is_array($categories))
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" 
                                            {{ old('category', $campaign->category) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Target Amount Field -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="target_amount" class="form-label">Target Amount (Rp) *</label>
                                <input type="number" 
                                       class="form-control @error('target_amount') is-invalid @enderror" 
                                       id="target_amount" 
                                       name="target_amount" 
                                       value="{{ old('target_amount', $campaign->target_amount) }}" 
                                       min="1000" 
                                       step="1000" 
                                       required>
                                @error('target_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date Field -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="datetime-local" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date', $campaign->end_date ? $campaign->end_date->format('Y-m-d\TH:i') : '') }}" 
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- User Selection - Only show if user_id column exists -->
                    @if($users->count() > 0)
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Campaign Owner *</label>
                        <select class="form-select @error('user_id') is-invalid @enderror" 
                                id="user_id" 
                                name="user_id" 
                                required>
                            <option value="">-- Pilih Pemilik Campaign --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                        {{ old('user_id', $campaign->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                 <input type="hidden" name="user_id" value="{{ $campaign->user_id }}">

                    @if(isset($campaign->user))
                    <div class="mb-3">
                        <label class="form-label">Campaign Owner</label>
                        <div class="form-control-plaintext">
                            {{ $campaign->user->name }} ({{ $campaign->user->email }})
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Status Field -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="">-- Pilih Status --</option>
                            <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $campaign->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="completed" {{ old('status', $campaign->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $campaign->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Field -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Campaign Image</label>
                        @if($campaign->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($campaign->image) }}" 
                                     alt="Current Image" 
                                     class="img-thumbnail" 
                                     style="max-height: 200px;">
                                <p class="text-muted small mt-1">Current image</p>
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <small class="text-muted">Leave empty to keep current image. Max size: 2MB</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Campaign
                        </button>
                        <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Campaign Statistics Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Campaign Statistics</h5>
                @php
                    $collected = $campaign->donations()->sum('amount');
                    $percentage = $campaign->target_amount > 0 ? ($collected/$campaign->target_amount)*100 : 0;
                @endphp
                <div class="progress mb-2" style="height: 10px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <div>Collected</div>
                    <div>Rp {{ number_format($collected) }}</div>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <div>Target</div>
                    <div>Rp {{ number_format($campaign->target_amount) }}</div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>Progress</div>
                    <div>{{ number_format($percentage, 1) }}%</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">Debug Info</h6>
                <small class="text-muted">
                    <strong>Current Category:</strong> {{ $campaign->category }}<br>
                    <strong>Available Categories:</strong><br>
                    @if(isset($categories))
                        @foreach($categories as $key => $value)
                            • {{ $key }} => {{ $value }}<br>
                        @endforeach
                    @else
                        No categories found
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Log category values
    console.log('Current campaign category:', '{{ $campaign->category }}');
    console.log('Available categories:', @json($categories ?? []));
    
    // Auto-select current category if not already selected
    const categorySelect = document.getElementById('category');
    const currentCategory = '{{ $campaign->category }}';
    
    if (categorySelect && currentCategory) {
        // Find and select the correct option
        for (let option of categorySelect.options) {
            if (option.value === currentCategory) {
                option.selected = true;
                break;
            }
        }
    }
});
</script>
@endsection