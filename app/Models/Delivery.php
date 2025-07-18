<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'paket_id',
        'status',
        'scheduled_at',
        'delivered_at',
        'courier_name',
        'kode_pengiriman',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
    public function details()
    {
        return $this->hasMany(DeliveryDetail::class);
    }

    public function routeSummary()
    {
        return $this->hasOne(RouteSummary::class, 'kode_pengiriman', 'kode_pengiriman');
    }

    public function routePolylines()
    {
        return $this->hasMany(RoutePolyline::class, 'kode_pengiriman', 'kode_pengiriman');
    }
    public function route()
    {
        return $this->hasOne(DeliveryRoutes::class);
    }
}
