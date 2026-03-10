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
        Schema::create('venue_zones', function (Blueprint $table) {
            $table->id();
            // Relación con el recinto. Si se borra el recinto, se borran sus zonas (onDelete cascade)
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            
            // Nombre de la zona (Ej: VIP, General, Lateral Izquierdo)
            $table->string('name');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_zones');
    }
};
