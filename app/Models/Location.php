<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lat', 'lng', 'paket_id'];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
}
