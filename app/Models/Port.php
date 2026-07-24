<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'city',
        'latitude',
        'longitude',
        'status',
    ];

    // Relasi ke Country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Shipment yang berangkat dari port ini
    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_port_id');
    }

    // Shipment yang menuju port ini
    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_port_id');
    }
}