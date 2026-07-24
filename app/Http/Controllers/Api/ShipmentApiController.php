<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with(['originCountry', 'destinationCountry', 'originPort', 'destinationPort']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shipment_number', 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
            });
        }

        $shipments = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengiriman (shipments) berhasil diambil',
            'data' => $shipments
        ]);
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
            'risk_level' => 'nullable|string',
        ]);

        $shipment = Shipment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengiriman berhasil didaftarkan',
            'data' => $shipment->load(['originCountry', 'destinationCountry', 'originPort', 'destinationPort'])
        ], 201);
    }

    public function show($id)
    {
        $shipment = Shipment::with(['originCountry', 'destinationCountry', 'originPort', 'destinationPort'])->find($id);

        if (!$shipment) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengiriman tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengiriman',
            'data' => $shipment
        ]);
    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengiriman tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'shipment_number' => 'sometimes|string|unique:shipments,shipment_number,' . $id,
            'origin_country_id' => 'sometimes|exists:countries,id',
            'destination_country_id' => 'sometimes|exists:countries,id',
            'origin_port_id' => 'sometimes|exists:ports,id',
            'destination_port_id' => 'sometimes|exists:ports,id',
            'status' => 'sometimes|string',
            'departure_date' => 'sometimes|date',
            'estimated_arrival' => 'sometimes|date',
            'actual_arrival' => 'nullable|date',
            'cargo_type' => 'sometimes|string',
            'risk_level' => 'nullable|string',
        ]);

        $shipment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pengiriman berhasil diperbarui',
            'data' => $shipment->load(['originCountry', 'destinationCountry', 'originPort', 'destinationPort'])
        ]);
    }

    public function destroy($id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengiriman tidak ditemukan'
            ], 404);
        }

        $shipment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pengiriman berhasil dihapus'
        ]);
    }
}
