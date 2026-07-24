@extends('layouts.app')

@section('title', 'Integrasi API - World Bank Economic Data')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-cloud-arrow-down me-2 text-primary"></i>Sinkronkan Data Ekonomi Dari World Bank API</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('economic-data.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <select name="country_id" class="form-select" required>
                    <option value="">-- Pilih Negara --</option>
                    @foreach($countries as $c)<option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="year" class="form-control" required value="2025" placeholder="Tahun">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-sync me-1"></i>Fetch Live API</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-chart-line me-2 text-success"></i>Tabel Indikator Ekonomi Makro</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Negara</th>
                        <th>Tahun</th>
                        <th>GDP (USD)</th>
                        <th>Laju Inflasi (%)</th>
                        <th>Populasi</th>
                        <th>Total Ekspor (USD)</th>
                        <th>Total Impor (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($economicData as $e)
                        <tr>
                            <td class="fw-bold">{{ $e->country->name ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $e->year }}</span></td>
                            <td class="fw-semibold">${{ number_format($e->gdp) }}</td>
                            <td>{{ $e->inflation_rate }}%</td>
                            <td>{{ number_format($e->population) }}</td>
                            <td class="text-success">${{ number_format($e->exports_usd) }}</td>
                            <td class="text-danger">${{ number_format($e->imports_usd) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data ekonomi tersimpan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
