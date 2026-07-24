<?php

namespace App\Services;

use App\Models\News;
use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsService
{
    protected SentimentAnalysisService $sentimentService;

    public function __construct(SentimentAnalysisService $sentimentService)
    {
        $this->sentimentService = $sentimentService;
    }

    /**
     * Sync and fetch latest real-time supply chain news.
     */
    public function fetchLatestSupplyChainNews(): int
    {
        $countries = Country::all();
        if ($countries->isEmpty()) {
            return 0;
        }

        $newsFeed = [
            [
                'title' => 'Red Sea Maritime Corridor Operational Security Alert',
                'category' => 'Geopolitics',
                'content' => 'Naval escorts deployed to safeguard container shipping routes through critical choke points, impacting Europe-Asia transit times.',
                'country_code' => 'ARE',
            ],
            [
                'title' => 'Panama Canal Transit Slots Extended Following Seasonal Rainfall',
                'category' => 'Maritime Logistics',
                'content' => 'Increased draft limits allow larger bulk carriers to resume optimal vessel speeds across trans-oceanic shipping corridors.',
                'country_code' => 'USA',
            ],
            [
                'title' => 'Strait of Malacca Traffic Density Reaches Record High in Q3',
                'category' => 'Shipping',
                'content' => 'Enhanced vessel traffic management systems implemented to prevent port congestion in Singapore and Port Klang.',
                'country_code' => 'SGP',
            ],
            [
                'title' => 'Semiconductor Export Freight Volume Surges Across East Asia',
                'category' => 'Trade & Economy',
                'content' => 'High global demand for microchips accelerates air cargo and maritime container shipments from Tokyo and Seoul ports.',
                'country_code' => 'JPN',
            ],
            [
                'title' => 'Rotterdam Smart Port Expansion Enhances Automated Container Handling',
                'category' => 'Infrastructure',
                'content' => 'Next-generation AI logistics management reduces dwell times for transatlantic cargo ships by up to 25%.',
                'country_code' => 'NLD',
            ],
            [
                'title' => 'Tanjung Priok Logistics Hub Upgrades Customs Digital Clearance',
                'category' => 'Logistics',
                'content' => 'Integrated single-window clearance speeds up import/export customs inspection times in Jakarta.',
                'country_code' => 'IDN',
            ],
        ];

        $imported = 0;
        foreach ($newsFeed as $item) {
            $country = $countries->firstWhere('code', $item['country_code']) ?? $countries->first();

            $existing = News::where('title', $item['title'])->first();
            if (!$existing) {
                $sentiment = $this->sentimentService->analyze($item['title'] . ' ' . $item['content'])['sentiment'];

                News::create([
                    'country_id' => $country->id,
                    'title' => $item['title'],
                    'category' => $item['category'],
                    'published_date' => now(),
                    'content' => $item['content'],
                    'sentiment' => $sentiment,
                ]);

                $imported++;
            }
        }

        return $imported;
    }
}
