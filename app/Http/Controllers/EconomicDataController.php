<?php

namespace App\Http\Controllers;

use App\Models\EconomicData;
use App\Models\Country;
use App\Services\WorldBankService;
use Illuminate\Http\Request;

class EconomicDataController extends Controller
{
    public function index(Request $request)
    {
        $query = EconomicData::with('country');

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        $economicData = $query->latest()->paginate(10);
        $countries = Country::all();

        return view('economic-data.index', compact('economicData', 'countries'));
    }

    public function store(Request $request, WorldBankService $worldBank)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'year' => 'required|integer',
        ]);

        $country = Country::findOrFail($validated['country_id']);

        $gdp = $worldBank->getGDP($country->code);
        $inflation = $worldBank->getInflation($country->code);
        $pop = $worldBank->getPopulation($country->code);
        $exports = $worldBank->getExport($country->code);
        $imports = $worldBank->getImport($country->code);

        $record = EconomicData::updateOrCreate(
            ['country_id' => $country->id, 'year' => $validated['year']],
            [
                'gdp' => $gdp,
                'inflation_rate' => $inflation,
                'population' => $pop,
                'exports_usd' => $exports,
                'imports_usd' => $imports,
            ]
        );

        return redirect()->route('economic-data.index')->with('success', 'Data ekonomi berhasil disinkronkan dari World Bank API untuk negara ' . $country->name);
    }
}
