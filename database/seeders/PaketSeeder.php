<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Paket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $lokasi = [
                ['lat' => -6.295430, 'lng' => 106.799927, 'alamat' => 'Jl. Intan I No.5, Cilandak Barat'],
                ['lat' => -6.296102, 'lng' => 106.800793, 'alamat' => 'Jl. Karang Tengah Raya No.17, Cilandak'],
                ['lat' => -6.297382, 'lng' => 106.801223, 'alamat' => 'Jl. Haji Jian No.10, Cilandak'],
                ['lat' => -6.298243, 'lng' => 106.800120, 'alamat' => 'Jl. Pangeran Antasari No.66, Cilandak'],
                ['lat' => -6.296854, 'lng' => 106.799546, 'alamat' => 'Jl. TB Simatupang No.45, Cilandak'],
                ['lat' => -6.294932, 'lng' => 106.798187, 'alamat' => 'Jl. Kebagusan Raya No.24, Cilandak'],
                ['lat' => -6.293820, 'lng' => 106.797214, 'alamat' => 'Jl. Ampera Raya No.89, Cilandak'],
                ['lat' => -6.295997, 'lng' => 106.796154, 'alamat' => 'Jl. Bangka No.2, Cilandak'],
                ['lat' => -6.296620, 'lng' => 106.795880, 'alamat' => 'Jl. Madrasah No.77, Cilandak'],
                ['lat' => -6.297777, 'lng' => 106.796734, 'alamat' => 'Jl. Taman Margasatwa No.21, Cilandak'],
            ];

            for ($i = 0; $i < count($lokasi); $i++) {
                $kode = 'PKT' . now()->format('Ymd') . strtoupper(Str::random(5));

                $paket = Paket::create([
                    'tanggal_pengiriman' => now()->addDays(rand(1, 5)),
                    'kode_paket' => $kode,
                    'status' => 'Pending',
                ]);

                Location::create([
                    'name' => "Penerima " . ($i + 1),
                    'lat' => $lokasi[$i]['lat'],
                    'lng' => $lokasi[$i]['lng'],
                    'paket_id' => $paket->id,
                ]);

                $paket->detail()->create([
                    'nama_penerima' => "Penerima " . ($i + 1),
                    'alamat_lengkap' => $lokasi[$i]['alamat'],
                    'jenis_barang' => "Barang " . ($i + 1),
                    'berat' => rand(1, 10),
                ]);
            }
        });
    }
}
