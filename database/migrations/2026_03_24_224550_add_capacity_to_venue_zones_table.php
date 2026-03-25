<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración para añadir la columna.
     */
    public function up(): void
    {
        Schema::table('venue_zones', function (Blueprint $table) {
            // Añadimos la columna capacity después del nombre
            $table->integer('capacity')->default(0)->after('name');
        });
    }

    /**
     * Revierte la migración (borra la columna).
     */
    public function down(): void
    {
        Schema::table('venue_zones', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }
};