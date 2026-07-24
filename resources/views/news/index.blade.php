@extends('layouts.app')

@section('title', 'Operasional - News & Sentiment Analysis')

@section('content')
<div class="mb-4">
    @livewire('realtime-news-component')
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-newspaper me-2 text-danger"></i>Berita Supply Chain & Analisis Sentimen</h6>
        <a href="{{ route('news.create') }}" class="btn btn-danger btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Berita Baru</a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('news.index') }}" class="row g-3 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari judul berita atau konten..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="sentiment" class="form-select">
                    <option value="">-- Filter Sentimen --</option>
                    <option value="Positive" {{ request('sentiment') == 'Positive' ? 'selected' : '' }}>Positive</option>
                    <option value="Negative" {{ request('sentiment') == 'Negative' ? 'selected' : '' }}>Negative</option>
                    <option value="Neutral" {{ request('sentiment') == 'Neutral' ? 'selected' : '' }}>Neutral</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-danger w-100"><i class="fa-solid fa-magnifying-glass me-1"></i>Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Judul Berita</th>
                        <th>Negara</th>
                        <th>Kategori</th>
                        <th>Tanggal Terbit</th>
                        <th>Sentimen Otomatis</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $n)
                        <tr>
                            <td class="fw-bold text-dark">{{ $n->title }}</td>
                            <td>{{ $n->country->name ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $n->category }}</span></td>
                            <td>{{ $n->published_date }}</td>
                            <td>
                                <span class="badge {{ $n->sentiment == 'Negative' ? 'bg-danger' : ($n->sentiment == 'Positive' ? 'bg-success' : 'bg-warning text-dark') }}">
                                    {{ $n->sentiment }}
                                </span>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('news.destroy', $n->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus berita?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada berita.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $news->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
