<div wire:poll.300s class="card card-stat bg-dark text-white border-0 shadow-lg p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0 text-info">
                <i class="fa-solid fa-map-location-dot me-2"></i>Global Supply Chain Map & Ports
            </h5>
            <small class="text-muted">Real-time port tracking & weather alerts (Livewire Poll: 5 mins)</small>
        </div>
        <div class="text-end">
            <span class="badge bg-success px-3 py-2 rounded-pill small">
                <i class="fa-solid fa-sync fa-spin me-1"></i> Realtime Sync Active
            </span>
        </div>
    </div>

    <div id="leaflet-cluster-map" style="height: 480px; border-radius: 0.75rem; overflow: hidden;" wire:ignore></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L !== 'undefined') {
                const map = L.map('leaflet-cluster-map').setView([20.0, 10.0], 2);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://carto.com/">CARTO</a> CargoVision',
                    subdomains: 'abcd',
                    maxZoom: 19
                }).addTo(map);

                const ports = @json($ports);
                ports.forEach(port => {
                    if (port.latitude && port.longitude) {
                        const marker = L.circleMarker([port.latitude, port.longitude], {
                            radius: 7,
                            fillColor: '#38bdf8',
                            color: '#0284c7',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.85
                        });

                        marker.bindPopup(`
                            <div style="font-family: Inter, sans-serif;">
                                <h6 style="margin: 0; font-weight: 700; color: #0f172a;">${port.name}</h6>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #475569;">Kota: ${port.city || '-'}, Negara: ${port.country ? port.country.name : '-'}</p>
                                <span style="display:inline-block; margin-top:6px; padding:2px 8px; font-size:10px; background:#10b981; color:#fff; border-radius:10px;">Status: Aktif</span>
                            </div>
                        `);
                        marker.addTo(map);
                    }
                });
            }
        });
    </script>
</div>
