@extends('layouts.admin')

@section('title', 'Create Artifact')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Create New Artifact</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.artifacts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    <option value="">Choose Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required></textarea>
                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="cultural_significance" class="form-label">Cultural Significance</label>
                <textarea class="form-control @error('cultural_significance') is-invalid @enderror" id="cultural_significance" name="cultural_significance" rows="4"></textarea>
                @error('cultural_significance') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                <small class="text-muted">JPG, PNG (Max 5MB)</small>
                @error('image') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords (comma separated)</label>
                <input type="text" class="form-control @error('keywords') is-invalid @enderror" id="keywords" name="keywords" placeholder="keyword1, keyword2, keyword3">
                @error('keywords') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_published" name="is_published" checked>
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
