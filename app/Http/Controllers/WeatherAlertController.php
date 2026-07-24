<?php

namespace App\Http\Controllers;

use App\Models\WeatherAlert;
use App\Models\Country;
use App\Models\Port;
use Illuminate\Http\Request;

class WeatherAlertController extends Controller
{
    public function index(Request $request)
    {
        $query = WeatherAlert::with(['country', 'port']);

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        $weatherAlerts = $query->latest()->paginate(10);
        $countries = Country::all();

        return view('weather-alerts.index', compact('weatherAlerts', 'countries'));
    }

    public function create()
    {
        $countries = Country::all();
        $ports = Port::all();
        return view('weather-alerts.create', compact('countries', 'ports'));
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
            'status' => 'required|string',
        ]);

        WeatherAlert::create($validated);

        return redirect()->route('weather-alerts.index')->with('success', 'Peringatan cuaca berhasil ditambahkan.');
    }

    public function show(WeatherAlert $weatherAlert)
    {
        $weatherAlert->load(['country', 'port']);
        return view('weather-alerts.show', compact('weatherAlert'));
    }

    public function edit(WeatherAlert $weatherAlert)
    {
        $countries = Country::all();
        $ports = Port::all();
        return view('weather-alerts.edit', compact('weatherAlert', 'countries', 'ports'));
    }

    public function update(Request $request, WeatherAlert $weatherAlert)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_id' => 'nullable|exists:ports,id',
            'title' => 'required|string|max:255',
            'event_type' => 'required|string|max:255',
            'severity' => 'required|string',
            'description' => 'nullable|string',
            'alert_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $weatherAlert->update($validated);

        return redirect()->route('weather-alerts.index')->with('success', 'Peringatan cuaca berhasil diperbarui.');
    }

    public function destroy(WeatherAlert $weatherAlert)
    {
        $weatherAlert->delete();
        return redirect()->route('weather-alerts.index')->with('success', 'Peringatan cuaca berhasil dihapus.');
    }
}
