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

    // public function paket()
    // {
    //     return $this->belongsTo(Paket::class);
    //     // return $this->belongsToMany(Paket::class, 'delivery_details', 'delivery_id', 'paket_id');
    // }

    public function pakets()
    {
        return $this->hasManyThrough(
            \App\Models\Paket::class,          // Model tujuan
            \App\Models\DeliveryDetail::class, // Tabel perantara (pivot)
            'delivery_id', // Foreign key di delivery_details
            'id',          // Primary key di tabel paket
            'id',          // Local key di tabel delivery
            'paket_id'     // Foreign key di delivery_details ke paket
        );
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
