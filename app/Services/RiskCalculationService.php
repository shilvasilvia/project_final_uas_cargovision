<?php

namespace App\Services;

use App\Models\Country;
use App\Models\WeatherAlert;
use App\Models\News;
use App\Models\MarketTrend;

class RiskCalculationService
{
    /**
     * Calculate Weighted Risk Score based on PDF Specification:
     * Weather Risk (30%) + Inflation Risk (20%) + Political News Risk (40%) + Currency Risk (10%)
     */
    public function calculateForCountry(Country $country): array
    {
        // 1. Weather Risk (30% Weight)
        $weatherAlertsCount = WeatherAlert::where('country_id', $country->id)
            ->where('status', 'active')
            ->count();
        $weatherRisk = min(100.0, $weatherAlertsCount * 30.0);

        // 2. Inflation Risk (20% Weight)
        $marketTrend = MarketTrend::where('country_id', $country->id)->latest()->first();
        $inflationRate = $marketTrend ? $marketTrend->inflation_rate : 2.5;
        $inflationRisk = min(100.0, max(0.0, $inflationRate * 12.0));

        // 3. Political & News Risk (40% Weight)
        $negativeNewsCount = News::where('country_id', $country->id)
            ->where('sentiment', 'Negative')
            ->count();
        $politicalNewsRisk = min(100.0, max(10.0, $negativeNewsCount * 35.0));

        // 4. Currency Risk (10% Weight)
        $currencyImpact = $marketTrend ? $marketTrend->currency_impact_score : 30.0;
        $currencyRisk = min(100.0, $currencyImpact);

        // Weighted Risk Model (PDF Page 8 Specification):
        // Total Risk = (Weather * 30%) + (Inflation * 20%) + (Political News * 40%) + (Currency * 10%)
        $overallScore = round(
            ($weatherRisk * 0.30) +
            ($inflationRisk * 0.20) +
            ($politicalNewsRisk * 0.40) +
            ($currencyRisk * 0.10),
            1
        );

        $category = match (true) {
            $overallScore >= 70 => 'Critical',
            $overallScore >= 45 => 'Medium',
            $overallScore >= 25 => 'Moderate',
            default => 'Low',
        };

        return [
            'country_id' => $country->id,
            'overall_score' => $overallScore,
            'economic_risk' => round($inflationRisk, 1),
            'weather_risk' => round($weatherRisk, 1),
            'geopolitical_risk' => round($politicalNewsRisk, 1),
            'operational_risk' => round($currencyRisk, 1),
            'risk_category' => $category,
            'calculated_at' => now(),
        ];
    }
}
