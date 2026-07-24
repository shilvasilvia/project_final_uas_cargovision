<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code',
        'capital',
        'region',
        'population',
    ];

    // Relasi: satu Country memiliki banyak Port
    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function marketTrends()
    {
        return $this->hasMany(MarketTrend::class);
    }
}