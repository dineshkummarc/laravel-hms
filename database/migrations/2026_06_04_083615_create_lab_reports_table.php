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
        Schema::create('lab_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('doctor_id')->constrained('users'); // Who requested it
            $table->foreignId('technician_id')->nullable()->constrained('users'); // Who performed it
            $table->foreignId('lab_test_id')->constrained();
            $table->enum('status', ['requested', 'sample_collected', 'analyzing', 'completed']);
            $table->json('results')->nullable(); // Raw data from machines
            $table->string('document_path')->nullable();
            $table->foreignId('invoice_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_reports');
    }
};
