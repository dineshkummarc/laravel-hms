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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
    // Link directly to user via mapped biometric identifier
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('biometric_device_id')->nullable()->constrained()->nullOnDelete();

    // Exact timestamp recorded by the physical device clock
    $table->dateTime('punch_time');

    // Type of punch
    $table->enum('punch_type', ['check_in', 'check_out', 'break_start', 'break_end'])->default('check_in');

    // Verification metadata from face recognition
    $table->string('verification_mode')->default('face'); // face, card, fingerprint, pin
    $table->decimal('confidence_score', 5, 2)->nullable(); // e.g., 98.45% match accuracy

    // For cloud camera systems that upload a snapshot of the log
    $table->string('snapshot_path')->nullable();

    // Status tracking for automated shift/overtime calculation later
    $table->enum('status', ['processed', 'pending', 'error'])->default('pending');
    $table->text('error_message')->nullable();

    // Store the raw JSON webhook from the machine for debugging network or signature issues
    $table->json('raw_payload')->nullable();

    $table->timestamps();

    // Performance indexes for heavy reporting
    $table->index(['user_id', 'punch_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
