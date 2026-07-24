@extends('layouts.app')

@section('title', 'Master Data - Global Ports')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-anchor me-2 text-success"></i>Daftar Pelabuhan (Ports)</h6>
        <a href="{{ route('ports.create') }}" class="btn btn-success btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Pelabuhan</a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('ports.index') }}" class="row g-3 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari pelabuhan atau kota..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="country_id" class="form-select">
                    <option value="">-- Filter Negara --</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}" {{ request('country_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-success w-100"><i class="fa-solid fa-magnifying-glass me-1"></i>Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pelabuhan</th>
                        <th>Negara</th>
                        <th>Kota</th>
                        <th>Kordinat (Lat, Long)</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ports as $p)
                        <tr>
                            <td class="fw-bold">{{ $p->name }}</td>
                            <td>{{ $p->country->name ?? '-' }}</td>
                            <td>{{ $p->city ?? '-' }}</td>
                            <td><small class="font-monospace">{{ $p->latitude ?? '0' }}, {{ $p->longitude ?? '0' }}</small></td>
                            <td><span class="badge bg-success">{{ $p->status }}</span></td>
                            <td class="text-end">
                                <form action="{{ route('ports.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelabuhan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data pelabuhan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $ports->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
