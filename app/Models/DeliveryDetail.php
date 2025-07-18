<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    use HasFactory;

    protected $fillable = ['delivery_id', 'paket_id'];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
}
