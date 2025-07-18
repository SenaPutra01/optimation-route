<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutePolyline extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pengiriman',
        'from',
        'to',
        'distance_km',
        'duration_min',
        'coordinates_json'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'kode_pengiriman', 'kode_pengiriman');
    }
}
