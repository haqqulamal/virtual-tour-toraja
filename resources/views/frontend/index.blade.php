@extends('layouts.app')

@section('title', __('messages.welcome'))

@section('content')
<div class="hero">
    <div class="container">
        <h1>{{ __('messages.welcome') }}</h1>
        <p>{{ __('messages.welcome_subtitle') }}</p>
        <a href="{{ route('tour.show', 1) }}" class="btn btn-light btn-lg mt-3">
            <i class="fas fa-play-circle"></i> {{ __('messages.explore_now') }}
        </a>
    </div>
</div>

<div class="container my-5">
    <!-- Virtual Tour Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-camera"></i> {{ __('messages.virtual_tour') }}
            </h2>
        </div>
        @foreach($scenes as $scene)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('uploads/scenes/' . $scene->panorama_image) }}" class="card-img-top" alt="{{ $scene->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $scene->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($scene->description, 100) }}</p>
                        <p class="card-text small">
                            <i class="fas fa-map-marker-alt"></i> {{ $scene->location }}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-top border-secondary">
                        <a href="{{ route('tour.show', $scene->id) }}" class="btn btn-primary btn-sm w-100">
                            {{ __('messages.explore_location') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Artifacts Section -->
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-artifact"></i> {{ __('messages.artifact_catalog') }}
            </h2>
        </div>
        <div class="col-12">
            <a href="{{ route('artifacts.index') }}" class="btn btn-primary">
                {{ __('messages.view_all') ?? 'Lihat Semua Artefak' }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('extra_css')
<style>
    .hero {
        background: linear-gradient(135deg, #2d9b6f 0%, #3b82f6 100%);
    }
</style>
@endsection
