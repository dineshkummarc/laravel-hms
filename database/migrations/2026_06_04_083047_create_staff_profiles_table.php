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
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();

            // Link to the base User record
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Professional Details
            $table->string('designation')->nullable();
            $table->string('shift')->default('Day')->nullable();
            $table->date('joining_date')->nullable();

            // Operational metadata
            $table->string('employee_code')->unique()->nullable();
            $table->text('bio')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
