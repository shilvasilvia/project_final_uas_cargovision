<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'overall_score',
        'economic_risk',
        'weather_risk',
        'geopolitical_risk',
        'operational_risk',
        'risk_category',
        'calculated_at',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
