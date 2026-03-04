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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade'); // A qué compra pertenece
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Dueño del ticket
        $table->foreignId('event_id')->constrained()->onDelete('cascade'); // Para qué evento es
        $table->uuid('ticket_code')->unique(); // El código único e irrepetible para el QR
        $table->enum('status', ['active', 'used', 'revoked'])->default('active'); // Si ya pasó por la puerta o no
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
