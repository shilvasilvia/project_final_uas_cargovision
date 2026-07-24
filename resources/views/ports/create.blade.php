@extends('layouts.app')

@section('title', 'Tambah Pelabuhan Baru')

@section('content')
<div class="card border-0 shadow-sm col-md-8 mx-auto">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-plus me-2 text-success"></i>Form Tambah Pelabuhan</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('ports.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Negara *</label>
                <select name="country_id" class="form-select" required>
                    <option value="">-- Pilih Negara --</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Pelabuhan *</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Port of Tanjung Priok">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Kota</label>
                <input type="text" name="city" class="form-control" placeholder="Jakarta">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Latitude</label>
                    <input type="number" step="any" name="latitude" class="form-control" placeholder="-6.1033">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Longitude</label>
                    <input type="number" step="any" name="longitude" class="form-control" placeholder="106.8797">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Status *</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="congested">Congested</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('ports.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Pelabuhan</button>
            </div>
        </form>
    </div>
</div>
@endsection
