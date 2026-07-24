@extends('layouts.app')

@section('title', 'Laporan & Export Executive Summary')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-filter me-2 text-primary"></i>Filter Laporan</h6>
        <div>
            <a href="{{ route('reports.pdf') }}" class="btn btn-danger btn-sm me-2"><i class="fa-solid fa-file-pdf me-1"></i>Export PDF</a>
            <a href="{{ route('reports.excel') }}" class="btn btn-success btn-sm"><i class="fa-solid fa-file-excel me-1"></i>Export Excel / CSV</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Negara</label>
                <select name="country_id" class="form-select">
                    <option value="">-- Semua Negara --</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}" {{ request('country_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-magnifying-glass me-1"></i>Terapkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Shipments Preview -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-ship me-2 text-info"></i>Ringkasan Data Pengiriman (Shipments)</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Shipment</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Departed</th>
                        <th>Est. Arrival</th>
                        <th>Cargo</th>
                        <th>Risk Level</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $s)
                        <tr>
                            <td class="fw-bold">{{ $s->shipment_number }}</td>
                            <td>{{ $s->originCountry->name ?? '-' }}</td>
                            <td>{{ $s->destinationCountry->name ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $s->status }}</span></td>
                            <td>{{ $s->departure_date }}</td>
                            <td>{{ $s->estimated_arrival }}</td>
                            <td>{{ $s->cargo_type }}</td>
                            <td>
                                <span class="badge {{ $s->risk_level == 'High' ? 'badge-risk-high' : 'badge-risk-low' }}">
                                    {{ $s->risk_level }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data pengiriman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Weather Alerts Preview -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-cloud-bolt me-2 text-warning"></i>Ringkasan Weather Alerts</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Judul Peringatan</th>
                        <th>Negara</th>
                        <th>Tipe Event</th>
                        <th>Severity</th>
                        <th>Tanggal Alert</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($weatherAlerts as $w)
                        <tr>
                            <td class="fw-bold">{{ $w->title }}</td>
                            <td>{{ $w->country->name ?? '-' }}</td>
                            <td>{{ $w->event_type }}</td>
                            <td><span class="badge bg-danger">{{ $w->severity }}</span></td>
                            <td>{{ $w->alert_date }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data peringatan cuaca.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
