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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quién compró
        $table->string('order_number')->unique(); // Ej: MISU-2026-0001
        $table->decimal('total_amount', 10, 2); // Total pagado
        $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending'); // Estado del pago
        $table->string('payment_method')->nullable(); // Ej: Pago Móvil, Zelle, Efectivo
        $table->string('payment_reference')->nullable(); // Referencia del banco
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
