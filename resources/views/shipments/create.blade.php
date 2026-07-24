@extends('layouts.app')

@section('title', 'Registrasi Shipment Baru')

@section('content')
<div class="card border-0 shadow-sm col-md-8 mx-auto">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-plus me-2 text-info"></i>Form Registrasi Shipment</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('shipments.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Nomor Shipment / Tracking *</label>
                <input type="text" name="shipment_number" class="form-control" required placeholder="SHP-2026-003">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Negara Asal *</label>
                    <select name="origin_country_id" class="form-select" required>
                        <option value="">-- Pilih Asal --</option>
                        @foreach($countries as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Pelabuhan Asal *</label>
                    <select name="origin_port_id" class="form-select" required>
                        <option value="">-- Pilih Port Asal --</option>
                        @foreach($ports as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Negara Tujuan *</label>
                    <select name="destination_country_id" class="form-select" required>
                        <option value="">-- Pilih Tujuan --</option>
                        @foreach($countries as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Pelabuhan Tujuan *</label>
                    <select name="destination_port_id" class="form-select" required>
                        <option value="">-- Pilih Port Tujuan --</option>
                        @foreach($ports as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Keberangkatan *</label>
                    <input type="date" name="departure_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Estimasi Kedatangan *</label>
                    <input type="date" name="estimated_arrival" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jenis Kargo *</label>
                    <input type="text" name="cargo_type" class="form-control" required placeholder="Electronics, Textiles, Raw Materials">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Status *</label>
                    <select name="status" class="form-select">
                        <option value="in_transit">In Transit</option>
                        <option value="delayed">Delayed</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Risk Level *</label>
                    <select name="risk_level" class="form-select">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
                <button type="submit" class="btn btn-info text-white"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Shipment</button>
            </div>
        </form>
    </div>
</div>
@endsection
