<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Country;
use App\Models\Port;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with(['originCountry', 'destinationCountry', 'originPort', 'destinationPort']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('shipment_number', 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        $shipments = $query->latest()->paginate(10);
        $countries = Country::all();
        $ports = Port::all();

        return view('shipments.index', compact('shipments', 'countries', 'ports'));
    }

    public function create()
    {
        $countries = Country::all();
        $ports = Port::all();
        return view('shipments.create', compact('countries', 'ports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_number' => 'required|string|unique:shipments,shipment_number',
            'origin_country_id' => 'required|exists:countries,id',
            'destination_country_id' => 'required|exists:countries,id',
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
            'status' => 'required|string',
            'departure_date' => 'required|date',
            'estimated_arrival' => 'required|date',
            'actual_arrival' => 'nullable|date',
            'cargo_type' => 'required|string',
            'risk_level' => 'required|string',
        ]);

        Shipment::create($validated);

        return redirect()->route('shipments.index')->with('success', 'Pengiriman berhasil didaftarkan.');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['originCountry', 'destinationCountry', 'originPort', 'destinationPort']);
        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $countries = Country::all();
        $ports = Port::all();
        return view('shipments.edit', compact('shipment', 'countries', 'ports'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'shipment_number' => 'required|string|unique:shipments,shipment_number,' . $shipment->id,
            'origin_country_id' => 'required|exists:countries,id',
            'destination_country_id' => 'required|exists:countries,id',
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
            'status' => 'required|string',
            'departure_date' => 'required|date',
            'estimated_arrival' => 'required|date',
            'actual_arrival' => 'nullable|date',
            'cargo_type' => 'required|string',
            'risk_level' => 'required|string',
        ]);

        $shipment->update($validated);

        return redirect()->route('shipments.index')->with('success', 'Pengiriman berhasil diperbarui.');
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return redirect()->route('shipments.index')->with('success', 'Pengiriman berhasil dihapus.');
    }
}
