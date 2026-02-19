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
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade');
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->string('image_path')->nullable();
        $table->dateTime('event_date');
        $table->boolean('is_featured')->default(false);
        $table->enum('status', ['Draft', 'Published', 'Cancelled'])->default('Draft');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
