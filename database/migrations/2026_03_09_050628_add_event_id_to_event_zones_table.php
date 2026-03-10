<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('event_zones', function (Blueprint $table) {
        // Añadimos la columna que falta y la relacionamos con la tabla events
        $table->foreignId('event_id')->after('id')->constrained()->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('event_zones', function (Blueprint $table) {
        $table->dropForeign(['event_id']);
        $table->dropColumn('event_id');
    });
}
};
