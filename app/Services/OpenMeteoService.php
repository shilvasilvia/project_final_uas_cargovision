<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenMeteoService
{
    /**
     * Fetch real-time weather parameters from Open-Meteo API.
     * Free open API - No API key required.
     */
    public function getWeather(float $latitude, float $longitude): array
    {
        try {
            $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
                'hourly' => 'temperature_2m,relative_humidity_2m,precipitation,wind_speed_10m',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $current = $data['current_weather'] ?? [];

                return [
                    'temperature' => $current['temperature'] ?? 28.5,
                    'wind_speed' => $current['windspeed'] ?? 14.2,
                    'weather_code' => $current['weathercode'] ?? 0,
                    'is_storm_risk' => ($current['windspeed'] ?? 0) > 30 || ($current['weathercode'] ?? 0) >= 80,
                    'source' => 'Open-Meteo API',
                ];
            }
        } catch (\Exception $e) {
            Log::warning('OpenMeteoService error: ' . $e->getMessage());
        }

        // Fallback default mock weather for offline/testing mode
        return [
            'temperature' => 27.0,
            'wind_speed' => 12.0,
            'weather_code' => 1,
            'is_storm_risk' => false,
            'source' => 'Open-Meteo (Fallback)',
        ];
    }
}
