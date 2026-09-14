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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('bed_number');

            // Using an enum for status provides data-level validation
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');

            // Using decimal for currency to prevent floating-point math errors
            $table->decimal('daily_rate', 10, 2)->default(0.00);
            // Indexing for faster lookups
            $table->index('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
