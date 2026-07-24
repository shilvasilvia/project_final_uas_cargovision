@extends('layouts.app')

@section('title', 'Global Supply Chain Risk Intelligence Dashboard')

@section('content')
<!-- Header Banner Section -->
<div class="card text-white border-0 shadow-lg p-4 mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.08) !important;">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge text-success border border-success px-3 py-2 rounded-pill small mb-2" style="background-color: rgba(16, 185, 129, 0.15);">
                <i class="fa-solid fa-circle text-success fa-xs me-1"></i> LIVE GLOBAL INTELLIGENCE
            </span>
            <h2 class="fw-bold text-white mb-1">Global Supply Chain Intelligence</h2>
            <p style="color: #94a3b8; max-width: 750px;" class="mb-0">
                Monitor global logistics, ports, economic trends, weather conditions, market intelligence and supply chain risks across 15 strategic trade nations.
            </p>
        </div>
        <div>
            <button onclick="window.location.reload()" class="btn btn-outline-info rounded-pill px-4 py-2 fw-semibold">
                <i class="fa-solid fa-rotate me-2"></i> Sync Global Data
            </button>
        </div>
    </div>
</div>

<!-- Country Local Time & Select Country Card -->
<div class="card text-white border-0 shadow-lg p-4 mb-4" style="background-color: #1e293b !important; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1) !important;">
    <div class="row g-3 align-items-center">
        <div class="col-md-7">
            <div class="text-uppercase text-info small fw-bold mb-1" style="letter-spacing: 1px;">
                <i class="fa-solid fa-clock me-1"></i> COUNTRY LOCAL TIME
            </div>
            <div class="d-flex align-items-baseline gap-3">
                <h2 class="display-6 fw-bold text-success mb-0" id="dash-local-clock">--:--:--</h2>
                <span class="small" id="dash-local-date" style="color: #94a3b8;">Memuat tanggal...</span>
            </div>
            <div class="small mt-1" style="color: #94a3b8;">
                <i class="fa-solid fa-globe me-1 text-info"></i> Zone: <span class="text-info fw-semibold">{{ $countryMeta['gmt'] }}</span>
            </div>
        </div>

        <div class="col-md-5">
            <label for="country_id" class="form-label text-white small fw-bold mb-2">Select Country</label>
            <form method="GET" action="{{ route('dashboard') }}" id="country-select-form">
                <select name="country_id" id="country_id" class="form-select text-white border-secondary rounded-3 py-2 px-3" style="background-color: #0f172a !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important;" onchange="document.getElementById('country-select-form').submit()">
                    @foreach($allCountries as $c)
                        <option value="{{ $c->id }}" style="background-color: #0f172a; color: #ffffff;" {{ ($selectedCountry && $selectedCountry->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->code }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Weather Highlight for Selected Country -->
    <div class="mt-3 pt-3 border-top border-secondary border-opacity-25 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="text-light small me-2"><i class="fa-solid fa-cloud-sun text-warning me-1"></i> Realtime Weather (Open-Meteo):</span>
            <span class="badge text-warning border border-warning px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(234, 179, 8, 0.15); font-size: 0.85rem;">
                <i class="fa-solid fa-temperature-high me-1"></i> {{ $weather['temperature'] }} °C
            </span>
            <span class="badge text-info border border-info px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(56, 189, 248, 0.15); font-size: 0.85rem;">
                <i class="fa-solid fa-wind me-1"></i> {{ $weather['wind_speed'] }} km/h
            </span>
        </div>
        <div>
            @if($weather['is_storm_risk'])
                <span class="badge text-danger border border-danger px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(239, 68, 68, 0.15);"><i class="fa-solid fa-triangle-exclamation me-1"></i> Storm Warning</span>
            @else
                <span class="badge text-success border border-success px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(16, 185, 129, 0.15);"><i class="fa-solid fa-check me-1"></i> Weather Optimal</span>
            @endif
        </div>
    </div>
</div>

<!-- Key Metrics Grid -->
<div class="row g-3 mb-4">
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="small fw-semibold" style="color: #94a3b8;">Countries</div>
            <div class="h2 fw-bold text-success my-1">{{ $totalCountries }}</div>
            <div class="small" style="color: #64748b;">Monitored globally</div>
        </div>
    </div>
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="small fw-semibold" style="color: #94a3b8;">Global Ports</div>
            <div class="h2 fw-bold text-info my-1">{{ $totalPorts }}</div>
            <div class="small" style="color: #64748b;">Logistics infrastructure</div>
        </div>
    </div>
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="small fw-semibold" style="color: #94a3b8;">Average Risk</div>
            <div class="h2 fw-bold text-danger my-1">{{ $avgRiskScore }}</div>
            <div class="small" style="color: #64748b;">Global risk index</div>
        </div>
    </div>
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="small fw-semibold" style="color: #94a3b8;">High Risk Countries</div>
            <div class="h2 fw-bold text-warning my-1">{{ $highRiskCount }}</div>
            <div class="small" style="color: #64748b;">Requires attention</div>
        </div>
    </div>
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="small fw-semibold" style="color: #94a3b8;">Intelligence News</div>
            <div class="h2 fw-bold text-primary my-1">{{ $intelligenceNewsCount }}</div>
            <div class="small" style="color: #64748b;">Global intelligence feeds</div>
        </div>
    </div>
</div>

<!-- ADMIN-ONLY SECTION: API Status Monitoring -->
@if(auth()->user() && auth()->user()->isAdmin())
<div class="card text-white border-0 shadow-lg p-4 mb-4" style="background-color: #0f172a !important; border-radius: 1rem; border: 1px solid rgba(56, 189, 248, 0.3) !important;">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-white mb-1">
                <i class="fa-solid fa-server text-info me-2"></i>API Status Monitoring
            </h4>
            <p class="small mb-0" style="color: #94a3b8;">Real-time monitoring of external data services used by the Global Supply Chain Intelligence platform.</p>
        </div>
        <div>
            <span class="badge text-success border border-success px-3 py-2 rounded-pill small fw-bold" style="background-color: rgba(16, 185, 129, 0.15);">
                <i class="fa-solid fa-circle fa-xs me-1"></i> LIVE MONITORING (ADMIN)
            </span>
        </div>
    </div>

    <!-- API Metric Overview Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="p-3 rounded-3 border" style="background-color: #1e293b; border-color: rgba(255,255,255,0.1) !important;">
                <div class="small fw-semibold text-uppercase" style="color: #94a3b8;">TOTAL API</div>
                <div class="h3 fw-bold text-info mb-0">5</div>
            </div>
        </div>
        <div class="col-md">
            <div class="p-3 rounded-3 border" style="background-color: #1e293b; border-color: rgba(255,255,255,0.1) !important;">
                <div class="small fw-semibold text-uppercase" style="color: #94a3b8;">ONLINE</div>
                <div class="h3 fw-bold text-success mb-0">5</div>
            </div>
        </div>
        <div class="col-md">
            <div class="p-3 rounded-3 border" style="background-color: #1e293b; border-color: rgba(255,255,255,0.1) !important;">
                <div class="small fw-semibold text-uppercase" style="color: #94a3b8;">OFFLINE</div>
                <div class="h3 fw-bold text-danger mb-0">0</div>
            </div>
        </div>
        <div class="col-md">
            <div class="p-3 rounded-3 border" style="background-color: #1e293b; border-color: rgba(255,255,255,0.1) !important;">
                <div class="small fw-semibold text-uppercase" style="color: #94a3b8;">UNKNOWN</div>
                <div class="h3 fw-bold text-warning mb-0">0</div>
            </div>
        </div>
        <div class="col-md">
            <div class="p-3 rounded-3 border" style="background-color: #1e293b; border-color: rgba(255,255,255,0.1) !important;">
                <div class="small fw-semibold text-uppercase" style="color: #94a3b8;">AVG RESPONSE</div>
                <div class="h3 fw-bold text-primary mb-0">315.60 <span class="fs-6 text-muted">ms</span></div>
            </div>
        </div>
    </div>

    <!-- Detailed External API Services Grid -->
    <div class="row g-3">
        @foreach($apiServices as $api)
            <div class="col-md-6 col-lg-4">
                <div class="p-3 rounded-3 border h-100" style="background-color: #1e293b; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-white">{{ $api['name'] }}</span>
                        <span class="badge text-success border border-success rounded-pill px-2 py-1 small fw-bold" style="background-color: rgba(16, 185, 129, 0.15);">
                            ● {{ $api['status'] }}
                        </span>
                    </div>
                    <p class="small mb-2" style="color: #94a3b8;">{{ $api['description'] }}</p>
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>HTTP Status: <strong class="text-success fw-bold">{{ $api['http_status'] }}</strong></span>
                        <span>Response: <strong class="text-info fw-bold">{{ $api['response_time'] }}</strong></span>
                    </div>
                    <div class="p-2 rounded text-info small text-truncate" style="background-color: #0b1120; font-family: monospace; font-size: 11px; border: 1px solid rgba(255,255,255,0.05);">
                        ENDPOINT: {{ $api['endpoint'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Interactive Map Component -->
<div class="mb-4">
    @livewire('map-component')
</div>

<!-- 1. Country Intelligence Cards Grid (15 Countries) -->
<div class="card text-white border-0 shadow-lg p-4 mb-4" style="background-color: #1e293b !important; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1) !important;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fa-solid fa-globe text-primary me-2"></i>Country Intelligence Center</h4>
            <p class="small mb-0" style="color: #94a3b8;">Ringkasan skor risiko, GDP, inflasi, mata uang, dan pelabuhan 15 negara utama</p>
        </div>
        <span class="badge bg-primary bg-opacity-20 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill small fw-bold">
            15 Countries Monitored
        </span>
    </div>

    <div class="row g-3">
        @foreach($countryCards as $card)
            <div class="col-md-6 col-lg-4">
                <div class="p-3 rounded-3 border h-100" style="background-color: #0f172a; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-bold text-white mb-0">{{ $card['name'] }}</h6>
                            <small style="color: #94a3b8;">{{ $card['region'] }} | {{ $card['capital'] }}</small>
                        </div>
                        <span class="badge bg-dark border border-secondary text-info px-2 py-1">{{ $card['code'] }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center my-3 p-2 rounded" style="background-color: #1e293b;">
                        <span class="small text-light">Overall Risk Index:</span>
                        <span class="badge {{ $card['risk_score'] >= 50 ? 'bg-danger text-white' : ($card['risk_score'] >= 30 ? 'bg-warning text-dark' : 'bg-success text-white') }} px-3 py-1 fw-bold">
                            {{ $card['risk_score'] }} ({{ $card['risk_category'] }})
                        </span>
                    </div>

                    <div class="row text-center g-2 small mb-3">
                        <div class="col-4">
                            <div class="p-1 rounded bg-dark border border-secondary border-opacity-25">
                                <div style="color: #94a3b8; font-size: 10px;">INFLATION</div>
                                <div class="fw-bold text-warning">{{ $card['inflation'] }}%</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-1 rounded bg-dark border border-secondary border-opacity-25">
                                <div style="color: #94a3b8; font-size: 10px;">GDP GROW</div>
                                <div class="fw-bold text-success">+{{ $card['gdp_growth'] }}%</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-1 rounded bg-dark border border-secondary border-opacity-25">
                                <div style="color: #94a3b8; font-size: 10px;">PORTS</div>
                                <div class="fw-bold text-info">{{ $card['ports_count'] }}</div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('countries.show', $card['id']) }}" class="btn btn-outline-info btn-sm w-100 rounded-pill">
                        <i class="fa-solid fa-eye me-1"></i> View Country Details
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- 2. Country Comparison Engine (Side-by-Side & Spider/Radar Chart) -->
<div class="card text-white border-0 shadow-lg p-4 mb-4" style="background-color: #1e293b !important; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1) !important;">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fa-solid fa-scale-balanced me-2 text-warning"></i>Country Comparison Engine</h4>
            <p class="small mb-0" style="color: #94a3b8;">Komparasi langsung risiko 5-dimensi & indikator antar 2 negara (Side-by-Side Mode)</p>
        </div>
    </div>

    <!-- Country A vs Country B Selector -->
    <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-5">
            <label class="form-label text-info small fw-bold"><i class="fa-solid fa-flag me-1"></i> Negara A (Country A)</label>
            <select name="country_a" class="form-select text-white rounded-3 py-2" style="background-color: #0f172a; border: 1px solid rgba(255,255,255,0.2);">
                @foreach($allCountries as $c)
                    <option value="{{ $c->id }}" {{ ($countryA && $countryA->id == $c->id) ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 text-center">
            <span class="badge bg-danger rounded-circle p-3 fs-5 fw-bold">VS</span>
        </div>
        <div class="col-md-5">
            <label class="form-label text-success small fw-bold"><i class="fa-solid fa-flag me-1"></i> Negara B (Country B)</label>
            <select name="country_b" class="form-select text-white rounded-3 py-2" style="background-color: #0f172a; border: 1px solid rgba(255,255,255,0.2);">
                @foreach($allCountries as $c)
                    <option value="{{ $c->id }}" {{ ($countryB && $countryB->id == $c->id) ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-compress me-1"></i> Bandingkan 2 Negara Ini
            </button>
        </div>
    </form>

    <div class="row g-4">
        <!-- Radar Chart 5 Dimensions -->
        <div class="col-md-6">
            <div class="p-3 rounded-3 border h-100" style="background-color: #0f172a; border-color: rgba(255,255,255,0.1) !important;">
                <h6 class="fw-bold text-center text-info mb-3">Radar Chart Komparasi Risiko (5-Dimensi)</h6>
                <div style="height: 300px;">
                    <canvas id="riskRadarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Side-by-Side Stats Comparison Table -->
        <div class="col-md-6">
            <div class="p-3 rounded-3 border h-100" style="background-color: #0f172a; border-color: rgba(255,255,255,0.1) !important;">
                <h6 class="fw-bold text-center text-warning mb-3">Tabel Matriks Komparasi Indikator</h6>
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Indikator</th>
                                <th class="text-info">{{ $countryA->name ?? 'Country A' }}</th>
                                <th class="text-success">{{ $countryB->name ?? 'Country B' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Overall Risk Index</td>
                                <td class="text-center fw-bold text-info">{{ $riskAData['overall_score'] }}</td>
                                <td class="text-center fw-bold text-success">{{ $riskBData['overall_score'] }}</td>
                            </tr>
                            <tr>
                                <td>Weather Risk (30%)</td>
                                <td class="text-center">{{ $riskAData['weather_risk'] }}</td>
                                <td class="text-center">{{ $riskBData['weather_risk'] }}</td>
                            </tr>
                            <tr>
                                <td>Inflation Risk (20%)</td>
                                <td class="text-center">{{ $riskAData['economic_risk'] }}</td>
                                <td class="text-center">{{ $riskBData['economic_risk'] }}</td>
                            </tr>
                            <tr>
                                <td>Geopolitical Risk (40%)</td>
                                <td class="text-center">{{ $riskAData['geopolitical_risk'] }}</td>
                                <td class="text-center">{{ $riskBData['geopolitical_risk'] }}</td>
                            </tr>
                            <tr>
                                <td>Currency Risk (10%)</td>
                                <td class="text-center">{{ $riskAData['operational_risk'] }}</td>
                                <td class="text-center">{{ $riskBData['operational_risk'] }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah Pelabuhan Hub</td>
                                <td class="text-center fw-bold">{{ $countryA ? $countryA->ports->count() : 0 }}</td>
                                <td class="text-center fw-bold">{{ $countryB ? $countryB->ports->count() : 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Currency Impact Dashboard & Exchange Rates Grid -->
<div class="card text-white border-0 shadow-lg p-4 mb-4" style="background-color: #1e293b !important; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1) !important;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fa-solid fa-coins me-2 text-success"></i>Currency Impact & Exchange Rate Dashboard</h4>
            <p class="small mb-0" style="color: #94a3b8;">Nilai tukar mata uang real-time terhadap USD & indikator tren forex pasar global</p>
        </div>
        <span class="badge text-success border border-success px-3 py-2 rounded-pill small fw-bold" style="background-color: rgba(16, 185, 129, 0.15);">
            Live Exchange Rates
        </span>
    </div>

    <div class="row g-3">
        @foreach($currencyGrid as $curr)
            <div class="col-md-4 col-lg-3">
                <div class="p-3 rounded-3 border h-100" style="background-color: #0f172a; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-dark border border-secondary text-info fw-bold">{{ $curr->currency_code }} / USD</span>
                        @if($curr->trend_direction === 'bullish')
                            <span class="badge text-success border border-success rounded-pill px-2 py-1 small" style="background-color: rgba(16, 185, 129, 0.15);">
                                <i class="fa-solid fa-arrow-trend-up me-1"></i>Bullish
                            </span>
                        @elseif($curr->trend_direction === 'bearish')
                            <span class="badge text-danger border border-danger rounded-pill px-2 py-1 small" style="background-color: rgba(239, 68, 68, 0.15);">
                                <i class="fa-solid fa-arrow-trend-down me-1"></i>Bearish
                            </span>
                        @else
                            <span class="badge text-secondary border border-secondary rounded-pill px-2 py-1 small" style="background-color: rgba(148, 163, 184, 0.15);">
                                <i class="fa-solid fa-minus me-1"></i>Stable
                            </span>
                        @endif
                    </div>
                    <div class="h4 fw-bold text-white mb-1">
                        {{ number_format($curr->exchange_rate_usd, 2) }}
                    </div>
                    <div class="small" style="color: #94a3b8;">
                        Negara: <strong>{{ $curr->country->name ?? '-' }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- 4. News Intelligence Visual Grid -->
<div class="card text-white border-0 shadow-lg p-4 mb-4" style="background-color: #1e293b !important; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1) !important;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fa-solid fa-newspaper me-2 text-info"></i>News Intelligence & Geopolitics Feed</h4>
            <p class="small mb-0" style="color: #94a3b8;">Umpan berita maritim & analisis sentimen disrupsi rantai pasok global</p>
        </div>
        <a href="{{ route('news.index') }}" class="btn btn-outline-info btn-sm rounded-pill px-3">
            Lihat Semua Berita <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-3">
        @foreach($recentNews as $n)
            <div class="col-md-6 col-lg-4">
                <div class="p-3 rounded-3 border h-100" style="background-color: #0f172a; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary bg-opacity-20 text-info border border-info border-opacity-25 px-2 py-1 small">
                            {{ $n->category }}
                        </span>
                        @if($n->sentiment === 'Positive')
                            <span class="badge text-success border border-success rounded-pill px-2 py-1 small" style="background-color: rgba(16, 185, 129, 0.15);">
                                Positive
                            </span>
                        @elseif($n->sentiment === 'Negative')
                            <span class="badge text-danger border border-danger rounded-pill px-2 py-1 small" style="background-color: rgba(239, 68, 68, 0.15);">
                                Negative Risk
                            </span>
                        @else
                            <span class="badge text-secondary border border-secondary rounded-pill px-2 py-1 small" style="background-color: rgba(148, 163, 184, 0.15);">
                                Neutral
                            </span>
                        @endif
                    </div>
                    <h6 class="fw-bold text-white mb-2" style="font-size: 0.95rem;">{{ $n->title }}</h6>
                    <p class="small mb-3" style="color: #94a3b8; line-height: 1.4;">{{ Str::limit($n->content, 110) }}</p>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25 small" style="color: #64748b;">
                        <span><i class="fa-solid fa-globe me-1 text-info"></i>{{ $n->country->name ?? 'Global' }}</span>
                        <span><i class="fa-solid fa-clock me-1"></i>{{ $n->published_date ? \Carbon\Carbon::parse($n->published_date)->diffForHumans() : 'Realtime' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Day.js & Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/utc.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/timezone.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Day.js Live Clock for Selected Country
        if (typeof dayjs !== 'undefined') {
            dayjs.extend(window.dayjs_plugin_utc);
            dayjs.extend(window.dayjs_plugin_timezone);

            const timeZone = "{{ $countryMeta['tz'] }}";

            function updateDashClock() {
                try {
                    const now = dayjs().tz(timeZone);
                    document.getElementById('dash-local-clock').textContent = now.format('HH:mm:ss');
                    document.getElementById('dash-local-date').textContent = now.format('D MMMM YYYY');
                } catch (e) {
                    const now = dayjs();
                    document.getElementById('dash-local-clock').textContent = now.format('HH:mm:ss');
                    document.getElementById('dash-local-date').textContent = now.format('D MMMM YYYY');
                }
            }

            updateDashClock();
            setInterval(updateDashClock, 1000);
        }

        // Radar Chart for Country Comparison
        const ctxRadar = document.getElementById('riskRadarChart').getContext('2d');
        new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: ['Weather Risk', 'Inflation Risk', 'Geopolitical Risk', 'Currency Risk', 'Operational Risk'],
                datasets: [
                    {
                        label: "{{ $countryA->name ?? 'Country A' }}",
                        data: [
                            {{ $riskAData['weather_risk'] }},
                            {{ $riskAData['economic_risk'] }},
                            {{ $riskAData['geopolitical_risk'] }},
                            {{ $riskAData['operational_risk'] }},
                            25.0
                        ],
                        backgroundColor: 'rgba(56, 189, 248, 0.25)',
                        borderColor: '#38bdf8',
                        pointBackgroundColor: '#38bdf8',
                    },
                    {
                        label: "{{ $countryB->name ?? 'Country B' }}",
                        data: [
                            {{ $riskBData['weather_risk'] }},
                            {{ $riskBData['economic_risk'] }},
                            {{ $riskBData['geopolitical_risk'] }},
                            {{ $riskBData['operational_risk'] }},
                            30.0
                        ],
                        backgroundColor: 'rgba(16, 185, 129, 0.25)',
                        borderColor: '#10b981',
                        pointBackgroundColor: '#10b981',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255,255,255,0.1)' },
                        grid: { color: 'rgba(255,255,255,0.1)' },
                        pointLabels: { color: '#94a3b8', font: { size: 11 } },
                        ticks: { color: '#64748b', backdropColor: 'transparent' }
                    }
                },
                plugins: {
                    legend: { labels: { color: '#ffffff' } }
                }
            }
        });
    });
</script>
@endsection
