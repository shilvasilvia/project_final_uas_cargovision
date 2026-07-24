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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
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

        // 3. Sample Countries
        $indonesia = Country::firstOrCreate(['code' => 'IDN'], [
            'name' => 'Indonesia',
            'capital' => 'Jakarta',
            'region' => 'Southeast Asia',
            'population' => 275000000,
        ]);

        $singapore = Country::firstOrCreate(['code' => 'SGP'], [
            'name' => 'Singapore',
            'capital' => 'Singapore',
            'region' => 'Southeast Asia',
            'population' => 5600000,
        ]);

        $china = Country::firstOrCreate(['code' => 'CHN'], [
            'name' => 'China',
            'capital' => 'Beijing',
            'region' => 'East Asia',
            'population' => 1400000000,
        ]);

        // 4. Sample Ports
        $portTanjungPriok = Port::firstOrCreate(['name' => 'Port of Tanjung Priok'], [
            'country_id' => $indonesia->id,
            'city' => 'Jakarta',
            'latitude' => -6.1033,
            'longitude' => 106.8797,
            'status' => 'active',
        ]);

        $portSingapore = Port::firstOrCreate(['name' => 'Port of Singapore'], [
            'country_id' => $singapore->id,
            'city' => 'Singapore',
            'latitude' => 1.2644,
            'longitude' => 103.8400,
            'status' => 'active',
        ]);

        // 5. Sample Shipments
        $shipment1 = Shipment::firstOrCreate(['shipment_number' => 'SHP-2026-001'], [
            'origin_country_id' => $indonesia->id,
            'destination_country_id' => $singapore->id,
            'origin_port_id' => $portTanjungPriok->id,
            'destination_port_id' => $portSingapore->id,
            'status' => 'in_transit',
            'departure_date' => now()->subDays(2),
            'estimated_arrival' => now()->addDays(3),
            'cargo_type' => 'Electronic Components',
            'risk_level' => 'Low',
        ]);

        // 6. Sample Favorites for Regular User
        Favorite::firstOrCreate([
            'user_id' => $user->id,
            'favoritable_type' => Country::class,
            'favoritable_id' => $indonesia->id,
        ]);

        Favorite::firstOrCreate([
            'user_id' => $user->id,
            'favoritable_type' => Shipment::class,
            'favoritable_id' => $shipment1->id,
        ]);

        // 7. Sample Weather Alerts
        WeatherAlert::firstOrCreate(['title' => 'Tropical Typhoon Severe Storm Warning'], [
            'country_id' => $china->id,
            'port_id' => null,
            'event_type' => 'Typhoon',
            'severity' => 'Critical',
            'description' => 'Super Typhoon approaches East China sea.',
            'alert_date' => now(),
            'status' => 'active',
        ]);

        // 8. Sample News & Risk Scores
        News::firstOrCreate(['title' => 'Port Congestion Impacts Maritime Supply Chains'], [
            'country_id' => $singapore->id,
            'category' => 'Logistics',
            'published_date' => now()->subDays(1),
            'content' => 'Increased trade volume leads to temporary delays.',
            'sentiment' => 'Negative',
        ]);

        RiskScore::firstOrCreate(['country_id' => $indonesia->id], [
            'overall_score' => 38.5,
            'economic_risk' => 30.0,
            'weather_risk' => 20.0,
            'geopolitical_risk' => 45.0,
            'operational_risk' => 35.0,
            'risk_category' => 'Low',
            'calculated_at' => now(),
        ]);
    }
}
