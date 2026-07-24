<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\RiskScore;
use App\Services\RiskCalculationService;
use Illuminate\Http\Request;

class RiskScoreApiController extends Controller
{
    public function index(Request $request)
    {
        $query = RiskScore::with('country');

        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->has('risk_category')) {
            $query->where('risk_category', $request->risk_category);
        }

        $scores = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar skor risiko berhasil diambil',
            'data' => $scores
        ]);
    }

    public function show($id)
    {
        $riskScore = RiskScore::with('country')->find($id);

        if (!$riskScore) {
            return response()->json([
                'success' => false,
                'message' => 'Data skor risiko tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail skor risiko',
            'data' => $riskScore
        ]);
    }

    public function calculate(Request $request, RiskCalculationService $calculator)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $country = Country::findOrFail($request->country_id);
        $calculatedData = $calculator->calculateForCountry($country);

        $riskScore = RiskScore::updateOrCreate(
            ['country_id' => $country->id],
            $calculatedData
        );

        return response()->json([
            'success' => true,
            'message' => 'Kalkulasi skor risiko berhasil dihitung ulang',
            'data' => $riskScore->load('country')
        ]);
    }
}
