<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paket_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained()->onDelete('cascade');
            $table->string('nama_penerima');
            $table->string('alamat_lengkap');
            $table->string('jenis_barang');
            $table->integer('berat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_details');
    }
};
