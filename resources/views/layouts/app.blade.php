<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Virtual Tour Budaya Toraja')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-dark: #0f1412;
            --surface-dark: #1a2320;
            --text-light: #e8f0ea;
            --accent-green: #2d9b6f;
            --accent-blue: #3b82f6;
        }

        body {
            background-color: var(--primary-dark);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--surface-dark) !important;
            border-bottom: 2px solid var(--accent-green);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--accent-green) !important;
            font-size: 1.25rem;
        }

        .nav-link {
            color: var(--text-light) !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--accent-green) !important;
        }

        .btn-primary {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
        }

        .btn-primary:hover {
            background-color: #1f6f48;
            border-color: #1f6f48;
        }

        .card {
            background-color: var(--surface-dark);
            border: 1px solid var(--accent-green);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(45, 155, 111, 0.3);
        }

        .footer {
            background-color: var(--surface-dark);
            border-top: 2px solid var(--accent-green);
            margin-top: 4rem;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .hero {
            background: linear-gradient(135deg, var(--accent-green) 0%, var(--accent-blue) 100%);
            padding: 6rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: white;
        }

        .hero p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 1.8rem;
            }
        }
    </style>

    @yield('extra_css')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-globe"></i> Budaya Toraja
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tour.show', 1) }}">{{ __('messages.virtual_tour') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('artifacts.index') }}">{{ __('messages.artifacts') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-language"></i> {{ app()->getLocale() === 'id' ? 'Bahasa Indonesia' : 'English' }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item" href="{{ route('language.switch', 'id') }}">{{ __('messages.indonesian') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">{{ __('messages.english') }}</a></li>
                        </ul>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('messages.admin_panel') }}</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="nav-link btn btn-link" type="submit">{{ __('messages.logout') }}</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>{{ __('messages.welcome') }}</h5>
                    <p>{{ __('messages.welcome_subtitle') }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>{{ __('messages.virtual_tour') }}</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('tour.show', 1) }}" class="text-decoration-none">Lembang Baruppu'</a></li>
                        <li><a href="{{ route('tour.show', 2) }}" class="text-decoration-none">Liang Alang</a></li>
                        <li><a href="{{ route('tour.show', 3) }}" class="text-decoration-none">Makam Ma'nene</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>{{ __('messages.contact') ?? 'Kontak' }}</h5>
                    <p>
                        <i class="fas fa-envelope"></i> info@toraja-tour.id<br>
                        <i class="fas fa-phone"></i> +62-274-XXXX
                    </p>
                </div>
            </div>
            <hr style="border-color: var(--accent-green);">
            <div class="text-center">
                <p class="mb-0">{{ __('messages.footer_text') }}</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('extra_js')
</body>
</html>
