<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('lab_reports', 'handed_over_at')) {
                $table->timestamp('handed_over_at')->nullable();
            }
            if (! Schema::hasColumn('lab_reports', 'handed_over_by')) {
                $table->foreignId('handed_over_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('lab_reports', function (Blueprint $table) {
            if (Schema::hasColumn('lab_reports', 'handed_over_by')) {
                $table->dropConstrainedForeignId('handed_over_by');
            }
            if (Schema::hasColumn('lab_reports', 'handed_over_at')) {
                $table->dropColumn('handed_over_at');
            }
        });
    }
};
