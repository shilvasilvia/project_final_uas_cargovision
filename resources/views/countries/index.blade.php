@extends('layouts.app')

@section('title', 'Master Data - Countries')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-globe me-2 text-primary"></i>Daftar Negara</h6>
        @if(auth()->user() && auth()->user()->isAdmin())
            <a href="{{ route('countries.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Negara</a>
        @endif
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('countries.index') }}" class="row g-3 mb-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Cari nama negara, kode (IDN, JPN), atau region..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="fa-solid fa-magnifying-glass me-1"></i>Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Negara</th>
                        <th>Ibu Kota</th>
                        <th>Region</th>
                        <th>Populasi</th>
                        <th>Total Ports</th>
                        <th class="text-end">Aksi / Favorite</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($countries as $c)
                        <tr>
                            <td><span class="badge bg-dark">{{ $c->code }}</span></td>
                            <td class="fw-bold">{{ $c->name }}</td>
                            <td>{{ $c->capital ?? '-' }}</td>
                            <td>{{ $c->region ?? '-' }}</td>
                            <td>{{ $c->population ? number_format($c->population) : '-' }}</td>
                            <td><span class="badge bg-info">{{ $c->ports->count() }} Ports</span></td>
                            <td class="text-end">
                                <form action="{{ route('favorites.toggle') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="country">
                                    <input type="hidden" name="id" value="{{ $c->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Tambah/Hapus Favorite Monitoring"><i class="fa-solid fa-star"></i></button>
                                </form>
                                @if(auth()->user() && auth()->user()->isAdmin())
                                    <form action="{{ route('countries.destroy', $c->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Yakin ingin menghapus negara ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data negara.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $countries->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
