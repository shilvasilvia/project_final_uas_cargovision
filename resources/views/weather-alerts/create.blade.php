@extends('layouts.app')

@section('title', 'Buat Weather Alert Baru')

@section('content')
<div class="card border-0 shadow-sm col-md-8 mx-auto">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-plus me-2 text-warning"></i>Form Peringatan Cuaca</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('weather-alerts.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Negara *</label>
                    <select name="country_id" class="form-select" required>
                        <option value="">-- Pilih Negara --</option>
                        @foreach($countries as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Pelabuhan (Opsional)</label>
                    <select name="port_id" class="form-select">
                        <option value="">-- Semua Pelabuhan --</option>
                        @foreach($ports as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Judul Peringatan *</label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Typhoon Warning in East China Sea">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Tipe Event *</label>
                    <input type="text" name="event_type" class="form-control" required placeholder="Typhoon, Storm, Flood">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Severity Level *</label>
                    <select name="severity" class="form-select">
                        <option value="Critical">Critical</option>
                        <option value="High">High</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Tanggal Alert *</label>
                    <input type="date" name="alert_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi Peringatan</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Rincian mengenai potensi keterlambatan atau cuaca ekstrim..."></textarea>
            </div>

            <input type="hidden" name="status" value="active">

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('weather-alerts.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
                <button type="submit" class="btn btn-warning text-dark fw-bold"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Alert</button>
            </div>
        </form>
    </div>
</div>
@endsection
