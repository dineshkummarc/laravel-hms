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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users');
$table->foreignId('appointment_id')->nullable()->constrained();
$table->foreignId('admission_id')->nullable()->constrained();
$table->decimal('subtotal', 10, 2);
$table->decimal('tax', 10, 2)->default(0);
$table->decimal('discount', 10, 2)->default(0);
$table->decimal('total', 10, 2);
$table->enum('status', ['unpaid', 'partial', 'paid']);
$table->json('line_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
