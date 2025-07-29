@extends('layouts.admin')

@section('title', 'Edit Campaign')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Campaign</h1>
    <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $campaign->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="5" required>{{ old('description', $campaign->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Target Amount</label>
                        <input type="number" name="target_amount" class="form-control @error('target_amount') is-invalid @enderror" 
                               value="{{ old('target_amount', $campaign->target_amount) }}" required>
                        @error('target_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="Kesehatan" {{ $campaign->category == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="Pendidikan" {{ $campaign->category == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Bencana" {{ $campaign->category == 'Bencana' ? 'selected' : '' }}>Bencana Alam</option>
                            <option value="Sosial" {{ $campaign->category == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                       value="{{ old('end_date', $campaign->end_date?->format('Y-m-d')) }}" required>
                @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Image</label>
                @if($campaign->image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($campaign->image) }}" alt="Current image" class="img-thumbnail" style="height: 100px">
                    </div>
                @endif
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Campaign</button>
                <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
