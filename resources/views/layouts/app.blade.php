<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Virtual Tour Budaya Toraja')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --bg-dark: #0f1412;
            --bg-surface: #1a2320;
            --text-light: #e8f0ea;
            --primary-green: #2d9b5e;
            --primary-teal: #1a7f6f;
            --accent-cyan: #4db8a0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background-color: var(--bg-surface) !important;
            border-bottom: 2px solid var(--primary-green);
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-cyan) !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 1.8rem;
        }

        .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--accent-cyan) !important;
        }

        .nav-link.active {
            color: var(--primary-green) !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background-color: var(--primary-green);
            border-radius: 2px;
        }

        /* Language Switcher */
        .language-switcher {
            margin-left: auto;
            display: flex;
            gap: 0.5rem;
        }

        .lang-btn {
            background-color: var(--bg-dark);
            border: 2px solid var(--primary-teal);
            color: var(--text-light);
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .lang-btn:hover {
            background-color: var(--primary-teal);
            color: var(--bg-dark);
        }

        .lang-btn.active {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: var(--bg-dark);
        }

        /* Main Content */
        main {
            min-height: calc(100vh - 124px);
            padding: 2rem 0;
        }

        /* Footer */
        footer {
            background-color: var(--bg-surface);
            border-top: 2px solid var(--primary-green);
            padding: 3rem 0 1rem;
            margin-top: 4rem;
            font-size: 0.95rem;
        }

        .footer-section h6 {
            color: var(--accent-cyan);
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--accent-cyan);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--primary-teal);
            margin-top: 2rem;
            color: #999;
        }

        /* Utilities */
        .text-primary-green {
            color: var(--primary-green);
        }

        .text-accent-cyan {
            color: var(--accent-cyan);
        }

        .btn-primary-green {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: var(--bg-dark);
            font-weight: 600;
        }

        .btn-primary-green:hover {
            background-color: var(--primary-teal);
            border-color: var(--primary-teal);
        }

        .btn-outline-green {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
        }

        .btn-outline-green:hover {
            background-color: var(--primary-green);
            color: var(--bg-dark);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .language-switcher {
                margin-left: 0;
                margin-top: 1rem;
            }

            .nav-link.active::after {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('tour.index') }}">
                <i class="fas fa-gopuram"></i>
                <span>Virtual Tour</span>
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">
                    <!-- Beranda -->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() === 'tour.index' ? 'active' : '' }}"
                            href="{{ route('tour.index') }}">
                            <i class="fas fa-home"></i>
                            Beranda
                        </a>
                    </li>

                    <!-- Koleksi -->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() === 'collection.index' ? 'active' : '' }}"
                            href="{{ route('collection.index') }}">
                            <i class="fas fa-th-large"></i>
                            Koleksi
                        </a>
                    </li>

                    <!-- Admin Link (if authenticated and admin) -->
                    @auth
                        @if (Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog"></i>
                                    Admin
                                </a>
                            </li>
                        @endif
                    @endauth

                    <!-- Language Switcher -->
                    <div class="language-switcher">
                        <a href="{{ route('locale.switch', 'id') }}"
                            class="lang-btn {{ app()->getLocale() === 'id' ? 'active' : '' }}">
                            ID
                        </a>
                        <a href="{{ route('locale.switch', 'en') }}"
                            class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            EN
                        </a>
                    </div>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-fluid">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <div class="row">
                <!-- About -->
                <div class="col-md-3 col-sm-6 footer-section mb-4">
                    <h6>Tentang Virtual Tour</h6>
                    <p>Platform interaktif untuk menjelajahi kekayaan budaya Toraja melalui pengalaman 360°.</p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-3 col-sm-6 footer-section mb-4">
                    <h6>Menu Utama</h6>
                    <ul>
                        <li><a href="{{ route('tour.index') }}">Beranda</a></li>
                        <li><a href="{{ route('collection.index') }}">Koleksi</a></li>
                        @auth
                            @if (Auth::user()->isAdmin())
                                <li><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>

                <!-- Cultural Info -->
                <div class="col-md-3 col-sm-6 footer-section mb-4">
                    <h6>Budaya Toraja</h6>
                    <ul>
                        <li><a href="{{ route('collection.index') }}?category=1">Tradisi & Upacara</a></li>
                        <li><a href="{{ route('collection.index') }}?category=2">Bangunan Tradisional</a></li>
                        <li><a href="{{ route('collection.index') }}?category=3">Seni & Kerajinan</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-md-3 col-sm-6 footer-section mb-4">
                    <h6>Kontak & Sosial</h6>
                    <div class="d-flex gap-3">
                        <a href="#" title="Facebook" class="text-accent-cyan">
                            <i class="fab fa-facebook fs-5"></i>
                        </a>
                        <a href="#" title="Instagram" class="text-accent-cyan">
                            <i class="fab fa-instagram fs-5"></i>
                        </a>
                        <a href="#" title="Twitter" class="text-accent-cyan">
                            <i class="fab fa-twitter fs-5"></i>
                        </a>
                        <a href="#" title="YouTube" class="text-accent-cyan">
                            <i class="fab fa-youtube fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Virtual Tour Budaya Toraja. Semua hak dilindungi.</p>
                <p>Dibuat dengan <i class="fas fa-heart text-primary-green"></i> untuk pelestarian budaya Toraja</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
