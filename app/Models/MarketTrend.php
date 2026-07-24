<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketTrend extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'currency_code',
        'exchange_rate_usd',
        'inflation_rate',
        'gdp_growth_rate',
        'currency_impact_score',
        'trend_direction',
        'recorded_at',
    ];

    protected $casts = [
        'exchange_rate_usd' => 'float',
        'inflation_rate' => 'float',
        'gdp_growth_rate' => 'float',
        'currency_impact_score' => 'float',
        'recorded_at' => 'datetime',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
