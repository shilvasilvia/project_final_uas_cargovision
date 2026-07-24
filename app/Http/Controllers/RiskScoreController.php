<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;
use App\Models\Country;
use App\Services\RiskCalculationService;
use Illuminate\Http\Request;

class RiskScoreController extends Controller
{
    public function index(Request $request)
    {
        $query = RiskScore::with('country');

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('risk_category')) {
            $query->where('risk_category', $request->risk_category);
        }

        $riskScores = $query->orderBy('overall_score', 'desc')->paginate(10);
        $countries = Country::all();

        return view('risk-scores.index', compact('riskScores', 'countries'));
    }

    public function calculate(Request $request, RiskCalculationService $calculator)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $country = Country::findOrFail($request->country_id);
        $calculated = $calculator->calculateForCountry($country);

        RiskScore::updateOrCreate(
            ['country_id' => $country->id],
            $calculated
        );

        return redirect()->route('risk-scores.index')->with('success', 'Skor risiko negara ' . $country->name . ' berhasil dihitung ulang.');
    }

    public function show(RiskScore $riskScore)
    {
        $riskScore->load('country');
        return view('risk-scores.show', compact('riskScore'));
    }
}
