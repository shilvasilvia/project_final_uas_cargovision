@extends('layouts.app')

@section('title', 'Analisis - Risk Score Calculation')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-calculator me-2 text-primary"></i>Trigger Rekalkulasi Skor Risiko per Negara</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('risk-scores.index') }}" class="row g-3">
            @csrf
            <div class="col-md-9">
                <select name="country_id" class="form-select" required>
                    <option value="">-- Pilih Negara Untuk Dihitung Ulang Risiko --</option>
                    @foreach($countries as $c)<option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-rotate me-1"></i>Hitung Ulang Risk Score</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-shield-halved me-2 text-primary"></i>Daftar Skor Risiko Negara (Risk Index)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Negara</th>
                        <th>Overall Score (0-100)</th>
                        <th>Economic Risk</th>
                        <th>Weather Risk</th>
                        <th>Geopolitical Risk</th>
                        <th>Operational Risk</th>
                        <th>Kategori Risk</th>
                        <th>Kalkulasi Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riskScores as $r)
                        <tr>
                            <td class="fw-bold fs-6">{{ $r->country->name ?? '-' }}</td>
                            <td><span class="fs-5 fw-bold text-primary">{{ $r->overall_score }}</span></td>
                            <td>{{ $r->economic_risk }}</td>
                            <td>{{ $r->weather_risk }}</td>
                            <td>{{ $r->geopolitical_risk }}</td>
                            <td>{{ $r->operational_risk }}</td>
                            <td>
                                <span class="badge {{ $r->risk_category == 'High' ? 'badge-risk-high' : 'badge-risk-low' }} px-3 py-2">
                                    {{ $r->risk_category }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $r->calculated_at ?? $r->updated_at }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada skor risiko terhitung.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
