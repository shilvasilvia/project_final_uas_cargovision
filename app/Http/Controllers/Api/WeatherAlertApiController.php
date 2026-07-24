<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WeatherAlert;
use Illuminate\Http\Request;

class WeatherAlertApiController extends Controller
{
    public function index(Request $request)
    {
        $query = WeatherAlert::with(['country', 'port']);

        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        $alerts = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar peringatan cuaca berhasil diambil',
            'data' => $alerts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_id' => 'nullable|exists:ports,id',
            'title' => 'required|string|max:255',
            'event_type' => 'required|string|max:255',
            'severity' => 'required|string',
            'description' => 'nullable|string',
            'alert_date' => 'required|date',
            'status' => 'nullable|string',
        ]);

        $alert = WeatherAlert::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Peringatan cuaca berhasil dibuat',
            'data' => $alert->load(['country', 'port'])
        ], 201);
    }

    public function show($id)
    {
        $alert = WeatherAlert::with(['country', 'port'])->find($id);

        if (!$alert) {
            return response()->json([
                'success' => false,
                'message' => 'Peringatan cuaca tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail peringatan cuaca',
            'data' => $alert
        ]);
    }

    public function update(Request $request, $id)
    {
        $alert = WeatherAlert::find($id);

        if (!$alert) {
            return response()->json([
                'success' => false,
                'message' => 'Peringatan cuaca tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'country_id' => 'sometimes|exists:countries,id',
            'port_id' => 'nullable|exists:ports,id',
            'title' => 'sometimes|string|max:255',
            'event_type' => 'sometimes|string|max:255',
            'severity' => 'sometimes|string',
            'description' => 'nullable|string',
            'alert_date' => 'sometimes|date',
            'status' => 'nullable|string',
        ]);

        $alert->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Peringatan cuaca berhasil diperbarui',
            'data' => $alert->load(['country', 'port'])
        ]);
    }

    public function destroy($id)
    {
        $alert = WeatherAlert::find($id);

        if (!$alert) {
            return response()->json([
                'success' => false,
                'message' => 'Peringatan cuaca tidak ditemukan'
            ], 404);
        }

        $alert->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peringatan cuaca berhasil dihapus'
        ]);
    }
}
