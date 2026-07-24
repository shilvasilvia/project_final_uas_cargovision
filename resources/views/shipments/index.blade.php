@extends('layouts.app')

@section('title', 'Operasional - Monitoring Shipments')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-boxes-packing me-2 text-info"></i>Daftar Pengiriman (Shipments)</h6>
        @if(auth()->user() && auth()->user()->isAdmin())
            <a href="{{ route('shipments.create') }}" class="btn btn-info text-white btn-sm"><i class="fa-solid fa-plus me-1"></i>Registrasi Shipment Baru</a>
        @endif
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('shipments.index') }}" class="row g-3 mb-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nomor shipment / jenis kargo..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Status Shipment --</option>
                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="risk_level" class="form-select">
                    <option value="">-- Level Risk --</option>
                    <option value="High" {{ request('risk_level') == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Low" {{ request('risk_level') == 'Low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-info w-100"><i class="fa-solid fa-magnifying-glass me-1"></i>Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No. Shipment</th>
                        <th>Rute (Asal &rarr; Tujuan)</th>
                        <th>Status</th>
                        <th>Departed</th>
                        <th>Est. Arrival</th>
                        <th>Jenis Kargo</th>
                        <th>Risk Level</th>
                        <th class="text-end">Aksi / Favorite</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $s)
                        <tr>
                            <td class="fw-bold text-primary">{{ $s->shipment_number }}</td>
                            <td>
                                {{ $s->originCountry->name ?? '-' }} ({{ $s->originPort->name ?? '' }})
                                <br>&rarr; {{ $s->destinationCountry->name ?? '-' }} ({{ $s->destinationPort->name ?? '' }})
                            </td>
                            <td>
                                <span class="badge {{ $s->status == 'delayed' ? 'bg-danger' : ($s->status == 'delivered' ? 'bg-success' : 'bg-info') }}">
                                    {{ $s->status }}
                                </span>
                            </td>
                            <td>{{ $s->departure_date }}</td>
                            <td>{{ $s->estimated_arrival }}</td>
                            <td>{{ $s->cargo_type }}</td>
                            <td>
                                <span class="badge {{ $s->risk_level == 'High' ? 'badge-risk-high' : 'badge-risk-low' }}">
                                    {{ $s->risk_level }}
                                </span>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('favorites.toggle') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="shipment">
                                    <input type="hidden" name="id" value="{{ $s->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Favorite Monitoring"><i class="fa-solid fa-star"></i></button>
                                </form>
                                @if(auth()->user() && auth()->user()->isAdmin())
                                    <form action="{{ route('shipments.destroy', $s->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Hapus data shipment?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data shipment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $shipments->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
