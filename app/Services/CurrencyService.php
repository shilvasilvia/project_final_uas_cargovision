<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    public function getRate(string $from, string $to): float
    {
        $cacheKey = "currency_{$from}_{$to}";

        return Cache::remember($cacheKey, 3600, function () use ($from, $to) {
            try {
                $response = Http::get("https://api.exchangerate-api.com/v4/latest/{$from}");
                if ($response->successful() && isset($response->json()['rates'][$to])) {
                    return (float) $response->json()['rates'][$to];
                }
            } catch (\Exception $e) {
                // Fallback static rates
            }

            $rates = [
                'USD_IDR' => 16250.0,
                'EUR_IDR' => 17600.0,
                'JPY_IDR' => 105.5,
                'SGD_IDR' => 12100.0,
            ];

            return $rates["{$from}_{$to}"] ?? 1.0;
        });
    }
}
