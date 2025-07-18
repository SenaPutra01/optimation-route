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
        Schema::create('route_polylines', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengiriman');
            $table->foreign('kode_pengiriman')
                ->references('kode_pengiriman')
                ->on('deliveries')
                ->onDelete('cascade');

            $table->string('from');
            $table->string('to');
            $table->decimal('distance_km', 8, 2);
            $table->decimal('duration_min', 8, 2);
            $table->longText('coordinates_json');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_polylines');
    }
};
