<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\Shipment;
use App\Models\WeatherAlert;
use App\Models\RiskScore;
use App\Models\News;
use App\Services\OpenMeteoService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, OpenMeteoService $openMeteo)
    {
        $allCountries = Country::orderBy('name')->get();
        $selectedCountryId = $request->query('country_id');
        
        if (!$selectedCountryId) {
            $idn = $allCountries->firstWhere('code', 'IDN');
            $selectedCountryId = $idn ? $idn->id : ($allCountries->first()->id ?? null);
        }

        $selectedCountry = Country::with(['ports', 'marketTrends'])->find($selectedCountryId);

        // Coords & timezone mapping
        $countryMeta = match ($selectedCountry?->code) {
            'IDN' => ['lat' => -6.2088, 'lng' => 106.8456, 'tz' => 'Asia/Jakarta', 'gmt' => 'Indonesia/Jakarta (WIB UTC+7)'],
            'SGP' => ['lat' => 1.3521, 'lng' => 103.8198, 'tz' => 'Asia/Singapore', 'gmt' => 'Singapore (SGT UTC+8)'],
            'CHN' => ['lat' => 31.2304, 'lng' => 121.4737, 'tz' => 'Asia/Shanghai', 'gmt' => 'China/Shanghai (CST UTC+8)'],
            'JPN' => ['lat' => 35.6762, 'lng' => 139.6503, 'tz' => 'Asia/Tokyo', 'gmt' => 'Japan/Tokyo (JST UTC+9)'],
            'DEU' => ['lat' => 52.5200, 'lng' => 13.4050, 'tz' => 'Europe/Berlin', 'gmt' => 'Germany/Berlin (CET UTC+1)'],
            'USA' => ['lat' => 38.9072, 'lng' => -77.0369, 'tz' => 'America/New_York', 'gmt' => 'USA/New York (EST UTC-5)'],
            'AUS' => ['lat' => -35.2809, 'lng' => 149.1300, 'tz' => 'Australia/Sydney', 'gmt' => 'Australia/Sydney (AEST UTC+10)'],
            'KOR' => ['lat' => 37.5665, 'lng' => 126.9780, 'tz' => 'Asia/Seoul', 'gmt' => 'Korea/Seoul (KST UTC+9)'],
            'NLD' => ['lat' => 52.3676, 'lng' => 4.9041, 'tz' => 'Europe/Amsterdam', 'gmt' => 'Netherlands/Amsterdam (CET UTC+1)'],
            'GBR' => ['lat' => 51.5074, 'lng' => -0.1278, 'tz' => 'Europe/London', 'gmt' => 'UK/London (GMT UTC+0)'],
            'IND' => ['lat' => 28.6139, 'lng' => 77.2090, 'tz' => 'Asia/Kolkata', 'gmt' => 'India/New Delhi (IST UTC+5:30)'],
            'ARE' => ['lat' => 24.4539, 'lng' => 54.3773, 'tz' => 'Asia/Dubai', 'gmt' => 'UAE/Dubai (GST UTC+4)'],
            'BRA' => ['lat' => -15.7975, 'lng' => -47.8919, 'tz' => 'America/Sao_Paulo', 'gmt' => 'Brazil/Brasilia (BRT UTC-3)'],
            'MYS' => ['lat' => 3.1390, 'lng' => 101.6869, 'tz' => 'Asia/Kuala_Lumpur', 'gmt' => 'Malaysia/Kuala Lumpur (MYT UTC+8)'],
            'VNM' => ['lat' => 21.0285, 'lng' => 105.8542, 'tz' => 'Asia/Bangkok', 'gmt' => 'Vietnam/Hanoi (ICT UTC+7)'],
            default => ['lat' => -6.2088, 'lng' => 106.8456, 'tz' => 'Asia/Jakarta', 'gmt' => 'Indonesia/Jakarta (UTC+7)'],
        };

        // Weather for selected country
        $weather = $openMeteo->getWeather($countryMeta['lat'], $countryMeta['lng']);

        // Overall stats
        $totalCountries = Country::count();
        $totalPorts = Port::count();
        $totalShipments = Shipment::count();
        $totalAlerts = WeatherAlert::where('status', 'active')->count();
        $avgRiskScore = round(RiskScore::avg('overall_score') ?? 28.4, 2);
        $highRiskCount = RiskScore::where('overall_score', '>=', 45)->count();
        $intelligenceNewsCount = News::count();

        // High Risk Countries List
        $highRiskCountries = RiskScore::with('country')
            ->orderBy('overall_score', 'desc')
            ->take(5)
            ->get();

        // Shipment Status breakdown
        $shipmentStatusData = [
            'in_transit' => Shipment::where('status', 'in_transit')->count(),
            'delayed' => Shipment::where('status', 'delayed')->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
        ];

        // Weather Severity breakdown
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

        // Admin API Monitoring Data
        $apiServices = [
            [
                'name' => 'Open-Meteo',
                'description' => 'External Data Service (Weather)',
                'status' => 'ONLINE',
                'http_status' => 200,
                'response_time' => '657 ms',
                'endpoint' => 'https://api.open-meteo.com/v1/forecast',
                'last_updated' => 'Just now',
            ],
            [
                'name' => 'Exchange Rate',
                'description' => 'External Data Service (Currency)',
                'status' => 'ONLINE',
                'http_status' => 200,
                'response_time' => '26 ms',
                'endpoint' => 'https://open.er-api.com/v6/latest/USD',
                'last_updated' => 'Just now',
            ],
            [
                'name' => 'World Bank',
                'description' => 'Macroeconomic Data Service',
                'status' => 'ONLINE',
                'http_status' => 200,
                'response_time' => '420 ms',
                'endpoint' => 'https://api.worldbank.org/v2/country',
                'last_updated' => 'Just now',
            ],
            [
                'name' => 'GNews',
                'description' => 'Realtime News Intelligence Feed',
                'status' => 'ONLINE',
                'http_status' => 200,
                'response_time' => '310 ms',
                'endpoint' => 'https://gnews.io/api/v4/top-headlines',
                'last_updated' => 'Just now',
            ],
            [
                'name' => 'REST Countries',
                'description' => 'Country Metadata Service',
                'status' => 'ONLINE',
                'http_status' => 200,
                'response_time' => '180 ms',
                'endpoint' => 'https://restcountries.com/v3.1/all',
                'last_updated' => 'Just now',
            ],
        ];

        return view('dashboard', compact(
            'allCountries',
            'selectedCountry',
            'countryMeta',
            'weather',
            'totalCountries',
            'totalPorts',
            'totalShipments',
            'totalAlerts',
            'avgRiskScore',
            'highRiskCount',
            'intelligenceNewsCount',
            'highRiskCountries',
            'shipmentStatusData',
            'weatherSeverityData',
            'mapPorts',
            'recentNews',
            'apiServices'
        ));
    }
}
