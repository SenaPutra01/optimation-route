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
        Schema::create('route_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengiriman');
            $table->foreign('kode_pengiriman')
                ->references('kode_pengiriman')
                ->on('deliveries')
                ->onDelete('cascade');
            $table->decimal('total_distance_km', 8, 2);
            $table->decimal('total_duration_min', 8, 2);
            $table->integer('fuel_cost_per_km');
            $table->integer('total_fuel_cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_summaries');
    }
};
