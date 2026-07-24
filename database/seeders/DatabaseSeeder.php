<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Country;
use App\Models\Port;
use App\Models\Shipment;
use App\Models\WeatherAlert;
use App\Models\News;
use App\Models\RiskScore;
use App\Models\Favorite;
use App\Models\EconomicData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User (Full Access)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Supply Chain (Full Access)',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Regular User (Pengguna Biasa)
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User Logistics Staff (Read-Only & Favorites)',
                'role' => 'user',
                'password' => Hash::make('password'),
            ]
        );

        // 3. 15 Strategic Global Supply Chain Countries
        $countriesData = [
            ['code' => 'IDN', 'name' => 'Indonesia', 'capital' => 'Jakarta', 'region' => 'Southeast Asia', 'population' => 275000000],
            ['code' => 'SGP', 'name' => 'Singapore', 'capital' => 'Singapore', 'region' => 'Southeast Asia', 'population' => 5600000],
            ['code' => 'CHN', 'name' => 'China', 'capital' => 'Beijing', 'region' => 'East Asia', 'population' => 1400000000],
            ['code' => 'JPN', 'name' => 'Japan', 'capital' => 'Tokyo', 'region' => 'East Asia', 'population' => 125000000],
            ['code' => 'DEU', 'name' => 'Germany', 'capital' => 'Berlin', 'region' => 'Europe', 'population' => 83200000],
            ['code' => 'USA', 'name' => 'United States', 'capital' => 'Washington D.C.', 'region' => 'North America', 'population' => 331000000],
            ['code' => 'AUS', 'name' => 'Australia', 'capital' => 'Canberra', 'region' => 'Oceania', 'population' => 25600000],
            ['code' => 'KOR', 'name' => 'South Korea', 'capital' => 'Seoul', 'region' => 'East Asia', 'population' => 51700000],
            ['code' => 'NLD', 'name' => 'Netherlands', 'capital' => 'Amsterdam', 'region' => 'Europe', 'population' => 17500000],
            ['code' => 'GBR', 'name' => 'United Kingdom', 'capital' => 'London', 'region' => 'Europe', 'population' => 67000000],
            ['code' => 'IND', 'name' => 'India', 'capital' => 'New Delhi', 'region' => 'South Asia', 'population' => 1408000000],
            ['code' => 'ARE', 'name' => 'United Arab Emirates', 'capital' => 'Abu Dhabi', 'region' => 'Middle East', 'population' => 9890000],
            ['code' => 'BRA', 'name' => 'Brazil', 'capital' => 'Brasilia', 'region' => 'South America', 'population' => 214000000],
            ['code' => 'MYS', 'name' => 'Malaysia', 'capital' => 'Kuala Lumpur', 'region' => 'Southeast Asia', 'population' => 32700000],
            ['code' => 'VNM', 'name' => 'Vietnam', 'capital' => 'Hanoi', 'region' => 'Southeast Asia', 'population' => 97300000],
        ];

        $countries = [];
        foreach ($countriesData as $c) {
            $countries[$c['code']] = Country::firstOrCreate(['code' => $c['code']], $c);
        }

        // 4. Ports for Global Maritime Hubs
        $portsData = [
            ['name' => 'Port of Tanjung Priok', 'country' => 'IDN', 'city' => 'Jakarta', 'lat' => -6.1033, 'lng' => 106.8797],
            ['name' => 'Port of Tanjung Perak', 'country' => 'IDN', 'city' => 'Surabaya', 'lat' => -7.1991, 'lng' => 112.7306],
            ['name' => 'Port of Singapore', 'country' => 'SGP', 'city' => 'Singapore', 'lat' => 1.2644, 'lng' => 103.8400],
            ['name' => 'Port of Shanghai', 'country' => 'CHN', 'city' => 'Shanghai', 'lat' => 31.2304, 'lng' => 121.4737],
            ['name' => 'Port of Ningbo-Zhoushan', 'country' => 'CHN', 'city' => 'Ningbo', 'lat' => 29.8683, 'lng' => 121.5440],
            ['name' => 'Port of Tokyo', 'country' => 'JPN', 'city' => 'Tokyo', 'lat' => 35.6191, 'lng' => 139.7753],
            ['name' => 'Port of Yokohama', 'country' => 'JPN', 'city' => 'Yokohama', 'lat' => 35.4437, 'lng' => 139.6380],
            ['name' => 'Port of Hamburg', 'country' => 'DEU', 'city' => 'Hamburg', 'lat' => 53.5511, 'lng' => 9.9937],
            ['name' => 'Port of Los Angeles', 'country' => 'USA', 'city' => 'Los Angeles', 'lat' => 33.7422, 'lng' => -118.2711],
            ['name' => 'Port of New York & New Jersey', 'country' => 'USA', 'city' => 'New York', 'lat' => 40.6698, 'lng' => -74.1331],
            ['name' => 'Port of Botany', 'country' => 'AUS', 'city' => 'Sydney', 'lat' => -33.9711, 'lng' => 151.2181],
            ['name' => 'Port of Busan', 'country' => 'KOR', 'city' => 'Busan', 'lat' => 35.1796, 'lng' => 129.0756],
            ['name' => 'Port of Rotterdam', 'country' => 'NLD', 'city' => 'Rotterdam', 'lat' => 51.9244, 'lng' => 4.4777],
            ['name' => 'Port of Felixstowe', 'country' => 'GBR', 'city' => 'Felixstowe', 'lat' => 51.9617, 'lng' => 1.3513],
            ['name' => 'Jawaharlal Nehru Port (JNPT)', 'country' => 'IND', 'city' => 'Navi Mumbai', 'lat' => 18.9499, 'lng' => 72.9515],
            ['name' => 'Jebel Ali Port', 'country' => 'ARE', 'city' => 'Dubai', 'lat' => 24.9857, 'lng' => 55.0642],
            ['name' => 'Port of Santos', 'country' => 'BRA', 'city' => 'Santos', 'lat' => -23.9608, 'lng' => -46.3339],
            ['name' => 'Port Klang', 'country' => 'MYS', 'city' => 'Klang', 'lat' => 2.9999, 'lng' => 101.3928],
            ['name' => 'Port of Hai Phong', 'country' => 'VNM', 'city' => 'Hai Phong', 'lat' => 20.8651, 'lng' => 106.6838],
        ];

        $ports = [];
        foreach ($portsData as $p) {
            $ports[$p['name']] = Port::firstOrCreate(['name' => $p['name']], [
                'country_id' => $countries[$p['country']]->id,
                'city' => $p['city'],
                'latitude' => $p['lat'],
                'longitude' => $p['lng'],
                'status' => 'active',
            ]);
        }

        // 5. Sample Shipments across 15 countries
        $shipmentsData = [
            ['num' => 'SHP-2026-001', 'org_c' => 'IDN', 'dst_c' => 'SGP', 'org_p' => 'Port of Tanjung Priok', 'dst_p' => 'Port of Singapore', 'status' => 'in_transit', 'cargo' => 'Electronic Components', 'risk' => 'Low'],
            ['num' => 'SHP-2026-002', 'org_c' => 'CHN', 'dst_c' => 'USA', 'org_p' => 'Port of Shanghai', 'dst_p' => 'Port of Los Angeles', 'status' => 'delayed', 'cargo' => 'Consumer Electronics', 'risk' => 'High'],
            ['num' => 'SHP-2026-003', 'org_c' => 'DEU', 'dst_c' => 'NLD', 'org_p' => 'Port of Hamburg', 'dst_p' => 'Port of Rotterdam', 'status' => 'delivered', 'cargo' => 'Automotive Parts', 'risk' => 'Low'],
            ['num' => 'SHP-2026-004', 'org_c' => 'JPN', 'dst_c' => 'KOR', 'org_p' => 'Port of Yokohama', 'dst_p' => 'Port of Busan', 'status' => 'in_transit', 'cargo' => 'Semiconductor Materials', 'risk' => 'Moderate'],
            ['num' => 'SHP-2026-005', 'org_c' => 'ARE', 'dst_c' => 'IND', 'org_p' => 'Jebel Ali Port', 'dst_p' => 'Jawaharlal Nehru Port (JNPT)', 'status' => 'in_transit', 'cargo' => 'Petrochemical Products', 'risk' => 'Moderate'],
            ['num' => 'SHP-2026-006', 'org_c' => 'BRA', 'dst_c' => 'CHN', 'org_p' => 'Port of Santos', 'dst_p' => 'Port of Ningbo-Zhoushan', 'status' => 'in_transit', 'cargo' => 'Agricultural Commodities', 'risk' => 'Low'],
            ['num' => 'SHP-2026-007', 'org_c' => 'MYS', 'dst_c' => 'VNM', 'org_p' => 'Port Klang', 'dst_p' => 'Port of Hai Phong', 'status' => 'in_transit', 'cargo' => 'Textiles & Apparel', 'risk' => 'Low'],
            ['num' => 'SHP-2026-008', 'org_c' => 'GBR', 'dst_c' => 'USA', 'org_p' => 'Port of Felixstowe', 'dst_p' => 'Port of New York & New Jersey', 'status' => 'delayed', 'cargo' => 'Pharmaceutical Supplies', 'risk' => 'High'],
        ];

        foreach ($shipmentsData as $s) {
            Shipment::firstOrCreate(['shipment_number' => $s['num']], [
                'origin_country_id' => $countries[$s['org_c']]->id,
                'destination_country_id' => $countries[$s['dst_c']]->id,
                'origin_port_id' => $ports[$s['org_p']]->id,
                'destination_port_id' => $ports[$s['dst_p']]->id,
                'status' => $s['status'],
                'departure_date' => now()->subDays(rand(1, 5)),
                'estimated_arrival' => now()->addDays(rand(2, 10)),
                'cargo_type' => $s['cargo'],
                'risk_level' => $s['risk'],
            ]);
        }

        // 6. Weather Alerts
        $weatherData = [
            ['title' => 'Super Typhoon Storm Surge Warning', 'country' => 'CHN', 'event' => 'Typhoon', 'severity' => 'Critical', 'desc' => 'Severe typhoon disruption near Shanghai port.'],
            ['title' => 'North Sea Storm & High Tide Advisory', 'country' => 'NLD', 'event' => 'Storm', 'severity' => 'High', 'desc' => 'Gale-force winds impacting North Sea shipping lanes.'],
            ['title' => 'Monsoon Rainfall & Flood Warning', 'country' => 'IDN', 'event' => 'Flood', 'severity' => 'Moderate', 'desc' => 'Heavy rainfall affecting port logistics in Jakarta.'],
            ['title' => 'Gulf Coast Tropical Storm Watch', 'country' => 'USA', 'event' => 'Tropical Storm', 'severity' => 'High', 'desc' => 'Potential delays along Eastern shipping routes.'],
        ];

        foreach ($weatherData as $w) {
            WeatherAlert::firstOrCreate(['title' => $w['title']], [
                'country_id' => $countries[$w['country']]->id,
                'port_id' => null,
                'event_type' => $w['event'],
                'severity' => $w['severity'],
                'description' => $w['desc'],
                'alert_date' => now(),
                'status' => 'active',
            ]);
        }

        // 7. News & Sentiment Analysis
        $newsData = [
            ['title' => 'Global Freight Rates Stabilize Amid Port Expansion', 'country' => 'SGP', 'cat' => 'Logistics', 'sent' => 'Positive', 'content' => 'Improved infrastructure lowers turn-around time.'],
            ['title' => 'Supply Chain Bottlenecks Reported at Major European Hubs', 'country' => 'DEU', 'cat' => 'Trade', 'sent' => 'Negative', 'content' => 'Temporary labor shortages cause minor maritime delays.'],
            ['title' => 'Semiconductor Export Growth Boosts Regional Trade Balance', 'country' => 'KOR', 'cat' => 'Economy', 'sent' => 'Positive', 'content' => 'High demand drives steady export numbers.'],
            ['title' => 'Middle East Canal Traffic Returns to Normal Operating Capacity', 'country' => 'ARE', 'cat' => 'Maritime', 'sent' => 'Positive', 'content' => 'Smooth transit through key maritime choke points.'],
        ];

        foreach ($newsData as $n) {
            News::firstOrCreate(['title' => $n['title']], [
                'country_id' => $countries[$n['country']]->id,
                'category' => $n['cat'],
                'published_date' => now()->subDays(rand(1, 3)),
                'content' => $n['content'],
                'sentiment' => $n['sent'],
            ]);
        }

        // 8. Risk Scores for all 15 countries
        $riskScoresData = [
            'IDN' => ['overall' => 42.5, 'eco' => 35.0, 'wea' => 45.0, 'geo' => 40.0, 'ops' => 48.0, 'cat' => 'Moderate'],
            'SGP' => ['overall' => 15.0, 'eco' => 12.0, 'wea' => 10.0, 'geo' => 15.0, 'ops' => 18.0, 'cat' => 'Low'],
            'CHN' => ['overall' => 68.0, 'eco' => 55.0, 'wea' => 75.0, 'geo' => 70.0, 'ops' => 65.0, 'cat' => 'High'],
            'JPN' => ['overall' => 28.0, 'eco' => 25.0, 'wea' => 35.0, 'geo' => 20.0, 'ops' => 30.0, 'cat' => 'Low'],
            'DEU' => ['overall' => 32.0, 'eco' => 30.0, 'wea' => 25.0, 'geo' => 35.0, 'ops' => 38.0, 'cat' => 'Low'],
            'USA' => ['overall' => 45.0, 'eco' => 40.0, 'wea' => 50.0, 'geo' => 42.0, 'ops' => 46.0, 'cat' => 'Moderate'],
            'AUS' => ['overall' => 22.0, 'eco' => 20.0, 'wea' => 30.0, 'geo' => 15.0, 'ops' => 22.0, 'cat' => 'Low'],
            'KOR' => ['overall' => 35.0, 'eco' => 30.0, 'wea' => 32.0, 'geo' => 45.0, 'ops' => 32.0, 'cat' => 'Moderate'],
            'NLD' => ['overall' => 25.0, 'eco' => 22.0, 'wea' => 30.0, 'geo' => 20.0, 'ops' => 28.0, 'cat' => 'Low'],
            'GBR' => ['overall' => 48.0, 'eco' => 45.0, 'wea' => 35.0, 'geo' => 52.0, 'ops' => 55.0, 'cat' => 'Moderate'],
            'IND' => ['overall' => 58.0, 'eco' => 50.0, 'wea' => 60.0, 'geo' => 55.0, 'ops' => 62.0, 'cat' => 'High'],
            'ARE' => ['overall' => 38.0, 'eco' => 30.0, 'wea' => 25.0, 'geo' => 50.0, 'ops' => 40.0, 'cat' => 'Moderate'],
            'BRA' => ['overall' => 52.0, 'eco' => 55.0, 'wea' => 48.0, 'geo' => 45.0, 'ops' => 58.0, 'cat' => 'Moderate'],
            'MYS' => ['overall' => 30.0, 'eco' => 28.0, 'wea' => 32.0, 'geo' => 25.0, 'ops' => 35.0, 'cat' => 'Low'],
            'VNM' => ['overall' => 40.0, 'eco' => 35.0, 'wea' => 42.0, 'geo' => 38.0, 'ops' => 45.0, 'cat' => 'Moderate'],
        ];

        foreach ($riskScoresData as $code => $r) {
            RiskScore::firstOrCreate(['country_id' => $countries[$code]->id], [
                'overall_score' => $r['overall'],
                'economic_risk' => $r['eco'],
                'weather_risk' => $r['wea'],
                'geopolitical_risk' => $r['geo'],
                'operational_risk' => $r['ops'],
                'risk_category' => $r['cat'],
                'calculated_at' => now(),
            ]);
        }

        // 9. Market Trends (Currency & Economic Impact)
        $marketTrendsData = [
            'IDN' => ['curr' => 'IDR', 'rate' => 15850.00, 'inf' => 2.8, 'gdp' => 5.05, 'score' => 45.0, 'dir' => 'bullish'],
            'SGP' => ['curr' => 'SGD', 'rate' => 1.34, 'inf' => 2.4, 'gdp' => 3.60, 'score' => 15.0, 'dir' => 'bullish'],
            'CHN' => ['curr' => 'CNY', 'rate' => 7.23, 'inf' => 0.5, 'gdp' => 5.20, 'score' => 60.0, 'dir' => 'bearish'],
            'JPN' => ['curr' => 'JPY', 'rate' => 155.20, 'inf' => 2.2, 'gdp' => 1.90, 'score' => 35.0, 'dir' => 'bearish'],
            'DEU' => ['curr' => 'EUR', 'rate' => 0.92, 'inf' => 2.5, 'gdp' => 0.30, 'score' => 30.0, 'dir' => 'stable'],
            'USA' => ['curr' => 'USD', 'rate' => 1.00, 'inf' => 3.1, 'gdp' => 2.50, 'score' => 25.0, 'dir' => 'bullish'],
            'AUS' => ['curr' => 'AUD', 'rate' => 1.52, 'inf' => 3.6, 'gdp' => 1.80, 'score' => 20.0, 'dir' => 'stable'],
            'KOR' => ['curr' => 'KRW', 'rate' => 1380.00, 'inf' => 2.7, 'gdp' => 1.40, 'score' => 40.0, 'dir' => 'stable'],
            'NLD' => ['curr' => 'EUR', 'rate' => 0.92, 'inf' => 2.6, 'gdp' => 0.10, 'score' => 22.0, 'dir' => 'stable'],
            'GBR' => ['curr' => 'GBP', 'rate' => 0.79, 'inf' => 2.3, 'gdp' => 0.50, 'score' => 42.0, 'dir' => 'bearish'],
            'IND' => ['curr' => 'INR', 'rate' => 83.40, 'inf' => 4.8, 'gdp' => 7.60, 'score' => 50.0, 'dir' => 'bullish'],
            'ARE' => ['curr' => 'AED', 'rate' => 3.67, 'inf' => 2.0, 'gdp' => 3.40, 'score' => 30.0, 'dir' => 'bullish'],
            'BRA' => ['curr' => 'BRL', 'rate' => 5.15, 'inf' => 3.9, 'gdp' => 2.90, 'score' => 55.0, 'dir' => 'bearish'],
            'MYS' => ['curr' => 'MYR', 'rate' => 4.72, 'inf' => 1.8, 'gdp' => 3.70, 'score' => 28.0, 'dir' => 'stable'],
            'VNM' => ['curr' => 'VND', 'rate' => 25450.00, 'inf' => 3.2, 'gdp' => 5.05, 'score' => 38.0, 'dir' => 'bullish'],
        ];

        foreach ($marketTrendsData as $code => $m) {
            \App\Models\MarketTrend::firstOrCreate(['country_id' => $countries[$code]->id], [
                'currency_code' => $m['curr'],
                'exchange_rate_usd' => $m['rate'],
                'inflation_rate' => $m['inf'],
                'gdp_growth_rate' => $m['gdp'],
                'currency_impact_score' => $m['score'],
                'trend_direction' => $m['dir'],
                'recorded_at' => now(),
            ]);
        }

        // 10. Sample Favorites for Regular User
        Favorite::firstOrCreate([
            'user_id' => $user->id,
            'favoritable_type' => Country::class,
            'favoritable_id' => $countries['IDN']->id,
        ]);

        Favorite::firstOrCreate([
            'user_id' => $user->id,
            'favoritable_type' => Country::class,
            'favoritable_id' => $countries['SGP']->id,
        ]);
    }
}

