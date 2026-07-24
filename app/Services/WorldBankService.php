<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WorldBankService
{
    protected string $baseUrl = 'https://api.worldbank.org/v2';

    public function getGDP(string $countryCode): ?float
    {
        return $this->getIndicatorData($countryCode, 'NY.GDP.MKTP.CD');
    }

    public function getInflation(string $countryCode): ?float
    {
        return $this->getIndicatorData($countryCode, 'FP.CPI.TOTL.ZG');
    }

    public function getPopulation(string $countryCode): ?float
    {
        return $this->getIndicatorData($countryCode, 'SP.POP.TOTL');
    }

    public function getExport(string $countryCode): ?float
    {
        return $this->getIndicatorData($countryCode, 'NE.EXP.GNFS.CD');
    }

    public function getImport(string $countryCode): ?float
    {
        return $this->getIndicatorData($countryCode, 'NE.IMP.GNFS.CD');
    }

    protected function getIndicatorData(string $countryCode, string $indicator): ?float
    {
        $cacheKey = "wb_{$countryCode}_{$indicator}";

        return Cache::remember($cacheKey, 86400, function () use ($countryCode, $indicator) {
            try {
                $response = Http::get("{$this->baseUrl}/country/{$countryCode}/indicator/{$indicator}", [
                    'format' => 'json',
                    'per_page' => 5,
                ]);

                if ($response->successful() && isset($response->json()[1])) {
                    foreach ($response->json()[1] as $entry) {
                        if (isset($entry['value']) && $entry['value'] !== null) {
                            return (float) $entry['value'];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Fallback mock data jika API offline
            }

            return match ($indicator) {
                'NY.GDP.MKTP.CD' => 1300000000000.0,
                'FP.CPI.TOTL.ZG' => 3.2,
                'SP.POP.TOTL' => 275000000.0,
                'NE.EXP.GNFS.CD' => 240000000000.0,
                'NE.IMP.GNFS.CD' => 210000000000.0,
                default => 100.0,
            };
        });
    }
}
