@extends('layouts.app')

@section('title', $scene->title . ' - Virtual Tour')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
<style>
    #panorama {
        width: 100%;
        height: 600px;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .scene-info {
        background-color: var(--surface-dark);
        padding: 2rem;
        border-radius: 8px;
        border-left: 4px solid var(--accent-green);
        margin-bottom: 2rem;
    }

    .hotspot-info {
        background-color: rgba(45, 155, 111, 0.1);
        border-left: 3px solid var(--accent-green);
        padding: 1rem;
        margin-top: 1rem;
    }

    .scene-navigation {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--accent-green);
    }

    .scene-nav-link {
        transition: all 0.3s ease;
    }

    .scene-nav-link:hover {
        transform: translateX(10px);
    }

    @media (max-width: 768px) {
        #panorama {
            height: 400px;
        }
    }
</style>
@endpush

@section('content')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Panorama Viewer -->
            <div id="panorama"></div>

            <!-- Scene Information -->
            <div class="scene-info">
                <h2>{{ $scene->title }}</h2>
                <p class="text-muted">
                    <i class="fas fa-map-marker-alt"></i> {{ $scene->location }}
                </p>
                <p class="mt-3">{{ $scene->description }}</p>
                @if($scene->latitude && $scene->longitude)
                    <p class="small text-muted">
                        📍 Lat: {{ $scene->latitude }}, Long: {{ $scene->longitude }}
                    </p>
                @endif
            </div>

            <!-- Hotspots Information -->
            @if($scene->hotspots->isNotEmpty())
                <div class="mt-4">
                    <h4>{{ __('messages.points_of_interest') ?? 'Titik Menarik' }}</h4>
                    @foreach($scene->hotspots as $hotspot)
                        <div class="hotspot-info">
                            <h6>{{ $hotspot->title }}</h6>
                            <p class="mb-2">{{ $hotspot->description }}</p>
                            <span class="badge bg-{{ $hotspot->type === 'info' ? 'info' : 'success' }}">
                                {{ $hotspot->type === 'info' ? __('messages.info_point') : __('messages.navigation') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Scene Navigation -->
            @if($scenes->count() > 1)
                <div class="scene-navigation">
                    <h5>{{ __('messages.other_locations') ?? 'Lokasi Lainnya' }}</h5>
                    <div class="list-group">
                        @foreach($scenes as $otherScene)
                            <a href="{{ route('tour.show', $otherScene->id) }}" 
                               class="list-group-item list-group-item-action scene-nav-link {{ $otherScene->id === $scene->id ? 'active' : '' }}"
                               style="background-color: {{ $otherScene->id === $scene->id ? 'var(--accent-green)' : 'transparent' }}; border-color: var(--accent-green);">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $otherScene->title }}</h6>
                                </div>
                                <p class="mb-1 small">{{ Str::limit($otherScene->location, 40) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Related Artifacts -->
            @php
                $relatedArtifacts = $scene->hotspots()
                    ->whereNotNull('artifact_id')
                    ->with('artifact')
                    ->get()
                    ->pluck('artifact')
                    ->unique('id');
            @endphp
            
            @if($relatedArtifacts->isNotEmpty())
                <div class="mt-4">
                    <h5>{{ __('messages.related_artifacts') }}</h5>
                    <div class="list-group">
                        @foreach($relatedArtifacts as $artifact)
                            <a href="{{ route('artifacts.show', $artifact->id) }}" 
                               class="list-group-item list-group-item-action"
                               style="border-color: var(--accent-green);">
                                {{ $artifact->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Navigation between scenes -->
<div class="container mb-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                @php
                    $prevScene = $scenes->where('order', '<', $scene->order)->last();
                    $nextScene = $scenes->where('order', '>', $scene->order)->first();
                @endphp

                @if($prevScene)
                    <a href="{{ route('tour.show', $prevScene->id) }}" class="btn btn-outline-light">
                        <i class="fas fa-chevron-left"></i> {{ $prevScene->title }}
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextScene)
                    <a href="{{ route('tour.show', $nextScene->id) }}" class="btn btn-outline-light">
                        {{ $nextScene->title }} <i class="fas fa-chevron-right"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
<script>
    // Initialize Pannellum viewer
    pannellum.viewer('panorama', {
        type: 'equirectangular',
        panorama: '{{ asset("uploads/scenes/" . $scene->panorama_image) }}',
        autoLoad: true,
        showControls: true,
        mouseZoom: true,
        compass: true,
    });
</script>
@endsection
