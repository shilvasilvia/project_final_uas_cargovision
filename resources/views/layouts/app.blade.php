<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Global Supply Chain Risk Intelligence') }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @livewireStyles
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            border-radius: 0.375rem;
            margin: 0.2rem 0.5rem;
            transition: all 0.2s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link i {
            width: 1.5rem;
        }
        .brand-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #38bdf8;
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            letter-spacing: 0.5px;
        }
        .top-navbar {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .card-stat {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }
        .card-stat:hover {
            transform: translateY(-3px);
        }
        .badge-risk-high { background-color: #ef4444; color: #fff; }
        .badge-risk-medium { background-color: #f59e0b; color: #fff; }
        .badge-risk-low { background-color: #10b981; color: #fff; }
    </style>
    @stack('styles')
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse px-0">
            <div class="brand-title">
                <i class="fa-solid fa-ship me-2"></i>Risk Intelligence
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-chart-line me-2"></i>Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}" href="{{ route('favorites.index') }}">
                        <i class="fa-solid fa-star me-2 text-warning"></i>Favorite Monitoring
                    </a>
                </li>

                <div class="text-uppercase px-3 mt-3 mb-1 text-muted small fw-bold" style="font-size:0.75rem;">Master & Data</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}" href="{{ route('countries.index') }}">
                        <i class="fa-solid fa-globe me-2"></i>Countries
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ports.*') ? 'active' : '' }}" href="{{ route('ports.index') }}">
                        <i class="fa-solid fa-anchor me-2"></i>Ports
                    </a>
                </li>

                <div class="text-uppercase px-3 mt-3 mb-1 text-muted small fw-bold" style="font-size:0.75rem;">Operasional & Analisis</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shipments.*') ? 'active' : '' }}" href="{{ route('shipments.index') }}">
                        <i class="fa-solid fa-boxes-packing me-2"></i>Shipments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('weather-alerts.*') ? 'active' : '' }}" href="{{ route('weather-alerts.index') }}">
                        <i class="fa-solid fa-cloud-bolt me-2"></i>Weather Alerts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('risk-scores.*') ? 'active' : '' }}" href="{{ route('risk-scores.index') }}">
                        <i class="fa-solid fa-shield-halved me-2"></i>Risk Scores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                        <i class="fa-solid fa-newspaper me-2"></i>News & Sentiment
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('country-comparisons.*') ? 'active' : '' }}" href="{{ route('country-comparisons.index') }}">
                        <i class="fa-solid fa-scale-balanced me-2"></i>Comparison
                    </a>
                </li>

                @if(auth()->user() && auth()->user()->isAdmin())
                    <div class="text-uppercase px-3 mt-3 mb-1 text-muted small fw-bold" style="font-size:0.75rem;">Laporan & API (Admin)</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i class="fa-solid fa-file-pdf me-2"></i>Reports & Export
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        <!-- Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <!-- Top Navbar Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom top-navbar p-3 rounded-3">
                <h4 class="h5 mb-0 fw-bold text-slate-800">
                    @yield('title', 'Global Supply Chain Risk Intelligence System')
                </h4>
                <div class="d-flex align-items-center gap-3">
                    @if(auth()->user() && auth()->user()->isAdmin())
                        <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fa-solid fa-crown me-1"></i> ADMIN (Full Access)</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="fa-solid fa-user me-1"></i> USER (Read-Only)</span>
                    @endif
                    <span class="fw-semibold">{{ auth()->user()->name ?? 'User' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</button>
                    </form>
                </div>
            </div>

            <!-- Flash Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- JS Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@livewireScripts
@stack('scripts')
</body>
</html>
