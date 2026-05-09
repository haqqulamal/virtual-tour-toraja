@extends('layouts.app')

@section('title', $artifact->name)

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('artifacts.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_catalog') ?? 'Kembali ke Katalog' }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            @if($artifact->image)
                <img src="{{ asset('uploads/artifacts/' . $artifact->image) }}" 
                     class="img-fluid rounded mb-3" alt="{{ $artifact->name }}" style="max-height: 500px; object-fit: cover; width: 100%;">
            @else
                <div class="bg-secondary d-flex align-items-center justify-content-center rounded mb-3" style="height: 400px;">
                    <i class="fas fa-image" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
            @endif
            
            <div class="card bg-dark border-success">
                <div class="card-body">
                    <h6 class="card-title">{{ __('messages.category') }}</h6>
                    <a href="{{ route('artifacts.index', ['category' => $artifact->category->id]) }}" class="btn btn-sm btn-outline-success">
                        {{ $artifact->category->name }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <h1 class="mb-2">{{ $artifact->name }}</h1>
            <p class="text-muted mb-4">
                <i class="fas fa-folder"></i> {{ $artifact->category->name }}
            </p>

            <div class="card bg-dark border-success mb-4">
                <div class="card-header border-success">
                    <h5 class="mb-0">{{ __('messages.description') }}</h5>
                </div>
                <div class="card-body">
                    {{ $artifact->description }}
                </div>
            </div>

            @if($artifact->cultural_significance)
                <div class="card bg-dark border-success mb-4">
                    <div class="card-header border-success">
                        <h5 class="mb-0">{{ __('messages.cultural_significance') }}</h5>
                    </div>
                    <div class="card-body">
                        {{ $artifact->cultural_significance }}
                    </div>
                </div>
            @endif

            @if($artifact->keywords)
                <div class="mb-4">
                    <h6>{{ __('messages.keywords') }}</h6>
                    @foreach($artifact->getKeywordsArray() as $keyword)
                        <span class="badge bg-success me-2">{{ $keyword }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if($relatedArtifacts->isNotEmpty())
        <div class="row mt-5">
            <div class="col-12">
                <h3>{{ __('messages.related_artifacts') }}</h3>
            </div>
            @foreach($relatedArtifacts as $related)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        @if($related->image)
                            <img src="{{ asset('uploads/artifacts/' . $related->image) }}" 
                                 class="card-img-top" alt="{{ $related->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $related->name }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($related->description, 60) }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-top border-secondary">
                            <a href="{{ route('artifacts.show', $related->id) }}" class="btn btn-primary btn-sm w-100">
                                {{ __('messages.view') ?? 'Lihat' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
