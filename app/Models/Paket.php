<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'tanggal_pengiriman',
        'kode_paket',
        'status'
    ];

    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function detail()
    {
        return $this->hasOne(PaketDetail::class);
    }
}
