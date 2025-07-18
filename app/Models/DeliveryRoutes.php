<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRoutes extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'optimized_route',
        'distance_matrix',
        'route_details',
        'total_distance_km',
    ];

    protected $casts = [
        'optimized_route' => 'array',
        'distance_matrix' => 'array',
        'route_details' => 'array',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
