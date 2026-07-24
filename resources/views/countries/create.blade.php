@extends('layouts.app')

@section('title', 'Tambah Negara Baru')

@section('content')
<div class="card border-0 shadow-sm col-md-8 mx-auto">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-plus me-2 text-primary"></i>Form Tambah Negara</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('countries.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Negara *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Indonesia">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@errorEnd
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kode Negara (3 Karakter) *</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required placeholder="IDN, SGP, USA">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@errorEnd
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Ibu Kota</label>
                    <input type="text" name="capital" class="form-control" value="{{ old('capital') }}" placeholder="Jakarta">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Region</label>
                    <input type="text" name="region" class="form-control" value="{{ old('region') }}" placeholder="Southeast Asia">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Populasi</label>
                    <input type="number" name="population" class="form-control" value="{{ old('population') }}" placeholder="275000000">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Negara</button>
            </div>
        </form>
    </div>
</div>
@endsection
