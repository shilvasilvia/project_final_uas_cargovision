<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'port_id',
        'title',
        'event_type',
        'severity',
        'description',
        'alert_date',
        'status',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function port()
    {
        return $this->belongsTo(Port::class);
    }
}
