@extends('layouts.admin')

@section('title', 'Edit Artifact')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Edit Artifact: {{ $artifact->name }}</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.artifacts.update', $artifact->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $artifact->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $artifact->name) }}" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $artifact->description) }}</textarea>
                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="cultural_significance" class="form-label">Cultural Significance</label>
                <textarea class="form-control @error('cultural_significance') is-invalid @enderror" id="cultural_significance" name="cultural_significance" rows="4">{{ old('cultural_significance', $artifact->cultural_significance) }}</textarea>
                @error('cultural_significance') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                @if($artifact->image)
                    <div class="mb-2">
                        <img src="{{ asset('uploads/artifacts/' . $artifact->image) }}" alt="{{ $artifact->name }}" style="max-width: 200px; border-radius: 4px;">
                    </div>
                @endif
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                <small class="text-muted">Leave empty to keep current image</small>
                @error('image') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords (comma separated)</label>
                <input type="text" class="form-control @error('keywords') is-invalid @enderror" id="keywords" name="keywords" value="{{ old('keywords', $artifact->keywords) }}">
                @error('keywords') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_published" name="is_published" {{ old('is_published', $artifact->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">
                    Published
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ __('messages.save') }}
                </button>
                <a href="{{ route('admin.artifacts.index') }}" class="btn btn-secondary">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
