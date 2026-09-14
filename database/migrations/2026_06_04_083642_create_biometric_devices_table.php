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
        Schema::create('biometric_devices', function (Blueprint $table) {
            $table->id();
    $table->string('name'); // e.g., "ER Entrance Face Terminal"
    $table->string('serial_number')->unique(); // Hardware SN used to validate incoming webhooks
    $table->string('ip_address')->nullable();
    $table->string('location')->nullable(); // Department/Room
    $table->string('api_token')->unique()->nullable(); // Used for device-to-server authentication
    $table->boolean('is_online')->default(false);
    $table->dateTime('last_ping_at')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometric_devices');
    }
};
