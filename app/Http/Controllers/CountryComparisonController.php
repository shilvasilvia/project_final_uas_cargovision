<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Services\WorldBankService;
use Illuminate\Http\Request;

class CountryComparisonController extends Controller
{
    public function index(Request $request, WorldBankService $worldBank)
    {
        $countries = Country::all();
        $country1 = null;
        $country2 = null;
        $comparisonData = null;

        if ($request->filled('country1_id') && $request->filled('country2_id')) {
            $c1 = Country::find($request->country1_id);
            $c2 = Country::find($request->country2_id);

            if ($c1 && $c2) {
                $risk1 = RiskScore::where('country_id', $c1->id)->first();
                $risk2 = RiskScore::where('country_id', $c2->id)->first();

                $comparisonData = [
                    'country1' => [
                        'model' => $c1,
                        'gdp' => $worldBank->getGDP($c1->code),
                        'inflation' => $worldBank->getInflation($c1->code),
                        'risk_score' => $risk1->overall_score ?? 35.0,
                        'risk_category' => $risk1->risk_category ?? 'Low',
                    ],
                    'country2' => [
                        'model' => $c2,
                        'gdp' => $worldBank->getGDP($c2->code),
                        'inflation' => $worldBank->getInflation($c2->code),
                        'risk_score' => $risk2->overall_score ?? 65.0,
                        'risk_category' => $risk2->risk_category ?? 'Medium',
                    ]
                ];
            }
        }

        return view('country-comparisons.index', compact('countries', 'comparisonData'));
    }
}
