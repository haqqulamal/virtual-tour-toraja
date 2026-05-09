@extends('layouts.admin')

@section('title', 'Edit Scene')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Edit Scene: {{ $scene->title }}</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.scenes.update', $scene->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">{{ __('messages.title') }}</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $scene->title) }}" required>
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">{{ __('messages.description') }}</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $scene->description) }}</textarea>
                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">{{ __('messages.location') }}</label>
                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $scene->location) }}" required>
                @error('location') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="latitude" class="form-label">{{ __('messages.latitude') }}</label>
                    <input type="number" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" step="0.0001" value="{{ old('latitude', $scene->latitude) }}" required>
                    @error('latitude') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="longitude" class="form-label">{{ __('messages.longitude') }}</label>
                    <input type="number" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" step="0.0001" value="{{ old('longitude', $scene->longitude) }}" required>
                    @error('longitude') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="panorama_image" class="form-label">{{ __('messages.panorama_image') }}</label>
                @if($scene->panorama_image)
                    <div class="mb-2">
                        <img src="{{ asset('uploads/scenes/' . $scene->panorama_image) }}" alt="{{ $scene->title }}" style="max-width: 200px; border-radius: 4px;">
                    </div>
                @endif
                <input type="file" class="form-control @error('panorama_image') is-invalid @enderror" id="panorama_image" name="panorama_image" accept="image/*">
                <small class="text-muted">Leave empty to keep current image</small>
                @error('panorama_image') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="order" class="form-label">{{ __('messages.order') }}</label>
                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $scene->order) }}" required>
                @error('order') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_published" name="is_published" {{ old('is_published', $scene->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">
                    {{ __('messages.is_published') }}
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ __('messages.save') }}
                </button>
                <a href="{{ route('admin.scenes.index') }}" class="btn btn-secondary">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
