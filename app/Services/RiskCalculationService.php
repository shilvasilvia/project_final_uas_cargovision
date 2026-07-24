<?php

namespace App\Services;

use App\Models\Country;
use App\Models\WeatherAlert;
use App\Models\News;

class RiskCalculationService
{
    public function calculateForCountry(Country $country): array
    {
        // 1. Economic Risk (0-100)
        $economicRisk = 30.0;

        // 2. Weather Risk based on active severe weather alerts
        $weatherAlertsCount = WeatherAlert::where('country_id', $country->id)
            ->where('status', 'active')
            ->count();
        $weatherRisk = min(100.0, $weatherAlertsCount * 25.0);

        // 3. Geopolitical & News Risk
        $newsCount = News::where('country_id', $country->id)->count();
        $geopoliticalRisk = min(100.0, 20.0 + ($newsCount * 10.0));

        // 4. Operational Risk
        $operationalRisk = 25.0;

        // Overall Score (Weighted Average)
        $overallScore = round(
            ($economicRisk * 0.25) +
            ($weatherRisk * 0.35) +
            ($geopoliticalRisk * 0.25) +
            ($operationalRisk * 0.15),
            2
        );

        $category = match (true) {
            $overallScore >= 75 => 'High',
            $overallScore >= 45 => 'Medium',
            default => 'Low',
        };

        return [
            'country_id' => $country->id,
            'overall_score' => $overallScore,
            'economic_risk' => $economicRisk,
            'weather_risk' => $weatherRisk,
            'geopolitical_risk' => $geopoliticalRisk,
            'operational_risk' => $operationalRisk,
            'risk_category' => $category,
            'calculated_at' => now(),
        ];
    }
}
