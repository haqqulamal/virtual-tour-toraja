@extends('layouts.app')

@section('title', 'Virtual Tour - Jelajahi Budaya Toraja')

@push('styles')
<style>
    .hero {
        background: linear-gradient(135deg, #2d9b5e 0%, #1a7f6f 100%);
        padding: 6rem 2rem;
        text-align: center;
        margin-bottom: 3rem;
        border-radius: 8px;
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero p {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.95);
        line-height: 1.8;
    }

    .scene-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .scene-card {
        background-color: var(--bg-surface);
        border: 2px solid var(--primary-green);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }

    .scene-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(45, 155, 94, 0.3);
        border-color: var(--accent-cyan);
    }

    .scene-thumbnail {
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

    .scene-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .scene-thumbnail::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.3);
        transition: opacity 0.3s ease;
        opacity: 0;
    }

    .scene-card:hover .scene-thumbnail::before {
        opacity: 1;
    }

    .scene-play-icon {
        position: absolute;
        width: 60px;
        height: 60px;
        background-color: var(--accent-cyan);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 10;
    }

    .scene-play-icon i {
        color: var(--bg-dark);
        font-size: 1.5rem;
        margin-left: 4px;
    }

    .scene-card:hover .scene-play-icon {
        opacity: 1;
    }

    .scene-info {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .scene-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--accent-cyan);
        margin-bottom: 0.75rem;
    }

    .scene-description {
        font-size: 0.95rem;
        color: #bbb;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .scene-button {
        align-self: flex-start;
        padding: 0.5rem 1.2rem;
        background-color: var(--primary-green);
        color: var(--bg-dark);
        border: none;
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .scene-button:hover {
        background-color: var(--accent-cyan);
        transform: translateX(5px);
    }

    /* Section Titles */
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent-cyan);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--primary-green);
    }

    /* Featured Section */
    .featured-section {
        background: linear-gradient(135deg, rgba(45, 155, 94, 0.1), rgba(26, 127, 111, 0.1));
        padding: 3rem;
        border-radius: 8px;
        border-left: 5px solid var(--primary-green);
        margin-bottom: 4rem;
    }

    .featured-label {
        display: inline-block;
        background-color: var(--primary-green);
        color: var(--bg-dark);
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }

    /* Info Section */
    .info-section {
        background-color: var(--bg-surface);
        padding: 2.5rem;
        border-radius: 8px;
        border: 1px solid var(--primary-teal);
        margin-bottom: 3rem;
    }

    .info-section h3 {
        color: var(--accent-cyan);
        font-size: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .info-section p {
        color: #ccc;
        line-height: 1.8;
        margin-bottom: 1rem;
    }

    .info-section ul {
        list-style: none;
        padding-left: 0;
    }

    .info-section ul li {
        padding: 0.5rem 0;
        color: #ccc;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-section ul li::before {
        content: '';
        width: 6px;
        height: 6px;
        background-color: var(--primary-green);
        border-radius: 50%;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-cyan) 100%);
        padding: 3rem;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 4rem;
    }

    .cta-section h2 {
        color: white;
        font-size: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .cta-section p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    .cta-button {
        display: inline-block;
        padding: 0.75rem 2rem;
        background-color: var(--bg-dark);
        color: var(--accent-cyan);
        border: none;
        border-radius: 4px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cta-button:hover {
        background-color: white;
        color: var(--primary-green);
        transform: scale(1.05);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 3rem 1.5rem;
        }

        .hero h1 {
            font-size: 2rem;
        }

        .hero p {
            font-size: 1rem;
        }

        .scene-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .featured-section,
        .info-section,
        .cta-section {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

<div class="container-lg">
    <!-- Hero Section -->
    <div class="hero">
        <h1>🏔️ Virtual Tour Budaya Toraja</h1>
        <p>Jelajahi keindahan alam dan kekayaan budaya Toraja melalui pengalaman panorama 360° yang menakjubkan</p>
    </div>

    <!-- Featured Scenes -->
    @if($scenes->count() > 0)
    <div class="featured-section">
        <span class="featured-label">⭐ Recommended</span>
        <h2 class="section-title">
            <i class="fas fa-map-pin"></i>
            Jelajahi Lokasi Pilihan
        </h2>
        
        <div class="scene-grid">
            @foreach($scenes as $scene)
            <div class="scene-card">
                <div class="scene-thumbnail">
                    @if($scene->thumbnail)
                    <img src="{{ asset('storage/' . $scene->thumbnail) }}" alt="{{ $scene->title }}">
                    @else
                    <i class="fas fa-image"></i>
                    @endif
                    <div class="scene-play-icon">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                
                <div class="scene-info">
                    <h3 class="scene-title">{{ $scene->title }}</h3>
                    <p class="scene-description">{{ Str::limit($scene->description, 80) }}</p>
                    <a href="{{ route('tour.show', $scene->id) }}" class="scene-button">
                        <i class="fas fa-play"></i>
                        Mulai Tur
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Info Sections -->
    <div class="row g-3">
        <!-- About Toraja -->
        <div class="col-lg-6">
            <div class="info-section">
                <h3><i class="fas fa-book me-2"></i>Tentang Toraja</h3>
                <p>
                    Toraja adalah sebuah wilayah di Sulawesi Selatan yang terkenal dengan budayanya yang unik dan 
                    tradisi yang kaya. Masyarakat Toraja memiliki warisan budaya yang telah bertahan selama berabad-abad 
                    dengan keunikan arsitektur, seni, dan upacara adat yang memukau.
                </p>
                <ul>
                    <li>Arsitektur rumah tradisional yang megah</li>
                    <li>Upacara adat yang kaya makna spiritual</li>
                    <li>Keindahan alam yang luar biasa</li>
                    <li>Warisan budaya yang diakui UNESCO</li>
                </ul>
            </div>
        </div>

        <!-- About Ma'nene Ritual -->
        <div class="col-lg-6">
            <div class="info-section">
                <h3><i class="fas fa-users me-2"></i>Ritual Ma'nene</h3>
                <p>
                    Ma'nene adalah sebuah ritual unik masyarakat Toraja yang melibatkan pemindahan dan pembersihan 
                    mayat leluhur mereka. Upacara ini biasa dilakukan setiap beberapa tahun sekali sebagai bentuk 
                    rasa hormat dan cinta kepada para leluhur mereka.
                </p>
                <ul>
                    <li>Dirayakan setiap 3-5 tahun</li>
                    <li>Melibatkan seluruh keluarga besar</li>
                    <li>Menggunakan busana tradisional yang indah</li>
                    <li>Diiringi dengan musik dan tarian adat</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Siap Menjelajahi Keindahan Toraja?</h2>
        <p>Masuki dunia panorama 360° dan rasakan pengalaman budaya Toraja seperti tidak pernah ada sebelumnya</p>
        @if($scenes->count() > 0)
        <a href="{{ route('tour.show', $scenes->first()->id) }}" class="cta-button">
            <i class="fas fa-play me-2"></i>Mulai Tur Sekarang
        </a>
        @endif
    </div>

    <!-- All Scenes Section -->
    @if($scenes->count() > 3)
    <div>
        <h2 class="section-title" style="margin-top: 3rem;">
            <i class="fas fa-globe"></i>
            Semua Lokasi
        </h2>
        
        <div class="scene-grid">
            @foreach($scenes as $scene)
            <div class="scene-card">
                <div class="scene-thumbnail">
                    @if($scene->thumbnail)
                    <img src="{{ asset('storage/' . $scene->thumbnail) }}" alt="{{ $scene->title }}">
                    @else
                    <i class="fas fa-image"></i>
                    @endif
                    <div class="scene-play-icon">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                
                <div class="scene-info">
                    <h3 class="scene-title">{{ $scene->title }}</h3>
                    <p class="scene-description">{{ Str::limit($scene->description, 80) }}</p>
                    <a href="{{ route('tour.show', $scene->id) }}" class="scene-button">
                        <i class="fas fa-play"></i>
                        Mulai Tur
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection
