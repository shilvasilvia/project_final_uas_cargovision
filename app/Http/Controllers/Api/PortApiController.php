<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\Request;

class PortApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Port::with('country');

        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $ports = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar pelabuhan berhasil diambil',
            'data' => $ports
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        $port = Port::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pelabuhan berhasil ditambahkan',
            'data' => $port->load('country')
        ], 201);
    }

    public function show($id)
    {
        $port = Port::with(['country', 'originShipments', 'destinationShipments'])->find($id);

        if (!$port) {
            return response()->json([
                'success' => false,
                'message' => 'Pelabuhan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pelabuhan',
            'data' => $port
        ]);
    }

    public function update(Request $request, $id)
    {
        $port = Port::find($id);

        if (!$port) {
            return response()->json([
                'success' => false,
                'message' => 'Pelabuhan tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'country_id' => 'sometimes|exists:countries,id',
            'name' => 'sometimes|string|max:255',
            'city' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        $port->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pelabuhan berhasil diperbarui',
            'data' => $port->load('country')
        ]);
    }

    public function destroy($id)
    {
        $port = Port::find($id);

        if (!$port) {
            return response()->json([
                'success' => false,
                'message' => 'Pelabuhan tidak ditemukan'
            ], 404);
        }

        $port->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pelabuhan berhasil dihapus'
        ]);
    }
}
