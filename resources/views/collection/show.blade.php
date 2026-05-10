@extends('layouts.app')

@section('title', $artifact->getLocalizedTitle() . ' - Koleksi Budaya Toraja')

@section('content')

@push('styles')
<style>
    /* Breadcrumb */
    .breadcrumb-section {
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--primary-teal);
        margin-bottom: 2rem;
    }

    .breadcrumb {
        display: flex;
        gap: 1rem;
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 0.95rem;
    }

    .breadcrumb li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .breadcrumb a {
        color: var(--accent-cyan);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb a:hover {
        color: var(--primary-green);
    }

    .breadcrumb .active {
        color: var(--text-light);
    }

    .breadcrumb li:not(:last-child)::after {
        content: '/';
        color: #666;
    }

    /* Main Content */
    .artifact-detail-main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
    }

    /* Image Section */
    .artifact-image-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .artifact-main-image {
        width: 100%;
        height: 500px;
        background: linear-gradient(135deg, #2d9b5e 0%, #1a7f6f 100%);
        border: 2px solid var(--primary-green);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
        overflow: hidden;
    }

    .artifact-main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-info {
        text-align: center;
        color: #999;
        font-size: 0.9rem;
    }

    /* Detail Section */
    .artifact-info-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Category & Title */
    .artifact-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .artifact-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: rgba(45, 155, 94, 0.2);
        color: var(--primary-green);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 700;
        font-size: 0.9rem;
        width: fit-content;
        text-transform: uppercase;
    }

    .artifact-detail-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent-cyan);
        line-height: 1.3;
    }

    /* Info Cards */
    .info-card {
        background-color: var(--bg-surface);
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid var(--primary-green);
    }

    .info-card h4 {
        color: var(--accent-cyan);
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card h4 i {
        color: var(--primary-green);
        font-size: 1.1rem;
    }

    .info-card p {
        color: #ddd;
        margin: 0;
        line-height: 1.6;
    }

    /* Description */
    .description-section {
        background-color: var(--bg-surface);
        padding: 2rem;
        border-radius: 8px;
        border: 1px solid var(--primary-teal);
    }

    .description-section h3 {
        color: var(--accent-cyan);
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .description-section h3 i {
        color: var(--primary-green);
    }

    .description-text {
        color: #ccc;
        line-height: 1.8;
        font-size: 1rem;
    }

    /* Back Button */
    .back-button-section {
        margin-bottom: 2rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: var(--bg-surface);
        color: var(--accent-cyan);
        border: 2px solid var(--primary-teal);
        border-radius: 4px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background-color: var(--primary-teal);
        color: var(--bg-dark);
    }

    /* Related Artifacts Section */
    .related-section {
        margin-top: 4rem;
        padding-top: 3rem;
        border-top: 2px solid var(--primary-green);
    }

    .related-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--accent-cyan);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .related-title i {
        color: var(--primary-green);
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .related-card {
        background-color: var(--bg-surface);
        border: 2px solid var(--primary-green);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(45, 155, 94, 0.3);
        border-color: var(--accent-cyan);
    }

    .related-image {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, var(--primary-teal), var(--accent-cyan));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-content {
        padding: 1rem;
    }

    .related-content h4 {
        color: var(--accent-cyan);
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .related-content p {
        color: #999;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .related-link {
        display: inline-block;
        padding: 0.5rem 1rem;
        background-color: var(--primary-green);
        color: var(--bg-dark);
        text-decoration: none;
        border-radius: 4px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .related-link:hover {
        background-color: var(--accent-cyan);
    }

    /* Empty Related */
    .no-related {
        text-align: center;
        padding: 2rem;
        color: #999;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .artifact-detail-main {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .artifact-main-image {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .artifact-main-image {
            height: 300px;
        }

        .artifact-detail-title {
            font-size: 1.5rem;
        }

        .related-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .description-section {
            padding: 1.5rem;
        }
    }
</style>
@endpush

<!-- Breadcrumb -->
<div class="breadcrumb-section">
    <div class="container-lg">
        <ul class="breadcrumb">
            <li><a href="{{ route('tour.index') }}"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="{{ route('collection.index') }}"><i class="fas fa-th-large"></i> Koleksi</a></li>
            <li class="active">{{ $artifact->getLocalizedTitle() }}</li>
        </ul>
    </div>
</div>

<!-- Back Button -->
<div class="container-lg back-button-section">
    <a href="{{ route('collection.index', ['category' => $artifact->category->slug]) }}" class="btn-back">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Koleksi
    </a>
</div>

<div class="container-lg">
    <!-- Main Detail Grid -->
    <div class="artifact-detail-main">
        <!-- Left: Image -->
        <div class="artifact-image-section">
            <div class="artifact-main-image">
                @if($artifact->image_path)
                <img src="{{ asset('storage/' . $artifact->image_path) }}" 
                     alt="{{ $artifact->getLocalizedTitle() }}">
                @else
                <i class="fas fa-image"></i>
                @endif
            </div>
            <div class="image-info">
                <p><i class="fas fa-info-circle me-1"></i>Klik untuk memperbesar gambar</p>
            </div>
        </div>

        <!-- Right: Information -->
        <div class="artifact-info-section">
            <!-- Header -->
            <div class="artifact-header">
                <!-- Category -->
                <div class="artifact-category-badge">
                    <i class="fas fa-folder"></i>
                    {{ $artifact->category->getLocalizedName() }}
                </div>

                <!-- Title -->
                <h1 class="artifact-detail-title">
                    {{ $artifact->getLocalizedTitle() }}
                </h1>
            </div>

            <!-- Info Cards -->
            @if($artifact->material)
            <div class="info-card">
                <h4>
                    <i class="fas fa-cube"></i>
                    Material
                </h4>
                <p>{{ $artifact->material }}</p>
            </div>
            @endif

            <!-- Category Full Info -->
            <div class="info-card">
                <h4>
                    <i class="fas fa-tag"></i>
                    Kategori Lengkap
                </h4>
                <p>
                    <a href="{{ route('collection.index', ['category' => $artifact->category->slug]) }}" 
                       style="color: var(--accent-cyan); text-decoration: none; transition: color 0.3s;">
                        {{ $artifact->category->getLocalizedName() }}
                    </a>
                </p>
            </div>

            <!-- Featured Badge -->
            @if($artifact->is_featured)
            <div class="info-card" style="border-left-color: #FFD700;">
                <h4 style="color: #FFD700;">
                    <i class="fas fa-star"></i>
                    Artefak Unggulan
                </h4>
                <p>Ini adalah salah satu artefak pilihan dalam koleksi kami.</p>
            </div>
            @endif

            <!-- Created Date -->
            <div class="info-card">
                <h4>
                    <i class="fas fa-calendar"></i>
                    Ditambahkan
                </h4>
                <p>{{ $artifact->created_at->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Description Section -->
    <div class="description-section">
        <h3>
            <i class="fas fa-book"></i>
            Deskripsi Lengkap
        </h3>
        <div class="description-text">
            {!! nl2br(e($artifact->getLocalizedDescription())) !!}
        </div>
    </div>

    <!-- Related Artifacts Section -->
    <div class="related-section">
        <h2 class="related-title">
            <i class="fas fa-link"></i>
            Artefak Terkait
        </h2>

        @if($relatedArtifacts->count() > 0)
        <div class="related-grid">
            @foreach($relatedArtifacts as $related)
            <div class="related-card">
                <!-- Image -->
                <div class="related-image">
                    @if($related->image_path)
                    <img src="{{ asset('storage/' . $related->image_path) }}" 
                         alt="{{ $related->getLocalizedTitle() }}"
                         loading="lazy">
                    @else
                    <i class="fas fa-image"></i>
                    @endif
                </div>

                <!-- Content -->
                <div class="related-content">
                    <h4>{{ $related->getLocalizedTitle() }}</h4>
                    <p>{{ Str::limit($related->getLocalizedDescription(), 60) }}</p>
                    <a href="{{ route('collection.show', $related->id) }}" class="related-link">
                        Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="no-related">
            <p><i class="fas fa-link"></i> Tidak ada artefak terkait dalam kategori ini.</p>
        </div>
        @endif
    </div>

    <!-- Footer Navigation -->
    <div style="margin-top: 4rem; padding-top: 2rem; border-top: 2px solid var(--primary-green); text-align: center;">
        <a href="{{ route('collection.index') }}" class="btn-back" style="margin-right: 1rem;">
            <i class="fas fa-arrow-left"></i>
            Lihat Semua Koleksi
        </a>
        <a href="{{ route('tour.index') }}" class="btn-back" style="background-color: var(--primary-green); color: var(--bg-dark); border-color: var(--primary-green);">
            <i class="fas fa-play"></i>
            Kembali ke Virtual Tour
        </a>
    </div>
</div>

@endsection
