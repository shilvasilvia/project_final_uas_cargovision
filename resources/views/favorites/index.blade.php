@extends('layouts.app')

@section('title', 'Favorite Monitoring - Pengawasan Pribadi')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-star me-2 text-warning"></i>Daftar Favorite Monitoring Pribadi Anda</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tipe Fitur</th>
                        <th>Rincian Item Terfavorit</th>
                        <th>Waktu Ditambahkan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($favorites as $fav)
                        <tr>
                            <td>
                                <span class="badge bg-primary">
                                    {{ class_basename($fav->favoritable_type) }}
                                </span>
                            </td>
                            <td class="fw-bold">
                                @if($fav->favoritable)
                                    {{ $fav->favoritable->name ?? $fav->favoritable->shipment_number ?? $fav->favoritable->title ?? 'Item' }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $fav->created_at->diffForHumans() }}</small></td>
                            <td class="text-end">
                                <form action="{{ route('favorites.toggle') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ strtolower(class_basename($fav->favoritable_type)) }}">
                                    <input type="hidden" name="id" value="{{ $fav->favoritable_id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-star-half-stroke me-1"></i>Hapus Favorit</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada item monitoring favorit. Tekan tombol ⭐ Favorit di halaman Countries, Ports, atau Shipments.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
