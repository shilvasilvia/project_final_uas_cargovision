<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $query = Port::with('country');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        $ports = $query->latest()->paginate(10);
        $countries = Country::all();

        return view('ports.index', compact('ports', 'countries'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('ports.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        Port::create($validated);

        return redirect()->route('ports.index')->with('success', 'Pelabuhan berhasil ditambahkan.');
    }

    public function show(Port $port)
    {
        $port->load(['country', 'originShipments', 'destinationShipments']);
        return view('ports.show', compact('port'));
    }

    public function edit(Port $port)
    {
        $countries = Country::all();
        return view('ports.edit', compact('port', 'countries'));
    }

    public function update(Request $request, Port $port)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        $port->update($validated);

        return redirect()->route('ports.index')->with('success', 'Pelabuhan berhasil diperbarui.');
    }

    public function destroy(Port $port)
    {
        $port->delete();
        return redirect()->route('ports.index')->with('success', 'Pelabuhan berhasil dihapus.');
    }
}
