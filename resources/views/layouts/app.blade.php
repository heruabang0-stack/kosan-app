<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pencari Kosan') - CariKos</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Leaflet Maps CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        /* ===== BACKGROUND GRADIENT ===== */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.08) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container, nav, footer {
            position: relative;
            z-index: 1;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(102, 126, 234, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* ===== CARD ===== */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
        }

        /* ===== MAPS CONTAINER ===== */
        .leaflet-map {
            border-radius: 15px;
            z-index: 10;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 4px;
        }

        /* ===== FORM ===== */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 10px 15px;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }

        /* ===== BUTTON ===== */
        .btn {
            border-radius: 10px;
            padding: 8px 18px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3, #6a3f92);
        }

        /* ===== FOOTER ===== */
        footer {
            background: rgba(33, 37, 41, 0.95) !important;
            backdrop-filter: blur(10px);
            color: #ffffff;
        }

        footer p {
            color: #ffffff !important;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('kos.index') }}">
                <i class="bi bi-house-heart-fill fs-3 me-2 text-warning"></i> 
                <span>CariKos</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kos.*') ? 'active fw-bold' : '' }}" href="{{ route('kos.index') }}">
                            <i class="bi bi-search me-1"></i> Cari Kos & Peta
                        </a>
                    </li>
                    @auth
                        @if(Auth::user()->role === 'user')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active fw-bold' : '' }}" href="{{ route('pesanan.index') }}">
                                    <i class="bi bi-calendar-check me-1"></i> Pesanan Saya
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold text-warning' : 'text-warning' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Admin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.kos.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.kos.index') }}">
                                    <i class="bi bi-building me-1"></i> Kelola Kos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.pesanan.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.pesanan.index') }}">
                                    <i class="bi bi-receipt me-1"></i> Kelola Pesanan
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        <li class="nav-item me-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-light btn-sm fw-bold text-primary">
                                <i class="bi bi-person-plus me-1"></i> Daftar
                            </a>
                        </li>
                    @else
                        {{-- Info Akun Aktif --}}
                        <li class="nav-item me-2 d-none d-md-flex align-items-center text-white">
                            <span class="small me-1">Halo,</span>
                            <strong>{{ Auth::user()->name }}</strong>
                            <span class="badge ms-2 bg-{{ Auth::user()->role === 'admin' ? 'warning text-dark' : 'info' }}">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </li>

                        {{-- TOMBOL LOGOUT LANGSUNG (MUDAH DIAKSES) --}}
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm shadow-sm fw-bold" title="Keluar dari akun ini">
                                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container mt-4 mb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="text-center py-4 mt-auto">
        <p class="mb-0">&copy; {{ date('Y') }} CariKos - Sistem Pencarian & Pemesanan Kos Terintegrasi Maps</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet Maps JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    @yield('scripts')
</body>
</html>
