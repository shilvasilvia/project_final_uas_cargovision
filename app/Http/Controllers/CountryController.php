<?php

namespace App\Http\Controllers;

use App\Models\Country;
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

    public function show(Country $country)
    {
        $country->load('ports');
        return view('countries.show', compact('country'));
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
