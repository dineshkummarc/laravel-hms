<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_units', function (Blueprint $table) {
            $table->id();
            $table->string('bag_number')->unique();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->enum('component', ['whole_blood', 'plasma', 'platelets', 'rbc'])->default('whole_blood');
            $table->unsignedInteger('units')->default(1);
            $table->string('donor_name')->nullable();
            $table->string('donor_phone')->nullable();
            $table->date('collected_on');
            $table->date('expires_on');
            $table->enum('status', ['available', 'reserved', 'issued', 'expired', 'discarded'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_units');
    }
};
