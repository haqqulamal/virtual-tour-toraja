@extends('layouts.admin')

@section('title', 'Edit Hotspot')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Edit Hotspot: {{ $hotspot->title }}</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.hotspots.update', $hotspot->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="scene_id" class="form-label">Scene</label>
                <select class="form-select @error('scene_id') is-invalid @enderror" id="scene_id" name="scene_id" required>
                    @foreach($scenes as $scene)
                        <option value="{{ $scene->id }}" {{ old('scene_id', $hotspot->scene_id) == $scene->id ? 'selected' : '' }}>
                            {{ $scene->title }}
                        </option>
                    @endforeach
                </select>
                @error('scene_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $hotspot->title) }}" required>
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $hotspot->description) }}</textarea>
                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pitch" class="form-label">Pitch (vertical angle)</label>
                    <input type="number" class="form-control @error('pitch') is-invalid @enderror" id="pitch" name="pitch" step="0.1" value="{{ old('pitch', $hotspot->pitch) }}" required>
                    @error('pitch') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="yaw" class="form-label">Yaw (horizontal angle)</label>
                    <input type="number" class="form-control @error('yaw') is-invalid @enderror" id="yaw" name="yaw" step="0.1" value="{{ old('yaw', $hotspot->yaw) }}" required>
                    @error('yaw') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required onchange="updateTypeFields()">
                    <option value="info" {{ old('type', $hotspot->type) === 'info' ? 'selected' : '' }}>Information Point</option>
                    <option value="scene" {{ old('type', $hotspot->type) === 'scene' ? 'selected' : '' }}>Scene Navigation</option>
                    <option value="artifact" {{ old('type', $hotspot->type) === 'artifact' ? 'selected' : '' }}>Artifact Link</option>
                </select>
                @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3" id="linkedSceneField">
                <label for="linked_scene_id" class="form-label">Link to Scene</label>
                <select class="form-select @error('linked_scene_id') is-invalid @enderror" id="linked_scene_id" name="linked_scene_id">
                    <option value="">Choose Scene</option>
                    @foreach($scenes as $scene)
                        <option value="{{ $scene->id }}" {{ old('linked_scene_id', $hotspot->linked_scene_id) == $scene->id ? 'selected' : '' }}>
                            {{ $scene->title }}
                        </option>
                    @endforeach
                </select>
                @error('linked_scene_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3" id="artifactField">
                <label for="artifact_id" class="form-label">Link to Artifact</label>
                <select class="form-select @error('artifact_id') is-invalid @enderror" id="artifact_id" name="artifact_id">
                    <option value="">Choose Artifact</option>
                    @foreach($artifacts as $artifact)
                        <option value="{{ $artifact->id }}" {{ old('artifact_id', $hotspot->artifact_id) == $artifact->id ? 'selected' : '' }}>
                            {{ $artifact->name }}
                        </option>
                    @endforeach
                </select>
                @error('artifact_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ __('messages.save') }}
                </button>
                <a href="{{ route('admin.hotspots.index') }}" class="btn btn-secondary">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updateTypeFields() {
    const type = document.getElementById('type').value;
    document.getElementById('linkedSceneField').style.display = type === 'scene' ? 'block' : 'none';
    document.getElementById('artifactField').style.display = type === 'artifact' ? 'block' : 'none';
}
updateTypeFields();
</script>
@endsection
