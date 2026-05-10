@extends('layouts.app')

@section('title', 'Koleksi Budaya Toraja')

@section('content')

@push('styles')
<style>
    /* Page Header */
    .collection-header {
        background: linear-gradient(135deg, #2d9b5e 0%, #1a7f6f 100%);
        padding: 3rem 0;
        margin-bottom: 3rem;
        border-bottom: 3px solid var(--accent-cyan);
    }

    .collection-header h1 {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .collection-header p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
    }

    /* Filters & Search Section */
    .collection-controls {
        background-color: var(--bg-surface);
        padding: 2rem;
        border-radius: 8px;
        border: 1px solid var(--primary-teal);
        margin-bottom: 3rem;
    }

    .controls-title {
        color: var(--accent-cyan);
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Category Filter Pills */
    .category-filters {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .category-pill {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        background-color: var(--bg-dark);
        border: 2px solid var(--primary-teal);
        color: var(--text-light);
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .category-pill:hover {
        border-color: var(--accent-cyan);
        color: var(--accent-cyan);
    }

    .category-pill.active {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: var(--bg-dark);
    }

    .category-pill i {
        margin-right: 0.5rem;
    }

    /* Search Bar */
    .search-section {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-input {
        flex: 1;
        max-width: 400px;
    }

    .search-input input {
        width: 100%;
        padding: 0.75rem 1rem;
        background-color: var(--bg-dark);
        border: 2px solid var(--primary-teal);
        border-radius: 4px;
        color: var(--text-light);
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input input::placeholder {
        color: #666;
    }

    .search-input input:focus {
        outline: none;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(45, 155, 94, 0.1);
    }

    .btn-search {
        padding: 0.75rem 1.5rem;
        background-color: var(--primary-green);
        color: var(--bg-dark);
        border: none;
        border-radius: 4px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-search:hover {
        background-color: var(--accent-cyan);
        transform: translateX(3px);
    }

    .btn-reset {
        padding: 0.75rem 1rem;
        background-color: transparent;
        color: var(--text-light);
        border: 2px solid var(--primary-teal);
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background-color: var(--primary-teal);
        color: var(--bg-dark);
    }

    /* Artifact Grid */
    .artifacts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .artifact-card {
        background-color: var(--bg-surface);
        border: 2px solid var(--primary-green);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .artifact-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(45, 155, 94, 0.3);
        border-color: var(--accent-cyan);
    }

    .artifact-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--primary-teal), var(--accent-cyan));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        overflow: hidden;
        position: relative;
    }

    .artifact-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .artifact-image::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .artifact-card:hover .artifact-image::before {
        opacity: 1;
    }

    .artifact-content {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .artifact-category {
        display: inline-block;
        background-color: rgba(45, 155, 94, 0.2);
        color: var(--primary-green);
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        width: fit-content;
        text-transform: uppercase;
    }

    .artifact-title {
        color: var(--accent-cyan);
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        min-height: 2.8rem;
    }

    .artifact-description {
        color: #aaa;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .artifact-material {
        color: #888;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-detail {
        align-self: flex-start;
        padding: 0.6rem 1.2rem;
        background-color: var(--primary-green);
        color: var(--bg-dark);
        border: none;
        border-radius: 4px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-detail:hover {
        background-color: var(--accent-cyan);
        transform: translateX(3px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background-color: rgba(45, 155, 94, 0.05);
        border-radius: 8px;
        border: 2px dashed var(--primary-green);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--primary-green);
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: var(--accent-cyan);
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #999;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }

    .empty-state-btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary-green);
        color: var(--bg-dark);
        text-decoration: none;
        border-radius: 4px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .empty-state-btn:hover {
        background-color: var(--accent-cyan);
    }

    /* Pagination */
    .pagination-section {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
    }

    .pagination a,
    .pagination span {
        padding: 0.6rem 0.9rem;
        background-color: var(--bg-surface);
        border: 2px solid var(--primary-teal);
        color: var(--text-light);
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .pagination a:hover {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: var(--bg-dark);
    }

    .pagination .active span {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: var(--bg-dark);
    }

    .pagination .disabled span {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Results Info */
    .results-info {
        color: #999;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .collection-header {
            padding: 2rem 0;
        }

        .collection-header h1 {
            font-size: 1.8rem;
        }

        .collection-header p {
            font-size: 1rem;
        }

        .collection-controls {
            padding: 1.5rem;
        }

        .search-section {
            flex-direction: column;
        }

        .search-input {
            max-width: 100%;
        }

        .search-input input,
        .btn-search,
        .btn-reset {
            width: 100%;
        }

        .category-filters {
            gap: 0.5rem;
        }

        .category-pill {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .artifacts-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .artifact-image {
            height: 180px;
        }

        .pagination {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

<!-- Page Header -->
<div class="collection-header">
    <div class="container-lg">
        <h1><i class="fas fa-th-large me-2"></i>Koleksi Budaya Toraja</h1>
        <p>Jelajahi koleksi lengkap artefak budaya dan tradisional dari Toraja</p>
    </div>
</div>

<div class="container-lg">
    <!-- Filters & Search -->
    <div class="collection-controls">
        <!-- Category Filter Heading -->
        <div class="controls-title">
            <i class="fas fa-filter"></i>
            Kategori
        </div>

        <!-- Category Filter Pills -->
        <div class="category-filters">
            <!-- "Semua" Button -->
            <a href="{{ route('collection.index') }}" 
               class="category-pill {{ !request('category') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                Semua Kategori
            </a>

            <!-- Category Buttons -->
            @foreach($categories as $category)
            <a href="{{ route('collection.index', ['category' => $category->slug, 'search' => request('search')]) }}" 
               class="category-pill {{ request('category') === $category->slug ? 'active' : '' }}">
                <i class="fas fa-tag"></i>
                {{ $category->getLocalizedName() }}
            </a>
            @endforeach
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('collection.index') }}" class="search-section">
            <div class="search-input">
                <input type="text" 
                       name="search" 
                       placeholder="Cari artefak budaya..." 
                       value="{{ request('search') }}"
                       aria-label="Search artifacts">
            </div>

            @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i>
                Cari
            </button>

            @if(request('search') || request('category'))
            <a href="{{ route('collection.index') }}" class="btn-reset">
                <i class="fas fa-times me-1"></i>Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Results Info -->
    @if($artifacts->count() > 0)
    <div class="results-info">
        <i class="fas fa-info-circle"></i>
        <span>Menampilkan <strong>{{ $artifacts->count() }}</strong> dari <strong>{{ $artifacts->total() }}</strong> artefak</span>
    </div>
    @endif

    <!-- Artifacts Grid -->
    @if($artifacts->count() > 0)
    <div class="artifacts-grid">
        @foreach($artifacts as $artifact)
        <div class="artifact-card">
            <!-- Thumbnail -->
            <div class="artifact-image">
                @if($artifact->image_path)
                <img src="{{ asset('storage/' . $artifact->image_path) }}" 
                     alt="{{ $artifact->getLocalizedTitle() }}"
                     loading="lazy">
                @else
                <i class="fas fa-image"></i>
                @endif
            </div>

            <!-- Content -->
            <div class="artifact-content">
                <!-- Category Badge -->
                <div class="artifact-category">
                    <i class="fas fa-folder me-1"></i>{{ $artifact->category->getLocalizedName() }}
                </div>

                <!-- Title -->
                <h3 class="artifact-title">{{ $artifact->getLocalizedTitle() }}</h3>

                <!-- Description -->
                <p class="artifact-description">
                    {{ Str::limit($artifact->getLocalizedDescription(), 100) }}
                </p>

                <!-- Material -->
                @if($artifact->material)
                <div class="artifact-material">
                    <i class="fas fa-cube" style="color: var(--primary-green);"></i>
                    <span>{{ $artifact->material }}</span>
                </div>
                @endif

                <!-- Detail Button -->
                <a href="{{ route('collection.show', $artifact->id) }}" class="btn-detail">
                    <i class="fas fa-arrow-right"></i>
                    Selengkapnya
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($artifacts->hasPages())
    <div class="pagination-section">
        {{ $artifacts->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-search"></i>
        </div>
        <h3>Tidak Ada Hasil</h3>
        <p>
            @if(request('search'))
                Maaf, tidak ada artefak yang cocok dengan pencarian "{{ request('search') }}".
            @elseif(request('category'))
                Maaf, tidak ada artefak di kategori ini.
            @else
                Tidak ada artefak yang tersedia saat ini.
            @endif
        </p>
        <a href="{{ route('collection.index') }}" class="empty-state-btn">
            <i class="fas fa-redo me-1"></i>Kembali ke Koleksi
        </a>
    </div>
    @endif
</div>

@endsection
