@extends('layouts.app')

@section('title', 'Input Berita Baru')

@section('content')
<div class="card border-0 shadow-sm col-md-8 mx-auto">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-plus me-2 text-danger"></i>Form Input Berita Supply Chain</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('news.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Negara *</label>
                    <select name="country_id" class="form-select" required>
                        <option value="">-- Pilih Negara Terkait --</option>
                        @foreach($countries as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kategori *</label>
                    <input type="text" name="category" class="form-control" required placeholder="Logistics, Port Operations, Geopolitics">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Judul Berita *</label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Port congestion and tariff disputes slow maritime trade">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal Terbit *</label>
                <input type="date" name="published_date" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Konten Berita *</label>
                <textarea name="content" class="form-control" rows="5" required placeholder="Tuliskan isi berita di sini... Sistem akan mendeteksi sentimen (Positive / Negative) secara otomatis."></textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan & Analisis Sentimen</button>
            </div>
        </form>
    </div>
</div>
@endsection
