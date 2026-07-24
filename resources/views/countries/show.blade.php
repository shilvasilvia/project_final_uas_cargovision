@extends('layouts.app')

@section('title', 'Detail Negara - ' . $country->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-slate-800">
            <i class="fa-solid fa-flag me-2 text-primary"></i>{{ $country->name }} ({{ $country->code }})
        </h3>
        <p class="text-muted mb-0">Regional: {{ $country->region ?? '-' }} | Ibu Kota: {{ $country->capital ?? '-' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Weather & Realtime Clock Banner -->
<div class="row g-3 mb-4">
    <!-- Realtime Local Clock Card -->
    <div class="col-md-6">
        <div class="card card-stat bg-dark text-white border-0 shadow-lg p-4 h-100" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-primary bg-opacity-30 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill small">
                    <i class="fa-solid fa-clock me-1"></i> Jam Lokal Negara
                </span>
                <span class="text-muted small">{{ $coords['gmt'] }}</span>
            </div>
            <div class="my-auto text-center">
                <h1 class="display-4 fw-bold text-info mb-1" id="live-local-clock">--:--:--</h1>
                <p class="text-slate-400 mb-0" id="live-local-date">Memuat tanggal lokal...</p>
            </div>
        </div>
    </div>

    <!-- Live Weather Card (Open-Meteo API) -->
    <div class="col-md-6">
        <div class="card card-stat bg-dark text-white border-0 shadow-lg p-4 h-100" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill small">
                    <i class="fa-solid fa-cloud-sun me-1"></i> Cuaca Real-time (Open-Meteo)
                </span>
                <span class="text-muted small"><i class="fa-solid fa-signal me-1 text-success"></i> Live API</span>
            </div>
            <div class="d-flex align-items-center justify-content-around my-auto">
                <div class="text-center">
                    <i class="fa-solid fa-temperature-high fa-3x text-warning mb-2"></i>
                    <h2 class="fw-bold mb-0 text-white">{{ $weather['temperature'] }} °C</h2>
                    <small class="text-muted">Temperatur Udara</small>
                </div>
                <div class="border-end border-secondary border-opacity-25" style="height: 60px;"></div>
                <div class="text-center">
                    <i class="fa-solid fa-wind fa-3x text-info mb-2"></i>
                    <h2 class="fw-bold mb-0 text-white">{{ $weather['wind_speed'] }} km/h</h2>
                    <small class="text-muted">Kecepatan Angin</small>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <span class="small text-muted">Status Risiko Cuaca:</span>
                @if($weather['is_storm_risk'])
                    <span class="badge bg-danger px-2 py-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Peringatan Badai</span>
                @else
                    <span class="badge bg-success px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i> Cuaca Kondusif</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Country Detail Cards -->
<div class="row g-3">
    <!-- Macro Stats -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 h-100">
            <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Indikator Ekonomi Makro</h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Populasi:</span>
                    <span class="fw-semibold">{{ number_format($country->population ?? 0) }} Jiwa</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Region:</span>
                    <span class="fw-semibold">{{ $country->region ?? '-' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Ibu Kota:</span>
                    <span class="fw-semibold">{{ $country->capital ?? '-' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Kode ISO:</span>
                    <span class="badge bg-dark">{{ $country->code }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Ports List -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-3 h-100">
            <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-anchor me-2 text-info"></i>Daftar Pelabuhan Maritim Hub</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pelabuhan</th>
                            <th>Kota</th>
                            <th>Kordinat (Lat, Lng)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($country->ports as $port)
                            <tr>
                                <td class="fw-bold text-dark">{{ $port->name }}</td>
                                <td>{{ $port->city }}</td>
                                <td><code>{{ $port->latitude }}, {{ $port->longitude }}</code></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada pelabuhan terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Day.js Clock Script -->
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/utc.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/timezone.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof dayjs !== 'undefined') {
            dayjs.extend(window.dayjs_plugin_utc);
            dayjs.extend(window.dayjs_plugin_timezone);

            const timeZone = "{{ $coords['tz'] }}";

            function updateClock() {
                try {
                    const now = dayjs().tz(timeZone);
                    document.getElementById('live-local-clock').textContent = now.format('HH:mm:ss');
                    document.getElementById('live-local-date').textContent = now.format('D MMMM YYYY');
                } catch (e) {
                    const now = dayjs();
                    document.getElementById('live-local-clock').textContent = now.format('HH:mm:ss');
                    document.getElementById('live-local-date').textContent = now.format('D MMMM YYYY');
                }
            }

            updateClock();
            setInterval(updateClock, 1000);
        }
    });
</script>
@endsection
