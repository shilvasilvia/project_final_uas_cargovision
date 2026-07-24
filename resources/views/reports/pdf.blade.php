<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Risk Intelligence Supply Chain Global</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0284c7; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #0284c7; }
        .header p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th { background-color: #f1f5f9; color: #1e293b; padding: 8px; text-align: left; }
        td { padding: 8px; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; color: #0f172a; }
        .badge { padding: 3px 6px; border-radius: 3px; color: #fff; font-size: 10px; font-weight: bold; }
        .badge-high { background-color: #ef4444; }
        .badge-low { background-color: #10b981; }
    </style>
</head>
<body>

<div class="header">
    <h2>GLOBAL SUPPLY CHAIN RISK INTELLIGENCE REPORT</h2>
    <p>Dicetak Pada: {{ now()->format('d F Y H:i') }} | Sistem Risk Monitoring Global</p>
</div>

<div class="section-title">1. Ringkasan Pengiriman (Shipments)</div>
<table>
    <thead>
        <tr>
            <th>No. Shipment</th>
            <th>Asal</th>
            <th>Tujuan</th>
            <th>Status</th>
            <th>Departure</th>
            <th>Est. Arrival</th>
            <th>Risk Level</th>
        </tr>
    </thead>
    <tbody>
        @foreach($shipments as $s)
            <tr>
                <td><strong>{{ $s->shipment_number }}</strong></td>
                <td>{{ $s->originCountry->name ?? '-' }}</td>
                <td>{{ $s->destinationCountry->name ?? '-' }}</td>
                <td>{{ $s->status }}</td>
                <td>{{ $s->departure_date }}</td>
                <td>{{ $s->estimated_arrival }}</td>
                <td><span class="badge {{ $s->risk_level == 'High' ? 'badge-high' : 'badge-low' }}">{{ $s->risk_level }}</span></td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="section-title">2. Peringatan Cuaca Buruk (Weather Alerts)</div>
<table>
    <thead>
        <tr>
            <th>Judul Peringatan</th>
            <th>Negara</th>
            <th>Tipe Event</th>
            <th>Severity</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($weatherAlerts as $w)
            <tr>
                <td><strong>{{ $w->title }}</strong></td>
                <td>{{ $w->country->name ?? '-' }}</td>
                <td>{{ $w->event_type }}</td>
                <td><span class="badge badge-high">{{ $w->severity }}</span></td>
                <td>{{ $w->alert_date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="section-title">3. Skor Risiko Negara (Risk Scores)</div>
<table>
    <thead>
        <tr>
            <th>Negara</th>
            <th>Overall Score</th>
            <th>Economic Risk</th>
            <th>Weather Risk</th>
            <th>Risk Category</th>
        </tr>
    </thead>
    <tbody>
        @foreach($riskScores as $r)
            <tr>
                <td><strong>{{ $r->country->name ?? '-' }}</strong></td>
                <td>{{ $r->overall_score }}</td>
                <td>{{ $r->economic_risk }}</td>
                <td>{{ $r->weather_risk }}</td>
                <td>{{ $r->risk_category }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
