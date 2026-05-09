@extends('layouts.app')

@section('title', __('messages.artifact_catalog'))

@section('content')
<div class="container my-5">
    <h1 class="mb-4">{{ __('messages.artifact_catalog') }}</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <!-- Filter by Category -->
            <div class="card bg-dark border-success">
                <div class="card-header border-success">
                    <h5 class="mb-0">{{ __('messages.filter_by_category') }}</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('artifacts.index') }}" class="d-block mb-2 {{ !request('category') ? 'text-success fw-bold' : '' }}">
                        {{ __('messages.all_categories') }}
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('artifacts.index', ['category' => $category->id]) }}" 
                           class="d-block mb-2 {{ request('category') == $category->id ? 'text-success fw-bold' : '' }}">
                            {{ $category->name }}
                            <span class="badge bg-success">{{ $category->artifacts_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search -->
            <div class="card bg-dark border-success mt-3">
                <div class="card-header border-success">
                    <h5 class="mb-0">{{ __('messages.search_artifacts') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('artifacts.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') ?? 'Cari...' }}"
                                   value="{{ request('search') }}">
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if($artifacts->isEmpty())
                <div class="alert alert-info text-center">
                    {{ __('messages.no_artifacts_found') }}
                </div>
            @else
                <div class="row">
                    @foreach($artifacts as $artifact)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                @if($artifact->image)
                                    <img src="{{ asset('uploads/artifacts/' . $artifact->image) }}" 
                                         class="card-img-top" alt="{{ $artifact->name }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                                        <i class="fas fa-image" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $artifact->name }}</h5>
                                    <p class="card-text text-muted small">
                                        {{ $artifact->category->name }}
                                    </p>
                                    <p class="card-text" style="font-size: 0.9rem;">
                                        {{ Str::limit($artifact->description, 80) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-top border-secondary">
                                    <a href="{{ route('artifacts.show', $artifact->id) }}" class="btn btn-primary btn-sm w-100">
                                        {{ __('messages.learn_more') ?? 'Pelajari Selengkapnya' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-12">
                        {{ $artifacts->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
