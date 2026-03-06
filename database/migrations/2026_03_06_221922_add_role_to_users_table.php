<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Por defecto, todo el que se registre será un 'client' (cliente normal)
        $table->string('role')->default('client')->after('email'); 
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}
};
