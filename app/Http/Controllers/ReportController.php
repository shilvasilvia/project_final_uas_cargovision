<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\WeatherAlert;
use App\Models\RiskScore;
use App\Models\Country;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::all();

        $queryShipments = Shipment::with(['originCountry', 'destinationCountry']);
        $queryWeather = WeatherAlert::with(['country', 'port']);
        $queryRisk = RiskScore::with('country');

        if ($request->filled('country_id')) {
            $queryShipments->where('origin_country_id', $request->country_id)
                           ->orWhere('destination_country_id', $request->country_id);
            $queryWeather->where('country_id', $request->country_id);
            $queryRisk->where('country_id', $request->country_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $queryShipments->whereBetween('departure_date', [$request->start_date, $request->end_date]);
            $queryWeather->whereBetween('alert_date', [$request->start_date, $request->end_date]);
        }

        $shipments = $queryShipments->latest()->take(20)->get();
        $weatherAlerts = $queryWeather->latest()->take(20)->get();
        $riskScores = $queryRisk->latest()->take(20)->get();

        return view('reports.index', compact('countries', 'shipments', 'weatherAlerts', 'riskScores'));
    }

    public function exportPdf(Request $request)
    {
        $shipments = Shipment::with(['originCountry', 'destinationCountry'])->latest()->get();
        $weatherAlerts = WeatherAlert::with('country')->latest()->get();
        $riskScores = RiskScore::with('country')->latest()->get();

        $pdf = Pdf::loadView('reports.pdf', compact('shipments', 'weatherAlerts', 'riskScores'));

        return $pdf->download('laporan-risk-intelligence-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $shipments = Shipment::with(['originCountry', 'destinationCountry'])->latest()->get();

        $csvHeader = ["No. Shipment", "Asal", "Tujuan", "Status", "Departed", "Est. Arrival", "Cargo", "Risk Level"];
        $csvData = [];
        $csvData[] = implode(',', $csvHeader);

        foreach ($shipments as $s) {
            $csvData[] = implode(',', [
                '"' . $s->shipment_number . '"',
                '"' . ($s->originCountry->name ?? '-') . '"',
                '"' . ($s->destinationCountry->name ?? '-') . '"',
                '"' . $s->status . '"',
                '"' . $s->departure_date . '"',
                '"' . $s->estimated_arrival . '"',
                '"' . $s->cargo_type . '"',
                '"' . $s->risk_level . '"',
            ]);
        }

        $content = implode("\n", $csvData);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="shipments-report-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
