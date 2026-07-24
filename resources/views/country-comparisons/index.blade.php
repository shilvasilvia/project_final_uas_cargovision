@extends('layouts.app')

@section('title', 'Analisis Komparasi Risiko Antar Negara')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-scale-balanced me-2 text-primary"></i>Pilih 2 Negara Untuk Dibandingkan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('country-comparisons.index') }}" class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-bold">Negara Pertama</label>
                <select name="country1_id" class="form-select" required>
                    <option value="">-- Pilih Negara 1 --</option>
                    @foreach($countries as $c)<option value="{{ $c->id }}" {{ request('country1_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>@endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold">Negara Kedua</label>
                <select name="country2_id" class="form-select" required>
                    <option value="">-- Pilih Negara 2 --</option>
                    @foreach($countries as $c)<option value="{{ $c->id }}" {{ request('country2_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>@endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-code-compare me-1"></i>Bandingkan</button>
            </div>
        </form>
    </div>
</div>

@if($comparisonData)
<div class="row g-4">
    <!-- Country 1 Card -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-top border-4 border-primary">
            <div class="card-body text-center p-4">
                <h4 class="fw-bold text-primary">{{ $comparisonData['country1']['model']->name }}</h4>
                <p class="text-muted">{{ $comparisonData['country1']['model']->region }} | Capital: {{ $comparisonData['country1']['model']->capital }}</p>
                <hr>
                <div class="mb-3">
                    <span class="text-muted d-block small">Overall Risk Score</span>
                    <span class="fs-2 fw-bold text-dark">{{ $comparisonData['country1']['risk_score'] }}</span>
                    <div><span class="badge {{ $comparisonData['country1']['risk_category'] == 'High' ? 'badge-risk-high' : 'badge-risk-low' }}">{{ $comparisonData['country1']['risk_category'] }} Risk</span></div>
                </div>
                <div class="row text-start mt-4 bg-light p-3 rounded-3">
                    <div class="col-6 mb-2"><strong>GDP:</strong> ${{ number_format($comparisonData['country1']['gdp'] / 1e9, 2) }}B</div>
                    <div class="col-6 mb-2"><strong>Inflation:</strong> {{ $comparisonData['country1']['inflation'] }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Country 2 Card -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-top border-4 border-danger">
            <div class="card-body text-center p-4">
                <h4 class="fw-bold text-danger">{{ $comparisonData['country2']['model']->name }}</h4>
                <p class="text-muted">{{ $comparisonData['country2']['model']->region }} | Capital: {{ $comparisonData['country2']['model']->capital }}</p>
                <hr>
                <div class="mb-3">
                    <span class="text-muted d-block small">Overall Risk Score</span>
                    <span class="fs-2 fw-bold text-dark">{{ $comparisonData['country2']['risk_score'] }}</span>
                    <div><span class="badge {{ $comparisonData['country2']['risk_category'] == 'High' ? 'badge-risk-high' : 'badge-risk-low' }}">{{ $comparisonData['country2']['risk_category'] }} Risk</span></div>
                </div>
                <div class="row text-start mt-4 bg-light p-3 rounded-3">
                    <div class="col-6 mb-2"><strong>GDP:</strong> ${{ number_format($comparisonData['country2']['gdp'] / 1e9, 2) }}B</div>
                    <div class="col-6 mb-2"><strong>Inflation:</strong> {{ $comparisonData['country2']['inflation'] }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
