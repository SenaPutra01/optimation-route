<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pengiriman',
        'total_distance_km',
        'total_duration_min',
        'fuel_cost_per_km',
        'total_fuel_cost'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'kode_pengiriman', 'kode_pengiriman');
    }
}
