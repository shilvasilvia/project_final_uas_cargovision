<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\OpenMeteoService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::with('ports');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
        }

        $countries = $query->latest()->paginate(10);
        return view('countries.index', compact('countries'));
    }

    public function create()
    {
        return view('countries.create');
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

        Country::create($validated);

        return redirect()->route('countries.index')->with('success', 'Negara berhasil ditambahkan.');
    }

    public function show(Country $country, OpenMeteoService $openMeteo)
    {
        $country->load(['ports', 'marketTrends']);

        // Default coordinate mapping for Open-Meteo Weather & Day.js Timezone
        $coords = match ($country->code) {
            'IDN' => ['lat' => -6.2088, 'lng' => 106.8456, 'tz' => 'Asia/Jakarta', 'gmt' => 'WIB (UTC+7)'],
            'SGP' => ['lat' => 1.3521, 'lng' => 103.8198, 'tz' => 'Asia/Singapore', 'gmt' => 'SGT (UTC+8)'],
            'CHN' => ['lat' => 31.2304, 'lng' => 121.4737, 'tz' => 'Asia/Shanghai', 'gmt' => 'CST (UTC+8)'],
            'JPN' => ['lat' => 35.6762, 'lng' => 139.6503, 'tz' => 'Asia/Tokyo', 'gmt' => 'JST (UTC+9)'],
            'DEU' => ['lat' => 52.5200, 'lng' => 13.4050, 'tz' => 'Europe/Berlin', 'gmt' => 'CET (UTC+1)'],
            'USA' => ['lat' => 38.9072, 'lng' => -77.0369, 'tz' => 'America/New_York', 'gmt' => 'EST (UTC-5)'],
            'AUS' => ['lat' => -35.2809, 'lng' => 149.1300, 'tz' => 'Australia/Sydney', 'gmt' => 'AEST (UTC+10)'],
            'KOR' => ['lat' => 37.5665, 'lng' => 126.9780, 'tz' => 'Asia/Seoul', 'gmt' => 'KST (UTC+9)'],
            'NLD' => ['lat' => 52.3676, 'lng' => 4.9041, 'tz' => 'Europe/Amsterdam', 'gmt' => 'CET (UTC+1)'],
            'GBR' => ['lat' => 51.5074, 'lng' => -0.1278, 'tz' => 'Europe/London', 'gmt' => 'GMT (UTC+0)'],
            'IND' => ['lat' => 28.6139, 'lng' => 77.2090, 'tz' => 'Asia/Kolkata', 'gmt' => 'IST (UTC+5:30)'],
            'ARE' => ['lat' => 24.4539, 'lng' => 54.3773, 'tz' => 'Asia/Dubai', 'gmt' => 'GST (UTC+4)'],
            'BRA' => ['lat' => -15.7975, 'lng' => -47.8919, 'tz' => 'America/Sao_Paulo', 'gmt' => 'BRT (UTC-3)'],
            'MYS' => ['lat' => 3.1390, 'lng' => 101.6869, 'tz' => 'Asia/Kuala_Lumpur', 'gmt' => 'MYT (UTC+8)'],
            'VNM' => ['lat' => 21.0285, 'lng' => 105.8542, 'tz' => 'Asia/Bangkok', 'gmt' => 'ICT (UTC+7)'],
            default => ['lat' => 0.0, 'lng' => 0.0, 'tz' => 'UTC', 'gmt' => 'UTC+0'],
        };

        // Fetch Live Weather from Open-Meteo
        $weather = $openMeteo->getWeather($coords['lat'], $coords['lng']);

        return view('countries.show', compact('country', 'weather', 'coords'));
    }

    public function edit(Country $country)
    {
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:countries,code,' . $country->id,
            'capital' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'population' => 'nullable|integer',
        ]);

        $country->update($validated);

        return redirect()->route('countries.index')->with('success', 'Negara berhasil diperbarui.');
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('countries.index')->with('success', 'Negara berhasil dihapus.');
    }
}
