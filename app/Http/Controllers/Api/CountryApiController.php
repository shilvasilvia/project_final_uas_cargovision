<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::with('ports');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }

        $countries = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar negara berhasil diambil',
            'data' => $countries
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:countries,code',
            'capital' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'population' => 'nullable|integer',
        ]);

        $country = Country::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Negara berhasil ditambahkan',
            'data' => $country
        ], 201);
    }

    public function show($id)
    {
        $country = Country::with(['ports'])->find($id);

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Data negara tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail negara',
            'data' => $country
        ]);
    }

    public function update(Request $request, $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Data negara tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:10|unique:countries,code,' . $id,
            'capital' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'population' => 'nullable|integer',
        ]);

        $country->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data negara berhasil diperbarui',
            'data' => $country
        ]);
    }

    public function destroy($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Data negara tidak ditemukan'
            ], 404);
        }

        $country->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data negara berhasil dihapus'
        ]);
    }
}
