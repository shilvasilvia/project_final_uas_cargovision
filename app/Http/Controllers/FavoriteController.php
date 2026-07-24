<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Country;
use App\Models\Shipment;
use App\Models\Port;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('favoritable')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:country,shipment,port',
            'id' => 'required|integer',
        ]);

        $modelClass = match ($validated['type']) {
            'country' => Country::class,
            'shipment' => Shipment::class,
            'port' => Port::class,
        };

        $existing = Favorite::where('user_id', Auth::id())
            ->where('favoritable_type', $modelClass)
            ->where('favoritable_id', $validated['id'])
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Item berhasil dihapus dari Favorite Monitoring.';
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'favoritable_type' => $modelClass,
                'favoritable_id' => $validated['id'],
            ]);
            $message = 'Item berhasil ditambahkan ke Favorite Monitoring pribadi Anda.';
        }

        return back()->with('success', $message);
    }
}
