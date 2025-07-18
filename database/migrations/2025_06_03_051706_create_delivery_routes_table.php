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
        Schema::create('delivery_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id')->unique();
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');

            // Data route
            $table->json('optimized_route');
            $table->json('distance_matrix');
            $table->json('route_details');
            $table->float('total_distance_km');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_routes');
    }
};
