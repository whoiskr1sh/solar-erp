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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_available_for_followup')->default(true)->after('is_active');
            $table->text('unavailability_reason')->nullable()->after('is_available_for_followup');
            $table->timestamp('unavailable_until')->nullable()->after('unavailability_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_available_for_followup', 'unavailability_reason', 'unavailable_until']);
        });
    }
};
