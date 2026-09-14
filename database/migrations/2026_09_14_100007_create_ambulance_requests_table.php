<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ambulance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('caller_name');
            $table->string('caller_phone');
            $table->string('pickup_address');
            $table->string('destination')->nullable();
            $table->enum('priority', ['standard', 'urgent', 'critical'])->default('urgent');
            $table->text('notes')->nullable();
            $table->enum('status', ['requested', 'dispatched', 'en_route', 'arrived', 'completed', 'cancelled'])->default('requested');
            $table->string('vehicle_code')->nullable();
            $table->string('driver_name')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulance_requests');
    }
};
