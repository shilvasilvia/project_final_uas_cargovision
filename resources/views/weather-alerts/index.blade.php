@extends('layouts.app')

@section('title', 'Operasional - Weather Alerts')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-cloud-bolt me-2 text-warning"></i>Peringatan Cuaca Buruk (Weather Alerts)</h6>
        <a href="{{ route('weather-alerts.create') }}" class="btn btn-warning text-dark btn-sm"><i class="fa-solid fa-plus me-1"></i>Buat Alert Baru</a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('weather-alerts.index') }}" class="row g-3 mb-3">
            <div class="col-md-5">
                <select name="country_id" class="form-select">
                    <option value="">-- All Countries --</option>
                    @foreach($countries as $c)<option value="{{ $c->id }}" {{ request('country_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select name="severity" class="form-select">
                    <option value="">-- All Severity Levels --</option>
                    <option value="Critical" {{ request('severity') == 'Critical' ? 'selected' : '' }}>Critical</option>
                    <option value="High" {{ request('severity') == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Moderate" {{ request('severity') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-warning text-dark w-100"><i class="fa-solid fa-magnifying-glass me-1"></i>Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Judul Alert</th>
                        <th>Negara & Port</th>
                        <th>Tipe Event</th>
                        <th>Severity</th>
                        <th>Tanggal Alert</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($weatherAlerts as $w)
                        <tr>
                            <td class="fw-bold text-dark">{{ $w->title }}</td>
                            <td>{{ $w->country->name ?? '-' }} {{ $w->port ? '('.$w->port->name.')' : '' }}</td>
                            <td><span class="badge bg-secondary">{{ $w->event_type }}</span></td>
                            <td>
                                <span class="badge {{ $w->severity == 'Critical' ? 'badge-risk-high' : 'badge-risk-medium' }}">
                                    {{ $w->severity }}
                                </span>
                            </td>
                            <td>{{ $w->alert_date }}</td>
                            <td><span class="badge bg-success">{{ $w->status }}</span></td>
                            <td class="text-end">
                                <form action="{{ route('weather-alerts.destroy', $w->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus alert?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada peringatan cuaca.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $weatherAlerts->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
