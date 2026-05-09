@extends('layouts.admin')

@section('title', __('messages.admin_dashboard'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1>{{ __('messages.admin_dashboard') }}</h1>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $scenesCount }}</h3>
                <p class="text-muted">{{ __('messages.total_scenes') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ $hotspotsCount }}</h3>
                <p class="text-muted">{{ __('messages.manage_hotspots') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning">{{ $artifactsCount }}</h3>
                <p class="text-muted">{{ __('messages.manage_artifacts') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $categoriesCount }}</h3>
                <p class="text-muted">{{ __('messages.manage_categories') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Scenes -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-success">
                <h5 class="mb-0">Recent Scenes</h5>
            </div>
            <div class="card-body">
                @if($recentScenes->isEmpty())
                    <p class="text-muted">No scenes yet</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($recentScenes as $scene)
                            <li class="list-group-item bg-transparent border-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $scene->title }}</h6>
                                        <small class="text-muted">{{ $scene->location }}</small>
                                    </div>
                                    <a href="{{ route('admin.scenes.edit', $scene->id) }}" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <a href="{{ route('admin.scenes.index') }}" class="btn btn-primary btn-sm mt-3 w-100">
                    View All Scenes
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Artifacts -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-success">
                <h5 class="mb-0">Recent Artifacts</h5>
            </div>
            <div class="card-body">
                @if($recentArtifacts->isEmpty())
                    <p class="text-muted">No artifacts yet</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($recentArtifacts as $artifact)
                            <li class="list-group-item bg-transparent border-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $artifact->name }}</h6>
                                        <small class="text-muted">{{ $artifact->category->name }}</small>
                                    </div>
                                    <a href="{{ route('admin.artifacts.edit', $artifact->id) }}" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <a href="{{ route('admin.artifacts.index') }}" class="btn btn-primary btn-sm mt-3 w-100">
                    View All Artifacts
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-success">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.scenes.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus"></i> Add Scene
                </a>
                <a href="{{ route('admin.hotspots.create') }}" class="btn btn-info me-2">
                    <i class="fas fa-plus"></i> Add Hotspot
                </a>
                <a href="{{ route('admin.artifacts.create') }}" class="btn btn-warning me-2">
                    <i class="fas fa-plus"></i> Add Artifact
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
