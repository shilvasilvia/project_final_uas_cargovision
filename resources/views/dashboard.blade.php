@extends('layouts.app')

@section('title', 'Global Supply Chain Intelligence Dashboard')

@section('content')
<!-- Header Banner Section -->
<div class="card bg-dark text-white border-0 shadow-lg p-4 mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.08) !important;">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge text-success border border-success px-3 py-2 rounded-pill small mb-2" style="background-color: rgba(16, 185, 129, 0.15);">
                <i class="fa-solid fa-circle text-success fa-xs me-1"></i> LIVE GLOBAL INTELLIGENCE
            </span>
            <h2 class="fw-bold text-white mb-1">Global Supply Chain Intelligence</h2>
            <p class="text-slate-400 mb-0" style="max-width: 700px; color: #94a3b8;">
                Monitor global logistics, ports, economic trends, weather conditions, market intelligence and supply chain risks.
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
                <span class="text-slate-400 small" id="dash-local-date" style="color: #94a3b8;">Memuat tanggal...</span>
            </div>
            <div class="text-muted small mt-1" style="color: #94a3b8 !important;">
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
            <div class="text-slate-400 small fw-semibold" style="color: #94a3b8;">Countries</div>
            <div class="h2 fw-bold text-success my-1">{{ $totalCountries }}</div>
            <div class="text-muted small" style="color: #64748b !important;">Monitored globally</div>
        </div>
    </div>
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="text-slate-400 small fw-semibold" style="color: #94a3b8;">Global Ports</div>
            <div class="h2 fw-bold text-info my-1">{{ $totalPorts }}</div>
            <div class="text-muted small" style="color: #64748b !important;">Logistics infrastructure</div>
        </div>
    </div>
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="text-slate-400 small fw-semibold" style="color: #94a3b8;">Average Risk</div>
            <div class="h2 fw-bold text-danger my-1">{{ $avgRiskScore }}</div>
            <div class="text-muted small" style="color: #64748b !important;">Global risk index</div>
        </div>
    </div>
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="text-slate-400 small fw-semibold" style="color: #94a3b8;">High Risk Countries</div>
            <div class="h2 fw-bold text-warning my-1">{{ $highRiskCount }}</div>
            <div class="text-muted small" style="color: #64748b !important;">Requires attention</div>
        </div>
    </div>
    <div class="col-md">
        <div class="card text-white border-0 shadow p-3 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255,255,255,0.08) !important;">
            <div class="text-slate-400 small fw-semibold" style="color: #94a3b8;">Intelligence News</div>
            <div class="h2 fw-bold text-primary my-1">{{ $intelligenceNewsCount }}</div>
            <div class="text-muted small" style="color: #64748b !important;">Global intelligence feeds</div>
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
            <p class="text-slate-400 small mb-0" style="color: #94a3b8;">Real-time monitoring of external data services used by the Global Supply Chain Intelligence platform.</p>
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

<!-- Charts & Analytics Section -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3 h-100" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.1) !important;">
            <h6 class="fw-bold text-white mb-3"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Status Pengiriman Barang (Shipments)</h6>
            <canvas id="shipmentChart" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3 h-100" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.1) !important;">
            <h6 class="fw-bold text-white mb-3"><i class="fa-solid fa-cloud-bolt me-2 text-warning"></i>Distribusi Keparahan Cuaca Alert</h6>
            <canvas id="weatherChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- High Risk Countries & Recent News -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3 h-100" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.1) !important;">
            <h6 class="fw-bold text-white mb-3"><i class="fa-solid fa-shield-cat me-2 text-danger"></i>Top Negara Indeks Risiko Tinggi</h6>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0" style="background-color: transparent;">
                    <thead>
                        <tr>
                            <th>Negara</th>
                            <th>Skor Risiko</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($highRiskCountries as $r)
                            <tr>
                                <td class="fw-semibold text-white">{{ $r->country->name ?? '-' }} ({{ $r->country->code ?? '' }})</td>
                                <td class="fw-bold text-danger">{{ $r->overall_score }}</td>
                                <td>
                                    <span class="badge {{ $r->risk_category == 'Critical' || $r->risk_category == 'High' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                        {{ $r->risk_category }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data risiko.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3 h-100" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.1) !important;">
            <h6 class="fw-bold text-white mb-3"><i class="fa-solid fa-newspaper me-2 text-info"></i>Berita Logistik Terkini</h6>
            <ul class="list-group list-group-flush" style="background-color: transparent;">
                @forelse($recentNews as $n)
                    <li class="list-group-item bg-transparent text-white px-0 border-secondary border-opacity-25">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-white text-truncate" style="max-width: 75%;">{{ $n->title }}</span>
                            <span class="badge {{ $n->sentiment == 'Negative' ? 'bg-danger' : ($n->sentiment == 'Positive' ? 'bg-success' : 'bg-secondary') }}">
                                {{ $n->sentiment }}
                            </span>
                        </div>
                        <small style="color: #94a3b8;"><i class="fa-solid fa-globe me-1 text-info"></i>{{ $n->country->name ?? 'Global' }} | {{ $n->category }}</small>
                    </li>
                @empty
                    <li class="list-group-item bg-transparent text-center text-muted py-4">Belum ada berita.</li>
                @endforelse
            </ul>
        </div>
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

        // Chart 1: Shipment Status
        const ctx1 = document.getElementById('shipmentChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['In Transit', 'Delayed', 'Delivered'],
                datasets: [{
                    data: [
                        {{ $shipmentStatusData['in_transit'] }},
                        {{ $shipmentStatusData['delayed'] }},
                        {{ $shipmentStatusData['delivered'] }}
                    ],
                    backgroundColor: ['#0284c7', '#ef4444', '#10b981']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Chart 2: Weather Alerts Severity
        const ctx2 = document.getElementById('weatherChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Critical', 'High', 'Moderate', 'Low'],
                datasets: [{
                    label: 'Jumlah Alert',
                    data: [
                        {{ $weatherSeverityData['Critical'] }},
                        {{ $weatherSeverityData['High'] }},
                        {{ $weatherSeverityData['Moderate'] }},
                        {{ $weatherSeverityData['Low'] }}
                    ],
                    backgroundColor: ['#dc2626', '#ea580c', '#d97706', '#16a34a']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
@endsection
