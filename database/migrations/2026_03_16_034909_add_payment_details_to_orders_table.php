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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('payment_name')->nullable()->after('payment_reference');
        $table->string('payment_document')->nullable()->after('payment_name');
        $table->string('payment_phone')->nullable()->after('payment_document');
        $table->string('payment_receipt_path')->nullable()->after('payment_phone');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
