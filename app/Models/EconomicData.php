<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EconomicData extends Model
{
    use HasFactory;

    protected $table = 'economic_data';

    protected $fillable = [
        'country_id',
        'year',
        'gdp',
        'inflation_rate',
        'population',
        'exports_usd',
        'imports_usd',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
