<?php

namespace App\Services;

class SentimentAnalysisService
{
    protected array $positiveWords = [
        'growth', 'stability', 'peace', 'agreement', 'trade', 'expansion',
        'profit', 'improvement', 'success', 'boom', 'recovery', 'cooperation',
        'stabilitas', 'pertumbuhan', 'kerjasama', 'keuntungan', 'pemulihan', 'sukses'
    ];

    protected array $negativeWords = [
        'war', 'conflict', 'strike', 'crisis', 'tariff', 'sanction',
        'embargo', 'delay', 'disruption', 'storm', 'typhoon', 'flood',
        'shortage', 'inflation', 'protest', 'riot', 'piracy', 'attack',
        'perang', 'konflik', 'krisis', 'sanksi', 'pemogokan', 'badai', 'inflasi', 'gangguan'
    ];

    public function analyze(string $text): array
    {
        $lowercase = strtolower($text);
        $posCount = 0;
        $negCount = 0;

        foreach ($this->positiveWords as $word) {
            if (str_contains($lowercase, $word)) {
                $posCount++;
            }
        }

        foreach ($this->negativeWords as $word) {
            if (str_contains($lowercase, $word)) {
                $negCount++;
            }
        }

        $sentiment = 'Neutral';
        if ($negCount > $posCount) {
            $sentiment = 'Negative';
        } elseif ($posCount > $negCount) {
            $sentiment = 'Positive';
        }

        return [
            'sentiment' => $sentiment,
            'positive_hits' => $posCount,
            'negative_hits' => $negCount,
            'score' => $posCount - $negCount,
        ];
    }
}
