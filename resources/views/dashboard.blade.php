@extends('layouts.app')

@section('title', 'Executive Risk Intelligence Dashboard')

@section('content')
<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card card-stat bg-white p-3 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Total Countries</div>
                    <div class="h3 fw-bold text-dark mb-0">{{ $totalCountries }}</div>
                </div>
                <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                    <i class="fa-solid fa-globe fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat bg-white p-3 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Global Ports</div>
                    <div class="h3 fw-bold text-dark mb-0">{{ $totalPorts }}</div>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-circle">
                    <i class="fa-solid fa-anchor fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat bg-white p-3 border-start border-4 border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Total Shipments</div>
                    <div class="h3 fw-bold text-dark mb-0">{{ $totalShipments }}</div>
                </div>
                <div class="bg-info-subtle text-info p-3 rounded-circle">
                    <i class="fa-solid fa-ship fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat bg-white p-3 border-start border-4 border-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Active Alerts</div>
                    <div class="h3 fw-bold text-dark mb-0">{{ $totalAlerts }}</div>
                </div>
                <div class="bg-danger-subtle text-danger p-3 rounded-circle">
                    <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map and High Risk Countries -->
<div class="row g-4 mb-4">
    <!-- Leaflet Map -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-map-location-dot me-2 text-primary"></i>Peta Lokasi Pelabuhan Global (Leaflet Interactive Map)</h6>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 380px; width: 100%; border-radius: 0 0 0.75rem 0.75rem;"></div>
            </div>
        </div>
    </div>

    <!-- Top 5 High Risk Countries -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-shield-cat me-2 text-danger"></i>Top 5 Negara Risiko Tertinggi</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($highRiskCountries as $score)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <div class="fw-bold">{{ $score->country->name ?? 'N/A' }} ({{ $score->country->code ?? '' }})</div>
                                <small class="text-muted">Economic: {{ $score->economic_risk }} | Weather: {{ $score->weather_risk }}</small>
                            </div>
                            <span class="badge {{ $score->overall_score >= 60 ? 'badge-risk-high' : 'badge-risk-medium' }} rounded-pill px-3 py-2">
                                {{ $score->overall_score }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">Belum ada kalkulasi risiko.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <!-- Chart 1: Shipment Status -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-chart-pie me-2 text-info"></i>Distribusi Status Pengiriman (Shipments)</h6>
            </div>
            <div class="card-body">
                <canvas id="shipmentChart" style="max-height: 260px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 2: Weather Alerts Severity -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-chart-bar me-2 text-warning"></i>Tingkat Keparahan Weather Alerts</h6>
            </div>
            <div class="card-body">
                <canvas id="weatherChart" style="max-height: 260px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Initialize Leaflet Map
    var map = L.map('map').setView([0, 110], 3);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var ports = @json($mapPorts);
    ports.forEach(function(port) {
        if(port.latitude && port.longitude) {
            L.marker([port.latitude, port.longitude])
                .addTo(map)
                .bindPopup("<b>" + port.name + "</b><br>" + (port.city ? port.city + ", " : "") + (port.country ? port.country.name : ""));
        }
    });

    // 2. Chart.js - Shipment Status
    var ctxShipment = document.getElementById('shipmentChart').getContext('2d');
    new Chart(ctxShipment, {
        type: 'doughnut',
        data: {
            labels: ['In Transit', 'Delayed', 'Delivered'],
            datasets: [{
                data: [{{ $shipmentStatusData['in_transit'] }}, {{ $shipmentStatusData['delayed'] }}, {{ $shipmentStatusData['delivered'] }}],
                backgroundColor: ['#0ea5e9', '#ef4444', '#10b981']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // 3. Chart.js - Weather Alerts
    var ctxWeather = document.getElementById('weatherChart').getContext('2d');
    new Chart(ctxWeather, {
        type: 'bar',
        data: {
            labels: ['Critical', 'High', 'Moderate', 'Low'],
            datasets: [{
                label: 'Jumlah Peringatan',
                data: [{{ $weatherSeverityData['Critical'] }}, {{ $weatherSeverityData['High'] }}, {{ $weatherSeverityData['Moderate'] }}, {{ $weatherSeverityData['Low'] }}],
                backgroundColor: ['#dc2626', '#f97316', '#eab308', '#22c55e']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
@endpush
