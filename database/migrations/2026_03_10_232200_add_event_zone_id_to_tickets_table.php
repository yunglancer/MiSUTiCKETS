<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Agregamos la columna justo después de event_id
            $table->foreignId('event_zone_id')
                  ->nullable()
                  ->after('event_id')
                  ->constrained('event_zones')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['event_zone_id']);
            $table->dropColumn('event_zone_id');
        });
    }
};