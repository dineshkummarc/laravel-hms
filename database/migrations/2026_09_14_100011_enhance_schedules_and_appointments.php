<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('doctor_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week')->default(1);
            $table->time('start_time')->default('09:00:00');
            $table->time('end_time')->default('17:00:00');
            $table->unsignedSmallInteger('slot_duration')->default(30);
            $table->boolean('is_available')->default(true);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->string('guest_name')->nullable()->after('patient_id');
            $table->string('guest_phone')->nullable()->after('guest_name');
            $table->text('reason')->nullable()->after('symptoms_summary');
            $table->foreignId('parent_appointment_id')->nullable()->after('department_id')->constrained('appointments')->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->change();
            $table->text('symptoms_summary')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('doctor_id');
            $table->dropColumn(['day_of_week', 'start_time', 'end_time', 'slot_duration', 'is_available']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_appointment_id');
            $table->dropColumn(['guest_name', 'guest_phone', 'reason']);
        });
    }
};
