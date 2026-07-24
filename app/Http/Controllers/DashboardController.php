<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\Shipment;
use App\Models\WeatherAlert;
use App\Models\RiskScore;
use App\Models\News;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCountries = Country::count();
        $totalPorts = Port::count();
        $totalShipments = Shipment::count();
        $totalAlerts = WeatherAlert::where('status', 'active')->count();

        // High Risk Countries
        $highRiskCountries = RiskScore::with('country')
            ->orderBy('overall_score', 'desc')
            ->take(5)
            ->get();

        // Chart 1: Shipments by Status
        $shipmentStatusData = [
            'in_transit' => Shipment::where('status', 'in_transit')->count(),
            'delayed' => Shipment::where('status', 'delayed')->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
        ];

        // Chart 2: Weather Alerts by Severity
        $weatherSeverityData = [
            'Critical' => WeatherAlert::where('severity', 'Critical')->count(),
            'High' => WeatherAlert::where('severity', 'High')->count(),
            'Moderate' => WeatherAlert::where('severity', 'Moderate')->count(),
            'Low' => WeatherAlert::where('severity', 'Low')->count(),
        ];

        // Ports for Leaflet Map
        $mapPorts = Port::with('country')->whereNotNull('latitude')->whereNotNull('longitude')->get();

        // Recent News
        $recentNews = News::with('country')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalCountries',
            'totalPorts',
            'totalShipments',
            'totalAlerts',
            'highRiskCountries',
            'shipmentStatusData',
            'weatherSeverityData',
            'mapPorts',
            'recentNews'
        ));
    }
}
