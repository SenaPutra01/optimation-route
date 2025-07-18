<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_penerima',
        'alamat_lengkap',
        'jenis_barang',
        'berat',
        // 'status'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
}
