<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('event_zones', function (Blueprint $table) {
        $table->id();
        // Relación con el evento
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        // Relación con la zona del recinto (VenueZone)
        $table->foreignId('venue_zone_id')->constrained()->onDelete('cascade');
        
        $table->decimal('price', 10, 2);
        $table->integer('capacity');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_zones');
    }
};
