<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Virtual Tour Budaya Toraja')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
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
        }

        .sidebar {
            background-color: var(--surface-dark);
            border-right: 2px solid var(--accent-green);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
            padding-top: 2rem;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .sidebar .nav-link {
            color: var(--text-light);
            border-left: 3px solid transparent;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(45, 155, 111, 0.1);
            border-left-color: var(--accent-green);
            color: var(--accent-green);
        }

        .card {
            background-color: var(--surface-dark);
            border: 1px solid var(--accent-green);
        }

        .btn-primary {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
        }

        .btn-primary:hover {
            background-color: #1f6f48;
            border-color: #1f6f48;
        }

        .table {
            color: var(--text-light);
        }

        .table thead {
            border-bottom: 2px solid var(--accent-green);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                position: relative;
                border-right: none;
                border-bottom: 2px solid var(--accent-green);
                padding-top: 1rem;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @yield('extra_css')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4 class="px-3 mb-4 text-success">
                <i class="fas fa-lock"></i> Admin Panel
            </h4>
            <nav class="nav flex-column">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> {{ __('messages.admin_dashboard') }}
                </a>
                <a href="{{ route('admin.scenes.index') }}" class="nav-link {{ Route::is('admin.scenes.*') ? 'active' : '' }}">
                    <i class="fas fa-camera"></i> {{ __('messages.manage_scenes') }}
                </a>
                <a href="{{ route('admin.hotspots.index') }}" class="nav-link {{ Route::is('admin.hotspots.*') ? 'active' : '' }}">
                    <i class="fas fa-crosshairs"></i> {{ __('messages.manage_hotspots') }}
                </a>
                <a href="{{ route('admin.artifacts.index') }}" class="nav-link {{ Route::is('admin.artifacts.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> {{ __('messages.manage_artifacts') }}
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Route::is('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i> {{ __('messages.manage_categories') }}
                </a>
                <hr class="my-3" style="border-color: var(--accent-green);">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start">
                        <i class="fas fa-sign-out-alt"></i> {{ __('messages.logout') }}
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content w-100">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra_js')
</body>
</html>
